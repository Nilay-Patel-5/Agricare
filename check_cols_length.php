<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("SELECT * FROM market_prices LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    foreach ($row as $k => $v) {
        echo "$k: '$v' (len: " . strlen($v) . ")\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
