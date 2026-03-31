<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    echo "SHOPS COUNT: " . $pdo->query("SELECT COUNT(*) FROM shops")->fetchColumn() . "\n";
    $stmt = $pdo->query("SELECT name, district FROM shops LIMIT 5");
    print_r($stmt->fetchAll());
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
