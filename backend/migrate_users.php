<?php
// backend/migrate_users.php - One-time migration script
require_once __DIR__ . '/db.php';

try {
    $pdo = Database::getConnection();
    $pdo->beginTransaction();

    echo "1. Creating 'admins' table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admins (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE,
            password TEXT,
            created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
        );
    ");

    echo "2. Migrating admin data from 'users' to 'admins'...\n";
    // Using a simple INSERT SELECT. Note: We don't copy role as it's implicit now.
    $pdo->exec("
        INSERT INTO admins (name, email, password, created_at, updated_at)
        SELECT name, email, password, created_at, updated_at 
        FROM users 
        WHERE role = 'admin'
        ON CONFLICT (email) DO NOTHING;
    ");

    echo "3. Renaming 'users' table to 'farmers'...\n";
    // Check if farmers already exists to avoid errors on re-run
    $check = $pdo->query("SELECT to_regclass('public.farmers')")->fetchColumn();
    if (!$check) {
        $pdo->exec("ALTER TABLE users RENAME TO farmers;");
    } else {
        echo "   (Skipping rename, 'farmers' table already exists)\n";
    }

    echo "4. Cleaning up 'farmers' table...\n";
    // Delete any admins left in the farmers table
    $pdo->exec("DELETE FROM farmers WHERE role = 'admin'");

    // Drop forbidden columns from farmers
    $columnsToDrop = ['user_id', 'dob', 'password', 'role'];
    foreach ($columnsToDrop as $col) {
        try {
            $pdo->exec("ALTER TABLE farmers DROP COLUMN IF EXISTS $col CASCADE;");
            echo "   Dropped column '$col' from farmers.\n";
        } catch (Exception $e) {
            echo "   Could not drop '$col' (might already be gone): " . $e->getMessage() . "\n";
        }
    }

    echo "5. Verifying schema...\n";
    $pdo->commit();
    echo "MIGRATION SUCCESSFUL.\n";

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "ERROR DURING MIGRATION: " . $e->getMessage() . "\n";
}
