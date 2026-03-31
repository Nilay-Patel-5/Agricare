<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    echo "TABLES IN PUBLIC SCHEMA:\n";
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
