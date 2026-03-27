<?php
header('Content-Type: application/json');
require_once __DIR__ . '/security_headers.php';
require_once __DIR__ . '/db.php';

try {
    $pdo = Database::getConnection();

    /* Read filters from frontend */
    $input = json_decode(file_get_contents("php://input"), true);
    $categoryFilter = $input['category'] ?? 'All';
    $search = $input['search'] ?? '';

    $query = "SELECT * FROM subsidies WHERE 1=1";
    $params = [];

    if ($categoryFilter !== 'All') {
        $query .= " AND category = :category";
        $params['category'] = $categoryFilter;
    }

    if (!empty($search)) {
        $query .= " AND (
            name ILIKE :search OR 
            name_gu ILIKE :search OR 
            name_hi ILIKE :search OR 
            description ILIKE :search OR 
            description_gu ILIKE :search OR 
            description_hi ILIKE :search
        )";
        $params['search'] = "%$search%";
    }

    $query .= " ORDER BY last_updated DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll();

    echo json_encode($results);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
