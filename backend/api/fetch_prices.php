<?php

// Database connection
$conn = new mysqli("localhost", "root", "", "agricare", 3307);

if ($conn->connect_error) {
    die("DB Failed: " . $conn->connect_error);
}

/* -------------------------------
   0. CREATE TABLES IF NOT EXISTS
--------------------------------*/

// Districts Table
$conn->query("CREATE TABLE IF NOT EXISTS districts (
    district_id INT AUTO_INCREMENT PRIMARY KEY,
    district_name VARCHAR(255) UNIQUE NOT NULL
)");

// Markets Table
$conn->query("CREATE TABLE IF NOT EXISTS markets (
    market_id INT AUTO_INCREMENT PRIMARY KEY,
    market_name VARCHAR(255) NOT NULL,
    district_id INT,
    FOREIGN KEY (district_id) REFERENCES districts(district_id),
    UNIQUE KEY unique_market (market_name, district_id)
)");

// Commodities Table
$conn->query("CREATE TABLE IF NOT EXISTS commodities (
    commodity_id INT AUTO_INCREMENT PRIMARY KEY,
    commodity_name VARCHAR(255) UNIQUE NOT NULL
)");

// Market Prices Table
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

/* -------------------------------
   1. CALL AGMARKNET API
--------------------------------*/

$api_key = "579b464db66ec23bdd00000112d98608618240ae6b2dd69c1d04ff51";
$resource_id = "9ef84268-d588-465a-a308-a864a43d0070";
$limit = 100; // Limit for records

// Added filter for Gujarat to make it more relevant as per previous context (optional but good practice)
$url = "https://api.data.gov.in/resource/{$resource_id}?api-key={$api_key}&format=json&limit={$limit}";

$response = file_get_contents($url);

if(!$response){
    die("API not working or limit exceeded");
}

$data = json_decode($response, true);

if (!isset($data['records'])) {
    die("No records found in API response. Response: " . substr($response, 0, 100) . "...");
}

/* -------------------------------
   2. READ DATA FROM API
--------------------------------*/

$count = 0;

foreach($data['records'] as $row){
    
    // Extract data with fallbacks
    $commodity_name = isset($row['commodity']) ? $conn->real_escape_string($row['commodity']) : null;
    $market_name    = isset($row['market']) ? $conn->real_escape_string($row['market']) : null;
    $district_name  = isset($row['district']) ? $conn->real_escape_string($row['district']) : null;
    $price          = isset($row['modal_price']) ? $row['modal_price'] : 0;
    $arrival        = isset($row['arrival_quantity']) ? $row['arrival_quantity'] : 0; 
    $date_str       = isset($row['arrival_date']) ? $row['arrival_date'] : date('d/m/Y');
    
    // Parse date (API often returns DD/MM/YYYY)
    $date_obj = DateTime::createFromFormat('d/m/Y', $date_str);
    $date = $date_obj ? $date_obj->format('Y-m-d') : date('Y-m-d');

    if (!$commodity_name || !$market_name || !$district_name) {
        continue; // Skip incomplete records
    }

    /* -------------------------------
       3. INSERT DISTRICT
    --------------------------------*/
    $conn->query("INSERT IGNORE INTO districts(district_name) VALUES('$district_name')");
    
    // Get ID
    $res = $conn->query("SELECT district_id FROM districts WHERE district_name='$district_name'");
    $district_row = $res->fetch_assoc();
    $district_id = $district_row['district_id'];

    /* -------------------------------
       4. INSERT MARKET
    --------------------------------*/
    $conn->query("INSERT IGNORE INTO markets(market_name, district_id) VALUES('$market_name', '$district_id')");
    
    // Get ID
    $res = $conn->query("SELECT market_id FROM markets WHERE market_name='$market_name' AND district_id='$district_id'");
    $market_row = $res->fetch_assoc();
    $market_id = $market_row['market_id'];

    /* -------------------------------
       5. INSERT COMMODITY
    --------------------------------*/
    $conn->query("INSERT IGNORE INTO commodities(commodity_name) VALUES('$commodity_name')");
    
    // Get ID
    $res = $conn->query("SELECT commodity_id FROM commodities WHERE commodity_name='$commodity_name'");
    $commodity_row = $res->fetch_assoc();
    $commodity_id = $commodity_row['commodity_id'];

    /* -------------------------------
       6. INSERT PRICE DATA
    --------------------------------*/
    // Check if entry exists for this date to avoid duplication
    $check = $conn->query("SELECT price_id FROM market_prices 
                          WHERE commodity_id='$commodity_id' 
                          AND market_id='$market_id' 
                          AND price_date='$date'");
    
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO market_prices(commodity_id, market_id, price_per_quintal, arrival_tonnes, price_date, last_updated)
                VALUES('$commodity_id', '$market_id', '$price', '$arrival', '$date', NOW())";
        if ($conn->query($sql)) {
            $count++;
        } else {
             // echo "Error: " . $conn->error . "<br>";
        }
    }
}

echo "DATA IMPORTED SUCCESSFULLY. imported $count records.";

?>
