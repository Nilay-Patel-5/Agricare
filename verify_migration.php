<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    echo "USERS COUNT: " . $stmt->fetchColumn() . "\n";
    
    $stmt = $pdo->query("SELECT role, COUNT(*) FROM users GROUP BY role");
    print_r($stmt->fetchAll());
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
