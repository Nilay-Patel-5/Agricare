<?php
header('Content-Type: application/json');
require_once __DIR__ . '/security_headers.php';
require_once __DIR__ . '/db.php';

/* Read filters from JS */
$input = json_decode(file_get_contents("php://input"), true);

$districts = $input['districts'] ?? [];
$markets = $input['markets'] ?? [];
$commodities = $input['commodities'] ?? [];

try {
    $pdo = Database::getConnection();

    /* Always Gujarat only */
    $sql = "SELECT district, market, commodity, min_price, max_price, modal_price, arrival_date FROM market_prices WHERE state = 'Gujarat'";
    $params = [];

    /* Apply filters only if selected */
    if (!empty($districts)) {
        $inDistricts = implode(',', array_fill(0, count($districts), '?'));
        $sql .= " AND district IN ($inDistricts)";
        foreach ($districts as $d) $params[] = $d;
    }

    if (!empty($markets)) {
        $inMarkets = implode(',', array_fill(0, count($markets), '?'));
        $sql .= " AND market IN ($inMarkets)";
        foreach ($markets as $m) $params[] = $m;
    }

    if (!empty($commodities)) {
        $inCommodities = implode(',', array_fill(0, count($commodities), '?'));
        $sql .= " AND commodity IN ($inCommodities)";
        foreach ($commodities as $c) $params[] = $c;
    }

    $sql .= " LIMIT 50";

    /* Fetch data */
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($data as $row) {
        $result[] = [
            'district' => $row['district'] ?? '',
            'market' => $row['market'] ?? '',
            'commodity' => $row['commodity'] ?? '',
            'min' => $row['min_price'] ?? '',
            'max' => $row['max_price'] ?? '',
            'modal' => $row['modal_price'] ?? '',
            'arrival_date' => $row['arrival_date'] ?? ''
        ];
    }

    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error."]);
}
