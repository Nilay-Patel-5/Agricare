<?php
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/sync_market.php';

try {
    date_default_timezone_set('Asia/Kolkata');
    $pdo = Database::getConnection();

    /* Read filters from frontend */
    $input = json_decode(file_get_contents("php://input"), true);
    $districts = $input['districts'] ?? [];
    $markets = $input['markets'] ?? [];
    $commodities = $input['commodities'] ?? [];

    // Get the latest arrival date first. 
    // Optimization: Sort by ID desc and take the arrival_date from the most recent record.
    // This is much faster than MAX(to_date(...)) on all rows.
    $dateQuery = "SELECT arrival_date FROM market_prices WHERE state ILIKE 'gujarat' ORDER BY id DESC LIMIT 1";
    $dateStmt = $pdo->prepare($dateQuery);
    $dateStmt->execute();
    $latestDateResult = $dateStmt->fetch();

    $latestDate = $latestDateResult['arrival_date'] ?? null;

    // Query for latest date data only
    $query = "SELECT * FROM market_prices WHERE state ILIKE 'gujarat'";
    $params = [];

    if ($latestDate) {
        $query .= " AND arrival_date = :latestDate";
        $params['latestDate'] = $latestDate;
    }

    if (!empty($districts)) {
        $placeholders = [];
        foreach ($districts as $i => $d) {
            $clean = trim($d); 
            $key = "dist$i";
            $placeholders[] = ":$key";
            $params[$key] = "%$clean%";
        }
        $query .= " AND (" . implode(" OR ", array_map(fn($p) => "district ILIKE $p", $placeholders)) . ")";
    }

    if (!empty($markets)) {
        $placeholders = [];
        foreach ($markets as $i => $m) {
            $clean = trim(str_replace(' APMC', '', $m));
            $key = "mkt$i";
            $placeholders[] = ":$key";
            $params[$key] = "%$clean%";
        }
        $query .= " AND (" . implode(" OR ", array_map(fn($p) => "market ILIKE $p", $placeholders)) . ")";
    }

    if (!empty($commodities)) {
        $placeholders = [];
        foreach ($commodities as $i => $c) {
            $clean = trim($c);
            $key = "cmd$i";
            $placeholders[] = ":$key";
            $params[$key] = "%$clean%";
        }
        $query .= " AND (" . implode(" OR ", array_map(fn($p) => "commodity ILIKE $p", $placeholders)) . ")";
    }

    // Optimization: Sort by id DESC instead of converting strings to dates.
    $query .= " ORDER BY id DESC LIMIT 500";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $data = $stmt->fetchAll();

    /* Prepare result for table */
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

    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
