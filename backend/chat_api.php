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

    $market = preg_match('/\b(market|mandi|price|prices|rate|rates|apmc|sell|selling)\b/u', $text) === 1;
    $subsidy = preg_match('/\b(subsidy|subsidies|scheme|schemes|loan|grant|benefit|benefits|irrigation)\b/u', $text) === 1;
    $schedule = preg_match('/\b(schedule|calendar|task|tasks|when|sow|plant|watering|fertilizer|spray|harvest)\b/u', $text) === 1;
    $pesticide = $hasImage || preg_match('/\b(pest|disease|pesticide|pesticides|insect|fungus|fungal|spray|treatment)\b/u', $text) === 1;

    $general = !$market && !$subsidy && !$schedule && !$pesticide;

    return [
        'market' => $market || $general,
        'subsidy' => $subsidy || $general,
        'schedule' => $schedule || $general,
        'pesticide' => $pesticide || $general,
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
    $pdo = Database::getConnection();
    // Removed chat_ensure_schema($pdo) for performance. Run setup_chat.php manually if needed.

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $userId = isset($_GET['user_id']) && ctype_digit((string) $_GET['user_id']) ? (int) $_GET['user_id'] : null;
        
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
        $userId = isset($_GET['user_id']) && ctype_digit((string) $_GET['user_id']) ? (int) $_GET['user_id'] : null;
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
    $userId = isset($data['user']['id']) ? (int) $data['user']['id'] : null;
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
    $intents = chat_detect_intents($message, isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name']));
    $marketRows = $intents['market'] ? chat_fetch_market_snapshot($pdo, $profile['district'], $profile['crop']) : [];
    $subsidyRows = $intents['subsidy'] ? chat_fetch_subsidy_snapshot($pdo, $profile['crop']) : [];
    $cropSchedule = $intents['schedule'] ? chat_fetch_crop_schedule($pdo, $profile['crop']) : [];

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

    $contextBlock = chat_context_block($profile, $marketRows, $subsidyRows, $cropSchedule, $identifiedPest);

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

    if (!$response['ok']) {
        http_response_code($response['status'] ?: 502);
        echo json_encode([
            'error' => $response['error'] ?? 'Gemini API failed or hit rate limits.',
            'configured' => true,
        ]);
        exit;
    }

    $reply = gemini_extract_output_text($response['data']);

    if ($reply === '') {
        http_response_code(502);
        echo json_encode(['error' => 'AI provider returned an empty reply.']);
        exit;
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
