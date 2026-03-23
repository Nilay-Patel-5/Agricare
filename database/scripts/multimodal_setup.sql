-- database/scripts/multimodal_setup.sql

-- Pesticides Table
CREATE TABLE IF NOT EXISTS pesticides (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(255),
    target_pests TEXT, -- Comma separated or description
    price_range VARCHAR(100), -- e.g. "Rs. 450 - 600"
    usage_instructions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Shops Table
CREATE TABLE IF NOT EXISTS shops (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    district VARCHAR(100),
    city VARCHAR(100),
    address TEXT,
    phone VARCHAR(20),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pest to Pesticide Mapping
CREATE TABLE IF NOT EXISTS pest_pesticide_mapping (
    id SERIAL PRIMARY KEY,
    pest_name VARCHAR(255) NOT NULL,
    pesticide_id INT REFERENCES pesticides(id) ON DELETE CASCADE,
    effectiveness VARCHAR(50) DEFAULT 'High'
);

-- Insert some sample data for demonstration (Aphids example)
INSERT INTO pesticides (name, brand, target_pests, price_range, usage_instructions)
VALUES 
('Imidacloprid 17.8% SL', 'Confidor', 'Aphids, Whitefly, Jassids', 'Rs. 350 - 450 per 100ml', 'Mix 0.5ml per liter of water. Spray on foliage.'),
('Thiamethoxam 25% WG', 'Actara', 'Aphids, Thrips, Jassids', 'Rs. 250 - 300 per 100g', 'Mix 1g per 3 liter of water. Soil drench or spray.'),
('Neem Oil (Azadirachtin)', 'OrganicCare', 'Aphids, Mealybugs, Mites', 'Rs. 150 - 200 per 250ml', 'Mix 5ml per liter of water with 2ml soap solution. Spray at evening.');

INSERT INTO shops (name, district, city, address, phone)
VALUES 
('Agri-Tech Solutions', 'Junagadh', 'Junagadh', 'Station Road, Near Bus Stand', '9876543210'),
('Kisan Seva Kendra', 'Junagadh', 'Keshod', 'Main Bazaar, Keshod', '9123456789'),
('Bharat Seeds & Pesticides', 'Rajkot', 'Gondal', 'Market Yard Road, Gondal', '9000888777');

INSERT INTO pest_pesticide_mapping (pest_name, pesticide_id, effectiveness)
SELECT 'Aphids', id, 'High' FROM pesticides WHERE name IN ('Imidacloprid 17.8% SL', 'Thiamethoxam 25% WG');

INSERT INTO pest_pesticide_mapping (pest_name, pesticide_id, effectiveness)
SELECT 'Aphids', id, 'Moderate (Organic)' FROM pesticides WHERE name = 'Neem Oil (Azadirachtin)';
