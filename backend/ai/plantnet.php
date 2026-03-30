<?php

function plantnet_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [
        'api_key' => getenv('PLANTNET_API_KEY') ?: '',
        'base_url' => 'https://my-api.plantnet.org/v2/identify/all',
        'timeout' => 45,
    ];

    return $config;
}

function plantnet_analyze_image(string $filePath, string $mimeType): string
{
    $config = plantnet_config();
    if (empty($config['api_key'])) {
        return 'Error: PlantNet API key is missing.';
    }

    $ch = curl_init($config['base_url'] . '?api-key=' . $config['api_key']);
    
    // PlantNet requires images as multipart/form-data
    $cfile = new CURLFile($filePath, $mimeType, 'images');
    
    $payload = [
        'images' => $cfile,
        'organs' => 'leaf' // Default to leaf for disease scans
    ];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => $config['timeout'],
    ]);

    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $error = curl_error($ch);

    if ($response === false || $error !== '') {
        return 'Error: PlantNet request failed: ' . $error;
    }

    if ($status < 200 || $status >= 300) {
        return 'Error: PlantNet API failed with status ' . $status . ' (Response: ' . substr($response, 0, 100) . ')';
    }

    $decoded = json_decode($response, true);
    if (!isset($decoded['results'][0]['species']['commonNames'][0])) {
         // Fallback to scientific name if common name missing
         $sciName = $decoded['results'][0]['species']['scientificNameWithoutAuthor'] ?? null;
         if ($sciName) {
             return ucwords($sciName);
         }
         return 'Error: Could not identify plant with PlantNet';
    }

    return ucwords($decoded['results'][0]['species']['commonNames'][0]);
}
