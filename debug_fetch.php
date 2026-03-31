<?php
require_once __DIR__ . '/backend/db.php';
require_once __DIR__ . '/backend/ai/chat_context.php';

try {
    $pdo = Database::getConnection();
    
    echo "--- TESTING SUBSIDY FETCH ---\n";
    $subs = chat_fetch_subsidy_snapshot($pdo, '');
    echo "Count: " . count($subs) . "\n";
    print_r($subs);

    echo "--- TESTING MARKET FETCH (AHMEDABAD) ---\n";
    $market = chat_fetch_market_snapshot($pdo, 'ahmedabad', '');
    echo "Count: " . count($market) . "\n";
    print_r($market);

} catch (Exception $e) {
    echo "TOP LEVEL ERROR: " . $e->getMessage();
}
