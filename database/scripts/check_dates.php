<?php
require_once __DIR__ . '/db.php';
try {
    $pdo = Database::getConnection();
    echo "Latest arrival dates in DB:\n";
    $res = $pdo->query("SELECT arrival_date, COUNT(*) FROM market_prices GROUP BY arrival_date ORDER BY to_date(arrival_date, 'DD/MM/YYYY') DESC NULLS LAST LIMIT 10")->fetchAll();
    print_r($res);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
