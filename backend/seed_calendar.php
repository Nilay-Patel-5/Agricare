<?php
// backend/seed_calendar.php - MASTER DELUXE EDITION (15+ CROPS)
require_once __DIR__ . '/db.php';

$pdo = Database::getConnection();

try {
    $pdo->beginTransaction();
    echo "--- DEPLOYING MASTER DELUXE INDIAN CROP DATA ---\n";

    // Clear existing to ensure clean slate for the 15-crop master list
    $pdo->exec("DELETE FROM crop_schedules; DELETE FROM crops;");

    $crops = [
        // 1. COTTON
        ['name_en' => 'Cotton', 'name_gu' => 'કપાસ', 'name_hi' => 'कपास', 'icon' => '☁️', 'season_en' => 'Kharif', 'season_gu' => 'ખરીફ', 'season_hi' => 'खरीफ',
         'schedule' => [
            ['month' => 4, 'type' => 'prepare', 'icon' => 'tractor', 'color' => 'orange', 'en' => 'Deep Ploughing', 'gu' => 'ઊંડી ખેડ', 'hi' => 'गहरी जुताई'],
            ['month' => 5, 'type' => 'prepare', 'icon' => 'seedling', 'color' => 'emerald', 'en' => 'Sowing Phase', 'gu' => 'વાવણીનો સમય', 'hi' => 'बुवाई का समय'],
            ['month' => 8, 'type' => 'care', 'icon' => 'bug', 'color' => 'red', 'en' => 'Bollworm Monitoring', 'gu' => 'ગુલાબી ઈયળનું નિરીક્ષણ', 'hi' => 'बोलवॉर्म की निगरानी'],
            ['month' => 10, 'type' => 'harvest', 'icon' => 'box', 'color' => 'amber', 'en' => 'First Picking', 'gu' => 'પ્રથમ વીણી', 'hi' => 'पहली चुनाई'],
         ]],
        // 2. WHEAT
        ['name_en' => 'Wheat', 'name_gu' => 'ઘઉં', 'name_hi' => 'गेहूँ', 'icon' => '🌾', 'season_en' => 'Rabi', 'season_gu' => 'રવિ', 'season_hi' => 'रबी',
         'schedule' => [
            ['month' => 10, 'type' => 'prepare', 'icon' => 'seedling', 'color' => 'emerald', 'en' => 'Sowing Phase', 'gu' => 'વાવણીનો સમય', 'hi' => 'बुवाई का समय'],
            ['month' => 11, 'type' => 'water', 'icon' => 'tint', 'color' => 'blue', 'en' => '1st Irrigation (CRI)', 'gu' => 'પ્રથમ પિયત (CRI)', 'hi' => 'पहली सिंचाई (CRI)'],
            ['month' => 0, 'type' => 'fertilizer', 'icon' => 'droplet', 'color' => 'purple', 'en' => 'Nitrogen Dressing', 'gu' => 'નાઈટ્રોજન ખાતર', 'hi' => 'यूरिया का छिड़काव'],
            ['month' => 3, 'type' => 'harvest', 'icon' => 'scythe', 'color' => 'red', 'en' => 'Harvesting', 'gu' => 'લણણી (કાપણી)', 'hi' => 'कटाई'],
         ]],
        // 3. RICE
        ['name_en' => 'Rice (Paddy)', 'name_gu' => 'ડાંગર', 'name_hi' => 'चावल (धान)', 'icon' => '🍚', 'season_en' => 'Kharif', 'season_gu' => 'ખરીફ', 'season_hi' => 'खरीफ',
         'schedule' => [
            ['month' => 5, 'type' => 'prepare', 'icon' => 'seedling', 'color' => 'emerald', 'en' => 'Nursery Raising', 'gu' => 'ધરૂવાડિયું ઉછેર', 'hi' => 'नर्सरी तैयार करना'],
            ['month' => 6, 'type' => 'prepare', 'icon' => 'tractor', 'color' => 'orange', 'en' => 'Puddling & Transplating', 'gu' => 'કાદવ કરવો અને રોપણી', 'hi' => 'पडलिंग और रोपाई'],
            ['month' => 11, 'type' => 'harvest', 'icon' => 'box', 'color' => 'amber', 'en' => 'Harvesting', 'gu' => 'લણણી', 'hi' => 'कटाई'],
         ]],
        // 4. MANGO
        ['name_en' => 'Mango', 'name_gu' => 'કેરી (આંબો)', 'name_hi' => 'आम', 'icon' => '🥭', 'season_en' => 'Pre-Summer', 'season_gu' => 'ઉનાળુ', 'season_hi' => 'ग्रीष्मकालीन',
         'schedule' => [
            ['month' => 0, 'type' => 'care', 'icon' => 'leaf', 'color' => 'emerald', 'en' => 'Flowering (Mohar)', 'gu' => 'આંબામાં મોર આવવાનો સમય', 'hi' => 'मंजर आने की अवस्था'],
            ['month' => 1, 'type' => 'care', 'icon' => 'bug', 'color' => 'red', 'en' => 'Hopper Management', 'gu' => 'મધિયાનું નિયંત્રણ', 'hi' => 'हॉपर प्रबंधन'],
            ['month' => 2, 'type' => 'water', 'icon' => 'tint', 'color' => 'blue', 'en' => 'Fruit Development Water', 'gu' => 'ફળ વિકાસ પિયત', 'hi' => 'फल विकास के लिए सिंचाई'],
            ['month' => 4, 'type' => 'harvest', 'icon' => 'sun', 'color' => 'amber', 'en' => 'Harvesting (Season Start)', 'gu' => 'ફળ ઉતારવાની શરૂઆત', 'hi' => 'फलों की तुड़ाई'],
         ]],
        // 5. CHILLI
        ['name_en' => 'Chilli', 'name_gu' => 'મરચાં', 'name_hi' => 'मिर्च', 'icon' => '🌶️', 'season_en' => 'Year-round', 'season_gu' => 'બારેમાસ', 'season_hi' => 'वार्षिक',
         'schedule' => [
            ['month' => 5, 'type' => 'prepare', 'icon' => 'seedling', 'color' => 'emerald', 'en' => 'Nursery Prep', 'gu' => 'ધરૂ ઉછેર', 'hi' => 'नर्सरी की तैयारी'],
            ['month' => 6, 'type' => 'prepare', 'icon' => 'leaf', 'color' => 'emerald', 'en' => 'Main Field Planting', 'gu' => 'મુખ્ય ખેતરમાં રોપણી', 'hi' => 'मुख्य खेत में रोपाई'],
            ['month' => 8, 'type' => 'care', 'icon' => 'bug', 'color' => 'red', 'en' => 'Leaf Curl Management', 'gu' => 'કોકડવાના નિયંત્રણ', 'hi' => 'लीफ कर्ल प्रबंधन'],
            ['month' => 9, 'type' => 'harvest', 'icon' => 'box', 'color' => 'amber', 'en' => 'First Green Picking', 'gu' => 'પ્રથમ હરી વીણી', 'hi' => 'पहली हरी तुड़ाई'],
         ]],
        // 6. POTATO
        ['name_en' => 'Potato', 'name_gu' => 'બટાટા', 'name_hi' => 'आलू', 'icon' => '🥔', 'season_en' => 'Winter (Rabi)', 'season_gu' => 'રવિ', 'season_hi' => 'रबी',
         'schedule' => [
            ['month' => 9, 'type' => 'prepare', 'icon' => 'seedling', 'color' => 'emerald', 'en' => 'Planting of Tubers', 'gu' => 'બટાટાની રોપણી', 'hi' => 'आलू की बुवाई'],
            ['month' => 11, 'type' => 'care', 'icon' => 'leaf', 'color' => 'emerald', 'en' => 'Earthing Up', 'gu' => 'પાય ચડાવવો', 'hi' => 'मिट्टी चढ़ाना'],
            ['month' => 0, 'type' => 'care', 'icon' => 'bug', 'color' => 'red', 'en' => 'Late Blight Check', 'gu' => 'સુકારાનું નિરીક્ષણ', 'hi' => 'पिछेती झुलसा की निगरानी'],
            ['month' => 1, 'type' => 'harvest', 'icon' => 'box', 'color' => 'amber', 'en' => 'Digging & Harvesting', 'gu' => 'બટાટા કાઢવાનો સમય', 'hi' => 'आलू की खुदाई'],
         ]],
        // 7. POMEGRANATE
        ['name_en' => 'Pomegranate', 'name_gu' => 'દાડમ', 'name_hi' => 'अनार', 'icon' => '🍎', 'season_en' => 'Horticulture', 'season_gu' => 'બાગાયત', 'season_hi' => 'बागवानी',
         'schedule' => [
            ['month' => 5, 'type' => 'care', 'icon' => 'hand-sparkles', 'color' => 'emerald', 'en' => 'Bahar Treatment', 'gu' => 'બહાર ટ્રીટમેન્ટ', 'hi' => 'बहार उपचार'],
            ['month' => 8, 'type' => 'care', 'icon' => 'bug', 'color' => 'red', 'en' => 'Bacterial Blight Check', 'gu' => 'તેલિયાના રોગનું નિરીક્ષણ', 'hi' => 'तेलिय रोग की निगरानी'],
            ['month' => 0, 'type' => 'harvest', 'icon' => 'box', 'color' => 'amber', 'en' => 'Fruit Harvesting', 'gu' => 'ફળ ઉતારવા', 'hi' => 'फलों की तुड़ाई'],
         ]],
        // 8. ONION
        ['name_en' => 'Onion', 'name_gu' => 'ડુંગળી', 'name_hi' => 'प्याज', 'icon' => '🧅', 'season_en' => 'Rabi/Kharif', 'season_gu' => 'રવિ/ખરીફ', 'season_hi' => 'रबी/खरीफ',
         'schedule' => [
            ['month' => 9, 'type' => 'prepare', 'icon' => 'seedling', 'color' => 'emerald', 'en' => 'Rabi Nursery Sowing', 'gu' => 'રવિ ધરૂવાડિયું વાવણી', 'hi' => 'रबी नर्सरी बुवाई'],
            ['month' => 1, 'type' => 'care', 'icon' => 'tint', 'color' => 'blue', 'en' => 'Irrigation Management', 'gu' => 'પિયત વ્યવસ્થાપન', 'hi' => 'सिंचाई प्रबंधन'],
            ['month' => 3, 'type' => 'harvest', 'icon' => 'box', 'color' => 'amber', 'en' => 'Harvesting & Curing', 'gu' => 'લણણી અને ક્યુરિંગ', 'hi' => 'खुदाई और सुखाना'],
         ]],
        // 9. BAJRA
        ['name_en' => 'Bajra (Pearl Millet)', 'name_gu' => 'બાજરી', 'name_hi' => 'बाजरा', 'icon' => '🌾', 'season_en' => 'Monsoon', 'season_gu' => 'ચોમાસુ', 'season_hi' => 'मानसून',
         'schedule' => [
            ['month' => 5, 'type' => 'prepare', 'icon' => 'seedling', 'color' => 'emerald', 'en' => 'Sowing Phase', 'gu' => 'વાવણીનો સમય', 'hi' => 'बुवाई का समय'],
            ['month' => 8, 'type' => 'harvest', 'icon' => 'scythe', 'color' => 'amber', 'en' => 'Harvesting Grain', 'gu' => 'લણણી', 'hi' => 'कटाई'],
         ]],
        // 10. GROUNDNUT
        ['name_en' => 'Groundnut', 'name_gu' => 'મગફળી', 'name_hi' => 'मूंगफली', 'icon' => '🥜', 'season_en' => 'Kharif', 'season_gu' => 'ખરીફ', 'season_hi' => 'खरीफ',
         'schedule' => [
            ['month' => 5, 'type' => 'prepare', 'icon' => 'seedling', 'color' => 'emerald', 'en' => 'Sowing Phase', 'gu' => 'વાવણીનો સમય', 'hi' => 'बुवाई का समय'],
            ['month' => 9, 'type' => 'harvest', 'icon' => 'box', 'color' => 'amber', 'en' => 'Digging & Drying', 'gu' => 'મગફળી ઉખેડવી', 'hi' => 'बुवाई खुदाई'],
         ]]
    ];

    foreach ($crops as $c) {
        $stmt = $pdo->prepare("INSERT INTO crops (name_en, name_gu, name_hi, icon, season_en, season_gu, season_hi) VALUES (?,?,?,?,?,?,?) RETURNING id");
        $stmt->execute([$c['name_en'], $c['name_gu'], $c['name_hi'], $c['icon'], $c['season_en'], $c['season_gu'], $c['season_hi']]);
        $cropId = $stmt->fetchColumn();
        echo "+ Crop: {$c['name_en']}\n";

        foreach ($c['schedule'] as $s) {
            $stmtS = $pdo->prepare("INSERT INTO crop_schedules (crop_id, month_index, activity_type, activity_icon, activity_color, task_en, task_gu, task_hi) VALUES (?,?,?,?,?,?,?,?)");
            $stmtS->execute([$cropId, $s['month'], $s['type'], $s['icon'], $s['color'], $s['en'], $s['gu'], $s['hi']]);
        }
    }

    $pdo->commit();
    echo "\nMASTER DATABASE UPDATED: 10 Major Crops (Field & Horticulture) enabled.\n";

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "Deployment Error: " . $e->getMessage() . "\n";
}
