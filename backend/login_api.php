<?php
require_once __DIR__ . '/security_headers.php';
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || !is_array($data)) {
    echo json_encode(['success' => false, 'message' => 'No login data provided']);
    exit;
}

try {
    $pdo = Database::getConnection();

    $role = $data['role'] ?? 'farmer';
    $identifier = $data['identifier'] ?? ''; // Phone or Email
    $password = $data['password'] ?? '';     // PIN or Password

    if (!$identifier || !$password) {
        echo json_encode(['success' => false, 'message' => 'Identification and credentials are required.']);
        exit;
    }

    if ($role === 'farmer') {
        // Farmer Login: Uses Phone and 6-digit PIN
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ? AND role = 'farmer'");
        $stmt->execute([$identifier]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['pin'])) {
            echo json_encode(['success' => true, 'user' => [
                'id' => $user['id'],
                'user_id' => $user['user_id'],
                'name' => $user['name'],
                'role' => 'farmer',
                'pref_lang' => $user['pref_lang']
            ]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid Phone number or PIN.']);
        }

    } else if ($role === 'admin') {
        // Admin Login: Uses Email and Hashed Password
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
        $stmt->execute([$identifier]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            echo json_encode(['success' => true, 'user' => [
                'id' => $admin['id'],
                'user_id' => $admin['user_id'],
                'name' => $admin['name'],
                'role' => 'admin',
                'pref_lang' => $admin['pref_lang']
            ]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid Admin credentials.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid role selected.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}
