// backend/src/server.js
const express = require('express');
const cors = require('cors');
const cookieParser = require('cookie-parser');
const session = require('express-session');
const { MongoStore } = require('connect-mongo');
const path = require('path');
const fs = require('fs');
const config = require('./config/env');
const connectDB = require('./config/db');
const backendRoutes = require('./routes/backendRoutes');
const Subsidy = require('./models/Subsidy');

// Initialize app
const app = express();

// Connect Database
connectDB();

// Ensure upload cache directories exist
const uploadDir = process.env.VERCEL === '1' ? '/tmp/uploads' : path.join(__dirname, 'cache/uploads');
if (!fs.existsSync(uploadDir)) {
    fs.mkdirSync(uploadDir, { recursive: true });
}

// Basic Middleware
app.use(cors({
    origin: true,
    credentials: true
}));
app.use(cookieParser());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Express Session using MongoDB storage
app.use(session({
    secret: config.sessionSecret,
    resave: false,
    saveUninitialized: false,
    store: MongoStore.create({
        mongoUrl: config.mongoUri,
        collectionName: 'sessions',
        ttl: 14 * 24 * 60 * 60 // 14 days
    }),
    cookie: {
        maxAge: 14 * 24 * 60 * 60 * 1000, // 14 days
        httpOnly: true,
        secure: false // Set to true if using HTTPS
    }
}));

// Configure view engine to use EJS
app.set('view engine', 'ejs');
app.set('views', [
    path.join(__dirname, '../../frontend'),
    path.join(__dirname, '../../dashboard')
]);

// Auth checking middleware for view templates
const viewRequireAuth = (role) => (req, res, next) => {
    if (!req.session || !req.session.user_id) {
        return res.redirect('/login');
    }
    if (role && req.session.user_role !== role) {
        return res.redirect('/login');
    }
    next();
};

// Root route
app.get('/', (req, res) => res.render('index'));

// Clean frontend routes
app.get('/market', (req, res) => res.render('market'));
app.get('/weather', (req, res) => res.render('weather'));
app.get('/faq', (req, res) => res.render('faq'));
app.get('/qna', (req, res) => res.redirect('/faq'));
app.get('/about', (req, res) => res.render('about'));
app.get('/login', (req, res) => res.render('login'));
app.get('/register', (req, res) => res.render('register'));
app.get('/crop-calendar', (req, res) => res.render('crop_calendar'));
app.get('/chatbot', viewRequireAuth('farmer'), (req, res) => res.render('chatbot'));

// Backward-compatible redirects for old PHP URLs
app.get('/frontend/index.php', (req, res) => res.redirect('/'));
app.get('/frontend/market.php', (req, res) => res.redirect('/market'));
app.get('/frontend/weather.php', (req, res) => res.redirect('/weather'));
app.get('/frontend/qna.php', (req, res) => res.redirect('/faq'));
app.get('/frontend/about.php', (req, res) => res.redirect('/about'));
app.get('/frontend/login.php', (req, res) => res.redirect('/login'));
app.get('/frontend/register.php', (req, res) => res.redirect('/register'));
app.get('/frontend/crop_calendar.php', (req, res) => res.redirect('/crop-calendar'));
app.get('/frontend/chatbot.php', (req, res) => res.redirect('/chatbot'));
app.get('/frontend/subsidies.php', (req, res) => res.redirect('/farmer-dashboard?mod=subsidies'));
app.get('/frontend/disease_detection.php', (req, res) => res.redirect('/farmer-dashboard?mod=disease'));

// Clean dashboard routes
app.get('/farmer-dashboard', viewRequireAuth('farmer'), async (req, res) => {
    try {
        const subsidies = await Subsidy.find({}).sort({ last_updated: -1 });
        const fallback = subsidies.map(sub => ({
            id: sub._id.toString(),
            name: sub.name,
            name_gu: sub.name_gu,
            name_hi: sub.name_hi,
            category: sub.category,
            description: sub.description,
            description_gu: sub.description_gu,
            description_hi: sub.description_hi,
            benefits: sub.benefits,
            benefits_gu: sub.benefits_gu,
            benefits_hi: sub.benefits_hi,
            apply_link: sub.apply_link,
            status: sub.status,
            last_updated: sub.last_updated ? sub.last_updated.toISOString() : new Date().toISOString()
        }));
        res.render('farmer', { dashboardSubsidyFallback: fallback });
    } catch (err) {
        console.error('Error fetching fallback subsidies:', err);
        res.render('farmer', { dashboardSubsidyFallback: [] });
    }
});

app.get('/admin-dashboard', viewRequireAuth('admin'), (req, res) => res.render('admin', { currentPage: 'admin' }));
app.get('/admin-dashboard/users', viewRequireAuth('admin'), (req, res) => res.render('admin_users', { currentPage: 'admin_users' }));
app.get('/admin-dashboard/subsidies', viewRequireAuth('admin'), (req, res) => res.render('admin_subsidies', { currentPage: 'admin_subsidies' }));
app.get('/admin-dashboard/market', viewRequireAuth('admin'), (req, res) => res.render('admin_market', { currentPage: 'admin_market' }));
app.get('/admin-dashboard/pesticides', viewRequireAuth('admin'), (req, res) => res.render('admin_pesticides', { currentPage: 'admin_pesticides' }));
app.get('/admin-dashboard/feedback', viewRequireAuth('admin'), (req, res) => res.render('admin_feedback', { currentPage: 'admin_feedback' }));
app.get('/profile', viewRequireAuth('farmer'), (req, res) => res.render('profile'));

// Backward-compatible redirects for old dashboard PHP URLs
app.get('/dashboard/farmer.php', (req, res) => res.redirect('/farmer-dashboard' + (req.query.mod ? '?mod=' + req.query.mod : '')));
app.get('/dashboard/admin.php', (req, res) => res.redirect('/admin-dashboard'));
app.get('/dashboard/admin_users.php', (req, res) => res.redirect('/admin-dashboard/users'));
app.get('/dashboard/admin_subsidies.php', (req, res) => res.redirect('/admin-dashboard/subsidies'));
app.get('/dashboard/admin_market.php', (req, res) => res.redirect('/admin-dashboard/market'));
app.get('/dashboard/admin_pesticides.php', (req, res) => res.redirect('/admin-dashboard/pesticides'));
app.get('/dashboard/admin_feedback.php', (req, res) => res.redirect('/admin-dashboard/feedback'));
app.get('/dashboard/profile.php', (req, res) => res.redirect('/profile'));

// Mount Node APIs at /backend to mirror relative fetch URLs (e.g. ../backend/login_api.php)
app.use('/backend', backendRoutes);

// Static assets serving (CSS, JS, images)
app.use('/frontend', express.static(path.join(__dirname, '../../frontend')));
app.use('/dashboard', express.static(path.join(__dirname, '../../dashboard')));

// Start Server
if (process.env.NODE_ENV !== 'production' && process.env.VERCEL !== '1') {
    app.listen(config.port, () => {
        console.log(`Server is running on port ${config.port}`);
    });
}

// Export for Vercel Serverless
module.exports = app;
