<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/chat_context.php';

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("SELECT DISTINCT pest_name FROM pest_pesticide_mapping ORDER BY pest_name LIMIT 100");
    $pests = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "--- EXISTING PESTS IN DATABASE ---\n";
    foreach ($pests as $p) {
        echo "- $p\n";
    }
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}

echo "\n--- SYSTEM DEBUG ---\n";
if (function_exists('chat_normalize_pest_name')) {
    echo "chat_normalize_pest_name exists.\n";
} else {
    echo "chat_normalize_pest_name DOES NOT exist.\n";
}
