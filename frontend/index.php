<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AgriCare | Gujarat's Smart Farming Platform</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="output.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&family=Noto+Sans+Gujarati:wght@400;500;700&family=Noto+Sans+Devanagari:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        /* Custom Animations & Effects */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .serif-title {
            font-family: 'Poppins', sans-serif;
            letter-spacing: -1px;
        }

        .glow-hover:hover {
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.4);
        }

        .pulse-glow {
            animation: pulse-green 3s infinite;
        }

        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }

            70% {
                box-shadow: 0 0 0 20px rgba(16, 185, 129, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .floating-farm {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .crop-grow {
            animation: grow 1s ease-out forwards;
            transform-origin: bottom;
        }

        @keyframes grow {
            from {
                transform: scaleY(0);
                opacity: 0;
            }

            to {
                transform: scaleY(1);
                opacity: 1;
            }
        }

        @keyframes shimmer {
            0% {
                transform: translateX(100vw);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        /* Tractor Animation */
        .tractor-trail {
            animation: drive 15s linear infinite;
        }

        @keyframes drive {
            0% {
                left: -10%;
            }

            100% {
                left: 110%;
            }
        }

        .desktop-container {
            max-width: 1400px;
            margin: 0 auto;
        }


        .farm-hero {
            background:
                linear-gradient(135deg, rgba(45, 173, 53, 0.90), rgba(120, 219, 226, 0.80)),
                url('assets/images/hero-bg.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>

<body class="bg-gray-50 overflow-x-hidden">

    <div id="progressBar"></div>

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
                    <a href="#home"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all hover:underline hover:underline-offset-4 focus:underline focus:underline-offset-4 active:underline active:underline-offset-4 decoration-2"
                        data-lang="home">🏠 Home</a>
                    <a href="#features"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all hover:underline hover:underline-offset-4 focus:underline focus:underline-offset-4 active:underline active:underline-offset-4 decoration-2"
                        data-lang="features">✨ Features</a>
                    <a href="market.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all hover:underline hover:underline-offset-4 focus:underline focus:underline-offset-4 active:underline active:underline-offset-4 decoration-2"
                        data-lang="market">📈 Market</a>
                    <a href="weather.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all hover:underline hover:underline-offset-4 focus:underline focus:underline-offset-4 active:underline active:underline-offset-4 decoration-2"
                        data-lang="weather">🌤️ Weather</a>

                    <a href="about.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all hover:underline hover:underline-offset-4 focus:underline focus:underline-offset-4 active:underline active:underline-offset-4 decoration-2"
                        data-lang="contact">ℹ️ About</a>
                </nav>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex bg-gray-100 p-1 rounded-xl">
                        <button onclick="changeLang('en')"
                            class="lang-btn px-3 py-1 text-sm font-bold rounded-lg transition-all active bg-white shadow text-emerald-700"
                            data-lang-key="en">EN</button>
                        <button onclick="changeLang('gu')"
                            class="lang-btn px-3 py-1 text-sm font-bold rounded-lg text-gray-500 hover:text-emerald-700 transition-all"
                            data-lang-key="gu">ગુ</button>
                        <button onclick="changeLang('hi')"
                            class="lang-btn px-3 py-1 text-sm font-bold rounded-lg text-gray-500 hover:text-emerald-700 transition-all"
                            data-lang-key="hi">हिं</button>
                    </div>

                    <a href="login.php" data-lang="loginBtn"
                        class="hidden lg:inline-flex text-emerald-700 font-bold py-3 px-6 hover:text-emerald-900 transition-all">
                        Login
                    </a>
                    <a href="register.php"
                        class="hidden lg:inline-flex bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all"
                        data-lang="registerBtn">
                        Register
                    </a>

                    <button id="mobileMenuBtn" class="xl:hidden text-2xl text-gray-700 p-2">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobileMenuOverlay"
            class="xl:hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden transition-opacity"></div>
        <div id="mobileMenu"
            class="xl:hidden fixed right-0 top-0 h-full w-80 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50 p-6 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-black text-emerald-800">Menu</h2>
                <button onclick="closeMobileMenu()" class="text-2xl text-gray-500 hover:text-red-500"><i
                        class="fas fa-times"></i></button>
            </div>

            <div class="flex gap-2 mb-8 justify-center bg-gray-50 p-3 rounded-xl">
                <button onclick="changeLang('en')"
                    class="flex-1 py-2 font-bold rounded bg-white shadow text-emerald-600 text-center">EN</button>
                <button onclick="changeLang('gu')"
                    class="flex-1 py-2 font-bold rounded hover:bg-white text-gray-600 text-center">ગુ</button>
                <button onclick="changeLang('hi')"
                    class="flex-1 py-2 font-bold rounded hover:bg-white text-gray-600 text-center">हिं</button>
            </div>

            <nav class="space-y-4">
                <a href="#home" onclick="closeMobileMenu()"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b">🏠 Home</a>
                <a href="#features" onclick="closeMobileMenu()"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b">✨
                    Features</a>
                <a href="market.php" onclick="closeMobileMenu()"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b">📈 Market</a>
                <a href="weather.php" onclick="closeMobileMenu()"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b">🌤️ Weather</a>

                <a href="about.php" onclick="closeMobileMenu()"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b">ℹ️
                    About</a>
                <a href="login.php" data-lang="loginBtn"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b">Login</a>
                <a href="register.php"
                    class="block bg-emerald-600 text-white font-bold py-3 rounded-xl text-center mt-6 shadow-lg">Register
                    Now</a>
            </nav>
        </div>
    </header>

    <section id="home" class="farm-hero pt-32 pb-20 lg:pt-48 lg:pb-32 relative overflow-hidden">
        <div class="absolute top-[85%] left-[-10%] w-24 h-24 text-orange-500 tractor-trail z-10 opacity-50">
            <i class="fas fa-tractor text-6xl"></i>
        </div>

        <div class="desktop-container px-6 lg:px-12 relative z-20">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div class="space-y-8 text-center lg:text-left">

                    <h1 class="text-5xl lg:text-7xl font-black text-gray-900 leading-tight">
                        <span class="text-emerald-800" data-lang="heroTitle">Smart Farming</span>
                        <br>
                        <span class="text-gray-800" data-lang="heroSubtitle">Future Ready.</span>
                    </h1>

                    <p class="text-xl text-emerald-900 font-medium leading-relaxed max-w-lg mx-auto lg:mx-0"
                        data-lang="heroDesc">
                        Helping farmers make smarter decisions with real-time weather insights, market trends, and crop
                        guidance
                    </p>

                    <div class="grid grid-cols-3 gap-4 py-6 border-y border-gray-200">
                        <div>
                            <div class="text-lg lg:text-xl font-black text-emerald-900 leading-tight"
                                data-lang="stat1_title">Digital Platform</div>
                            <div class="text-sm font-bold text-purple-700" data-lang="stat1_desc">For Farmers</div>
                        </div>
                        <div>
                            <div class="text-lg lg:text-xl font-black text-emerald-900 leading-tight"
                                data-lang="stat2_title">100+ Crops</div>
                            <div class="text-sm font-bold text-purple-700" data-lang="stat2_desc">Covered</div>
                        </div>
                        <div>
                            <div class="text-lg lg:text-xl font-black text-emerald-900 leading-tight"
                                data-lang="stat3_title">Smart Insights</div>
                            <div class="text-sm font-bold text-purple-700" data-lang="stat3_desc">AI Assisted</div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="register.php"
                            class="px-8 py-4 bg-emerald-600 text-white font-bold rounded-2xl shadow-xl hover:bg-emerald-700 hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                            <i class="fas fa-rocket"></i> <span data-lang="cta1">START NOW</span>
                        </a>
                        <a href="market.php"
                            class="px-8 py-4 bg-white text-emerald-800 border-2 border-emerald-100 font-bold rounded-2xl hover:bg-emerald-50 transition-all flex items-center justify-center gap-3">
                            <i class="fas fa-chart-line"></i> <span data-lang="cta2">VIEW MARKETS</span>
                        </a>
                    </div>
                </div>

                <div class="relative floating-farm hidden lg:block">
                    <div
                        class="relative z-10 glass-card p-4 rounded-[3rem] shadow-2xl transform rotate-3 hover:rotate-0 transition-all duration-500">
                        <img src="assets/images/hero-farm.jpeg" alt="Smart Farming"
                            class="rounded-[2.5rem] w-full shadow-lg">



                    </div>
                    <div
                        class="absolute top-10 -right-10 w-64 h-64 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob">
                    </div>
                    <div
                        class="absolute -bottom-10 left-10 w-64 h-64 bg-emerald-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="market" class="bg-gray-900 border-y-4 border-emerald-500 overflow-hidden relative py-4">
        <div
            class="absolute top-0 left-0 bg-red-600 text-white px-4 py-1 font-bold text-xs z-20 uppercase tracking-widest shadow-lg transform -skew-x-12 ml-4 mt-[-4px]">
            Live Mandi
        </div>

        <div class="whitespace-nowrap overflow-hidden flex hover:[animation-play-state:paused]">
            <div id="mandiTicker" style="animation-duration: 240s;"
                class="animate-[shimmer_60s_linear_infinite] flex gap-16 text-xl font-mono font-bold text-emerald-400 hover:[animation-play-state:paused]">
                <span>Loading live market data...</span>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-gray-50 desktop-container px-6 lg:px-12">
        <div class="text-center mb-20">
            <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-6" data-lang="tools">Powerful Tools</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto" data-lang="tools-desc">Technology designed specifically
                for the needs of Indian agriculture.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <a href="disease_detection.php"
                class="bg-white p-10 rounded-[2rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                <div
                    class="w-20 h-20 bg-emerald-100 rounded-2xl flex items-center justify-center text-4xl text-emerald-600 mb-8 group-hover:scale-110 transition-transform">
                    <i class="fas fa-microscope"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4" data-lang="prediction">Crop Disease Detector</h3>
                <p class="text-gray-600 leading-relaxed" data-lang="prediction-desc">Identify crop diseases at an early
                    stage using image-based analysis and smart pattern detection</p>
            </a>

            <a href="weather.php"
                class="bg-white p-10 rounded-[2rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                <div
                    class="w-20 h-20 bg-orange-100 rounded-2xl flex items-center justify-center text-4xl text-orange-600 mb-8 group-hover:scale-110 transition-transform">
                    <i class="fas fa-cloud-sun-rain"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4" data-lang="climate">Weather Alerts</h3>
                <p class="text-gray-600 leading-relaxed" data-lang="climate-desc">Hyper-local weather forecasts with
                    automatic irrigation advisory for your village.</p>
            </a>

            <a href="market.php"
                class="bg-white p-10 rounded-[2rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                <div
                    class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center text-4xl text-blue-600 mb-8 group-hover:scale-110 transition-transform">
                    <i class="fas fa-store"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4" data-lang="market_intel">Mandi Prices</h3>
                <p class="text-gray-600 leading-relaxed" data-lang="market-desc">Direct APMC connection to find the best
                    selling price for your crops near you.</p>
            </a>
        </div>
    </section>

    <footer id="contact" class="bg-gray-900 text-white pt-20 pb-10">
        <div class="desktop-container px-6 lg:px-12">
            <div class="grid lg:grid-cols-4 gap-12 mb-16">
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center text-2xl">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <span class="text-3xl font-black" data-lang="logo">AgriCare</span>
                    </div>
                    <p class="text-gray-400 text-lg mb-8 max-w-md" data-lang="footer-desc">
                        Empowering farmers with data, connecting markets with transparency, and building a sustainable
                        future.
                    </p>
                    <div class="flex gap-4">
                        <a href="#"
                            class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-emerald-500 transition-colors"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#"
                            class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-emerald-500 transition-colors"><i
                                class="fab fa-facebook"></i></a>
                        <a href="#"
                            class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-emerald-500 transition-colors"><i
                                class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-6 border-l-4 border-emerald-500 pl-4" data-lang="solutions">
                        Solutions</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="#" class="hover:text-emerald-400 transition-colors" data-lang="sol_advisory">Crop
                                Advisory</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors" data-lang="sol_disease">Crop
                                Disease Identification</a></li>
                        <li><a href="market.php" class="hover:text-emerald-400 transition-colors"
                                data-lang="sol_market">Market
                                Connect</a></li>
                        <li><a href="weather.php" class="hover:text-emerald-400 transition-colors" data-lang="sol_weather">Weather
                                Station</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-6 border-l-4 border-orange-500 pl-4" data-lang="contact">ℹ️ About
                    </h4>
                    <ul class="space-y-4 text-gray-400">
                        <li data-lang="location">⚲ Location: Gujarat, India</li>
                        <li data-lang="email">✉ Contact: project@agricare.demo</li>
                        <li data-lang="phone">🕻 Phone: Available on request</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex justify-center items-center text-gray-500 text-sm">
                <p data-lang="copyright">© 2026 AgriCare. Made with ❤️ for Farmers.</p>
            </div>
        </div>
    </footer>

    <!-- Load Multi-Language External Translations Script here instead of local definition -->
    <script src="js/translations.js"></script>
    <script>
        // 2. Language Switcher Logic
        function changeLang(lang) {
            localStorage.setItem('agricare_lang', lang);
            // Remove active classes from all buttons
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow', 'text-emerald-700');
                btn.classList.add('text-gray-500');
            });

            // Add active class to selected lang buttons (desktop & mobile)
            document.querySelectorAll(`button[onclick="changeLang('${lang}')"]`).forEach(btn => {
                btn.classList.add('bg-white', 'shadow', 'text-emerald-700');
                btn.classList.remove('text-gray-500');
            });

            // Update Text Content
            document.querySelectorAll('[data-lang]').forEach(element => {
                const key = element.getAttribute('data-lang');
                if (translations[lang] && translations[lang][key]) {
                    element.innerHTML = translations[lang][key];
                }
            });

            // Change Font Family based on Lang
            const body = document.body;
            body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') body.classList.add('gu-text');
            if (lang === 'hi') body.classList.add('hi-text');

            // Re-render the Live Mandi Ticker so crop/district translations switch immediately
            if (document.getElementById('mandiTicker')) {
                fetchMandiTicker();
            }
        }

        // 3. Mobile Menu Logic
        const mobileMenu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        const menuBtn = document.getElementById('mobileMenuBtn');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
            overlay.classList.add('opacity-100'); // simple fade in effect if added
        });

        function closeMobileMenu() {
            mobileMenu.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        }

        overlay.addEventListener('click', closeMobileMenu);

        // 4. Scroll Progress Bar
        window.onscroll = function() {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            const bar = document.getElementById("progressBar");
            bar.style.width = scrolled + "%";
            bar.style.height = "5px";
            bar.style.background = "linear-gradient(90deg, #10b981, #f59e0b)";
            bar.style.position = "fixed";
            bar.style.top = "0";
            bar.style.left = "0";
            bar.style.zIndex = "100";
        };

        // 5. Number Counter Animation
        const observeStats = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = document.querySelectorAll('.stats-counter');
                    counters.forEach(counter => {
                        const target = +counter.getAttribute('data-count');
                        const speed = 100; // Lower is slower

                        const updateCount = () => {
                            const count = +counter.innerText.replace(/,/g, '').replace('+', '').replace('%', '');
                            const inc = target / speed;

                            if (count < target) {
                                let displayVal = Math.ceil(count + inc);
                                if (target > 1000) displayVal = displayVal.toLocaleString();
                                counter.innerText = displayVal;
                                setTimeout(updateCount, 20);
                            } else {
                                counter.innerText = target.toLocaleString() + (counter.getAttribute('data-lang') === 'accuracy' ? '%' : '+');
                            }
                        };
                        updateCount();
                    });
                    observeStats.disconnect(); // Run once
                }
            });
        });

        const statsSection = document.querySelector('.stats-counter');
        if (statsSection) observeStats.observe(statsSection.parentElement);

        // --- Fetch Live Mandi Prices for Ticker ---
        async function fetchMandiTicker() {
            try {
                const res = await fetch("../backend/get_market.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        districts: [],
                        markets: [],
                        commodities: []
                    })
                });

                if (!res.ok) throw new Error("Backend error");

                const payload = await res.json();
                const ticker = document.getElementById('mandiTicker');

                if (payload.success && payload.rows && payload.rows.length > 0) {
                    // Take top 15 results to scroll
                    const displayData = payload.rows.slice(0, 15);
                    const lang = localStorage.getItem('agricare_lang') || 'en';

                    // Generate items
                    const content = displayData.map((r, i) => {
                        const getEmoji = (c) => {
                            c = c.toLowerCase();
                            if (c.includes('wheat')) return '🌾';
                            if (c.includes('rice') || c.includes('paddy')) return '🍚';
                            if (c.includes('maize') || c.includes('corn')) return '🌽';
                            if (c.includes('onion')) return '🧅';
                            if (c.includes('potato')) return '🥔';
                            if (c.includes('tomato')) return '🍅';
                            if (c.includes('cotton')) return '☁️';
                            if (c.includes('peanut') || c.includes('groundnut')) return '🥜';
                            if (c.includes('garlic')) return '🧄';
                            if (c.includes('lemon')) return '🍋';
                            if (c.includes('apple')) return '🍎';
                            if (c.includes('banana')) return '🍌';
                            if (c.includes('mango')) return '🥭';
                            if (c.includes('chilli') || c.includes('chilly')) return '🌶️';
                            if (c.includes('carrot')) return '🥕';
                            if (c.includes('cabbage')) return '🥬';
                            if (c.includes('brinjal') || c.includes('eggplant')) return '🍆';
                            if (c.includes('gram') || c.includes('pulse') || c.includes('bean') || c.includes('moong') || c.includes('tuvar') || c.includes('urad')) return '🫘';
                            if (c.includes('mustard') || c.includes('seed') || c.includes('castor') || c.includes('sesame')) return '🪴';
                            if (c.includes('guar')) return '🎋';
                            return '🌱';
                        };
                        const emoji = getEmoji(r.commodity);

                        const escapeHtml = (v) => String(v).replace(/[&<>"']/g, c => ({
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#39;'
                        })[c]);
                        let commodityName = escapeHtml(getLocalizedMarketName(r.commodity, lang));
                        let districtName = escapeHtml(getLocalizedMarketName(r.district, lang));
                        let modal = escapeHtml(String(r.modal));

                        return `<span>${emoji} ${commodityName} (${districtName}): ₹${modal} <span class="text-green-400">● ${lang === 'gu' ? 'લાઇવ' : (lang === 'hi' ? 'लाइव' : 'Live')}</span></span>`;
                    }).join('');

                    // Duplicate content to avoid gap during infinite scroll
                    ticker.innerHTML = content.repeat(10);
                } else {
                    const lang = localStorage.getItem('agricare_lang') || 'en';
                    let noDataMsg = 'No live market data available at the moment.';
                    if (lang === 'gu') noDataMsg = 'હાલમાં કોઈ લાઇવ બજાર ડેટા ઉપલબ્ધ નથી.';
                    if (lang === 'hi') noDataMsg = 'फिलहाल कोई लाइव मार्केट डेटा उपलब्ध नहीं है।';
                    ticker.innerHTML = `<span>${noDataMsg}</span>`;
                }
            } catch (err) {
                console.error("Mandi ticker error:", err);
                const ticker = document.getElementById('mandiTicker');
                const lang = localStorage.getItem('agricare_lang') || 'en';
                let errorMsg = 'System offline. Could not fetch live prices.';
                if (lang === 'gu') errorMsg = 'સિસ્ટમ ઑફલાઇન છે. લાઇવ કિંમતો લાવી શકાઈ નથી.';
                if (lang === 'hi') errorMsg = 'सिस्टम ऑफ़लाइन है. लाइव कीमतें प्राप्त नहीं की जा सकीं।';
                if (ticker) ticker.innerHTML = `<span>${errorMsg}</span>`;
            }
        }

        // Initialize fetching on load and automatically refresh every 5 minutes
        document.addEventListener('DOMContentLoaded', () => {
            fetchMandiTicker();
            setInterval(fetchMandiTicker, 5 * 60 * 1000);

            const urlParams = new URLSearchParams(window.location.search);
            const initialLang = urlParams.get('lang') || localStorage.getItem('agricare_lang') || 'en';
            changeLang(initialLang);
        });
    </script>
</body>

</html>