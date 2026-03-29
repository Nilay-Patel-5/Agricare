<?php
require_once __DIR__ . '/db.php';
try {
    $pdo = Database::getConnection();
    echo "CROPS SCHEMA:\n";
    $stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'crops'");
    print_r($stmt->fetchAll());
    
    echo "SCHEDULES SCHEMA:\n";
    $stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'crop_schedules'");
    print_r($stmt->fetchAll());
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
