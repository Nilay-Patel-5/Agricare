<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No input data provided']);
    exit;
}

$role = $data['role'] ?? 'farmer';
$pdo = Database::getConnection();

try {
    if ($role === 'farmer') {
        $phone = $data['phone'] ?? '';
        $dob = $data['dob'] ?? '';

        if (!$phone || !$dob) {
            echo json_encode(['success' => false, 'message' => 'Phone and DOB are required']);
            exit;
        }

        // Specifically look for the 'farmer' record for this phone number
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ? AND role = 'farmer'");
        $stmt->execute([$phone]);
        $user = $stmt->fetch();

        if ($user) {
            if ($user['dob'] === $dob) {
                echo json_encode(['success' => true, 'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'phone' => $user['phone'],
                    'district' => $user['district'],
                    'role' => 'farmer'
                ]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid Date of Birth for your Farmer account']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Farmer account not found with this phone number']);
        }

    } else if ($role === 'admin') {
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            echo json_encode(['success' => false, 'message' => 'Email and Password are required']);
            exit;
        }

        // Specifically look for the 'admin' record for this email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            echo json_encode(['success' => true, 'user' => [
                'id' => $admin['id'],
                'name' => $admin['name'],
                'email' => $admin['email'],
                'role' => 'admin'
            ]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid admin credentials']);
        }
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
