<?php
require_once __DIR__ . '/security_headers.php';
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Admin access required.']);
    exit;
}

try {
    $pdo = Database::getConnection();
    
    // Join with farmers table to get the name and phone of the person who gave feedback
    $stmt = $pdo->prepare("
        SELECT f.id, f.subject, f.message, f.rating, f.created_at, 
               u.name as farmer_name, u.phone as farmer_phone
        FROM feedbacks f
        LEFT JOIN farmers u ON f.user_id = u.id
        ORDER BY f.created_at DESC
    ");
    $stmt->execute();
    $feedbacks = $stmt->fetchAll();

    echo json_encode(['success' => true, 'feedbacks' => $feedbacks]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
