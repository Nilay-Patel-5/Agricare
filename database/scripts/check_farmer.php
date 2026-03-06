<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

data = json_decode(file_get_contents('php://input'), true);
$phone = $data['phone'] ?? '';
if (!$phone) {
    echo json_encode(['valid' => false]);
    exit;
}

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE phone = ? AND role = 'farmer'");
    $stmt->execute([$phone]);
    $count = (int) $stmt->fetchColumn();
    echo json_encode(['valid' => $count > 0]);
} catch (Exception $e) {
    echo json_encode(['valid' => false]);
}
