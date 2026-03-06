<?php

require_once __DIR__ . '/db.php';

try {
    $pdo = Database::getConnection();

    // API URL (replace API KEY)
    $api_key = "579b464db66ec23bdd0000012b67c7ab775a420174e338ebaf35bb0c";
    $url = "https://api.data.gov.in/resource/9ef84268-d588-465a-a308-a864a43d0070?api-key=$api_key&format=json&limit=20";

    $response = file_get_contents($url);
    if ($response === false) {
        die("API request failed.");
    }

    $data = json_decode($response, true);

    if (isset($data['records']) && is_array($data['records'])) {
        // Clear recent data to prevent duplicates (optional, based on requirement)
        // $pdo->exec("DELETE FROM market_prices WHERE state = 'Gujarat'");

        $stmt = $pdo->prepare("
            INSERT INTO market_prices (state, district, market, commodity, variety, grade, arrival_date, min_price, max_price, modal_price)
            VALUES (:state, :district, :market, :commodity, :variety, :grade, :arrival_date, :min_price, :max_price, :modal_price)
        ");

        $count = 0;
        foreach ($data['records'] as $record) {
            $stmt->execute([
                ':state' => $record['state'] ?? 'Gujarat',
                ':district' => $record['district'] ?? null,
                ':market' => $record['market'] ?? null,
                ':commodity' => $record['commodity'] ?? null,
                ':variety' => $record['variety'] ?? null,
                ':grade' => $record['grade'] ?? null,
                ':arrival_date' => $record['arrival_date'] ?? null,
                ':min_price' => isset($record['min_price']) && is_numeric($record['min_price']) ? $record['min_price'] : 0,
                ':max_price' => isset($record['max_price']) && is_numeric($record['max_price']) ? $record['max_price'] : 0,
                ':modal_price' => isset($record['modal_price']) && is_numeric($record['modal_price']) ? $record['modal_price'] : 0,
            ]);
            $count++;
        }

        echo "Data Inserted Successfully ($count records)";
    } else {
        echo "API Failed or no records returned.";
    }
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage();
}
