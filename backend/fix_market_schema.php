<?php
require_once __DIR__ . '/db.php';
try {
    $pdo = Database::getConnection();

    // Check if constraint already exists
    $res = $pdo->query("SELECT constraint_name FROM information_schema.table_constraints WHERE table_name = 'market_prices' AND constraint_name = 'unique_market_data'")->fetch();

    if (!$res) {
        $pdo->exec("ALTER TABLE market_prices ADD CONSTRAINT unique_market_data UNIQUE (state, district, market, commodity, variety, arrival_date)");
        echo "Constraint unique_market_data added.\n";
    } else {
        echo "Constraint unique_market_data already exists.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
