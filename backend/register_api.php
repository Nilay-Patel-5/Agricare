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
    if ($email === '') $email = null;
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
    if (!$name || !$phone || !$district || !$city || !$pincode || !$pref_lang || !$pin) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Advanced Full Name Validation: At least 2 words, each 2+ letters, exact spacing
    if (
        mb_strlen($name, 'UTF-8') < 3 ||
        mb_strlen($name, 'UTF-8') > 50 ||
        !preg_match('/^[\p{L}]{2,}(?:\s[\p{L}]{2,})+$/u', $name)
    ) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Name must contain at least a first and last name (each 2+ chars), using only valid letters and spaces.']);
        exit;
    }

    // Email Validation: Pattern Match & Limit
    if ($email && (strlen($email) < 5 || strlen($email) > 100 || !preg_match('/^[a-zA-Z0-9._]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/', $email))) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }

    // Phone: exactly 10 digits
    if (!preg_match('/^[6-9]\d{9}$/', $phone)) {
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

    // Pincode: exactly 6 digits & Regional Validation Check
    if (!preg_match('/^\d{6}$/', $pincode)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Pincode must be exactly 6 digits.']);
        exit;
    }
    
    // Cross-verify District boundaries with Pincode natively securely via Cache or API
    $cacheFile = __DIR__ . "/cache/pincodes/$pincode.json";
    $pincodeLookupDistricts = [];
    $isPincodeVerified = false;
    
    if (file_exists($cacheFile)) {
        $cacheData = file_get_contents($cacheFile);
        if ($cacheData) {
            $parsed = json_decode($cacheData, true);
            if ($parsed && isset($parsed['districts'])) {
                $pincodeLookupDistricts = $parsed['districts'];
            }
        }
    } else {
        // Fallback live check
        $ctx = stream_context_create(['http' => ['timeout' => 4]]); // fast timeout
        $apiUrl = "https://api.postalpincode.in/pincode/" . $pincode;
        $response = @file_get_contents($apiUrl, false, $ctx);
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data[0]['Status']) && $data[0]['Status'] === 'Success' && isset($data[0]['PostOffice'])) {
                foreach ($data[0]['PostOffice'] as $po) {
                    if (!empty($po['District']) && !in_array($po['District'], $pincodeLookupDistricts)) {
                        $pincodeLookupDistricts[] = $po['District'];
                    }
                }
                if (!is_dir(__DIR__ . '/cache/pincodes')) mkdir(__DIR__ . '/cache/pincodes', 0755, true);
                file_put_contents($cacheFile, json_encode(['districts' => $pincodeLookupDistricts]));
            }
        }
    }
    
    // If the lookup was totally empty, we skip the mismatch lockout so we don't accidentally block legitimate folks when the Indian Postal server burns down.
    if (!empty($pincodeLookupDistricts)) {
        $pincodeMatch = false;
        foreach($pincodeLookupDistricts as $d) {
            if (stripos($d, $district) !== false || stripos($district, $d) !== false) {
                $pincodeMatch = true;
                break;
            }
        }
        if (!$pincodeMatch) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Pincode operates under a different geographical district.']);
            exit;
        }
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
    $sql = "SELECT id FROM farmers WHERE phone = ?";
    $params = [$phone];
    if ($email !== null) {
        $sql .= " OR email = ?";
        $params[] = $email;
    }
    $check = $pdo->prepare($sql);
    $check->execute($params);
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
        if ($userRow) {
            $userRow['role'] = 'farmer'; // Add virtual role for client
            session_start();
            $_SESSION['user_id'] = $userRow['id'];
            $_SESSION['user_name'] = $userRow['name'];
            $_SESSION['user_role'] = 'farmer';
        }
        echo json_encode(['success' => true, 'message' => 'Registration successful!', 'user' => $userRow]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error during registration.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}
