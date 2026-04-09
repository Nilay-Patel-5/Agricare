<?php
header('Content-Type: application/json');
require_once __DIR__ . '/security_headers.php';
require_once __DIR__ . '/db.php';

try {
    $pdo = Database::getConnection();
    
    $district = trim($_GET['district'] ?? '');
    $city = trim($_GET['city'] ?? '');
    
    // Base query
    $query = "SELECT * FROM crops";
    $params = [];
    
    if (!empty($district)) {
        // Find crops that are active in the market for this district (and city if provided)
        $query .= " WHERE EXISTS (
            SELECT 1 FROM market_prices 
            WHERE district ILIKE :district ";
        
        $params['district'] = '%' . $district . '%';

        if (!empty($city)) {
            $query .= " AND market ILIKE :market ";
            $params['market'] = '%' . $city . '%';
        }

        $query .= " AND (
                commodity ILIKE '%' || name_en || '%' OR 
                name_en ILIKE '%' || commodity || '%' OR
                commodity ILIKE '%' || name_gu || '%' OR
                commodity ILIKE '%' || name_hi || '%'
            )
        )";
    }
    
    $query .= " ORDER BY id";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $crops = $stmt->fetchAll();

    // If no crops found for district, fallback to general popular crops
    if (empty($crops) && !empty($district)) {
        $stmt = $pdo->query("SELECT * FROM crops ORDER BY id LIMIT 15");
        $crops = $stmt->fetchAll();
    }

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
            'task_hi' => $sched['task_hi'],
            'start_day' => (int)$sched['start_day'],
            'end_day' => (int)$sched['end_day']
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
