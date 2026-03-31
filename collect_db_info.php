<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    $data = [];

    $res = $pdo->query("SELECT * FROM market_prices LIMIT 1");
    $row = $res->fetch(PDO::FETCH_ASSOC);
    $data['market_prices'] = array_keys($row ?: ['EMPTY']);
    $data['market_prices_latest'] = $pdo->query("SELECT arrival_date FROM market_prices ORDER BY id DESC LIMIT 1")->fetchColumn();

    $res = $pdo->query("SELECT * FROM subsidies LIMIT 1");
    $row = $res->fetch(PDO::FETCH_ASSOC);
    $data['subsidies'] = array_keys($row ?: ['EMPTY']);

    file_put_contents('db_info.json', json_encode($data, JSON_PRETTY_PRINT));
} catch (Exception $e) {
    file_put_contents('db_info.txt', $e->getMessage());
}
