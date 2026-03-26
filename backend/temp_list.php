<?php
$dsn = "pgsql:host=db.fnfqrectniyjpkyfkmal.supabase.co;port=5432;dbname=postgres;sslmode=require";
$pdo = new PDO($dsn, 'postgres', 'nrpsupabase7');

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
