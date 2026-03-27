<?php
header('Content-Type: application/json');

// Gemini is cloud based and always online.
http_response_code(200);
echo json_encode([
    'status' => 'online',
    'version' => 'Gemini 2.5 Flash',
    'uptime' => '100%',
    'engine' => 'Google Generative AI'
]);
