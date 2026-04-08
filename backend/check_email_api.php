<?php
require_once __DIR__ . '/security_headers.php';
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = trim(strip_tags($_GET['e'] ?? ''));

if (!$email) {
    echo json_encode(['success' => false, 'exists' => false, 'message' => 'Empty email']);
    exit;
}

// Ensure the requested email fits valid structure roughly before querying db.
if (!preg_match('/^[a-zA-Z0-9._]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/', $email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'exists' => false, 'message' => 'Invalid email format']);
    exit;
}

try {
    $pdo = Database::getConnection();
    
    // Check farmers table for email duplicates
    $stmt = $pdo->prepare("SELECT id FROM farmers WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => true, 'exists' => true, 'message' => 'Email already registered']);
    } else {
        echo json_encode(['success' => true, 'exists' => false]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'exists' => false, 'message' => 'Database error']);
}
