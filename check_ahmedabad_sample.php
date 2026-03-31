<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    echo "AHMEDABAD SAMPLE:\n";
    $stmt = $pdo->query("SELECT * FROM market_prices WHERE district ILIKE '%ahmedabad%' LIMIT 1");
    print_r($stmt->fetch(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
