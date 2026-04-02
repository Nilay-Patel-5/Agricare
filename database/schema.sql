-- Agricare Professional Database Schema
-- Optimized for PostgreSQL / Supabase
-- Refined to match current application naming conventions

-- Extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";

-------------------------------------------------------------------------------
-- 1. ADMINS TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS admins CASCADE;
CREATE TABLE admins (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    password TEXT, -- Hashed password
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

-------------------------------------------------------------------------------
-- 2. FARMERS TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS farmers CASCADE;
CREATE TABLE farmers (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(15) UNIQUE,
    district VARCHAR(50),
    city VARCHAR(50),
    pincode VARCHAR(10),
    pin TEXT, -- Hashed 6-digit PIN for farmers
    pref_lang VARCHAR(5) DEFAULT 'en',
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_farmers_phone ON farmers(phone);
CREATE INDEX IF NOT EXISTS idx_farmers_email ON farmers(email);


-------------------------------------------------------------------------------
-- 2. MARKET PRICES TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS market_prices CASCADE;
CREATE TABLE market_prices (
    id SERIAL PRIMARY KEY,
    state VARCHAR(50) DEFAULT 'Gujarat',
    district VARCHAR(50) NOT NULL,
    market VARCHAR(100) NOT NULL,
    commodity VARCHAR(100) NOT NULL,
    variety VARCHAR(100),
    grade VARCHAR(50),
    arrival_date VARCHAR(20) NOT NULL, -- Format: DD/MM/YYYY from API
    min_price INTEGER,
    max_price INTEGER,
    modal_price INTEGER,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(state, district, market, commodity, variety, arrival_date)
);

CREATE INDEX IF NOT EXISTS idx_market_prices_district ON market_prices(district);
CREATE INDEX IF NOT EXISTS idx_market_prices_commodity ON market_prices(commodity);
CREATE INDEX IF NOT EXISTS idx_market_prices_agg ON market_prices(district, market, commodity);

-------------------------------------------------------------------------------
-- 3. SUBSIDIES TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS subsidies CASCADE;
CREATE TABLE subsidies (
    id SERIAL PRIMARY KEY,
    title_en VARCHAR(255) NOT NULL,
    title_gu VARCHAR(255),
    title_hi VARCHAR(255),
    category_en VARCHAR(100),
    category_gu VARCHAR(100),
    category_hi VARCHAR(100),
    description_en TEXT,
    description_gu TEXT,
    description_hi TEXT,
    benefits_en TEXT,
    benefits_gu TEXT,
    benefits_hi TEXT,
    apply_link TEXT,
    status VARCHAR(20) DEFAULT 'active',
    last_updated TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

-- GIN index for fast text search on multi-lingual titles and descriptions
CREATE INDEX IF NOT EXISTS idx_subsidies_search_en ON subsidies USING gin(title_en gin_trgm_ops, description_en gin_trgm_ops);

-------------------------------------------------------------------------------
-- 4. PESTICIDES TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS pesticides CASCADE;
CREATE TABLE pesticides (
    id SERIAL PRIMARY KEY,
    name_en VARCHAR(255) NOT NULL,
    name_gu VARCHAR(255),
    name_hi VARCHAR(255),
    brand VARCHAR(100),
    category VARCHAR(100), -- Insecticide, Fungicide, etc.
    price_range VARCHAR(100),
    usage_en TEXT,
    usage_gu TEXT,
    usage_hi TEXT,
    target_pests_en TEXT,
    target_pests_gu TEXT,
    target_pests_hi TEXT,
    image_url TEXT,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

-- GIN index for search
CREATE INDEX IF NOT EXISTS idx_pesticides_search_en ON pesticides USING gin(name_en gin_trgm_ops, target_pests_en gin_trgm_ops);

-------------------------------------------------------------------------------
-- 5. PEST PESTICIDE MAPPING
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS pest_pesticide_mapping CASCADE;
CREATE TABLE pest_pesticide_mapping (
    id SERIAL PRIMARY KEY,
    pesticide_id INTEGER REFERENCES pesticides(id) ON DELETE CASCADE,
    pest_name VARCHAR(255) NOT NULL,
    effectiveness VARCHAR(50) DEFAULT 'High'
);

CREATE INDEX IF NOT EXISTS idx_pest_mapping_pest_name ON pest_pesticide_mapping(pest_name);

-------------------------------------------------------------------------------
-- 6. CROPS TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS crops CASCADE;
CREATE TABLE crops (
    id SERIAL PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_gu VARCHAR(100),
    name_hi VARCHAR(100),
    icon VARCHAR(50),
    season_en VARCHAR(50),
    season_gu VARCHAR(50),
    season_hi VARCHAR(50),
    category VARCHAR(50), -- Cereal, Vegetable, Fruit
    image_url TEXT
);

-------------------------------------------------------------------------------
-- 7. CROP SCHEDULES TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS crop_schedules CASCADE;
CREATE TABLE crop_schedules (
    id SERIAL PRIMARY KEY,
    crop_id INTEGER REFERENCES crops(id) ON DELETE CASCADE,
    month_index INTEGER NOT NULL, -- 0-11 for Jan-Dec
    activity_type VARCHAR(50),    -- Planting, Irrigation, Harvesting
    activity_icon VARCHAR(50),
    activity_color VARCHAR(20),
    task_en TEXT,
    task_gu TEXT,
    task_hi TEXT
);

CREATE INDEX IF NOT EXISTS idx_crop_schedules_crop_id ON crop_schedules(crop_id);

-------------------------------------------------------------------------------
-- 8. SHOPS TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS shops CASCADE;
CREATE TABLE shops (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    district VARCHAR(50) NOT NULL,
    city VARCHAR(100),
    address TEXT,
    phone VARCHAR(20),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_shops_district ON shops(district);

-------------------------------------------------------------------------------
-- 9. CHAT MESSAGES TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS chat_messages CASCADE;
CREATE TABLE chat_messages (
    id SERIAL PRIMARY KEY,
    user_id INTEGER,
    session_key VARCHAR(120) NOT NULL,
    role VARCHAR(20) NOT NULL, -- 'user', 'assistant'
    message TEXT NOT NULL,
    model VARCHAR(80),
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_chat_messages_user_id ON chat_messages(user_id);
CREATE INDEX IF NOT EXISTS idx_chat_messages_session_key ON chat_messages(session_key);
CREATE INDEX IF NOT EXISTS idx_chat_messages_created_at ON chat_messages(created_at);

-------------------------------------------------------------------------------
-- 10. NEWS TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS news CASCADE;
CREATE TABLE news (
    id SERIAL PRIMARY KEY,
    title_en VARCHAR(255) NOT NULL,
    title_gu VARCHAR(255),
    title_hi VARCHAR(255),
    content_en TEXT NOT NULL,
    content_gu TEXT,
    content_hi TEXT,
    image_url TEXT,
    category VARCHAR(50),
    published_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_news_published_at ON news(published_at);

-------------------------------------------------------------------------------
-- 11. WEATHER CACHE TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS weather_cache CASCADE;
CREATE TABLE weather_cache (
    id SERIAL PRIMARY KEY,
    city VARCHAR(100) UNIQUE NOT NULL,
    json_data JSONB NOT NULL,
    fetched_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_weather_cache_city ON weather_cache(city);

-------------------------------------------------------------------------------
-- 12. AI SCANS TABLE
-------------------------------------------------------------------------------
DROP TABLE IF EXISTS ai_scans CASCADE;
CREATE TABLE ai_scans (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES farmers(id) ON DELETE CASCADE,
    pest_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_ai_scans_user_id ON ai_scans(user_id);
CREATE INDEX IF NOT EXISTS idx_ai_scans_created_at ON ai_scans(created_at);
