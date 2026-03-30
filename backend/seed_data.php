<?php
// backend/seed_data.php — Run once to seed pesticides + subsidies data
// Access via: http://localhost:8000/backend/seed_data.php
require_once __DIR__ . '/db.php';

header('Content-Type: text/html; charset=utf-8');

$pdo = Database::getConnection();
$results = [];

// ─── 1. Pesticides ────────────────────────────────────────────────────────────
$pesticides = [
    ['Imidacloprid 17.8% SL',   'Confidor',     'Aphids, Whitefly, Thrips',         'Rs. 400 - 700',  'Mix 0.5 ml/L water. Spray on leaves.'],
    ['Chlorpyrifos 20% EC',     'Dursban',      'Stem Borer, Caterpillars, Ants',   'Rs. 300 - 500',  'Mix 2 ml/L water. Spray at base of plant.'],
    ['Mancozeb 75% WP',         'Dithane M-45', 'Early Blight, Late Blight, Downy Mildew', 'Rs. 200 - 400', 'Mix 2g/L water. Spray every 7 days.'],
    ['Copper Oxychloride 50% WP','Blitox',       'Bacterial Leaf Spot, Downy Mildew','Rs. 250 - 450',  'Mix 3g/L water. Spray in morning.'],
    ['Carbendazim 50% WP',      'Bavistin',     'Powdery Mildew, Root Rot, Wilt',   'Rs. 350 - 600',  'Mix 1g/L water. Drench soil or spray.'],
    ['Lambda-Cyhalothrin 5% EC','Karate',        'Aphids, Caterpillars, Pests',      'Rs. 500 - 800',  'Mix 1 ml/L water. Spray thoroughly.'],
    ['Propiconazole 25% EC',    'Tilt',          'Rust, Leaf Spot, Scab',            'Rs. 600 - 900',  'Mix 1 ml/L water. Spray at disease onset.'],
    ['Thiamethoxam 25% WG',     'Actara',        'Whitefly, Aphids, Leafhoppers',    'Rs. 700 - 1100', 'Mix 0.3g/L water. Spray or drench.'],
    ['Azadirachtin 1% EC',      'Neem Gold',     'All soft-bodied insects (Organic)','Rs. 150 - 300',  'Mix 5 ml/L water. Spray every 5 days.'],
    ['Tricyclazole 75% WP',     'Beam',          'Rice Blast',                       'Rs. 400 - 700',  'Mix 0.6g/L water. Spray at heading stage.'],
];

$insertP = $pdo->prepare("
    INSERT INTO pesticides (name_en, name_gu, name_hi, brand, target_pests_en, price_range, usage_en)
    VALUES (?,?,?,?,?,?,?)
    ON CONFLICT DO NOTHING
");

foreach ($pesticides as $p) {
    try {
        $insertP->execute([$p[0], $p[0], $p[0], $p[1], $p[2], $p[3], $p[4]]);
        $results[] = "✅ Pesticide: {$p[1]} ({$p[0]})";
    } catch (Exception $e) {
        $results[] = "⚠️ Skip pesticide {$p[1]}: " . $e->getMessage();
    }
}

// ─── 2. Pest ↔ Pesticide Mappings ────────────────────────────────────────────
$mappings = [
    ['Early Blight',       'Dithane M-45',  'High'],
    ['Early Blight',       'Tilt',          'High'],
    ['Late Blight',        'Dithane M-45',  'High'],
    ['Late Blight',        'Blitox',        'High'],
    ['Powdery Mildew',     'Bavistin',      'High'],
    ['Powdery Mildew',     'Tilt',          'Moderate'],
    ['Aphids',             'Confidor',      'High'],
    ['Aphids',             'Actara',        'High'],
    ['Aphids',             'Neem Gold',     'Organic'],
    ['Whitefly',           'Confidor',      'High'],
    ['Whitefly',           'Actara',        'High'],
    ['Stem Borer',         'Dursban',       'High'],
    ['Caterpillars',       'Karate',        'High'],
    ['Caterpillars',       'Dursban',       'Moderate'],
    ['Downy Mildew',       'Blitox',        'High'],
    ['Downy Mildew',       'Dithane M-45',  'Moderate'],
    ['Leaf Spot',          'Blitox',        'High'],
    ['Bacterial Leaf Spot','Blitox',        'High'],
    ['Root Rot',           'Bavistin',      'High'],
    ['Wilt',               'Bavistin',      'Moderate'],
    ['Rice Blast',         'Beam',          'High'],
    ['Rust',               'Tilt',          'High'],
    ['Thrips',             'Confidor',      'Moderate'],
    ['Leafhoppers',        'Actara',        'High'],
    ['Fungal Disease',     'Dithane M-45',  'Moderate'],
];

$insertM = $pdo->prepare("
    INSERT INTO pest_pesticide_mapping (pest_name, pesticide_id, effectiveness)
    SELECT ?, p.id, ?
    FROM pesticides p
    WHERE p.brand = ?
    LIMIT 1
    ON CONFLICT DO NOTHING
");

foreach ($mappings as $m) {
    try {
        $insertM->execute([$m[0], $m[2], $m[1]]);
        $results[] = "✅ Mapping: {$m[0]} → {$m[1]} ({$m[2]})";
    } catch (Exception $e) {
        $results[] = "⚠️ Skip mapping {$m[0]}: " . $e->getMessage();
    }
}

// ─── 3. Subsidies ─────────────────────────────────────────────────────────────
$subsidies = [
    [
        'name'        => 'PM Kisan Samman Nidhi',
        'category'    => 'Financial',
        'description' => 'Direct income support of Rs. 6,000/year to small and marginal farmers.',
        'benefits'    => 'Rs. 6,000 per year in three equal installments of Rs. 2,000 each.',
        'eligibility' => 'Small/marginal farmers with cultivable land. Aadhaar and bank account required.',
        'apply_link'  => 'https://pmkisan.gov.in/',
        'status'      => 'Live',
    ],
    [
        'name'        => 'PM Fasal Bima Yojana',
        'category'    => 'Insurance',
        'description' => 'Crop insurance scheme providing financial support to farmers suffering crop loss due to calamities.',
        'benefits'    => 'Coverage for crop loss due to natural calamities, pests & diseases. Premium as low as 2%.',
        'eligibility' => 'All farmers growing notified crops. Compulsory for loanee farmers.',
        'apply_link'  => 'https://pmfby.gov.in/',
        'status'      => 'Live',
    ],
    [
        'name'        => 'Kisan Credit Card (KCC)',
        'category'    => 'Financial',
        'description' => 'Provides farmers with affordable credit for agricultural needs including crop production.',
        'benefits'    => 'Credit up to Rs. 3 lakh at 7% interest rate (4% effective with prompt repayment).',
        'eligibility' => 'All farmers, tenant farmers, sharecroppers, and SHGs.',
        'apply_link'  => 'https://www.nabard.org/content1.aspx?id=572&catid=8&mid=490',
        'status'      => 'Live',
    ],
    [
        'name'        => 'Sub-Mission on Agricultural Mechanization (SMAM)',
        'category'    => 'Equipment',
        'description' => 'Subsidy on purchase of agricultural machinery and equipment.',
        'benefits'    => '40-50% subsidy on farm equipment purchase. CHC setup assistance.',
        'eligibility' => 'Farmers, FPOs, Self Help Groups, NGOs.',
        'apply_link'  => 'https://agrimachinery.nic.in/',
        'status'      => 'Live',
    ],
    [
        'name'        => 'National Food Security Mission (NFSM)',
        'category'    => 'Seed & Fertilizer',
        'description' => 'Promotes production of rice, wheat, pulses and coarse cereals.',
        'benefits'    => 'Free/subsidized seeds, fertilizers, and micronutrients. Training support.',
        'eligibility' => 'Farmers in selected districts cultivating notified crops.',
        'apply_link'  => 'https://nfsm.gov.in/',
        'status'      => 'Live',
    ],
    [
        'name'        => 'Pradhan Mantri Krishi Sinchai Yojana (PMKSY)',
        'category'    => 'Irrigation',
        'description' => 'Expanding irrigation coverage for every field (Har Khet Ko Pani) and improving water use efficiency.',
        'benefits'    => 'Subsidy on drip/sprinkler irrigation systems. 45-55% cost coverage.',
        'eligibility' => 'All categories of farmers. Small/marginal farmers get higher subsidy.',
        'apply_link'  => 'https://pmksy.gov.in/',
        'status'      => 'Live',
    ],
    [
        'name'        => 'Gujarat Agriculture Subsidy Scheme (iKhedut)',
        'category'    => 'Equipment',
        'description' => 'Gujarat state scheme for agricultural inputs, mechanization, and horticulture development.',
        'benefits'    => 'Subsidies on seeds, fertilizers, drip irrigation, farm machinery (25-75%).',
        'eligibility' => 'Gujarat farmers registered on iKhedut portal.',
        'apply_link'  => 'https://ikhedut.gujarat.gov.in/',
        'status'      => 'Live',
    ],
    [
        'name'        => 'Soil Health Card Scheme',
        'category'    => 'Training',
        'description' => 'Issues Soil Health Cards to farmers with crop-wise recommendations for nutrients.',
        'benefits'    => 'Free soil testing, personalized fertilizer recommendations, higher yields.',
        'eligibility' => 'All farmers across India.',
        'apply_link'  => 'https://soilhealth.dac.gov.in/',
        'status'      => 'Live',
    ],
];

$insertS = $pdo->prepare("
    INSERT INTO subsidies (name, category, description, benefits, eligibility, apply_link, status, last_updated)
    VALUES (:name, :category, :description, :benefits, :eligibility, :apply_link, :status, CURRENT_TIMESTAMP)
    ON CONFLICT DO NOTHING
");

foreach ($subsidies as $s) {
    try {
        $insertS->execute($s);
        $results[] = "✅ Subsidy: {$s['name']}";
    } catch (Exception $e) {
        $results[] = "⚠️ Skip subsidy {$s['name']}: " . $e->getMessage();
    }
}

// ─── Output ──────────────────────────────────────────────────────────────────
echo '<html><body style="font-family:monospace;padding:20px;"><h2>Seed Results</h2><pre>';
echo implode("\n", $results);
echo '</pre><p><strong>Done!</strong> Delete this file after seeding. <a href="/dashboard/admin.php">→ Go to Admin</a></p></body></html>';
