<?php
require_once __DIR__ . '/security_headers.php';
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$userDataHeader = $_SERVER['HTTP_X_USER_DATA'] ?? '';
$user = $userDataHeader ? json_decode($userDataHeader, true) : json_decode($_COOKIE['agricare_user'] ?? '{}', true);
if (($user['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden.']);
    exit;
}

$pdo    = Database::getConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        // Fetch Farmers only for the Registry UI
        $stmtFarmers = $pdo->query("SELECT id, name, email, phone, district, city, pref_lang, created_at FROM farmers ORDER BY created_at DESC");
        $farmers = $stmtFarmers->fetchAll();

        echo json_encode([
            'success' => true, 
            'farmers' => $farmers
        ]);

    } elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id   = filter_var($data['id'] ?? 0, FILTER_VALIDATE_INT);
        $type = $data['type'] ?? 'farmer'; // 'farmer' or 'admin'

        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        if ($type === 'admin') {
            // Prevent deleting last admin if necessary, but for now just allow
            $pdo->prepare("DELETE FROM admins WHERE id = ?")->execute([$id]);
        } else {
            $pdo->prepare("DELETE FROM farmers WHERE id = ?")->execute([$id]);
        }
        
        echo json_encode(['success' => true]);

    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
