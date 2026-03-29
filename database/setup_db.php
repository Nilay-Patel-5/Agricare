<?php
/**
 * Agricare Database Setup Script
 * Use this to initialize or update your PostgreSQL database schema.
 */

require_once __DIR__ . '/../backend/db.php';

echo "--- Agricare Database Setup ---\n";

try {
    $pdo = Database::getConnection();
    echo "Connected to database successfully.\n";

    $schemaPath = __DIR__ . '/schema.sql';
    if (!file_exists($schemaPath)) {
        throw new Exception("Schema file not found at: $schemaPath");
    }

    $sql = file_get_contents($schemaPath);
    echo "Read schema.sql (" . strlen($sql) . " bytes).\n";

    // Split SQL by semicolons, but be careful of semicolons inside strings or functions.
    // For this specific schema, a simple split or executing the whole block works.
    // Postgres supports multiple statements in one exec() call.
    
    echo "Executing schema...\n";
    $pdo->exec($sql);
    
    echo "Database schema applied successfully!\n";
    
    // List tables as verification
    echo "\nTables in database:\n";
    $stmt = $pdo->query("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['tablename'] . "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
