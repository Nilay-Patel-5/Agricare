<?php
require_once __DIR__ . '/security_headers.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$pincode = trim(strip_tags($_GET['pincode'] ?? ''));

if (!preg_match('/^\d{6}$/', $pincode)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid pincode format.']);
    exit;
}

$cacheDir = __DIR__ . '/cache/pincodes';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

$cacheFile = "$cacheDir/$pincode.json";

if (file_exists($cacheFile)) {
    // Read from cache
    $cacheData = file_get_contents($cacheFile);
    if ($cacheData) {
        $result = json_decode($cacheData, true);
        if ($result && isset($result['districts'])) {
            echo json_encode(['success' => true, 'cached' => true, 'districts' => $result['districts']]);
            exit;
        }
    }
}

// Ensure safe timeout
$ctx = stream_context_create(['http' => ['timeout' => 5]]);
$apiUrl = "https://api.postalpincode.in/pincode/" . $pincode;

$response = @file_get_contents($apiUrl, false, $ctx);

if ($response) {
    $data = json_decode($response, true);
    if (isset($data[0]['Status']) && $data[0]['Status'] === 'Success' && isset($data[0]['PostOffice'])) {
        $districts = [];
        foreach ($data[0]['PostOffice'] as $po) {
            if (!empty($po['District']) && !in_array($po['District'], $districts)) {
                $districts[] = $po['District'];
            }
        }
        
        $cachePayload = json_encode(['districts' => $districts]);
        file_put_contents($cacheFile, $cachePayload); // Write to cache!
        
        echo json_encode(['success' => true, 'cached' => false, 'districts' => $districts]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Pincode not found.']);
        exit;
    }
}

// Fallback if API fails
http_response_code(503);
echo json_encode(['success' => false, 'message' => 'Postal API unavailable.']);
exit;
