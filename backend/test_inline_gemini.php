<?php
require_once __DIR__ . '/gemini_client.php';
$config = gemini_config();

$base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=';
$payload = [
    'contents' => [
        [
            'parts' => [
                ['text' => "What color is this image? Reply short"],
                [
                    'inlineData' => [
                        'mimeType' => 'image/png',
                        'data' => $base64
                    ]
                ]
            ]
        ]
    ]
];

$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $config['api_key'];
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0
]);
$response = curl_exec($ch);
echo "RESPONSE FROM REST API:\n$response\n";
