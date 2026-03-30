<?php

function subsidy_columns(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'subsidies'");
    $columns = [];
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $column) {
        $columns[$column] = true;
    }
    return $columns;
}

function subsidy_pick_column(array $columns, array $candidates): ?string
{
    foreach ($candidates as $candidate) {
        if (isset($columns[$candidate])) {
            return $candidate;
        }
    }
    return null;
}

function subsidy_select_rows(PDO $pdo, string $categoryFilter, string $search): array
{
    $columns = subsidy_columns($pdo);

    $nameColumn = subsidy_pick_column($columns, ['name', 'title_en']);
    $categoryColumn = subsidy_pick_column($columns, ['category', 'category_en']);
    $descriptionColumn = subsidy_pick_column($columns, ['description', 'description_en']);
    $statusColumn = subsidy_pick_column($columns, ['status']);
    $lastUpdatedColumn = subsidy_pick_column($columns, ['last_updated', 'updated_at', 'created_at']);

    if ($nameColumn === null) {
        return [];
    }

    $selectParts = [
        'id',
        "{$nameColumn} AS name",
        ($categoryColumn ? "{$categoryColumn} AS category" : "'' AS category"),
        ($descriptionColumn ? "{$descriptionColumn} AS description" : "'' AS description"),
        ($statusColumn ? "{$statusColumn} AS status" : "'Live' AS status"),
        ($lastUpdatedColumn ? "{$lastUpdatedColumn} AS last_updated" : 'CURRENT_TIMESTAMP AS last_updated'),
    ];

    $query = 'SELECT ' . implode(', ', $selectParts) . ' FROM subsidies WHERE 1=1';
    $params = [];

    if ($categoryFilter !== '' && strcasecmp($categoryFilter, 'All') !== 0 && $categoryColumn !== null) {
        $query .= " AND {$categoryColumn} = :category";
        $params['category'] = $categoryFilter;
    }

    if ($search !== '') {
        $searchParts = [];
        foreach ([$nameColumn, $descriptionColumn, 'name_gu', 'name_hi', 'title_gu', 'title_hi', 'description_gu', 'description_hi'] as $column) {
            if ($column !== null && isset($columns[$column])) {
                $searchParts[] = "{$column} ILIKE :search";
            }
        }
        if ($searchParts) {
            $query .= ' AND (' . implode(' OR ', $searchParts) . ')';
            $params['search'] = "%{$search}%";
        }
    }

    $query .= $lastUpdatedColumn !== null ? " ORDER BY {$lastUpdatedColumn} DESC" : ' ORDER BY id DESC';

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function subsidy_insert_row(PDO $pdo, array $data): void
{
    $columns = subsidy_columns($pdo);
    $payload = [];

    if (isset($columns['name'])) {
        $payload['name'] = $data['name'];
    }
    if (isset($columns['title_en'])) {
        $payload['title_en'] = $data['name'];
    }
    if (isset($columns['category'])) {
        $payload['category'] = $data['category'];
    }
    if (isset($columns['category_en'])) {
        $payload['category_en'] = $data['category'];
    }
    if (isset($columns['description'])) {
        $payload['description'] = $data['description'];
    }
    if (isset($columns['description_en'])) {
        $payload['description_en'] = $data['description'];
    }
    if (isset($columns['benefits'])) {
        $payload['benefits'] = $data['benefits'];
    }
    if (isset($columns['benefits_en'])) {
        $payload['benefits_en'] = $data['benefits'];
    }
    if (isset($columns['eligibility'])) {
        $payload['eligibility'] = $data['eligibility'];
    }
    if (isset($columns['apply_link'])) {
        $payload['apply_link'] = $data['apply_link'];
    }
    if (isset($columns['status'])) {
        $payload['status'] = $data['status'] !== '' ? $data['status'] : 'Live';
    }
    if (isset($columns['last_updated'])) {
        $payload['last_updated'] = date('c');
    }

    if (!$payload) {
        throw new RuntimeException('Subsidy table is missing supported columns.');
    }

    $columnSql = implode(', ', array_keys($payload));
    $placeholders = implode(', ', array_map(static fn(string $key): string => ':' . $key, array_keys($payload)));
    $stmt = $pdo->prepare("INSERT INTO subsidies ({$columnSql}) VALUES ({$placeholders})");
    $stmt->execute($payload);
}
