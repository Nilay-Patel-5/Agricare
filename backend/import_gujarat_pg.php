<?php
// backend/import_gujarat_pg.php - Import Market Data to PostgreSQL

require_once __DIR__ . '/db.php';

$pdo = Database::getConnection();

$apiKey = "579b464db66ec23bdd0000012b67c7ab775a420174e338ebaf35bb0c";
$offset = 0;
$limit = 100;
$totalInserted = 0;

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
        . "?api-key=$apiKey&format=json&limit=$limit&offset=$offset";

    $json = @file_get_contents($url);
    if ($json === false)
        break;

    $data = json_decode($json, true);
    if (!isset($data['records']) || empty($data['records']))
        break;

    foreach ($data['records'] as $row) {
        if (strtolower($row['state'] ?? '') !== 'gujarat')
            continue;

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
        $totalInserted++;
    }

    echo "Offset $offset processed | Records Synced: $totalInserted\n";
    $offset += $limit;
    sleep(1);
    if ($offset > 5000)
        break;
}

echo "Total records processed: $totalInserted\n";
