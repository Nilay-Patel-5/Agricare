<?php
require '../vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");

echo "<h2>Databases:</h2>";

foreach ($client->listDatabases() as $db) {
    echo $db->getName() . "<br>";
}

echo "<hr><h2>Collections inside agricare:</h2>";

$db = $client->agricare;

foreach ($db->listCollections() as $col) {
    echo $col->getName() . "<br>";
}

echo "<hr><h2>Count:</h2>";

echo $db->market_prices->countDocuments();