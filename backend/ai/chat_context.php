<?php

require_once __DIR__ . '/../db.php';

function chat_ensure_schema(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS chat_messages (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NULL,
            session_key VARCHAR(120) NOT NULL,
            role VARCHAR(20) NOT NULL,
            message TEXT NOT NULL,
            model VARCHAR(80) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_chat_messages_user_id ON chat_messages(user_id)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_chat_messages_session_key ON chat_messages(session_key)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_chat_messages_created_at ON chat_messages(created_at)");
    
    // Create cache directory if not exists
    $cacheDir = __DIR__ . '/cache';
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0755, true);
    }
}

function chat_cache_get(string $key, int $ttl = 3600): ?array
{
    $file = __DIR__ . '/cache/' . md5($key) . '.json';
    if (file_exists($file) && (time() - filemtime($file) < $ttl)) {
        $content = file_get_contents($file);
        return json_decode($content, true);
    }
    return null;
}

function chat_cache_set(string $key, array $data): void
{
    $file = __DIR__ . '/cache/' . md5($key) . '.json';
    file_put_contents($file, json_encode($data));
}

function chat_normalize_profile(array $clientProfile): array
{
    return [
        'id' => isset($clientProfile['id']) ? (int) $clientProfile['id'] : null,
        'name' => trim((string) ($clientProfile['name'] ?? '')),
        'role' => trim((string) ($clientProfile['role'] ?? 'farmer')),
        'pref_lang' => trim((string) ($clientProfile['pref_lang'] ?? 'en')),
        'district' => trim((string) ($clientProfile['district'] ?? '')),
        'city' => trim((string) ($clientProfile['city'] ?? '')),
        'crop' => trim((string) ($clientProfile['crop'] ?? '')),
    ];
}

function chat_load_user_profile(PDO $pdo, ?int $userId, array $clientProfile): array
{
    $profile = chat_normalize_profile($clientProfile);

    if (!$userId) {
        return $profile;
    }

    $stmt = $pdo->prepare("SELECT id, name, role, pref_lang, district, city FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $userId]);
    $dbUser = $stmt->fetch();

    if (!$dbUser) {
        return $profile;
    }

    return [
        'id' => (int) $dbUser['id'],
        'name' => $profile['name'] !== '' ? $profile['name'] : (string) $dbUser['name'],
        'role' => $profile['role'] !== '' ? $profile['role'] : (string) $dbUser['role'],
        'pref_lang' => $profile['pref_lang'] !== '' ? $profile['pref_lang'] : (string) $dbUser['pref_lang'],
        'district' => $profile['district'] !== '' ? $profile['district'] : (string) ($dbUser['district'] ?? ''),
        'city' => $profile['city'] !== '' ? $profile['city'] : (string) ($dbUser['city'] ?? ''),
        'crop' => $profile['crop'],
    ];
}

function chat_fetch_recent_history(PDO $pdo, ?int $userId, string $sessionKey, int $limit = 8): array
{
    if ($userId) {
        $stmt = $pdo->prepare("
            SELECT role, message, created_at
            FROM chat_messages
            WHERE user_id = :user_id OR session_key = :session_key
            ORDER BY created_at DESC, id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':session_key', $sessionKey, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("
            SELECT role, message, created_at
            FROM chat_messages
            WHERE session_key = :session_key
            ORDER BY created_at DESC, id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':session_key', $sessionKey, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
    }

    return array_reverse($stmt->fetchAll() ?: []);
}

function chat_fetch_sessions(PDO $pdo, ?int $userId): array
{
    if (!$userId) {
        return [];
    }

    // Get the first user message of each session as the title
    $stmt = $pdo->prepare("
        SELECT DISTINCT ON (session_key) 
               session_key, 
               message as title, 
               created_at
        FROM chat_messages
        WHERE user_id = :user_id AND role = 'user'
        ORDER BY session_key, created_at ASC
    ");
    $stmt->execute(['user_id' => $userId]);
    $sessions = $stmt->fetchAll() ?: [];

    // Sort by most recent activity
    $latestStmt = $pdo->prepare("
        SELECT session_key, MAX(created_at) as last_activity
        FROM chat_messages
        WHERE user_id = :user_id
        GROUP BY session_key
    ");
    $latestStmt->execute(['user_id' => $userId]);
    $activities = [];
    foreach ($latestStmt->fetchAll() as $row) {
        $activities[$row['session_key']] = $row['last_activity'];
    }

    foreach ($sessions as &$s) {
        $s['last_activity'] = $activities[$s['session_key']] ?? $s['created_at'];
    }

    usort($sessions, function ($a, $b) {
        return strtotime($b['last_activity']) - strtotime($a['last_activity']);
    });

    return $sessions;
}

function chat_delete_session(PDO $pdo, ?int $userId, string $sessionKey): bool
{
    $stmt = $pdo->prepare("DELETE FROM chat_messages WHERE user_id = :user_id AND session_key = :session_key");
    return $stmt->execute([
        'user_id' => $userId,
        'session_key' => $sessionKey
    ]);
}

function chat_store_message(PDO $pdo, ?int $userId, string $sessionKey, string $role, string $message, ?string $model = null): void
{
    $stmt = $pdo->prepare("
        INSERT INTO chat_messages (user_id, session_key, role, message, model)
        VALUES (:user_id, :session_key, :role, :message, :model)
    ");
    $stmt->bindValue(':user_id', $userId, $userId ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(':session_key', $sessionKey, PDO::PARAM_STR);
    $stmt->bindValue(':role', $role, PDO::PARAM_STR);
    $stmt->bindValue(':message', $message, PDO::PARAM_STR);
    $stmt->bindValue(':model', $model, $model !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->execute();
}

function chat_fetch_market_snapshot(PDO $pdo, string $district, string $crop): array
{
    $cacheKey = "market_snap_{$district}_{$crop}";
    $cached = chat_cache_get($cacheKey, 1800); // 30 mins cache
    if ($cached !== null) {
        return $cached;
    }

    try {
        // Optimized query: avoid to_date on all rows for sorting. 
        // We use arrival_date filter if possible, otherwise we sort by ID desc as a proxy for latest.
        $sql = "
            SELECT commodity, district, market, modal_price, arrival_date
            FROM market_prices
            WHERE state ILIKE 'gujarat'
        ";
        $params = [];

        if ($district !== '') {
            $sql .= " AND district ILIKE :district";
            $params['district'] = '%' . $district . '%';
        }

        if ($crop !== '') {
            $sql .= " AND commodity ILIKE :commodity";
            $params['commodity'] = '%' . $crop . '%';
        }

        // Sorting by id DESC is much faster than to_date conversion on all rows.
        $sql .= " ORDER BY id DESC LIMIT 5";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll() ?: [];
        chat_cache_set($cacheKey, $rows);
        return $rows;
    } catch (Throwable $e) {
        return [];
    }
}

function chat_fetch_subsidy_snapshot(PDO $pdo, string $crop): array
{
    $cacheKey = "subsidy_snap_{$crop}";
    $cached = chat_cache_get($cacheKey, 3600); // 1 hour cache
    if ($cached !== null) {
        return $cached;
    }

    try {
        if ($crop !== '') {
            $stmt = $pdo->prepare("
                SELECT category, name, benefits, apply_link
                FROM subsidies
                WHERE name ILIKE :crop
                   OR description ILIKE :crop
                   OR benefits ILIKE :crop
                ORDER BY last_updated DESC NULLS LAST
                LIMIT 5
            ");
            $stmt->execute(['crop' => '%' . $crop . '%']);
            $rows = $stmt->fetchAll() ?: [];
            chat_cache_set($cacheKey, $rows);
            return $rows;
        }

        $stmt = $pdo->query("
            SELECT category, name, benefits, apply_link
            FROM subsidies
            ORDER BY last_updated DESC NULLS LAST
            LIMIT 5
        ");
        $rows = $stmt->fetchAll() ?: [];
        chat_cache_set($cacheKey, $rows);
        return $rows;
    } catch (Throwable $e) {
        return [];
    }
}

function chat_fetch_crop_schedule(PDO $pdo, string $crop): array
{
    if ($crop === '') {
        return [];
    }

    $cacheKey = "crop_sched_{$crop}";
    $cached = chat_cache_get($cacheKey, 3600); // 1 hour cache
    if ($cached !== null) {
        return $cached;
    }

    try {
        $stmt = $pdo->prepare("
            SELECT id, name_en, season_en
            FROM crops
            WHERE name_en ILIKE :crop OR name_gu ILIKE :crop OR name_hi ILIKE :crop
            ORDER BY id
            LIMIT 1
        ");
        $stmt->execute(['crop' => '%' . $crop . '%']);
        $cropRow = $stmt->fetch();

        if (!$cropRow) {
            chat_cache_set($cacheKey, []);
            return [];
        }

        $scheduleStmt = $pdo->prepare("
            SELECT month_index, task_en
            FROM crop_schedules
            WHERE crop_id = :crop_id
            ORDER BY month_index
        ");
        $scheduleStmt->execute(['crop_id' => $cropRow['id']]);

        $result = [
            'name' => $cropRow['name_en'],
            'season' => $cropRow['season_en'],
            'items' => $scheduleStmt->fetchAll() ?: [],
        ];
        chat_cache_set($cacheKey, $result);
        return $result;
    } catch (Throwable $e) {
        return [];
    }
}

function chat_fetch_pesticide_recommendations(PDO $pdo, string $pestName): array
{
    if ($pestName === '') {
        return [];
    }
    
    try {
        // Try exact match first
        $stmt = $pdo->prepare("
            SELECT p.name_en as name, p.brand, p.price_range, p.usage_en as usage_instructions, m.effectiveness, m.pest_name as matched_pest
            FROM pesticides p
            JOIN pest_pesticide_mapping m ON p.id = m.pesticide_id
            WHERE m.pest_name ILIKE :pest
               OR :pest_raw ILIKE '%' || m.pest_name || '%'
            ORDER BY m.effectiveness DESC, p.id ASC
        ");
        $stmt->execute([
            'pest' => '%' . $pestName . '%',
            'pest_raw' => $pestName
        ]);
        return $stmt->fetchAll() ?: [];
    } catch (Throwable $e) {
        return [];
    }
}

function chat_fetch_local_shops(PDO $pdo, string $district): array
{
    try {
        $sql = "SELECT name, city, address, phone FROM shops";
        $params = [];
        
        if ($district !== '') {
            $sql .= " WHERE district ILIKE :district";
            $params['district'] = '%' . $district . '%';
        }
        
        $sql .= " ORDER BY id LIMIT 3";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll() ?: [];
        
        // Fallback: If no shops in this district, show ANY available shops as "Nearby"
        if (empty($rows) && $district !== '') {
            $stmt = $pdo->query("SELECT name, city, address, phone FROM shops ORDER BY id LIMIT 3");
            return $stmt->fetchAll() ?: [];
        }
        
        return $rows;
    } catch (Throwable $e) {
        return [];
    }
}

function chat_normalize_pest_name(string $raw): string
{
    // If it's technically a class name from a dataset (e.g., Potato___Early_blight)
    $clean = str_replace('___', ' ', $raw);
    $clean = str_replace('_', ' ', $clean);
    
    // Split into parts and take the disease part after the plant name
    $parts = explode(' ', $clean);
    if (count($parts) > 1) {
        // If it starts with a plant name like 'Apple', 'Corn', etc.
        // We take the rest as the disease.
        $disease = implode(' ', array_slice($parts, 1));
        return trim($disease);
    }
    
    return trim($clean);
}

function chat_context_block(array $profile, array $marketRows, array $subsidyRows, array $cropSchedule, string $identifiedPest = ''): string
{
    $profileLines = [
        'Farmer profile:',
        '- Name: ' . ($profile['name'] !== '' ? $profile['name'] : 'Unknown'),
        '- Role: ' . ($profile['role'] !== '' ? $profile['role'] : 'farmer'),
        '- Preferred language: ' . ($profile['pref_lang'] !== '' ? $profile['pref_lang'] : 'en'),
        '- District: ' . ($profile['district'] !== '' ? $profile['district'] : 'Unknown'),
        '- City: ' . ($profile['city'] !== '' ? $profile['city'] : 'Unknown'),
        '- Selected crop: ' . ($profile['crop'] !== '' ? $profile['crop'] : 'Not provided'),
    ];

    $pestLines = [];
    if ($identifiedPest !== '') {
        $commonName = chat_normalize_pest_name($identifiedPest);
        $pestLines[] = "IDENTIFIED PEST FROM PHOTO: " . strtoupper($commonName);
        
        $pdo = Database::getConnection();
        // Search by both raw and common name
        $pesticides = chat_fetch_pesticide_recommendations($pdo, $commonName);
        if (!$pesticides) {
            $pesticides = chat_fetch_pesticide_recommendations($pdo, $identifiedPest);
        }
        if ($pesticides) {
            $pestLines[] = "Recommended Pesticides:";
            foreach ($pesticides as $p) {
                $pestLines[] = sprintf("- %s (%s) | Effectiveness: %s | Price: %s", $p['name'], $p['brand'], $p['effectiveness'], $p['price_range']);
            }
        }
        
        $shops = chat_fetch_local_shops($pdo, $profile['district']);
        if ($shops) {
            $pestLines[] = "Nearby Shops to buy these:";
            foreach ($shops as $s) {
                $address = ($s['address'] ?? '') . ', ' . ($s['city'] ?? '');
                $mapUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode($address);
                $pestLines[] = sprintf("- %s | Ph: %s | Addr: %s | VIEW ON GOOGLE MAPS: %s", $s['name'], $s['phone'], $s['address'], $mapUrl);
            }
        }
    }

    $marketLines = ['Recent market prices:'];
    if ($marketRows) {
        foreach ($marketRows as $row) {
            $marketLines[] = sprintf(
                '- %s in %s / %s: Rs.%s on %s',
                $row['commodity'] ?? 'Unknown commodity',
                $row['district'] ?? 'Unknown district',
                $row['market'] ?? 'Unknown market',
                $row['modal_price'] ?? 'NA',
                $row['arrival_date'] ?? 'Unknown date'
            );
        }
    } else {
        $marketLines[] = '- No matching mandi price snapshot available.';
    }

    $subsidyLines = ['Relevant subsidies:'];
    if ($subsidyRows) {
        foreach ($subsidyRows as $row) {
            $subsidyLines[] = sprintf(
                '- %s | %s | Benefit: %s | Apply: %s',
                $row['category'] ?? 'General',
                $row['name'] ?? 'Unnamed scheme',
                $row['benefits'] ?? 'Not listed',
                $row['apply_link'] ?? 'Not listed'
            );
        }
    } else {
        $subsidyLines[] = '- No subsidy records available.';
    }

    $scheduleLines = ['Crop calendar:'];
    if (!empty($cropSchedule['items'])) {
        $scheduleLines[] = '- Crop: ' . ($cropSchedule['name'] ?? 'Unknown');
        $scheduleLines[] = '- Season: ' . ($cropSchedule['season'] ?? 'Unknown');
        foreach (array_slice($cropSchedule['items'], 0, 6) as $item) {
            $scheduleLines[] = sprintf('- Month %s: %s', (int) $item['month_index'] + 1, $item['task_en'] ?? 'No task');
        }
    } else {
        $scheduleLines[] = '- No crop schedule loaded for the selected crop.';
    }

    return implode("\n", array_merge($profileLines, [''], $pestLines, [''], $marketLines, [''], $subsidyLines, [''], $scheduleLines));
}
