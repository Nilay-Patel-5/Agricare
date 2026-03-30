<?php

require_once __DIR__ . '/common.php';

class DiseaseDetector {
    /**
     * Identifies plant disease using the local AI service.
     * 
     * @param string $imagePath Path to the uploaded image.
     * @param string $mimeType MIME type of the image.
     * @return array Identification data (label, plant, confidence, info).
     */
    public static function identify(string $imagePath, string $mimeType = 'image/jpeg'): array {
        if (!ai_ensure_engine()) {
            return ['error' => 'Local AI model is not ready. Check the model file and Python setup in the ai folder.'];
        }

        $data = ai_run_prediction($imagePath);
        if (!$data || isset($data['error'])) {
            return ['error' => $data['error'] ?? 'Invalid response from local AI inference'];
        }

        return [
            'label' => $data['label'] ?? ($data['disease'] ?? 'Unknown'),
            'plant' => $data['plant'] ?? 'Detected',
            'confidence' => $data['confidence'] ?? 0.0,
            'top3' => $data['top3'] ?? [],
            'info' => $data['info'] ?? [
                'desc' => 'Identified as ' . ($data['label'] ?? 'Unknown'),
                'irrigation' => 'Check local guidelines.',
                'treatment' => 'Consult an expert.'
            ]
        ];
    }
}
