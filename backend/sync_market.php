<?php
// backend/sync_market.php - Sync latest data from server
require_once __DIR__ . '/db.php';

$pdo = Database::getConnection();
$apiKey = "579b464db66ec23bdd0000012b67c7ab775a420174e338ebaf35bb0c";
$limit = 50;
$offset = 0;
$count = 0;

echo "Syncing latest market data for Today (" . date('d/m/Y') . ")...\n";

$sql = "INSERT INTO market_prices (state, district, market, commodity, variety, grade, arrival_date, min_price, max_price, modal_price) 
        VALUES (:state, :district, :market, :commodity, :variety, :grade, :arrival_date, :min_price, :max_price, :modal_price)
        ON CONFLICT (state, district, market, commodity, variety, arrival_date) 
        DO UPDATE SET 
            min_price = EXCLUDED.min_price, 
            max_price = EXCLUDED.max_price, 
            modal_price = EXCLUDED.modal_price";

$stmt = $pdo->prepare($sql);

while (true) {
    $url = "https://api.data.gov.in/resource/9ef84268-d588-465a-a308-a864a43d0070"
        . "?api-key=$apiKey&format=json&limit=$limit&offset=$offset&filters[state]=Gujarat";

    $json = @file_get_contents($url);
    if ($json === false)
        break;

    $data = json_decode($json, true);
    if (!isset($data['records']) || empty($data['records']))
        break;

    foreach ($data['records'] as $row) {
        $stmt->execute([
            'state' => $row['state'],
            'district' => $row['district'],
            'market' => $row['market'],
            'commodity' => $row['commodity'],
            'variety' => $row['variety'],
            'grade' => $row['grade'],
            'arrival_date' => $row['arrival_date'],
            'min_price' => (int) $row['min_price'],
            'max_price' => (int) $row['max_price'],
            'modal_price' => (int) $row['modal_price']
        ]);
        $count++;
    }

    echo "Offset $offset processed... ($count records synced)\n";

    // Stop if we see dates older than 3 days to avoid full table scan
    $lastDate = end($data['records'])['arrival_date'];
    $lastDt = DateTime::createFromFormat('d/m/Y', $lastDate);
    $threeDaysAgo = new DateTime();
    $threeDaysAgo->modify('-3 days');

    if ($lastDt < $threeDaysAgo) {
        echo "Reached data older than 3 days. Stopping sync.\n";
        break;
    }

    $offset += $limit;
    if ($offset > 200)
        break; // Performance limit for sync
}

echo "Sync complete. Total processed: $count\n";
