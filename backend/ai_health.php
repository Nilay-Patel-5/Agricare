<?php
require_once __DIR__ . '/ai/common.php';

header('Content-Type: application/json');

if (!ai_is_healthy()) {
    http_response_code(503);
    echo json_encode([
        'status' => 'offline',
        'version' => 'Local Plant Disease Model',
        'engine' => 'Python CLI Inference',
        'details' => 'Model file, prediction script, or Python runtime is missing.'
    ]);
    exit;
}

http_response_code(200);
echo json_encode([
    'status' => 'online',
    'version' => 'Local Plant Disease Model',
    'uptime' => 'ready',
    'engine' => 'Python CLI Inference'
]);
