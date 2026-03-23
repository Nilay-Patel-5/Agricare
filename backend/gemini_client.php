<?php

function gemini_config(): array
{
    $config = ['api_key' => getenv('GEMINI_API_KEY') ?: ''];
    $localConfigFile = __DIR__ . '/gemini.local.php';
    if (file_exists($localConfigFile)) {
        $localConfig = require $localConfigFile;
        if (is_array($localConfig)) {
            $config = array_merge($config, $localConfig);
        }
    }
    return $config;
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

    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $config['api_key'];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_TIMEOUT => 60,
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
