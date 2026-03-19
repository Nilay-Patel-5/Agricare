<?php
header('Content-Type: application/json');

require_once __DIR__ . '/ai_common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['image']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No image uploaded']);
    exit;
}

if (!ai_ensure_engine()) {
    http_response_code(503);
    echo json_encode(['error' => 'AI engine unavailable']);
    exit;
}

$image = new CURLFile(
    $_FILES['image']['tmp_name'],
    $_FILES['image']['type'] ?: 'application/octet-stream',
    $_FILES['image']['name'] ?: 'upload.jpg'
);

$response = ai_request('POST', '/predict', [
    'connect_timeout' => 3,
    'timeout' => 60,
    'body' => ['image' => $image],
]);

if (!$response['ok']) {
    http_response_code(502);
    echo json_encode(['error' => $response['error'] ?: 'AI prediction request failed']);
    exit;
}

$status = $response['status'] ?: 500;
http_response_code($status);
echo $response['body'];
