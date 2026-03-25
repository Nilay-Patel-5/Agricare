<?php

function gemini_config(): array
{
    $config = [
        'api_key' => getenv('GEMINI_API_KEY') ?: '',
        'text_model' => getenv('GEMINI_TEXT_MODEL') ?: 'gemini-2.5-flash',
        'vision_model' => getenv('GEMINI_VISION_MODEL') ?: 'gemini-2.5-flash',
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

function gemini_text_create(array $messages, ?string $model = null): array
{
    $config = gemini_config();
    if (empty($config['api_key']) || $config['api_key'] === 'YOUR_GEMINI_API_KEY_HERE') {
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
        if ($content === '') {
            continue;
        }

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

    $targetModel = $model ?: ($config['text_model'] ?? 'gemini-2.5-flash');
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . rawurlencode($targetModel) . ':generateContent?key=' . $config['api_key'];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_TIMEOUT => (int) ($config['timeout'] ?? 60),
    ]);

    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $error = curl_error($ch);

    if ($response === false || $error !== '') {
        return [
            'ok' => false,
            'status' => 502,
            'data' => null,
            'error' => $error !== '' ? $error : 'Gemini request failed.',
        ];
    }

    $data = json_decode($response, true);
    if (!is_array($data)) {
        return [
            'ok' => false,
            'status' => $status ?: 502,
            'data' => null,
            'error' => 'Invalid JSON response from Gemini API.',
        ];
    }

    if ($status < 200 || $status >= 300) {
        $message = $data['error']['message'] ?? 'Gemini request failed.';
        return [
            'ok' => false,
            'status' => $status,
            'data' => $data,
            'error' => is_string($message) ? $message : 'Gemini request failed.',
        ];
    }

    return [
        'ok' => true,
        'status' => $status,
        'data' => $data,
        'error' => '',
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

function gemini_analyze_image(string $filePath, string $mimeType): string
{
    $config = gemini_config();
    if (empty($config['api_key']) || $config['api_key'] === 'YOUR_GEMINI_API_KEY_HERE') {
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
            'maxOutputTokens' => 2048,
        ]
    ];

    $visionModel = $config['vision_model'] ?? 'gemini-2.5-flash';
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . rawurlencode($visionModel) . ':generateContent?key=' . $config['api_key'];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_TIMEOUT => (int) ($config['timeout'] ?? 60),
    ]);

    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

    if ($status !== 200 || !$response) {
        if ($status === 429) {
            file_put_contents(__DIR__ . '/gemini_error.log', "Rate limit hit (429): " . $response);
            return 'Error: AI Free Tier limit reached. Please wait 60 seconds and try again.';
        }
        return 'Error: Gemini API request failed (Code ' . $status . ')';
    }

    $data = json_decode($response, true);
    if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        $finishReason = $data['candidates'][0]['finishReason'] ?? 'Unknown';
        file_put_contents(__DIR__ . '/gemini_error.log', "Raw response: " . $response);
        return "Error: Gemini model blocked response (Reason: {$finishReason})";
    }

    $identifiedContent = trim($data['candidates'][0]['content']['parts'][0]['text']);
    if (strpos($identifiedContent, '</think>') !== false) {
        $textParts = explode('</think>', $identifiedContent);
        $identifiedContent = trim(end($textParts));
    }
    
    return trim(str_replace(['"', '\''], '', $identifiedContent), " .\t\n\r\0\x0B");
}
