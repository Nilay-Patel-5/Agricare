<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AgriCare | Live Market Data</title>
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

        .desktop-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* No Data Placeholder Styles */
        .no-data-icon {
            width: 80px;
            height: 80px;
            background: #f8fafc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid #f1f5f9;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
            color: #94a3b8;
            font-size: 2rem;
            margin-bottom: 1.5rem;
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
                    <a href="index.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all hover:underline hover:underline-offset-4 focus:underline focus:underline-offset-4 active:underline active:underline-offset-4 decoration-2"
                        data-lang="home">🏠 Home</a>
                    <a href="index.php#features"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all hover:underline hover:underline-offset-4 focus:underline focus:underline-offset-4 active:underline active:underline-offset-4 decoration-2"
                        data-lang="features">✨ Features</a>
                    <a href="market.php"
                        class="text-emerald-700 bg-emerald-50 font-bold px-6 py-3 rounded-full transition-all decoration-2"
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
                <h2 class="text-2xl font-black text-emerald-800" data-lang="menu_title">Menu</h2>
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
                <a href="index.php" onclick="closeMobileMenu()"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b"
                    data-lang="home">🏠 Home</a>
                <a href="index.php#features" onclick="closeMobileMenu()"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b"
                    data-lang="features">✨
                    Features</a>
                <a href="market.php" onclick="closeMobileMenu()"
                    class="block text-lg font-semibold text-emerald-600 py-2 border-b bg-emerald-50 rounded pl-2"
                    data-lang="market">📈
                    Market</a>
                <a href="weather.php" onclick="closeMobileMenu()"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b"
                    data-lang="weather">🌤️
                    Weather</a>
                <a href="about.php" onclick="closeMobileMenu()"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b"
                    data-lang="contact">ℹ️
                    About</a>
                <a href="login.php" data-lang="loginBtn"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b">Login</a>
                <a href="register.php" data-lang="registerBtn"
                    class="block bg-emerald-600 text-white font-bold py-3 rounded-xl text-center mt-6 shadow-lg">Register
                    Now</a>
            </nav>
        </div>
    </header>

    <main class="pt-32 pb-20 lg:pt-48 lg:pb-32 desktop-container px-6 lg:px-12 min-h-screen">
        <!-- Page Title & Search -->
        <div class="flex flex-col items-center text-center mb-12 gap-6">
            <div>
                <h1 class="text-4xl lg:text-5xl font-black text-gray-900 mb-2" data-lang="mandi_title">
                    <span class="text-emerald-600">Live</span> Mandi Prices
                </h1>
                <p class="text-gray-600 text-lg mb-4" data-lang="mandi_subtitle">Real-time agricultural market rates from
                    across Gujarat</p>
            </div>
        </div>

        <!-- Market Selection Filters -->
        <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2" data-lang="select_criteria">
                <i class="fas fa-filter text-emerald-600"></i> Select Market Criteria
            </h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 pt-2">
                <!-- District -->
                <div class="relative">
                    <label class="absolute -top-2 left-3 bg-white px-1 text-xs font-semibold text-slate-500 z-10"
                        data-lang="district_label">District</label>
                    <div class="relative">
                        <button onclick="toggleDistrictDropdown()" id="districtDistplay"
                            class="w-full bg-slate-50/50 border border-slate-300 text-slate-700 py-2.5 px-4 rounded-lg text-left font-medium focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all flex justify-between items-center truncate">
                            <span class="truncate block w-[90%]" data-lang="all_districts">All Districts</span>
                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform"
                                id="districtArrow"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="districtOptionsWrapper"
                            class="hidden absolute top-full left-0 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl z-50 overflow-hidden">
                            <div class="p-2 border-b border-gray-100">
                                <div class="relative">
                                    <i
                                        class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" id="districtSearch" placeholder="Search..."
                                        class="w-full pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 text-gray-600"
                                        style="padding-left: 2.5rem;" onkeyup="filterDistricts(this.value)">
                                </div>
                            </div>
                            <div id="districtOptions" style="max-height: 185px; overflow-y: auto;"
                                class="p-1 overscroll-contain">
                                <!-- Checkboxes injected via JS -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Market -->
                <div class="relative">
                    <label class="absolute -top-2 left-3 bg-white px-1 text-xs font-semibold text-slate-500 z-10"
                        data-lang="market_label">Market</label>
                    <div class="relative">
                        <button onclick="toggleMarketDropdown()" id="marketDisplay"
                            class="w-full bg-slate-50/50 border border-slate-300 text-slate-700 py-2.5 px-4 rounded-lg text-left font-medium focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all flex justify-between items-center truncate">
                            <span class="truncate block w-[90%]" data-lang="all_markets">All Markets</span>
                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform"
                                id="marketArrow"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="marketOptionsWrapper"
                            class="hidden absolute top-full left-0 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl z-50 overflow-hidden">
                            <div class="p-2 border-b border-gray-100">
                                <div class="relative">
                                    <i
                                        class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" id="marketSearch" placeholder="Search..."
                                        class="w-full pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 text-gray-600"
                                        style="padding-left: 2.5rem;" onkeyup="filterMarkets(this.value)">
                                </div>
                            </div>
                            <div id="marketOptions" style="max-height: 185px; overflow-y: auto;"
                                class="p-1 overscroll-contain">
                                <!-- Checkboxes injected via JS -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commodity Group -->
                <div class="relative">
                    <label class="absolute -top-2 left-3 bg-white px-1 text-xs font-semibold text-slate-500 z-10"
                        data-lang="group_label">Commodity Group</label>
                    <div class="relative">
                        <button id="commodityGroupDisplay" onclick="toggleCommodityGroupDropdown()"
                            class="w-full bg-slate-50/50 border border-slate-300 text-slate-700 py-3 px-4 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-medium text-left flex justify-between items-center">
                            <span class="truncate block" data-lang="all_groups">All Groups</span>
                            <i id="commodityGroupArrow"
                                class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200"></i>
                        </button>

                        <div id="commodityGroupOptionsWrapper"
                            class="hidden absolute top-full left-0 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl z-50 overflow-hidden">
                            <div class="p-2 border-b border-gray-100">
                                <div class="relative">
                                    <i
                                        class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" id="commodityGroupSearch" placeholder="Search..."
                                        class="w-full pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 text-gray-600"
                                        style="padding-left: 2.5rem;" onkeyup="filterCommodityGroups(this.value)">
                                </div>
                            </div>
                            <div id="commodityGroupOptions" style="max-height: 185px; overflow-y: auto;"
                                class="p-1 overscroll-contain">
                                <!-- Checkboxes injected via JS -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commodity -->
                <div class="relative">
                    <label class="absolute -top-2 left-3 bg-white px-1 text-xs font-semibold text-slate-500 z-10"
                        data-lang="commodity_label">Commodity</label>
                    <div class="relative">
                        <button id="commodityDisplay" onclick="toggleCommodityDropdown()"
                            class="w-full bg-slate-50/50 border border-slate-300 text-slate-700 py-3 px-4 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-medium text-left flex justify-between items-center">
                            <span class="truncate block" data-lang="all_commodities">All Commodities</span>
                            <i id="commodityArrow"
                                class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200"></i>
                        </button>

                        <div id="commodityOptionsWrapper"
                            class="hidden absolute top-full left-0 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl z-50 overflow-hidden">
                            <div class="p-2 border-b border-gray-100">
                                <div class="relative">
                                    <i
                                        class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" id="commoditySearch" placeholder="Search..."
                                        class="w-full pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 text-gray-600"
                                        style="padding-left: 2.5rem;" onkeyup="filterCommodities(this.value)">
                                </div>
                            </div>
                            <div id="commodityOptions" style="max-height: 185px; overflow-y: auto;"
                                class="p-1 overscroll-contain">
                                <!-- Checkboxes injected via JS -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button id="searchBtn"
                    class="bg-gradient-to-r from-emerald-500 to-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center gap-2">
                    <i class="fas fa-search"></i> <span data-lang="get_prices">Get Prices</span>
                </button>
            </div>
        </div>

        <!-- Arrival Date Display -->
        <div class="flex justify-end mb-4">
            <div id="arrivalDateDisplay" class="hidden inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 px-4 py-2 rounded-full text-sm font-bold border border-emerald-200 shadow-sm">
                <i class="far fa-calendar-alt"></i> <span data-lang="data_as_of">Data as of:</span> <span id="arrivalDateText">--</span>
            </div>
        </div>

        <!-- Result Placeholder -->
        <div id="resultsArea">
            <div id="noDataPlaceholder"
                class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl shadow-sm border border-gray-100">
                <div class="no-data-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800" data-lang="no_data_available">No Data Available</h3>
                <p class="text-gray-500 mt-2" data-lang="no_data_subtitle">Select criteria and click search to view
                    prices</p>
            </div>
            <div id="tableContainer" class="hidden">
                <!-- Table will be injected here -->
            </div>
        </div>
    </main>


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

    <script src="js/translations.js"></script>
    <script>
        let currentMarketData = null;
        document.getElementById("searchBtn").addEventListener("click", async function() {
            const btn = this;
            const originalContent = btn.innerHTML;

            // Show loading state
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

            const filters = {
                districts: selectedDistricts.length === districts.length ? [] : selectedDistricts,
                markets: selectedMarkets.length === currentAvailableMarkets.length ? [] : selectedMarkets,
                commodities: selectedCommodities.length === currentAvailableCommodities.length ? [] : selectedCommodities
            };

            try {
                const res = await fetch("../backend/get_market.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(filters)
                });

                const payload = await res.json().catch(() => null);
                if (!res.ok) {
                    const message = payload?.error || payload?.hint || "Backend error";
                    throw new Error(message);
                }

                const data = (payload && payload.success && payload.rows) ? payload.rows : (Array.isArray(payload) ? payload : []);
                showResults(data);
            } catch (error) {
                console.error("Fetch error:", error);
                const container = document.getElementById("tableContainer");
                const placeholder = document.getElementById("noDataPlaceholder");
                const errorMessage = escapeHtml(error?.message || "Could not connect to the live market service.");

                placeholder.classList.add("hidden");
                container.classList.remove("hidden");
                container.innerHTML = `
                    <div class="bg-red-50 border-l-4 border-red-500 p-8 rounded-2xl shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-red-800">Connection Failed</h3>
                                <p class="text-red-600">${errorMessage}</p>
                            </div>
                        </div>
                    </div>`;
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        });

        function escapeHtml(value) {
            return String(value).replace(/[&<>"']/g, function(char) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                })[char];
            });
        }


        let currentPage = 1;
        const PAGE_SIZE = 100;

        function showResults(rows) {
            currentMarketData = rows;
            currentPage = 1;
            renderPage();
        }

        function renderPage() {
            const rows = currentMarketData;
            const container = document.getElementById("tableContainer");
            const placeholder = document.getElementById("noDataPlaceholder");

            if (!rows || rows.length === 0) {
                placeholder.classList.remove("hidden");
                container.classList.add("hidden");
                const dateDisplay = document.getElementById("arrivalDateDisplay");
                if (dateDisplay) dateDisplay.classList.add("hidden");
                return;
            }

            placeholder.classList.add("hidden");
            container.classList.remove("hidden");

            // Display arrival date
            const dateDisplay = document.getElementById("arrivalDateDisplay");
            const dateText = document.getElementById("arrivalDateText");
            if (dateDisplay && dateText && rows[0].arrival_date) {
                dateText.innerText = rows[0].arrival_date;
                dateDisplay.classList.remove("hidden");
            }

            const totalPages = Math.ceil(rows.length / PAGE_SIZE);
            const start = (currentPage - 1) * PAGE_SIZE;
            const end = Math.min(start + PAGE_SIZE, rows.length);
            const pageRows = rows.slice(start, end);

            let html = `
    <div class="flex flex-wrap justify-between items-center mb-3 gap-3">
        <span class="text-sm text-gray-600 font-medium">
            ${translations[currentLang]['showing_records'] || 'Showing'} <strong>${start + 1}–${end}</strong> ${translations[currentLang]['of_records'] || 'of'} <strong>${rows.length}</strong> ${translations[currentLang]['records'] || 'records'}
        </span>
        <div class="flex items-center gap-2">
            <button onclick="changePage(-1)" id="prevPageBtn"
                class="px-4 py-1.5 rounded-lg border border-emerald-300 text-emerald-700 font-semibold text-sm hover:bg-emerald-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                ${currentPage === 1 ? 'disabled' : ''}>
                ← ${translations[currentLang]['prev_page'] || 'Prev'}
            </button>
            <span class="text-sm font-semibold text-gray-700 px-2">${translations[currentLang]['page'] || 'Page'} ${currentPage} / ${totalPages}</span>
            <button onclick="changePage(1)" id="nextPageBtn"
                class="px-4 py-1.5 rounded-lg border border-emerald-300 text-emerald-700 font-semibold text-sm hover:bg-emerald-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                ${currentPage === totalPages ? 'disabled' : ''}>
                ${translations[currentLang]['next_page'] || 'Next'} →
            </button>
        </div>
    </div>
    <div class="overflow-x-auto bg-white rounded-2xl shadow">
    <table class="w-full text-sm text-left">
    <thead class="bg-emerald-600 text-white">
        <tr>
            <th class="p-3">${translations[currentLang]['district_label'] || 'District'}</th>
            <th class="p-3">${translations[currentLang]['market_label'] || 'Market'}</th>
            <th class="p-3">${translations[currentLang]['commodity_label'] || 'Commodity'}</th>
            <th class="p-3">${translations[currentLang]['min_price'] || 'Min Price (₹/Quintal)'}</th>
            <th class="p-3">${translations[currentLang]['max_price'] || 'Max Price (₹/Quintal)'}</th>
            <th class="p-3">${translations[currentLang]['modal_price'] || 'Modal Price (₹/Quintal)'}</th>
        </tr>
    </thead>
    <tbody>`;

            pageRows.forEach(r => {
                const trDistrict = escapeHtml(getLocalizedMarketName(r.district, currentLang));
                const trMarket = escapeHtml(getLocalizedMarketName(r.market, currentLang));
                const trCommodity = escapeHtml(getLocalizedMarketName(r.commodity, currentLang));
                const trMin = escapeHtml(String(r.min));
                const trMax = escapeHtml(String(r.max));
                const trModal = escapeHtml(String(r.modal));

                html += `
        <tr class="border-b hover:bg-gray-50">
            <td class="p-3">${trDistrict}</td>
            <td class="p-3">${trMarket}</td>
            <td class="p-3">${trCommodity}</td>
            <td class="p-3">₹${trMin}</td>
            <td class="p-3">₹${trMax}</td>
            <td class="p-3 font-bold text-emerald-700">₹${trModal}</td>
        </tr>`;
            });

            html += `</tbody></table></div>`;

            // Bottom pagination
            if (totalPages > 1) {
                html += `
    <div class="flex flex-wrap justify-between items-center mt-3 gap-3">
        <span class="text-sm text-gray-600 font-medium">
            ${translations[currentLang]['showing_records'] || 'Showing'} <strong>${start + 1}–${end}</strong> ${translations[currentLang]['of_records'] || 'of'} <strong>${rows.length}</strong> ${translations[currentLang]['records'] || 'records'}
        </span>
        <div class="flex items-center gap-2">
            <button onclick="changePage(-1)"
                class="px-4 py-1.5 rounded-lg border border-emerald-300 text-emerald-700 font-semibold text-sm hover:bg-emerald-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                ${currentPage === 1 ? 'disabled' : ''}>
                ← ${translations[currentLang]['prev_page'] || 'Prev'}
            </button>
            <span class="text-sm font-semibold text-gray-700 px-2">${translations[currentLang]['page'] || 'Page'} ${currentPage} / ${totalPages}</span>
            <button onclick="changePage(1)"
                class="px-4 py-1.5 rounded-lg border border-emerald-300 text-emerald-700 font-semibold text-sm hover:bg-emerald-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                ${currentPage === totalPages ? 'disabled' : ''}>
                ${translations[currentLang]['next_page'] || 'Next'} →
            </button>
        </div>
    </div>`;
            }

            container.innerHTML = html;

            // Scroll to top of table on page change
            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function changePage(delta) {
            if (!currentMarketData) return;
            const totalPages = Math.ceil(currentMarketData.length / PAGE_SIZE);
            currentPage = Math.max(1, Math.min(currentPage + delta, totalPages));
            renderPage();
        }
        // Copying minimal needed script for menu & language


        let currentLang = 'en';

        function changeLang(lang) {
            localStorage.setItem('agricare_lang', lang);
            currentLang = lang;
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow', 'text-emerald-700');
                btn.classList.add('text-gray-500');
            });
            document.querySelectorAll(`button[onclick="changeLang('${lang}')"]`).forEach(btn => {
                btn.classList.add('bg-white', 'shadow', 'text-emerald-700');
                btn.classList.remove('text-gray-500');
            });
            document.querySelectorAll('[data-lang]').forEach(element => {
                const key = element.getAttribute('data-lang');
                if (translations[lang] && translations[lang][key]) {
                    element.innerHTML = translations[lang][key];
                }
            });

            // Update placeholders
            document.querySelectorAll('input[placeholder]').forEach(input => {
                if (translations[lang] && translations[lang]['search_placeholder']) {
                    input.placeholder = translations[lang]['search_placeholder'];
                }
            });

            // Change Font Family
            const body = document.body;
            body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') body.classList.add('gu-text');
            if (lang === 'hi') body.classList.add('hi-text');

            // Refresh displays to reflect language change
            renderDistricts();
            updateDistrictDisplay();
            renderMarkets();
            updateMarketDisplay();
            renderCommodityGroups();
            updateCommodityGroupDisplay();
            renderCommodities();
            updateCommodityDisplay();

            // Re-render the market table instantly if data exists
            if (currentMarketData) {
                showResults(currentMarketData);
            }
        }

        function getLocalizedMarketName(market, lang) {
            if (!market) return '';

            // Fast exact match for full strings (like commodities or exact market names)
            if (translations[lang] && translations[lang][market]) {
                return translations[lang][market];
            }

            let localized = market;
            const suffixes = {
                en: {
                    ' APMC': ' APMC',
                    ' Vegetable': ' Vegetable',
                    ' Vegetable': ' vegetable',
                    ' Veg Yard': ' Vegetable Yard',
                    ' VegetableMarket': ' Vegetable Market',
                    ' VegetableMarket': ' Vegetable Market',
                    ' VegetableYard': ' Vegetable Yard',
                    ' Vegetablemarket': ' Vegetable Market',
                    ' Market': ' Market',
                    ' Dist.': ' District',
                    ' dist.': ' district',
                    ' Station Road': ' Station Road',
                    ' station road': ' station road'
                },
                gu: {
                    ' APMC': ' એપીએમસી',
                    ' Vegetable': ' શાકભાજી',
                    ' Vegetable': ' શાકભાજી',
                    ' Veg Yard': ' શાકભાજી માર્કેટ',
                    ' Vegetable Market': ' શાકભાજી માર્કેટ',
                    ' Vegetable Market': ' શાકભાજી માર્કેટ',
                    ' VegetableYard': ' શાકભાજી માર્કેટ',
                    ' Veg': ' શાકભાજી',
                    ' veg': ' શાકભાજી',
                    ' Fruit Market': ' ફળ બજાર',
                    ' Grain Market': ' અનાજ બજાર',
                    ' Sub yard': ' પેટા યાર્ડ',
                    ' Yard': ' યાર્ડ',
                    ' Vegetablemarket': ' શાકભાજી માર્કેટ',
                    ' Market': ' માર્કેટ',
                    ' Dist.': ' જિલ્લો',
                    ' dist.': ' જિલ્લો',
                    ' station road': ' સ્ટેશન રોડ',
                    ' Station Road': ' સ્ટેશન રોડ'
                },
                hi: {
                    ' APMC': ' एपीएमसी',
                    ' Vegetable Market': ' सब्जी मंडी',
                    ' Vegetable Yard': ' सब्जी मंडी',
                    ' Fruit Market': ' फल बाजार',
                    ' Grain Market': ' अनाज बाजार',
                    ' Sub Yard': ' उप मंडी',
                    ' Yard': ' मंडी',
                    ' Market': ' बाजार',
                    ' Dist.': ' जिला',
                    ' dist.': ' जिला',
                    ' station road': ' स्टेशन रोड',
                    ' station road': ' स्टेशन रोड'
                }
            };

            // Smart translation for APMC names
            // Keys to exclude from market name replacement to prevent unintended UI word translation (like emojis)
            const excludeKeys = ['home', 'features', 'market', 'weather', 'contact', 'logo', 'tagline', 'solutions'];

            // Sort keys by length descending to match longest substrings first
            const sortedKeys = Object.keys(translations[lang])
                .filter(k => k.length > 2 && !excludeKeys.includes(k))
                .sort((a, b) => b.length - a.length);

            for (const eng of sortedKeys) {
                // Use a proper regex to handle non-word boundaries (like brackets in strings)
                const regex = new RegExp(`(^|\\W)(${escapeRegExp(eng)})(?=\\W|$)`, 'gi');

                if (regex.test(localized)) {
                    localized = localized.replace(regex, (match, prefix) => prefix + translations[lang][eng]);
                }
            }


            // Replace suffixes
            if (suffixes[lang]) {
                for (const [eng, loc] of Object.entries(suffixes[lang])) {
                    localized = localized.replace(eng, loc);
                }
            }

            return localized;
        }

        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        const mobileMenu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        setTimeout(() => {
            const loader = document.getElementById('loader');
            if (loader) loader.classList.add('hidden');
        }, 500);

        const urlParams = new URLSearchParams(window.location.search);
        const initialLang = urlParams.get('lang') || localStorage.getItem('agricare_lang') || 'en';

        const menuBtn = document.getElementById('mobileMenuBtn');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
            overlay.classList.add('opacity-100');
        });

        function closeMobileMenu() {
            mobileMenu.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        }

        overlay.addEventListener('click', closeMobileMenu);

        // Scroll Progress Bar
        window.onscroll = function() {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            const bar = document.getElementById("progressBar");
            if (bar) {
                bar.style.width = scrolled + "%";
                bar.style.height = "5px";
                bar.style.background = "linear-gradient(90deg, #10b981, #f59e0b)";
                bar.style.position = "fixed";
                bar.style.top = "0";
                bar.style.left = "0";
                bar.style.zIndex = "100";
            }
        };
        // 6. Custom District Dropdown Logic
        const districts = [
            "Ahmedabad", "Amreli", "Anand", "Aravalli", "Banaskantha", "Bharuch",
            "Bhavnagar", "Botad", "Chhota Udaipur", "Dahod", "Dang", "Devbhumi Dwarka",
            "Gandhinagar", "Gir Somnath", "Jamnagar", "Junagarh", "Kachchh", "Kheda",
            "Mahisagar", "Mehsana", "Morbi", "Narmada", "Navsari", "Panchmahals",
            "Patan", "Porbandar", "Rajkot", "Sabarkantha", "Surat", "Surendranagar",
            "Tapi", "The Dangs", "Vadodara(Baroda)", "Valsad"
        ];

        const districtMarkets = {
            "Ahmedabad": [
                "Ahmedabad APMC", "Ahmedabad(Chimanbhai Patal Market, Vasana) APMC",
                "Ahmedabad(Flower Market, Jamalpur) APMC", "Ahmedabad(Fruit Market, Naroda) APMC",
                "Ahmedabad(Manekchowk) APMC", "Ahmedabad(Rajnagar Sub Yard) APMC",
                "Balasinor APMC", "Barvala APMC", "Bavla APMC", "Becharaji APMC", "Dhandhuka APMC",
                "Bavla APMC", "Becharaji APMC", "Dhandhuka APMC",
                "Dhandkuka(Dholera) APMC", "Dholka APMC", "Dholka(Koth) APMC",
                "Dholka(Market Yard) APMC", "Kadi APMC", "Kadi(Kadi Cotton Yard) APMC",
                "Kapadanj(Moti Jaher) APMC", "Kapadvanj APMC", "Kathlal APMC",
                "Kheda APMC", "Kheda(Nayaka) APMC", "Mandal APMC", "Matar APMC",
                "Matar(Limbasi) APMC", "Mehmadabad APMC", "Mehmadabad(Vegetable Market) APMC",
                "Mehsana APMC", "Mehsana(Jornang) APMC", "Mehsana(Vegetable Market) APMC",
                "Nadiad APMC", "Nadiyad(Chaklasi) APMC", "Nadiyad(Piplag) APMC",
                "Rampura APMC", "Ranpur APMC", "Sanad APMC", "Thasara APMC",
                "Thasara(Dokar) APMC", "Unava APMC", "Unjha APMC", "Vadnagar APMC",
                "Vadnagar(Kheralu) APMC", "Vijapur APMC", "Vijapur(Gojjariya) APMC",
                "Vijapur(Kukarvada) APMC", "Vijapur(Ladol) APMC", "Vijapur(Vegetable Market) APMC",
                "Viramgam APMC", "Virpur APMC", "Visnagar APMC"
            ],
            "Amreli": [
                "Amreli APMC", "Babra APMC", "Bagasara APMC", "Damnagar APMC",
                "Dhari APMC", "Dhari(Chalala) APMC", "Khambha APMC", "Lathi APMC",
                "Rajula(Dungar) APMC", "Rajula APMC", "Savarkundla APMC", "Timbi APMC"
            ],
            "Anand": [
                "Anand APMC", "Anand(Vegetable Yard) APMC", "Anklav APMC", "Borsad APMC",
                "Borsad(Asodar) APMC", "Borsad(Vegetable Yard) APMC", "Khambhat APMC",
                "Khambhat(Grain Market) APMC", "Khambhat(Vegetable Yard) APMC",
                "Petlad APMC", "Petlad(Vegetable Yard) APMC", "Sojitra APMC",
                "Tarapur APMC", "Umreth APMC"
            ],
            "Banaskantha": [
                "Amirgadh APMC", "Banaskantha APMC", "Bhabhar APMC", "Danta APMC",
                "Deesa APMC", "Deesa(Bhildi) APMC", "Deesa(Vegetable Yard) APMC",
                "Dhanera APMC", "Dhanera(Samarwada) APMC", "Dhanera(Vegetable Yard) APMC",
                "Diyodar APMC", "Lakhani APMC", "Palanpur APMC", "Palanpur(Vegetable Yard) APMC",
                "Panthawada APMC", "Thara APMC", "Thara(Shihori) APMC", "Tharad APMC",
                "Tharad(Rah) APMC", "Vadgam APMC", "Vav APMC"
            ],
            "Bharuch": [
                "Amod APMC", "Ankleshwar APMC", "Baruch Vagara APMC", "Bharuch APMC",
                "Hasot APMC", "Hasot(Elav) APMC", "Jambusar APMC", "Jambusar(Kaavi) APMC",
                "Jhagadiya APMC", "Valia APMC", "Valia(Nethrang) APMC"
            ],
            "Bhavnagar": [
                "Bhavnagar APMC", "Gariyadar APMC", "Mahuva(Station Road) APMC",
                "Palitana APMC", "Taleja APMC"
            ],
            "Botad": [
                "Botad APMC", "Botad(Bhabarkot) APMC", "Botad(Haddad) APMC",
                "Gadada APMC"
            ],
            "Chhota Udaipur": [
                "Bodeliu APMC", "Chachack APMC", "Hadad APMC", "Jetpur-Pavi APMC",
                "Kalediya APMC", "Modasar APMC", "Panvad APMC", "Tejgarh APMC",
                "Thalkala APMC"
            ],
            "Dahod": [
                "Dahod APMC", "Dahod(Garbada) APMC", "Dahod(Himalaya) APMC",
                "Dahod(Jesavada) APMC", "Dahod(Vegetable Market) APMC", "Davgadbaria(Piplod) APMC",
                "Devgadhbaria APMC", "Jhalod APMC", "Limkheda APMC", "Zalod(Sanjeli) APMC",
                "Zalod APMC"
            ],
            "Dang": [
                "Vaghai APMC"
            ],
            "Devbhumi Dwarka": [
                "Bhanvad APMC", "Jam Khambalia APMC"
            ],
            "Gandhinagar": [
                "Dehgam APMC", "Dehgam(Rekhiyal) APMC",
                "Kalol APMC", "Kalol(Vegetable Market) APMC",
                "Mansa APMC", "Mansa(Vegetable Yard) APMC",
                "Radheja APMC", "Randheja(Chiloda) APMC",
                "Randheja(Vegetable Market) APMC"
            ],
            "Gir Somnath": [
                "Kodinar APMC", "Talalagir APMC", "Una APMC", "Veraval APMC"
            ],
            "Jamnagar": [
                "Bhatiya APMC", "Dhrol APMC", "Jam Jodhpur APMC", "Jamnagar APMC",
                "Jodiya APMC", "Kalawad APMC", "Lalpur APMC"
            ],
            "Junagarh": [
                "Bhesan APMC", "Junagadh APMC", "Junagadh(Vegetable Yard) APMC",
                "Keshod APMC", "Kodinar(Dollasa) APMC", "Malia Hatina APMC",
                "Manavdar APMC", "Mangrol APMC", "Talala APMC", "Vanthli APMC",
                "Visavadar APMC"
            ],
            "Kachchh": [
                "Abdasa APMC", "Anjar APMC", "Anjar(Vegetable Yard) APMC",
                "Bachau APMC", "Bhuj APMC", "Mandvi(Kachchh) APMC", "Mundra APMC",
                "Nakhatrana APMC", "Rapar APMC"
            ],
            "Kheda": [
                "Balasinor APMC", "Kapadanj(Moti Jaher) APMC", "Kapadvanj APMC",
                "Kathlal APMC", "Kheda APMC", "Kheda(Nayaka) APMC", "Matar APMC",
                "Matar(Limbasi) APMC", "Mehmadabad APMC", "Mehmadabad(Veg.Market) APMC",
                "Nadiad APMC", "Nadiyad(Chaklasi) APMC", "Nadiyad(Piplag) APMC",
                "Thasara APMC", "Thasara(Dokar) APMC", "Virpur APMC"
            ],
            "Mehsana": [
                "Becharaji APMC", "Kadi APMC", "Kadi(Kadi Cotton Yard) APMC",
                "Mehsana APMC", "Mehsana(Jornang) APMC", "Mehsana( Vegetable Yard) APMC",
                "Unava APMC", "Unjha APMC", "Vadnagar APMC", "Vadnagar(Kheralu) APMC",
                "Vijapur APMC", "Vijapur(Gojjariya) APMC", "Vijapur(Kukarvada) APMC",
                "Vijapur(Ladol) APMC", "Vijapur(Vegetable Yard) APMC", "Visnagar APMC"
            ],
            "Morbi": [
                "Halvad APMC", "Morbi APMC", "Vankaner APMC", "Vankaner(Sub Yard) APMC"
            ],
            "Narmada": [
                "Dediyapada APMC", "Garudeshwal APMC", "Rajpipla APMC", "Selemba APMC",
                "Selemba(Navgam Javli) APMC", "Tilakwada APMC"
            ],
            "Navsari": [
                "Bilimora APMC", "Bilimora(Gandevi) APMC", "Chikhali APMC",
                "Navsari APMC", "Vansda APMC", "Vansda(Limzar) APMC"
            ],
            "Panchmahals": [
                "Derol APMC", "Derol(Adadara) APMC", "Derol(Vejalpur) APMC",
                "Godhra APMC", "Godhra Kakanpur APMC", "Godhra(Timbaroad) APMC",
                "Gogamba APMC", "Gogamba(Similiya) APMC", "Halol APMC", "Khanpur APMC",
                "Lunavada APMC", "Lunavada(Kobamba) APMC", "Morva Hafad APMC",
                "Santrampur APMC", "Santrampur(Fathaepura) APMC", "Shehra APMC"
            ],
            "Patan": [
                "Chansama APMC", "Harij APMC", "Patan APMC", "Patan(Vegetable Yard) APMC",
                "Radhanpur APMC", "Sami APMC", "Siddhpur APMC", "Varahi APMC"
            ],
            "Porbandar": [
                "Kutiyana APMC", "Porbandar APMC"
            ],
            "Rajkot": [
                "Dhoraji APMC", "Gondal APMC", "Gondal(Vegetable Market) APMC",
                "Jamkandorna APMC", "Jasdan APMC", "Jasdan(Vichhiya) APMC",
                "Jetpur APMC", "Rajkot APMC", "Rajkot(Sub Yard) APMC",
                "Upleta APMC"
            ],
            "Sabarkantha": [
                "Khedbrahma APMC", "Bayad APMC", "Bayad(Demai) APMC",
                "Bayad(Sadamba) APMC", "Bhiloda APMC", "Dhansura APMC",
                "Himatnagar APMC", "Himatnagar(Gambhoi) APMC",
                "Himatnagar(Vegetable Market) APMC", "Idar APMC", "Idar(Jadar) APMC",
                "Khedbrahma(Lambadia) APMC", "Khedbrahma(Posina) APMC", "Malpur APMC",
                "Meghraj APMC", "Meghraj(Radlavada) APMC", "Modasa APMC",
                "Modasa(Tintoi) APMC", "Prantij APMC", "Prantij(Salala) APMC",
                "Talod APMC", "Talod(Harsol) APMC", "Vadali APMC",
                "Vijaynagar(Kundlakap) APMC"
            ],
            "Surat": [
                "Bardoli APMC", "Bardoli(Kadod) APMC", "Bardoli(Madhi) APMC",
                "Kosamba APMC", "Kosamba(Vankal) APMC", "Kosamba(Zangvav) APMC",
                "Mahuva APMC", "Mahuva(Anaval) APMC", "Mandvi APMC", "Mandvi(Amba Paradi) APMC",
                "Mandvi(Surat) APMC", "Surat APMC"
            ],
            "Tapi": [
                "Nizar APMC", "Nizar(Kukarmuda) APMC", "Nizar(Pumkitalov) APMC",
                "Songadh APMC", "Songadh(Badarpada) APMC", "Songadh(Umrada) APMC",
                "Uchhal APMC", "Uchhal(Karod) APMC",
                "Valod APMC", "Valod(Buhari) APMC", "Vyara(Paati) APMC", "Vyara APMC"
            ],
            "Surendranagar": [
                "Chotila(Vegetable Yard) APMC", "Chotila APMC",
                "Dasada Patadi APMC", "Dhrangadhra APMC", "Lakhtar APMC",
                "Limdi APMC", "Muli APMC", "Sayala APMC", "Vadhvan APMC"
            ],
            "The Dangs": [
                "Ahwa-Dang APMC"
            ],
            "Vadodara(Baroda)": [
                "Chhota Udaipur APMC", "Dabhoi(Kayavarohan) APMC", "Dabhoi APMC",
                "Karjan APMC", "Kawant APMC", "Nasvadi APMC", "Padra APMC",
                "Savli APMC", "Savli(Desar) APMC", "Savli(Samlaya) APMC",
                "Shinor APMC", "Vadodara APMC", "Vadodara(Navapura) APMC",
                "Vadodara(Sayajipura) APMC", "Vagodiya APMC"
            ],
            "Valsad": [
                "Chikli(Khorgam) APMC", "Dharampur(Vegetable Yard) APMC", "Pardi APMC",
                "Pardi(Motavagachiya) APMC", "Pardi(Rohinee) APMC", "Pardi(Ugvada) APMC",
                "Pardi(Vapi) APMC", "Valsad APMC", "Valsad(Dungari) APMC"
            ]
        };

        const districtWrapper = document.getElementById('districtOptionsWrapper');
        const districtOptionsContainer = document.getElementById('districtOptions');
        const districtDisplay = document.getElementById('districtDistplay');
        const districtArrow = document.getElementById('districtArrow');
        let selectedDistricts = [...districts];

        // Initialize Options
        function renderDistricts(filterText = '') {
            districtOptionsContainer.innerHTML = '';
            const filtered = districts.filter(d => {
                const searchLower = filterText.toLowerCase();
                const locVal = (translations[currentLang][d] || d).toLowerCase();
                return d.toLowerCase().includes(searchLower) || locVal.includes(searchLower);
            });
            const isLimitReached = selectedDistricts.length >= 5;

            // Add 'All Districts' option at the top if no search filter
            if (filterText === '') {
                const allSelected = selectedDistricts.length === districts.length;
                const div = document.createElement('div');
                div.className = "flex items-center p-1.5 rounded transition-colors hover:bg-gray-50 cursor-pointer border-b border-gray-100 mb-1";
                div.innerHTML = `
                    <input type="checkbox" ${allSelected ? 'checked' : ''} 
                        class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 cursor-pointer">
                    <span class="ml-2 font-bold text-sm text-emerald-700">${translations[currentLang].all_districts}</span>
                `;
                div.onclick = (e) => {
                    e.stopPropagation();
                    const checkbox = div.querySelector('input');
                    checkbox.checked = !checkbox.checked;
                    toggleAllDistricts(checkbox.checked);
                };
                div.querySelector('input').onclick = (e) => {
                    e.stopPropagation();
                    toggleAllDistricts(e.target.checked);
                };
                districtOptionsContainer.appendChild(div);
            }

            if (filtered.length === 0 && filterText !== '') {
                districtOptionsContainer.innerHTML = `<div class="p-2 text-sm text-gray-400 text-center">${translations[currentLang].no_districts_found}</div>`;
                return;
            }

            filtered.forEach(district => {
                const isSelected = selectedDistricts.includes(district);
                const isAllSelected = selectedDistricts.length === districts.length;
                const isDisabled = (!isSelected && isLimitReached) || (isSelected && isAllSelected);

                const div = document.createElement('div');
                div.className = `flex items-center p-1.5 rounded transition-colors ${isDisabled ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer'}`;

                div.innerHTML = `
                    <input type="checkbox" value="${district}" 
                        class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 district-checkbox ${isDisabled ? 'cursor-not-allowed' : 'cursor-pointer'}" 
                        ${isSelected ? 'checked' : ''} 
                        ${isDisabled ? 'disabled' : ''}>
                    <span class="ml-2 font-medium text-sm ${isDisabled ? 'text-gray-400' : 'text-gray-700'}">${translations[currentLang][district] || district}</span>
                `;

                if (!isDisabled) {
                    div.onclick = (e) => {
                        e.stopPropagation(); // Prevent closing dropdown
                        if (e.target.tagName !== 'INPUT') {
                            const checkbox = div.querySelector('input');
                            if (!checkbox.disabled) {
                                checkbox.checked = !checkbox.checked;
                                handleDistrictSelection(checkbox);
                            }
                        }
                    };
                    div.querySelector('input').onclick = (e) => {
                        e.stopPropagation(); // Prevent closing dropdown
                        handleDistrictSelection(e.target);
                    };
                }

                districtOptionsContainer.appendChild(div);
            });
        }

        function toggleAllDistricts(checked) {
            if (checked) {
                selectedDistricts = [...districts];
            } else {
                selectedDistricts = [];
            }
            renderDistricts(document.getElementById('districtSearch').value);
            updateDistrictDisplay();
            updateMarketOptions();
        }

        // Initial render
        renderDistricts();

        function filterDistricts(text) {
            renderDistricts(text);
        }

        function handleDistrictSelection(checkbox) {
            const val = checkbox.value;

            if (checkbox.checked) {
                if (selectedDistricts.length >= 5) {
                    checkbox.checked = false;
                    return;
                }
                selectedDistricts.push(val);
            } else {
                selectedDistricts = selectedDistricts.filter(d => d !== val);
            }
            // Re-render to apply disabled styles to others if limit reached
            renderDistricts(document.getElementById('districtSearch').value);
            updateDistrictDisplay();

            // Keep focus on search input if open
            if (!districtWrapper.classList.contains('hidden')) {
                const searchInput = document.getElementById('districtSearch');
                searchInput.focus();
            }
            updateMarketOptions();
        }

        // Market Dropdown Logic
        const marketWrapper = document.getElementById('marketOptionsWrapper');
        const marketOptionsContainer = document.getElementById('marketOptions');
        const marketDisplay = document.getElementById('marketDisplay');
        const marketArrow = document.getElementById('marketArrow');
        let selectedMarkets = [];
        let currentAvailableMarkets = [];

        function renderMarkets(filterText = '') {
            marketOptionsContainer.innerHTML = '';
            const filtered = currentAvailableMarkets.filter(m => {
                const searchLower = filterText.toLowerCase();
                const locVal = getLocalizedMarketName(m, currentLang).toLowerCase();
                return m.toLowerCase().includes(searchLower) || locVal.includes(searchLower);
            });
            const isAllDistrictsSelected = selectedDistricts.length === districts.length;
            const isLimitReached = selectedMarkets.length >= 5 || isAllDistrictsSelected;

            // Add 'All Markets' option at the top if no search filter and NOT all districts selected
            if (filterText === '' && currentAvailableMarkets.length > 0) {
                const allSelected = selectedMarkets.length === currentAvailableMarkets.length;
                const isDisabled = isAllDistrictsSelected;

                const div = document.createElement('div');
                div.className = `flex items-center p-1.5 rounded transition-colors ${isDisabled ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer'} border-b border-gray-100 mb-1`;
                div.innerHTML = `
                    <input type="checkbox" ${allSelected ? 'checked' : ''} ${isDisabled ? 'disabled' : ''}
                        class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 ${isDisabled ? 'cursor-not-allowed' : 'cursor-pointer'}">
                    <span class="ml-2 font-bold text-sm ${isDisabled ? 'text-gray-400' : 'text-emerald-700'}">${translations[currentLang].all_markets}</span>
                `;

                if (!isDisabled) {
                    div.onclick = (e) => {
                        e.stopPropagation();
                        const checkbox = div.querySelector('input');
                        checkbox.checked = !checkbox.checked;
                        toggleAllMarkets(checkbox.checked);
                    };
                    div.querySelector('input').onclick = (e) => {
                        e.stopPropagation();
                        toggleAllMarkets(e.target.checked);
                    };
                }
                marketOptionsContainer.appendChild(div);
            }

            if (filtered.length === 0 && (filterText !== '' || currentAvailableMarkets.length === 0)) {
                marketOptionsContainer.innerHTML = `<div class="p-2 text-sm text-gray-400 text-center">${currentAvailableMarkets.length === 0 ? translations[currentLang].select_district_first : translations[currentLang].no_markets_found}</div>`;
                return;
            }

            filtered.forEach(market => {
                const isSelected = selectedMarkets.includes(market);
                const isAllSelected = selectedMarkets.length === currentAvailableMarkets.length && currentAvailableMarkets.length > 0;
                const isDisabled = (!isSelected && isLimitReached) || (isSelected && isAllSelected);

                const div = document.createElement('div');
                div.className = `flex items-center p-1.5 rounded transition-colors ${isDisabled ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer'}`;

                div.innerHTML = `
                    <input type="checkbox" value="${market}" 
                        class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 market-checkbox ${isDisabled ? 'cursor-not-allowed' : 'cursor-pointer'}" 
                        ${isSelected ? 'checked' : ''} 
                        ${isDisabled ? 'disabled' : ''}>
                    <span class="ml-2 font-medium text-sm ${isDisabled ? 'text-gray-400' : 'text-gray-700'}">${getLocalizedMarketName(market, currentLang)}</span>
                `;

                if (!isDisabled) {
                    div.onclick = (e) => {
                        e.stopPropagation();
                        if (e.target.tagName !== 'INPUT') {
                            const checkbox = div.querySelector('input');
                            if (!checkbox.disabled) {
                                checkbox.checked = !checkbox.checked;
                                handleMarketSelection(checkbox);
                            }
                        }
                    };
                    div.querySelector('input').onclick = (e) => {
                        e.stopPropagation();
                        handleMarketSelection(e.target);
                    };
                }

                marketOptionsContainer.appendChild(div);
            });
        }

        function filterMarkets(text) {
            renderMarkets(text);
        }

        function toggleAllMarkets(checked) {
            if (checked) {
                selectedMarkets = [...currentAvailableMarkets];
            } else {
                selectedMarkets = [];
            }
            renderMarkets(document.getElementById('marketSearch').value);
            updateMarketDisplay();
        }

        function handleMarketSelection(checkbox) {
            const val = checkbox.value;

            if (checkbox.checked) {
                if (selectedMarkets.length >= 5) {
                    checkbox.checked = false;
                    return;
                }
                selectedMarkets.push(val);
            } else {
                selectedMarkets = selectedMarkets.filter(m => m !== val);
            }
            renderMarkets(document.getElementById('marketSearch').value);
            updateMarketDisplay();

            if (!marketWrapper.classList.contains('hidden')) {
                document.getElementById('marketSearch').focus();
            }
        }

        function updateMarketDisplay() {
            const span = marketDisplay.querySelector('span');
            const marketFieldContainer = marketDisplay.closest('.relative').parentElement;
            const isAllDistrictsSelected = selectedDistricts.length === districts.length;

            if (isAllDistrictsSelected) {
                marketDisplay.classList.add('opacity-40', 'cursor-not-allowed', 'pointer-events-none');
                if (marketFieldContainer) marketFieldContainer.classList.add('opacity-50');
                // Ensure all markets are selected when All Districts chosen
                selectedMarkets = [...currentAvailableMarkets];
                span.textContent = translations[currentLang].all_markets;
                span.classList.remove('text-emerald-700');
            } else {
                marketDisplay.classList.remove('opacity-40', 'cursor-not-allowed', 'pointer-events-none');
                if (marketFieldContainer) marketFieldContainer.classList.remove('opacity-50');
                if (selectedMarkets.length === 0 || (currentAvailableMarkets.length > 0 && selectedMarkets.length === currentAvailableMarkets.length)) {
                    span.textContent = translations[currentLang].all_markets;
                    span.classList.remove('text-emerald-700');
                } else {
                    span.textContent = selectedMarkets.map(m => getLocalizedMarketName(m, currentLang)).join(', ');
                    span.classList.add('text-emerald-700');
                }
            }
        }



        function updateDistrictDisplay() {
            const span = districtDisplay.querySelector('span');
            if (selectedDistricts.length === 0 || selectedDistricts.length === districts.length) {
                span.textContent = translations[currentLang].all_districts;
                span.classList.remove('text-emerald-700');
            } else {
                span.textContent = selectedDistricts.map(d => translations[currentLang][d] || d).join(', ');
                span.classList.add('text-emerald-700');
            }
        }

        // Commodity Group Logic
        const commodityGroupWrapper = document.getElementById('commodityGroupOptionsWrapper');
        const commodityGroupOptionsContainer = document.getElementById('commodityGroupOptions');
        const commodityGroupDisplay = document.getElementById('commodityGroupDisplay');
        const commodityGroupArrow = document.getElementById('commodityGroupArrow');
        let selectedCommodityGroups = ["Cereals", "Fibre Crops", "Oil Seeds", "Pulses", "Vegetables", "Fruits", "Spices", "Commercial Crops", "Plantation Crops", "Others"];

        const commodityGroups = [
            "Cereals", "Fibre Crops", "Oil Seeds", "Pulses", "Vegetables", "Fruits", "Spices", "Commercial Crops", "Plantation Crops", "Others"
        ];

        function renderCommodityGroups(filterText = '') {
            commodityGroupOptionsContainer.innerHTML = '';
            const filtered = commodityGroups.filter(g => {
                const searchLower = filterText.toLowerCase();
                const locVal = (translations[currentLang][g] || g).toLowerCase();
                return g.toLowerCase().includes(searchLower) || locVal.includes(searchLower);
            });
            const isLimitReached = selectedCommodityGroups.length >= 10;

            // Add 'All Groups' option at the top if no search filter
            if (filterText === '') {
                const allSelected = selectedCommodityGroups.length === commodityGroups.length;
                const div = document.createElement('div');
                div.className = "flex items-center p-1.5 rounded transition-colors hover:bg-gray-50 cursor-pointer border-b border-gray-100 mb-1";
                div.innerHTML = `
                    <input type="checkbox" ${allSelected ? 'checked' : ''} 
                        class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 cursor-pointer">
                    <span class="ml-2 font-bold text-sm text-emerald-700">${translations[currentLang].all_groups}</span>
                `;
                div.onclick = (e) => {
                    e.stopPropagation();
                    const checkbox = div.querySelector('input');
                    checkbox.checked = !checkbox.checked;
                    toggleAllCommodityGroups(checkbox.checked);
                };
                div.querySelector('input').onclick = (e) => {
                    e.stopPropagation();
                    toggleAllCommodityGroups(e.target.checked);
                };
                commodityGroupOptionsContainer.appendChild(div);
            }

            if (filtered.length === 0 && filterText !== '') {
                commodityGroupOptionsContainer.innerHTML = `<div class="p-2 text-sm text-gray-400 text-center">${translations[currentLang].no_groups_found}</div>`;
                return;
            }

            filtered.forEach(group => {
                const isSelected = selectedCommodityGroups.includes(group);
                const isAllSelected = selectedCommodityGroups.length === commodityGroups.length;
                const isDisabled = (!isSelected && isLimitReached) || (isSelected && isAllSelected);

                const div = document.createElement('div');
                div.className = `flex items-center p-1.5 rounded transition-colors ${isDisabled ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer'}`;

                div.innerHTML = `
                    <input type="checkbox" value="${group}" 
                        class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 group-checkbox ${isDisabled ? 'cursor-not-allowed' : 'cursor-pointer'}" 
                        ${isSelected ? 'checked' : ''} 
                        ${isDisabled ? 'disabled' : ''}>
                    <span class="ml-2 font-medium text-sm ${isDisabled ? 'text-gray-400' : 'text-gray-700'}">${translations[currentLang][group] || group}</span>
                `;

                if (!isDisabled) {
                    div.onclick = (e) => {
                        e.stopPropagation();
                        if (e.target.tagName !== 'INPUT') {
                            const checkbox = div.querySelector('input');
                            if (!checkbox.disabled) {
                                checkbox.checked = !checkbox.checked;
                                handleCommodityGroupSelection(checkbox);
                            }
                        }
                    };
                    div.querySelector('input').onclick = (e) => {
                        e.stopPropagation();
                        handleCommodityGroupSelection(e.target);
                    };
                }

                commodityGroupOptionsContainer.appendChild(div);
            });
        }

        function toggleAllCommodityGroups(checked) {
            if (checked) {
                // Since limit is 10 and there are 10 groups, we can select all
                selectedCommodityGroups = [...commodityGroups];
            } else {
                selectedCommodityGroups = [];
            }
            renderCommodityGroups(document.getElementById('commodityGroupSearch').value);
            updateCommodityGroupDisplay();
            updateCommodityOptions();
        }

        function filterCommodityGroups(text) {
            renderCommodityGroups(text);
        }

        // Commodity Dropdown Logic
        const commodityWrapper = document.getElementById('commodityOptionsWrapper');
        const commodityOptionsContainer = document.getElementById('commodityOptions');
        const commodityDisplay = document.getElementById('commodityDisplay');
        const commodityArrow = document.getElementById('commodityArrow');

        const commodityGroupCommodities = {
            "Cereals": [
                "Bajra(Pearl Millet/Cumbu)", "Bajra(Pearl Millet)", "Barley(Jau)", "Jowar(Sorghum)",
                "Maize", "Paddy(Common)", "Paddy(Dhan)(Common)", "Paddy(Dhan)(Basmati)", "Ragi(Finger Millet)", "Ragi (Finger Millet)", "Wheat"
            ],
            "Fibre Crops": ["Cotton", "Jute"],
            "Oil Seeds": ["Castor Seed", "Groundnut", "Groundnut(Split)", "Linseed", "Mustard", "Niger Seed(Ramtil)", "Niger Seed", "Safflower", "Sesamum(Sesame,Gingelly,Til)", "Sesame(Til)", "Soyabean", "Sunflower", "Sunflower Seed", "Toria"],
            "Pulses": [
                "Arhar(Tur/Red Gram)(Whole)", "Bengal Gram(Gram)(Whole)", "Bengal Gram", "Big Gram",
                "Black Gram(Urd Beans)(Whole)", "Black Gram (Urad)", "Gram", "Green Gram(Moong)(Whole)", "Green Gram (Moong)",
                "Kabuli Chana(Chickpeas-White)", "Lentil(Masur)(Whole)", "Lentil (Masur)", "Peas(Dry)", "Red Gram", "Red Gram (Tur/Arhar)"
            ],
            "Vegetables": [
                "Banana - Green", "Beetroot", "Bhindi(Ladies Finger)", "Bitter gourd", "Bitter Gourd",
                "Bottle gourd", "Bottle Gourd", "Brinjal", "Cabbage", "Capsicum", "Carrot", "Cauliflower",
                "Cluster beans", "Coriander(Leaves)", "Cowpea(Vegetable)", "Cucumbar(Kheera)", "Cucumber",
                "Drumstick", "Elephant Yam(Suran)/Amorphophallus", "French Beans(Frasbean)",
                "Ginger(Green)", "Ginger", "Green Chilli", "Indian Beans(Seam)", "Lady Finger (Bhindi)", "Lemon",
                "Little gourd(Kundru)", "Long Melon(Kakri)", "Onion", "Onion Green",
                "Pea Pod/Pea Cod", "Pegeon Pea(Arhar Fali)", "Pointed gourd(Parval)",
                "Potato", "Pumpkin", "Raddish", "Radish", "Rat Tail Radish(Mogari)", "Ridgeguard(Tori)", "Ridge Gourd",
                "Spinach", "Sponge gourd", "Surat Beans(Papadi)", "Sweet Potato",
                "Tinda", "Tomato", "Yam(Ratalu)"
            ],
            "Fruits": ["Apple", "Banana", "Grapes", "Guava", "Mango", "Mango(Raw-Ripe)", "Mousambi(Sweet Lime)", "Orange", "Papaya", "Papaya(Raw)", "Pineapple", "Pomegranate", "Water Melon"],
            "Spices": ["Ajwan", "Black Pepper", "Coriander", "Coriander(Leaves)", "Corriander seed", "Cumin Seed (Jeera)", "Cummin Seed(Jeera)", "Dry Chilli", "Dry Chillies", "Fenugreek (Methi)", "Garlic", "Methi Seeds", "Soanf", "Turmeric", "Turmeric(raw)"],
            "Commercial Crops": ["Cotton", "Jute", "Sugarcane", "Tobacco"],
            "Plantation Crops": ["Arecanut", "Coconut"],
            "Others": ["Banana", "Chikoos(Sapota)", "Cotton", "Guar", "Guar Seed(Cluster Beans Seed)", "Isabgul(Psyllium)", "Kinnow", "Mango(Raw-Ripe)", "Papaya", "Papaya(Raw)", "Sugarcane"]
        };

        let currentAvailableCommodities = [];
        let commoditiesToShowInitial = new Set();
        Object.values(commodityGroupCommodities).forEach(group => {
            group.forEach(c => commoditiesToShowInitial.add(c));
        });
        let selectedCommodities = Array.from(commoditiesToShowInitial).sort();

        function renderCommodities(filterText = '') {
            commodityOptionsContainer.innerHTML = '';
            const filtered = currentAvailableCommodities.filter(c => {
                const searchLower = filterText.toLowerCase();
                const locVal = (translations[currentLang][c] || c).toLowerCase();
                return c.toLowerCase().includes(searchLower) || locVal.includes(searchLower);
            });
            const isLimitReached = false; // No limit for commodities

            // Add 'All Commodities' option at the top if no search filter
            if (filterText === '' && currentAvailableCommodities.length > 0) {
                const allSelected = selectedCommodities.length === currentAvailableCommodities.length;
                const div = document.createElement('div');
                div.className = "flex items-center p-1.5 rounded transition-colors hover:bg-gray-50 cursor-pointer border-b border-gray-100 mb-1";
                div.innerHTML = `
                    <input type="checkbox" ${allSelected ? 'checked' : ''} 
                        class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 cursor-pointer">
                    <span class="ml-2 font-bold text-sm text-emerald-700">${translations[currentLang].all_commodities}</span>
                `;
                div.onclick = (e) => {
                    e.stopPropagation();
                    const checkbox = div.querySelector('input');
                    checkbox.checked = !checkbox.checked;
                    toggleAllCommodities(checkbox.checked);
                };
                div.querySelector('input').onclick = (e) => {
                    e.stopPropagation();
                    toggleAllCommodities(e.target.checked);
                };
                commodityOptionsContainer.appendChild(div);
            }

            if (filtered.length === 0 && filterText !== '') {
                commodityOptionsContainer.innerHTML = `<div class="p-2 text-sm text-gray-400 text-center">${translations[currentLang].no_commodities_found}</div>`;
                return;
            }

            filtered.forEach(commodity => {
                const isSelected = selectedCommodities.includes(commodity);
                const isAllSelected = selectedCommodities.length === currentAvailableCommodities.length && currentAvailableCommodities.length > 0;
                const isDisabled = (!isSelected && isLimitReached) || (isSelected && isAllSelected);

                const div = document.createElement('div');
                div.className = `flex items-center p-1.5 rounded transition-colors ${isDisabled ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer'}`;

                div.innerHTML = `
                    <input type="checkbox" value="${commodity}" 
                        class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 commodity-checkbox ${isDisabled ? 'cursor-not-allowed' : 'cursor-pointer'}" 
                        ${isSelected ? 'checked' : ''} 
                        ${isDisabled ? 'disabled' : ''}>
                    <span class="ml-2 font-medium text-sm ${isDisabled ? 'text-gray-400' : 'text-gray-700'}">${translations[currentLang][commodity] || commodity}</span>
                `;

                if (!isDisabled) {
                    div.onclick = (e) => {
                        e.stopPropagation();
                        if (e.target.tagName !== 'INPUT') {
                            const checkbox = div.querySelector('input');
                            if (!checkbox.disabled) {
                                checkbox.checked = !checkbox.checked;
                                handleCommoditySelection(checkbox);
                            }
                        }
                    };
                    div.querySelector('input').onclick = (e) => {
                        e.stopPropagation();
                        handleCommoditySelection(e.target);
                    };
                }

                commodityOptionsContainer.appendChild(div);
            });
        }

        function toggleAllCommodities(checked) {
            if (checked) {
                selectedCommodities = [...currentAvailableCommodities];
            } else {
                selectedCommodities = [];
            }
            renderCommodities(document.getElementById('commoditySearch').value);
            updateCommodityDisplay();
        }

        function filterCommodities(text) {
            renderCommodities(text);
        }

        function handleCommoditySelection(checkbox) {
            const val = checkbox.value;

            if (checkbox.checked) {
                selectedCommodities.push(val);
            } else {
                selectedCommodities = selectedCommodities.filter(c => c !== val);
            }
            renderCommodities(document.getElementById('commoditySearch').value);
            updateCommodityDisplay();

            if (!commodityWrapper.classList.contains('hidden')) {
                document.getElementById('commoditySearch').focus();
            }
        }

        function updateCommodityDisplay() {
            const span = commodityDisplay.querySelector('span');
            if (selectedCommodities.length === 0 || (currentAvailableCommodities.length > 0 && selectedCommodities.length === currentAvailableCommodities.length)) {
                span.textContent = translations[currentLang].all_commodities;
                span.classList.remove('text-emerald-700');
            } else {
                span.textContent = selectedCommodities.map(c => translations[currentLang][c] || c).join(', ');
                span.classList.add('text-emerald-700');
            }
        }

        function updateMarketOptions() {
            let marketsToShow = new Set();
            const districtsToIterate = selectedDistricts.length > 0 ? selectedDistricts : districts;

            districtsToIterate.forEach(district => {
                if (districtMarkets[district]) {
                    districtMarkets[district].forEach(m => marketsToShow.add(m));
                }
            });
            currentAvailableMarkets = Array.from(marketsToShow).sort();
            selectedMarkets = selectedMarkets.filter(m => currentAvailableMarkets.includes(m));
            renderMarkets();
            updateMarketDisplay();
        }

        function updateCommodityOptions() {
            let commoditiesToShow = new Set();
            const groupsToIterate = selectedCommodityGroups.length > 0 ? selectedCommodityGroups : commodityGroups;

            groupsToIterate.forEach(group => {
                if (commodityGroupCommodities[group]) {
                    commodityGroupCommodities[group].forEach(c => commoditiesToShow.add(c));
                }
            });
            currentAvailableCommodities = Array.from(commoditiesToShow).sort();

            selectedCommodities = selectedCommodities.filter(c => currentAvailableCommodities.includes(c));

            renderCommodities();
            updateCommodityDisplay();
        }

        function closeAllDropdownsExcept(except) {
            if (except !== 'district' && !districtWrapper.classList.contains('hidden')) {
                districtWrapper.classList.add('hidden');
                districtArrow.classList.remove('rotate-180');
            }
            if (except !== 'market' && !marketWrapper.classList.contains('hidden')) {
                marketWrapper.classList.add('hidden');
                marketArrow.classList.remove('rotate-180');
            }
            if (except !== 'commodityGroup' && !commodityGroupWrapper.classList.contains('hidden')) {
                commodityGroupWrapper.classList.add('hidden');
                commodityGroupArrow.classList.remove('rotate-180');
            }
            if (except !== 'commodity' && !commodityWrapper.classList.contains('hidden')) {
                commodityWrapper.classList.add('hidden');
                commodityArrow.classList.remove('rotate-180');
            }
        }

        function toggleCommodityDropdown() {
            closeAllDropdownsExcept('commodity');

            commodityWrapper.classList.toggle('hidden');
            commodityArrow.classList.toggle('rotate-180');
            if (!commodityWrapper.classList.contains('hidden')) {
                document.getElementById('commoditySearch').focus();
            }
        }

        function updateCommodityGroupDisplay() {
            const span = commodityGroupDisplay.querySelector('span');
            if (selectedCommodityGroups.length === 0 || selectedCommodityGroups.length === commodityGroups.length) {
                span.textContent = translations[currentLang].all_groups;
                span.classList.remove('text-emerald-700');
            } else {
                span.textContent = selectedCommodityGroups.map(g => translations[currentLang][g] || g).join(', ');
                span.classList.add('text-emerald-700');
            }
        }

        // Modified handlers to include Commodity dropdown logic
        function handleCommodityGroupSelection(checkbox) {
            const val = checkbox.value;

            if (checkbox.checked) {
                if (selectedCommodityGroups.length >= 10) {
                    checkbox.checked = false;
                    return;
                }
                selectedCommodityGroups.push(val);
            } else {
                selectedCommodityGroups = selectedCommodityGroups.filter(g => g !== val);
            }
            renderCommodityGroups(document.getElementById('commodityGroupSearch').value);
            updateCommodityGroupDisplay();
            updateCommodityOptions(); // Update commodities when groups change

            if (!commodityGroupWrapper.classList.contains('hidden')) {
                document.getElementById('commodityGroupSearch').focus();
            }
        }

        function toggleDistrictDropdown() {
            closeAllDropdownsExcept('district');

            districtWrapper.classList.toggle('hidden');
            districtArrow.classList.toggle('rotate-180');
            if (!districtWrapper.classList.contains('hidden')) {
                document.getElementById('districtSearch').focus();
            }
        }

        function toggleMarketDropdown() {
            closeAllDropdownsExcept('market');

            if (selectedDistricts.length === districts.length) return; // Prevent opening if All Districts selected

            marketWrapper.classList.toggle('hidden');
            marketArrow.classList.toggle('rotate-180');
            if (!marketWrapper.classList.contains('hidden')) {
                document.getElementById('marketSearch').focus();
            }
        }

        function toggleCommodityGroupDropdown() {
            closeAllDropdownsExcept('commodityGroup');

            commodityGroupWrapper.classList.toggle('hidden');
            commodityGroupArrow.classList.toggle('rotate-180');
            if (!commodityGroupWrapper.classList.contains('hidden')) {
                document.getElementById('commodityGroupSearch').focus();
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            // District
            if (!districtDisplay.contains(e.target) && !districtWrapper.contains(e.target)) {
                if (!districtWrapper.classList.contains('hidden')) {
                    districtWrapper.classList.add('hidden');
                    districtArrow.classList.remove('rotate-180');
                }
            }
            // Market
            if (!marketDisplay.contains(e.target) && !marketWrapper.contains(e.target)) {
                if (!marketWrapper.classList.contains('hidden')) {
                    marketWrapper.classList.add('hidden');
                    marketArrow.classList.remove('rotate-180');
                }
            }
            // Commodity Group
            if (!commodityGroupDisplay.contains(e.target) && !commodityGroupWrapper.contains(e.target)) {
                if (!commodityGroupWrapper.classList.contains('hidden')) {
                    commodityGroupWrapper.classList.add('hidden');
                    commodityGroupArrow.classList.remove('rotate-180');
                }
            }
            // Commodity
            if (!commodityDisplay.contains(e.target) && !commodityWrapper.contains(e.target)) {
                if (!commodityWrapper.classList.contains('hidden')) {
                    commodityWrapper.classList.add('hidden');
                    commodityArrow.classList.remove('rotate-180');
                }
            }
        });

        // Initialize proper state
        updateMarketOptions();
        updateCommodityOptions();

        // This sets up the language and does the initial render of everything
        changeLang(initialLang);

        // first page load
        document.getElementById("searchBtn").click();
        setInterval(() => {
            if (!document.hidden) {
                document.getElementById("searchBtn").click();
            }
        }, 15 * 60 * 1000);

        // Scroll Progress Bar
        window.onscroll = function() {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            let bar = document.getElementById("progressBar");
            if (bar) {
                bar.style.width = scrolled + "%";
                bar.style.height = "5px";
                bar.style.background = "linear-gradient(90deg, #10b981, #f59e0b)";
                bar.style.position = "fixed";
                bar.style.top = "0";
                bar.style.left = "0";
                bar.style.zIndex = "100";
            }
        };
    </script>
</body>

</html>
