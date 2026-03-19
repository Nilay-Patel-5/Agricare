<?php
require_once 'backend/db.php';

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query('SELECT DISTINCT arrival_date FROM market_prices ORDER BY arrival_date DESC LIMIT 10');
    $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Available dates in database:\n";
    foreach($dates as $d) {
        echo "  - " . $d . "\n";
    }
    
    // Also check the count for each date
    echo "\nData count by date:\n";
    $stmt = $pdo->query('SELECT arrival_date, COUNT(*) as count FROM market_prices GROUP BY arrival_date ORDER BY arrival_date DESC LIMIT 10');
    $results = $stmt->fetchAll();
    foreach($results as $r) {
        echo "  - " . $r['arrival_date'] . ": " . $r['count'] . " records\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
