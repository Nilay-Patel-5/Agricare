<?php
try {
    $ip = "2406:da1a:6b0:f608:aaa2:fb73:1a35:9838";
    $port = 6543;
    $user = "postgres";
    $pass = "nrpsupabase7";
    $db = "postgres";
    
    $dsn = "pgsql:host=[$ip];port=$port;dbname=$db;sslmode=require";
    echo "Testing connection to IP [$ip] on port $port...\n";
    $p = new PDO($dsn, $user, $pass);
    echo "CONNECTED SUCCESSFULLY TO IP!\n";
} catch (Exception $e) {
    echo "Connection to IP failed: " . $e->getMessage() . "\n";
}
