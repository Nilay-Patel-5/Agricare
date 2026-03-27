<?php
require_once 'd:/SGP/Agricare/backend/db.php';

$start = microtime(true);
try {
    $pdo = Database::getConnection();
    $end = microtime(true);
    echo "Connection successful in " . ($end - $start) . " seconds\n";
    
    $start = microtime(true);
    $stmt = $pdo->query("SELECT 1");
    $end = microtime(true);
    echo "Simple query successful in " . ($end - $start) . " seconds\n";
    
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
