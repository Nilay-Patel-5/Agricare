<?php
// Ignore deprecation warnings to prevent 500 errors in newer PHP versions
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', '0');

if (!headers_sent()) {
    header('Content-Type: application/json');
}

set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    // Ignore if error has been suppressed with an @ or is outside our reporting level
    if (!(error_reporting() & $severity)) {
        return false;
    }

    throw new ErrorException($message, 0, $severity, $file, $line);
});

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/ai/chat_context.php';
require_once __DIR__ . '/ai/gemini.php';
require_once __DIR__ . '/ai/groq.php';
require_once __DIR__ . '/ai/common.php';
require_once __DIR__ . '/ai/Detector.php';

function chat_json_input(): array
{
    $data = [];
    if (!empty($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw ?: '', true);
    } else {
        $data = $_POST;
    }
    return is_array($data) ? $data : [];
}

function chat_session_key_from_request(array $data): string
{
    $key = trim((string) ($data['session_key'] ?? ($_GET['session_key'] ?? '')));
    return $key !== '' ? substr($key, 0, 120) : 'guest';
}

function chat_detect_intents(string $message, bool $hasImage): array
{
    $text = mb_strtolower($message);

    $market = preg_match('/\b(market|mandi|price|prices|rate|rates|apmc|sell|selling|મંડી|ભાવ|मंडी)\b/u', $text) === 1;
    $subsidy = preg_match('/\b(subsidy|subsidies|scheme|schemes|loan|grant|benefit|benefits|irrigation|સબસિડી|યોજના|योजना)\b/u', $text) === 1;
    $schedule = preg_match('/\b(schedule|calendar|task|tasks|when|sow|plant|watering|fertilizer|spray|harvest|પત્રક|कैलेंडर)\b/u', $text) === 1;
    $pesticide = $hasImage || preg_match('/\b(pest|disease|pesticide|pesticides|insect|fungus|fungal|spray|treatment|જીવાત|રોગ|कीट)\b/u', $text) === 1;
    $shop = preg_match('/\b(shop|shops|store|stores|buy|purchase|dealer|agri-shop|agriculture shop|locate|near me|nearby|દુકાન|માર્કેટ|દુકાનો|दुकान)\b/u', $text) === 1;

    $general = !$market && !$subsidy && !$schedule && !$pesticide && !$shop;

    return [
        'market' => $market || $general,
        'subsidy' => $subsidy || $general,
        'schedule' => $schedule || $general,
        'pesticide' => $pesticide || $general,
        'shop' => $shop || $general,
    ];
}

function chat_trim_history_messages(array $history, int $maxChars = 600): array
{
    $trimmed = [];
    foreach ($history as $item) {
        $message = trim((string) ($item['message'] ?? ''));
        if ($message === '') {
            continue;
        }
        if (mb_strlen($message) > $maxChars) {
            $message = mb_substr($message, 0, $maxChars) . '...';
        }
        $trimmed[] = [
            'role' => ($item['role'] ?? '') === 'assistant' ? 'assistant' : 'user',
            'content' => $message,
        ];
    }
    return $trimmed;
}

try {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized. Please login.']);
        exit;
    }

    $sessionUserId = (int) $_SESSION['user_id'];
    $sessionRole = $_SESSION['user_role'] ?? 'farmer';

    $pdo = Database::getConnection();
    // Removed chat_ensure_schema($pdo) for performance. Run setup_chat.php manually if needed.

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $userId = isset($_GET['user_id']) && ctype_digit((string) $_GET['user_id']) ? (int) $_GET['user_id'] : $sessionUserId;
        
        // Authorization: Farmer cannot access other's chats
        if ($sessionRole === 'farmer' && $userId !== $sessionUserId) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        if (isset($_GET['sessions'])) {
            echo json_encode(['sessions' => chat_fetch_sessions($pdo, $userId)]);
            exit;
        }

        $sessionKey = chat_session_key_from_request([]);
        $history = chat_fetch_recent_history($pdo, $userId, $sessionKey, 50);
        echo json_encode(['messages' => $history]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $userId = isset($_GET['user_id']) && ctype_digit((string) $_GET['user_id']) ? (int) $_GET['user_id'] : $sessionUserId;
        
        if ($sessionRole === 'farmer' && $userId !== $sessionUserId) {
             http_response_code(403);
             echo json_encode(['error' => 'Forbidden']);
             exit;
        }

        $sessionKey = trim((string) ($_GET['session_key'] ?? ''));
        
        if ($sessionKey !== '') {
            $ok = chat_delete_session($pdo, $userId, $sessionKey);
            echo json_encode(['ok' => $ok]);
            exit;
        }
        
        http_response_code(400);
        echo json_encode(['error' => 'Session key required for delete']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

    $data = chat_json_input();
    $message = trim((string) ($data['message'] ?? ''));
    $userId = $sessionUserId; // Always use session ID
    $sessionKey = chat_session_key_from_request($data);
    $clientProfile = is_array($data['user'] ?? null) ? $data['user'] : [];

    if ($message === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Message is required.']);
        exit;
    }

    if (mb_strlen($message) > 2000) {
        http_response_code(400);
        echo json_encode(['error' => 'Message is too long.']);
        exit;
    }

    $profile = chat_load_user_profile($pdo, $userId, $clientProfile);
    $history = chat_fetch_recent_history($pdo, $userId, $sessionKey, 6);
    
    // Keyword-based override for better search relevance
    $searchDistrict = (string)($profile['district'] ?? '');
    $searchCrop = (string)($profile['crop'] ?? '');
    
    $districts = ['ahmedabad', 'surat', 'rajkot', 'vadodara', 'bhavnagar', 'amreli', 'junagadh', 'mehsana', 'anand', 'kutch', 'dahod', 'gandhinagar'];
    $normalizedMsg = mb_strtolower($message);
    foreach ($districts as $d) {
        if (strpos($normalizedMsg, $d) !== false) {
            $searchDistrict = $d;
            break;
        }
    }
    
    // Simple crop keywords
    $crops = ['wheat', 'cotton', 'groundnut', 'tomato', 'potato', 'onion', 'mango', 'banana', 'chilli'];
    foreach ($crops as $c) {
        if (strpos($normalizedMsg, $c) !== false) {
            $searchCrop = $c;
            break;
        }
    }
    
    // Subsidy specific keywords
    $subsidyKeyword = null;
    if (strpos($normalizedMsg, 'irrigation') !== false || strpos($normalizedMsg, 'drip') !== false) {
        $subsidyKeyword = 'irrigation';
    } elseif (strpos($normalizedMsg, 'tractor') !== false || strpos($normalizedMsg, 'machine') !== false) {
        $subsidyKeyword = 'mechanization';
    }

    $intents = chat_detect_intents($message, isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name']));
    
    $isTodayReq = (strpos($normalizedMsg, 'today') !== false || strpos($normalizedMsg, 'નવીનતમ') !== false || strpos($normalizedMsg, 'આજે') !== false);

    $marketRows = $intents['market'] ? chat_fetch_market_snapshot($pdo, $searchDistrict, $searchCrop) : [];
    
    // Prioritize today's data if specifically requested and latest isn't today
    if ($isTodayReq && $marketRows) {
        $todayStr = (new DateTime('now', new DateTimeZone('Asia/Kolkata')))->format('d/m/Y');
        usort($marketRows, function($a, $b) use ($todayStr) {
            $dateA = $a['arrival_date'] ?? '';
            $dateB = $b['arrival_date'] ?? '';
            if ($dateA === $todayStr && $dateB !== $todayStr) return -1;
            if ($dateA !== $todayStr && $dateB === $todayStr) return 1;
            return 0;
        });
    }

    $subsidyRows = $intents['subsidy'] ? chat_fetch_subsidy_snapshot($pdo, $subsidyKeyword ?: $searchCrop) : [];
    $cropSchedule = $intents['schedule'] ? chat_fetch_crop_schedule($pdo, $searchCrop) : [];
    $shopRows = $intents['shop'] ? chat_fetch_local_shops($pdo, $searchDistrict) : [];

    $identifiedPest = '';
    $pestResData = null; // structured data for frontend cards

    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        // Use the centralized AI Folder service for disease detection
        $mimeType = $_FILES['image']['type'] ?: 'image/jpeg';
        
        $diagResult = DiseaseDetector::identify($_FILES['image']['tmp_name'], $mimeType);
        
        if (!isset($diagResult['error'])) {
            $identifiedPest = $diagResult['label'] ?? 'Unknown';
        } else {
             file_put_contents(__DIR__ . '/vision_debug.log', "AI Folder Detection Error: " . $diagResult['error'] . "\n", FILE_APPEND);
             $identifiedPest = ''; 
        }
        
        file_put_contents(__DIR__ . '/vision_debug.log', "AI Decision: " . $identifiedPest . "\n", FILE_APPEND);
        
        if ($identifiedPest !== '' && strpos($identifiedPest, 'Unknown') === false && strpos(strtolower($identifiedPest), 'healthy') === false) {
            $commonName = chat_normalize_pest_name($identifiedPest);
            $pesticides = chat_fetch_pesticide_recommendations($pdo, $commonName);
            if (!$pesticides) {
                $pesticides = chat_fetch_pesticide_recommendations($pdo, $identifiedPest);
            }
            
            $shops = chat_fetch_local_shops($pdo, $profile['district']);
            
            // Enrich shops with google maps URL
            foreach ($shops as &$shop) {
                $addr = trim(($shop['address'] ?? '') . ', ' . ($shop['city'] ?? ''));
                $shop['map_url'] = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($addr);
            }
            
            $pestResData = [
                'pest_name' => $commonName ?: $identifiedPest,
                'pesticides' => $pesticides ?: [],
                'shops' => $shops ?: [],
            ];
        }
    }

    $contextBlock = chat_context_block($profile, $marketRows, $subsidyRows, $cropSchedule, $shopRows, $identifiedPest);

    $userMessageForStore = $message;
    if ($identifiedPest !== '') {
        $userMessageForStore = "[Sent a photo] " . $message;
    }
    chat_store_message($pdo, $userId, $sessionKey, 'user', $userMessageForStore, null);

    $langNames = [
        'en' => 'English',
        'hi' => 'Hindi',
        'gu' => 'Gujarati'
    ];
    $targetLang = $langNames[$profile['pref_lang']] ?? 'English';

    $pestSection = '';
    if ($identifiedPest !== '') {
        $pestSection = "\n\nIMPORTANT: A photo was analyzed. Pest Detected = \"{$identifiedPest}\". You MUST mention this.";
    }

    $conversation = [
        [
            'role' => 'system',
            'content' => "You are AgriBot, a farming assistant. Reply ONLY in $targetLang.
Rules: Be brief, practical, and action-oriented. Use the provided farm data first.{$pestSection}",
        ],
        [
            'role' => 'user',
            'content' => "FARM DATA:\n{$contextBlock}",
        ],
    ];

    $conversation = array_merge($conversation, chat_trim_history_messages($history));

    $conversation[] = [
        'role' => 'user',
        'content' => $message,
    ];

    $provider = 'gemini';
    $modelUsed = gemini_config()['text_model'] ?? 'gemini-1.5-flash';
    $response = gemini_text_create($conversation, $modelUsed);
    $reply = $response['ok'] ? gemini_extract_output_text($response['data']) : '';
    $providerErrors = [];

    if (!$response['ok']) {
        $providerErrors[] = 'Gemini: ' . ($response['error'] ?? 'request failed');
    } elseif ($reply === '') {
        $providerErrors[] = 'Gemini: empty reply';
    }

    if (!$response['ok'] || $reply === '') {
        $groqPayload = [
            'model' => groq_config()['model'] ?? 'llama-3.1-8b-instant',
            'messages' => $conversation,
            'temperature' => 0.3,
            'max_tokens' => 1024,
        ];
        $groqResponse = groq_chat_create($groqPayload);

        if ($groqResponse['ok']) {
            $groqReply = groq_extract_output_text($groqResponse['data']);
            if ($groqReply !== '') {
                $provider = 'groq';
                $modelUsed = $groqPayload['model'];
                $response = $groqResponse;
                $reply = $groqReply;
            } else {
                $providerErrors[] = 'Groq: empty reply';
            }
        } else {
            $providerErrors[] = 'Groq: ' . ($groqResponse['error'] ?? 'request failed');
        }
    }

    // FINAL FAIL-SAFE: If no AI replied, provide a hardcoded helpful response
    if ($reply === '') {
        $provider = 'failsafe';
        $modelUsed = 'hardcoded-agricultural-logic';
        
        $fallbacks = [
            'en' => [
                "I'm sorry, I'm having a bit of trouble connecting to my AI core right now. However, I've noted your interest in {$searchCrop} in {$searchDistrict}. Please try again in 30 seconds, or check the 'Mandi Prices' and 'Subsidies' sections in the menu for direct data.",
                "My apologies, our agricultural data servers are temporarily busy. If you're asking about {$searchCrop}, make sure to check for any local weather alerts or visit the nearest agri-shop listed in your dashboard.",
                "AgriBot is experiencing high traffic. While I wait for my connection to restore, rest assured that your previous data for {$searchDistrict} is safe. Please refresh and ask me again! 🌿"
            ],
            'gu' => [
                "ક્ષમા કરશો, અત્યારે સર્વર વ્યસ્ત છે. મેં {$searchDistrict} માં {$searchCrop} માટેની તમારી પૂછપરછ નોંધી લીધી છે. કૃપા કરીને થોડીવાર પછી પ્રયત્ન કરો અથવા મેનુમાં 'મંડી ભાવ' અને 'સબસીડી' વિભાગ તપાસો.",
                "દુઃખ સાથે જણાવવાનું કે અત્યારે ટેકનિકલ કારણોસર જવાબ આપી શકાતો નથી. જો તમે {$searchCrop} વિશે પૂછતા હોવ, તો કૃપા કરીને તમારા ડેશબોર્ડમાં નજીકની ખેતીવાડીની દુકાનનો સંપર્ક કરો.",
                "AgriBot અત્યારે ટ્રાફિકને કારણે ધીમું છે. તમારી {$searchDistrict} થી લગતી જૂની વિગતો સુરક્ષિત છે. મહેરબાની કરીને પેજ રિફ્રેશ કરો અને ફરી પૂછો! 🌿"
            ],
            'hi' => [
                "क्षमा करें, वर्तमान में सर्वर व्यस्त है। मैंने {$searchDistrict} में {$searchCrop} के लिए आपकी पूछताछ नोट कर ली है। कृपया 30 सेकंड बाद पुनः प्रयास करें या 'मंडी भाव' और 'सब्सिडी' अनुभाग देखें।",
                "असुविधा के लिए खेद है, कृषि डेटा सर्वर अभी व्यस्त हैं। यदि आप {$searchCrop} के बारे में पूछ रहे हैं, तो कृपया अपने डैशबोर्ड में नजदीकी कृषि दुकान से संपर्क करें।",
                "AgriBot अभी बिजी है। आपकी {$searchDistrict} से संबंधित जानकारी सुरक्षित है। कृपया पेज रिफ्रेश करें और फिर से पूछें! 🌿"
            ]
        ];

        $lang = $profile['pref_lang'] ?? 'en';
        $msgs = $fallbacks[$lang] ?? $fallbacks['en'];
        $reply = $msgs[array_rand($msgs)];
    }

    chat_store_message($pdo, $userId, $sessionKey, 'assistant', $reply, $modelUsed);

    $result = [
        'reply' => $reply,
        'model' => $modelUsed,
        'provider' => $provider,
    ];
    
    if ($pestResData !== null) {
        $result['pest_result'] = $pestResData;
    }

    echo json_encode($result);
} catch (Throwable $e) {
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    if (!headers_sent()) {
        header('Content-Type: application/json');
    }

    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
} finally {
    restore_error_handler();
}
