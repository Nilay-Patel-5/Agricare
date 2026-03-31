<?php
// backend/migrate_to_unified_users.php
require_once __DIR__ . '/db.php';

echo "--- Starting Database Migration: Unified Users ---\n";

try {
    $pdo = Database::getConnection();

    // 1. Create Users Table if not exists
    echo "1. Ensuring 'users' table exists...\n";
    $pdo->exec("
        CREATE EXTENSION IF NOT EXISTS \"uuid-ossp\";
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            user_id UUID DEFAULT uuid_generate_v4(),
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE,
            phone VARCHAR(15) UNIQUE,
            dob DATE,
            role VARCHAR(20) NOT NULL DEFAULT 'farmer',
            district VARCHAR(50),
            city VARCHAR(50),
            pincode VARCHAR(10),
            pin TEXT, 
            password TEXT,
            pref_lang VARCHAR(5) DEFAULT 'en',
            created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // 2. Migrate Farmers
    $checkFarmers = $pdo->query("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'farmers')");
    if ($checkFarmers->fetchColumn()) {
        echo "2. Found 'farmers' table. Migrating data to 'users'...\n";
        $migrateFarmers = $pdo->prepare("
            INSERT INTO users (name, email, phone, district, city, pincode, pin, pref_lang, role, created_at, updated_at)
            SELECT f.name, f.email, f.phone, f.district, f.city, f.pincode, f.pin, f.pref_lang, 'farmer', 
                   COALESCE(f.created_at, CURRENT_TIMESTAMP), 
                   COALESCE(f.updated_at, CURRENT_TIMESTAMP)
            FROM farmers f
            WHERE NOT EXISTS (
                SELECT 1 FROM users u 
                WHERE (u.phone IS NOT NULL AND u.phone = f.phone) 
                   OR (u.email IS NOT NULL AND u.email = f.email)
            )
        ");
        $migrateFarmers->execute();
        $count = $migrateFarmers->rowCount();
        echo "   --- Migrated $count farmers.\n";
    }

    // 3. Migrate Admins
    $checkAdmins = $pdo->query("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'admins')");
    if ($checkAdmins->fetchColumn()) {
        echo "3. Found 'admins' table. Migrating data to 'users'...\n";
        $migrateAdmins = $pdo->prepare("
            INSERT INTO users (name, email, password, role, created_at, updated_at)
            SELECT name, email, password, 'admin', 
                   COALESCE(created_at, CURRENT_TIMESTAMP), 
                   COALESCE(updated_at, CURRENT_TIMESTAMP)
            FROM admins
            ON CONFLICT (email) DO NOTHING
        ");
        $migrateAdmins->execute();
        $count = $migrateAdmins->rowCount();
        echo "   --- Migrated $count admins.\n";
    }

    // 4. Update Sequence (PostgreSQL SERIAL fix)
    echo "4. Syncing ID sequences...\n";
    $pdo->exec("SELECT setval(pg_get_serial_sequence('users', 'id'), coalesce(max(id), 1)) FROM users;");

    echo "\n--- Migration Completed Successfully ---\n";
    echo "You can now safely login using the PHP APIs.\n";
    echo "Tip: Run 'php backend/get_schema.php' to verify the new schema.\n";

} catch (Exception $e) {
    echo "\n[ERROR] Migration Failed: " . $e->getMessage() . "\n";
}
