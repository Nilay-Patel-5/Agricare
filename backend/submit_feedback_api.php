<?php
require_once __DIR__ . '/security_headers.php';
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'farmer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login as a farmer.']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || !isset($data['message']) || empty(trim($data['message']))) {
    echo json_encode(['success' => false, 'message' => 'Message is required.']);
    exit;
}

try {
    $pdo = Database::getConnection();
    
    $userId = $_SESSION['user_id'];
    $subject = trim($data['subject'] ?? 'General Feedback');
    $message = trim($data['message']);
    $rating = isset($data['rating']) ? (int)$data['rating'] : null;

    if ($rating !== null && ($rating < 1 || $rating > 5)) {
        echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5.']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO feedbacks (user_id, subject, message, rating) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([$userId, $subject, $message, $rating]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Feedback submitted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit feedback.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
