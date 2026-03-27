<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

try {
    $pdo = Database::getConnection();

    $api_key = getenv('DATA_GOV_API_KEY') ?: '';
    if (!$api_key) {
        http_response_code(500);
        echo json_encode(['error' => 'API key not configured.']);
        exit;
    }

    $url = "https://api.data.gov.in/resource/9ef84268-d588-465a-a308-a864a43d0070?api-key=$api_key&format=json&limit=20";

    $response = file_get_contents($url);
    if ($response === false) {
        http_response_code(502);
        echo json_encode(['error' => 'External API request failed.']);
        exit;
    }

    $data = json_decode($response, true);

    if (isset($data['records']) && is_array($data['records'])) {
        $stmt = $pdo->prepare("
            INSERT INTO market_prices (state, district, market, commodity, variety, grade, arrival_date, min_price, max_price, modal_price)
            VALUES (:state, :district, :market, :commodity, :variety, :grade, :arrival_date, :min_price, :max_price, :modal_price)
        ");

        $count = 0;
        foreach ($data['records'] as $record) {
            $stmt->execute([
                ':state'        => $record['state'] ?? 'Gujarat',
                ':district'     => $record['district'] ?? null,
                ':market'       => $record['market'] ?? null,
                ':commodity'    => $record['commodity'] ?? null,
                ':variety'      => $record['variety'] ?? null,
                ':grade'        => $record['grade'] ?? null,
                ':arrival_date' => $record['arrival_date'] ?? null,
                ':min_price'    => is_numeric($record['min_price'] ?? null) ? $record['min_price'] : 0,
                ':max_price'    => is_numeric($record['max_price'] ?? null) ? $record['max_price'] : 0,
                ':modal_price'  => is_numeric($record['modal_price'] ?? null) ? $record['modal_price'] : 0,
            ]);
            $count++;
        }

        echo json_encode(['success' => true, 'inserted' => $count]);
    } else {
        http_response_code(502);
        echo json_encode(['error' => 'API returned no records.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error.']);
}
