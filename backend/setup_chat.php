<?php
// backend/setup_chat.php - One-time setup for Chat system
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/chat_context.php';

try {
    $pdo = Database::getConnection();
    echo "Connected to database.\n";
    
    echo "Ensuring chat schema...\n";
    chat_ensure_schema($pdo);
    echo "Chat schema ensured successfully.\n";

    // Add additional index for market_prices if not exists
    echo "Optimizing market_prices indices...\n";
    // Since arrival_date is a string 'DD/MM/YYYY', we can't easily index it for sorting as a date 
    // without a functional index. 
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_market_prices_arrival_date_str ON market_prices(arrival_date)");
    
    echo "Setup complete.\n";
} catch (Throwable $e) {
    echo "Setup failed: " . $e->getMessage() . "\n";
    exit(1);
}
