// backend/src/services/syncService.js
const fs = require('fs');
const path = require('path');
const config = require('../config/env');
const MarketPrice = require('../models/MarketPrice');

const syncMetaPath = path.join(__dirname, '../cache/last_market_sync.json');

const buildMarketApiUrl = (apiKey, filters = {}, limit = 500, offset = 0) => {
    const params = new URLSearchParams({
        'api-key': apiKey,
        'format': 'json',
        'limit': limit.toString(),
        'offset': offset.toString()
    });

    Object.entries(filters).forEach(([key, val]) => {
        params.append(`filters[${key}]`, val);
    });

    return `https://api.data.gov.in/resource/9ef84268-d588-465a-a308-a864a43d0070?${params.toString()}`;
};

const fetchMarketApiPage = async (apiKey, filters, limit, offset) => {
    const url = buildMarketApiUrl(apiKey, filters, limit, offset);
    try {
        const response = await fetch(url, { signal: AbortSignal.timeout(20000) });
        if (!response.ok) {
            throw new Error(`API returned HTTP ${response.status}`);
        }
        const data = await response.json();
        return data.records || [];
    } catch (err) {
        console.error(`Fetch API page error: ${err.message}`);
        throw err;
    }
};

const syncLatestMarketData = async (lookbackDays = 7, limit = 500, maxPages = 20) => {
    const apiKey = config.dataGovApiKey;
    if (!apiKey) {
        throw new Error('DATA_GOV_API_KEY is not configured.');
    }

    let targetDate = null;
    let processed = 0;
    let pagesFetched = 0;

    // Look back up to lookbackDays to find the most recent available date
    for (let dayOffset = 0; dayOffset <= lookbackDays; dayOffset++) {
        const date = new Date();
        date.setDate(date.getDate() - dayOffset);
        
        // Format date as DD/MM/YYYY
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const arrivalDate = `${day}/${month}/${year}`;

        console.log(`Checking market data for Gujarat on arrival_date: ${arrivalDate}...`);
        
        let records;
        try {
            records = await fetchMarketApiPage(apiKey, { state: 'Gujarat', arrival_date: arrivalDate }, limit, 0);
        } catch (e) {
            continue; // if fetch fails for this day, try previous
        }

        if (!records || records.length === 0) {
            continue; // try next day
        }

        targetDate = records[0].arrival_date || arrivalDate;

        for (let page = 0; page < maxPages; page++) {
            const offset = page * limit;
            let pageRecords = page === 0 ? records : [];
            
            if (page > 0) {
                try {
                    pageRecords = await fetchMarketApiPage(apiKey, { state: 'Gujarat', arrival_date: arrivalDate }, limit, offset);
                } catch (e) {
                    break;
                }
            }

            if (!pageRecords || pageRecords.length === 0) {
                break;
            }

            // Create bulk write operations for MongoDB
            const bulkOps = pageRecords.map(row => ({
                updateOne: {
                    filter: {
                        state: row.state || 'Gujarat',
                        district: row.district || '',
                        market: row.market || '',
                        commodity: row.commodity || '',
                        variety: row.variety || '',
                        arrival_date: row.arrival_date || arrivalDate
                    },
                    update: {
                        $set: {
                            min_price: parseInt(row.min_price) || 0,
                            max_price: parseInt(row.max_price) || 0,
                            modal_price: parseInt(row.modal_price) || 0
                        }
                    },
                    upsert: true
                }
            }));

            await MarketPrice.bulkWrite(bulkOps);
            processed += pageRecords.length;
            pagesFetched++;

            if (pageRecords.length < limit) {
                break;
            }
        }

        break; // Once we successfully synced the latest available day, stop
    }

    const result = {
        target_date: targetDate,
        processed,
        pages_fetched: pagesFetched,
        synced_at: new Date().toISOString()
    };

    // Save sync metadata to cache
    const cacheDir = path.dirname(syncMetaPath);
    if (!fs.existsSync(cacheDir)) {
        fs.mkdirSync(cacheDir, { recursive: true });
    }
    fs.writeFileSync(syncMetaPath, JSON.stringify(result, null, 4));

    return result;
};

module.exports = {
    syncLatestMarketData
};
