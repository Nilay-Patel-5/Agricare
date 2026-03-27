<?php
require_once __DIR__ . '/grok_client.php';
$config = grok_config();

$ch = curl_init('https://api.x.ai/v1/models');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $config['api_key'],
    ],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0,
]);

$rawBody = curl_exec($ch);
file_put_contents(__DIR__ . '/models.json', $rawBody);
echo "Written to models.json\n";
