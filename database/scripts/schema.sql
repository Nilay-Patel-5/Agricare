-- Create database (Run manually if needed):
-- CREATE DATABASE agricare;

-- 1. Subsidies Table
CREATE TABLE IF NOT EXISTS subsidies (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    name_gu VARCHAR(255),
    name_hi VARCHAR(255),
    category VARCHAR(100) NOT NULL,
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
    apply_link TEXT,
    status VARCHAR(50) DEFAULT 'Live',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Market Prices Table
CREATE TABLE IF NOT EXISTS market_prices (
    id SERIAL PRIMARY KEY,
    state VARCHAR(100),
    district VARCHAR(100),
    market VARCHAR(100),
    commodity VARCHAR(100),
    variety VARCHAR(100),
    grade VARCHAR(100),
    arrival_date VARCHAR(50),
    min_price INT,
    max_price INT,
    modal_price INT,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_market_price UNIQUE (state, district, market, commodity, variety, arrival_date)
);

-- 3. Users Table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) UNIQUE,
    email VARCHAR(255) UNIQUE, -- For Admins
    password TEXT, -- Hashed password for Admins/Farmers
    dob DATE, -- For 18+ validation
    role VARCHAR(20) DEFAULT 'farmer', -- 'farmer' or 'admin'
    district VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Crops Table
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

-- 5. Crop Schedules Table
CREATE TABLE IF NOT EXISTS crop_schedules (
    id SERIAL PRIMARY KEY,
    crop_id INT REFERENCES crops(id) ON DELETE CASCADE,
    month_index INT NOT NULL, -- 0 to 11
    activity_type VARCHAR(50), -- 'prepare', 'water', 'fertilizer', 'care', 'harvest'
    activity_icon VARCHAR(50), -- Lucide/FontAwesome icon name
    activity_color VARCHAR(20), -- 'orange', 'blue', 'emerald', 'purple', 'red'
    task_en TEXT,
    task_gu TEXT,
    task_hi TEXT
);

-- Indexes for performance
CREATE INDEX idx_subsidies_category ON subsidies(category);
CREATE INDEX idx_market_prices_location ON market_prices(state, district, market);
CREATE INDEX idx_market_prices_commodity ON market_prices(commodity);
CREATE INDEX idx_users_phone ON users(phone);
CREATE INDEX idx_crop_schedules_crop ON crop_schedules(crop_id);
