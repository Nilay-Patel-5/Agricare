<?php

function openai_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [
        'api_key' => getenv('OPENAI_API_KEY') ?: '',
        'model' => getenv('AGRICARE_OPENAI_MODEL') ?: 'gpt-5-mini',
        'base_url' => rtrim(getenv('OPENAI_BASE_URL') ?: 'https://api.openai.com/v1', '/'),
        'timeout' => (int) (getenv('AGRICARE_OPENAI_TIMEOUT') ?: 45),
        'verify_ssl' => true,
        'ca_info' => getenv('OPENAI_CA_INFO') ?: '',
    ];

    $localConfigFile = __DIR__ . '/openai.local.php';
    if (file_exists($localConfigFile)) {
        $localConfig = require $localConfigFile;
        if (is_array($localConfig)) {
            $config = array_merge($config, array_filter($localConfig, static fn($value) => $value !== null && $value !== ''));
        }
    }

    return $config;
}

function openai_resolve_ca_info(array $config): string
{
    if (!empty($config['ca_info']) && is_string($config['ca_info']) && file_exists($config['ca_info'])) {
        return $config['ca_info'];
    }

    $iniCandidates = [
        ini_get('curl.cainfo') ?: '',
        ini_get('openssl.cafile') ?: '',
    ];

    foreach ($iniCandidates as $candidate) {
        if (is_string($candidate) && $candidate !== '' && file_exists($candidate)) {
            return $candidate;
        }
    }

    $commonCandidates = [
        'C:\\Program Files\\PostgreSQL\\18\\pgAdmin 4\\python\\Lib\\site-packages\\certifi\\cacert.pem',
        'C:\\Program Files\\Python310\\Lib\\site-packages\\pip\\_vendor\\certifi\\cacert.pem',
        'C:\\Program Files\\PostgreSQL\\18\\pgAdmin 4\\python\\Lib\\site-packages\\pip\\_vendor\\certifi\\cacert.pem',
    ];

    foreach ($commonCandidates as $candidate) {
        if (file_exists($candidate)) {
            return $candidate;
        }
    }

    return '';
}

function openai_is_configured(): bool
{
    $config = openai_config();
    return $config['api_key'] !== '';
}

function openai_responses_create(array $payload): array
{
    $config = openai_config();

    if ($config['api_key'] === '') {
        return [
            'ok' => false,
            'status' => 503,
            'data' => null,
            'error' => 'Missing OPENAI_API_KEY configuration.',
        ];
    }

    $ch = curl_init($config['base_url'] . '/responses');
    $curlOptions = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $config['api_key'],
        ],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => $config['timeout'],
    ];

    $resolvedCaInfo = openai_resolve_ca_info($config);

    if (empty($config['verify_ssl'])) {
        $curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
        $curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
    } else {
        $curlOptions[CURLOPT_SSL_VERIFYPEER] = true;
        $curlOptions[CURLOPT_SSL_VERIFYHOST] = 2;
        if ($resolvedCaInfo !== '') {
            $curlOptions[CURLOPT_CAINFO] = $resolvedCaInfo;
        }
    }

    curl_setopt_array($ch, $curlOptions);

    $rawBody = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $error = curl_error($ch);

    if ($rawBody === false || $error !== '') {
        return [
            'ok' => false,
            'status' => 502,
            'data' => null,
            'error' => $error !== '' ? $error : 'OpenAI request failed.',
        ];
    }

    $data = json_decode($rawBody, true);
    if (!is_array($data)) {
        return [
            'ok' => false,
            'status' => $status ?: 502,
            'data' => null,
            'error' => 'Invalid JSON response from OpenAI.',
        ];
    }

    if ($status < 200 || $status >= 300) {
        $message = $data['error']['message'] ?? 'OpenAI request failed.';
        return [
            'ok' => false,
            'status' => $status,
            'data' => $data,
            'error' => $message,
        ];
    }

    return [
        'ok' => true,
        'status' => $status,
        'data' => $data,
        'error' => '',
    ];
}

function openai_extract_output_text(array $responseData): string
{
    if (!empty($responseData['output_text']) && is_string($responseData['output_text'])) {
        return trim($responseData['output_text']);
    }

    if (empty($responseData['output']) || !is_array($responseData['output'])) {
        return '';
    }

    $chunks = [];
    foreach ($responseData['output'] as $outputItem) {
        if (($outputItem['type'] ?? '') !== 'message' || empty($outputItem['content']) || !is_array($outputItem['content'])) {
            continue;
        }

        foreach ($outputItem['content'] as $contentItem) {
            $text = $contentItem['text'] ?? '';
            if (is_string($text) && $text !== '') {
                $chunks[] = trim($text);
            }
        }
    }

    return trim(implode("\n\n", array_filter($chunks)));
}
