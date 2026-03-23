<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

try {
    $pdo = Database::getConnection();
    
    // Fetch all crops and their schedules in one (or two) efficient calls
    $stmt = $pdo->query("SELECT * FROM crops ORDER BY id");
    $crops = $stmt->fetchAll();

    // Fetch ALL schedules at once to avoid N+1 problem
    $stmtAllScheds = $pdo->query("SELECT * FROM crop_schedules ORDER BY crop_id, month_index");
    $allSchedules = $stmtAllScheds->fetchAll();

    // Map schedules to crops in memory
    $schedulesByCrop = [];
    foreach ($allSchedules as $sched) {
        $schedulesByCrop[$sched['crop_id']][] = [
            'month_index' => $sched['month_index'],
            'activity_type' => $sched['activity_type'],
            'activity_icon' => $sched['activity_icon'],
            'activity_color' => $sched['activity_color'],
            'task_en' => $sched['task_en'],
            'task_gu' => $sched['task_gu'],
            'task_hi' => $sched['task_hi']
        ];
    }

    foreach ($crops as &$crop) {
        $crop['schedule'] = $schedulesByCrop[$crop['id']] ?? [];
    }

    echo json_encode($crops);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
