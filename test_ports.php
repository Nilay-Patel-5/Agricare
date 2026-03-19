<?php
require 'backend/db.php';
try {
    $pdo = Database::getConnection();
    echo "Connected via 5432 successfully.\n";
} catch (Exception $e) {
    echo "Port 5432 failed: " . $e->getMessage() . "\n";
    // Try 6543
    try {
        $config = [
            'host' => 'aws-1-ap-south-1.pooler.supabase.com',
            'db' => 'postgres',
            'user' => 'postgres.fnfqrectniyjpkyfkmal',
            'port' => '6543',
            'pass' => 'nrpsupabase7',
            'sslmode' => 'require'
        ];
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['db']};sslmode={$config['sslmode']}";
        $p = new PDO($dsn, $config['user'], $config['pass']);
        echo "Connected via 6543 successfully!\n";
    } catch (Exception $ex) {
        echo "Port 6543 failed: " . $ex->getMessage() . "\n";
    }
}
