<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    $tables = ['market_prices', 'subsidies', 'shops', 'users'];
    foreach ($tables as $t) {
        $count = $pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn();
        echo "TABLE $t: $count rows\n";
        if ($count > 0) {
            $cols = $pdo->query("SELECT * FROM $t LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            echo "Columns: " . implode(', ', array_keys($cols)) . "\n\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
