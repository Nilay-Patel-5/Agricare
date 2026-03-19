<?php
require 'backend/db.php';
try {
    $pdo = Database::getConnection();
    echo "Connected successfully.\n";
    
    $sql = "
    ALTER TABLE users 
    ADD COLUMN IF NOT EXISTS user_id VARCHAR(50) UNIQUE,
    ADD COLUMN IF NOT EXISTS city VARCHAR(100),
    ADD COLUMN IF NOT EXISTS pincode VARCHAR(10),
    ADD COLUMN IF NOT EXISTS pin CHAR(6),
    ADD COLUMN IF NOT EXISTS pref_lang VARCHAR(10) DEFAULT 'en';

    CREATE INDEX IF NOT EXISTS idx_users_phone ON users(phone);
    CREATE INDEX IF NOT EXISTS idx_users_user_id ON users(user_id);
    ";
    
    $pdo->exec($sql);
    echo "Users table updated successfully with all columns.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
