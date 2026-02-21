<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->agricare->market_prices;

$data = $collection->find([], ['limit' => 10]);

foreach ($data as $row) {
    echo $row['commodity'] . " - ₹" . $row['modal_price'] . "<br>";
}