<?php
require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->agricare->market_prices;

/* Read filters from JS */
$input = json_decode(file_get_contents("php://input"), true);

$districts = $input['districts'] ?? [];
$markets = $input['markets'] ?? [];
$commodities = $input['commodities'] ?? [];

/* Always Gujarat only */
$query = [
    'state' => 'Gujarat'
];

/* Apply filters only if selected */
if (!empty($districts)) {
    $query['district'] = ['$in' => $districts];
}

if (!empty($markets)) {
    $query['market'] = ['$in' => $markets];
}

if (!empty($commodities)) {
    $query['commodity'] = ['$in' => $commodities];
}

/* Fetch data */
$data = $collection->find($query, ['limit' => 50]);

$result = [];

foreach ($data as $row) {
    $result[] = [
        'district' => $row['district'] ?? '',
        'market' => $row['market'] ?? '',
        'commodity' => $row['commodity'] ?? '',
        'min' => $row['min_price'] ?? '',
        'max' => $row['max_price'] ?? '',
        'modal' => $row['modal_price'] ?? ''
    ];
}

echo json_encode($result);