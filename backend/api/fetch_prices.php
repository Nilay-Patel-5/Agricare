<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../env.php';

$host = getenv('MYSQL_DB_HOST') ?: 'localhost';
$user = getenv('MYSQL_DB_USER') ?: '';
$pass = getenv('MYSQL_DB_PASS') ?: '';
$db   = getenv('MYSQL_DB_NAME') ?: '';
$port = (int) (getenv('MYSQL_DB_PORT') ?: 3306);

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

// Create tables if not exist
$conn->query("CREATE TABLE IF NOT EXISTS districts (
    district_id INT AUTO_INCREMENT PRIMARY KEY,
    district_name VARCHAR(255) UNIQUE NOT NULL
)");
$conn->query("CREATE TABLE IF NOT EXISTS markets (
    market_id INT AUTO_INCREMENT PRIMARY KEY,
    market_name VARCHAR(255) NOT NULL,
    district_id INT,
    FOREIGN KEY (district_id) REFERENCES districts(district_id),
    UNIQUE KEY unique_market (market_name, district_id)
)");
$conn->query("CREATE TABLE IF NOT EXISTS commodities (
    commodity_id INT AUTO_INCREMENT PRIMARY KEY,
    commodity_name VARCHAR(255) UNIQUE NOT NULL
)");
$conn->query("CREATE TABLE IF NOT EXISTS market_prices (
    price_id INT AUTO_INCREMENT PRIMARY KEY,
    commodity_id INT,
    market_id INT,
    price_per_quintal DECIMAL(10,2),
    arrival_tonnes DECIMAL(10,2),
    price_date DATE,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commodity_id) REFERENCES commodities(commodity_id),
    FOREIGN KEY (market_id) REFERENCES markets(market_id)
)");

$api_key     = getenv('DATA_GOV_API_KEY') ?: '';
$resource_id = '9ef84268-d588-465a-a308-a864a43d0070';

if (!$api_key) {
    http_response_code(500);
    echo json_encode(['error' => 'API key not configured.']);
    exit;
}

$url      = "https://api.data.gov.in/resource/{$resource_id}?api-key={$api_key}&format=json&limit=100";
$response = file_get_contents($url);

if ($response === false) {
    http_response_code(502);
    echo json_encode(['error' => 'External API request failed.']);
    exit;
}

$data = json_decode($response, true);
if (!isset($data['records'])) {
    http_response_code(502);
    echo json_encode(['error' => 'No records found in API response.']);
    exit;
}

// Prepared statements to prevent SQL injection
$stmtInsertDistrict   = $conn->prepare("INSERT IGNORE INTO districts (district_name) VALUES (?)");
$stmtSelectDistrict   = $conn->prepare("SELECT district_id FROM districts WHERE district_name = ?");
$stmtInsertMarket     = $conn->prepare("INSERT IGNORE INTO markets (market_name, district_id) VALUES (?, ?)");
$stmtSelectMarket     = $conn->prepare("SELECT market_id FROM markets WHERE market_name = ? AND district_id = ?");
$stmtInsertCommodity  = $conn->prepare("INSERT IGNORE INTO commodities (commodity_name) VALUES (?)");
$stmtSelectCommodity  = $conn->prepare("SELECT commodity_id FROM commodities WHERE commodity_name = ?");
$stmtCheckPrice       = $conn->prepare("SELECT price_id FROM market_prices WHERE commodity_id = ? AND market_id = ? AND price_date = ?");
$stmtInsertPrice      = $conn->prepare("INSERT INTO market_prices (commodity_id, market_id, price_per_quintal, arrival_tonnes, price_date, last_updated) VALUES (?, ?, ?, ?, ?, NOW())");

$count = 0;

foreach ($data['records'] as $row) {
    $commodity_name = trim($row['commodity'] ?? '');
    $market_name    = trim($row['market'] ?? '');
    $district_name  = trim($row['district'] ?? '');
    $price          = is_numeric($row['modal_price'] ?? null) ? $row['modal_price'] : 0;
    $arrival        = is_numeric($row['arrival_quantity'] ?? null) ? $row['arrival_quantity'] : 0;
    $date_str       = $row['arrival_date'] ?? date('d/m/Y');

    if (!$commodity_name || !$market_name || !$district_name) {
        continue;
    }

    $date_obj = DateTime::createFromFormat('d/m/Y', $date_str);
    $date     = $date_obj ? $date_obj->format('Y-m-d') : date('Y-m-d');

    // District
    $stmtInsertDistrict->bind_param('s', $district_name);
    $stmtInsertDistrict->execute();
    $stmtSelectDistrict->bind_param('s', $district_name);
    $stmtSelectDistrict->execute();
    $stmtSelectDistrict->bind_result($district_id);
    $stmtSelectDistrict->fetch();
    $stmtSelectDistrict->reset();

    // Market
    $stmtInsertMarket->bind_param('si', $market_name, $district_id);
    $stmtInsertMarket->execute();
    $stmtSelectMarket->bind_param('si', $market_name, $district_id);
    $stmtSelectMarket->execute();
    $stmtSelectMarket->bind_result($market_id);
    $stmtSelectMarket->fetch();
    $stmtSelectMarket->reset();

    // Commodity
    $stmtInsertCommodity->bind_param('s', $commodity_name);
    $stmtInsertCommodity->execute();
    $stmtSelectCommodity->bind_param('s', $commodity_name);
    $stmtSelectCommodity->execute();
    $stmtSelectCommodity->bind_result($commodity_id);
    $stmtSelectCommodity->fetch();
    $stmtSelectCommodity->reset();

    // Check duplicate
    $stmtCheckPrice->bind_param('iis', $commodity_id, $market_id, $date);
    $stmtCheckPrice->execute();
    $stmtCheckPrice->store_result();
    if ($stmtCheckPrice->num_rows === 0) {
        $stmtInsertPrice->bind_param('iidds', $commodity_id, $market_id, $price, $arrival, $date);
        if ($stmtInsertPrice->execute()) {
            $count++;
        }
    }
    $stmtCheckPrice->free_result();
}

echo json_encode(['success' => true, 'inserted' => $count]);
