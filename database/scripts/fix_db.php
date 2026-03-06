<?php
require_once __DIR__ . '/db.php';

try {
    $pdo = Database::getConnection();

    // 1. Create Subsidies Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS subsidies (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255),
            name_gu VARCHAR(255),
            name_hi VARCHAR(255),
            category VARCHAR(100),
            category_gu VARCHAR(100),
            category_hi VARCHAR(100),
            description TEXT,
            description_gu TEXT,
            description_hi TEXT,
            benefits TEXT,
            benefits_gu TEXT,
            benefits_hi TEXT,
            eligibility TEXT,
            eligibility_gu TEXT,
            eligibility_hi TEXT,
            apply_link VARCHAR(255),
            status VARCHAR(50),
            last_updated DATE
        );
    ");

    // Clear existing data
    $pdo->exec("TRUNCATE TABLE subsidies RESTART IDENTITY;");

    // Seed Subsidies
    $subsidies = [
        [
            'Pradhan Mantri Kisan Samman Nidhi (PM-KISAN)',
            'પ્રધાનમંત્રી કિસાન સન્માન નિધિ',
            'प्रधानमंत्री किसान सम्मान निधि',
            'Income Support',
            'આવક સહાય',
            'आय सहायता',
            'Direct income support of ₹6,000 per year to farmer families.',
            'ખેડૂત પરિવારોને વાર્ષિક ₹૬,૦૦૦ની સીધી આવક સહાય.',
            'किसान परिवारों को प्रति वर्ष ₹6,000 की प्रत्यक्ष आय सहायता।',
            '₹2,000 every 4 months.',
            'દર ૪ મહિને ₹૨,૦૦૦.',
            'हर 4 महीने में ₹2,000।',
            'Small and marginal farmers.',
            'નાના અને સીમાંત ખેડૂતો.',
            'छोटे और सीमांत किसान।',
            'https://pmkisan.gov.in/',
            'Live',
            date('Y-m-d')
        ],
        [
            'Gujarat State Subsidy for Drip Irrigation',
            'ટપક સિંચાઈ માટે ગુજરાત રાજ્ય સબસિડી',
            'ड्रिप सिंचाई के लिए गुजरात राज्य सब्सिडी',
            'Irrigation',
            'સિંચાઈ',
            'सिंचाई',
            'Financial assistance for installing micro-irrigation systems.',
            'માઇક્રો-ઇરિગેશન સિસ્ટમ સ્થાપિત કરવા માટે નાણાકીય સહાય.',
            'सूक्ष्म सिंचाई प्रणाली स्थापित करने के लिए वित्तीय सहायता।',
            'Up to 70% to 90% subsidy depending on category.',
            'કેટેગરી મુજબ ૭૦% થી ૯૦% સબસિડી.',
            'श्रेणी के आधार पर 70% से 90% तक सब्सिडी।',
            'All farmers in Gujarat state.',
            'ગુજરાત રાજ્યના તમામ ખેડૂતો.',
            'गुजरात राज्य के सभी किसान।',
            'https://ikhedut.gujarat.gov.in/',
            'Live',
            date('Y-m-d')
        ],
        [
            'Agriculture Equipment Subsidy (i-Khedut)',
            'કૃષિ સાધનોની સબસિડી (આઈ-ખેડૂત)',
            'कृषि उपकरण सब्सिडी (आई-खेड़ूत)',
            'Equipment',
            'સાધનો',
            'उपकरण',
            'Subsidy for purchase of tractors, rotavators, and other tools.',
            'ટ્રેક્ટર, રોટાવેટર અને અન્ય સાધનોની ખરીદી માટે સબસિડી.',
            'ट्रैक्टर, रोटावेटर और अन्य उपकरणों की खरीद के लिए सब्सिडी।',
            'Flat percentage discount based on tool type.',
            'સાધનના પ્રકારના આધારે નિશ્ચિત ટકાવારી છૂટ.',
            'उपकरण के प्रकार के आधार पर निश्चित प्रतिशत छूट।',
            'Registered farmers on i-Khedut portal.',
            'આઈ-ખેડૂત પોર્ટલ પર નોંધાયેલા ખેડૂતો.',
            'आई-खेड़ूत पोर्टल पर पंजीकृत किसान।',
            'https://ikhedut.gujarat.gov.in/',
            'Upcoming',
            date('Y-m-d')
        ]
    ];

    $stmt = $pdo->prepare("
        INSERT INTO subsidies 
        (name, name_gu, name_hi, category, category_gu, category_hi, description, description_gu, description_hi, benefits, benefits_gu, benefits_hi, eligibility, eligibility_gu, eligibility_hi, apply_link, status, last_updated) 
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($subsidies as $sub) {
        $stmt->execute($sub);
    }
    echo "Subsidies seeded into PostgreSQL successfully!\n";

    // 3. User Management
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            phone VARCHAR(20) UNIQUE,
            email VARCHAR(255) UNIQUE, 
            password TEXT,
            dob DATE,
            role VARCHAR(20) DEFAULT 'farmer',
            district VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // Default Admin (if not exists)
    $admin_email = 'admin@agricare.com';
    $checkAdmin = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $checkAdmin->execute([$admin_email]);
    if ($checkAdmin->fetchColumn() == 0) {
        $admin_pass = password_hash('Admin@123', PASSWORD_BCRYPT);
        $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)")
            ->execute(['System Admin', $admin_email, $admin_pass, 'admin']);
        echo "Default admin created: admin@agricare.com / Admin@123\n";
    }

    // 4. Crop Calendar
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS crops (
            id SERIAL PRIMARY KEY,
            name_en VARCHAR(100) NOT NULL,
            name_gu VARCHAR(100),
            name_hi VARCHAR(100),
            icon VARCHAR(50),
            season_en VARCHAR(100),
            season_gu VARCHAR(100),
            season_hi VARCHAR(100)
        );
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS crop_schedules (
            id SERIAL PRIMARY KEY,
            crop_id INT REFERENCES crops(id) ON DELETE CASCADE,
            month_index INT NOT NULL,
            activity_type VARCHAR(50),
            activity_icon VARCHAR(50),
            activity_color VARCHAR(20),
            task_en TEXT,
            task_gu TEXT,
            task_hi TEXT
        );
    ");

    $pdo->exec("TRUNCATE TABLE crops RESTART IDENTITY CASCADE;");

    // Seed Crops
    $stmtCrop = $pdo->prepare("INSERT INTO crops (name_en, name_gu, name_hi, icon, season_en, season_gu, season_hi) VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING id");
    
    // Wheat
    $stmtCrop->execute(['Wheat', 'ઘઉં', 'गेहूँ', '🌾', 'Rabi (Winter)', 'રવિ (શિયાળુ)', 'रबी (सर्दियों)']);
    $wheatId = $stmtCrop->fetchColumn();

    // Cotton
    $stmtCrop->execute(['Cotton', 'કપાસ', 'કપાસ', '☁️', 'Kharif (Monsoon)', 'ખરીફ (ચોમાસુ)', 'खरीफ (मानसून)']);
    $cottonId = $stmtCrop->fetchColumn();

    $stmtSched = $pdo->prepare("INSERT INTO crop_schedules (crop_id, month_index, activity_type, activity_icon, activity_color, task_en, task_gu, task_hi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Wheat Schedule
    $wheatSchedule = [
        [10, 'prepare', 'tractor', 'orange', 'Field Preparation & Sowing', 'ખેતરની તૈયારી અને વાવણી', 'ફીલ્ડ કી તૈયારી એવં બુવાઈ'],
        [11, 'water', 'tint', 'blue', 'First Irrigation (CRI Stage)', 'પ્રથમ પિયત (CRI તબક્કો)', 'પહલી સિંચાઈ'],
        [12, 'fertilizer', 'leaf', 'emerald', 'Top Dressing Nitrogen', 'નાઈટ્રોજન ખાતર આપવું', 'યૂરિયા કા છિડકાવ'],
        [0, 'care', 'bug', 'purple', 'Weed & Pest Control', 'નીંદણ અને જીવાત નિયંત્રણ', 'ખરપતવાર નિયંત્રણ'],
        [1, 'water', 'tint', 'blue', 'Flowering Stage Irrigation', 'ફૂલ અવસ્થાએ પિયત', 'ફૂલ આને પર સિંચાઈ'],
        [2, 'harvest', 'sun', 'amber', 'Grain Filling', 'દાણા ભરવાનો સમય', 'દાના ભરને કા સમય'],
        [3, 'harvest', 'scythe', 'red', 'Harvesting', 'લણણી', 'કટાઈ']
    ];

    foreach ($wheatSchedule as $s) {
        $stmtSched->execute(array_merge([$wheatId], $s));
    }

    // Cotton Schedule
    $cottonSchedule = [
        [4, 'prepare', 'tractor', 'orange', 'Deep Ploughing & Prep', 'ઊંડી ખેડ અને તૈયારી', 'ગહરી જુતાઈ ઔર તૈયારી'],
        [5, 'prepare', 'seedling', 'emerald', 'Pre-monsoon Sowing', 'ચોમાસા પહેલા વાવણી', 'માનસૂન પૂર્વ બુવાઈ'],
        [6, 'care', 'leaf', 'emerald', 'Weeding & Thinning', 'નીંદણ અને પારવણી', 'ખરપતવાર નિયંત્રણ'],
        [7, 'fertilizer', 'flask', 'purple', 'Fertilizer Application', 'ખાતરનો ઉપયોગ', 'ઉર્વરક કા પ્રયોગ'],
        [8, 'care', 'bug', 'red', 'Pest Scouting (Bollworm)', 'જીવાત નિરીક્ષણ (ઇયળ)', 'કીટ નિરીક્ષણ (ઇલ્લી)'],
        [9, 'water', 'tint', 'blue', 'Square & Boll Formation', 'ઝીંડવા બેસવાનો સમય', 'ટિંડે બનના'],
        [10, 'harvest', 'box', 'amber', 'First Picking', 'પ્રથમ વીણી', 'પહલી ચુનાઈ'],
        [11, 'harvest', 'box', 'amber', 'Second Picking', 'બીજી વીણી', 'દૂસરી ચુનાઈ']
    ];

    foreach ($cottonSchedule as $s) {
        $stmtSched->execute(array_merge([$cottonId], $s));
    }

    echo "Crops and Schedules seeded successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
