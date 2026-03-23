<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/chat_context.php';

try {
    $pdo = Database::getConnection();
    $district = trim($_GET['district'] ?? '');
    $pest     = trim($_GET['pest'] ?? '');

    // Geolocation coordinates from browser (optional)
    $lat = isset($_GET['lat']) && is_numeric($_GET['lat']) ? (float) $_GET['lat'] : null;
    $lng = isset($_GET['lng']) && is_numeric($_GET['lng']) ? (float) $_GET['lng'] : null;

    $shops = chat_fetch_local_shops($pdo, $district);

    foreach ($shops as &$shop) {
        $shopName = trim($shop['name'] ?? '');
        $addr     = trim(($shop['address'] ?? '') . ', ' . ($shop['city'] ?? ''));

        if ($lat !== null && $lng !== null) {
            // Direct "near me" Google Maps search anchored to farmer GPS location
            $shop['map_url'] = 'https://www.google.com/maps/search/'
                . urlencode($shopName ?: 'agricultural shop')
                . '/@' . $lat . ',' . $lng . ',14z';

            // Navigation link: get directions from GPS location to shop address
            $shop['directions_url'] = 'https://www.google.com/maps/dir/'
                . $lat . ',' . $lng . '/'
                . urlencode($addr);
        } else {
            // Fallback: plain address search
            $shop['map_url'] = 'https://www.google.com/maps/search/?api=1&query='
                . urlencode($shopName . ' ' . $addr);
            $shop['directions_url'] = '';
        }
    }
    unset($shop);

    // Build an embedded Google Maps iframe src for "agricultural shops near me"
    $mapEmbedSrc = null;
    if ($lat !== null && $lng !== null) {
        $searchQ = urlencode('agricultural shop near me');
        // Embed search map centred on farmer's GPS
        $mapEmbedSrc = "https://maps.google.com/maps?q={$searchQ}&ll={$lat},{$lng}&z=13&output=embed";
    } elseif ($district !== '') {
        $searchQ = urlencode('agricultural shop in ' . $district);
        $mapEmbedSrc = "https://maps.google.com/maps?q={$searchQ}&z=12&output=embed";
    }

    $pesticides = [];
    if ($pest !== '') {
        $commonName = chat_normalize_pest_name($pest);
        $pesticides = chat_fetch_pesticide_recommendations($pdo, $commonName);
        if (!$pesticides) {
            $pesticides = chat_fetch_pesticide_recommendations($pdo, $pest);
        }
    }

    echo json_encode([
        'shops'         => $shops,
        'pesticides'    => $pesticides,
        'district'      => $district,
        'pest'          => $pest,
        'lat'           => $lat,
        'lng'           => $lng,
        'map_embed_src' => $mapEmbedSrc,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
