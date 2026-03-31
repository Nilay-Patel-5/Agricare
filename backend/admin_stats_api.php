<?php
header('Content-Type: application/json');
require_once __DIR__ . '/security_headers.php';
require_once __DIR__ . '/db.php';

require_once __DIR__ . '/demo_admin_data.php';

// Auth check: only admin role allowed
$userDataHeader = $_SERVER['HTTP_X_USER_DATA'] ?? '';
$user = $userDataHeader ? json_decode($userDataHeader, true) : json_decode($_COOKIE['agricare_user'] ?? '{}', true);

if (($user['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden.']);
    exit;
}

try {
    $pdo = Database::getConnection();

    // Helper to safely count rows in a table (returns 0 if table missing)
    function countTable($pdo, $sql) {
        try {
            return (int) $pdo->query($sql)->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    $farmers   = countTable($pdo, "SELECT COUNT(*) FROM farmers");
    $admins    = countTable($pdo, "SELECT COUNT(*) FROM admins");
    $subsidies = countTable($pdo, "SELECT COUNT(*) FROM subsidies");
    if ($subsidies === 0) {
        $subsidies = count(admin_demo_subsidies());
    }
    $markets   = countTable($pdo, "SELECT COUNT(DISTINCT market) FROM market_prices");
    $scans     = countTable($pdo, "SELECT COUNT(*) FROM ai_scans");

    // Attempt to fetch recent users, fallback to empty array if fails
    $recentUsers = [];
    try {
        $stmt = $pdo->query("SELECT id, name, district, phone, created_at FROM farmers ORDER BY created_at DESC LIMIT 5");
        $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {}

    echo json_encode([
        'farmers'     => $farmers,
        'admins'      => $admins,
        'subsidies'   => $subsidies,
        'markets'     => $markets,
        'scans'       => $scans,
        'recentUsers' => $recentUsers,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
