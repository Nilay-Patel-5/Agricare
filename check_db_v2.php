<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    
    echo "--- MARKET_PRICES ---\n";
    $stmt = $pdo->query("SELECT * FROM market_prices LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r(array_keys($row));
    echo "Latest arrival_date: " . $pdo->query("SELECT arrival_date FROM market_prices ORDER BY id DESC LIMIT 1")->fetchColumn() . "\n";

    echo "--- SUBSIDIES ---\n";
    $stmt = $pdo->query("SELECT * FROM subsidies LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r(array_keys($row));

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
