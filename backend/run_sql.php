<?php
// backend/run_sql.php - Helper to run SQL files
require_once __DIR__ . '/db.php';

if ($argc < 2) {
    echo "Usage: php run_sql.php <sql_file_path>\n";
    exit(1);
}

$sqlFile = $argv[1];
if (!file_exists($sqlFile)) {
    echo "Error: SQL file not found at $sqlFile\n";
    exit(1);
}

try {
    $pdo = Database::getConnection();
    echo "Connected to database.\n";
    
    $sql = file_get_contents($sqlFile);
    $pdo->exec($sql);
    
    echo "SQL script executed successfully.\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
