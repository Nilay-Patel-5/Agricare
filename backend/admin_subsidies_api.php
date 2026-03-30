<?php
require_once __DIR__ . '/security_headers.php';
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/subsidy_support.php';

$user = json_decode($_COOKIE['agricare_user'] ?? '{}', true);
if (($user['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden.']);
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

    $pdo = Database::getConnection();
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
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error.']);
}
