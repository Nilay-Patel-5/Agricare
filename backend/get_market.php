<?php
header('Content-Type: application/json');
require_once __DIR__ . '/security_headers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/sync_market.php';

try {
    date_default_timezone_set('Asia/Kolkata');
    $rawInput = file_get_contents("php://input");
    $input = json_decode($rawInput ?: '[]', true);
    if (!is_array($input)) {
        $input = [];
    }
    
    // Simple Cache Logic
    $cachePath = __DIR__ . '/cache/get_market_cache.json';
    $cacheTtl = 600; // 10 minutes cache
    
    // Check if we can serve from cache
    if (file_exists($cachePath) && (time() - filemtime($cachePath)) < $cacheTtl) {
        // Only serve cache if no specific filters are applied
        if (empty($input['districts']) && empty($input['markets']) && empty($input['commodities'])) {
            header('X-Cache: HIT');
            readfile($cachePath);
            exit;
        }
    }

    $pdo = Database::getConnection();
    $today = date('d/m/Y');
    $syncMetaPath = __DIR__ . '/cache/last_market_sync.json';
    $syncMeta = ['synced_at' => null, 'target_date' => null];

    if (is_file($syncMetaPath)) {
        $decoded = json_decode((string) file_get_contents($syncMetaPath), true);
        if (is_array($decoded)) {
            $syncMeta = array_merge($syncMeta, $decoded);
        }
    }

    $latestLocalDate = $pdo->query("SELECT arrival_date FROM market_prices ORDER BY id DESC LIMIT 1")->fetchColumn();
    $lastSyncTs = !empty($syncMeta['synced_at']) ? strtotime((string) $syncMeta['synced_at']) : 0;
    $shouldSync = (!$lastSyncTs || (time() - $lastSyncTs) > 3600) && ($latestLocalDate !== $today); // 1 hour sync gate

    if ($shouldSync) {
        try {
            $syncResult = syncLatestMarketData();
            $syncMeta = [
                'synced_at' => $syncResult['synced_at'] ?? date(DATE_ATOM),
                'target_date' => $syncResult['target_date'] ?? $latestLocalDate,
                'processed' => $syncResult['processed'] ?? 0,
            ];
            @file_put_contents($syncMetaPath, json_encode($syncMeta, JSON_PRETTY_PRINT));
        } catch (Throwable $syncError) {
            // Keep serving the latest local data if sync fails.
        }
    }

    $districts = $input['districts'] ?? [];
    $markets = $input['markets'] ?? [];
    $commodities = $input['commodities'] ?? [];

    $latestDate = $latestLocalDate;

    $query = "SELECT * FROM market_prices WHERE state ILIKE 'gujarat'";
    $params = [];

    if ($latestDate) {
        $query .= " AND arrival_date = :latestDate";
        $params['latestDate'] = $latestDate;
    }

    if (!empty($districts)) {
        $placeholders = [];
        foreach ($districts as $i => $d) {
            $clean = trim(str_replace('Banaskantha', 'Banaskanth', $d));
            $key = "dist$i";
            $placeholders[] = ":$key";
            $params[$key] = "%$clean%";
        }
        if (!empty($placeholders)) {
            $query .= " AND district ILIKE ANY (ARRAY[" . implode(",", $placeholders) . "])";
        }
    }

    if (!empty($markets)) {
        $placeholders = [];
        foreach ($markets as $i => $m) {
            $clean = trim(str_replace(' APMC', '', $m));
            $baseName = explode('(', $clean)[0]; 
            $key = "mkt$i";
            $placeholders[] = ":$key";
            $params[$key] = "%" . trim($baseName) . "%";
        }
        if (!empty($placeholders)) {
            $query .= " AND market ILIKE ANY (ARRAY[" . implode(",", $placeholders) . "])";
        }
    }

    if (!empty($commodities)) {
        $placeholders = [];
        foreach ($commodities as $i => $c) {
            $baseName = explode('(', $c)[0];
            $key = "cmd$i";
            $placeholders[] = ":$key";
            $params[$key] = "%" . trim($baseName) . "%";
        }
        if (!empty($placeholders)) {
            $query .= " AND commodity ILIKE ANY (ARRAY[" . implode(",", $placeholders) . "])";
        }
    }

    $query .= " ORDER BY district ASC, market ASC, commodity ASC LIMIT 500";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $data = $stmt->fetchAll();

    $result = [];
    foreach ($data as $row) {
        $result[] = [
            'commodity' => $row['commodity'] ?? '',
            'market' => $row['market'] ?? '',
            'district' => $row['district'] ?? '',
            'min' => (int) ($row['min_price'] ?? 0),
            'max' => (int) ($row['max_price'] ?? 0),
            'modal' => (int) ($row['modal_price'] ?? 0),
            'arrival_date' => $row['arrival_date'] ?? ''
        ];
    }

    $response = [
        'success' => true,
        'target_date' => $latestDate,
        'today' => $today,
        'synced_at' => $syncMeta['synced_at'] ?? null,
        'rows' => $result,
    ];

    $jsonOutput = json_encode($response);
    
    // Save to cache if no filters applied
    if (empty($districts) && empty($markets) && empty($commodities)) {
        $cacheDir = dirname($cachePath);
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0777, true);
        }
        @file_put_contents($cachePath, $jsonOutput);
    }
    
    header('X-Cache: MISS');
    echo $jsonOutput;

} catch (Exception $e) {
    if (!empty($cachePath) && file_exists($cachePath)) {
        header('X-Cache: STALE');
        readfile($cachePath);
        exit;
    }

    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

