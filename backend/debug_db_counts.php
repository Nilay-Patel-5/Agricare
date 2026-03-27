<?php
require_once __DIR__ . '/db.php';
$pdo = Database::getConnection();
echo "--- COUNT PESTS ---\n";
echo "Pesticides count: " . $pdo->query("SELECT count(*) FROM pesticides")->fetchColumn() . "\n";
echo "Mappings count: " . $pdo->query("SELECT count(*) FROM pest_pesticide_mapping")->fetchColumn() . "\n";
echo "Samples from pesticides:\n";
print_r($pdo->query("SELECT name, brand FROM pesticides LIMIT 5")->fetchAll());
echo "Samples from mappings:\n";
print_r($pdo->query("SELECT pest_name, pesticide_id FROM pest_pesticide_mapping LIMIT 5")->fetchAll());
