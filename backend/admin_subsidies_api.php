<?php
require_once __DIR__ . '/security_headers.php';
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/subsidy_support.php';

session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden. Admin access required.']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid payload.']);
        exit;
    }

    $pdo = Database::getConnection();
    $action = trim((string) ($data['action'] ?? 'add'));

    if ($action === 'delete') {
        $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Valid subsidy id is required.']);
            exit;
        }

        subsidy_delete_row($pdo, (int) $id);
        echo json_encode(['success' => true, 'message' => 'Subsidy deleted successfully.']);
        exit;
    }

    $name = trim((string) ($data['name'] ?? ''));
    $category = trim((string) ($data['category'] ?? ''));
    $description = trim((string) ($data['description'] ?? ''));
    $benefits = trim((string) ($data['benefits'] ?? ''));
    $eligibility = trim((string) ($data['eligibility'] ?? ''));
    $applyLink = trim((string) ($data['apply_link'] ?? ''));
    $status = trim((string) ($data['status'] ?? 'Live'));

    if ($name === '' || $category === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Name and category are required.']);
        exit;
    }

    subsidy_insert_row($pdo, [
        'name' => $name,
        'category' => $category,
        'description' => $description,
        'benefits' => $benefits,
        'eligibility' => $eligibility,
        'apply_link' => $applyLink,
        'status' => $status !== '' ? $status : 'Live',
    ]);

    echo json_encode(['success' => true, 'message' => 'Subsidy added successfully.']);
} catch (Throwable $e) {
    $message = $e->getMessage();
    if ($message === 'Subsidy not found.' || $message === 'Valid subsidy id is required.') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $message]);
        exit;
    }

    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $message]);
}
