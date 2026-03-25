<?php
require_once __DIR__ . '/security_headers.php';
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$user = json_decode($_COOKIE['agricare_user'] ?? '{}', true);
if (($user['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden.']);
    exit;
}

$pdo    = Database::getConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT id, name, email, phone, district, city, pref_lang, role, created_at FROM users WHERE role = 'farmer' ORDER BY created_at DESC");
        echo json_encode(['success' => true, 'users' => $stmt->fetchAll()]);

    } elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id   = filter_var($data['id'] ?? 0, FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }
        // Prevent deleting admins
        $check = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $check->execute([$id]);
        $row = $check->fetch();
        if (!$row || $row['role'] === 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Cannot delete admin accounts.']);
            exit;
        }
        $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'farmer'")->execute([$id]);
        echo json_encode(['success' => true]);

    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error.']);
}
