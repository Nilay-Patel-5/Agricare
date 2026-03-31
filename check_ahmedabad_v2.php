<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    echo "AHMEDABAD TOTAL: " . $pdo->query("SELECT COUNT(*) FROM market_prices WHERE TRIM(district) ILIKE 'ahmedabad'")->fetchColumn() . "\n";
    $stmt = $pdo->query("SELECT DISTINCT arrival_date FROM market_prices WHERE TRIM(district) ILIKE 'ahmedabad' ORDER BY arrival_date DESC LIMIT 5");
    print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
