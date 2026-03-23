<?php
require 'backend/db.php';
try {
    $pdo = Database::getConnection();
    echo "Connected successfully.\n";
    
    // Full Schema Update Logic
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        user_id VARCHAR(50) UNIQUE,
        name VARCHAR(255) NOT NULL,
        phone VARCHAR(20) UNIQUE,
        email VARCHAR(255) UNIQUE,
        password TEXT,
        pin VARCHAR(10),
        dob DATE,
        role VARCHAR(20) DEFAULT 'farmer',
        district VARCHAR(100),
        city VARCHAR(100),
        pincode VARCHAR(20),
        pref_lang VARCHAR(10) DEFAULT 'en',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    -- Ensure all columns exist
    ALTER TABLE users ADD COLUMN IF NOT EXISTS user_id VARCHAR(50);
    ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(20);
    ALTER TABLE users ADD COLUMN IF NOT EXISTS email VARCHAR(255);
    ALTER TABLE users ADD COLUMN IF NOT EXISTS password TEXT;
    ALTER TABLE users ADD COLUMN IF NOT EXISTS pin VARCHAR(10);
    ALTER TABLE users ADD COLUMN IF NOT EXISTS dob DATE;
    ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(20) DEFAULT 'farmer';
    ALTER TABLE users ADD COLUMN IF NOT EXISTS district VARCHAR(100);
    ALTER TABLE users ADD COLUMN IF NOT EXISTS city VARCHAR(100);
    ALTER TABLE users ADD COLUMN IF NOT EXISTS pincode VARCHAR(20);
    ALTER TABLE users ADD COLUMN IF NOT EXISTS pref_lang VARCHAR(10) DEFAULT 'en';
    ALTER TABLE users ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

    -- Update defaults
    UPDATE users SET pref_lang = 'en' WHERE pref_lang IS NULL OR pref_lang = '';
    UPDATE users SET role = 'farmer' WHERE role IS NULL OR role = '';
    
    -- Create Indexes
    CREATE UNIQUE INDEX IF NOT EXISTS idx_users_user_id_unique ON users(user_id);
    CREATE UNIQUE INDEX IF NOT EXISTS idx_users_phone_unique ON users(phone);
    CREATE UNIQUE INDEX IF NOT EXISTS idx_users_email_unique ON users(email);
    ";
    
    $pdo->exec($sql);
    echo "Database schema updated successfully.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
