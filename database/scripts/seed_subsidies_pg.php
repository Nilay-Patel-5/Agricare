<?php
// backend/seed_subsidies_pg.php - Seeding for PostgreSQL

require_once __DIR__ . '/db.php';

try {
    $pdo = Database::getConnection();

    // Clear existing data
    $pdo->exec("TRUNCATE TABLE subsidies");

    $subsidies = [
        [
            'name' => 'PM Kisan Samman Nidhi',
            'name_gu' => 'પીએમ કિસાન સન્માન નિધિ',
            'name_hi' => 'पीएम किसान सम्मान निधि',
            'category' => 'Income Support',
            'category_gu' => 'આવક સહાય',
            'category_hi' => 'आय सहायता',
            'description' => 'Direct income support of ₹6,000 per year to all landholding farmer families.',
            'description_gu' => 'તમામ જમીનધારક ખેડૂત પરિવારોને વાર્ષિક ₹6,000 ની સીધી આવક સહાય.',
            'description_hi' => 'सभी भूमिधारक किसान परिवारों को प्रति वर्ष ₹6,000 की प्रत्यक्ष आय सहायता।',
            'benefits' => '₹2,000 every 4 months directly to bank account.',
            'benefits_gu' => 'દર 4 મહિને ₹2,000 સીધા બેંક ખાતામાં.',
            'benefits_hi' => 'हर 4 महीने में ₹2,000 सीधे बैंक खाते में।',
            'eligibility' => 'Small and marginal farmers with landholding.',
            'eligibility_gu' => 'જમીન ધરાવતા નાના અને સીમાંત ખેડૂતો.',
            'eligibility_hi' => 'भूमि धारक छोटे और सीमांत किसान।',
            'apply_link' => 'https://pmkisan.gov.in/',
            'status' => 'Live'
        ],
        [
            'name' => 'Drip Irrigation Subsidy',
            'name_gu' => 'ટપક સિંચાઈ સબસિડી',
            'name_hi' => 'ड्रिप सिंचाई सब्सिडी',
            'category' => 'Irrigation',
            'category_gu' => 'સિંચાઈ',
            'category_hi' => 'सिंचाई',
            'description' => 'Financial assistance for installing drip and sprinkler irrigation systems.',
            'description_gu' => 'ટપક અને ફુવારા પદ્ધતિ માટે નાણાકીય સહાય.',
            'description_hi' => 'ड्रिप और स्प्रिंकलर सिंचाई प्रणाली स्थापित करने के लिए वित्तीय सहायता।',
            'benefits' => 'Up to 70% to 90% subsidy on installation cost.',
            'benefits_gu' => 'ખર્ચના 70% થી 90% સુધીની સબસીડી.',
            'benefits_hi' => 'स्थापना लागत पर 70% से 90% तक की सब्सिडी।',
            'eligibility' => 'Farmers having own land and water source.',
            'eligibility_gu' => 'પોતાની જમીન અને પાણીનો સ્ત્રોત ધરાવતા ખેડૂતો.',
            'eligibility_hi' => 'स्वयं की भूमि और जल स्रोत वाले किसान।',
            'apply_link' => 'https://ikhedut.gujarat.gov.in/',
            'status' => 'Live'
        ],
        [
            'name' => 'Tractor Subsidy Scheme',
            'name_gu' => 'ટ્રેક્ટર સબસિડી યોજના',
            'name_hi' => 'ट्रैक्टर सब्सिडी योजना',
            'category' => 'Equipment',
            'category_gu' => 'સાધનો',
            'category_hi' => 'उपकरण',
            'description' => 'Subsidy for purchasing new tractors to modernize farming.',
            'description_gu' => 'ખેતીના આધુનિકીકરણ માટે નવા ટ્રેક્ટર ખરીદવા પર સબસિડી.',
            'description_hi' => 'खेती के आधुनिकीकरण के लिए नए ट्रैक्टर खरीदने पर सब्सिडी।',
            'benefits' => 'Subsidy up to ₹45,000 to ₹60,000.',
            'benefits_gu' => '₹45,000 થી ₹60,000 સુધીની સબસિડી.',
            'benefits_hi' => '₹45,000 से ₹60,000 तक की सब्सिडी।',
            'eligibility' => 'Registered farmers in Gujarat state.',
            'eligibility_gu' => 'ગુજરાત રાજ્યના નોંધાયેલા ખેડૂતો.',
            'eligibility_hi' => 'गुजरात राज्य के पंजीकृत किसान।',
            'apply_link' => 'https://ikhedut.gujarat.gov.in/',
            'status' => 'Upcoming'
        ],
        [
            'name' => 'Organic Farming Assistance',
            'name_gu' => 'સજીવ ખેતી સહાય',
            'name_hi' => 'जैविक खेती सहायता',
            'category' => 'Income Support',
            'category_gu' => 'આવક સહાય',
            'category_hi' => 'आय सहायता',
            'description' => 'Financial assistance for farmers adopting organic farming practices.',
            'description_gu' => 'સજીવ ખેતી અપનાવતા ખેડૂતો માટે નાણાકીય સહાય.',
            'description_hi' => 'जैविक खेती अपनाने वाले किसानों के लिए वित्तीय सहायता।',
            'benefits' => '₹10,000 per year for 3 years.',
            'benefits_gu' => '3 વર્ષ માટે વાર્ષિક ₹10,000.',
            'benefits_hi' => '3 वर्षों के लिए ₹10,000 प्रति वर्ष।',
            'eligibility' => 'Farmers with organic certification.',
            'eligibility_gu' => 'ઓર્ગેનિક પ્રમાણપત્ર ધરાવતા ખેડૂતો.',
            'eligibility_hi' => 'जैविक प्रमाणन वाले किसान।',
            'apply_link' => 'https://dag.gujarat.gov.in/',
            'status' => 'Live'
        ],
        [
            'name' => 'Solar Pump Subsidy (KUSUM)',
            'name_gu' => 'સોલાર પંપ સબસિડી',
            'name_hi' => 'सोलर पंप सब्सिडी',
            'category' => 'Irrigation',
            'category_gu' => 'સિંચાઈ',
            'category_hi' => 'सिंचाई',
            'description' => 'Subsidy for solar-powered irrigation pumps under PM-KUSUM scheme.',
            'description_gu' => 'PM-KUSUM યોજના હેઠળ સોલાર સિંચાઈ પંપ માટે સબસિડી.',
            'description_hi' => 'PM-KUSUM योजना के तहत सौर ऊर्जा संचालित सिंचाई पंपों के लिए सब्सिडी।',
            'benefits' => 'Up to 60% subsidy on solar pump cost.',
            'benefits_gu' => 'સોલાર પંપના ખર્ચ પર 60% સુધીની સબસિડી.',
            'benefits_hi' => 'सोलर पंप की लागत पर 60% तक की सब्सिडी।',
            'eligibility' => 'Farmers needing irrigation for area without electricity.',
            'eligibility_gu' => 'વીજળી વગરના વિસ્તારમાં સિંચાઈની જરૂર હોય તેવા ખેડૂતો.',
            'eligibility_hi' => 'बिना बिजली वाले क्षेत्र के लिए सिंचाई की आवश्यकता वाले किसान।',
            'apply_link' => 'https://mnre.gov.in/pm-kusum/',
            'status' => 'Live'
        ]
    ];

    $sql = "INSERT INTO subsidies (name, name_gu, name_hi, category, category_gu, category_hi, description, description_gu, description_hi, benefits, benefits_gu, benefits_hi, eligibility, eligibility_gu, eligibility_hi, apply_link, status) VALUES (:name, :name_gu, :name_hi, :category, :category_gu, :category_hi, :description, :description_gu, :description_hi, :benefits, :benefits_gu, :benefits_hi, :eligibility, :eligibility_gu, :eligibility_hi, :apply_link, :status)";

    $stmt = $pdo->prepare($sql);

    foreach ($subsidies as $sub) {
        $stmt->execute($sub);
    }

    echo "PostgreSQL Subsidies seeded successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
