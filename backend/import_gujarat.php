<?php

require '../vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->agricare->market_prices;

$apiKey = "579b464db66ec23bdd0000012b67c7ab775a420174e338ebaf35bb0c";

$offset = 0;
$limit  = 100;

$totalInserted = 0;

while (true) {

    $url = "https://api.data.gov.in/resource/9ef84268-d588-465a-a308-a864a43d0070"
         . "?api-key=$apiKey&format=json&limit=$limit&offset=$offset";

    $json = file_get_contents($url);
    $data = json_decode($json, true);

    if (!isset($data['records']) || count($data['records']) == 0) {
        break;
    }

    $batchInsert = [];

    foreach ($data['records'] as $row) {

        if (strtolower($row['state']) !== 'gujarat')
            continue;

        $batchInsert[] = [
            'state' => $row['state'],
            'district' => $row['district'],
            'market' => $row['market'],
            'commodity' => $row['commodity'],
            'variety' => $row['variety'],
            'grade' => $row['grade'],
            'arrival_date' => $row['arrival_date'],
            'min_price' => (int)$row['min_price'],
            'max_price' => (int)$row['max_price'],
            'modal_price' => (int)$row['modal_price']
        ];
    }

    if (!empty($batchInsert)) {
        $collection->insertMany($batchInsert);
        $totalInserted += count($batchInsert);
    }

    echo "Offset $offset processed | Inserted: " . count($batchInsert) . "<br>";

    $offset += $limit;

    sleep(1); // avoid API block
}

echo "<h2>Total Gujarat Records Inserted: $totalInserted</h2>";