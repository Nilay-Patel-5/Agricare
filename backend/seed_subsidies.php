<?php
require __DIR__ . '/../vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->agricare;
    $collection = $db->subsidies;

    // Clear existing data (optional, but good for demo)
    $collection->deleteMany([]);

    $subsidies = [
        [
            'name' => 'Pradhan Mantri Kisan Samman Nidhi (PM-KISAN)',
            'name_gu' => 'પ્રધાનમંત્રી કિસાન સન્માન નિધિ',
            'name_hi' => 'प्रधानमंत्री किसान सम्मान निधि',
            'category' => 'Income Support',
            'category_gu' => 'આવક સહાય',
            'category_hi' => 'आय सहायता',
            'description' => 'Direct income support of ₹6,000 per year to farmer families.',
            'description_gu' => 'ખેડૂત પરિવારોને વાર્ષિક ₹૬,૦૦૦ની સીધી આવક સહાય.',
            'description_hi' => 'किसान परिवारों को प्रति वर्ष ₹6,000 की प्रत्यक्ष आय सहायता।',
            'benefits' => '₹2,000 every 4 months.',
            'benefits_gu' => 'દર ૪ મહિને ₹૨,૦૦૦.',
            'benefits_hi' => 'हर 4 महीने में ₹2,000।',
            'eligibility' => 'Small and marginal farmers.',
            'eligibility_gu' => 'નાના અને સીમાંત ખેડૂતો.',
            'eligibility_hi' => 'छोटे और सीमांत किसान।',
            'apply_link' => 'https://pmkisan.gov.in/',
            'status' => 'Live',
            'last_updated' => date('Y-m-d')
        ],
        [
            'name' => 'Gujarat State Subsidy for Drip Irrigation',
            'name_gu' => 'ટપક સિંચાઈ માટે ગુજરાત રાજ્ય સબસિડી',
            'name_hi' => 'ड्रिप सिंचाई के लिए गुजरात राज्य सब्सिडी',
            'category' => 'Irrigation',
            'category_gu' => 'સિંચાઈ',
            'category_hi' => 'सिंचाई',
            'description' => 'Financial assistance for installing micro-irrigation systems.',
            'description_gu' => 'માઇક્રો-ઇરિગેશન સિસ્ટમ સ્થાપિત કરવા માટે નાણાકીય સહાય.',
            'description_hi' => 'सूक्ष्म सिंचाई प्रणाली स्थापित करने के लिए वित्तीय सहायता।',
            'benefits' => 'Up to 70% to 90% subsidy depending on category.',
            'benefits_gu' => 'કેટેગરી મુજબ ૭૦% થી ૯૦% સબસિડી.',
            'benefits_hi' => 'श्रेणी के आधार पर 70% से 90% तक सब्सिडी।',
            'eligibility' => 'All farmers in Gujarat state.',
            'eligibility_gu' => 'ગુજરાત રાજ્યના તમામ ખેડૂતો.',
            'eligibility_hi' => 'गुजरात राज्य के सभी किसान।',
            'apply_link' => 'https://ikhedut.gujarat.gov.in/',
            'status' => 'Live',
            'last_updated' => date('Y-m-d')
        ],
        [
            'name' => 'Agriculture Equipment Subsidy (i-Khedut)',
            'name_gu' => 'કૃષિ સાધનોની સબસિડી (આઈ-ખેડૂત)',
            'name_hi' => 'कृषि उपकरण सब्सिडी (आई-खेड़ूत)',
            'category' => 'Equipment',
            'category_gu' => 'સાધનો',
            'category_hi' => 'उपकरण',
            'description' => 'Subsidy for purchase of tractors, rotavators, and other tools.',
            'description_gu' => 'ટ્રેક્ટર, રોટાવેટર અને અન્ય સાધનોની ખરીદી માટે સબસિડી.',
            'description_hi' => 'ट्रैक्टर, रोटावेटर और अन्य उपकरणों की खरीद के लिए सब्सिडी।',
            'benefits' => 'Flat percentage discount based on tool type.',
            'benefits_gu' => 'સાધનના પ્રકારના આધારે નિશ્ચિત ટકાવારી છૂટ.',
            'benefits_hi' => 'उपकरण के प्रकार के आधार पर निश्चित प्रतिशत छूट।',
            'eligibility' => 'Registered farmers on i-Khedut portal.',
            'eligibility_gu' => 'આઈ-ખેડૂત પોર્ટલ પર નોંધાયેલા ખેડૂતો.',
            'eligibility_hi' => 'आई-खेड़ूत पोर्टल पर पंजीकृत किसान।',
            'apply_link' => 'https://ikhedut.gujarat.gov.in/',
            'status' => 'Upcoming',
            'last_updated' => date('Y-m-d')
        ]
    ];

    $collection->insertMany($subsidies);
    echo "Subsidies seeded successfully!\n";
} catch (Exception $e) {
    echo "Seed failed: " . $e->getMessage() . "\n";
}
