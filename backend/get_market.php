<?php
header('Content-Type: application/json');

require '../vendor/autoload.php';

use MongoDB\BSON\Regex;

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->agricare->market_prices;

/* Read filters from frontend */
$input = json_decode(file_get_contents("php://input"), true);

$districts   = $input['districts']   ?? [];
$markets     = $input['markets']     ?? [];
$commodities = $input['commodities'] ?? [];

/* ---------------- MASTER FILTER ---------------- */
$filter = [
    '$and' => []
];

/* Always Gujarat */
$filter['$and'][] = [
    'state' => new MongoDB\BSON\Regex('gujarat', 'i')
];


/* ---------- DISTRICT FILTER ---------- */
if (!empty($districts)) {

    $districtConditions = [];

    foreach ($districts as $d) {
        $clean = preg_replace('/\(.+\)/', '', $d);
        $clean = trim($clean);

        $districtConditions[] = [
            'district' => new MongoDB\BSON\Regex($clean, 'i')
        ];
    }

    $filter['$and'][] = ['$or' => $districtConditions];
}


/* ---------- MARKET FILTER ---------- */
if (!empty($markets)) {

    $marketConditions = [];

    foreach ($markets as $m) {
        $clean = str_replace(' APMC', '', $m);
        $clean = trim($clean);

        $marketConditions[] = [
            'market' => new MongoDB\BSON\Regex($clean, 'i')
        ];
    }

    $filter['$and'][] = ['$or' => $marketConditions];
}


/* ---------- COMMODITY FILTER ---------- */
if (!empty($commodities)) {

    $commodityConditions = [];

    foreach ($commodities as $c) {
        $clean = preg_replace('/\(.+\)/', '', $c);
        $clean = trim($clean);

        $commodityConditions[] = [
            'commodity' => new MongoDB\BSON\Regex($clean, 'i')
        ];
    }

    $filter['$and'][] = ['$or' => $commodityConditions];
}

/* Query database */
$data = $collection->find($filter, ['limit' => 500]);

/* Prepare result for table */
$result = [];

foreach ($data as $row) {
    $result[] = [
        'commodity' => $row['commodity'] ?? '',
        'market'    => $row['market'] ?? '',
        'district'  => $row['district'] ?? '',
        'min'       => (int)($row['min_price'] ?? 0),
        'max'       => (int)($row['max_price'] ?? 0),
        'modal'     => (int)($row['modal_price'] ?? 0),
        'arrival_date' => $row['arrival_date'] ?? ''
    ];
}

echo json_encode($result);
