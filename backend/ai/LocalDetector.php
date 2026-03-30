<?php

require_once __DIR__ . '/common.php';

class LocalDiseaseDetector {
    public static function predict(string $imagePath): array {
        if (!ai_ensure_engine()) {
            return ['error' => 'Local AI model is not ready.'];
        }

        $data = ai_run_prediction($imagePath);
        return is_array($data) ? $data : ['error' => 'Invalid JSON response from local AI model'];
    }
}
