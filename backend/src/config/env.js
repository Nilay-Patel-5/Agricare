// backend/src/config/env.js
const path = require('path');
const dotenv = require('dotenv');

// Load environment variables from the root directory
dotenv.config({ path: path.join(__dirname, '../../../.env') });

const config = {
    port: process.env.PORT || 3000,
    mongoUri: process.env.MONGODB_URI || 'mongodb://127.0.0.1:27017/agricare',
    sessionSecret: process.env.SESSION_SECRET || 'agricare-super-secret-key-12345',
    dataGovApiKey: process.env.DATA_GOV_API_KEY || '',
    geminiApiKeys: (process.env.GEMINI_API_KEY || '').split(','),
    groqApiKey: process.env.GROQ_API_KEY || '',
};

module.exports = config;
