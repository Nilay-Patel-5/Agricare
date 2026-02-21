<?php
header('Content-Type: application/json');
require '../vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->agricare->market_prices;

/* Distinct values */
$districts = $collection->distinct("district", ['state'=>'Gujarat']);
$markets   = $collection->distinct("market", ['state'=>'Gujarat']);
$commodities = $collection->distinct("commodity", ['state'=>'Gujarat']);

echo json_encode([
    "districts"=>$districts,
    "markets"=>$markets,
    "commodities"=>$commodities
]);