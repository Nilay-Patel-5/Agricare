<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No registration data provided']);
    exit;
}

try {
    $pdo = Database::getConnection();

    // Basic Info
    $user_id = $data['user_id'] ?? '';
    $name = $data['full_name'] ?? $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone_no'] ?? $data['phone'] ?? '';
    $role = $data['role'] ?? 'farmer';
    $dob = $data['dob'] ?? null;
    $pref_lang = $data['pref_lang'] ?? 'en';

    // Farmer-Specific
    $district = $data['district'] ?? '';
    $city = $data['city'] ?? '';
    $pincode = $data['pincode'] ?? '';
    $pin = $data['pin'] ?? '';

    // Admin-Specific
    $password = $data['password'] ?? '';

    // Basic Validation
    if (!$name || !$user_id || ($role === 'farmer' && (!$phone || !$pin)) || ($role === 'admin' && (!$email || !$password))) {
        echo json_encode(['success' => false, 'message' => 'Mandatory fields are missing.']);
        exit;
    }

    // Check if user_id or unique contact exists
    $check = $pdo->prepare("SELECT id FROM users WHERE user_id = ? OR (phone != '' AND phone = ?) OR (email != '' AND email = ?)");
    $check->execute([$user_id, $phone, $email]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'User ID, phone or email already registered.']);
        exit;
    }

    // For admins, hash the password. For farmers, keep the PIN (or hash it if preferred, but usually keep simple for farmers)
    $hashed_password = $role === 'admin' ? password_hash($password, PASSWORD_DEFAULT) : null;

    // Insert New User
    $sql = "INSERT INTO users (user_id, name, email, phone, dob, role, district, city, pincode, pin, password, pref_lang) 
            VALUES (:uid, :name, :email, :phone, :dob, :role, :district, :city, :pincode, :pin, :pwd, :lang)";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':uid' => $user_id,
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':dob' => $dob,
        ':role' => $role,
        ':district' => $district,
        ':city' => $city,
        ':pincode' => $pincode,
        ':pin' => $pin,
        ':pwd' => $hashed_password,
        ':lang' => $pref_lang
    ]);

    if ($result) {
        $newId = $pdo->lastInsertId();
        $ustmt = $pdo->prepare("SELECT id, user_id, name, role, pref_lang FROM users WHERE id = ?");
        $ustmt->execute([$newId]);
        $userRow = $ustmt->fetch();
        echo json_encode(['success' => true, 'message' => 'Registration successful!', 'user' => $userRow]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error during registration.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
