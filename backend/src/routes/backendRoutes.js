// backend/src/routes/backendRoutes.js
const express = require('express');
const router = express.Router();
const path = require('path');
const fs = require('fs');
const mongoose = require('mongoose');

const Admin = require('../models/Admin');
const Farmer = require('../models/Farmer');
const MarketPrice = require('../models/MarketPrice');
const Subsidy = require('../models/Subsidy');
const Pesticide = require('../models/Pesticide');
const Crop = require('../models/Crop');
const Shop = require('../models/Shop');
const ChatMessage = require('../models/ChatMessage');
const Feedback = require('../models/Feedback');
const AIScan = require('../models/AIScan');

const { syncLatestMarketData } = require('../services/syncService');
const { runDiseaseDetection } = require('../services/visionService');
const { geminiTextCreate, geminiExtractText, groqChatCreate, groqExtractText } = require('../services/llmService');

// Middleware to ensure user is logged in
const requireAuth = (req, res, next) => {
    if (!req.session || !req.session.user_id) {
        return res.status(401).json({ success: false, error: 'Unauthorized. Please login.', message: 'Unauthorized. Please login.' });
    }
    next();
};

// 1. CAPTCHA API
router.get('/captcha_api.php', (req, res) => {
    res.setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    res.setHeader('Pragma', 'no-cache');
    
    const characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghkmnpqrstuvwxyz';
    let randomString = '';
    for (let i = 0; i < 5; i++) {
        randomString += characters[Math.floor(Math.random() * characters.length)];
    }
    req.session.captcha_code = randomString;
    return res.json({ success: true, captcha: randomString });
});

// 2. LOGIN API
router.post('/login_api.php', async (req, res) => {
    try {
        const { identifier = '', password = '', captcha = '' } = req.body || {};
        
        if (!req.session.captcha_code || captcha.toLowerCase() !== req.session.captcha_code.toLowerCase()) {
            req.session.captcha_code = null;
            return res.json({ success: false, message: 'Invalid CAPTCHA code. Please try again.' });
        }
        req.session.captcha_code = null; // clear it

        if (!identifier.trim() || !password) {
            return res.json({ success: false, message: 'Identification and credentials are required.' });
        }

        let isAuthenticated = false;
        let userData = null;

        const cleanIdentifier = identifier.trim();

        // 1. Check if numeric -> Farmer by Phone
        if (/^[0-9]+$/.test(cleanIdentifier)) {
            if (/^[6-9]\d{9}$/.test(cleanIdentifier)) {
                const farmer = await Farmer.findOne({ phone: cleanIdentifier });
                if (farmer && await farmer.comparePin(password)) {
                    isAuthenticated = true;
                    userData = {
                        id: farmer._id.toString(),
                        name: farmer.name,
                        role: 'farmer',
                        pref_lang: farmer.pref_lang,
                        district: farmer.district,
                        city: farmer.city
                    };
                }
            }
        } 
        // 2. Check if admin email
        else if (/^[a-zA-Z0-9._%+-]+@agricare\.admin$/.test(cleanIdentifier)) {
            const admin = await Admin.findOne({ email: cleanIdentifier });
            if (admin && await admin.comparePassword(password)) {
                isAuthenticated = true;
                userData = {
                    id: admin._id.toString(),
                    name: admin.name,
                    role: 'admin',
                    pref_lang: 'en'
                };
            }
        } 
        // 3. General Email -> Farmer by Email
        else if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(cleanIdentifier)) {
            const farmer = await Farmer.findOne({ email: cleanIdentifier });
            if (farmer && await farmer.comparePin(password)) {
                isAuthenticated = true;
                userData = {
                    id: farmer._id.toString(),
                    name: farmer.name,
                    role: 'farmer',
                    pref_lang: farmer.pref_lang,
                    district: farmer.district,
                    city: farmer.city
                };
            }
        }

        if (isAuthenticated && userData) {
            req.session.user_id = userData.id;
            req.session.user_name = userData.name;
            req.session.user_role = userData.role;
            return res.json({ success: true, user: userData });
        } else {
            return res.json({ success: false, message: 'Invalid credentials.' });
        }
    } catch (err) {
        console.error('Login error:', err);
        return res.status(500).json({ success: false, message: 'Server error. Please try again.' });
    }
});

const districtAliases = {
    'Ahmedabad': ['ahmedabad'],
    'Amreli': ['amreli'],
    'Anand': ['anand'],
    'Aravalli': ['aravalli', 'sabarkantha', 'sabar kantha'],
    'Banaskantha': ['banaskantha', 'banas kantha'],
    'Bharuch': ['bharuch'],
    'Bhavnagar': ['bhavnagar'],
    'Botad': ['botad', 'bhavnagar', 'ahmedabad'],
    'Chhota Udepur': ['chhota udepur', 'chhotaudepur', 'vadodara', 'baroda'],
    'Dahod': ['dahod', 'dohad'],
    'Dang': ['dang', 'dangs', 'the dangs'],
    'Devbhoomi Dwarka': ['devbhoomi dwarka', 'devbhumi dwarka', 'jamnagar'],
    'Gandhinagar': ['gandhinagar'],
    'Gir Somnath': ['gir somnath', 'junagadh'],
    'Jamnagar': ['jamnagar'],
    'Junagadh': ['junagadh'],
    'Kheda': ['kheda', 'nadiad'],
    'Kutch': ['kutch', 'kachchh'],
    'Mahisagar': ['mahisagar', 'panchmahal', 'panch mahals', 'kheda'],
    'Mehsana': ['mehsana', 'mahesana'],
    'Morbi': ['morbi', 'rajkot', 'surendranagar', 'jamnagar'],
    'Narmada': ['narmada', 'bharuch'],
    'Navsari': ['navsari', 'valsad'],
    'Panchmahal': ['panchmahal', 'panch mahals'],
    'Patan': ['patan'],
    'Porbandar': ['porbandar'],
    'Rajkot': ['rajkot'],
    'Sabarkantha': ['sabarkantha', 'sabar kantha'],
    'Surat': ['surat'],
    'Surendranagar': ['surendranagar'],
    'Tapi': ['tapi', 'surat'],
    'Vadodara': ['vadodara', 'baroda'],
    'Valsad': ['valsad']
};

function normalizeString(str) {
    return (str || '').toLowerCase().replace(/[^a-z0-9]/g, '');
}

function verifyPincodeDistrictMatch(pincodeDistricts, selectedDistrict) {
    if (!pincodeDistricts || pincodeDistricts.length === 0) return true;
    const normSelected = normalizeString(selectedDistrict);
    const allowed = districtAliases[selectedDistrict] || [selectedDistrict];
    const normAllowed = allowed.map(normalizeString);
    
    return pincodeDistricts.some(d => {
        const normD = normalizeString(d);
        return normAllowed.some(alias => 
            normD.includes(alias) || alias.includes(normD)
        );
    });
}

// 3. REGISTER API
router.post('/register_api.php', async (req, res) => {
    try {
        const { full_name, name, email, phone_no, phone, district, city, pincode, pref_lang, pin, role = 'farmer' } = req.body || {};

        if (role !== 'farmer') {
            return res.status(400).json({ success: false, message: 'Only farmer registration is allowed.' });
        }

        const inputName = (full_name || name || '').trim();
        const inputEmail = (email || '').trim() || null;
        const inputPhone = (phone_no || phone || '').trim();
        const inputDistrict = (district || '').trim();
        const inputCity = (city || '').trim();
        const inputPincode = (pincode || '').trim();
        const inputLang = (pref_lang || '').trim();
        const inputPin = (pin || '').trim();

        if (!inputName || !inputPhone || !inputDistrict || !inputCity || !inputPincode || !inputLang || !inputPin) {
            return res.status(400).json({ success: false, message: 'All fields are required.' });
        }

        // Validate Pincode district boundaries using postal API
        const pincodesDir = process.env.VERCEL === '1' ? '/tmp/pincodes' : path.join(__dirname, '../cache/pincodes');
        const pincodeCachePath = path.join(pincodesDir, `${inputPincode}.json`);
        let pincodeLookupDistricts = [];
        let pincodeApiFailed = false;

        if (fs.existsSync(pincodeCachePath)) {
            try {
                const cacheData = JSON.parse(fs.readFileSync(pincodeCachePath, 'utf8'));
                pincodeLookupDistricts = cacheData.districts || [];
            } catch (e) {}
        } else {
            try {
                const response = await fetch(`https://api.postalpincode.in/pincode/${inputPincode}`, { signal: AbortSignal.timeout(4000) });
                if (response.ok) {
                    const postalData = await response.json();
                    if (postalData && postalData[0]) {
                        if (postalData[0].Status === 'Success') {
                            const offices = postalData[0].PostOffice || [];
                            offices.forEach(po => {
                                if (po.District && !pincodeLookupDistricts.includes(po.District)) {
                                    pincodeLookupDistricts.push(po.District);
                                }
                            });
                            if (!fs.existsSync(pincodesDir)) {
                                fs.mkdirSync(pincodesDir, { recursive: true });
                            }
                            fs.writeFileSync(pincodeCachePath, JSON.stringify({ districts: pincodeLookupDistricts }));
                        } else if (postalData[0].Status === 'Error') {
                            if (!fs.existsSync(pincodesDir)) {
                                fs.mkdirSync(pincodesDir, { recursive: true });
                            }
                            fs.writeFileSync(pincodeCachePath, JSON.stringify({ districts: [] }));
                            return res.status(400).json({ success: false, message: 'Pincode not found. Please enter a valid Indian pincode.' });
                        }
                    }
                }
            } catch (e) {
                pincodeApiFailed = true;
            }
        }

        if (pincodeLookupDistricts.length > 0) {
            const pincodeMatch = verifyPincodeDistrictMatch(pincodeLookupDistricts, inputDistrict);
            if (!pincodeMatch) {
                return res.status(400).json({ success: false, message: 'Pincode operates under a different geographical district.' });
            }
        } else if (fs.existsSync(pincodeCachePath) && !pincodeApiFailed) {
            return res.status(400).json({ success: false, message: 'Pincode not found. Please enter a valid Indian pincode.' });
        }

        // Duplicate Check
        const query = { phone: inputPhone };
        if (inputEmail) {
            query.$or = [{ phone: inputPhone }, { email: inputEmail }];
        }
        const duplicate = await Farmer.findOne(query);
        if (duplicate) {
            return res.status(409).json({ success: false, message: 'Phone or email already registered.' });
        }

        // Create Farmer
        const newFarmer = await Farmer.create({
            name: inputName,
            email: inputEmail,
            phone: inputPhone,
            district: inputDistrict,
            city: inputCity,
            pincode: inputPincode,
            pin: inputPin, // Automatically hashed by Mongoose schema
            pref_lang: inputLang
        });

        req.session.user_id = newFarmer._id.toString();
        req.session.user_name = newFarmer.name;
        req.session.user_role = 'farmer';

        return res.json({
            success: true,
            message: 'Registration successful!',
            user: {
                id: newFarmer._id.toString(),
                name: newFarmer.name,
                pref_lang: newFarmer.pref_lang,
                role: 'farmer'
            }
        });
    } catch (err) {
        console.error('Registration error:', err);
        return res.status(500).json({ success: false, message: 'Server error. Please try again.' });
    }
});

// 4. PROFILE GET/POST API
router.route('/profile_api.php')
    .all(requireAuth)
    .get(async (req, res) => {
        try {
            const reqId = req.query.id || req.session.user_id;
            
            if (req.session.user_role === 'farmer' && reqId.toString() !== req.session.user_id.toString()) {
                return res.status(403).json({ success: false, message: 'Forbidden. You can only access your own profile.' });
            }

            const farmer = await Farmer.findById(reqId).select('-pin');
            if (!farmer) {
                return res.status(404).json({ success: false, message: 'User not found' });
            }

            // Map fields to match SQL return format
            return res.json({
                success: true,
                user: {
                    id: farmer._id.toString(),
                    name: farmer.name,
                    email: farmer.email || '',
                    phone: farmer.phone,
                    district: farmer.district,
                    city: farmer.city,
                    pincode: farmer.pincode,
                    pref_lang: farmer.pref_lang
                }
            });
        } catch (err) {
            return res.status(500).json({ success: false, message: 'Server error: ' + err.message });
        }
    })
    .post(async (req, res) => {
        try {
            const reqId = req.body.id || req.session.user_id;

            if (req.session.user_role === 'farmer' && reqId.toString() !== req.session.user_id.toString()) {
                return res.status(403).json({ success: false, message: 'Forbidden. You can only access your own profile.' });
            }

            const { name, email, phone, district, city, pincode, pref_lang, pin } = req.body || {};

            if (!name || !phone || !district || !city || !pincode || !pref_lang) {
                return res.status(400).json({ success: false, message: 'All fields except PIN and Email are required.' });
            }

            const cleanEmail = email ? email.trim() : null;

            // Duplicate check
            const duplicate = await Farmer.findOne({
                _id: { $ne: reqId },
                $or: [{ phone }, ...(cleanEmail ? [{ email: cleanEmail }] : [])]
            });
            if (duplicate) {
                return res.status(409).json({ success: false, message: 'Phone or email already registered by another user.' });
            }

            const farmer = await Farmer.findById(reqId);
            if (!farmer) {
                return res.status(404).json({ success: false, message: 'Farmer not found.' });
            }

            farmer.name = name.trim();
            farmer.email = cleanEmail;
            farmer.phone = phone.trim();
            farmer.district = district.trim();
            farmer.city = city.trim();
            farmer.pincode = pincode.trim();
            farmer.pref_lang = pref_lang.trim();

            if (pin && pin.trim()) {
                if (!/^\d{6}$/.test(pin.trim())) {
                    return res.status(400).json({ success: false, message: 'PIN must be exactly 6 digits.' });
                }
                farmer.pin = pin.trim();
            }

            await farmer.save();

            return res.json({
                success: true,
                message: 'Profile updated successfully!',
                user: {
                    id: farmer._id.toString(),
                    name: farmer.name,
                    pref_lang: farmer.pref_lang,
                    role: 'farmer'
                }
            });
        } catch (err) {
            return res.status(500).json({ success: false, message: 'Server error: ' + err.message });
        }
    });

// 5. GET MARKET API (Mandi Ticker & Filter search query)
router.post('/get_market.php', async (req, res) => {
    try {
        const { districts = [], markets = [], commodities = [] } = req.body || {};
        
        // Simple Caching logic
        const cachePath = process.env.VERCEL === '1' ? '/tmp/get_market_cache.json' : path.join(__dirname, '../cache/get_market_cache.json');
        
        // Check if cached
        const hasFilters = districts.length > 0 || markets.length > 0 || commodities.length > 0;
        if (!hasFilters && fs.existsSync(cachePath)) {
            const stat = fs.statSync(cachePath);
            if (Date.now() - stat.mtimeMs < 600000) { // 10 mins cache
                res.setHeader('X-Cache', 'HIT');
                return res.json(JSON.parse(fs.readFileSync(cachePath, 'utf8')));
            }
        }

        // Trigger market sync asynchronously in background if out-of-sync
        const syncMetaPath = process.env.VERCEL === '1' ? '/tmp/last_market_sync.json' : path.join(__dirname, '../cache/last_market_sync.json');
        let shouldSync = true;
        
        if (fs.existsSync(syncMetaPath)) {
            try {
                const meta = JSON.parse(fs.readFileSync(syncMetaPath, 'utf8'));
                const lastSync = meta.synced_at ? new Date(meta.synced_at).getTime() : 0;
                if (Date.now() - lastSync < 3600000) { // 1 hour sync gate
                    shouldSync = false;
                }
            } catch (e) {}
        }
        
        if (shouldSync) {
            syncLatestMarketData().catch(err => console.error('Background market sync failed:', err));
        }

        // Construct MongoDB query
        const query = { state: { $regex: /^gujarat$/i } };
        
        if (districts.length > 0) {
            const mappedDistricts = districts.map(d => new RegExp(d.replace('Banaskantha', 'Banaskanth').trim(), 'i'));
            query.district = { $in: mappedDistricts };
        }
        
        if (markets.length > 0) {
            const mappedMarkets = markets.map(m => new RegExp(m.replace(' APMC', '').split('(')[0].trim(), 'i'));
            query.market = { $in: mappedMarkets };
        }
        
        if (commodities.length > 0) {
            const mappedCommodities = commodities.map(c => new RegExp(c.split('(')[0].trim(), 'i'));
            query.commodity = { $in: mappedCommodities };
        }

        // Query the latest date present in DB first
        const latestPriceDoc = await MarketPrice.findOne({}).sort({ arrival_date: -1 });
        if (latestPriceDoc && latestPriceDoc.arrival_date) {
            query.arrival_date = latestPriceDoc.arrival_date;
        }

        const data = await MarketPrice.find(query)
            .sort({ district: 1, market: 1, commodity: 1 })
            .limit(500);

        const resultRows = data.map(r => ({
            commodity: r.commodity,
            market: r.market,
            district: r.district,
            min: r.min_price,
            max: r.max_price,
            modal: r.modal_price,
            arrival_date: r.arrival_date
        }));

        const response = {
            success: true,
            target_date: latestPriceDoc ? latestPriceDoc.arrival_date : '',
            today: new Date().toLocaleDateString('en-GB'),
            rows: resultRows
        };

        // Cache if no filters
        if (!hasFilters) {
            const cacheDir = path.dirname(cachePath);
            if (!fs.existsSync(cacheDir)) {
                fs.mkdirSync(cacheDir, { recursive: true });
            }
            fs.writeFileSync(cachePath, JSON.stringify(response));
        }

        res.setHeader('X-Cache', 'MISS');
        return res.json(response);

    } catch (err) {
        console.error('Market prices API error:', err);
        return res.status(500).json({ error: err.message });
    }
});

// 6. GET FILTERS API (For Mandi Search Autocomplete lists)
router.post('/get_filters.php', async (req, res) => {
    try {
        const cachePath = process.env.VERCEL === '1' ? '/tmp/get_filters_cache.json' : path.join(__dirname, '../cache/get_filters_cache.json');
        
        if (fs.existsSync(cachePath)) {
            const stat = fs.statSync(cachePath);
            if (Date.now() - stat.mtimeMs < 3600000) { // 1 hour cache
                res.setHeader('X-Cache', 'HIT');
                return res.json(JSON.parse(fs.readFileSync(cachePath, 'utf8')));
            }
        }

        // Distinct queries in MongoDB
        const districts = await MarketPrice.distinct('district', { state: { $regex: /^gujarat$/i } });
        const markets = await MarketPrice.distinct('market', { state: { $regex: /^gujarat$/i } });
        const commodities = await MarketPrice.distinct('commodity', { state: { $regex: /^gujarat$/i } });

        districts.sort();
        markets.sort();
        commodities.sort();

        const response = {
            districts: districts.filter(Boolean),
            markets: markets.filter(Boolean),
            commodities: commodities.filter(Boolean)
        };

        fs.writeFileSync(cachePath, JSON.stringify(response));
        res.setHeader('X-Cache', 'MISS');
        return res.json(response);

    } catch (err) {
        return res.status(500).json({ error: 'Server error' });
    }
});

// 7. GET SUBSIDIES API
router.post('/get_subsidies.php', async (req, res) => {
    try {
        const { category = 'All', search = '' } = req.body || {};
        const query = {};
        
        if (category !== 'All' && category !== '') {
            query.category = category;
        }
        
        if (search && search.trim() !== '') {
            const regex = new RegExp(search.trim(), 'i');
            query.$or = [
                { name: regex },
                { name_gu: regex },
                { name_hi: regex },
                { description: regex },
                { description_gu: regex },
                { description_hi: regex },
                { benefits: regex },
                { benefits_gu: regex },
                { benefits_hi: regex }
            ];
        }

        const subsidies = await Subsidy.find(query).sort({ last_updated: -1 });

        const mappedResult = subsidies.map(sub => ({
            id: sub._id.toString(),
            name: sub.name,
            name_gu: sub.name_gu || sub.name,
            name_hi: sub.name_hi || sub.name,
            category: sub.category,
            category_gu: sub.category_gu || sub.category,
            category_hi: sub.category_hi || sub.category,
            description: sub.description,
            description_gu: sub.description_gu || sub.description,
            description_hi: sub.description_hi || sub.description,
            benefits: sub.benefits || '',
            benefits_gu: sub.benefits_gu || sub.benefits || '',
            benefits_hi: sub.benefits_hi || sub.benefits || '',
            eligibility: sub.eligibility || 'All Farmers',
            eligibility_gu: sub.eligibility_gu || 'બધા ખેડૂતો',
            eligibility_hi: sub.eligibility_hi || 'सभी किसान',
            apply_link: sub.apply_link || '',
            status: sub.status || 'Live',
            last_updated: sub.last_updated ? sub.last_updated.toISOString() : new Date().toISOString()
        }));

        return res.json(mappedResult);
    } catch (err) {
        return res.status(500).json({ error: err.message });
    }
});

// 8. SHOPS API
router.get('/shops_api.php', async (req, res) => {
    try {
        const district = trimString(req.query.district);
        const pest = trimString(req.query.pest);
        
        const lat = req.query.lat ? parseFloat(req.query.lat) : null;
        const lng = req.query.lng ? parseFloat(req.query.lng) : null;

        // Fetch local shops
        let query = {};
        if (district) {
            query.district = new RegExp(district, 'i');
        }
        let shopsList = await Shop.find(query).limit(3);

        // Fallback to general shops if empty
        if (shopsList.length === 0 && district) {
            shopsList = await Shop.find({}).limit(3);
        }

        const enrichedShops = shopsList.map(s => {
            const shopName = s.name;
            const addr = `${s.address || ''}, ${s.city || ''}`.trim();
            let map_url = '';
            let directions_url = '';

            if (lat !== null && lng !== null) {
                map_url = `https://www.google.com/maps/search/${encodeURIComponent(shopName)}/@${lat},${lng},14z`;
                directions_url = `https://www.google.com/maps/dir/${lat},${lng}/${encodeURIComponent(addr)}`;
            } else {
                map_url = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(shopName + ' ' + addr)}`;
            }

            return {
                name: s.name,
                city: s.city,
                address: s.address,
                phone: s.phone,
                map_url,
                directions_url
            };
        });

        // Maps iframe embedding link builder
        let mapEmbedSrc = null;
        if (lat !== null && lng !== null) {
            mapEmbedSrc = `https://maps.google.com/maps?q=${encodeURIComponent('agricultural shop near me')}&ll=${lat},${lng}&z=13&output=embed`;
        } else if (district) {
            mapEmbedSrc = `https://maps.google.com/maps?q=${encodeURIComponent('agricultural shop in ' + district)}&z=12&output=embed`;
        }

        let pesticides = [];
        if (pest) {
            const commonName = normalizePestName(pest);
            // Search Pesticide matching nested pests array or raw pesticide properties
            pesticides = await Pesticide.find({
                $or: [
                    { 'pests.pest_name': { $regex: new RegExp(commonName, 'i') } },
                    { 'pests.pest_name': { $regex: new RegExp(pest, 'i') } },
                    { name: { $regex: new RegExp(commonName, 'i') } }
                ]
            });
        }

        return res.json({
            shops: enrichedShops,
            pesticides: pesticides.map(p => ({
                name: p.name,
                brand: p.brand,
                price_range: p.price_range,
                category: p.category,
                usage_instructions: p.usage,
                target_pests: p.target_pests
            })),
            district,
            pest,
            lat,
            lng,
            map_embed_src: mapEmbedSrc
        });

    } catch (err) {
        return res.status(500).json({ error: err.message });
    }
});

// Helper string trimmer
function trimString(val) {
    return val ? val.toString().trim() : '';
}

// 9. SUBMIT FEEDBACK API
router.post('/submit_feedback_api.php', requireAuth, async (req, res) => {
    try {
        const { subject = '', message = '', rating = 5 } = req.body || {};
        
        if (!subject.trim() || !message.trim()) {
            return res.status(400).json({ success: false, error: 'Subject and message are required.' });
        }

        const score = Math.max(1, Math.min(5, parseInt(rating) || 5));

        await Feedback.create({
            user_id: req.session.user_id,
            subject: subject.trim(),
            message: message.trim(),
            rating: score
        });

        return res.json({ success: true, message: 'Feedback submitted successfully.' });
    } catch (err) {
        return res.status(500).json({ success: false, error: 'Database insertion failed.' });
    }
});

// 10. GET FEEDBACKS API
router.get('/get_feedbacks_api.php', requireAuth, async (req, res) => {
    try {
        if (req.session.user_role !== 'admin') {
            return res.status(403).json({ error: 'Forbidden' });
        }
        
        const feedbacks = await Feedback.find({})
            .populate('user_id', 'name phone')
            .sort({ createdAt: -1 });

        const mapped = feedbacks.map(f => ({
            id: f._id.toString(),
            farmer_name: f.user_id ? f.user_id.name : 'Unknown User',
            farmer_phone: f.user_id ? f.user_id.phone : 'N/A',
            subject: f.subject,
            message: f.message,
            rating: f.rating,
            created_at: f.createdAt.toISOString()
        }));

        return res.json({ success: true, feedbacks: mapped });
    } catch (err) {
        return res.status(500).json({ error: err.message });
    }
});

// 11. AI SCANS & SYSTEM READY MARKER
router.get('/ai_health.php', (req, res) => {
    const ready = fs.existsSync(path.join(__dirname, '../../../ai/plant_disease_model.keras'));
    return res.json({ status: ready ? 'healthy' : 'error' });
});

// Multipart/form-data upload setup for disease diagnostic
const multer = require('multer');
const uploadDir = process.env.VERCEL === '1' ? '/tmp/uploads/' : path.join(__dirname, '../cache/uploads/');
const upload = multer({ dest: uploadDir });

router.post('/ai_predict.php', upload.single('image'), async (req, res) => {
    try {
        if (!req.file) {
            return res.status(400).json({ error: 'No image uploaded' });
        }

        const userId = req.body.user_id || req.session.user_id || null;
        const lang = req.body.lang || 'en';

        const predictResult = await runDiseaseDetection(req.file.path, lang);
        
        // delete temp file
        fs.unlink(req.file.path, () => {});

        if (predictResult.error) {
            return res.status(500).json({ error: 'AI prediction failed: ' + predictResult.error });
        }

        const identifiedPest = predictResult.label || 'Unknown';
        const rawDisease = predictResult.disease || 'unknown';

        // Check if healthy
        if (rawDisease.toLowerCase().includes('healthy')) {
            return res.json({
                label: identifiedPest,
                plant: predictResult.plant || 'Detected',
                confidence: predictResult.confidence,
                info: {
                    desc: predictResult.info?.desc || 'The plant appears healthy.',
                    irrigation: predictResult.info?.irrigation || 'Maintain normal watering.',
                    treatment: predictResult.info?.treatment || 'No treatment required.'
                }
            });
        }

        // Fetch pesticide recommendations
        const commonName = normalizePestName(identifiedPest);
        const pDocs = await Pesticide.find({
            $or: [
                { 'pests.pest_name': { $regex: new RegExp(commonName, 'i') } },
                { 'pests.pest_name': { $regex: new RegExp(identifiedPest, 'i') } }
            ]
        });

        let treatment = predictResult.info?.treatment || 'Ensure proper air circulation. ';
        if (pDocs.length > 0) {
            const names = pDocs.map(p => `Use ${p.brand} (${p.name})`);
            treatment += names.join(', ') + '.';
        } else {
            treatment += 'Consult your local agri-expert for specific local pesticide brands.';
        }

        // Log scan event — always store raw English class name for analytics
        if (userId && Farmer.db.models.Farmer) {
            try {
                // rawDisease is the English class key (e.g. "Corn___Northern_Leaf_Blight")
                // normalise it: strip plant prefix, replace ___ and _ with spaces
                const englishPestName = rawDisease
                    .replace(/^[^_]+___/, '')       // remove "Plant___" prefix
                    .replace(/___/g, ' - ')
                    .replace(/_/g, ' ')
                    .trim();
                await AIScan.create({
                    user_id: userId,
                    pest_name: englishPestName || identifiedPest
                });
            } catch (e) {
                console.error('Scan logging failed:', e);
            }
        }

        return res.json({
            label: commonName || identifiedPest,
            plant: predictResult.plant || 'Detected',
            confidence: predictResult.confidence,
            top3: predictResult.top3 || [],
            info: {
                desc: predictResult.info?.desc || `Identified as ${identifiedPest}`,
                irrigation: predictResult.info?.irrigation || 'Reduce foliar moisture to prevent spread.',
                treatment
            }
        });

    } catch (err) {
        if (req.file && fs.existsSync(req.file.path)) {
            fs.unlink(req.file.path, () => {});
        }
        return res.status(500).json({ error: err.message });
    }
});

// Helper tool to extract main pest names
function normalizePestName(raw) {
    const clean = raw.replace(/___/g, ' ').replace(/_/g, ' ');
    const parts = clean.split(' ');
    if (parts.length > 1) {
        return parts.slice(1).join(' ').trim();
    }
    return clean.trim();
}

// 12. CHAT BOT INTEGRATION API (AgriBot)
router.route('/chat_api.php')
    .all(requireAuth)
    .get(async (req, res) => {
        try {
            const reqUserId = req.query.user_id || req.session.user_id;

            if (req.session.user_role === 'farmer' && reqUserId.toString() !== req.session.user_id.toString()) {
                return res.status(403).json({ error: 'Forbidden' });
            }

            // session retrieval
            if (req.query.sessions !== undefined) {
                console.log('Fetching sessions for reqUserId:', reqUserId);
                // Return unique session keys first user message
                const history = await ChatMessage.aggregate([
                    { $match: { user_id: new mongoose.Types.ObjectId(reqUserId), role: 'user' } },
                    { $sort: { createdAt: 1 } },
                    { $group: {
                        _id: '$session_key',
                        title: { $first: '$message' },
                        createdAt: { $first: '$createdAt' }
                    }},
                    { $sort: { createdAt: -1 } }
                ]);
                
                const sessions = history.map(h => ({
                    session_key: h._id,
                    title: h.title,
                    created_at: h.createdAt.toISOString(),
                    last_activity: h.createdAt.toISOString()
                }));
                console.log('Returned sessions count:', sessions.length);
                return res.json({ sessions });
            }

            const sessionKey = req.query.session_key || 'guest';
            const history = await ChatMessage.find({
                user_id: reqUserId,
                session_key: sessionKey
            }).sort({ createdAt: 1 }).limit(50);

            return res.json({
                messages: history.map(h => ({
                    role: h.role,
                    message: h.message,
                    model: h.model || '',
                    created_at: h.createdAt.toISOString()
                }))
            });

        } catch (err) {
            return res.status(500).json({ error: err.message });
        }
    })
    .delete(async (req, res) => {
        try {
            const reqUserId = req.query.user_id || req.session.user_id;
            if (req.session.user_role === 'farmer' && reqUserId.toString() !== req.session.user_id.toString()) {
                return res.status(403).json({ error: 'Forbidden' });
            }

            const { session_key } = req.query || {};
            if (!session_key) {
                return res.status(400).json({ error: 'Session key required' });
            }

            await ChatMessage.deleteMany({ user_id: reqUserId, session_key });
            return res.json({ ok: true });
        } catch (err) {
            return res.status(500).json({ error: err.message });
        }
    })
    .post(upload.single('image'), async (req, res) => {
        try {
            const { message = '', session_key = 'guest', user = {} } = req.body || {};

            if (!message.trim()) {
                return res.status(400).json({ error: 'Message is required.' });
            }

            const farmer = await Farmer.findById(req.session.user_id);
            if (!farmer) {
                return res.status(401).json({ error: 'Unauthorized. Please login.' });
            }

            // Save user message
            await ChatMessage.create({
                user_id: req.session.user_id,
                session_key,
                role: 'user',
                message: message.trim()
            });

            // Detect Intents using keywords
            const text = message.toLowerCase();
            const hasMarket = /\b(market|mandi|price|prices|rate|rates|apmc|sell|selling|મંડી|ભાવ|मंडी)\b/.test(text);
            const hasSubsidy = /\b(subsidy|subsidies|scheme|schemes|loan|grant|benefit|benefits|irrigation|સબસિડી|સહાય|યોજના|योजना)\b/.test(text);
            const hasSchedule = /\b(schedule|calendar|task|tasks|when|sow|plant|watering|fertilizer|spray|harvest|પત્રક|कैलेंडर)\b/.test(text);
            const hasShop = /\b(shop|shops|store|stores|buy|purchase|dealer|agri-shop|agriculture shop|locate|near me|nearby|દુકાન|માર્કેટ|દુકાનો|दुकान)\b/.test(text);
            const general = !hasMarket && !hasSubsidy && !hasSchedule && !hasShop;

            let searchCrop = farmer.pref_lang === 'gu' ? 'ઘઉં' : 'wheat';
            const crops = ['wheat', 'cotton', 'groundnut', 'tomato', 'potato', 'onion', 'mango', 'banana', 'chilli'];
            for (const c of crops) {
                if (text.includes(c)) {
                    searchCrop = c;
                    break;
                }
            }

            // Gather context data for the LLM
            let marketContext = '';
            if (hasMarket || general) {
                const prices = await MarketPrice.find({ district: new RegExp(farmer.district, 'i'), commodity: new RegExp(searchCrop, 'i') })
                    .sort({ arrival_date: -1 }).limit(5);
                marketContext = prices.map(p => `- Mandi ${p.market} (${p.district}): ₹${p.modal_price} on ${p.arrival_date}`).join('\n');
            }

            let subsidyContext = '';
            if (hasSubsidy || general) {
                const subsidies = await Subsidy.find({ $or: [{ name: new RegExp(searchCrop, 'i') }, { category: 'Financial' }] }).limit(3);
                subsidyContext = subsidies.map(s => `- Scheme ${s.name}: Category ${s.category}, Apply link: ${s.apply_link}`).join('\n');
            }

            let scheduleContext = '';
            if (hasSchedule || general) {
                const crop = await Crop.findOne({ name: new RegExp(searchCrop, 'i') });
                if (crop) {
                    scheduleContext = `Seasonal activity schedules for ${crop.name} (${crop.season}):\n` + 
                        crop.schedule.slice(0, 4).map(s => `- Month index ${s.month_index + 1}: ${s.task_en}`).join('\n');
                }
            }

            let shopContext = '';
            if (hasShop || general) {
                const shops = await Shop.find({ district: new RegExp(farmer.district, 'i') }).limit(3);
                shopContext = shops.map(s => `- Vendor ${s.name} at ${s.address}, Phone ${s.phone}`).join('\n');
            }

            const promptContext = `
FARM CONTEXT INFO:
Farmer: ${farmer.name} located in ${farmer.city}, ${farmer.district}. Preferred Language: ${farmer.pref_lang}.
Market snapshots:
${marketContext || 'No matching prices'}
Subsidies:
${subsidyContext || 'No schemes'}
Crop schedule:
${scheduleContext || 'No calendar schedule'}
Local shops:
${shopContext || 'No shops listed'}
`;

            const conversation = [
                {
                    role: 'system',
                    content: `You are AgriBot, an elite Agricultural Expert AI possessing deep knowledge of agronomy, plant pathology, meteorology, soil science, and agricultural economics. 
You must reply ONLY in the farmer's preferred language: ${farmer.pref_lang === 'gu' ? 'Gujarati' : farmer.pref_lang === 'hi' ? 'Hindi' : 'English'}.
Rules:
1. Provide highly actionable, scientifically accurate, and comprehensive step-by-step advice.
2. Address the farmer by name and deeply integrate the context details provided below into your strategy.
3. If the farmer asks a non-agricultural question, politely steer the conversation back to farming.
4. Use structured markdown formatting (bold text, bullet points, numbered lists) to make complex advice easy to read and extremely professional.`
                },
                {
                    role: 'user',
                    content: `${promptContext}\n\nFarmer question: ${message}`
                }
            ];

            let reply = '';
            let modelUsed = 'gemini-1.5-flash';
            let provider = 'gemini';

            // Primary Call: Gemini
            const geminiRes = await geminiTextCreate(conversation, 'gemini-1.5-flash');
            if (geminiRes.ok) {
                reply = geminiExtractText(geminiRes.data);
                modelUsed = geminiRes.model;
            } else {
                // Secondary Fallback: Groq
                const groqRes = await groqChatCreate(conversation, 'llama-3.1-8b-instant');
                if (groqRes.ok) {
                    reply = groqExtractText(groqRes.data);
                    modelUsed = 'llama-3.1-8b-instant';
                    provider = 'groq';
                }
            }

            // Final Failsafe
            if (!reply) {
                provider = 'failsafe';
                modelUsed = 'hardcoded-agricultural-logic';
                reply = farmer.pref_lang === 'gu' 
                    ? `ક્ષમા કરશો, સર્વર વ્યસ્ત છે. કૃપા કરીને થોડીવાર પછી પ્રયત્ન કરો. તમે મેનુમાં 'મંડી ભાવ' અથવા 'સબસીડી' જોઈ શકો છો.`
                    : farmer.pref_lang === 'hi'
                    ? `क्षमा करें, सर्वर व्यस्त है। कृपया थोड़ी देर बाद पुनः प्रयास करें। आप मंडी या सब्सिडी अनुभाग देख सकते हैं।`
                    : `I'm sorry, I'm having a bit of trouble connecting to my AI core. Please check Mandi Prices and Subsidies sections directly from the menu.`;
            }

            // Save assistant reply
            await ChatMessage.create({
                user_id: req.session.user_id,
                session_key,
                role: 'assistant',
                message: reply,
                model: modelUsed
            });

            return res.json({
                reply,
                model: modelUsed,
                provider
            });

        } catch (err) {
            console.error('Chat Bot error:', err);
            return res.status(500).json({ error: 'Server error: ' + err.message });
        }
    });

// 13. ADMIN STATS & USERS BASE CONTROL
router.get('/admin_stats_api.php', requireAuth, async (req, res) => {
    try {
        if (req.session.user_role !== 'admin') {
            return res.status(403).json({ error: 'Forbidden' });
        }

        const totalFarmers = await Farmer.countDocuments({});
        const totalSubsidies = await Subsidy.countDocuments({});
        const totalMarkets = await MarketPrice.distinct('market').then(list => list.length);
        const totalScans = await AIScan.countDocuments({});

        const recentUsers = await Farmer.find({})
            .sort({ createdAt: -1 })
            .limit(5);

        // --- Advanced analytics ---
        // Chat stats
        const totalChatMessages = await ChatMessage.countDocuments({});
        const uniqueSessions = await ChatMessage.distinct('session_key').then(list => list.length);

        // Feedback stats
        const totalFeedbacks = await Feedback.countDocuments({});
        const feedbackAggResult = await Feedback.aggregate([
            { $group: { _id: null, avgRating: { $avg: '$rating' } } }
        ]);
        const avgRating = feedbackAggResult.length > 0 ? Math.round(feedbackAggResult[0].avgRating * 10) / 10 : 0;

        const ratingBreakdown = await Feedback.aggregate([
            { $group: { _id: '$rating', count: { $sum: 1 } } },
            { $sort: { _id: -1 } }
        ]);
        const ratingMap = {};
        for (let i = 1; i <= 5; i++) ratingMap[i] = 0;
        for (const r of ratingBreakdown) ratingMap[r._id] = r.count;

        // Top AI diagnostics (pest names)
        const topPests = await AIScan.aggregate([
            { $group: { _id: '$pest_name', count: { $sum: 1 } } },
            { $sort: { count: -1 } },
            { $limit: 8 }
        ]);

        // Top user districts
        const topDistricts = await Farmer.aggregate([
            { $match: { district: { $exists: true, $ne: '' } } },
            { $group: { _id: '$district', count: { $sum: 1 } } },
            { $sort: { count: -1 } },
            { $limit: 10 }
        ]);

        // Registrations in last 7 days
        const now = new Date();
        const last7 = Array.from({ length: 7 }, (_, i) => {
            const d = new Date(now);
            d.setDate(d.getDate() - (6 - i));
            return d;
        });
        const regTrend = await Promise.all(last7.map(async (day) => {
            const start = new Date(day); start.setHours(0, 0, 0, 0);
            const end = new Date(day); end.setHours(23, 59, 59, 999);
            const count = await Farmer.countDocuments({ createdAt: { $gte: start, $lte: end } });
            return { label: start.toLocaleDateString('en-IN', { weekday: 'short', day: 'numeric', month: 'short' }), count };
        }));

        return res.json({
            farmers: totalFarmers,
            subsidies: totalSubsidies,
            markets: totalMarkets,
            scans: totalScans,
            recentUsers: recentUsers.map(u => ({
                name: u.name,
                phone: u.phone,
                district: u.district,
                created_at: u.createdAt.toISOString()
            })),
            analytics: {
                totalChatMessages,
                uniqueSessions,
                totalFeedbacks,
                avgRating,
                ratingBreakdown: ratingMap,
                topPests: topPests.map(p => ({ name: p._id, count: p.count })),
                topDistricts: topDistricts.map(d => ({ district: d._id, count: d.count })),
                regTrend
            }
        });

    } catch (err) {
        return res.status(500).json({ error: err.message });
    }
});

// ADMIN USERS API
router.route('/admin_users_api.php')
    .all(requireAuth)
    .get(async (req, res) => {
        try {
            if (req.session.user_role !== 'admin') return res.status(403).json({ error: 'Forbidden' });
            
            const farmers = await Farmer.find({}).sort({ createdAt: -1 });
            const mapped = farmers.map(f => ({
                id: f._id.toString(),
                name: f.name,
                email: f.email || '',
                phone: f.phone,
                district: f.district,
                city: f.city,
                pincode: f.pincode,
                pref_lang: f.pref_lang,
                created_at: f.createdAt.toISOString()
            }));
            return res.json({ success: true, farmers: mapped });
        } catch (err) {
            return res.status(500).json({ error: err.message });
        }
    })
    .delete(async (req, res) => {
        try {
            if (req.session.user_role !== 'admin') return res.status(403).json({ error: 'Forbidden' });
            const id = req.query.id || req.body.id;
            if (!id) return res.status(400).json({ error: 'ID required' });
            
            await Farmer.findByIdAndDelete(id);
            return res.json({ success: true, message: 'Farmer deleted successfully.' });
        } catch (err) {
            return res.status(500).json({ error: err.message });
        }
    });

// ADMIN SUBSIDIES API
router.route('/admin_subsidies_api.php')
    .all(requireAuth)
    .get(async (req, res) => {
        try {
            if (req.session.user_role !== 'admin') return res.status(403).json({ error: 'Forbidden' });
            const list = await Subsidy.find({}).sort({ last_updated: -1 });
            return res.json(list.map(s => ({
                id: s._id.toString(),
                name: s.name,
                name_gu: s.name_gu || '',
                name_hi: s.name_hi || '',
                category: s.category,
                description: s.description,
                benefits: s.benefits || '',
                eligibility: s.eligibility || 'All Farmers',
                apply_link: s.apply_link || '',
                status: s.status || 'Live'
            })));
        } catch (err) {
            return res.status(500).json({ error: err.message });
        }
    })
    .post(async (req, res) => {
        try {
            if (req.session.user_role !== 'admin') return res.status(403).json({ error: 'Forbidden' });
            const { id, name, name_gu, name_hi, category, description, description_gu, description_hi, benefits, benefits_gu, benefits_hi, eligibility, apply_link, status } = req.body || {};

            if (!name || !category || !description) {
                return res.status(400).json({ error: 'Name, Category, and Description are required.' });
            }

            const data = {
                name,
                name_gu: name_gu || name,
                name_hi: name_hi || name,
                category,
                description,
                description_gu: description_gu || description,
                description_hi: description_hi || description,
                benefits,
                benefits_gu: benefits_gu || benefits || '',
                benefits_hi: benefits_hi || benefits || '',
                eligibility: eligibility || 'All Farmers',
                apply_link,
                status: status || 'Live',
                last_updated: new Date()
            };

            if (id) {
                // Update
                await Subsidy.findByIdAndUpdate(id, data);
                return res.json({ success: true, message: 'Subsidy updated successfully!' });
            } else {
                // Insert
                await Subsidy.create(data);
                return res.json({ success: true, message: 'Subsidy added successfully!' });
            }
        } catch (err) {
            return res.status(500).json({ error: err.message });
        }
    })
    .delete(async (req, res) => {
        try {
            if (req.session.user_role !== 'admin') return res.status(403).json({ error: 'Forbidden' });
            const id = req.query.id || req.body.id;
            await Subsidy.findByIdAndDelete(id);
            return res.json({ success: true, message: 'Subsidy deleted successfully.' });
        } catch (err) {
            return res.status(500).json({ error: err.message });
        }
    });

// ADMIN PESTICIDES API
router.route('/admin_pesticides_api.php')
    .all(requireAuth)
    .get(async (req, res) => {
        try {
            if (req.session.user_role !== 'admin') return res.status(403).json({ error: 'Forbidden' });
            
            const list = await Pesticide.find({}).sort({ createdAt: -1 });
            
            const allPesticides = list.map(p => ({
                id: p._id.toString(),
                name: p.name,
                brand: p.brand || ''
            }));
            
            const mappings = [];
            list.forEach(p => {
                if (p.pests && p.pests.length > 0) {
                    p.pests.forEach(pest => {
                        mappings.push({
                            mapping_id: `${p._id.toString()}_${pest._id.toString()}`,
                            pest_name: pest.pest_name,
                            effectiveness: pest.effectiveness,
                            pesticide_id: p._id.toString(),
                            name: p.name,
                            brand: p.brand || '',
                            price_range: p.price_range || '',
                            target_pests: p.target_pests || '',
                            usage_instructions: p.usage || ''
                        });
                    });
                }
            });

            return res.json({
                status: 'success',
                mappings,
                pesticides: allPesticides
            });
        } catch (err) {
            return res.status(500).json({ status: 'error', message: err.message });
        }
    })
    .post(async (req, res) => {
        try {
            if (req.session.user_role !== 'admin') return res.status(403).json({ error: 'Forbidden' });
            
            const { action = '', id, brand, name, target_pests, price_range, usage_instructions, pest_name, pesticide_id, effectiveness } = req.body || {};

            if (action === 'add_pesticide') {
                if (!name || !brand) {
                    return res.status(400).json({ status: 'error', message: 'Name and brand are required.' });
                }
                const newP = await Pesticide.create({
                    name,
                    brand,
                    category: 'Chemical', // Default category
                    price_range: price_range || '',
                    target_pests: target_pests || '',
                    usage: usage_instructions || '',
                    pests: []
                });
                return res.json({
                    status: 'success',
                    message: 'Pesticide added.',
                    pesticide: {
                        id: newP._id.toString(),
                        name: newP.name,
                        brand: newP.brand
                    }
                });
            } else if (action === 'add_mapping') {
                if (!pest_name || !pesticide_id) {
                    return res.status(400).json({ status: 'error', message: 'pest_name and pesticide_id are required.' });
                }
                const pesticide = await Pesticide.findById(pesticide_id);
                if (!pesticide) {
                    return res.status(404).json({ status: 'error', message: 'Pesticide not found.' });
                }
                pesticide.pests.push({
                    pest_name,
                    effectiveness: effectiveness || 'High'
                });
                await pesticide.save();
                return res.json({ status: 'success', message: 'Mapping created.' });
            } else if (action === 'delete_mapping') {
                if (!id) {
                    return res.status(400).json({ status: 'error', message: 'Valid mapping id is required.' });
                }
                const [pesticideId, pestSubdocId] = id.split('_');
                if (!pesticideId || !pestSubdocId) {
                    return res.status(400).json({ status: 'error', message: 'Invalid mapping ID format.' });
                }
                const pesticide = await Pesticide.findById(pesticideId);
                if (!pesticide) {
                    return res.status(404).json({ status: 'error', message: 'Pesticide not found.' });
                }
                pesticide.pests = pesticide.pests.filter(p => p._id.toString() !== pestSubdocId);
                await pesticide.save();
                return res.json({ status: 'success', message: 'Mapping removed.' });
            } else {
                return res.status(400).json({ status: 'error', message: 'Invalid action.' });
            }
        } catch (err) {
            return res.status(500).json({ status: 'error', message: err.message });
        }
    });

// 14. SIMPLE REGIONAL AND METADATA CHECK APIS
router.get('/check_pincode_api.php', async (req, res) => {
    try {
        const { pincode = '', district = '' } = req.query || {};
        if (!pincode) return res.json({ verified: false });

        const pincodesDir = path.join(__dirname, '../cache/pincodes');
        const pincodeCachePath = path.join(pincodesDir, `${pincode}.json`);
        let pincodeLookupDistricts = [];
        let pincodeApiFailed = false;

        if (fs.existsSync(pincodeCachePath)) {
            try {
                const cacheData = JSON.parse(fs.readFileSync(pincodeCachePath, 'utf8'));
                pincodeLookupDistricts = cacheData.districts || [];
            } catch (e) {}
        } else {
            try {
                const response = await fetch(`https://api.postalpincode.in/pincode/${pincode}`, { signal: AbortSignal.timeout(4000) });
                if (response.ok) {
                    const data = await response.json();
                    if (data && data[0]) {
                        if (data[0].Status === 'Success') {
                            const offices = data[0].PostOffice || [];
                            offices.forEach(po => {
                                if (po.District && !pincodeLookupDistricts.includes(po.District)) {
                                    pincodeLookupDistricts.push(po.District);
                                }
                            });
                            if (!fs.existsSync(pincodesDir)) {
                                fs.mkdirSync(pincodesDir, { recursive: true });
                            }
                            fs.writeFileSync(pincodeCachePath, JSON.stringify({ districts: pincodeLookupDistricts }));
                        } else if (data[0].Status === 'Error') {
                            if (!fs.existsSync(pincodesDir)) {
                                fs.mkdirSync(pincodesDir, { recursive: true });
                            }
                            fs.writeFileSync(pincodeCachePath, JSON.stringify({ districts: [] }));
                        }
                    }
                }
            } catch (e) {
                pincodeApiFailed = true;
            }
        }

        if (pincodeApiFailed) {
            return res.json({ verified: true, fallback: true, districts: [] });
        }

        if (fs.existsSync(pincodeCachePath)) {
            if (pincodeLookupDistricts.length > 0) {
                if (district) {
                    const match = verifyPincodeDistrictMatch(pincodeLookupDistricts, district);
                    return res.json({ verified: match, districts: pincodeLookupDistricts });
                }
                return res.json({ verified: true, districts: pincodeLookupDistricts });
            }
            return res.json({ verified: false, districts: [] });
        }

        return res.json({ verified: true, fallback: true, districts: [] });
    } catch (err) {
        return res.json({ verified: true, fallback: true, districts: [] });
    }
});

// CHECK EMAIL API
router.get('/check_email_api.php', async (req, res) => {
    try {
        const { email = '' } = req.query || {};
        if (!email) return res.json({ available: true });

        const count = await Farmer.countDocuments({ email: email.trim() });
        return res.json({ available: count === 0 });
    } catch (err) {
        return res.json({ available: true });
    }
});

// GET CROPS API
router.get('/get_crops.php', async (req, res) => {
    try {
        const { district = '' } = req.query || {};
        let query = {};
        if (district && district.trim()) {
            const cleanDistrict = district.trim().replace('Banaskantha', 'Banaskanth');
            query = {
                $or: [
                    { districts: { $exists: false } },
                    { districts: { $size: 0 } },
                    { districts: { $regex: new RegExp(cleanDistrict.replace('Banaskanth', 'Banaskantha'), 'i') } },
                    { districts: { $regex: new RegExp(cleanDistrict.replace('Banaskantha', 'Banaskanth'), 'i') } }
                ]
            };
        }
        const crops = await Crop.find(query);
        // Map to client expected crop select payload fields
        return res.json(crops.map(c => ({
            id: c.name.toLowerCase(),
            name: c.name,
            name_en: c.name,
            name_gu: c.name_gu || c.name,
            name_hi: c.name_hi || c.name,
            icon: c.icon,
            season: c.season,
            season_en: c.season,
            season_gu: c.season_gu || c.season,
            season_hi: c.season_hi || c.season,
            category: c.category,
            schedule: c.schedule.map(s => ({
                month_index: s.month_index,
                activity_type: s.activity_type,
                activity_icon: s.activity_icon,
                activity_color: s.activity_color,
                task_en: s.task_en,
                task_gu: s.task_gu || s.task_en,
                task_hi: s.task_hi || s.task_en,
                start_day: s.month_index * 30 + 1,
                end_day: (s.month_index + 1) * 30
            }))
        })));
    } catch (err) {
        return res.status(500).json({ error: err.message });
    }
});

// LOGOUT ROUTE
router.post('/logout.php', (req, res) => {
    req.session.destroy(err => {
        if (err) return res.status(500).json({ success: false, message: 'Logout failed.' });
        res.clearCookie('connect.sid');
        return res.json({ success: true, message: 'Logged out successfully.' });
    });
});

module.exports = router;
