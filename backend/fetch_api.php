<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->agricare;
$collection = $db->market_prices;

// API URL (replace API KEY)
$api_key = "579b464db66ec23bdd0000012b67c7ab775a420174e338ebaf35bb0c";

$url = "https://api.data.gov.in/resource/9ef84268-d588-465a-a308-a864a43d0070?api-key=$api_key&format=json&limit=20";

$response = file_get_contents($url);
$data = json_decode($response, true);

if(isset($data['records'])) {

    foreach($data['records'] as $record) {
        $collection->insertOne($record);
    }

    echo "Data Inserted Successfully";

} else {
    echo "API Failed";
}