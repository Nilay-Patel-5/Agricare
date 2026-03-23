<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');

if (!headers_sent()) {
    header('Content-Type: application/json');
}

set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false;
    }

    throw new ErrorException($message, 0, $severity, $file, $line);
});

require_once __DIR__ . '/chat_context.php';
require_once __DIR__ . '/groq_client.php';
require_once __DIR__ . '/gemini_client.php';
require_once __DIR__ . '/ai_common.php';

function chat_json_input(): array
{
    $data = [];
    if ($_SERVER['CONTENT_TYPE'] && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
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
    $history = chat_fetch_recent_history($pdo, $userId, $sessionKey, 8);
    $marketRows = chat_fetch_market_snapshot($pdo, $profile['district'], $profile['crop']);
    $subsidyRows = chat_fetch_subsidy_snapshot($pdo, $profile['crop']);
    $cropSchedule = chat_fetch_crop_schedule($pdo, $profile['crop']);

    $identifiedPest = '';
    $pestResData = null; // structured data for frontend cards

    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        // Use Google Gemini Vision (Free Tier) to identify crop diseases instantly
        $mimeType = $_FILES['image']['type'] ?: 'image/jpeg';
        
        $identifiedPest = gemini_analyze_image($_FILES['image']['tmp_name'], $mimeType);
        
        file_put_contents(__DIR__ . '/vision_debug.log', "Gemini Identified: " . $identifiedPest . "\n", FILE_APPEND);
        
        if ($identifiedPest !== '' && strpos($identifiedPest, 'Error:') === false && strpos($identifiedPest, 'Unknown') === false) {
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
Rules: DO NOT say 'I cannot help'. Be brief and helpful.{$pestSection}",
        ],
        [
            'role' => 'user',
            'content' => "FARM DATA:\n{$contextBlock}",
        ],
        [
            'role' => 'assistant',
            'content' => "Understood. Farm data loaded.",
        ],
    ];

    foreach ($history as $item) {
        $role = ($item['role'] ?? '') === 'assistant' ? 'assistant' : 'user';
        $conversation[] = [
            'role' => $role,
            'content' => (string) ($item['message'] ?? ''),
        ];
    }

    $conversation[] = [
        'role' => 'user',
        'content' => $message,
    ];

    $config = groq_config();
    $response = groq_chat_create([
        'model' => $config['model'],
        'stream' => false,
        'messages' => $conversation,
    ]);

    if (!$response['ok']) {
        http_response_code($response['status'] ?: 502);
        echo json_encode([
            'error' => $response['error'],
            'configured' => true,
        ]);
        exit;
    }

    $reply = groq_extract_output_text($response['data']);
    if ($reply === '') {
        http_response_code(502);
        echo json_encode(['error' => 'Groq returned an empty reply.']);
        exit;
    }

    chat_store_message($pdo, $userId, $sessionKey, 'assistant', $reply, $config['model']);

    $result = [
        'reply' => $reply,
        'model' => $config['model'],
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
