<?php
require_once __DIR__ . '/../env.php';

$host = getenv('MYSQL_DB_HOST') ?: 'localhost';
$user = getenv('MYSQL_DB_USER') ?: '';
$pass = getenv('MYSQL_DB_PASS') ?: '';
$db   = getenv('MYSQL_DB_NAME') ?: '';
$port = (int) (getenv('MYSQL_DB_PORT') ?: 3306);

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    http_response_code(500);
    die("Connection failed.");
}

foreach (['districts', 'markets', 'commodities'] as $table) {
    echo "=== " . strtoupper($table) . " TABLE ===\n";
    $result = $conn->query("DESCRIBE `$table`");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo $row['Field'] . " - " . $row['Type'] . "\n";
        }
    } else {
        echo "Error describing $table.\n";
    }
    echo "\n";
}
