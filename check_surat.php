<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    echo "SURAT ENTRIES: " . $pdo->query("SELECT COUNT(*) FROM market_prices WHERE district ILIKE '%Surat%'")->fetchColumn() . "\n";
    $stmt = $pdo->query("SELECT DISTINCT district FROM market_prices LIMIT 10");
    echo "DISTRICTS: " . implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN)) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
