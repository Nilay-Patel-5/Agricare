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

        /* Hide scrollbar for mobile menu */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 4px;
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
                <i class="far fa-calendar-alt"></i> Data as of: <span id="arrivalDateText">--</span>
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

    <script>
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

                if (!res.ok) throw new Error("Backend error");

                const data = await res.json();
                showResults(data);
            } catch (error) {
                console.error("Fetch error:", error);
                const container = document.getElementById("tableContainer");
                const placeholder = document.getElementById("noDataPlaceholder");

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
                                <p class="text-red-600">Could not connect to the live market database. Please ensure the backend server and MongoDB are running.</p>
                            </div>
                        </div>
                    </div>`;
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        });


        function showResults(rows) {

            const container = document.getElementById("tableContainer");
            const placeholder = document.getElementById("noDataPlaceholder");

            if (!rows || rows.length === 0) {
                placeholder.classList.remove("hidden");
                container.classList.add("hidden");

                // Hide arrival date if no data
                const dateDisplay = document.getElementById("arrivalDateDisplay");
                if (dateDisplay) dateDisplay.classList.add("hidden");

                return;
            }

            placeholder.classList.add("hidden");
            container.classList.remove("hidden");

            // Display dynamic arrival date from first record
            const dateDisplay = document.getElementById("arrivalDateDisplay");
            const dateText = document.getElementById("arrivalDateText");
            if (dateDisplay && dateText && rows[0].arrival_date) {
                dateText.innerText = rows[0].arrival_date;
                dateDisplay.classList.remove("hidden");
            }

            let html = `
    <div class="overflow-x-auto bg-white rounded-2xl shadow">
    <table class="w-full text-sm text-left">
    <thead class="bg-emerald-600 text-white">
        <tr>
            <th class="p-3" data-lang="district_label">${translations[currentLang]['district_label'] || 'District'}</th>
            <th class="p-3" data-lang="market_label">${translations[currentLang]['market_label'] || 'Market'}</th>
            <th class="p-3" data-lang="commodity_label">${translations[currentLang]['commodity_label'] || 'Commodity'}</th>
            <th class="p-3" data-lang="min_price">${translations[currentLang]['min_price'] || 'Min Price'}</th>
            <th class="p-3" data-lang="max_price">${translations[currentLang]['max_price'] || 'Max Price'}</th>
            <th class="p-3" data-lang="modal_price">${translations[currentLang]['modal_price'] || 'Modal Price'}</th>
        </tr>
    </thead>
    <tbody>`;

            rows.forEach(r => {
                const trDistrict = getLocalizedMarketName(r.district, currentLang);
                const trMarket = getLocalizedMarketName(r.market, currentLang);
                const trCommodity = getLocalizedMarketName(r.commodity, currentLang);

                html += `
        <tr class="border-b hover:bg-gray-50">
            <td class="p-3">${trDistrict}</td>
            <td class="p-3">${trMarket}</td>
            <td class="p-3">${trCommodity}</td>
            <td class="p-3">₹${r.min}</td>
            <td class="p-3">₹${r.max}</td>
            <td class="p-3 font-bold text-emerald-700">₹${r.modal}</td>
        </tr>`;
            });

            html += "</tbody></table></div>";

            container.innerHTML = html;
        }
        // Copying minimal needed script for menu & language
        const translations = {
            en: {
                logo: 'AgriCare',
                tagline: "Gujarat's Smart Farming Platform",
                home: '🏠 Home',
                features: '✨ Features',
                market: '📈 Market',
                weather: '🌤️ Weather',
                contact: 'ℹ️ About',
                loginBtn: 'Login',
                registerBtn: 'Register',
                'footer-desc': 'Empowering farmers with data, connecting markets with transparency, and building a sustainable future.',
                solutions: 'Solutions',
                copyright: '© 2026 AgriCare. Made with ❤️ for Farmers.',
                sol_advisory: 'Crop Advisory',
                sol_disease: 'Crop Disease Identification',
                sol_market: 'Market Connect',
                sol_weather: 'Weather Station',
                location: '⚲ Location: Gujarat, India',
                email: '✉ Contact: project@agricare.demo',
                phone: '🕻 Phone: Available on request',
                // Market Specific
                mandi_title: '<span class="text-emerald-600">Live</span> Mandi Prices',
                mandi_subtitle: 'Real-time agricultural market rates from across Gujarat',
                select_criteria: 'Select Market Criteria',
                district_label: 'District',
                market_label: 'Market',
                group_label: 'Commodity Group',
                commodity_label: 'Commodity',
                min_price: 'Min Price',
                max_price: 'Max Price',
                modal_price: 'Modal Price',
                all_districts: 'All Districts',
                all_markets: 'All Markets',
                all_groups: 'All Groups',
                all_commodities: 'All Commodities',
                get_prices: 'Get Prices',
                menu_title: 'Menu',
                select_district: 'Select District',
                select_market: 'Select Market',
                select_group: 'Select Group',
                select_commodity: 'Select Commodity',
                districts_selected: 'Districts Selected',
                markets_selected: 'Markets Selected',
                groups_selected: 'Groups Selected',
                commodities_selected: 'Commodities Selected',
                search_placeholder: 'Search...',
                no_districts_found: 'No districts found',
                select_district_first: 'Select a district first',
                no_markets_found: 'No markets found',
                "no_groups_found": "No groups found",
                "no_commodities_found": "No commodities found",
                "no_data_available": "No Data Available",
                "no_data_subtitle": "Select criteria and click search to view prices",
                "Cereals": "Cereals",
                "Fibre Crops": "Fibre Crops",
                "Oil Seeds": "Oil Seeds",
                "Pulses": "Pulses",
                "Vegetables": "Vegetables",
                "Others": "Others",
                // Districts
                "Ahmedabad": "Ahmedabad",
                "Amreli": "Amreli",
                "Anand": "Anand",
                "Aravalli": "Aravalli",
                "Banaskantha": "Banaskantha",
                "Bharuch": "Bharuch",
                "Bhavnagar": "Bhavnagar",
                "Botad": "Botad",
                "Chhota Udaipur": "Chhota Udaipur",
                "Dahod": "Dahod",
                "Dang": "Dang",
                "Devbhumi Dwarka": "Devbhumi Dwarka",
                "Gandhinagar": "Gandhinagar",
                "Gir Somnath": "Gir Somnath",
                "Jamnagar": "Jamnagar",
                "Junagarh": "Junagadh",
                "Kachchh": "Kutch",
                "Kheda": "Kheda",
                "Mahisagar": "Mahisagar",
                "Mehsana": "Mehsana",
                "Morbi": "Morbi",
                "Narmada": "Narmada",
                "Navsari": "Navsari",
                "Panchmahals": "Panchmahals",
                "Patan": "Patan",
                "Porbandar": "Porbandar",
                "Rajkot": "Rajkot",
                "Sabarkantha": "Sabarkantha",
                "Surat": "Surat",
                "Surendranagar": "Surendranagar",
                "Tapi": "Tapi",
                "The Dangs": "The Dangs",
                "Vadodara(Baroda)": "Vadodara",
                "Valsad": "Valsad",
                // Commodities
                "Bajra(Pearl Millet/Cumbu)": "Bajra",
                "Barley(Jau)": "Barley",
                "Jowar(Sorghum)": "Jowar",
                "Maize": "Maize",
                "Paddy(Common)": "Paddy",
                "Ragi(Finger Millet)": "Ragi",
                "Wheat": "Wheat",
                "Cotton": "Cotton",
                "Jute": "Jute",
                "Copra": "Copra",
                "Groundnut": "Groundnut",
                "Mustard": "Mustard",
                "Niger Seed(Ramtil)": "Niger Seed",
                "Safflower": "Safflower",
                "Sesamum(Sesame,Gingelly,Til)": "Sesame",
                "Soyabean": "Soyabean",
                "Sunflower": "Sunflower",
                "Sunflower Seed": "Sunflower Seed",
                "Toria": "Toria",
                "Arhar(Tur/Red Gram)(Whole)": "Arhar/Tur",
                "Bengal Gram(Gram)(Whole)": "Chana/Gram",
                "Black Gram(Urd Beans)(Whole)": "Urad",
                "Green Gram(Moong)(Whole)": "Moong",
                "Lentil(Masur)(Whole)": "Masur",
                "Onion": "Onion",
                "Potato": "Potato",
                "Tomato": "Tomato",
                "Sugarcane": "Sugarcane",
                // Market Towns/Descriptions
                "Abdasa": "Abdasa",
                "Halvad": "Halvad",
                "Khedbrahma": "Khedbrahma",
                "Chimanbhai Patal Market Vasana": "Chimanbhai Patel Market Vasana",
                "Flower Market Jamalpur": "Flower Market Jamalpur",
                "Fruit Market, Naroda": "Fruit Market, Naroda",
                "Manekchowk": "Manekchowk",
                "Rajnagar sub yard": "Rajnagar Sub Yard",
                "Ahwa-Dang": "Ahwa-Dang",
                "Amirgadh": "Amirgadh",
                "Amod": "Amod",
                "Anklav": "Anklav",
                "Ankleshwar": "Ankleshwar",
                "Babra": "Babra",
                "Bachau": "Bachau",
                "Bagasara": "Bagasara",
                "Balasinor": "Balasinor",
                "Kadod": "Kadod",
                "Madhi": "Madhi",
                "Baruch Vagara": "Bharuch Vagara",
                "Barvala": "Barvala",
                "Bavla": "Bavla",
                "Bayad": "Bayad",
                "Demai": "Demai",
                "Sadamba": "Sadamba",
                "Bhabhar": "Bhabhar",
                "Bhanvad": "Bhanvad",
                "Bhatiya": "Bhatiya",
                "Bhesan": "Bhesan",
                "Bhiloda": "Bhiloda",
                "Bhuj": "Bhuj",
                "Bilimora": "Bilimora",
                "Gandevi": "Gandevi",
                "Bodeliu": "Bodeli",
                "Borsad": "Borsad",
                "Asodar": "Asodar",
                "Barsad": "Barsad",
                "Bhabarkot": "Bhabarkot",
                "Haddad": "Haddad",
                "Hadad": "Hadad",
                "Chachack": "Chachack",
                "Chansama": "Chansama",
                "Chikhali": "Chikhali",
                "Chikli": "Chikli",
                "Khorgam": "Khorgam",
                "Chotila": "Chotila",
                "Dabhoi": "Dabhoi",
                "Kayavarohan": "Kayavarohan",
                "Garbada": "Garbada",
                "Himalaya": "Himalaya",
                "Jesavada": "Jesavada",
                "Damnagar": "Damnagar",
                "Danta": "Danta",
                "Dasada Patadi": "Dasada Patadi",
                "Piplod": "Piplod",
                "Dediyapada": "Dediyapada",
                "Deesa": "Deesa",
                "Bhildi": "Bhildi",
                "Dehgam": "Dehgam",
                "Rekhiyal": "Rekhiyal",
                "Derol": "Derol",
                "Adadara": "Adadara",
                "Vejalpur": "Vejalpur",
                "Devgadhbaria": "Devgadhbaria",
                "Dhandhuka": "Dhandhuka",
                "Dhandkuka": "Dhandhuka",
                "Dholera": "Dholera",
                "Dholka": "Dholka",
                "Koth": "Koth",
                "Dhoraji": "Dhoraji",
                "Dhrangadhra": "Dhrangadhra",
                "Dhrol": "Dhrol",
                "Diyodar": "Diyodar",
                "Gadada": "Gadhada",
                "Gariyadar": "Gariyadhar",
                "Garudeshwal": "Garudeshwar",
                "Godhra": "Godhra",
                "Kakanpur": "Kakanpur",
                "Timbaroad": "Timbaroad",
                "Gogamba": "Gogamba",
                "Similiya": "Similiya",
                "Gondal": "Gondal",
                "Halol": "Halol",
                "Harij": "Harij",
                "Hasot": "Hasot",
                "Elav": "Elav",
                "Himatnagar": "Himatnagar",
                "Gambhoi": "Gambhoi",
                "Idar": "Idar",
                "Jadar": "Jadar",
                "Jam Jodhpur": "Jam Jodhpur",
                "Jam Khambalia": "Jam Khambalia",
                "Jambusar": "Jambusar",
                "Kaavi": "Kaavi",
                "Jamkandorna": "Jamkandorna",
                "Jasdan": "Jasdan",
                "Vichhiya": "Vichhiya",
                "Jetpur-Pavi": "Jetpur-Pavi",
                "Jetpur": "Jetpur",
                "Jhagadiya": "Jhagadiya",
                "Jhalod": "Jhalod",
                "Jodiya": "Jodiya",
                "K.Mandvi": "K.Mandvi",
                "Kadi": "Kadi",
                "Kalawad": "Kalawad",
                "Kalediya": "Kalediya",
                "Kalol": "Kalol",
                "Moti Jaher": "Moti Jaher",
                "Kapadvanj": "Kapadvanj",
                "Karjan": "Karjan",
                "Kathlal": "Kathlal",
                "Kawant": "Kawant",
                "Keshod": "Keshod",
                "Khambha": "Khambha",
                "Khambhat": "Khambhat",
                "Grain Market": "Grain Market",
                "Khanpur": "Khanpur",
                "Nayaka": "Nayaka",
                "Lambadia": "Lambadia",
                "Posina": "Posina",
                "Kodinar": "Kodinar",
                "Dollasa": "Dollasa",
                "Kosamba": "Kosamba",
                "Vankal": "Vankal",
                "Zangvav": "Zangvav",
                "Kutiyana": "Kutiyana",
                "Lakhani": "Lakhani",
                "Lakhtar": "Lakhtar",
                "Lalpur": "Lalpur",
                "Lathi": "Lathi",
                "Limdi": "Limdi",
                "Limkheda": "Limkheda",
                "Lunavada": "Lunavada",
                "Kobamba": "Kobamba",
                "Anaval": "Anaval",
                "Malia Hatina": "Malia Hatina",
                "Malpur": "Malpur",
                "Manavdar": "Manavdar",
                "Mandal": "Mandal",
                "Mandvi": "Mandvi",
                "Amba Paradi": "Amba Paradi",
                "Mangrol": "Mangrol",
                "Mansa": "Mansa",
                "Vaghai": "Vaghai",
                "Radlavada": "Radlavada",
                "Ladol": "Ladol",
                "Jornang": "Jornang",
                "Tintoi": "Tintoi",
                "Modasar": "Modasar",
                "Morva Hafad": "Morva Hadaf",
                "Muli": "Muli",
                "Mundra": "Mundra",
                "Nadiad": "Nadiad",
                "Chaklasi": "Chaklasi",
                "Piplag": "Piplag",
                "Nakhatrana": "Nakhatrana",
                "Nasvadi": "Nasvadi",
                "Nizar": "Nizar",
                "Kukarmuda": "Kukarmuda",
                "Pumkitalov": "Pumkitalov",
                "Padra": "Padra",
                "Palanpur": "Palanpur",
                "Palitana": "Palitana",
                "Panthawada": "Panthawada",
                "Panvad": "Panvad",
                "Pardi": "Pardi",
                "Motavagachiya": "Motavagachiya",
                "Rohinee": "Rohinee",
                "Ugvada": "Ugvada",
                "Vapi": "Vapi",
                "Petlad": "Petlad",
                "Prantij": "Prantij",
                "Salala": "Salala",
                "Radhanpur": "Radhanpur",
                "Radheja": "Radheja",
                "Rajpipla": "Rajpipla",
                "Dungar": "Dungar",
                "Rajula": "Rajula",
                "Rampura": "Rampura",
                "Randheja": "Randheja",
                "Chiloda": "Chiloda",
                "Ranpur": "Ranpur",
                "Rapar": "Rapar",
                "S.Mandvi": "S.Mandvi",
                "Sami": "Sami",
                "Sanad": "Sanand",
                "Santrampur": "Santrampur",
                "Fathaepura": "Fathaepura",
                "Savarkundla": "Savarkundla",
                "Savli": "Savli",
                "Desar": "Desar",
                "Samlaya": "Samlaya",
                "Sayala": "Sayala",
                "Selemba": "Selemba",
                "Navgam Javli": "Navgam Javli",
                "Shehra": "Shehra",
                "Shinor": "Shinor",
                "Siddhpur": "Siddhpur",
                "Sojitra": "Sojitra",
                "Songadh": "Songadh",
                "Badarpada": "Badarpada",
                "Umrada": "Umrada",
                "Talala": "Talala",
                "Talalagir": "Talalagir",
                "Taleja": "Taleja",
                "Talod": "Talod",
                "Harsol": "Harsol",
                "Tarapur": "Tarapur",
                "Tejgarh": "Tejgarh",
                "Thalkala": "Thalkala",
                "Thara": "Thara",
                "Shihori": "Shihori",
                "Tharad": "Tharad",
                "Rah": "Rah",
                "Thasara": "Thasara",
                "Dokar": "Dokar",
                "Tilakwada": "Tilakwada",
                "Timbi": "Timbi",
                "Uchhal": "Uchhal",
                "Karod": "Karod",
                "Umreth": "Umreth",
                "Una": "Una",
                "Unava": "Unava",
                "Unjha": "Unjha",
                "Upleta": "Upleta",
                "Vadali": "Vadali",
                "Vadgam": "Vadgam",
                "Vadhvan": "Vadhvan",
                "Vadnagar": "Vadnagar",
                "Kheralu": "Kheralu",
                "Navapura": "Navapura",
                "Sayajipura": "Sayajipura",
                "Vagodiya": "Vagodiya",
                "Valia": "Valia",
                "Nethrang": "Nethrang",
                "Valod": "Valod",
                "Buhari": "Buhari",
                "Dungari": "Dungari",
                "Vankaner": "Vankaner",
                "Vansda": "Vansda",
                "Limzar": "Limzar",
                "Vanthli": "Vanthli",
                "Varahi": "Varahi",
                "Vav": "Vav",
                "Veraval": "Veraval",
                "Vijapur": "Vijapur",
                "Gojjariya": "Gojjariya",
                "Kukarvada": "Kukarvada",
                "Vijaynagar": "Vijaynagar",
                "Kundlakap": "Kundlakap",
                "Viramgam": "Viramgam",
                "Virpur": "Virpur",
                "Visavadar": "Visavadar",
                "Visnagar": "Visnagar",
                "Vyara": "Vyara",
                "Paati": "Paati",
                "Sanjeli": "Sanjeli",
                "Bardoli": "Bardoli",
                "Becharaji": "Becharaji",
                "Davgadbaria": "Devgadhbaria",
                "Dhanera": "Dhanera",
                "Dhansura": "Dhansura",
                "Dharampur": "Dharampur",
                "Dhari": "Dhari",
                "Junagadh": "Junagadh",
                "Junagarh": "Junagadh",
                "cotton": "Cotton",
                "Kapadan": "Kapadvanj",
                "Kapadvanj": "Kapadvanj",
                "Kapadanj": "Kapadvanj",
                "Mahuva": "Mahuva",
                "Manas": "Mansa",
                "Matar": "Matar",
                "Zalod": "Jhalod",
                "Vadodara": "Vadodara",
                "Nadiyad": "Nadiad",
                "Modasa": "Modasa",
                "Anjar": "Anjar",
                "Samarwada": "Samarwada",
                "Chalala": "Chalala",
                "Limbasi": "Limbasi",
                "APMC": "APMC",
                "Sub yard": "Sub Yard",
                "Mehmadabad": "Mahemdavad",
                "Meghraj": "Meghraj",
                "District": "District",
                "Veg,Yard": "Vegetable Yard",
                "Veg,Sub": "Sub Yard",
                "Station Road": "Station Road",
                "Dist.": "District",
                "Dist": "District"
            },
            gu: {
                logo: 'એગ્રીકેર',
                tagline: "ગુજરાતનું સ્માર્ટ કૃષિ પ્લેટફોર્મ",
                home: '🏠 મુખ્ય',
                features: '✨ સુવિધાઓ',
                market: '📈 બજાર',
                weather: '🌤️ હવામાન',
                contact: 'ℹ️ પરિચય',
                loginBtn: 'લોગિન',
                registerBtn: 'નોંધણી',
                'footer-desc': 'ખેડૂતોને સશક્તિકરણ, પારદર્શિતા સાથે બજારોને જોડવું અને ટકાઉ ભવિષ્યનું નિર્માણ.',
                solutions: 'ઉકેલો',
                copyright: '© 2026 એગ્રીકેર. ખેડૂતો માટે ❤️ થી બનાવેલ.',
                sol_advisory: 'પાક સલાહ',
                sol_disease: 'પાક રોગ ઓળખ',
                sol_market: 'બજાર જોડાણ',
                sol_weather: 'હવામાન સ્ટેશન',
                location: '⚲ સ્થળ: ગુજરાત, ભારત',
                email: '✉ સંપર્ક: project@agricare.demo',
                phone: '🕻 ફોન: વિનંતી પર ઉપલબ્ધ',
                // Market Specific
                mandi_title: '<span class="text-emerald-600">લાઈવ</span> મંડી ભાવ',
                mandi_subtitle: 'સમગ્ર ગુજરાતમાંથી વાસ્તવિક સમયના કૃષિ બજાર ભાવ',
                select_criteria: 'બજારના માપદંડ પસંદ કરો',
                district_label: 'જિલ્લો',
                market_label: 'બજાર',
                group_label: 'પાક જૂથ',
                commodity_label: 'પાક',
                min_price: 'લઘુત્તમ ભાવ',
                max_price: 'મહત્તમ ભાવ',
                modal_price: 'સામાન્ય ભાવ',
                all_districts: 'બધા જિલ્લાઓ',
                all_markets: 'બધા બજારો',
                all_groups: 'બધા જૂથો',
                all_commodities: 'બધા પાક',
                get_prices: 'ભાવ મેળવો',
                menu_title: 'મેનુ',
                select_district: 'જિલ્લો પસંદ કરો',
                select_market: 'બજાર પસંદ કરો',
                select_group: 'પાક જૂથ પસંદ કરો',
                select_commodity: 'પાક પસંદ કરો',
                districts_selected: 'જિલ્લાઓ પસંદ કરેલ',
                markets_selected: 'બજારો પસંદ કરેલ',
                groups_selected: 'પાક જૂથો પસંદ કરેલ',
                commodities_selected: 'પાક પસંદ કરેલ',
                search_placeholder: 'શોધો...',
                no_districts_found: 'કોઈ જિલ્લા મળ્યા નથી',
                select_district_first: 'પહેલા જિલ્લા પસંદ કરો',
                no_markets_found: 'કોઈ બજાર મળ્યા નથી',
                "no_groups_found": "કોઈ જૂથ મળ્યા નથી",
                "no_commodities_found": "કોઈ પાક મળ્યા નથી",
                "no_data_available": "કોઈ માહિતી ઉપલબ્ધ નથી",
                "no_data_subtitle": "માપદંડ પસંદ કરો અને ભાવ જોવા માટે શોધો પર ક્લિક કરો",
                "Cereals": "ધાન્ય",
                "Fibre Crops": "રેસાવાળા પાક",
                "Oil Seeds": "તેલીબિયાં",
                "Pulses": "કઠોળ",
                "Vegetables": "શાકભાજી",
                "Others": "અન્ય",
                // Districts
                "Ahmedabad": "અમદાવાદ",
                "Amreli": "અમરેલી",
                "Anand": "આણંદ",
                "Aravalli": "અરવલ્લી",
                "Banaskantha": "બનાસકાંઠા",
                "Bharuch": "ભરૂચ",
                "Bhavnagar": "ભાવનગર",
                "Botad": "બોટાદ",
                "Chhota Udaipur": "છોટા ઉદેપુર",
                "Dahod": "દાહોદ",
                "Dang": "ડાંગ",
                "Devbhumi Dwarka": "દેવભૂમિ દ્વારકા",
                "Gandhinagar": "ગાંધીનગર",
                "Gir Somnath": "ગીર સોમનાથ",
                "Jamnagar": "જામનગર",
                "Junagarh": "જૂનાગઢ",
                "Kachchh": "કચ્છ",
                "Kheda": "ખેડા",
                "Mahisagar": "મહીસાગર",
                "Mehsana": "મહેસાણા",
                "Morbi": "મોરબી",
                "Narmada": "નર્મદા",
                "Navsari": "નવસારી",
                "Panchmahals": "પંચમહાલ",
                "Patan": "પાટણ",
                "Porbandar": "પોરબંદર",
                "Rajkot": "રાજકોટ",
                "Sabarkantha": "સાબરકાંઠા",
                "Surat": "સુરત",
                "Surendranagar": "સુરેન્દ્રનગર",
                "Tapi": "તાપી",
                "The Dangs": "ડાંગ",
                "Vadodara(Baroda)": "વડોદરા",
                "Valsad": "વલસાડ",
                // Commodities
                "Bajra(Pearl Millet/Cumbu)": "બાજરી",
                "Barley(Jau)": "જવ",
                "Jowar(Sorghum)": "જુવાર",
                "Maize": "મકાઈ",
                "Paddy(Common)": "ડાંગર",
                "Ragi(Finger Millet)": "રાગી",
                "Wheat": "ઘઉં",
                "Cotton": "કપાસ",
                "Jute": "શણ",
                "Copra": "કોપરા",
                "Groundnut": "મગફળી",
                "Mustard": "રાયડો",
                "Niger Seed(Ramtil)": "રામ તલ",
                "Safflower": "કુસુમ",
                "Sesamum(Sesame,Gingelly,Til)": "તલ",
                "Soyabean": "સોયાબીન",
                "Sunflower": "સૂર્યમુખી",
                "Sunflower Seed": "સૂર્યમુખી બીજ",
                "Toria": "તોરિયા",
                "Arhar(Tur/Red Gram)(Whole)": "તુવેર",
                "Bengal Gram(Gram)(Whole)": "ચણા",
                "Black Gram(Urd Beans)(Whole)": "અડદ",
                "Green Gram(Moong)(Whole)": "મગ",
                "Lentil(Masur)(Whole)": "મસૂર",
                "Onion": "ડુંગળી",
                "Potato": "બટાકા",
                "Tomato": "ટામેટા",
                "Sugarcane": "શેરડી",
                // Market Towns/Descriptions
                "Abdasa": "અબડાસા",
                "Halvad": "હળવદ",
                "Khedbrahma": "ખેડબ્રહ્મા",
                "Chimanbhai Patal Market Vasana": "ચિમનભાઈ પટેલ માર્કેટ વાસણા",
                "Flower Market Jamalpur": "ફ્લાવર માર્કેટ જમાલપુર",
                "Fruit Market, Naroda": "ફ્રૂટ માર્કેટ, નરોડા",
                "Manekchowk": "માણેક ચોક",
                "Rajnagar sub yard": "રાજનગર પેટા યાર્ડ",
                "Ahwa-Dang": "આહવા-ડાંગ",
                "Amirgadh": "અમીરગઢ",
                "Amod": "આમોદ",
                "Anklav": "આંકલાવ",
                "Ankleshwar": "અંકલેશ્વર",
                "Babra": "બાબરા",
                "Bachau": "ભચાઉ",
                "Bagasara": "બગસરા",
                "Balasinor": "બાલાસિનોર",
                "Kadod": "કડોદ",
                "Madhi": "મઢી",
                "Baruch Vagara": "ભરૂચ વાગરા",
                "Barvala": "બરવાળા",
                "Bavla": "બાવળા",
                "Bayad": "બાયડ",
                "Demai": "દેમાઈ",
                "Sadamba": "સડાંબા",
                "Bhabhar": "ભાભર",
                "Bhanvad": "ભાણવડ",
                "Bhatiya": "ભાટિયા",
                "Bhesan": "ભેસાણ",
                "Bhiloda": "ભિલોડા",
                "Bhuj": "ભુજ",
                "Bilimora": "બીલીમોરા",
                "Gandevi": "ગણદેવી",
                "Bodeliu": "બોડેલી",
                "Borsad": "બોરસદ",
                "Asodar": "અસોદર",
                "Barsad": "બોરસદ",
                "Bhabarkot": "ભાભરકોટ",
                "Haddad": "હડદ",
                "Hadad": "હડદ",
                "Chachack": "ચાચક",
                "Chansama": "ચાણસ્મા",
                "Chikhali": "ચીખલી",
                "Chikli": "ચીખલી",
                "Khorgam": "ખોરગામ",
                "Chotila": "ચોટીલા",
                "Dabhoi": "ડભોઈ",
                "Kayavarohan": "કાયાવરોહણ",
                "Garbada": "ગરબાડા",
                "Himalaya": "હિમાલય",
                "Jesavada": "જેસવાડા",
                "Damnagar": "દામનગર",
                "Danta": "દાંતા",
                "Dasada Patadi": "દસાડા પાટડી",
                "Piplod": "પીપલોદ",
                "Dediyapada": "દેડિયાપાડા",
                "Deesa": "ડીસા",
                "Bhildi": "ભીલડી",
                "Dehgam": "દહેગામ",
                "Rekhiyal": "રખિયાલ",
                "Derol": "ડેરોલ",
                "Adadara": "અડાદરા",
                "Vejalpur": "વેજલપુર",
                "Devgadhbaria": "દેવગઢબારિયા",
                "Dhandhuka": "ધંધુકા",
                "Dhandkuka": "ધંધુકા",
                "Dholera": "ધોલેરા",
                "Dholka": "ધોળકા",
                "Koth": "કોઠ",
                "Dhoraji": "ધોરાજી",
                "Dhrangadhra": "ધ્રાંગધ્રા",
                "Dhrol": "ધ્રોલ",
                "Diyodar": "દિયોદર",
                "Gadada": "ગઢડા",
                "Gariyadar": "ગારીયાધાર",
                "Garudeshwal": "ગરુડેશ્વર",
                "Godhra": "ગોધરા",
                "Kakanpur": "કાકણપુર",
                "Timbaroad": "ટીમ્બારોડ",
                "Gogamba": "ઘોઘંબા",
                "Similiya": "સિમિલિયા",
                "Gondal": "ગોંડલ",
                "Halol": "હાલોલ",
                "Harij": "હારીજ",
                "Hasot": "હાસોટ",
                "Elav": "ઈલાવ",
                "Himatnagar": "હિંમતનગર",
                "Gambhoi": "ગામભોઈ",
                "Idar": "ઈડર",
                "Jadar": "જાદર",
                "Jam Jodhpur": "જામ જોધપુર",
                "Jam Khambalia": "જામ ખંભાળિયા",
                "Jambusar": "જંબુસર",
                "Kaavi": "કાવી",
                "Jamkandorna": "જામકંડોરણા",
                "Jasdan": "જસદણ",
                "Vichhiya": "વિછીયા",
                "Jetpur-Pavi": "જેતપુર-પાવી",
                "Jetpur": "જેતપુર",
                "Jhagadiya": "ઝઘડિયા",
                "Jhalod": "ઝાલોદ",
                "Jodiya": "જોડિયા",
                "K.Mandvi": "કે. માંડવી",
                "Kadi": "કડી",
                "Kalawad": "કાલાવડ",
                "Kalediya": "કલેડિયા",
                "Kalol": "કલોલ",
                "Moti Jaher": "મોટી ઝાહેર",
                "Kapadvanj": "કપડવંજ",
                "Karjan": "કરજણ",
                "Kathlal": "કઠલાલ",
                "Kawant": "કવાંટ",
                "Keshod": "કેશોદ",
                "Khambha": "ખાંભા",
                "Khambhat": "ખંભાત",
                "Grain Market": "અનાજ બજાર",
                "Khanpur": "ખાનપુર",
                "Nayaka": "નાયકા",
                "Lambadia": "લામ્બડિયા",
                "Posina": "પોશીના",
                "Kodinar": "કોડીનાર",
                "Dollasa": "ડોલાસા",
                "Kosamba": "કોસંબા",
                "Vankal": "વાંકલ",
                "Zangvav": "ઝાંગવાવ",
                "Kutiyana": "કુતિયાણા",
                "Lakhani": "લાખાણી",
                "Lakhtar": "લખતર",
                "Lalpur": "લાલપુર",
                "Lathi": "લાઠી",
                "Limdi": "લીમડી",
                "Limkheda": "લીમખેડા",
                "Lunavada": "લુણાવાડા",
                "Kobamba": "કોબામ્બા",
                "Anaval": "અનાવલ",
                "Malia Hatina": "માળિયા હાટીના",
                "Malpur": "માલપુર",
                "Manavdar": "માણાવદર",
                "Mandal": "માંડલ",
                "Mandvi": "માંડવી",
                "Amba Paradi": "આંબા પારડી",
                "Mangrol": "માંગરોળ",
                "Mansa": "માણસા",
                "Vaghai": "વઘઈ",
                "Radlavada": "રાડલાવડા",
                "Ladol": "લાડોલ",
                "Jornang": "જોરનાંગ",
                "Tintoi": "ટીંટોઈ",
                "Modasar": "મોડાસર",
                "Morva Hafad": "મોરવા હડફ",
                "Muli": "મૂળી",
                "Mundra": "મુંદ્રા",
                "Nadiad": "નડીઆદ",
                "Chaklasi": "ચકલાસી",
                "Piplag": "પીપલગ",
                "Nakhatrana": "નખત્રાણા",
                "Nasvadi": "નસવાડી",
                "Nizar": "નિઝર",
                "Kukarmuda": "કુકરમુંડા",
                "Pumkitalov": "પુંકીતલોવ",
                "Padra": "પાદરા",
                "Palanpur": "પાલનપુર",
                "Palitana": "પાલીતાણા",
                "Panthawada": "પાંથવાડા",
                "Panvad": "પાનવડ",
                "Pardi": "પારડી",
                "Motavagachiya": "મોટાવઘાછિયા",
                "Rohinee": "રોહિણી",
                "Ugvada": "ઉગવાડા",
                "Vapi": "વાપી",
                "Petlad": "પેટલાદ",
                "Prantij": "પ્રાંતિજ",
                "Salala": "સલાલ",
                "Radhanpur": "રાધનપુર",
                "Radheja": "રાધેજા",
                "Rajpipla": "રાજપીપળા",
                "Dungar": "ડુંગર",
                "Rajula": "રાજુલા",
                "Rampura": "રામપુરા",
                "Randheja": "રાંધેજા",
                "Chiloda": "ચિલોડા",
                "Ranpur": "રાણપુર",
                "Rapar": "રાપર",
                "S.Mandvi": "એસ.માંડવી",
                "Sami": "સમી",
                "Sanad": "સાણંદ",
                "Santrampur": "સંતરામપુર",
                "Fathaepura": "ફતેપુરા",
                "Savarkundla": "સાવરકુંડલા",
                "Savli": "સાવલી",
                "Desar": "ડેસર",
                "Samlaya": "સામલાયા",
                "Sayala": "સાયલા",
                "Selemba": "સેલેમ્બા",
                "Navgam Javli": "નવગામ જાવલી",
                "Shehra": "શહેરા",
                "Shinor": "શિનોર",
                "Siddhpur": "સિદ્ધપુર",
                "Sojitra": "સોજિત્રા",
                "Songadh": "સોનગઢ",
                "Badarpada": "બાદરપાડા",
                "Umrada": "ઉમરડા",
                "Talala": "તાલાલા",
                "Talalagir": "તાલાલાગીર",
                "Taleja": "તાલેજા",
                "Talod": "તલોદ",
                "Harsol": "હરસોલ",
                "Tarapur": "તારાપુર",
                "Tejgarh": "તેજગઢ",
                "Thalkala": "થાલકાલા",
                "Thara": "થરા",
                "Shihori": "શિહોરી",
                "Tharad": "થરાદ",
                "Rah": "રાહ",
                "Thasara": "ઠાસરા",
                "Dokar": "ડોકર",
                "Tilakwada": "તિલકવાડા",
                "Timbi": "ટીંબી",
                "Uchhal": "ઉચ્છલ",
                "Karod": "કરોડ",
                "Umreth": "ઉમરેઠ",
                "Una": "ઉના",
                "Unava": "ઉનાવા",
                "Unjha": "ઊંઝા",
                "Upleta": "ઉપલેટા",
                "Vadali": "વડાલી",
                "Vadgam": "વડગામ",
                "Vadhvan": "વઢવાણ",
                "Vadnagar": "વડનગર",
                "Kheralu": "ખેરાલુ",
                "Navapura": "નવાપુરા",
                "Sayajipura": "સયાજીપુરા",
                "Vagodiya": "વાઘોડિયા",
                "Valia": "વાલિયા",
                "Nethrang": "નેત્રંગ",
                "Valod": "વાલોડ",
                "Buhari": "બુહારી",
                "Dungari": "ડુંગરી",
                "Vankaner": "વાંકાનેર",
                "Vansda": "વાંસદા",
                "Limzar": "લીમઝર",
                "Vanthli": "વંથલી",
                "Varahi": "વારાહી",
                "Vav": "વાવ",
                "Veraval": "વેરાવળ",
                "Vijapur": "વિજાપુર",
                "Gojjariya": "ગોઝારિયા",
                "Kukarvada": "કુકરવાડા",
                "Vijaynagar": "વિજયનગર",
                "Kundlakap": "કુંડલાકાપ",
                "Viramgam": "વિરમગામ",
                "Virpur": "વિરપુર",
                "Visavadar": "વિસાવદર",
                "Visnagar": "વિસનગર",
                "Vyara": "વ્યારા",
                "Paati": "પાટી",
                "Sanjeli": "સંજેલી",
                "Bardoli": "બારડોલી",
                "Becharaji": "બેચરાજી",
                "Davgadbaria": "દેવગઢબારિયા",
                "Dhanera": "ધાનેરા",
                "Dhansura": "ધનસુરા",
                "Dharampur": "ધરમપુર",
                "Dhari": "ધારી",
                "Junagadh": "જૂનાગઢ",
                "Junagarh": "જૂનાગઢ",
                "cotton": "કપાસ",
                "Kapadan": "કપડવંજ",
                "Kapadvanj": "કપડવંજ",
                "Kapadanj": "કપડવંજ",
                "Mahuva": "મહુવા",
                "Manas": "માણસા",
                "Matar": "માતર",
                "Zalod": "ઝાલોદ",
                "Vadodara": "વડોદરા",
                "Nadiyad": "નડીઆદ",
                "Modasa": "મોડાસા",
                "Anjar": "અંજાર",
                "Samarwada": "સમરવાડા",
                "Chalala": "ચલાલા",
                "Limbasi": "લીંબાસી",
                "APMC": "એપીએમસી",
                "Sub yard": "પેટા યાર્ડ",
                "Mehmadabad": "મહેમદાવાદ",
                "Meghraj": "મેઘરજ",
                "District": "જિલ્લો",
                "Veg,Yard": "શાકભાજી માર્કેટ",
                "Veg,Sub": "પેટા યાર્ડ",
                "Veg": "શાકભાજી",
                "Market": "માર્કેટ",
                "Station Road": "સ્ટેશન રોડ",
                "Dist.": "જિલ્લો",
                "Dist": "જિલ્લો"
            },
            hi: {
                logo: 'एग्रीकेर',
                tagline: "गुजरात का स्मार्ट कृषि-मंच",
                home: '🏠 होम',
                features: '✨ विशेषताएँ',
                market: '📈 बाज़ार',
                weather: '🌤️ मौसम',
                contact: 'ℹ️ परिचय',
                loginBtn: 'लॉगिन',
                registerBtn: 'रजिस्टर',
                'footer-desc': 'किसानों को डेटा के साथ सशक्त बनाना और एक स्थायी भविष्य का निर्माण करना।',
                solutions: 'समाधान',
                copyright: '© 2026 एग्रीकेर। किसानों के लिए ❤️ से बनाया गया।',
                sol_advisory: 'फसल सलाह',
                sol_disease: 'फसल रोग पहचान',
                sol_market: 'बाज़ार संपर्क',
                sol_weather: 'मौसम स्टेशन',
                location: '⚲ स्थान: गुजरात, भारत',
                email: '✉ संपर्क: project@agricare.demo',
                phone: '🕻 फोन: अनुरोध पर उपलब्ध',
                // Market Specific
                mandi_title: '<span class="text-emerald-600">लाइव</span> मंडी भाव',
                mandi_subtitle: 'पूरे गुजरात से वास्तविक समय की कृषि बाजार दरें',
                select_criteria: 'बाजार मानदंड चुनें',
                district_label: 'जिला',
                market_label: 'बाजार',
                group_label: 'फसल समूह',
                commodity_label: 'फसल',
                min_price: 'न्यूनतम भाव',
                max_price: 'अधिकतम भाव',
                modal_price: 'मॉडल भाव',
                all_districts: 'सभी जिले',
                all_markets: 'सभी बाजार',
                all_groups: 'सभी समूह',
                all_commodities: 'सभी फसलें',
                get_prices: 'भाव प्राप्त करें',
                menu_title: 'मेन्यू',
                select_district: 'जिला चुनें',
                select_market: 'बाजार चुनें',
                select_group: 'फसल समूह चुनें',
                select_commodity: 'फसल चुनें',
                districts_selected: 'जिले चयनित',
                markets_selected: 'बाजार चयनित',
                groups_selected: 'फसल समूह चयनित',
                commodities_selected: 'फसल चयनित',
                search_placeholder: 'खोजें...',
                no_districts_found: 'कोई जिला नहीं मिला',
                select_district_first: 'पहले जिला चुनें',
                no_markets_found: 'कोई बाजार नहीं मिला',
                "no_groups_found": "कोई समूह नहीं मिला",
                "no_commodities_found": "कोई फसल नहीं मिली",
                "no_data_available": "कोई डेटा उपलब्ध नहीं",
                "no_data_subtitle": "मानदंड चुनें और भाव देखने के लिए खोजें पर क्लिक करें",
                "Cereals": "अनाज",
                "Fibre Crops": "रेशेदार फसलें",
                "Oil Seeds": "तिलहन",
                "Pulses": "दलहन",
                "Vegetables": "सब्जियाँ",
                "Others": "अन्य",
                // Districts
                "Ahmedabad": "अहमदाबाद",
                "Amreli": "अमरेली",
                "Anand": "आणंद",
                "Aravalli": "अरावली",
                "Banaskantha": "बनासकांठा",
                "Bharuch": "भरूच",
                "Bhavnagar": "भावनगर",
                "Botad": "बोटाद",
                "Chhota Udaipur": "छोटा उदयपुर",
                "Dahod": "दाहोद",
                "Dang": "डांग",
                "Devbhumi Dwarka": "देवभूमि द्वारका",
                "Gandhinagar": "गांधीनगर",
                "Gir Somnath": "गिर सोमनाथ",
                "Jamnagar": "जामनगर",
                "Junagarh": "जूनागढ़",
                "Kachchh": "कच्छ",
                "Kheda": "खेड़ा",
                "Mahisagar": "महीसागर",
                "Mehsana": "मेहसाणा",
                "Morbi": "मोरबी",
                "Narmada": "नर्मदा",
                "Navsari": "नवसारी",
                "Panchmahals": "पंचमहल",
                "Patan": "पाटन",
                "Porbandar": "पोरबंदर",
                "Rajkot": "राजकोट",
                "Sabarkantha": "साबरकांठा",
                "Surat": "सूरत",
                "Surendranagar": "सुरेंद्रनगर",
                "Tapi": "तापी",
                "The Dangs": "डांग",
                "Vadodara(Baroda)": "वडोदरा",
                "Valsad": "वलसाड",
                // Commodities
                "Bajra(Pearl Millet/Cumbu)": "बाजरा",
                "Barley(Jau)": "जौ",
                "Jowar(Sorghum)": "ज्वार",
                "Maize": "मक्का",
                "Paddy(Common)": "धान",
                "Ragi(Finger Millet)": "रागी",
                "Wheat": "गेहूँ",
                "Cotton": "कपास",
                "Jute": "जूट",
                "Copra": "खोपरा",
                "Groundnut": "मूँगफली",
                "Mustard": "सरसों",
                "Niger Seed(Ramtil)": "रामतिल",
                "Safflower": "कुसुम",
                "Sesamum(Sesame,Gingelly,Til)": "तिल",
                "Soyabean": "सोयाबीन",
                "Sunflower": "सूरजमुखी",
                "Sunflower Seed": "सूरजमुखी के बीज",
                "Toria": "तोरिया",
                "Arhar(Tur/Red Gram)(Whole)": "अरहर",
                "Bengal Gram(Gram)(Whole)": "चना",
                "Black Gram(Urd Beans)(Whole)": "उड़द",
                "Green Gram(Moong)(Whole)": "मूँગ",
                "Lentil(Masur)(Whole)": "मसूर",
                "Onion": "प्याज",
                "Potato": "आलू",
                "Tomato": "टमाटर",
                "Sugarcane": "गन्ना",
                // Market Towns/Descriptions
                "Abdasa": "अबडासा",
                "Halvad": "हलवद",
                "Khedbrahma": "खेड़ब्रह्मा",
                "Chimanbhai Patal Market Vasana": "चिमनभाई पटेल मार्केट वासणा",
                "Flower Market Jamalpur": "फ्लावर मार्केट जमालपुर",
                "Fruit Market, Naroda": "फ्रूट मार्केट, नरोडा",
                "Manekchowk": "माणक चौक",
                "Rajnagar sub yard": "राजनगर सब यार्ड",
                "Ahwa-Dang": "अहवा-डांग",
                "Amirgadh": "अमीरगढ़",
                "Amod": "आमोद",
                "Anklav": "अंकलाव",
                "Ankleshwar": "अंकलेश्वर",
                "Babra": "बाबरा",
                "Bachau": "भचाउ",
                "Bagasara": "बगसरा",
                "Balasinor": "बालासिनोर",
                "Kadod": "कडोद",
                "Madhi": "मढ़ी",
                "Baruch Vagara": "भरूच वागरा",
                "Barvala": "बरवाला",
                "Bavla": "बावला",
                "Bayad": "बायड",
                "Demai": "डेमाई",
                "Sadamba": "सडांबा",
                "Bhabhar": "भाबर",
                "Bhanvad": "भाणवड",
                "Bhatiya": "भाटिया",
                "Bhesan": "भेसाण",
                "Bhiloda": "भिलोडा",
                "Bhuj": "भुज",
                "Bilimora": "बिलीमोरा",
                "Gandevi": "गणदेवी",
                "Bodeliu": "बोडेली",
                "Borsad": "बोरसद",
                "Asodar": "असोदर",
                "Barsad": "बोरसद",
                "Bhabarkot": "भाभरकोट",
                "Haddad": "हडद",
                "Hadad": "हडद",
                "Chachack": "चाचक",
                "Chansama": "चाणस्मा",
                "Chikhali": "चिखली",
                "Chikli": "चिखली",
                "Khorgam": "खोरगाम",
                "Chotila": "चोटिला",
                "Dabhoi": "डभोई",
                "Kayavarohan": "कायावरोहण",
                "Garbada": "गरबाडा",
                "Himalaya": "हिमालय",
                "Jesavada": "जेसवाडा",
                "Damnagar": "दामनगर",
                "Danta": "दांता",
                "Dasada Patadi": "दसाडा पाटड़ी",
                "Piplod": "पिपलोद",
                "Dediyapada": "डेड़ियापाड़ा",
                "Deesa": "डीसा",
                "Bhildi": "भीलड़ी",
                "Dehgam": "दहेगाम",
                "Rekhiyal": "रखियाल",
                "Derol": "डेरोल",
                "Adadara": "अडादरा",
                "Vejalpur": "वेजलपुर",
                "Devgadhbaria": "देवगढ़बारिया",
                "Dhandhuka": "धंधुका",
                "Dhandkuka": "धंधुका",
                "Dholera": "धोलेरा",
                "Dholka": "धोळका",
                "Koth": "कोठ",
                "Dhoraji": "धोराजी",
                "Dhrangadhra": "ध्रांगध्रा",
                "Dhrol": "ध्रोल",
                "Diyodar": "दियोदर",
                "Gadada": "गढडा",
                "Gariyadar": "गरियाधार",
                "Garudeshwal": "गरुड़ेश्वर",
                "Godhra": "गोधरा",
                "Kakanpur": "काकणपुर",
                "Timbaroad": "टीम्बारोड",
                "Gogamba": "घोघंबा",
                "Similiya": "सिमिलिया",
                "Gondal": "गोंडल",
                "Halol": "हलोल",
                "Harij": "हारीज",
                "Hasot": "हासोट",
                "Elav": "इलाव",
                "Himatnagar": "हिम्मतनगर",
                "Gambhoi": "गामभोई",
                "Idar": "ईडर",
                "Jadar": "जादर",
                "Jam Jodhpur": "जाम जोधपुर",
                "Jam Khambalia": "जाम खंभाळिया",
                "Jambusar": "जंबूसर",
                "Kaavi": "कावी",
                "Jamkandorna": "जामकंडोरणा",
                "Jasdan": "जसदण",
                "Vichhiya": "विछिया",
                "Jetpur-Pavi": "जेतपुर-पारी",
                "Jetpur": "जेतपुर",
                "Jhagadiya": "झगड़िया",
                "Jhalod": "झालोद",
                "Jodiya": "जोडिया",
                "K.Mandvi": "के. मांडवी",
                "Kadi": "कड़ी",
                "Kalawad": "कालावड",
                "Kalediya": "कलेड़िया",
                "Kalol": "कलोल",
                "Moti Jaher": "मोटीा झहेर",
                "Kapadvanj": "कपड़वंज",
                "Karjan": "करजण",
                "Kathlal": "कठलाल",
                "Kawant": "कवांट",
                "Keshod": "केशोद",
                "Khambha": "खांभा",
                "Khambhat": "खंभात",
                "Grain Market": "अनाज बाजार",
                "Khanpur": "खानपुर",
                "Nayaka": "नायका",
                "Lambadia": "लाम्बडिया",
                "Posina": "पोशीना",
                "Kodinar": "कोडीनार",
                "Dollasa": "डोलासा",
                "Kosamba": "कोसंबा",
                "Vankal": "वांकल",
                "Zangvav": "जांगवाव",
                "Kutiyana": "कुतियाना",
                "Lakhani": "लाखाणी",
                "Lakhtar": "लखतर",
                "Lalpur": "लालपुर",
                "Lathi": "लाठी",
                "Limdi": "लीमडी",
                "Limkheda": "लीमखेड़ा",
                "Lunavada": "लूणावाड़ा",
                "Kobamba": "कोबाम्बा",
                "Anaval": "अनावल",
                "Malia Hatina": "माळिया हाटीणा",
                "Malpur": "मालपुर",
                "Manavdar": "मानवदर",
                "Mandal": "मंडल",
                "Mandvi": "मांडवी",
                "Amba Paradi": "आंबा पारडी",
                "Mangrol": "मांगरोल",
                "Mansa": "माणसा",
                "Vaghai": "वघई",
                "Radlavada": "राडलावाड़ा",
                "Ladol": "लाडोल",
                "Jornang": "जोरनांग",
                "Tintoi": "टिंटोई",
                "Modasar": "मोडसर",
                "Morva Hafad": "मोरवा हडफ",
                "Muli": "मूली",
                "Mundra": "मुंद्रा",
                "Nadiad": "नडियाद",
                "Chaklasi": "चकलासी",
                "Piplag": "पीपलग",
                "Nakhatrana": "नखत्राणा",
                "Nasvadi": "नसवाड़ी",
                "Nizar": "निझर",
                "Kukarmuda": "कुकरमुंडा",
                "Pumkitalov": "पुंकीतलोव",
                "Padra": "पादरा",
                "Palanpur": "पालनपुर",
                "Palitana": "पालिताना",
                "Panthawada": "पांथवाड़ा",
                "Panvad": "पानवड",
                "Pardi": "पारडी",
                "Motavagachiya": "मोटावघाछिया",
                "Rohinee": "रोहिणी",
                "Ugvada": "उगवाड़ा",
                "Vapi": "वापी",
                "Petlad": "पेटलाद",
                "Prantij": "प्रांतिज",
                "Salala": "सलाल",
                "Radhanpur": "राधनपुर",
                "Radheja": "राधजा",
                "Rajpipla": "राजपीपला",
                "Dungar": "डूंगर",
                "Rajula": "राजूला",
                "Rampura": "रामपुरा",
                "Randheja": "रांधेजा",
                "Chiloda": "चिलोड़ा",
                "Ranpur": "राणपुर",
                "Rapar": "रापर",
                "S.Mandvi": "एस.मांडवी",
                "Sami": "समी",
                "Sanad": "साणंद",
                "Santrampur": "संतरामपुर",
                "Fathaepura": "फतेपुरा",
                "Savarkundla": "सावरकुंडला",
                "Savli": "सावरी",
                "Desar": "डेसर",
                "Samlaya": "सामलाया",
                "Sayala": "सायला",
                "Selemba": "सेलेंबा",
                "Navgam Javli": "नवगाम जावली",
                "Shehra": "शेहरा",
                "Shinor": "शिनोर",
                "Siddhpur": "सिद्धपुर",
                "Sojitra": "सोजित्रा",
                "Songadh": "सोनगढ़",
                "Badarpada": "बादरपाडा",
                "Umrada": "उमरडा",
                "Talala": "तालाला",
                "Talalagir": "तालालागीर",
                "Taleja": "तालेजा",
                "Talod": "तलोद",
                "Harsol": "हरसोल",
                "Tarapur": "तारापुर",
                "Tejgarh": "तेजगढ़",
                "Thalkala": "थालकाला",
                "Thara": "थरा",
                "Shihori": "शिहोरी",
                "Tharad": "थराद",
                "Rah": "राह",
                "Thasara": "ठासरा",
                "Dokar": "डोकर",
                "Tilakwada": "तिलकवाड़ा",
                "Timbi": "टिम्बी",
                "Uchhal": "उच्छल",
                "Karod": "करोड़",
                "Umreth": "उमरेठ",
                "Una": "ऊना",
                "Unava": "उनावा",
                "Unjha": "ऊंझा",
                "Upleta": "उपलेटा",
                "Vadali": "वडाली",
                "Vadgam": "वदगाम",
                "Vadhvan": "वढवाण",
                "Vadnagar": "वडनगर",
                "Kheralu": "खेरालू",
                "Navapura": "नवापुरा",
                "Sayajipura": "सयाजीपुरा",
                "Vagodiya": "वाघोडिया",
                "Valia": "वालिया",
                "Nethrang": "नेत्रांग",
                "Valod": "वालोड",
                "Buhari": "बुहारी",
                "Dungari": "डुंगरी",
                "Vankaner": "वांकानेर",
                "Vansda": "वांसदा",
                "Limzar": "लीमजर",
                "Vanthli": "वंथली",
                "Varahi": "वाराही",
                "Vav": "वाव",
                "Veraval": "वेरावल",
                "Vijapur": "विजापुर",
                "Gojjariya": "गोजारिया",
                "Kukarvada": "कुकरवाडा",
                "Vijaynagar": "विजयनगर",
                "Kundlakap": "कुंडलाकाप",
                "Viramgam": "विरमगाम",
                "Virpur": "विरपुर",
                "Visavadar": "विसावदर",
                "Visnagar": "विसनगर",
                "Vyara": "व्यारा",
                "Paati": "पाटी",
                "Sanjeli": "संजेली",
                "Bardoli": "बारडोली",
                "Becharaji": "बेचराजी",
                "Davgadbaria": "देवगढ़बारिया",
                "Dhanera": "धानेरा",
                "Dhansura": "धनसुरा",
                "Dharampur": "धर्मपुर",
                "Dhari": "धारी",
                "Junagadh": "जूनागढ़",
                "Junagarh": "जूनागढ़",
                "cotton": "कपास",
                "Kapadan": "कपड़वंज",
                "Kapadvanj": "कपड़वंज",
                "Kapadanj": "कपड़वंज",
                "Mahuva": "महुआ",
                "Manas": "मानसा",
                "Matar": "मातर",
                "Zalod": "झालोद",
                "Vadodara": "वड़ोदरा",
                "Nadiyad": "नडियाद",
                "Modasa": "मोडासा",
                "Anjar": "अंजार",
                "Samarwada": "समरवाड़ा",
                "Chalala": "चलाला",
                "Limbasi": "लिंबासी",
                "APMC": "एपीएमसी",
                "Sub yard": "उप मंडी",
                "Mehmadabad": "महमदाबाद",
                "Meghraj": "मेघराज",
                "District": "जिला",
                "Veg,Yard": "सब्जी मंडी",
                "Veg,Sub": "उप मंडी",
                "Market": "बाजार",
                "Station Road": "स्टेशन रोड",
                "Dist.": "जिला",
                "Dist": "जिला",
                "Veg": "सब्जी"
            }
        };

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
        }

        function getLocalizedMarketName(market, lang) {
            let localized = market;
            const suffixes = {
                en: {
                    ' APMC': ' APMC',
                    ' Veg.': ' Vegetable',
                    ' veg.': ' vegetable',
                    ' Veg Yard': ' Vegetable Yard',
                    ' Veg.Market': ' Vegetable Market',
                    ' Veg,Market': ' Vegetable Market',
                    ' Veg,Yard': ' Vegetable Yard',
                    ' Veg.market': ' Vegetable Market',
                    ' Market': ' Market',
                    ' Dist.': ' District',
                    ' dist.': ' district',
                    ' Station Road': ' Station Road',
                    ' station road': ' station road'
                },
                gu: {
                    ' APMC': ' એપીએમસી',
                    ' Veg.': ' શાકભાજી',
                    ' veg.': ' શાકભાજી',
                    ' Veg Yard': ' શાકભાજી માર્કેટ',
                    ' Veg.Market': ' શાકભાજી માર્કેટ',
                    ' Veg,Market': ' શાકભાજી માર્કેટ',
                    ' Veg,Yard': ' શાકભાજી માર્કેટ',
                    ' Veg': ' શાકભાજી',
                    ' veg': ' શાકભાજી',
                    ' Fruit Market': ' ફળ બજાર',
                    ' Grain Market': ' અનાજ બજાર',
                    ' Sub yard': ' પેટા યાર્ડ',
                    ' sub yard': ' પેટા યાર્ડ',
                    ' Yard': ' યાર્ડ',
                    ' Veg.market': ' શાકભાજી માર્કેટ',
                    ' Market': ' માર્કેટ',
                    ' Dist.': ' જિલ્લો',
                    ' dist.': ' જિલ્લો',
                    ' station road': ' સ્ટેશન રોડ',
                    ' Station Road': ' સ્ટેશન રોડ'
                },
                hi: {
                    ' APMC': ' एपीएमसी',
                    ' Veg.': ' सब्जी मंडी',
                    ' veg.': ' सब्जी मंडी',
                    ' Veg Yard': ' सब्जी मंडी',
                    ' Veg.Market': ' सब्जी मंडी',
                    ' Veg,Market': ' सब्जी मंडी',
                    ' Veg,Yard': ' सब्जी मंडी',
                    ' Fruit Market': ' फल बाजार',
                    ' Grain Market': ' अनाज बाजार',
                    ' Sub yard': ' उप मंडी',
                    ' sub yard': ' उप मंडी',
                    ' Yard': ' मंडी',
                    ' Veg.market': ' सब्जी मंडी',
                    ' Market': ' बाजार',
                    ' Dist.': ' जिला',
                    ' dist.': ' जिला',
                    ' station road': ' स्टेशन रोड',
                    ' Station Road': ' स्टेशन रोड'
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
                const regex = new RegExp(`\\b${escapeRegExp(eng)}\\b`, 'gi');

                if (regex.test(localized)) {
                    localized = localized.replace(regex, translations[lang][eng]);
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
                "Ahmedabad APMC", "Ahmedabad(Chimanbhai Patal Market Vasana) APMC",
                "Ahmedabad(Flower Market Jamalpur) APMC", "Ahmedabad(Fruit Market, Naroda) APMC",
                "Ahmedabad(Manekchowk) APMC", "Ahmedabad(Rajnagar sub yard) APMC",
                "Balasinor APMC", "Barvala APMC", "Bavla APMC", "Becharaji APMC", "Dhandhuka APMC",
                "Bavla APMC", "Becharaji APMC", "Dhandhuka APMC",
                "Dhandkuka(Dholera) APMC", "Dholka APMC", "Dholka(Koth) APMC",
                "Dholka(Market Yard Veg) APMC", "Kadi APMC", "Kadi(Kadi cotton Yard) APMC",
                "Kapadanj(Moti Jaher) APMC", "Kapadvanj APMC", "Kathlal APMC",
                "Kheda APMC", "Kheda(Nayaka) APMC", "Mandal APMC", "Matar APMC",
                "Matar(Limbasi) APMC", "Mehmadabad APMC", "Mehmadabad(Veg ,market) APMC",
                "Mehsana APMC", "Mehsana(Jornang) APMC", "Mehsana(Mehsana Veg) APMC",
                "Nadiad APMC", "Nadiyad(Chaklasi) APMC", "Nadiyad(Piplag) APMC",
                "Rampura APMC", "Ranpur APMC", "Sanad APMC", "Thasara APMC",
                "Thasara(Dokar) APMC", "Unava APMC", "Unjha APMC", "Vadnagar APMC",
                "Vadnagar(Kheralu) APMC", "Vijapur APMC", "Vijapur(Gojjariya) APMC",
                "Vijapur(Kukarvada) APMC", "Vijapur(Ladol) APMC", "Vijapur(veg) APMC",
                "Viramgam APMC", "Virpur APMC", "Visnagar APMC"
            ],
            "Amreli": [
                "Amreli APMC", "Babra APMC", "Bagasara APMC", "Damnagar APMC",
                "Dhari APMC", "Dhari(Chalala) APMC", "Khambha APMC", "Lathi APMC",
                "Rajula (Dungar) APMC", "Rajula APMC", "Savarkundla APMC", "Timbi APMC"
            ],
            "Anand": [
                "Anand APMC", "Anand(Veg,Yard,Anand) APMC", "Anklav APMC", "Borsad APMC",
                "Borsad(Asodar) APMC", "Borsad(Veg Yard Barsad) APMC", "Khambhat APMC",
                "Khambhat(Grain Market) APMC", "Khambhat(Veg Yard Khambhat) APMC",
                "Petlad APMC", "Petlad(Veg Yard, Petlad) APMC", "Sojitra APMC",
                "Tarapur APMC", "Umreth APMC"
            ],
            "Banaskantha": [
                "Amirgadh APMC", "Banaskantha APMC", "Bhabhar APMC", "Danta APMC",
                "Deesa APMC", "Deesa(Bhildi) APMC", "Deesa(Deesa Veg Yard) APMC",
                "Dhanera APMC", "Dhanera(Samarwada) APMC", "Dhanera(Veg,Yard Dhanera) APMC",
                "Diyodar APMC", "Lakhani APMC", "Palanpur APMC", "Palanpur(Veg,Yard Palanpur) APMC",
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
                "Dahod(Jesavada) APMC", "Dahod(Veg. Market) APMC", "Davgadbaria(Piplod) APMC",
                "Devgadhbaria APMC", "Jhalod APMC", "Limkheda APMC", "Zalod(Sanjeli) APMC",
                "Zalod(Zalod) APMC"
            ],
            "Dang": [
                "Vaghai APMC"
            ],
            "Devbhumi Dwarka": [
                "Bhanvad APMC", "Jam Khambalia APMC"
            ],
            "Gandhinagar": [
                "Dehgam APMC", "Dehgam(Rekhiyal) APMC",
                "Kalol APMC", "Kalol(Veg,Market,Kalol) APMC",
                "Mansa APMC", "Mansa(Manas Veg Yard) APMC",
                "Radheja APMC", "Randheja(Chiloda) APMC",
                "Randheja(Veg.Market Gandhinagar) APMC"
            ],
            "Gir Somnath": [
                "Kodinar APMC", "Talalagir APMC", "Una APMC", "Veraval APMC"
            ],
            "Jamnagar": [
                "Bhatiya APMC", "Dhrol APMC", "Jam Jodhpur APMC", "Jamnagar APMC",
                "Jodiya APMC", "Kalawad APMC", "Lalpur APMC"
            ],
            "Junagarh": [
                "Bhesan APMC", "Junagadh APMC", "Junagadh(Veg,Yard, Junagadh) APMC",
                "Keshod APMC", "Kodinar(Dollasa) APMC", "Malia Hatina APMC",
                "Manavdar APMC", "Mangrol APMC", "Talala APMC", "Vanthli APMC",
                "Visavadar APMC"
            ],
            "Kachchh": [
                "Abdasa APMC", "Anjar APMC", "Anjar(Veg,Sub Yard) APMC",
                "Bachau APMC", "Bhuj APMC", "K.Mandvi APMC", "Mundra APMC",
                "Nakhatrana APMC", "Rapar APMC"
            ],
            "Kheda": [
                "Balasinor APMC", "Kapadanj(Moti Jaher) APMC", "Kapadvanj APMC",
                "Kathlal APMC", "Kheda APMC", "Kheda(Nayaka) APMC", "Matar APMC",
                "Matar(Limbasi) APMC", "Mehmadabad APMC", "Mehmadabad(Veg ,market) APMC",
                "Nadiad APMC", "Nadiyad(Chaklasi) APMC", "Nadiyad(Piplag) APMC",
                "Thasara APMC", "Thasara(Dokar) APMC", "Virpur APMC"
            ],
            "Mehsana": [
                "Becharaji APMC", "Kadi APMC", "Kadi(Kadi cotton Yard) APMC",
                "Mehsana APMC", "Mehsana(Jornang) APMC", "Mehsana(Mehsana Veg) APMC",
                "Unava APMC", "Unjha APMC", "Vadnagar APMC", "Vadnagar(Kheralu) APMC",
                "Vijapur APMC", "Vijapur(Gojjariya) APMC", "Vijapur(Kukarvada) APMC",
                "Vijapur(Ladol) APMC", "Vijapur(veg) APMC", "Visnagar APMC"
            ],
            "Morbi": [
                "APMC HALVAD", "Morbi APMC", "Vankaner APMC", "Vankaner(Sub yard) APMC"
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
                "Chansama APMC", "Harij APMC", "Patan APMC", "Patan(Veg,Yard Patan) APMC",
                "Radhanpur APMC", "Sami APMC", "Siddhpur APMC", "Varahi APMC"
            ],
            "Porbandar": [
                "Kutiyana APMC", "Porbandar APMC"
            ],
            "Rajkot": [
                "Dhoraji APMC", "Gondal APMC", "Gondal(Veg.market Gondal) APMC",
                "Jamkandorna APMC", "Jasdan APMC", "Jasdan(Vichhiya) APMC",
                "Jetpur(Dist.Rajkot) APMC", "Rajkot APMC", "Rajkot(Veg.Sub Yard) APMC",
                "Upleta APMC"
            ],
            "Sabarkantha": [
                "APMC Khedbrahma", "Bayad APMC", "Bayad(Demai) APMC",
                "Bayad(Sadamba) APMC", "Bhiloda APMC", "Dhansura APMC",
                "Himatnagar APMC", "Himatnagar(Gambhoi) APMC",
                "Himatnagar(Veg.Market Himatnagar) APMC", "Idar APMC", "Idar(Jadar) APMC",
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
                "S.Mandvi APMC", "Surat APMC"
            ],
            "Tapi": [
                "Nizar APMC", "Nizar(Kukarmuda) APMC", "Nizar(Pumkitalov) APMC",
                "Songadh APMC", "Songadh(Badarpada) APMC", "Songadh(Umrada) APMC",
                "Uchhal APMC", "Uchhal(Karod) APMC",
                "Valod APMC", "Valod(Buhari) APMC", "Vyara(Paati) APMC", "Vyara APMC"
            ],
            "Surendranagar": [
                "Chotila(Veg,Yard) APMC", "Chotila APMC", "Chotila(Veg,Yard Chotila) APMC",
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
                "Chikli(Khorgam) APMC", "Dharampur(veg) APMC", "Pardi APMC",
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
            const filtered = districts.filter(d => d.toLowerCase().includes(filterText.toLowerCase()));
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
            const filtered = currentAvailableMarkets.filter(m => m.toLowerCase().includes(filterText.toLowerCase()));
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
        let selectedCommodityGroups = ["Cereals", "Fibre Crops", "Oil Seeds", "Pulses", "Vegetables", "Others"];

        const commodityGroups = [
            "Cereals", "Fibre Crops", "Oil Seeds", "Pulses", "Vegetables", "Others"
        ];

        function renderCommodityGroups(filterText = '') {
            commodityGroupOptionsContainer.innerHTML = '';
            const filtered = commodityGroups.filter(g => g.toLowerCase().includes(filterText.toLowerCase()));
            const isLimitReached = selectedCommodityGroups.length >= 6;

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
                // Since limit is 6 and there are 6 groups, we can select all
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
        let selectedCommodities = [
            "Bajra(Pearl Millet/Cumbu)", "Barley(Jau)", "Jowar(Sorghum)",
            "Maize", "Paddy(Common)", "Ragi(Finger Millet)", "Wheat",
            "Cotton", "Jute",
            "Copra", "Groundnut", "Mustard", "Niger Seed(Ramtil)",
            "Safflower", "Sesamum(Sesame,Gingelly,Til)", "Soyabean",
            "Sunflower", "Sunflower Seed", "Toria",
            "Arhar(Tur/Red Gram)(Whole)", "Bengal Gram(Gram)(Whole)",
            "Black Gram(Urd Beans)(Whole)", "Green Gram(Moong)(Whole)",
            "Lentil(Masur)(Whole)",
            "Onion", "Potato", "Tomato",
            "Sugarcane"
        ];
        let currentAvailableCommodities = [];

        const commodityGroupCommodities = {
            "Cereals": [
                "Bajra(Pearl Millet/Cumbu)", "Barley(Jau)", "Jowar(Sorghum)",
                "Maize", "Paddy(Common)", "Ragi(Finger Millet)", "Wheat"
            ],
            "Fibre Crops": ["Cotton", "Jute"],
            "Oil Seeds": [
                "Copra", "Groundnut", "Mustard", "Niger Seed(Ramtil)",
                "Safflower", "Sesamum(Sesame,Gingelly,Til)", "Soyabean",
                "Sunflower", "Sunflower Seed", "Toria"
            ],
            "Pulses": [
                "Arhar(Tur/Red Gram)(Whole)", "Bengal Gram(Gram)(Whole)",
                "Black Gram(Urd Beans)(Whole)", "Green Gram(Moong)(Whole)",
                "Lentil(Masur)(Whole)"
            ],
            "Vegetables": ["Onion", "Potato", "Tomato"],
            "Others": ["Sugarcane"]
        };

        function renderCommodities(filterText = '') {
            commodityOptionsContainer.innerHTML = '';
            const filtered = currentAvailableCommodities.filter(c => c.toLowerCase().includes(filterText.toLowerCase()));
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
                if (selectedCommodityGroups.length >= 6) {
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