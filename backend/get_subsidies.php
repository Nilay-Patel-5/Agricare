<?php
header('Content-Type: application/json');
require_once __DIR__ . '/security_headers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/demo_admin_data.php';
require_once __DIR__ . '/subsidy_support.php';

try {
    /* Read filters from frontend */
    $input = json_decode(file_get_contents("php://input"), true);
    $categoryFilter = $input['category'] ?? 'All';
    $search = $input['search'] ?? '';
    $results = [];

    try {
        $pdo = Database::getConnection();
        $results = subsidy_select_rows($pdo, (string) $categoryFilter, (string) $search);
    } catch (Throwable $dbError) {
        $results = [];
    }

    if (!$results) {
        $results = admin_demo_subsidies();
        if ($categoryFilter !== 'All' && $categoryFilter !== '') {
            $results = array_values(array_filter($results, static fn(array $row): bool => strcasecmp($row['category'] ?? '', (string) $categoryFilter) === 0));
        }
        if ($search !== '') {
            $needle = mb_strtolower((string) $search);
            $results = array_values(array_filter($results, static function (array $row) use ($needle): bool {
                $haystack = mb_strtolower(($row['name'] ?? '') . ' ' . ($row['description'] ?? ''));
                return str_contains($haystack, $needle);
            }));
        }
    }

    echo json_encode(array_values($results));
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
