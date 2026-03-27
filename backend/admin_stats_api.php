<?php
header('Content-Type: application/json');
require_once __DIR__ . '/security_headers.php';
require_once __DIR__ . '/db.php';

// Auth check: only admin role allowed
$user = json_decode($_COOKIE['agricare_user'] ?? '{}', true);
if (($user['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden.']);
    exit;
}

try {
    $pdo = Database::getConnection();

    $farmers   = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'farmer'")->fetchColumn();
    $subsidies = $pdo->query("SELECT COUNT(*) FROM subsidies")->fetchColumn();
    $markets   = $pdo->query("SELECT COUNT(DISTINCT state) FROM market_prices")->fetchColumn();
    $scans     = $pdo->query("SELECT COUNT(*) FROM ai_scans")->fetchColumn();

    // Only expose non-PII fields
    $stmt = $pdo->query("SELECT id, name, district, phone, role, created_at FROM users ORDER BY created_at DESC LIMIT 5");
    $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'farmers'     => (int) $farmers,
        'subsidies'   => (int) $subsidies,
        'markets'     => (int) $markets,
        'scans'       => (int) $scans,
        'recentUsers' => $recentUsers,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error.']);
}
