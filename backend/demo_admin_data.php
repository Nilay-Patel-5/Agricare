<?php

function admin_demo_subsidies(): array
{
    return [
        [
            'id' => 1001,
            'name' => 'PM Kisan Samman Nidhi',
            'category' => 'Financial',
            'description' => 'Direct income support for eligible farmer families through three installments each year.',
            'benefits' => 'Rs. 6,000 per year paid in three equal installments.',
            'eligibility' => 'Landholding farmer families with valid Aadhaar and bank details.',
            'apply_link' => 'https://pmkisan.gov.in/',
            'status' => 'Live',
            'last_updated' => '2026-03-12T00:00:00+05:30',
        ],
        [
            'id' => 1002,
            'name' => 'PM Fasal Bima Yojana',
            'category' => 'Insurance',
            'description' => 'Crop insurance support for notified crops affected by weather events, pests, and diseases.',
            'benefits' => 'Low farmer premium with claim support on insured crop loss.',
            'eligibility' => 'Farmers growing notified crops in covered areas.',
            'apply_link' => 'https://pmfby.gov.in/',
            'status' => 'Live',
            'last_updated' => '2026-02-28T00:00:00+05:30',
        ],
        [
            'id' => 1003,
            'name' => 'Kisan Credit Card',
            'category' => 'Financial',
            'description' => 'Short-term working capital support for crop production and allied farm activities.',
            'benefits' => 'Access to low-interest crop loans and flexible seasonal credit.',
            'eligibility' => 'Farmers, tenant farmers, sharecroppers, and eligible joint borrowers.',
            'apply_link' => 'https://www.nabard.org/',
            'status' => 'Live',
            'last_updated' => '2026-01-20T00:00:00+05:30',
        ],
        [
            'id' => 1004,
            'name' => 'Pradhan Mantri Krishi Sinchai Yojana',
            'category' => 'Irrigation',
            'description' => 'Support for micro-irrigation and better water-use efficiency at farm level.',
            'benefits' => 'Subsidy support for drip and sprinkler systems, often 45% to 55% of cost.',
            'eligibility' => 'Farmers applying through approved state agriculture or horticulture channels.',
            'apply_link' => 'https://pmksy.gov.in/',
            'status' => 'Live',
            'last_updated' => '2026-03-05T00:00:00+05:30',
        ],
        [
            'id' => 1005,
            'name' => 'Sub-Mission on Agricultural Mechanization',
            'category' => 'Equipment',
            'description' => 'Capital support for selected farm machinery, implements, and custom hiring centers.',
            'benefits' => 'Subsidy on tractors, seed drills, planters, and other notified machinery.',
            'eligibility' => 'Individual farmers, FPOs, SHGs, and other approved entities.',
            'apply_link' => 'https://agrimachinery.nic.in/',
            'status' => 'Live',
            'last_updated' => '2026-02-14T00:00:00+05:30',
        ],
        [
            'id' => 1006,
            'name' => 'Gujarat iKhedut Assistance',
            'category' => 'Equipment',
            'description' => 'State-level assistance for farm equipment, horticulture inputs, and irrigation components.',
            'benefits' => 'Selected categories receive subsidy support on approved purchases and installations.',
            'eligibility' => 'Registered farmers in Gujarat applying through the iKhedut portal.',
            'apply_link' => 'https://ikhedut.gujarat.gov.in/',
            'status' => 'Live',
            'last_updated' => '2026-03-18T00:00:00+05:30',
        ],
    ];
}

function admin_demo_pesticides(): array
{
    return [
        ['id' => 201, 'name' => 'Imidacloprid 17.8% SL', 'brand' => 'Confidor', 'price_range' => 'Rs. 450 - 700', 'target_pests' => 'Aphids, whitefly, jassids, thrips', 'usage_instructions' => 'Use at label rate with thorough foliar coverage.', 'category' => 'Insecticide'],
        ['id' => 202, 'name' => 'Thiamethoxam 25% WG', 'brand' => 'Actara', 'price_range' => 'Rs. 700 - 1100', 'target_pests' => 'Whitefly, aphids, leafhoppers', 'usage_instructions' => 'Apply at early infestation stage as per label guidance.', 'category' => 'Insecticide'],
        ['id' => 203, 'name' => 'Mancozeb 75% WP', 'brand' => 'Dithane M-45', 'price_range' => 'Rs. 220 - 420', 'target_pests' => 'Early blight, late blight, downy mildew', 'usage_instructions' => 'Protective spray at recommended interval when disease pressure starts.', 'category' => 'Fungicide'],
        ['id' => 204, 'name' => 'Copper Oxychloride 50% WP', 'brand' => 'Blitox', 'price_range' => 'Rs. 260 - 460', 'target_pests' => 'Bacterial leaf spot, downy mildew, leaf spot', 'usage_instructions' => 'Apply as a preventive or early curative spray.', 'category' => 'Fungicide'],
        ['id' => 205, 'name' => 'Carbendazim 50% WP', 'brand' => 'Bavistin', 'price_range' => 'Rs. 350 - 620', 'target_pests' => 'Powdery mildew, wilt, root rot', 'usage_instructions' => 'Use for foliar spray or soil drench depending on crop and disease.', 'category' => 'Systemic Fungicide'],
        ['id' => 206, 'name' => 'Propiconazole 25% EC', 'brand' => 'Tilt', 'price_range' => 'Rs. 600 - 920', 'target_pests' => 'Rust, leaf spot, sheath blight', 'usage_instructions' => 'Apply at disease onset and repeat only as needed.', 'category' => 'Fungicide'],
    ];
}

function admin_demo_pest_mappings(): array
{
    return [
        ['mapping_id' => 301, 'pest_name' => 'Aphids', 'effectiveness' => 'High', 'pesticide_id' => 201, 'name' => 'Imidacloprid 17.8% SL', 'brand' => 'Confidor', 'price_range' => 'Rs. 450 - 700', 'target_pests' => 'Aphids, whitefly, jassids, thrips', 'usage_instructions' => 'Use at label rate with thorough foliar coverage.'],
        ['mapping_id' => 302, 'pest_name' => 'Whitefly', 'effectiveness' => 'High', 'pesticide_id' => 202, 'name' => 'Thiamethoxam 25% WG', 'brand' => 'Actara', 'price_range' => 'Rs. 700 - 1100', 'target_pests' => 'Whitefly, aphids, leafhoppers', 'usage_instructions' => 'Apply at early infestation stage as per label guidance.'],
        ['mapping_id' => 303, 'pest_name' => 'Early Blight', 'effectiveness' => 'High', 'pesticide_id' => 203, 'name' => 'Mancozeb 75% WP', 'brand' => 'Dithane M-45', 'price_range' => 'Rs. 220 - 420', 'target_pests' => 'Early blight, late blight, downy mildew', 'usage_instructions' => 'Protective spray at recommended interval when disease pressure starts.'],
        ['mapping_id' => 304, 'pest_name' => 'Bacterial Leaf Spot', 'effectiveness' => 'High', 'pesticide_id' => 204, 'name' => 'Copper Oxychloride 50% WP', 'brand' => 'Blitox', 'price_range' => 'Rs. 260 - 460', 'target_pests' => 'Bacterial leaf spot, downy mildew, leaf spot', 'usage_instructions' => 'Apply as a preventive or early curative spray.'],
        ['mapping_id' => 305, 'pest_name' => 'Powdery Mildew', 'effectiveness' => 'High', 'pesticide_id' => 205, 'name' => 'Carbendazim 50% WP', 'brand' => 'Bavistin', 'price_range' => 'Rs. 350 - 620', 'target_pests' => 'Powdery mildew, wilt, root rot', 'usage_instructions' => 'Use for foliar spray or soil drench depending on crop and disease.'],
        ['mapping_id' => 306, 'pest_name' => 'Rust', 'effectiveness' => 'High', 'pesticide_id' => 206, 'name' => 'Propiconazole 25% EC', 'brand' => 'Tilt', 'price_range' => 'Rs. 600 - 920', 'target_pests' => 'Rust, leaf spot, sheath blight', 'usage_instructions' => 'Apply at disease onset and repeat only as needed.'],
    ];
}
