<?php

function crop_health_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [
        'api_key' => getenv('CROP_HEALTH_API_KEY') ?: '',
        'base_url' => getenv('CROP_HEALTH_API_URL') ?: 'https://crop.kindwise.com/api/v1/identification',
        'timeout' => 45,
        'language' => getenv('CROP_HEALTH_LANGUAGE') ?: 'en',
    ];

    return $config;
}

function crop_health_identify(string $filePath, string $mimeType): array
{
    $config = crop_health_config();
    if ($config['api_key'] === '') {
        return ['error' => 'Crop.health API key is missing.'];
    }

    $bytes = @file_get_contents($filePath);
    if ($bytes === false) {
        return ['error' => 'Could not read uploaded image for crop.health analysis.'];
    }

    $payload = [
        'images' => [base64_encode($bytes)],
    ];

    $query = http_build_query([
        'details' => 'description,treatment,symptoms,severity,common_names',
        'language' => $config['language'],
    ]);

    $ch = curl_init($config['base_url'] . '?' . $query);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Api-Key: ' . $config['api_key'],
            'Content-Type: application/json',
            'Accept: application/json',
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => $config['timeout'],
    ]);

    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $error = curl_error($ch);

    if ($response === false || $error !== '') {
        return ['error' => 'Crop.health request failed: ' . $error];
    }

    if ($status < 200 || $status >= 300) {
        return ['error' => 'Crop.health API failed with status ' . $status . ' (Response: ' . substr($response, 0, 200) . ')'];
    }

    $decoded = json_decode($response, true);
    if (!is_array($decoded)) {
        return ['error' => 'Invalid JSON response from crop.health'];
    }

    $cropSuggestion = $decoded['result']['crop']['suggestions'][0] ?? null;
    $diseaseSuggestion = $decoded['result']['disease']['suggestions'][0] ?? null;

    if (!is_array($diseaseSuggestion)) {
        return ['error' => 'Crop.health response did not include a disease suggestion.'];
    }

    $diseaseName = trim((string) (
        $diseaseSuggestion['common_name']
        ?? $diseaseSuggestion['name']
        ?? $diseaseSuggestion['scientific_name']
        ?? ''
    ));

    if ($diseaseName === '') {
        return ['error' => 'Crop.health response did not include a usable disease name.'];
    }

    $description = crop_health_extract_text($diseaseSuggestion['details']['description'] ?? null);
    $treatment = crop_health_extract_treatment($diseaseSuggestion['details']['treatment'] ?? null);
    $symptoms = crop_health_extract_text($diseaseSuggestion['details']['symptoms'] ?? null);
    $severity = crop_health_extract_text($diseaseSuggestion['details']['severity'] ?? null);

    $cropName = '';
    if (is_array($cropSuggestion)) {
        $cropName = trim((string) (
            $cropSuggestion['common_name']
            ?? $cropSuggestion['name']
            ?? $cropSuggestion['scientific_name']
            ?? ''
        ));
    }

    return [
        'label' => crop_health_normalize_label($diseaseName),
        'plant' => $cropName !== '' ? crop_health_normalize_label($cropName) : 'Detected',
        'confidence' => isset($diseaseSuggestion['probability']) ? round(((float) $diseaseSuggestion['probability']) * 100, 1) : 95.0,
        'description' => $description,
        'treatment' => $treatment,
        'symptoms' => $symptoms,
        'severity' => $severity,
        'raw' => $decoded,
    ];
}

function crop_health_extract_text($value): string
{
    if (is_string($value)) {
        return trim($value);
    }

    if (is_array($value)) {
        foreach (['value', 'text', 'description'] as $key) {
            if (isset($value[$key]) && is_string($value[$key]) && trim($value[$key]) !== '') {
                return trim($value[$key]);
            }
        }

        $parts = [];
        array_walk_recursive($value, static function ($item) use (&$parts): void {
            if (is_string($item) && trim($item) !== '') {
                $parts[] = trim($item);
            }
        });

        return $parts ? implode(' ', array_slice($parts, 0, 3)) : '';
    }

    return '';
}

function crop_health_extract_treatment($value): string
{
    if (is_string($value)) {
        return trim($value);
    }

    if (!is_array($value)) {
        return '';
    }

    $parts = [];
    foreach (['prevention', 'biological', 'chemical'] as $section) {
        if (!isset($value[$section])) {
            continue;
        }

        $text = crop_health_extract_text($value[$section]);
        if ($text !== '') {
            $parts[] = $text;
        }
    }

    if ($parts) {
        return implode(' ', $parts);
    }

    return crop_health_extract_text($value);
}

function crop_health_normalize_label(string $label): string
{
    $label = str_replace(['_', '-'], ' ', trim($label));
    $label = preg_replace('/\s+/', ' ', $label);
    return ucwords(strtolower((string) $label));
}
