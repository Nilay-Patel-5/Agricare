<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No registration data provided']);
    exit;
}

$pdo = Database::getConnection();

try {
    $name = $data['name'] ?? '';
    $phone = $data['phone'] ?? '';
    $dob = $data['dob'] ?? '';
    $district = $data['district'] ?? '';
    $role = 'farmer'; // Registration is ONLY allowed for farmers. 

    if (!$name || !$phone || !$dob) {
        echo json_encode(['success' => false, 'message' => 'Name, Phone, and Date of Birth are mandatory.']);
        exit;
    }

    // Check if phone already registered
    $check = $pdo->prepare("SELECT id FROM users WHERE phone = ?");
    $check->execute([$phone]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'This phone number is already registered.']);
        exit;
    }

    // Insert new Farmer
    $stmt = $pdo->prepare("INSERT INTO users (name, phone, dob, role, district) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $phone, $dob, $role, $district])) {
        $newId = $pdo->lastInsertId();
        // fetch the new user row (excluding sensitive fields)
        $ustmt = $pdo->prepare("SELECT id,name,phone,district,role FROM users WHERE id = ?");
        $ustmt->execute([$newId]);
        $userRow = $ustmt->fetch();
        echo json_encode(['success' => true, 'message' => 'Registration successful!', 'user' => $userRow]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error during registration.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
