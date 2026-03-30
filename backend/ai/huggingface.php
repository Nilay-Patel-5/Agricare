<?php

function huggingface_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [
        'api_key' => getenv('HUGGINGFACE_API_KEY') ?: '',
        'model' => 'linkanjarad/mobilenet_v2_plant_disease',
        'timeout' => 45,
    ];

    return $config;
}

function huggingface_analyze_image(string $filePath, string $mimeType): string
{
    $config = huggingface_config();
    if (empty($config['api_key'])) {
        return 'Error: HuggingFace API key is missing.';
    }

    $url = 'https://api-inference.huggingface.co/models/' . $config['model'];
    $data = file_get_contents($filePath);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $config['api_key'],
            'Content-Type: application/octet-stream',
            'Accept: application/json'
        ],
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => $config['timeout'],
    ]);

    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $error = curl_error($ch);

    if ($response === false || $error !== '') {
        return 'Error: HuggingFace request failed: ' . $error;
    }

    if ($status === 503) {
        return 'Error: HuggingFace model is currently loading on the server. Please try again in 20 seconds.';
    }

    if ($status < 200 || $status >= 300) {
        return 'Error: HuggingFace API failed with status ' . $status;
    }

    $decoded = json_decode($response, true);
    if (!is_array($decoded) || empty($decoded)) {
        return 'Error: Invalid JSON response from HuggingFace';
    }

    $bestLabel = null;
    $bestScore = -1;

    foreach ($decoded as $item) {
        if (isset($item['label'], $item['score']) && $item['score'] > $bestScore) {
            $bestScore = $item['score'];
            $bestLabel = $item['label'];
        }
    }

    if (!$bestLabel) {
        return 'Error: Could not extract label from HuggingFace response';
    }

    $cleanLabel = $bestLabel;
    if (strpos($cleanLabel, '___') !== false) {
        $parts = explode('___', $cleanLabel);
        $cleanLabel = end($parts);
    }
    if (strpos($cleanLabel, '__') !== false) {
        $parts = explode('__', $cleanLabel);
        $cleanLabel = end($parts);
    }
    
    $cleanLabel = str_replace(['_', '-'], ' ', $cleanLabel);
    $cleanLabel = ucwords(strtolower(trim($cleanLabel)));

    if (stripos($cleanLabel, 'healthy') !== false) {
        return 'Healthy';
    }

    return $cleanLabel;
}
