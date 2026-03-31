<?php
// backend/seed_shops.php
require_once __DIR__ . '/db.php';

header('Content-Type: text/plain');

try {
    $pdo = Database::getConnection();
    echo "--- Seeding Shops Data ---\n";

    $shops = [
        ['Ahmedabad Agriculture Store', 'Ahmedabad', 'Sanand', 'Near Market Yard, Sanand', '9876543210'],
        ['Surat Kisan Mandi', 'Surat', 'Surat', 'Ring Road, Surat', '9876543211'],
        ['Rajkot Fertilizers', 'Rajkot', 'Rajkot', 'GIDC Area, Rajkot', '9876543212'],
        ['Vadodara Seed Hub', 'Vadodara', 'Vadodara', 'Old City, Vadodara', '9876543213'],
        ['Bhavnagar Agri Care', 'Bhavnagar', 'Bhavnagar', 'Station Road', '9876543214'],
        ['Amreli Farmer Friend', 'Amreli', 'Amreli', 'Main Bazar', '9876543215'],
        ['Junagadh Agro Center', 'Junagadh', 'Junagadh', 'Talala Road', '9876543216'],
        ['Mehsana Pesticides', 'Mehsana', 'Mehsana', 'Modhera Road', '9876543217'],
        ['Anand Agri Implements', 'Anand', 'Anand', 'V.V. Nagar', '9876543218'],
        ['Kutch Farmer Outlet', 'Kutch', 'Bhuj', 'Mundra Road', '9876543219'],
    ];

    $stmt = $pdo->prepare("INSERT INTO shops (name, district, city, address, phone) VALUES (?, ?, ?, ?, ?)");

    foreach ($shops as $shop) {
        $stmt->execute($shop);
        echo "Seeded: {$shop[0]}\n";
    }

    echo "\n--- Seeding Completed ---\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
