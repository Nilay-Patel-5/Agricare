<?php
header('Content-Type: application/json');

require_once __DIR__ . '/ai_common.php';

if (!ai_ensure_engine()) {
    http_response_code(503);
    echo json_encode([
        'status' => 'offline',
        'error' => 'AI engine unavailable',
    ]);
    exit;
}

$response = ai_request('GET', '/health', [
    'connect_timeout' => 2,
    'timeout' => 5,
]);

if (!$response['ok'] || $response['status'] < 200 || $response['status'] >= 300) {
    http_response_code(502);
    echo json_encode([
        'status' => 'offline',
        'error' => $response['error'] ?: 'AI health check failed',
    ]);
    exit;
}

http_response_code(200);
echo $response['body'];
