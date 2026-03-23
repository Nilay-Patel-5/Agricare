<?php
// backend/admin_stats_api.php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

try {
    $pdo = Database::getConnection();

    // 1. Total Farmers
    $farmers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'farmer'")->fetchColumn();

    // 2. Active Programs (subsidies)
    $subsidies = $pdo->query("SELECT COUNT(*) FROM subsidies")->fetchColumn();

    // 3. Mandi Nodes (markets)
    $markets = $pdo->query("SELECT COUNT(DISTINCT state) FROM market_prices")->fetchColumn();

    // 4. Neural Load Scans (the new ai_scans table)
    $scans = $pdo->query("SELECT COUNT(*) FROM ai_scans")->fetchColumn();

    // 5. Recent Users
    $stmt = $pdo->query("SELECT id, name, phone, district, role, created_at FROM users ORDER BY created_at DESC LIMIT 5");
    $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'farmers' => (int)$farmers,
        'subsidies' => (int)$subsidies,
        'markets' => (int)$markets,
        'scans' => (int)$scans,
        'recentUsers' => $recentUsers
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
