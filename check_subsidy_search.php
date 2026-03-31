<?php
require_once __DIR__ . '/backend/db.php';
try {
    $pdo = Database::getConnection();
    echo "--- SUBSIDY CHECK ---\n";
    $stmt = $pdo->query("SELECT title_en, description_en FROM subsidies WHERE description_en ILIKE '%irrigation%' LIMIT 5");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
