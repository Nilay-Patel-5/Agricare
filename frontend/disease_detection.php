<?php
header("Location: ../dashboard/farmer.php?mod=disease");
exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disease Detection | AgriCare</title>
    <meta name="description" content="AI-powered plant disease detection for Gujarat farmers.">
    <link rel="stylesheet" href="output.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&family=Noto+Sans+Gujarati:wght@400;500;700&family=Noto+Sans+Devanagari:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        .gu-text {
            font-family: 'Noto Sans Gujarati', sans-serif;
        }

        .hi-text {
            font-family: 'Noto Sans Devanagari', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .desktop-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* ── Drop Zone ─────────────────────────────────────── */
        #drop-zone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #drop-zone.drag-over {
            border-color: #10b981 !important;
            background: rgba(16, 185, 129, 0.05) !important;
            transform: scale(1.02);
        }

        /* ── Image Preview ─────────────────────────────────── */
        #image-preview {
            max-width: 100%;
            height: auto;
            border-radius: 2rem;
            display: none;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
        }

        /* ── Confidence Bar ────────────────────────────────── */
        .confidence-bar-track {
            background: #f1f5f9;
            border-radius: 999px;
            height: 8px;
            overflow: hidden;
        }

        .confidence-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            transition: width 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            width: 0%;
        }

        /* ── Status Colors ─────────────────────────────────── */
        .status-online {
            color: #10b981;
        }

        .status-offline {
            color: #f97316;
        }

        @keyframes pulse-soft {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .pulse-soft {
            animation: pulse-soft 2s infinite;
        }

        /* ── Custom Animations ────────────────────────────── */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-up {
            animation: slideUp 0.6s ease-out forwards;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Header -->
    <header
        class="fixed top-0 w-full bg-white/95 backdrop-blur-3xl shadow-lg border-b-4 border-emerald-500 z-50 transition-all duration-500">
        <div class="desktop-container px-6 lg:px-12 py-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="flex items-center gap-4 group">
                    <div
                        class="w-14 h-14 lg:w-16 lg:h-16 bg-gradient-to-br from-yellow-400 via-orange-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg group-hover:scale-110 transition-all duration-300">
                        <i class="fas fa-wheat-awn"></i>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-2xl lg:text-3xl font-black text-gray-800 tracking-tight" data-lang="logo">
                            AgriCare</h1>
                        <p class="text-xs font-bold text-emerald-600 tracking-wider uppercase hidden sm:block"
                            data-lang="tagline">Gujarat's Smart Farming Platform</p>
                    </div>
                </a>

                <nav class="hidden xl:flex items-center gap-1">
                    <a href="index.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all"
                        data-lang="home">🏠 Home</a>
                    <a href="market.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all"
                        data-lang="market">📈 Market</a>
                    <a href="subsidies.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all"
                        data-lang="subsidies">💰 Subsidies</a>
                    <a href="weather.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all"
                        data-lang="weather">🌤️ Weather</a>
                    <a href="#" class="text-emerald-700 bg-emerald-50 font-bold px-6 py-3 rounded-full transition-all"
                        data-lang="sol_disease">🩺 Detection</a>
                    <a href="about.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all"
                        data-lang="contact">ℹ️ About</a>
                </nav>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex bg-gray-100 p-1 rounded-xl">
                        <button onclick="changeLang('en')"
                            class="lang-btn px-3 py-1 text-sm font-bold rounded-lg transition-all active bg-white shadow text-emerald-700">EN</button>
                        <button onclick="changeLang('gu')"
                            class="lang-btn px-3 py-1 text-sm font-bold rounded-lg text-gray-500 hover:text-emerald-700 transition-all">ગુ</button>
                        <button onclick="changeLang('hi')"
                            class="lang-btn px-3 py-1 text-sm font-bold rounded-lg text-gray-500 hover:text-emerald-700 transition-all">हिं</button>
                    </div>
                    <a href="login.php"
                        class="hidden lg:inline-flex text-emerald-700 font-bold py-3 px-6 hover:text-emerald-900 transition-all"
                        data-lang="loginBtn">Login</a>
                    <a href="register.php"
                        class="hidden lg:inline-flex bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all"
                        data-lang="registerBtn">Register</a>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-32 pb-20 desktop-container px-6 lg:px-12">
        <!-- Hero Section -->
        <div class="text-center mb-16 slide-up">
            <span
                class="inline-block bg-emerald-100 text-emerald-800 font-bold px-4 py-1 rounded-full mb-4 text-sm uppercase tracking-wider"
                data-lang="ai_powered">AI Vision Powered</span>
            <h1 class="text-4xl lg:text-6xl font-black text-gray-900 mb-6" data-lang="disease_title">Smart Disease
                Detection</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed" data-lang="disease_subtitle">Identify
                plant diseases instantly using our advanced AI model. Simply upload a photo of a leaf to get a
                professional diagnosis and treatment advice.</p>
        </div>

        <div class="grid lg:grid-cols-12 gap-10 items-start">

            <!-- Analysis Panel (Left) -->
            <div class="lg:col-span-5 space-y-8">
                <div
                    class="glass-card p-10 rounded-[3rem] shadow-xl border border-white relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-emerald-100 rounded-bl-[5rem] -mr-16 -mt-16 opacity-50">
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-2" data-lang="upload_ready">Ready to Scan?</h2>
                    <p class="text-sm text-gray-500 mb-8" data-lang="upload_instructions">Upload a clear photo of the
                        infected leaf for accurate results.</p>

                    <!-- Drop Zone -->
                    <div id="drop-zone"
                        class="border-4 border-dashed border-gray-200 rounded-[2.5rem] p-12 text-center cursor-pointer bg-white group-hover:border-emerald-300 transition-all">
                        <input type="file" id="file-input" class="hidden" accept="image/*">
                        <div class="space-y-6">
                            <div
                                class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center mx-auto text-emerald-500 group-hover:scale-110 transition-transform duration-500">
                                <i data-lucide="upload-cloud" class="w-10 h-10"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" data-lang="drag_drop">Drag & Drop Image</p>
                                <p class="text-sm text-gray-400 font-medium" data-lang="file_types">Supports: JPG, PNG,
                                    WEBP</p>
                            </div>
                        </div>
                    </div>

                    <!-- Image Preview -->
                    <div id="preview-wrapper" class="mt-8 hidden">
                        <img id="image-preview" src="" alt="Preview" class="w-full h-64 object-cover">
                        <button onclick="resetScan()"
                            class="mt-4 w-full text-sm font-bold text-red-500 hover:text-red-700 transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="trash-2" class="w-4 h-4"></i> <span data-lang="remove_image">Remove
                                Image</span>
                        </button>
                    </div>

                    <!-- Action Button -->
                    <button id="analyze-btn" disabled
                        class="w-full mt-8 bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 text-white font-bold py-5 rounded-2xl shadow-lg disabled:opacity-50 disabled:grayscale transition-all flex items-center justify-center gap-3 text-lg">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                        <span id="btn-text" data-lang="btn_analyze">Analyze Health Now</span>
                    </button>
                </div>

                <!-- Local AI Status Marker -->
                <div id="api-status" class="glass-card p-6 rounded-2xl flex items-center gap-5 border border-gray-100">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center"
                        id="status-icon-box">
                        <i data-lucide="activity" class="w-6 h-6 text-emerald-500 pulse-soft"></i>
                    </div>
                    <div>
                        <p id="status-label" class="text-xs font-black uppercase tracking-widest text-emerald-600"
                            data-lang="status_online">Local AI Model Ready</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">GPU ACCELERATED · VERSION 2.1.0</p>
                    </div>
                </div>
            </div>

            <!-- Result Panel (Right) -->
            <div class="lg:col-span-7">
                <div id="empty-state"
                    class="glass-card h-full min-h-[500px] rounded-[3rem] border-4 border-dashed border-gray-100 flex flex-col items-center justify-center text-center p-12">
                    <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mb-8">
                        <i data-lucide="scan" class="w-16 h-16"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-300" data-lang="awaiting_scan">Awaiting Prediction Data
                    </h3>
                    <p class="text-gray-400 font-medium max-w-sm mt-4 leading-relaxed" data-lang="empty_desc">Once you
                        upload and analyze an image, the detailed diagnosis, confidence level, and treatment plan will
                        appear here.</p>
                </div>

                <div id="result-panel" class="hidden slide-up space-y-8">
                    <div class="glass-card p-10 rounded-[3rem] shadow-xl border border-white">
                        <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-6">
                            <h2 class="text-3xl font-black text-gray-900 tracking-tight" data-lang="diagnosis_report">
                                Diagnosis Report</h2>
                            <div class="flex items-center gap-4">
                                <div id="confidence-pill"
                                    class="px-5 py-2 bg-emerald-50 text-emerald-700 text-sm font-black rounded-full border border-emerald-100 shadow-sm">
                                    0% CONFIDENCE
                                </div>
                            </div>
                        </div>

                        <!-- User Image Mini Preview in Result -->
                        <div class="mb-8 rounded-[2rem] overflow-hidden border-4 border-white shadow-lg h-48 w-full">
                            <img id="result-image-preview" src="" alt="Analyzed Crop"
                                class="w-full h-full object-cover">
                        </div>

                        <!-- Main Result Box -->
                        <div
                            class="bg-gradient-to-br from-emerald-50/50 to-emerald-100/30 p-8 rounded-[2rem] border border-emerald-100 mb-8 flex gap-6 items-start">
                            <div
                                class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center text-emerald-600 shrink-0">
                                <i data-lucide="stethoscope" class="w-8 h-8"></i>
                            </div>
                            <div>
                                <h3 id="disease-name" class="text-3xl font-black text-emerald-800 leading-tight">
                                    Processing...</h3>
                                <p id="plant-type"
                                    class="text-emerald-600 font-bold uppercase tracking-widest text-xs mt-1">Detecting
                                    Species...</p>
                                <p id="disease-desc" class="text-gray-600 font-medium mt-4 leading-relaxed italic">The
                                    AI is mapping the visual data against 38 known disease categories...</p>
                            </div>
                        </div>

                        <!-- Confidence Bar -->
                        <div class="mb-10">
                            <div class="flex justify-between items-end mb-3">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest"
                                    data-lang="model_confidence">AI Probability</span>
                                <span id="confidence-text" class="text-2xl font-black text-emerald-600">0%</span>
                            </div>
                            <div class="confidence-bar-track">
                                <div id="confidence-bar" class="confidence-bar-fill"></div>
                            </div>
                        </div>

                        <!-- Advice Grid -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="p-6 bg-white/50 rounded-2xl border border-gray-100">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
                                        <i data-lucide="droplet" class="w-4 h-4"></i>
                                    </div>
                                    <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest"
                                        data-lang="irrigation">Watering</h4>
                                </div>
                                <p id="irrigation-text"
                                    class="text-sm text-gray-500 font-medium leading-relaxed italic">Analysis in
                                    progress...</p>
                            </div>

                            <div class="p-6 bg-white/50 rounded-2xl border border-gray-100">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500">
                                        <i data-lucide="flask-conical" class="w-4 h-4"></i>
                                    </div>
                                    <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest"
                                        data-lang="treatment">Treatment</h4>
                                </div>
                                <p id="treatment-text" class="text-sm text-gray-500 font-medium leading-relaxed italic">
                                    Analysis in progress...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Top Classes Table -->
                    <div class="glass-card p-6 rounded-3xl border border-white shadow-lg overflow-hidden">
                        <h4
                            class="text-xs font-black text-gray-800 uppercase tracking-widest mb-6 px-2 flex items-center gap-3">
                            <i data-lucide="bar-chart-2" class="w-5 h-5 text-emerald-500"></i>
                            <span data-lang="top_predictions">Secondary Probabilities</span>
                        </h4>
                        <div id="top-predictions" class="space-y-2">
                            <!-- JS injected rows -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-20 pb-10">
        <div class="desktop-container px-6 lg:px-12 text-center lg:text-left">
            <div class="grid lg:grid-cols-4 gap-12 mb-16">
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-center lg:justify-start gap-4 mb-6">
                        <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center text-2xl"><i
                                class="fas fa-leaf"></i></div>
                        <span class="text-3xl font-black" data-lang="logo">AgriCare</span>
                    </div>
                    <p class="text-gray-400 text-lg mb-8 max-w-md mx-auto lg:mx-0" data-lang="footer-desc">Empowering
                        farmers with smart data, vision-AI technology, and building a sustainable future.</p>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-6 border-l-4 border-emerald-500 pl-4" data-lang="solutions">
                        Solutions</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="market.php" class="hover:text-emerald-400 transition-colors"
                                data-lang="market">Market Connect</a></li>
                        <li><a href="subsidies.php" class="hover:text-emerald-400 transition-colors"
                                data-lang="subsidies">Subsidy Portal</a></li>
                        <li><a href="weather.php" class="hover:text-emerald-400 transition-colors"
                                data-lang="weather">Weather Station</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-6 border-l-4 border-orange-500 pl-4" data-lang="contact">About</h4>
                    <p class="text-gray-400" data-lang="location">⚲ Gujarat, India</p>
                    <p class="text-gray-400 mt-2">✉ project@agricare.demo</p>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex justify-center text-gray-500 text-sm">
                <p data-lang="copyright">© 2026 AgriCare. Made with ❤️ for Farmers.</p>
            </div>
        </div>
    </footer>

    <script>
        const AI_HEALTH_ENDPOINT = '../backend/ai_health.php';
        const AI_PREDICT_ENDPOINT = '../backend/ai_predict.php';
        lucide.createIcons();

        // ── Translation Engine ───────────────────────────────────────
        const translations = {
            en: {
                logo: "AgriCare",
                tagline: "Gujarat's Smart Farming Platform",
                home: "🏠 Home",
                market: "📈 Market",
                subsidies: "💰 Subsidies",
                weather: "🌤️ Weather",
                contact: "ℹ️ About",
                ai_powered: "AI Vision Powered",
                disease_title: "Smart Disease Detection",
                disease_subtitle: "Identify plant diseases instantly using our advanced AI model. Simply upload a photo of a leaf to get a professional diagnosis.",
                upload_ready: "Ready to Scan?",
                upload_instructions: "Upload a clear photo derived from the infected area.",
                drag_drop: "Drag & Drop Image",
                file_types: "Supports JPG, PNG, WEBP",
                remove_image: "Remove Image",
                btn_analyze: "Analyze Health Now",
                status_online: "Local AI Model Ready",
                status_offline: "Local AI Model Offline",
                awaiting_scan: "Awaiting Scanned Data",
                empty_desc: "Diagnostic metrics will appear here once analysis is triggered.",
                diagnosis_report: "Diagnosis Report",
                model_confidence: "Model Confidence",
                irrigation: "Irrigation Advisor",
                treatment: "Standard Protocol",
                top_predictions: "Alternative Classes",
                solutions: "Solutions",
                copyright: "© 2026 AgriCare. Made with ❤️ for Farmers.",
                loginBtn: "Login",
                registerBtn: "Register",
                location: "⚲ Gujarat, India",
                'footer-desc': "Empowering farmers with smart data and vision-AI technology."
            },
            gu: {
                logo: "એગ્રીકેર",
                tagline: "ગુજરાતનું સ્માર્ટ કૃષિ પ્લેટફોર્મ",
                home: "🏠 મુખ્ય",
                market: "📈 બજાર",
                subsidies: "💰 સબસિડી",
                weather: "🌤️ હવામાન",
                contact: "ℹ️ પરિચય",
                ai_powered: "AI વિઝન સંચાલિત",
                disease_title: "સ્માર્ટ રોગ શોધ",
                disease_subtitle: "અમારા અદ્યતન AI મોડેલનો ઉપયોગ કરીને પાક રોગોને તરત જ ઓળખો. માત્ર એક ફોટો અપલોડ કરો.",
                upload_ready: "સ્કેન માટે તૈયાર?",
                upload_instructions: "ચોક્કસ પરિણામો માટે પાંદડાનો સ્પષ્ટ ફોટો અપલોડ કરો.",
                drag_drop: "ફોટો અહીં મૂકો",
                file_types: "JPG, PNG, WEBP સપોર્ટ કરે છે",
                remove_image: "ફોટો દૂર કરો",
                btn_analyze: "તપાસ શરૂ કરો",
                status_online: "AI એન્જિન ઓનલાઇન",
                status_offline: "AI એન્જિન ઓફલાઇન",
                awaiting_scan: "સ્કેન ડેટાની પ્રતીક્ષા છે",
                empty_desc: "તમે ફોટો અપલોડ કરી તપાસ શરૂ કરશો એટલે પરિણામ અહીં દેખાશે.",
                diagnosis_report: "તપાસ રિપોર્ટ",
                model_confidence: "AI વિશ્વાસ લેવલ",
                irrigation: "પિયત સલાહ",
                treatment: "સારવાર પદ્ધતિ",
                top_predictions: "અન્ય શક્યતાઓ",
                solutions: "ઉકેલો",
                copyright: "© 2026 એગ્રીકેર. ખેડૂતો માટે ❤️ થી બનાવેલ.",
                loginBtn: "લોગિન",
                registerBtn: "નોંધણી",
                location: "⚲ ગુજરાત, ભારત",
                'footer-desc': "સ્માર્ટ ડેટા અને AI ટેકનોલોજી દ્વારા ખેડૂતોને સશક્ત બનાવવું."
            },
            hi: {
                logo: "एग्रीकेयर",
                tagline: "गुजरात का स्मार्ट कृषि-मंच",
                home: "🏠 होम",
                market: "📈 बाज़ार",
                subsidies: "💰 सब्सिडी",
                weather: "🌤️ मौसम",
                contact: "ℹ️ परिचय",
                ai_powered: "AI विजन समर्थित",
                disease_title: "स्मार्ट रोग पहचान",
                disease_subtitle: "हमारे उन्नत AI मॉडल का उपयोग करके फसल रोगों की तुरंत पहचान करें। बस एक फोटो अपलोड करें।",
                upload_ready: "स्कैन के लिए तैयार?",
                upload_instructions: "सटीक परिणामों के लिए पत्ती की स्पष्ट फोटो अपलोड करें।",
                drag_drop: "फोटो यहाँ डालें",
                file_types: "JPG, PNG, WEBP समर्थित",
                remove_image: "फोटो हटाएँ",
                btn_analyze: "अभी जाँच करें",
                status_online: "AI इंजन ऑनलाइन",
                status_offline: "AI इंजन ऑफलाइन",
                awaiting_scan: "स्कैन डेटा की प्रतीक्षा",
                empty_desc: "फोटो अपलोड करने और जाँच शुरू करने के बाद परिणाम यहाँ दिखाई देंगे।",
                diagnosis_report: "जाँच रिपोर्ट",
                model_confidence: "AI विश्वास स्तर",
                irrigation: "सिंचाई सलाह",
                treatment: "उपचार पद्धति",
                top_predictions: "अन्य संभावनाएँ",
                solutions: "समाधान",
                copyright: "© 2026 एग्रीकेयर। किसानों के लिए ❤️ से बनाया गया।",
                loginBtn: "लॉगिन",
                registerBtn: "पंजीकरण",
                location: "⚲ गुजरात, भारत",
                'footer-desc': "स्मार्ट डेटा और AI तकनीक के माध्यम से किसानों का सशक्तिकरण।"
            }
        };

        function changeLang(lang) {
            localStorage.setItem('agricare_lang', lang);
            document.querySelectorAll('.lang-btn').forEach(btn => btn.classList.remove('bg-white', 'shadow', 'text-emerald-700'));
            document.querySelectorAll(`button[onclick="changeLang('${lang}')"]`).forEach(btn => btn.classList.add('bg-white', 'shadow', 'text-emerald-700'));

            document.querySelectorAll('[data-lang]').forEach(el => {
                const key = el.getAttribute('data-lang');
                if (translations[lang][key]) el.innerHTML = translations[lang][key];
            });

            const body = document.body;
            body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') body.classList.add('gu-text');
            if (lang === 'hi') body.classList.add('hi-text');
        }

        // Initialize Lang
        const initialLang = localStorage.getItem('agricare_lang') || 'en';
        changeLang(initialLang);

        // ── Logic Elements ──────────────────────────────────────────
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const previewWrap = document.getElementById('preview-wrapper');
        const previewImg = document.getElementById('image-preview');
        const analyzeBtn = document.getElementById('analyze-btn');

        // Health Checker
        async function checkApi() {
            try {
                const res = await fetch(AI_HEALTH_ENDPOINT, { signal: AbortSignal.timeout(6000) });
                if (res.ok) updateApiStatus('online');
                else updateApiStatus('offline');
            } catch { updateApiStatus('offline'); }
        }

        function updateApiStatus(state) {
            const label = document.getElementById('status-label');
            const icon = document.getElementById('status-icon-box');
            if (state === 'online') {
                label.textContent = translations[localStorage.getItem('agricare_lang') || 'en'].status_online;
                label.className = "text-xs font-black uppercase tracking-widest text-emerald-600 font-bold";
                icon.innerHTML = '<i data-lucide="check-circle" class="w-6 h-6 text-emerald-500"></i>';
            } else {
                label.textContent = translations[localStorage.getItem('agricare_lang') || 'en'].status_offline;
                label.className = "text-xs font-black uppercase tracking-widest text-orange-500 font-bold";
                icon.innerHTML = '<i data-lucide="wifi-off" class="w-6 h-6 text-orange-400"></i>';
            }
            lucide.createIcons();
        }
        setInterval(checkApi, 10000);
        checkApi();

        // Files
        dropZone.onclick = () => fileInput.click();
        fileInput.onchange = (e) => handleFiles(e.target.files);
        dropZone.ondragover = (e) => { e.preventDefault(); dropZone.classList.add('drag-over'); };
        dropZone.ondragleave = () => dropZone.classList.remove('drag-over');
        dropZone.ondrop = (e) => { e.preventDefault(); handleFiles(e.dataTransfer.files); };

        function handleFiles(files) {
            if (!files[0]) return;
            const file = files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                dropZone.classList.add('hidden');
                previewWrap.classList.remove('hidden');
                analyzeBtn.disabled = false;
            };
            reader.readAsDataURL(file);
        }

        function resetScan() {
            fileInput.value = '';
            previewImg.src = '';
            previewWrap.classList.add('hidden');
            dropZone.classList.remove('hidden');
            analyzeBtn.disabled = true;
            document.getElementById('result-panel').classList.add('hidden');
            document.getElementById('empty-state').classList.remove('hidden');
        }

        // Analysis
        analyzeBtn.onclick = async () => {
            const file = fileInput.files[0];
            if (!file) return;

            analyzeBtn.disabled = true;
            document.getElementById('btn-text').textContent = "Neural Mapping...";

            try {
                const fd = new FormData();
                fd.append('image', file);
                const res = await fetch(AI_PREDICT_ENDPOINT, { method: 'POST', body: fd });
                if (!res.ok) throw new Error("Local model prediction failed");
                const data = await res.json();
                showResults(data);
            } catch (err) {
                alert("Local AI prediction is not available. Please check the model file and Python setup in the ai folder.");
                analyzeBtn.disabled = false;
                document.getElementById('btn-text').textContent = translations[localStorage.getItem('agricare_lang') || 'en'].btn_analyze;
            }
        };

        function showResults(data) {
            document.getElementById('empty-state').classList.add('hidden');
            document.getElementById('result-panel').classList.remove('hidden');

            // Show user image in results
            document.getElementById('result-image-preview').src = previewImg.src;

            document.getElementById('disease-name').textContent = data.label;
            const plantName = data.plant || data.plant_type || 'Plant';
            document.getElementById('plant-type').textContent = plantName + " SPECIES";
            document.getElementById('disease-desc').textContent = data.info.desc;
            document.getElementById('irrigation-text').textContent = data.info.irrigation;
            document.getElementById('treatment-text').textContent = data.info.treatment;

            const conf = data.confidence.toFixed(1);
            document.getElementById('confidence-text').textContent = conf + "%";
            document.getElementById('confidence-pill').textContent = conf + "% CONFIDENCE";
            document.getElementById('confidence-bar').style.width = Math.max(10, conf) + "%";

            // Top Classes
            const topDiv = document.getElementById('top-predictions');
            topDiv.innerHTML = '';
            if (data.top3) {
                data.top3.forEach((item, i) => {
                    topDiv.innerHTML += `
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-100/50">
                            <span class="text-xs font-bold text-gray-700">${item.label}</span>
                            <span class="text-xs font-black text-emerald-600">${item.confidence.toFixed(1)}%</span>
                        </div>
                    `;
                });
            }

            analyzeBtn.disabled = false;
            document.getElementById('btn-text').textContent = translations[localStorage.getItem('agricare_lang') || 'en'].btn_analyze;
            lucide.createIcons();
        }

    </script>
</body>

</html>
