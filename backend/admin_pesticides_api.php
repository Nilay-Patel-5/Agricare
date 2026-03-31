<?php
header('Content-Type: application/json');
require_once __DIR__ . '/security_headers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/demo_admin_data.php';

// Auth check: only admin role allowed
$userDataHeader = $_SERVER['HTTP_X_USER_DATA'] ?? '';
$user = $userDataHeader ? json_decode($userDataHeader, true) : json_decode($_COOKIE['agricare_user'] ?? '{}', true);
if (($user['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden.']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("
                SELECT m.id as mapping_id, m.pest_name, m.effectiveness,
                       p.id as pesticide_id, p.name_en as name, p.brand, p.price_range, p.target_pests_en as target_pests, p.usage_en as usage_instructions
                FROM pest_pesticide_mapping m
                JOIN pesticides p ON m.pesticide_id = p.id
                ORDER BY m.pest_name ASC, m.effectiveness DESC
            ");
            $mappings = $stmt->fetchAll();

            $stmtAllP = $pdo->query("SELECT id, name_en as name, brand FROM pesticides ORDER BY name_en ASC");
            $allPesticides = $stmtAllP->fetchAll();
        } catch (Throwable $dbError) {
            $mappings = [];
            $allPesticides = [];
        }

        if (!$mappings) {
            $mappings = admin_demo_pest_mappings();
        }
        if (!$allPesticides) {
            $allPesticides = array_map(static fn(array $row): array => [
                'id' => $row['id'],
                'name' => $row['name'],
                'brand' => $row['brand'],
            ], admin_demo_pesticides());
        }

        echo json_encode(['status' => 'success', 'mappings' => $mappings, 'pesticides' => $allPesticides]);

    } elseif ($method === 'POST') {
        $pdo = Database::getConnection();
        $data   = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'] ?? '';

        if ($action === 'add_pesticide') {
            $name  = trim($data['name'] ?? '');
            $brand = trim($data['brand'] ?? '');
            if (!$name || !$brand) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Name and brand are required.']);
                exit;
            }
            $stmt = $pdo->prepare("INSERT INTO pesticides (name_en, name_gu, name_hi, brand, target_pests_en, price_range, usage_en) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $name, $name, $brand, trim($data['target_pests'] ?? ''), trim($data['price_range'] ?? ''), trim($data['usage_instructions'] ?? '')]);
            echo json_encode(['status' => 'success', 'message' => 'Pesticide added.']);

        } elseif ($action === 'add_mapping') {
            $pestName      = trim($data['pest_name'] ?? '');
            $pesticideId   = filter_var($data['pesticide_id'] ?? '', FILTER_VALIDATE_INT);
            $effectiveness = trim($data['effectiveness'] ?? '');
            if (!$pestName || !$pesticideId) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'pest_name and pesticide_id are required.']);
                exit;
            }
            $stmt = $pdo->prepare("INSERT INTO pest_pesticide_mapping (pest_name, pesticide_id, effectiveness) VALUES (?, ?, ?)");
            $stmt->execute([$pestName, $pesticideId, $effectiveness]);
            echo json_encode(['status' => 'success', 'message' => 'Mapping created.']);

        } elseif ($action === 'delete_mapping') {
            $id = filter_var($data['id'] ?? '', FILTER_VALIDATE_INT);
            if (!$id) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Valid id is required.']);
                exit;
            }
            $stmt = $pdo->prepare("DELETE FROM pest_pesticide_mapping WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Mapping removed.']);

        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error.']);
}
