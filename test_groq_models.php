<?php
require_once __DIR__ . '/backend/env.php';

$apiKey = getenv('GROQ_API_KEY');
$url = 'https://api.groq.com/openai/v1/models';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
if (isset($data['data'])) {
    foreach ($data['data'] as $model) {
        if (strpos($model['id'], 'vision') !== false || strpos($model['id'], 'llama-3.2-11b') !== false || strpos($model['id'], 'llama-3.2-90b') !== false) {
            echo "Found model: {$model['id']}\n";
        }
    }
} else {
    echo "Failed to list models: $response\n";
}
