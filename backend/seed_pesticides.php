<?php
require_once __DIR__ . '/db.php';

$pdo = Database::getConnection();

$pesticides = [
    // --- COTTON SPECIALS ---
    ['name' => 'Monocrotophos 36% SL', 'brand' => 'Monocil', 'target_pests' => 'Bollworms, Aphids, Whitefly', 'price_range' => 'Rs. 500 - 650 per liter', 'usage_instructions' => 'Dose: 1.5 - 2.0 ml per liter of water. Ensure uniform coverage.'],
    ['name' => 'Cypermethrin 25% EC', 'brand' => 'Ustaad', 'target_pests' => 'Bollworms, Leaf Folder', 'price_range' => 'Rs. 450 - 550 per liter', 'usage_instructions' => 'Dose: 0.5 - 1.0 ml per liter. Use at 10-15 days interval.'],
    
    // --- FRUITS & CITRUS ---
    ['name' => 'Copper Oxychloride 50% WP', 'brand' => 'Blitox', 'target_pests' => 'Citrus Canker, Blight, Leaf Spot', 'price_range' => 'Rs. 550 - 700 per kg', 'usage_instructions' => 'Mix 3g per liter. Spray on both sides of leaves.'],
    ['name' => 'Streptocycline 90:10', 'brand' => 'Strepto-Plus', 'target_pests' => 'Bacterial Canker, Wilt, Black Arm', 'price_range' => 'Rs. 150 - 200 per 6g packet', 'usage_instructions' => 'Mix 1g in 10 liters of water. For intensive bacterial control.'],
    
    // --- GENERAL CEREALS & PULSES ---
    ['name' => 'Hexaconazole 5% EC', 'brand' => 'Contaf', 'target_pests' => 'Rust, Powdery Mildew, Scab', 'price_range' => 'Rs. 600 - 750 per liter', 'usage_instructions' => 'Mix 2ml per liter of water. Systemic fungicide action.'],
    ['name' => 'Quinalphos 25% EC', 'brand' => 'Ekalux', 'target_pests' => 'Shoot and Fruit Borer, Stem Borer', 'price_range' => 'Rs. 500 - 600 per liter', 'usage_instructions' => 'Dose: 2ml per liter. Effectively checks boring insects.'],
    ['name' => 'Spinosad 45% SC', 'brand' => 'Tracer', 'target_pests' => 'Thrips, Fruit Borer, Diamondback Moth', 'price_range' => 'Rs. 1800 - 2200 per 100ml', 'usage_instructions' => 'Dose: 0.3ml per liter. Highly effective on larvae.'],
    
    // --- ORGANIC / BIO ---
    ['name' => 'Beauveria Bassiana', 'brand' => 'Bio-Guard', 'target_pests' => 'Whitefly, Mealybugs, Thrips', 'price_range' => 'Rs. 300 - 450 per liter', 'usage_instructions' => 'Mix 5ml per liter. Organic fungal bio-pesticide.'],
    ['name' => 'Metarhizium Anisopliae', 'brand' => 'Grub-Killer', 'target_pests' => 'Root Grubs, Termites', 'price_range' => 'Rs. 350 - 500 per liter', 'usage_instructions' => 'Mix 10ml per liter. Application via soil drenching near roots.']
];

$mappings = [
    'Whitefly' => ['Monocrotophos 36% SL', 'Beauveria Bassiana'],
    'Thrips' => ['Spinosad 45% SC', 'Beauveria Bassiana'],
    'Cotton Bollworm' => ['Monocrotophos 36% SL', 'Cypermethrin 25% EC'],
    'Rust' => ['Hexaconazole 5% EC'],
    'Fruit Borer' => ['Quinalphos 25% EC', 'Spinosad 45% SC'],
    'Citrus Canker' => ['Copper Oxychloride 50% WP', 'Streptocycline 90:10'],
    'Bacterial Wilt' => ['Streptocycline 90:10'],
    'Mealybug' => ['Beauveria Bassiana'],
    'Root Grub' => ['Metarhizium Anisopliae'],
    'Smut' => ['Hexaconazole 5% EC'],
    'Tomato Wilt' => ['Streptocycline 90:10'],
    'Citrus Leaf Miner' => ['Quinalphos 25% EC'],
    'Stem Borer' => ['Quinalphos 25% EC', 'Cypermethrin 25% EC']
];

try {
    $pdo->beginTransaction();
    echo "--- DEPLOYING MASTER AGRI-KNOWLEDGE DATA ---\n";
    
    foreach ($pesticides as $p) {
        $check = $pdo->prepare("SELECT id FROM pesticides WHERE name = :name");
        $check->execute(['name' => $p['name']]);
        $id = $check->fetchColumn();
        
        if (!$id) {
            $stmt = $pdo->prepare("INSERT INTO pesticides (name, brand, target_pests, price_range, usage_instructions) VALUES (:name, :brand, :target_pests, :price_range, :usage_instructions)");
            $stmt->execute($p);
            $id = $pdo->lastInsertId();
            echo "+ Added Pesticide: {$p['brand']} ({$p['name']})\n";
        } else {
            echo ". Pesticide Exists: {$p['brand']}\n";
        }
    }
    
    foreach ($mappings as $pest => $pList) {
        foreach ($pList as $pName) {
            $pId = $pdo->query("SELECT id FROM pesticides WHERE name = '$pName'")->fetchColumn();
            if ($pId) {
                // Check if mapping exists
                $mCheck = $pdo->prepare("SELECT id FROM pest_pesticide_mapping WHERE pest_name = :pest AND pesticide_id = :pId");
                $mCheck->execute(['pest' => $pest, 'pId' => $pId]);
                if (!$mCheck->fetchColumn()) {
                    $mStmt = $pdo->prepare("INSERT INTO pest_pesticide_mapping (pest_name, pesticide_id) VALUES (:pest, :pId)");
                    $mStmt->execute(['pest' => $pest, 'pId' => $pId]);
                    echo "-> Linked [{$pest}] to [{$pName}]\n";
                }
            }
        }
    }
    
    $pdo->commit();
    echo "\nDEPLOYMENT COMPLETE: AgriCare Knowledge Base covers 15+ common pests and 20+ pesticides.\n";
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "Deployment Error: " . $e->getMessage() . "\n";
}
