-- Agricare - Automated Data Cleanup Script
-- Run this script IN YOUR SUPABASE SQL EDITOR once to enable automation.

-- 1. Enable the pg_cron extension if not already enabled
CREATE EXTENSION IF NOT EXISTS pg_cron;

-- 2. Cleanup existing duplicate or old data immediately (Optional)
DELETE FROM market_prices WHERE created_at < NOW() - INTERVAL '7 days';

-- 3. Schedule the market_prices cleanup job
-- This job runs every day at 00:00 (Midnight)
-- It deletes all records older than 7 days from creating time.
SELECT cron.schedule(
    'cleanup-market-prices-7-days',
    '0 0 * * *',
    $$ DELETE FROM market_prices WHERE created_at < NOW() - INTERVAL '7 days' $$
);

-- 4. Schedule the weather_cache cleanup job
-- This job runs every day at 01:00 AM
-- It deletes weather cache older than 1 day to keep the table small.
SELECT cron.schedule(
    'cleanup-weather-cache-1-day',
    '0 1 * * *',
    $$ DELETE FROM weather_cache WHERE fetched_at < NOW() - INTERVAL '1 day' $$
);

-- 5. To verify that jobs are scheduled, run:
-- SELECT * FROM cron.job;
