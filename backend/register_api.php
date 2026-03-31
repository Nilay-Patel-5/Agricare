<?php
require_once __DIR__ . '/security_headers.php';
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$raw  = file_get_contents('php://input');
$data = json_decode($raw ?: '', true);

if ((!$data || !is_array($data)) && !empty($_POST)) {
    $data = $_POST;
}

if (!$data || !is_array($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No registration data provided']);
    exit;
}

// Valid districts and languages
$validDistricts = [
    'Ahmedabad', 'Amreli', 'Anand', 'Arvalli', 'Banaskantha', 'Bharuch', 'Bhavnagar',
    'Botad', 'Chhota Udaipur', 'Dahod', 'Dang', 'Devbhoomi Dwarka', 'Gandhinagar',
    'Gir Somnath', 'Jamnagar', 'Junagadh', 'Kheda', 'Kutch', 'Mahisagar', 'Mehsana',
    'Morbi', 'Narmada', 'Navsari', 'Panchmahal', 'Patan', 'Porbandar', 'Rajkot',
    'Sabarkantha', 'Surat', 'Surendranagar', 'Tapi', 'Vadodara', 'Valsad'
];
$validLangs = ['en', 'gu', 'hi'];

try {
    $pdo = Database::getConnection();

    // Sanitize & extract
    $name     = trim(strip_tags($data['full_name'] ?? $data['name'] ?? ''));
    $email    = trim($data['email'] ?? '');
    $phone    = trim($data['phone_no'] ?? $data['phone'] ?? '');
    $district = trim(strip_tags($data['district'] ?? ''));
    $city     = trim(strip_tags($data['city'] ?? ''));
    $pincode  = trim($data['pincode'] ?? '');
    $pref_lang = trim($data['pref_lang'] ?? '');
    $pref_lang = trim($data['pref_lang'] ?? '');
    $pin      = trim($data['pin'] ?? '');

    // Role check
    if (($data['role'] ?? 'farmer') !== 'farmer') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Only farmer registration is allowed.']);
        exit;
    }

    // Required fields
    if (!$name || !$email || !$phone || !$district || !$city || !$pincode || !$pref_lang || !$pin) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Full name: Indian format with at least first and last name, only letters and spaces
    if (
        strlen($name) < 3 ||
        strlen($name) > 60 ||
        !preg_match('/^[\p{L}]+(?:\s+[\p{L}]+)+$/u', $name)
    ) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Full name must be in Indian name format using only letters and spaces, like "Garv Patel".']);
        exit;
    }

    // Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }

    // Phone: exactly 10 digits
    if (!preg_match('/^\d{10}$/', $phone)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Phone number must be exactly 10 digits.']);
        exit;
    }

    // District: must be from allowed list
    if (!in_array($district, $validDistricts, true)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please select a valid district.']);
        exit;
    }

    // City: letters, spaces, hyphens, brackets — max 60 chars
    if (!preg_match('/^[\p{L}0-9\s\-().\/]{2,60}$/u', $city)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please select a valid city.']);
        exit;
    }

    // Pincode: exactly 6 digits
    if (!preg_match('/^\d{6}$/', $pincode)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Pincode must be exactly 6 digits.']);
        exit;
    }

    // Preferred language
    if (!in_array($pref_lang, $validLangs, true)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please select a valid language.']);
        exit;
    }

    // PIN: exactly 6 digits
    if (!preg_match('/^\d{6}$/', $pin)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'PIN must be exactly 6 digits.']);
        exit;
    }

    // Duplicate check
    $check = $pdo->prepare("SELECT id FROM farmers WHERE (phone IS NOT NULL AND phone = ?) OR (email IS NOT NULL AND email = ?)");
    $check->execute([$phone, $email]);
    if ($check->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Phone or email already registered.']);
        exit;
    }

    // Insert into farmers table
    $stmt = $pdo->prepare("
        INSERT INTO farmers (name, email, phone, district, city, pincode, pin, pref_lang)
        VALUES (:name, :email, :phone, :district, :city, :pincode, :pin, :lang)
    ");
    $result = $stmt->execute([
        ':name'     => $name,
        ':email'    => $email,
        ':phone'    => $phone,
        ':district' => $district,
        ':city'     => $city,
        ':pincode'  => $pincode,
        ':pin'      => password_hash($pin, PASSWORD_DEFAULT),
        ':lang'     => $pref_lang,
    ]);
 
    if ($result) {
        $newId = $pdo->lastInsertId();
        $ustmt = $pdo->prepare("SELECT id, name, pref_lang FROM farmers WHERE id = ?");
        $ustmt->execute([$newId]);
        $userRow = $ustmt->fetch();
        if ($userRow) $userRow['role'] = 'farmer'; // Add virtual role for client
        echo json_encode(['success' => true, 'message' => 'Registration successful!', 'user' => $userRow]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error during registration.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}
