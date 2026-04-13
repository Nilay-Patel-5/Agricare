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
    session_start();

    $captchaVal = $data['captcha'] ?? '';
    if (empty($_SESSION['captcha_code']) || strcasecmp($_SESSION['captcha_code'], $captchaVal) !== 0) {
        unset($_SESSION['captcha_code']);
        echo json_encode(['success' => false, 'message' => 'Invalid CAPTCHA code. Please try again.']);
        exit;
    }
    unset($_SESSION['captcha_code']); // Clear it once used successfully

    $pdo = Database::getConnection();

    $identifier = trim($data['identifier'] ?? ''); // Phone or Email
    $password = $data['password'] ?? '';     // PIN or Password

    if (!$identifier || !$password) {
        echo json_encode(['success' => false, 'message' => 'Identification and credentials are required.']);
        exit;
    }

    $isAuthenticated = false;
    $userData = null;

    if (preg_match('/^[0-9]+$/', $identifier)) {
        // Numeric -> try Farmer by Phone
        if (preg_match('/^[6-9]\d{9}$/', $identifier)) {
             $stmt = $pdo->prepare("SELECT * FROM farmers WHERE phone = ?");
             $stmt->execute([$identifier]);
             $user = $stmt->fetch();
             
             if ($user && password_verify($password, $user['pin'])) {
                 $isAuthenticated = true;
                 $userData = [
                     'id' => $user['id'],
                     'name' => $user['name'],
                     'role' => 'farmer',
                     'pref_lang' => $user['pref_lang'] ?? 'en',
                     'district' => $user['district'] ?? '',
                     'city' => $user['city'] ?? ''
                 ];
             }
        }
    } elseif (preg_match('/^[a-zA-Z0-9._%+-]+@agricare\.admin$/', $identifier)) {
        // Admin Domain -> try Admin
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$identifier]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $isAuthenticated = true;
            $userData = ['id' => $admin['id'], 'name' => $admin['name'], 'role' => 'admin', 'pref_lang' => 'en'];
        }
    } elseif (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        // General Email -> try Farmer by Email
        $stmt = $pdo->prepare("SELECT * FROM farmers WHERE email = ?");
        $stmt->execute([$identifier]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['pin'])) {
            $isAuthenticated = true;
            $userData = ['id' => $user['id'], 'name' => $user['name'], 'role' => 'farmer', 'pref_lang' => $user['pref_lang'] ?? 'en'];
        }
    }

    if ($isAuthenticated) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_name'] = $userData['name'];
        $_SESSION['user_role'] = $userData['role'];
        echo json_encode(['success' => true, 'user' => $userData]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}
