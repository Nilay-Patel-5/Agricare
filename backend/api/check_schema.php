<?php
$conn = new mysqli("localhost", "root", "", "agricare", 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== DISTRICTS TABLE ===\n";
$result = $conn->query("DESCRIBE districts");
if ($result) {
    while($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error describing districts: " . $conn->error . "\n";
}

echo "\n=== MARKETS TABLE ===\n";
$result = $conn->query("DESCRIBE markets");
if ($result) {
    while($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error describing markets: " . $conn->error . "\n";
}

echo "\n=== COMMODITIES TABLE ===\n";
$result = $conn->query("DESCRIBE commodities");
if ($result) {
    while($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error describing commodities: " . $conn->error . "\n";
}
?>
