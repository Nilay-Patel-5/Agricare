<?php
require_once __DIR__ . '/db.php';
try {
    $pdo = Database::getConnection();

    // Remove existing crops beyond wheat and rice (IDs > 2)
    $pdo->exec("DELETE FROM crop_schedules WHERE crop_id > 2");
    $pdo->exec("DELETE FROM crops WHERE id > 2");
    
    // Reset sequence so IDs are consistent if desired, but not strictly necessary.
    $pdo->exec("SELECT setval('crops_id_seq', 2, true)");

    $cropsList = [
        ['Maize', 'મકાઈ', 'मक्का', '🌽', 'Kharif', 'ખરીફ', 'खरीफ', 'Cereal'],
        ['Barley', 'જવ', 'जौ', '🌾', 'Rabi', 'રબી', 'रबी', 'Cereal'],
        ['Jowar (Sorghum)', 'જુવાર', 'ज्वार', '🌾', 'Kharif', 'ખરીફ', 'खरीफ', 'Cereal'],
        ['Bajra (Pearl Millet)', 'બાજરી', 'बाजरा', '🌾', 'Kharif', 'ખરીફ', 'खरीफ', 'Cereal'],
        ['Ragi (Finger Millet)', 'રાગી', 'रागी', '🌾', 'Kharif', 'ખરીફ', 'खरीफ', 'Cereal'],
        ['Bengal Gram (Chana)', 'ચણા', 'चना', '🌱', 'Rabi', 'રબી', 'रबी', 'Pulse'],
        ['Tur / Arhar Dal', 'તુવેર', 'अरहर', '🌱', 'Kharif', 'ખરીફ', 'खरीफ', 'Pulse'],
        ['Moong (Green Gram)', 'મગ', 'मूंग', '🌱', 'Kharif', 'ખરીફ', 'खरीफ', 'Pulse'],
        ['Urad (Black Gram)', 'અડદ', 'उड़द', '🌱', 'Kharif', 'ખરીફ', 'खरीफ', 'Pulse'],
        ['Masoor (Lentil)', 'મસૂર', 'मसूर', '🌱', 'Rabi', 'રબી', 'रबी', 'Pulse'],
        ['Peas', 'વટાણા', 'मटर', '🟢', 'Rabi', 'રબી', 'रबी', 'Pulse'],
        ['Horse Gram', 'કળથી', 'कुल्थी', '🌱', 'Rabi', 'રબી', 'रबी', 'Pulse'],
        ['Groundnut', 'મગફળી', 'मूंगफली', '🥜', 'Kharif', 'ખરીફ', 'खरीफ', 'Oilseed'],
        ['Mustard', 'રાયડો', 'सरसों', '🌼', 'Rabi', 'રબી', 'रबी', 'Oilseed'],
        ['Soyabean', 'સોયાબીન', 'सोयाबीन', '🌱', 'Kharif', 'ખરીફ', 'खरीफ', 'Oilseed'],
        ['Sunflower', 'સૂર્યમુખી', 'सूरजमुखी', '🌻', 'Kharif', 'ખરીફ', 'खरीफ', 'Oilseed'],
        ['Sesame (Til)', 'તલ', 'तिल', '🌱', 'Kharif', 'ખરીફ', 'खरीफ', 'Oilseed'],
        ['Castor Seed', 'એરંડા', 'अरंडी', '🌱', 'Kharif', 'ખરીફ', 'खरीफ', 'Oilseed'],
        ['Linseed', 'અળસી', 'अलसी', '🌱', 'Rabi', 'રબી', 'रबी', 'Oilseed'],
        ['Niger Seed', 'રામતીલ', 'रामतिल', '🌱', 'Kharif', 'ખરીફ', 'खरीफ', 'Oilseed'],
        ['Cotton', 'કપાસ', 'कपास', '☁️', 'Kharif', 'ખરીફ', 'खरीफ', 'Cash Crop'],
        ['Sugarcane', 'શેરડી', 'गन्ना', '🎋', 'Kharif', 'ખરીફ', 'खरीफ', 'Cash Crop'],
        ['Jute', 'શણ', 'जूट', '🌾', 'Kharif', 'ખરીફ', 'खरीफ', 'Cash Crop'],
        ['Tobacco', 'તમાકુ', 'तंबाकू', '🍂', 'Kharif', 'ખરીફ', 'खरीफ', 'Cash Crop'],
        ['Potato', 'બટાટા', 'आलू', '🥔', 'Rabi', 'રબી', 'रबी', 'Vegetable'],
        ['Onion', 'ડુંગળી', 'प्याज', '🧅', 'Rabi', 'રબી', 'रबी', 'Vegetable'],
        ['Tomato', 'ટામેટા', 'टमाटर', '🍅', 'Rabi', 'રબી', 'रबी', 'Vegetable'],
        ['Brinjal', 'રીંગણ', 'बैंगन', '🍆', 'Kharif', 'ખરીફ', 'खरीफ', 'Vegetable'],
        ['Cabbage', 'કોબીજ', 'पत्ता गोभी', '🥬', 'Rabi', 'રબી', 'रबी', 'Vegetable'],
        ['Cauliflower', 'ફુલાવર', 'फूलगोभी', '🥦', 'Rabi', 'રબી', 'रबी', 'Vegetable'],
        ['Green Chilli', 'લીલા મરચા', 'हरी मिर्च', '🌶️', 'Kharif', 'ખરીફ', 'खरीफ', 'Spice'],
        ['Okra (Bhindi)', 'ભીંડા', 'भिंडी', '🥒', 'Kharif', 'ખરીફ', 'खरीफ', 'Vegetable'],
        ['Bottle Gourd', 'દૂધી', 'लौकी', '🥒', 'Kharif', 'ખરીફ', 'खरीफ', 'Vegetable'],
        ['Pumpkin', 'કોળું', 'कद्दू', '🎃', 'Kharif', 'ખરીફ', 'खरीफ', 'Vegetable'],
        ['Mango', 'કેરી', 'आम', '🥭', 'Perennial', 'બારમાસી', 'बारहमासी', 'Fruit'],
        ['Banana', 'કેળા', 'केला', '🍌', 'Perennial', 'બારમાસી', 'बारहमासी', 'Fruit'],
        ['Apple', 'સફરજન', 'सेब', '🍎', 'Perennial', 'બારમાસી', 'बारहमासी', 'Fruit'],
        ['Orange', 'સંતરા', 'संतरा', '🍊', 'Perennial', 'બારમાસી', 'बारहमासी', 'Fruit'],
        ['Papaya', 'પપૈયું', 'पपीता', '🍈', 'Perennial', 'બારમાસી', 'बारहमासी', 'Fruit'],
        ['Grapes', 'દ્રાક્ષ', 'अंगूर', '🍇', 'Perennial', 'બારમાસી', 'बारहमासी', 'Fruit'],
        ['Guava', 'જામફળ', 'अमरूद', '🍐', 'Perennial', 'બારમાસી', 'बारहमासी', 'Fruit'],
        ['Pineapple', 'અનાનસ', 'अनानास', '🍍', 'Perennial', 'બારમાસી', 'बारहमासी', 'Fruit'],
        ['Pomegranate', 'દાડમ', 'अनार', '🍎', 'Perennial', 'બારમાસી', 'बारहमासी', 'Fruit'],
        ['Turmeric', 'હળદર', 'हल्दी', '🫚', 'Kharif', 'ખરીફ', 'खरीफ', 'Spice'],
        ['Coriander', 'ધાણા', 'धनिया', '🌿', 'Rabi', 'રબી', 'रबी', 'Spice'],
        ['Cumin (Jeera)', 'જીરું', 'जीरा', '🌱', 'Rabi', 'રબી', 'रबी', 'Spice'],
        ['Garlic', 'લસણ', 'लहसुन', '🧄', 'Rabi', 'રબી', 'रबी', 'Spice'],
        ['Ginger', 'આદુ', 'अदरक', '🫚', 'Kharif', 'ખરીફ', 'खरीफ', 'Spice'],
        ['Black Pepper', 'કાળા મરી', 'काली मिर्च', '🌶️', 'Perennial', 'બારમાસી', 'बारहमासी', 'Spice'],
        ['Red Chilli', 'લાલ મરચું', 'लाल मिर्च', '🌶️', 'Kharif', 'ખરીફ', 'खरीफ', 'Spice'],
        ['Tea', 'ચા', 'चाय', '🍵', 'Perennial', 'બારમાસી', 'बारहमासी', 'Plantation'],
        ['Coffee', 'કોફી', 'कॉफी', '☕', 'Perennial', 'બારમાસી', 'बारहमासी', 'Plantation'],
        ['Rubber', 'રબર', 'रबर', '🌳', 'Perennial', 'બારમાસી', 'बारहमासी', 'Plantation'],
        ['Copra', 'કોપરું', 'खोपरा', '🥥', 'Perennial', 'બારમાસી', 'बारहमासी', 'Plantation'],
        ['Dry Coconut', 'સૂકું નાળિયેર', 'सूखा नारियल', '🥥', 'Perennial', 'બારમાસી', 'बारहमासी', 'Plantation'],
        ['Tamarind', 'આમલી', 'इमली', '🟤', 'Perennial', 'બારમાસી', 'बारहमासी', 'Plantation'],
        ['Betel Leaves', 'નાગરવેલના પાન', 'पान के पत्ते', '🍃', 'Perennial', 'બારમાસી', 'बारहमासी', 'Plantation'],
        ['Arecanut', 'સોપારી', 'सुपारी', '🌰', 'Perennial', 'બારમાસી', 'बारहमासी', 'Plantation'],
        ['Honey', 'મધ', 'शहद', '🍯', 'Perennial', 'બારમાસી', 'बारहमासी', 'Allied']
    ];

    $insertCropStmt = $pdo->prepare("INSERT INTO crops (name_en, name_gu, name_hi, icon, season_en, season_gu, season_hi, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?) RETURNING id");
    
    $insertSchedStmt = $pdo->prepare("INSERT INTO crop_schedules (crop_id, month_index, activity_type, activity_icon, activity_color, task_en, task_gu, task_hi, start_day, end_day) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $generalSchedule = [
        ['activity_icon' => 'seedling', 'activity_color' => 'emerald', 'task_en' => 'Soil Preparation', 'task_gu' => 'જમીનની તૈયારી', 'task_hi' => 'मिट्टी की तैयारी', 'start' => 0, 'end' => 10],
        ['activity_icon' => 'seedling', 'activity_color' => 'emerald', 'task_en' => 'Sowing/Planting', 'task_gu' => 'વાવણી/રોપણી', 'task_hi' => 'बुवाई/रोपण', 'start' => 11, 'end' => 20],
        ['activity_icon' => 'tint', 'activity_color' => 'blue', 'task_en' => 'First Irrigation', 'task_gu' => 'પ્રથમ પિયત', 'task_hi' => 'पहली सिंचाई', 'start' => 21, 'end' => 40],
        ['activity_icon' => 'flask', 'activity_color' => 'purple', 'task_en' => 'Fertilizer & Weeding', 'task_gu' => 'ખાતર અને નીંદણ', 'task_hi' => 'उर्वरक और निराई', 'start' => 41, 'end' => 60],
        ['activity_icon' => 'leaf', 'activity_color' => 'green', 'task_en' => 'Growth & Care', 'task_gu' => 'વિકાસ અને કાળજી', 'task_hi' => 'विकास और देखभाल', 'start' => 61, 'end' => 90],
        ['activity_icon' => 'scissors', 'activity_color' => 'amber', 'task_en' => 'Harvesting', 'task_gu' => 'લણણી', 'task_hi' => 'कटाई', 'start' => 91, 'end' => 110],
    ];

    $pdo->beginTransaction();

    foreach ($cropsList as $crop) {
        $insertCropStmt->execute($crop);
        $cropId = $insertCropStmt->fetchColumn();

        foreach ($generalSchedule as $index => $stage) {
            $insertSchedStmt->execute([
                $cropId,
                0, // month_index
                '', // activity_type
                $stage['activity_icon'],
                $stage['activity_color'],
                $stage['task_en'],
                $stage['task_gu'],
                $stage['task_hi'],
                $stage['start'],
                $stage['end']
            ]);
        }
    }

    $pdo->commit();
    echo "Successfully seeded " . count($cropsList) . " new crops and their schedules.";

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error: " . $e->getMessage();
}
