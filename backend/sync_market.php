<?php
// backend/sync_market.php - Sync latest market data from Data.gov.in
require_once __DIR__ . '/db.php';

function buildMarketApiUrl(string $apiKey, array $filters = [], int $limit = 500, int $offset = 0): string
{
    $params = [
        'api-key' => $apiKey,
        'format' => 'json',
        'limit' => $limit,
        'offset' => $offset,
    ];

    foreach ($filters as $key => $value) {
        $params["filters[$key]"] = $value;
    }

    return 'https://api.data.gov.in/resource/9ef84268-d588-465a-a308-a864a43d0070?' . http_build_query($params);
}

function fetchMarketApiPage(string $apiKey, array $filters, int $limit, int $offset): array
{
    $url = buildMarketApiUrl($apiKey, $filters, $limit, $offset);
    $context = stream_context_create([
        'http' => [
            'timeout' => 20,
        ],
    ]);

    $json = @file_get_contents($url, false, $context);
    if ($json === false) {
        throw new RuntimeException('Failed to fetch market data from data.gov.in.');
    }

    $data = json_decode($json, true);
    if (!is_array($data)) {
        throw new RuntimeException('Invalid market API response.');
    }

    return $data['records'] ?? [];
}

function syncLatestMarketData(int $lookbackDays = 7, int $limit = 500, int $maxPages = 20): array
{
    date_default_timezone_set('Asia/Kolkata');

    $pdo = Database::getConnection();
    $apiKey = getenv('DATA_GOV_API_KEY') ?: '';
    if (!$apiKey) {
        throw new RuntimeException('DATA_GOV_API_KEY environment variable is not set.');
    }

    $sql = "INSERT INTO market_prices (state, district, market, commodity, variety, grade, arrival_date, min_price, max_price, modal_price)
            VALUES (:state, :district, :market, :commodity, :variety, :grade, :arrival_date, :min_price, :max_price, :modal_price)
            ON CONFLICT (state, district, market, commodity, variety, arrival_date)
            DO UPDATE SET
                min_price = EXCLUDED.min_price,
                max_price = EXCLUDED.max_price,
                modal_price = EXCLUDED.modal_price";
    $stmt = $pdo->prepare($sql);

    $targetDate = null;
    $processed = 0;
    $pagesFetched = 0;

    for ($dayOffset = 0; $dayOffset <= $lookbackDays; $dayOffset++) {
        $date = new DateTimeImmutable('today', new DateTimeZone('Asia/Kolkata'));
        if ($dayOffset > 0) {
            $date = $date->sub(new DateInterval("P{$dayOffset}D"));
        }

        $arrivalDate = $date->format('d/m/Y');
        $records = fetchMarketApiPage($apiKey, [
            'state' => 'Gujarat',
            'arrival_date' => $arrivalDate,
        ], $limit, 0);

        if (empty($records)) {
            continue;
        }

        $targetDate = $records[0]['arrival_date'] ?? $arrivalDate;

        for ($page = 0; $page < $maxPages; $page++) {
            $offset = $page * $limit;
            $pageRecords = $page === 0
                ? $records
                : fetchMarketApiPage($apiKey, [
                    'state' => 'Gujarat',
                    'arrival_date' => $arrivalDate,
                ], $limit, $offset);

            if (empty($pageRecords)) {
                break;
            }

            foreach ($pageRecords as $row) {
                $stmt->execute([
                    'state' => $row['state'] ?? 'Gujarat',
                    'district' => $row['district'] ?? '',
                    'market' => $row['market'] ?? '',
                    'commodity' => $row['commodity'] ?? '',
                    'variety' => $row['variety'] ?? '',
                    'grade' => $row['grade'] ?? '',
                    'arrival_date' => $row['arrival_date'] ?? $arrivalDate,
                    'min_price' => (int) ($row['min_price'] ?? 0),
                    'max_price' => (int) ($row['max_price'] ?? 0),
                    'modal_price' => (int) ($row['modal_price'] ?? 0),
                ]);
                $processed++;
            }

            $pagesFetched++;

            if (count($pageRecords) < $limit) {
                break;
            }
        }

        break;
    }

    return [
        'target_date' => $targetDate,
        'processed' => $processed,
        'pages_fetched' => $pagesFetched,
        'synced_at' => date(DATE_ATOM),
    ];
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    try {
        $result = syncLatestMarketData();
        if ($result['target_date']) {
            echo "Synced {$result['processed']} records for {$result['target_date']} in {$result['pages_fetched']} page(s)." . PHP_EOL;
        } else {
            echo "No Gujarat market data found in the last 7 days." . PHP_EOL;
        }
    } catch (Throwable $e) {
        fwrite(STDERR, $e->getMessage() . PHP_EOL);
        exit(1);
    }
}
