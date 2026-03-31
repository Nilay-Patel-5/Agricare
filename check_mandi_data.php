<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    echo "LATEST AHMEDABAD ENTRIES:\n";
    $stmt = $pdo->prepare("SELECT * FROM market_prices WHERE district ILIKE 'ahmedabad' ORDER BY id DESC LIMIT 5");
    $stmt->execute();
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
