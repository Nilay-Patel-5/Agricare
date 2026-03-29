<?php
require_once __DIR__ . '/db.php';
try {
    $pdo = Database::getConnection();
    
    // Dump crops
    $stmt = $pdo->query("SELECT * FROM crops ORDER BY id");
    $crops = $stmt->fetchAll();
    echo "CROPS:\n";
    print_r($crops);

    // Dump schedules
    $stmt = $pdo->query("SELECT * FROM crop_schedules ORDER BY crop_id, month_index");
    $scheds = $stmt->fetchAll();
    echo "\nSCHEDULES:\n";
    print_r($scheds);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
