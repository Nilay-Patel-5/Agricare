<?php
date_default_timezone_set('Asia/Kolkata');
require_once __DIR__ . '/db.php';

$today = date('Y-m-d');
$todayFmt = date('d/m/Y'); // API stores as DD/MM/YYYY

// Check last_sync.txt
$syncFile = __DIR__ . '/last_sync.txt';
$lastSync = file_exists($syncFile) ? trim(file_get_contents($syncFile)) : 'FILE NOT FOUND';

echo "=== MARKET DATA STATUS REPORT ===" . PHP_EOL;
echo "Today (IST)         : $today" . PHP_EOL;
echo "Last Sync Recorded  : $lastSync" . PHP_EOL;
echo "Sync flag up-to-date: " . ($lastSync === $today ? "YES" : "NO") . PHP_EOL;
echo PHP_EOL;

try {
    $pdo = Database::getConnection();
    echo "DB Connection       : SUCCESS" . PHP_EOL;

    // Total rows
    $total = $pdo->query("SELECT COUNT(*) as cnt FROM market_prices")->fetch()['cnt'];
    echo "Total DB Records    : $total" . PHP_EOL;

    // Latest arrival date
    $row = $pdo->query("SELECT MAX(to_date(arrival_date,'DD/MM/YYYY')) as latest_dt FROM market_prices WHERE state ILIKE 'gujarat'")->fetch();
    $latestDt = $row['latest_dt'] ?? null;
    echo "Latest arrival_date : " . ($latestDt ?? 'N/A') . PHP_EOL;
    echo "Data is today?      : " . ($latestDt === $today ? "YES - Data is FRESH" : "NO - Latest is $latestDt (today is $today)") . PHP_EOL;

    // Records for today
    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM market_prices WHERE arrival_date = :d");
    $stmt->execute([':d' => $todayFmt]);
    $todayCount = $stmt->fetch()['cnt'];
    echo "Records for today   : $todayCount ($todayFmt)" . PHP_EOL;

    // Top 5 latest
    echo PHP_EOL . "=== LATEST 5 RECORDS ===" . PHP_EOL;
    $rows = $pdo->query("SELECT commodity, market, district, arrival_date, modal_price FROM market_prices WHERE state ILIKE 'gujarat' ORDER BY to_date(arrival_date,'DD/MM/YYYY') DESC LIMIT 5")->fetchAll();
    foreach ($rows as $r) {
        echo sprintf("  [%s] %-25s | %-20s | ₹%s/q\n", $r['arrival_date'], $r['commodity'], $r['market'], $r['modal_price']);
    }
} catch (Exception $e) {
    echo "DB ERROR: " . $e->getMessage() . PHP_EOL;
}
