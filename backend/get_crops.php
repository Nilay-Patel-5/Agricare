<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

try {
    $pdo = Database::getConnection();
    
    // Fetch all crops
    $stmt = $pdo->query("SELECT * FROM crops ORDER BY id");
    $crops = $stmt->fetchAll();

    foreach ($crops as &$crop) {
        $stmtSched = $pdo->prepare("SELECT month_index, activity_type, activity_icon, activity_color, task_en, task_gu, task_hi FROM crop_schedules WHERE crop_id = ? ORDER BY month_index");
        $stmtSched->execute([$crop['id']]);
        $crop['schedule'] = $stmtSched->fetchAll();
    }

    echo json_encode($crops);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
