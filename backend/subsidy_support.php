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

    $benefitsColumn = subsidy_pick_column($columns, ['benefits', 'benefits_en']);
    $eligibilityColumn = subsidy_pick_column($columns, ['eligibility', 'eligibility_en']);
    $applyLinkColumn = subsidy_pick_column($columns, ['apply_link']);

    if ($nameColumn === null) {
        return [];
    }

    $selectParts = [
        'id',
        "{$nameColumn} AS name",
        ($categoryColumn ? "{$categoryColumn} AS category" : "'' AS category"),
        ($descriptionColumn ? "{$descriptionColumn} AS description" : "'' AS description"),
        ($benefitsColumn ? "{$benefitsColumn} AS benefits" : "'' AS benefits"),
        ($eligibilityColumn ? "{$eligibilityColumn} AS eligibility" : "'' AS eligibility"),
        ($applyLinkColumn ? "{$applyLinkColumn} AS apply_link" : "'' AS apply_link"),
        ($statusColumn ? "{$statusColumn} AS status" : "'Live' AS status"),
        ($lastUpdatedColumn ? "{$lastUpdatedColumn} AS last_updated" : 'CURRENT_TIMESTAMP AS last_updated'),
    ];

    foreach (['gu', 'hi'] as $lang) {
        $n = subsidy_pick_column($columns, ["name_$lang", "title_$lang"]);
        $selectParts[] = ($n ? "{$n} AS name_$lang" : "'' AS name_$lang");
        
        $c = subsidy_pick_column($columns, ["category_$lang"]);
        $selectParts[] = ($c ? "{$c} AS category_$lang" : "'' AS category_$lang");
        
        $d = subsidy_pick_column($columns, ["description_$lang"]);
        $selectParts[] = ($d ? "{$d} AS description_$lang" : "'' AS description_$lang");
        
        $b = subsidy_pick_column($columns, ["benefits_$lang"]);
        $selectParts[] = ($b ? "{$b} AS benefits_$lang" : "'' AS benefits_$lang");
        
        $e = subsidy_pick_column($columns, ["eligibility_$lang"]);
        $selectParts[] = ($e ? "{$e} AS eligibility_$lang" : "'' AS eligibility_$lang");
    }

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

function subsidy_delete_row(PDO $pdo, int $id): void
{
    if ($id <= 0) {
        throw new RuntimeException('Valid subsidy id is required.');
    }

    $stmt = $pdo->prepare('DELETE FROM subsidies WHERE id = :id');
    $stmt->execute(['id' => $id]);

    if ($stmt->rowCount() < 1) {
        throw new RuntimeException('Subsidy not found.');
    }
}
