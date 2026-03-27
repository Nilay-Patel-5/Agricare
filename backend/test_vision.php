<?php
require_once __DIR__ . '/groq_client.php';
$config = groq_config();
$ch = curl_init('https://api.groq.com/openai/v1/models');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $config['api_key']],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0,
]);
file_put_contents('groq_models.json', curl_exec($ch));
echo "Models saved.";
