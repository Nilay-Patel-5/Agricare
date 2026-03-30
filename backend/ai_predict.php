<?php
// Suppress display_errors so PHP warnings never corrupt JSON output
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', '0');
ob_start(); // Buffer any accidental output

header('Content-Type: application/json');
set_time_limit(90);

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/ai/Detector.php';
require_once __DIR__ . '/ai/chat_context.php';

// Log errors for debugging
function log_ai_error(string $msg): void {
    file_put_contents(__DIR__ . '/ai_errors.log', date('[Y-m-d H:i:s] ') . $msg . "\n", FILE_APPEND);
}

// Flush any accidental output and send clean JSON error
function ai_json_error(string $message, int $code = 500): void {
    ob_end_clean();
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['error' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ai_json_error('Method not allowed', 405);
}

if (!isset($_FILES['image']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
    ai_json_error('No image uploaded', 400);
}

$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

try {
    $mimeType = $_FILES['image']['type'] ?: 'image/jpeg';
    $pdo = null;

    // Use the unified AI Disease Detection folder service
    $result = DiseaseDetector::identify($_FILES['image']['tmp_name'], $mimeType);

    if (isset($result['error'])) {
        log_ai_error("AI Detection failed: " . $result['error']);
        // If the local AI fails, we might still want to provide a mock result for smooth UX,
        // but as per "remove them", we rely solely on the AI folder.
        ai_json_error("AI System could not process your request. " . $result['error']);
    }

    $identifiedPest = $result['label'] ?? 'Unknown';
    $identifiedPlant = $result['plant'] ?? 'Detected';
    $apiConfidence = $result['confidence'] ?? 0.0;
    $apiInfo = $result['info'] ?? [];

    if ($identifiedPest === 'Unknown' || $identifiedPest === 'Healthy' || (strpos(strtolower($identifiedPest), 'healthy') !== false)) {
        echo json_encode([
            'label' => $identifiedPest,
            'plant' => $identifiedPlant,
            'confidence' => $apiConfidence,
            'info' => [
                'desc' => $apiInfo['desc'] ?? 'The plant appears healthy.',
                'irrigation' => $apiInfo['irrigation'] ?? 'Maintain normal watering.',
                'treatment' => $apiInfo['treatment'] ?? 'No treatment required.',
            ]
        ]);
        exit;
    }

    // Fetch pesticide data from our local database
    $commonName = chat_normalize_pest_name($identifiedPest);
    $pesticides = [];

    try {
        require_once __DIR__ . '/db.php';
        $pdo = Database::getConnection();
        $pesticides = chat_fetch_pesticide_recommendations($pdo, $commonName);
        if (!$pesticides) {
            $pesticides = chat_fetch_pesticide_recommendations($pdo, $identifiedPest);
        }
    } catch (Exception $e) {
        log_ai_error("DB Connection Failed during pesticide lookup: " . $e->getMessage());
    }

    $treatment = ($apiInfo['treatment'] ?? '') !== '' ? $apiInfo['treatment'] . ' ' : "Ensure proper air circulation. ";
    if ($pesticides) {
        $pLines = [];
        foreach ($pesticides as $p) {
            $pLines[] = "Use " . $p['brand'] . " (" . $p['name'] . ")";
        }
        $treatment .= implode(", ", $pLines) . ".";
    } else {
        $treatment .= "Consult your local agri-expert for specific local pesticide brands.";
    }

    // Log scan analytics
    if ($userId > 0) {
        try {
            if ($pdo instanceof PDO) {
                $stmtLog = $pdo->prepare("INSERT INTO ai_scans (user_id, pest_name) VALUES (?, ?)");
                $stmtLog->execute([$userId, $identifiedPest]);
            }
        } catch (Exception $logEx) {
            log_ai_error("ai_scans insert failed: " . $logEx->getMessage());
        }
    }

    // Send the final response
    ob_end_clean();
    echo json_encode([
        'label'      => $commonName ?: $identifiedPest,
        'plant'      => $identifiedPlant,
        'confidence' => $apiConfidence,
        'top3'       => $result['top3'] ?? [],
        'info'       => [
            'desc'       => $apiInfo['desc'] ?? "Identified as " . $identifiedPest,
            'irrigation' => $apiInfo['irrigation'] ?? "Reduce foliar moisture to prevent spread.",
            'treatment'  => $treatment
        ]
    ]);

} catch (Exception $e) {
    log_ai_error("General Exception: " . $e->getMessage());
    ai_json_error($e->getMessage());
}
