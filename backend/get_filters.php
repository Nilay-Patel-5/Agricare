<?php
header('Content-Type: application/json');
require_once __DIR__ . '/security_headers.php';
require_once __DIR__ . '/db.php';

try {
    // Simple Cache Logic
    $cachePath = __DIR__ . '/cache/get_filters_cache.json';
    $cacheTtl = 3600; // 1 hour cache
    
    if (file_exists($cachePath) && (time() - filemtime($cachePath)) < $cacheTtl) {
        header('X-Cache: HIT');
        readfile($cachePath);
        exit;
    }

    $pdo = Database::getConnection();

    // Distinct values for Gujarat
    $stmtDistricts = $pdo->query("SELECT DISTINCT district FROM market_prices WHERE state = 'Gujarat' AND district IS NOT NULL ORDER BY district");
    $districts = $stmtDistricts->fetchAll(PDO::FETCH_COLUMN);

    $stmtMarkets = $pdo->query("SELECT DISTINCT market FROM market_prices WHERE state = 'Gujarat' AND market IS NOT NULL ORDER BY market");
    $markets = $stmtMarkets->fetchAll(PDO::FETCH_COLUMN);

    $stmtCommodities = $pdo->query("SELECT DISTINCT commodity FROM market_prices WHERE state = 'Gujarat' AND commodity IS NOT NULL ORDER BY commodity");
    $commodities = $stmtCommodities->fetchAll(PDO::FETCH_COLUMN);

    $response = [
        "districts" => $districts,
        "markets" => $markets,
        "commodities" => $commodities
    ];

    $jsonOutput = json_encode($response);
    $cacheDir = dirname($cachePath);
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0777, true);
    }
    @file_put_contents($cachePath, $jsonOutput);

    header('X-Cache: MISS');
    echo $jsonOutput;
} catch (Exception $e) {
    if (file_exists($cachePath)) {
        header('X-Cache: STALE');
        readfile($cachePath);
        exit;
    }

    $marketCachePath = __DIR__ . '/cache/get_market_cache.json';
    if (file_exists($marketCachePath)) {
        $marketPayload = json_decode((string) file_get_contents($marketCachePath), true);
        $rows = is_array($marketPayload['rows'] ?? null) ? $marketPayload['rows'] : [];

        if ($rows) {
            $response = [
                'districts' => array_values(array_unique(array_filter(array_map(static fn(array $row): string => (string) ($row['district'] ?? ''), $rows)))),
                'markets' => array_values(array_unique(array_filter(array_map(static fn(array $row): string => (string) ($row['market'] ?? ''), $rows)))),
                'commodities' => array_values(array_unique(array_filter(array_map(static fn(array $row): string => (string) ($row['commodity'] ?? ''), $rows)))),
            ];

            sort($response['districts']);
            sort($response['markets']);
            sort($response['commodities']);

            header('X-Cache: DERIVED');
            echo json_encode($response);
            exit;
        }
    }

    http_response_code(500);
    echo json_encode(["error" => "Server error."]);
}

