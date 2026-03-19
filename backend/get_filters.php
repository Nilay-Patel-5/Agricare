<?php
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

try {
    $pdo = Database::getConnection();

    // Distinct values for Gujarat
    $stmtDistricts = $pdo->query("SELECT DISTINCT district FROM market_prices WHERE state = 'Gujarat' AND district IS NOT NULL ORDER BY district");
    $districts = $stmtDistricts->fetchAll(PDO::FETCH_COLUMN);

    $stmtMarkets = $pdo->query("SELECT DISTINCT market FROM market_prices WHERE state = 'Gujarat' AND market IS NOT NULL ORDER BY market");
    $markets = $stmtMarkets->fetchAll(PDO::FETCH_COLUMN);

    $stmtCommodities = $pdo->query("SELECT DISTINCT commodity FROM market_prices WHERE state = 'Gujarat' AND commodity IS NOT NULL ORDER BY commodity");
    $commodities = $stmtCommodities->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        "districts" => $districts,
        "markets" => $markets,
        "commodities" => $commodities
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
