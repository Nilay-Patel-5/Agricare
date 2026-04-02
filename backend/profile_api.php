<?php
require_once __DIR__ . '/security_headers.php';
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// For GETRequests, data might come from query params
if ($method === 'GET') {
    $data = $_GET;
}

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

$userId = $data['id'];

try {
    $pdo = Database::getConnection();

    if ($method === 'GET') {
        // Fetch profile
        $stmt = $pdo->prepare("SELECT id, name, email, phone, district, city, pincode, pref_lang FROM farmers WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } elseif ($method === 'POST') {
        // Update profile
        $name      = trim(strip_tags($data['name'] ?? ''));
        $email     = trim($data['email'] ?? '');
        if ($email === '') $email = null;
        $phone     = trim($data['phone'] ?? '');
        $district  = trim(strip_tags($data['district'] ?? ''));
        $city      = trim(strip_tags($data['city'] ?? ''));
        $pincode   = trim($data['pincode'] ?? '');
        $pref_lang = trim($data['pref_lang'] ?? '');
        $pin       = trim($data['pin'] ?? '');

        // Basic Validation (reusing logic from register_api.php)
        if (!$name || !$phone || !$district || !$city || !$pincode || !$pref_lang) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'All fields except PIN and Email are required.']);
            exit;
        }

        // Phone: exactly 10 digits
        if (!preg_match('/^\d{10}$/', $phone)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Phone number must be exactly 10 digits.']);
            exit;
        }

        // Pincode: exactly 6 digits
        if (!preg_match('/^\d{6}$/', $pincode)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Pincode must be exactly 6 digits.']);
            exit;
        }

        // Duplicate check (excluding current user)
        $sqlC = "SELECT id FROM farmers WHERE (phone = ?";
        $paramsC = [$phone];
        if ($email !== null) {
            $sqlC .= " OR email = ?";
            $paramsC[] = $email;
        }
        $sqlC .= ") AND id != ?";
        $paramsC[] = $userId;

        $check = $pdo->prepare($sqlC);
        $check->execute($paramsC);
        if ($check->fetch()) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Phone or email already registered by another user.']);
            exit;
        }

        // Start building UPDATE query
        $sql = "UPDATE farmers SET name = ?, email = ?, phone = ?, district = ?, city = ?, pincode = ?, pref_lang = ?";
        $params = [$name, $email, $phone, $district, $city, $pincode, $pref_lang];

        if ($pin) {
            if (!preg_match('/^\d{6}$/', $pin)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'PIN must be exactly 6 digits.']);
                exit;
            }
            $sql .= ", pin = ?";
            $params[] = password_hash($pin, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = ?";
        $params[] = $userId;

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute($params);

        if ($result) {
            // Return updated user info (simplified) to sync with client
            echo json_encode([
                'success' => true, 
                'message' => 'Profile updated successfully!',
                'user' => [
                    'id' => $userId,
                    'name' => $name,
                    'pref_lang' => $pref_lang,
                    'role' => 'farmer'
                ]
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
