<?php

function gemini_config(): array
{
    $config = [
        'api_key' => getenv('GEMINI_API_KEY') ?: '',
        'text_model' => getenv('GEMINI_TEXT_MODEL') ?: 'gemini-1.5-flash',
        'vision_model' => getenv('GEMINI_VISION_MODEL') ?: 'gemini-1.5-flash',
        'timeout' => (int) (getenv('GEMINI_TIMEOUT') ?: 60),
    ];
    $localConfigFile = __DIR__ . '/gemini.local.php';
    if (file_exists($localConfigFile)) {
        $localConfig = require $localConfigFile;
        if (is_array($localConfig)) {
            $config = array_merge($config, $localConfig);
        }
    }
    return $config;
}

function gemini_get_api_keys(array $config): array
{
    $keys = $config['api_key'] ?? '';
    $keyArray = [];
    if (is_string($keys)) {
        if (strpos($keys, ',') !== false) {
            $keyArray = array_filter(array_map('trim', explode(',', $keys)));
        } else {
            $keyArray = [trim($keys)];
        }
    } elseif (is_array($keys)) {
        $keyArray = $keys;
    }
    if (!empty($keyArray)) {
        shuffle($keyArray);
    }
    return $keyArray;
}

function gemini_text_create(array $messages, ?string $model = null): array
{
    $config = gemini_config();
    $apiKeys = gemini_get_api_keys($config);

    if (empty($apiKeys)) {
        return [
            'ok' => false,
            'status' => 401,
            'data' => null,
            'error' => 'Gemini API key is not configured.',
        ];
    }

    $contents = [];
    $systemInstruction = null;

    foreach ($messages as $message) {
        $role = (string) ($message['role'] ?? 'user');
        $content = trim((string) ($message['content'] ?? ''));
        if ($content === '') continue;

        if ($role === 'system') {
            $systemInstruction = ['parts' => [['text' => $content]]];
            continue;
        }

        $contents[] = [
            'role' => $role === 'assistant' ? 'model' : 'user',
            'parts' => [['text' => $content]],
        ];
    }

    $payload = [
        'contents' => $contents,
        'generationConfig' => [
            'temperature' => 0.3,
            'maxOutputTokens' => 1024,
        ],
    ];

    if ($systemInstruction !== null) {
        $payload['systemInstruction'] = $systemInstruction;
    }

    $targetModel = $model ?: ($config['text_model'] ?? 'gemini-1.5-flash');
    $timeout = (int) ($config['timeout'] ?? 60);
    $lastResult = null;

    $fallbackModels = array_unique(array_filter([
        $targetModel,
        'gemini-2.5-flash',
        'gemini-2.0-flash',
        'gemini-flash-latest',
        'gemini-2.0-flash-lite'
    ]));

    foreach ($apiKeys as $apiKey) {
        if ($apiKey === 'YOUR_GEMINI_API_KEY_HERE') continue;

        foreach ($fallbackModels as $currentModel) {
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . rawurlencode($currentModel) . ':generateContent?key=' . $apiKey;

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_TIMEOUT => $timeout,
            ]);

            $response = curl_exec($ch);
            $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($response === false || $error !== '') {
                $lastResult = [
                    'ok' => false,
                    'status' => 502,
                    'data' => null,
                    'error' => $error !== '' ? $error : 'Gemini request failed.',
                ];
                continue; // try next model
            }

            $data = json_decode($response, true);
            if (!is_array($data)) {
                $lastResult = [
                    'ok' => false,
                    'status' => $status ?: 502,
                    'data' => null,
                    'error' => 'Invalid JSON response from Gemini API.',
                ];
                continue; // try next model
            }

            if ($status === 429) {
                // Rate limit hit. Could be burst (2 RPS) or per-model limit (15 RPM). 
                // We sleep 1 second to avoid hitting simple burst limits simultaneously, then try another model/key
                sleep(1);
                $lastResult = [
                    'ok' => false,
                    'status' => 429,
                    'data' => $data,
                    'error' => 'Rate limit hit',
                ];
                continue; // try next model in fallback list!
            }

            if ($status < 200 || $status >= 300) {
                $message = $data['error']['message'] ?? 'Gemini request failed.';
                $lastResult = [
                    'ok' => false,
                    'status' => $status,
                    'data' => $data,
                    'error' => is_string($message) ? $message : 'Gemini request failed.',
                ];
                if (stripos($message, 'expired') !== false || stripos($message, 'invalid') !== false) {
                    continue 2; // Jump to next API Key entirely if key is dead
                }
                continue; // try next model
            }

            // Success
            return [
                'ok' => true,
                'status' => $status,
                'data' => $data,
                'error' => '',
            ];
        }
    }

    // If all keys and models failed
    $errorMsg = $lastResult['error'] ?? 'All Gemini API keys failed or were rate-limited.';
    if (($lastResult['status'] ?? 0) === 429) {
        $errorMsg = 'All AI keys and model fallbacks are currently rate-limited. Please wait 1 minute and try again.';
    } elseif (stripos($errorMsg, 'expired') !== false || stripos($errorMsg, 'invalid') !== false) {
        $errorMsg = 'CRITICAL: EVERY SINGLE API key you provided is either EXPIRED or INVALID in Google\'s system. Please generate brand new keys from Google AI Studio.';
    }

    return [
        'ok' => false,
        'status' => $lastResult['status'] ?? 500,
        'data' => $lastResult['data'] ?? null,
        'error' => $errorMsg,
    ];
}

function gemini_extract_output_text(array $responseData): string
{
    $parts = $responseData['candidates'][0]['content']['parts'] ?? [];
    $text = '';
    foreach ($parts as $part) {
        if (isset($part['text']) && is_string($part['text'])) {
            $text .= $part['text'];
        }
    }
    return trim($text);
}

function gemini_call_vision(string $apiKey, string $model, array $payload, int $timeout): array
{
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . rawurlencode($model) . ':generateContent?key=' . $apiKey;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_TIMEOUT => $timeout,
    ]);
    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    return ['status' => $status, 'body' => $response];
}

function gemini_analyze_image(string $filePath, string $mimeType): string
{
    $config = gemini_config();
    $apiKeys = gemini_get_api_keys($config);

    if (empty($apiKeys)) {
        return 'Error: Gemini API key is missing. Please configure gemini.local.php.';
    }

    $base64Data = base64_encode(file_get_contents($filePath));

    $payload = [
        'contents' => [
            [
                'parts' => [
                    ['text' => "Analyze this image and identify the plant disease or pest. Reply ONLY with the exact common name of the disease or pest (e.g., 'Early Blight', 'Aphids', 'Powdery Mildew'). Do NOT include full sentences. If it is a healthy plant, reply 'Healthy'. If you don't know, reply 'Unknown'."],
                    [
                        'inlineData' => [
                            'mimeType' => $mimeType,
                            'data' => $base64Data
                        ]
                    ]
                ]
            ]
        ],
        'safetySettings' => [
            ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
            ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
            ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
            ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE']
        ],
        'generationConfig' => [
            'temperature' => 0.1,
            'maxOutputTokens' => 512,
        ]
    ];

    $primaryModel = $config['vision_model'] ?? 'gemini-2.5-flash';
    $fallbackModels = array_unique(array_filter([
        $primaryModel,
        'gemini-2.0-flash',
        'gemini-flash-latest',
        'gemini-2.0-flash-lite'
    ]));

    $timeout = (int) ($config['timeout'] ?? 60);
    $lastError = '';

    foreach ($apiKeys as $apiKey) {
        if ($apiKey === 'YOUR_GEMINI_API_KEY_HERE') continue;

        foreach ($fallbackModels as $model) {
            $result = gemini_call_vision($apiKey, $model, $payload, $timeout);
            $status = $result['status'];
            $response = $result['body'];

            if ($status === 429) {
                file_put_contents(__DIR__ . '/gemini_error.log', "Rate limit hit (429) on {$model} with key. Trying next key...\n", FILE_APPEND);
                $lastError = '429';
                continue 2; // Jump to the next API key instead of just next model
            }

            if ($status !== 200 || !$response) {
                $lastError = 'Error: Gemini API request failed (Code ' . $status . ') on model ' . $model;
                continue; // try next model
            }

            $data = json_decode($response, true);
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $finishReason = $data['candidates'][0]['finishReason'] ?? 'Unknown';
                file_put_contents(__DIR__ . '/gemini_error.log', "Blocked on {$model} (Reason: {$finishReason}): " . $response . "\n", FILE_APPEND);
                $lastError = "Error: Gemini model blocked response (Reason: {$finishReason})";
                continue; // try next model
            }

            $identifiedContent = trim($data['candidates'][0]['content']['parts'][0]['text']);
            if (strpos($identifiedContent, '</think>') !== false) {
                $textParts = explode('</think>', $identifiedContent);
                $identifiedContent = trim(end($textParts));
            }

            return trim(str_replace(['"', "'"], '', $identifiedContent), " .\t\n\r\0\x0B");
        }
    }

    if ($lastError === '429') {
        return 'Error: All configured API keys are rate-limited. Please wait 60 seconds and try again.';
    }
    if (stripos($lastError, 'expired') !== false || stripos($lastError, 'invalid') !== false) {
        return 'CRITICAL ERROR: All of your configured API keys are expired or strictly invalid. You must generate brand new keys from Google AI Studio.';
    }
    return $lastError ?: 'Error: Gemini API request failed.';
}

