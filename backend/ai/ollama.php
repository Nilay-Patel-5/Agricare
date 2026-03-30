<?php

function ollama_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [
        'base_url' => rtrim(getenv('AGRICARE_OLLAMA_URL') ?: 'http://127.0.0.1:11434', '/'),
        'model' => getenv('AGRICARE_OLLAMA_MODEL') ?: 'qwen2.5:0.5b',
        'timeout' => (int) (getenv('AGRICARE_OLLAMA_TIMEOUT') ?: 90),
    ];

    $localConfigFile = __DIR__ . '/ollama.local.php';
    if (file_exists($localConfigFile)) {
        $localConfig = require $localConfigFile;
        if (is_array($localConfig)) {
            $config = array_merge($config, array_filter($localConfig, static fn($value) => $value !== null && $value !== ''));
        }
    }

    return $config;
}

function ollama_chat_create(array $payload): array
{
    $config = ollama_config();
    $ch = curl_init($config['base_url'] . '/api/chat');

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => $config['timeout'],
    ]);

    $rawBody = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $error = curl_error($ch);

    if ($rawBody === false || $error !== '') {
        return [
            'ok' => false,
            'status' => 502,
            'data' => null,
            'error' => $error !== '' ? $error : 'Ollama request failed.',
        ];
    }

    $data = json_decode($rawBody, true);
    if (!is_array($data)) {
        return [
            'ok' => false,
            'status' => $status ?: 502,
            'data' => null,
            'error' => 'Invalid JSON response from Ollama.',
        ];
    }

    if ($status < 200 || $status >= 300) {
        $message = $data['error'] ?? 'Ollama request failed.';
        return [
            'ok' => false,
            'status' => $status,
            'data' => $data,
            'error' => is_string($message) ? $message : 'Ollama request failed.',
        ];
    }

    return [
        'ok' => true,
        'status' => $status,
        'data' => $data,
        'error' => '',
    ];
}

function ollama_extract_output_text(array $responseData): string
{
    $message = $responseData['message']['content'] ?? '';
    return is_string($message) ? trim($message) : '';
}
