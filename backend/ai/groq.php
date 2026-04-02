<?php

function groq_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [
        'api_key' => getenv('GROQ_API_KEY') ?: '',
        'base_url' => getenv('GROQ_BASE_URL') ?: 'https://api.groq.com/openai/v1',
        'model' => getenv('GROQ_MODEL') ?: 'llama-3.1-8b-instant',
        'timeout' => (int) (getenv('GROQ_TIMEOUT') ?: 60),
    ];

    foreach ([__DIR__ . '/groq.local.php'] as $localConfigFile) {
        if (file_exists($localConfigFile)) {
            $localConfig = require $localConfigFile;
            if (is_array($localConfig)) {
                $config = array_merge($config, array_filter($localConfig, static fn($value) => $value !== null && $value !== ''));
            }
        }
    }

    return $config;
}

function groq_chat_create(array $payload): array
{
    $config = groq_config();
    if (empty($config['api_key'])) {
        return [
            'ok' => false,
            'status' => 401,
            'data' => null,
            'error' => 'Groq API key is not configured. Please add it to groq.local.php or set sensations GROQ_API_KEY.',
        ];
    }

    $ch = curl_init($config['base_url'] . '/chat/completions');

    if (!isset($payload['model'])) {
        $payload['model'] = $config['model'];
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $config['api_key'],
        ],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => $config['timeout'],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
    ]);

    $rawBody = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $error = curl_error($ch);

    if ($rawBody === false || $error !== '') {
        return [
            'ok' => false,
            'status' => 502,
            'data' => null,
            'error' => $error !== '' ? $error : 'Groq request failed.',
        ];
    }

    $data = json_decode($rawBody, true);
    if (!is_array($data)) {
        return [
            'ok' => false,
            'status' => $status ?: 502,
            'data' => null,
            'error' => 'Invalid JSON response from Groq API.',
        ];
    }

    if ($status < 200 || $status >= 300) {
        $message = $data['error']['message'] ?? ($data['error'] ?? 'Groq request failed.');
        return [
            'ok' => false,
            'status' => $status,
            'data' => $data,
            'error' => is_string($message) ? $message : 'Groq request failed.',
        ];
    }

    return [
        'ok' => true,
        'status' => $status,
        'data' => $data,
        'error' => '',
    ];
}

function groq_extract_output_text(array $responseData): string
{
    $message = $responseData['choices'][0]['message']['content'] ?? '';
    return is_string($message) ? trim($message) : '';
}
