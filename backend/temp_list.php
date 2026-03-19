<?php
$dsn = "pgsql:host=localhost;port=5432;dbname=agricare_db";
$pdo = new PDO($dsn, 'postgres', 'nrp@postgres7');

$out = "=== COMMODITIES ===\n";
$stmt = $pdo->query("SELECT DISTINCT commodity FROM market_prices ORDER BY commodity");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $out .= $row['commodity'] . "\n";
}

$out .= "\n=== DISTRICTS ===\n";
$stmt = $pdo->query("SELECT DISTINCT district FROM market_prices ORDER BY district");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $out .= $row['district'] . "\n";
}

file_put_contents('temp_out.txt', $out);
