<?php
// backend/admin_pesticides_api.php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$pdo = Database::getConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        // Fetch all pests and their mappings
        $stmt = $pdo->query("
            SELECT m.id as mapping_id, m.pest_name, m.effectiveness, p.id as pesticide_id, p.name, p.brand, p.price_range 
            FROM pest_pesticide_mapping m
            JOIN pesticides p ON m.pesticide_id = p.id
            ORDER BY m.pest_name ASC, m.effectiveness DESC
        ");
        $mappings = $stmt->fetchAll();

        $stmtAllP = $pdo->query("SELECT id, name, brand FROM pesticides ORDER BY name ASC");
        $allPesticides = $stmtAllP->fetchAll();

        echo json_encode([
            'status' => 'success',
            'mappings' => $mappings,
            'pesticides' => $allPesticides
        ]);
    } 
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'] ?? '';

        if ($action === 'add_pesticide') {
            $stmt = $pdo->prepare("INSERT INTO pesticides (name, brand, target_pests, price_range, usage_instructions) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['name'], $data['brand'], $data['target_pests'], $data['price_range'], $data['usage_instructions']]);
            echo json_encode(['status' => 'success', 'message' => 'Pesticide added']);
        } 
        elseif ($action === 'add_mapping') {
            $stmt = $pdo->prepare("INSERT INTO pest_pesticide_mapping (pest_name, pesticide_id, effectiveness) VALUES (?, ?, ?)");
            $stmt->execute([$data['pest_name'], $data['pesticide_id'], $data['effectiveness']]);
            echo json_encode(['status' => 'success', 'message' => 'Mapping created']);
        }
        elseif ($action === 'delete_mapping') {
            $stmt = $pdo->prepare("DELETE FROM pest_pesticide_mapping WHERE id = ?");
            $stmt->execute([$data['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Mapping removed']);
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
