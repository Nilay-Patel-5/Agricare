<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Weather Dashboard | AgriCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&family=Noto+Sans+Gujarati:wght@400;500;700&family=Noto+Sans+Devanagari:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        .gu-text {
            font-family: 'Noto Sans Gujarati', sans-serif;
        }

        .hi-text {
            font-family: 'Noto Sans Devanagari', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }

        .weather-icon-lg {
            font-size: 4rem;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        .slide-up {
            animation: slideUp 0.5s ease-out forwards;
        }

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

        /* Modal Animation */
        .modal-enter {
            animation: modalFadeIn 0.3s ease-out forwards;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Loading Spinner */
        .loader {
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #10b981;
            width: 20px;
            height: 20px;
            -webkit-animation: spin 1s linear infinite;
            /* Safari */
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Error Message Animation */
        .error-shake {
            animation: shake 0.5s cubic-bezier(.36, .07, .19, .97) both;
        }

        @keyframes shake {

            10%,
            90% {
                transform: translate3d(-1px, 0, 0);
            }

            20%,
            80% {
                transform: translate3d(2px, 0, 0);
            }

            30%,
            50%,
            70% {
                transform: translate3d(-4px, 0, 0);
            }

            40%,
            60% {
                transform: translate3d(4px, 0, 0);
            }
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

        .farm-hero {
            background:
                linear-gradient(135deg, rgba(236, 253, 245, 0.85), rgba(209, 250, 229, 0.90)),
                url('assets/images/weather-bg-web.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>

<body class="text-gray-800 farm-hero min-h-screen">

    <div id="progressBar"></div>

    <!-- Access Modal -->
    <div id="accessModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl modal-enter relative">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl text-emerald-600 mx-auto mb-4">
                    <i class="fas fa-cloud-sun-rain"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-900" data-lang="modalTitle">Check Weather</h2>
                <p class="text-gray-500" data-lang="modalDesc">Get accurate forecasts for your farm.</p>
            </div>

            <form id="weatherForm" class="space-y-4">
                <!-- State Selection Removed (Hardcoded to Gujarat) -->
                <input type="hidden" id="stateSelect" value="Gujarat">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1" data-lang="labelCity">City / District</label>
                    <select id="citySelect" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium" required>
                        <!-- Populated via JS -->
                        <option value="">Select City</option>
                    </select>
                </div>
                
                <!-- Crop Selection Removed (Hardcoded to General) -->
                <input type="hidden" id="cropSelect" value="">

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 mt-2">
                    <span data-lang="btnForecast">Get Forecast</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

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
                    <a href="index.php#home"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all hover:underline hover:underline-offset-4 focus:underline focus:underline-offset-4 active:underline active:underline-offset-4 decoration-2"
                        data-lang="home">🏠 Home</a>
                    <a href="index.php#features"
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

                    <button onclick="resetLocation()" class="text-sm font-bold text-gray-500 hover:text-emerald-600 underline hidden lg:block border-l-2 border-gray-200 pl-4" data-lang="changeLoc">Change Location</button>

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

            <button onclick="resetLocation(); closeMobileMenu();" class="w-full text-center text-sm font-bold text-gray-500 hover:text-emerald-600 underline py-2 mb-4 bg-gray-50 rounded-lg" data-lang="changeLoc">Change Location</button>

            <nav class="space-y-4">
                <a href="index.php#home" onclick="closeMobileMenu()"
                    class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b">🏠 Home</a>
                <a href="index.php#features" onclick="closeMobileMenu()"
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

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-4 lg:px-12 max-w-7xl mx-auto min-h-screen">

        <!-- Error Container -->
        <div id="errorContainer" class="hidden max-w-4xl mx-auto mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md error-shake" role="alert">
            <p class="font-bold">Error</p>
            <p id="errorMessage">Something went wrong.</p>
        </div>

        <!-- Location Header -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4 slide-up" style="animation-delay: 0.1s;">
            <div>
                <div class="flex items-center gap-2 text-gray-500 font-medium mb-1">
                    <i class="fas fa-map-marker-alt text-emerald-500"></i>
                    <span id="displayLocation">Loading...</span>
                </div>
                <h1 class="text-4xl font-black text-gray-900" data-lang="dashboardTitle">Weather Dashboard</h1>
                <p class="text-gray-500 text-sm mt-1" id="currentDate">Update: Just now</p>
            </div>
            <button id="refreshBtn" onclick="fetchWeather(true)" class="bg-white border border-gray-200 hover:bg-emerald-50 text-emerald-700 px-4 py-2 rounded-lg font-bold shadow-sm transition-all flex items-center gap-2">
                <i class="fas fa-sync-alt" id="refreshIcon"></i> <span data-lang="btnRefresh">Refresh</span>
            </button>
        </div>

        <!-- Current Weather Card -->
        <div class="grid lg:grid-cols-3 gap-8 mb-12 slide-up" style="animation-delay: 0.2s;">
            <!-- Main Card -->
            <div class="lg:col-span-2 glass-card rounded-[2.5rem] p-8 lg:p-12 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-yellow-100 rounded-full blur-3xl opacity-50 -mr-16 -mt-16 group-hover:bg-yellow-200 transition-colors duration-700"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-4 mb-2">
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold uppercase tracking-wide" id="weatherConditionTag">--</span>
                        </div>
                        <div class="text-7xl lg:text-8xl font-black text-gray-900 tracking-tighter mb-2" id="tempDisplay">--°</div>
                        <p class="text-xl text-gray-500 font-medium"><span data-lang="feelsLike">Feels like</span> <span id="feelsLike">--°</span></p>
                    </div>

                    <div class="text-center">
                        <i class="fas fa-cloud-sun text-gray-300 weather-icon-lg animate-pulse" id="weatherIcon"></i>
                        <p class="text-lg font-bold text-gray-700 mt-4" id="conditionText">--</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 w-full md:w-auto">
                        <div class="bg-white/60 p-4 rounded-2xl text-center">
                            <div class="text-blue-500 mb-1"><i class="fas fa-tint"></i></div>
                            <div class="text-xl font-black text-gray-800" id="humidity">--%</div>
                            <div class="text-xs text-gray-500 font-bold" data-lang="humidity">Humidity</div>
                        </div>
                        <div class="bg-white/60 p-4 rounded-2xl text-center">
                            <div class="text-gray-500 mb-1"><i class="fas fa-wind"></i></div>
                            <div class="text-xl font-black text-gray-800" id="windSpeed">-- km/h</div>
                            <div class="text-xs text-gray-500 font-bold" data-lang="wind">Wind</div>
                        </div>
                        <div class="bg-white/60 p-4 rounded-2xl text-center col-span-2">
                            <div class="text-blue-400 mb-1"><i class="fas fa-cloud-rain"></i></div>
                            <div class="text-xl font-black text-gray-800" id="rainProb">--</div>
                            <div class="text-xs text-gray-500 font-bold" data-lang="rainChance">Rain Chance</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advisory Summary -->
            <div class="bg-emerald-600 rounded-[2.5rem] p-8 text-white flex flex-col justify-between shadow-xl relative overflow-hidden">
                <div class="absolute bottom-0 right-0 text-emerald-700 text-9xl opacity-20 -mb-4 -mr-4"><i class="fas fa-seedling"></i></div>

                <div class="relative z-10">
                    <h3 class="text-2xl font-black mb-6 flex items-center gap-2">
                        <i class="fas fa-robot"></i> <span data-lang="agriAdvisor">Agri-Advisor</span>
                    </h3>

                    <div id="quickAdvise" class="space-y-4">
                        <!-- Dynamic Content -->
                        <p class="opacity-75 text-sm">Loading advice...</p>
                    </div>
                </div>

                <!-- Crop focus removed -->
            </div>
        </div>

        <!-- 7-Day Forecast -->
        <div class="mb-16 slide-up" style="animation-delay: 0.3s;">
            <h3 class="text-xl font-black text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-emerald-500"></i> <span data-lang="forecastTitle">7-Day Forecast</span>
            </h3>
            <div class="flex gap-4 overflow-x-auto pb-4 snap-x hide-scroll" id="forecastContainer">
                <!-- Forecast Items via JS -->
                <div class="p-4 text-gray-500">Loading forecast...</div>
            </div>
        </div>

        <!-- Detailed Advisory Section -->
        <div class="grid md:grid-cols-1 gap-8 slide-up" style="animation-delay: 0.4s;">
            <div class="bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100">
                <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-2 border-b border-gray-100 pb-4">
                    <i class="fas fa-clipboard-list text-orange-500"></i> <span data-lang="advisoryTitle">🌾 Agricultural Advisory</span>
                </h3>
                <ul class="space-y-4" id="mainAdvisoryList">
                    <!-- Dynamic List -->
                </ul>
            </div>
        </div>

    </main>

    <!-- Footer -->
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
        // --- Translations Dictionary ---
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
                modalTitle: 'Check Weather',
                modalDesc: 'Get accurate forecasts for your farm.',
                labelState: 'State',
                labelCity: 'City / District',
                labelCrop: 'Select Crop (Optional)',
                cropGeneral: '-- General --',
                cropCotton: 'Cotton (Kapas)',
                cropGroundnut: 'Groundnut (Magfali)',
                cropWheat: 'Wheat (Gehu)',
                cropRice: 'Rice (Dangar)',
                cropSugarcane: 'Sugarcane',
                btnForecast: 'Get Forecast',
                changeLoc: 'Change Location',
                navHome: 'Home',
                dashboardTitle: 'Weather Dashboard',
                btnRefresh: 'Refresh',
                feelsLike: 'Feels like',
                humidity: 'Humidity',
                wind: 'Wind',
                rainChance: 'Rain Chance',
                agriAdvisor: 'Agri-Advisor',
                cropFocus: 'Crop Focus',
                forecastTitle: '7-Day Forecast',
                advisoryTitle: '🌾 Agricultural Advisory',
                tipsTitle: 'Specific Tips:',
                location: '⚲ Location: Gujarat, India',
                email: '✉ Contact: project@agricare.demo',
                phone: '🕻 Phone: Available on request',
                'footer-desc': 'Empowering farmers with data, connecting markets with transparency, and building a sustainable future.',
                solutions: 'Solutions',
                sol_advisory: 'Crop Advisory',
                sol_disease: 'Crop Disease Identification',
                sol_market: 'Market Connect',
                sol_weather: 'Weather Station',
                copyright: '© 2026 AgriCare. Made with ❤️ for Farmers.',
                loading: 'Loading...',
                updated: 'Updated:',
                justnow: 'Just now',
                errorTitle: 'Error',
                errorDefault: 'Something went wrong. Please check your connection or city name.'
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
                modalTitle: 'હવામાન તપાસો',
                modalDesc: 'તમારા ખેતર માટે સચોટ આગાહી મેળવો.',
                labelState: 'રાજ્ય',
                labelCity: 'શહેર / જિલ્લો',
                labelCrop: 'પાક પસંદ કરો (વૈકલ્પિક)',
                cropGeneral: '-- સામાન્ય --',
                cropCotton: 'કપાસ',
                cropGroundnut: 'મગફળી',
                cropWheat: 'ઘઉં',
                cropRice: 'ડાંગર',
                cropSugarcane: 'શેરડી',
                btnForecast: 'આગાહી મેળવો',
                changeLoc: 'સ્થાન બદલો',
                navHome: 'મુખ્ય',
                dashboardTitle: 'હવામાન ડેશબોર્ડ',
                btnRefresh: 'રિફ્રેશ',
                feelsLike: 'અનુભૂતિ',
                humidity: 'ભેજ',
                wind: 'પવન',
                rainChance: 'વરસાદની શક્યતા',
                agriAdvisor: 'કૃષિ સલાહકાર',
                cropFocus: 'પાક ફોકસ',
                forecastTitle: '૭ દિવસની આગાહી',
                advisoryTitle: '🌾 કૃષિ સલાહ',
                tipsTitle: 'ચોક્કસ ટીપ્સ:',
                location: '⚲ સ્થળ: ગુજરાત, ભારત',
                email: '✉ સંપર્ક: project@agricare.demo',
                phone: '🕻 ફોન: વિનંતી પર ઉપલબ્ધ',
                'footer-desc': 'ખેડૂતોને સશક્તિકરણ, પારદર્શિતા સાથે બજારોને જોડવું અને ટકાઉ ભવિષ્યનું નિર્માણ.',
                solutions: 'ઉકેલો',
                sol_advisory: 'પાક સલાહ',
                sol_disease: 'પાક રોગ ઓળખ',
                sol_market: 'બજાર જોડાણ',
                sol_weather: 'હવામાન સ્ટેશન',
                copyright: '© 2026 એગ્રીકેર. ખેડૂતો માટે ❤️ થી બનાવેલ.',
                loading: 'લોડિંગ...',
                updated: 'અપડેટ:',
                justnow: 'હમણાં જ',
                errorTitle: 'ભૂલ',
                errorDefault: 'કંઈક ખોટું થયું છે. કૃપા કરીને તમારું ઇન્ટરનેટ અથવા શહેરનું નામ તપાસો.'
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
                modalTitle: 'मौसम की जाँच करें',
                modalDesc: 'अपने खेत के लिए सटीक पूर्वानुमान प्राप्त करें।',
                labelState: 'राज्य',
                labelCity: 'शहर / जिला',
                labelCrop: 'फसल चुनें (वैकल्पिक)',
                cropGeneral: '-- सामान्य --',
                cropCotton: 'कपास',
                cropGroundnut: 'मूंगफली',
                cropWheat: 'गेहूं',
                cropRice: 'धान',
                cropSugarcane: 'गन्ना',
                btnForecast: 'पूर्वानुमान प्राप्त करें',
                changeLoc: 'स्थान बदलें',
                navHome: 'होम',
                dashboardTitle: 'मौसम डैशबोर्ड',
                btnRefresh: 'रिफ्रेश',
                feelsLike: 'महसूस',
                humidity: 'नमी',
                wind: 'हवा',
                rainChance: 'बारिश की संभावना',
                agriAdvisor: 'कृषि सलाहकार',
                cropFocus: 'फसल फोकस',
                forecastTitle: '7-दिवसीय पूर्वानुमान',
                advisoryTitle: '🌾 कृषि सलाह',
                tipsTitle: 'विशिष्ट सुझाव:',
                location: '⚲ स्थान: गुजरात, भारत',
                email: '✉ संपर्क: project@agricare.demo',
                phone: '🕻 फोन: अनुरोध पर उपलब्ध',
                'footer-desc': 'किसानों को डेटा के साथ सशक्त बनाना और एक स्थायी भविष्य का निर्माण करना।',
                solutions: 'समाधान',
                sol_advisory: 'फसल सलाह',
                sol_disease: 'फसल रोग पहचान',
                sol_market: 'बाज़ार संपर्क',
                sol_weather: 'मौसम स्टेशन',
                copyright: '© 2026 एग्रीकेर। किसानों के लिए ❤️ से बनाया गया।',
                loading: 'लोड हो रहा है...',
                updated: 'अपडेट:',
                justnow: 'अभी',
                errorTitle: 'त्रुटि',
                errorDefault: 'कुछ गलत हो गया। कृपया अपना कनेक्शन या शहर का नाम जांचें।'
            }
        };

        // --- Crop Key Map ---
        // Maps the `value` from SELECT to the translation KEY
        const cropKeyMap = {
            'Cotton': 'cropCotton',
            'Groundnut': 'cropGroundnut',
            'Wheat': 'cropWheat',
            'Rice': 'cropRice',
            'Sugarcane': 'cropSugarcane',
            '': 'cropGeneral'
        };

        // --- District Data (Gujarat Only with Translations) ---
        const stateDistricts = {
            'Gujarat': [
                { id: 'Ahmedabad', en: 'Ahmedabad', gu: 'અમદાવાદ', hi: 'अहमदाबाद' },
                { id: 'Amreli', en: 'Amreli', gu: 'અમરેલી', hi: 'अमरेली' },
                { id: 'Anand', en: 'Anand', gu: 'આણંદ', hi: 'आणंद' },
                { id: 'Banaskantha', en: 'Banaskantha', gu: 'બનાસકાંઠા', hi: 'बनासकांठा' },
                { id: 'Bharuch', en: 'Bharuch', gu: 'ભરૂચ', hi: 'भरूच' },
                { id: 'Bhavnagar', en: 'Bhavnagar', gu: 'ભાવનગર', hi: 'भावनगर' },
                { id: 'Botad', en: 'Botad', gu: 'બોટાદ', hi: 'बोटाद' },
                { id: 'Chhota Udaipur', en: 'Chhota Udaipur', gu: 'છોટા ઉદેપુર', hi: 'छोटा उदयपुर' },
                { id: 'Dahod', en: 'Dahod', gu: 'દાહોદ', hi: 'दाहोद' },
                { id: 'Dang', en: 'Dang', gu: 'ડાંગ', hi: 'डांग' },
                { id: 'Devbhoomi Dwarka', en: 'Devbhoomi Dwarka', gu: 'દેવભૂમિ દ્વારકા', hi: 'देवभूमि द्वारका' },
                { id: 'Gandhinagar', en: 'Gandhinagar', gu: 'ગાંધીનગર', hi: 'गांधीनगर' },
                { id: 'Gir Somnath', en: 'Gir Somnath', gu: 'ગીર સોમનાથ', hi: 'गिर सोमनाथ' },
                { id: 'Jamnagar', en: 'Jamnagar', gu: 'જામનગર', hi: 'जामनगर' },
                { id: 'Junagadh', en: 'Junagadh', gu: 'જૂનાગઢ', hi: 'जूनागढ़' },
                { id: 'Kheda', en: 'Kheda', gu: 'ખેડા', hi: 'खेड़ा' },
                { id: 'Kutch', en: 'Kutch', gu: 'કચ્છ', hi: 'कच्छ' },
                { id: 'Mahisagar', en: 'Mahisagar', gu: 'મહીસાગર', hi: 'महीसागर' },
                { id: 'Mehsana', en: 'Mehsana', gu: 'મહેસાણા', hi: 'मेहसाणा' },
                { id: 'Morbi', en: 'Morbi', gu: 'મોરબી', hi: 'मोरबी' },
                { id: 'Narmada', en: 'Narmada', gu: 'નર્મદા', hi: 'नर्मदा' },
                { id: 'Navsari', en: 'Navsari', gu: 'નવસારી', hi: 'नवसारी' },
                { id: 'Panchmahal', en: 'Panchmahal', gu: 'પંચમહાલ', hi: 'पंचमहाल' },
                { id: 'Patan', en: 'Patan', gu: 'પાટણ', hi: 'पाटन' },
                { id: 'Porbandar', en: 'Porbandar', gu: 'પોરબંદર', hi: 'पोरबंदर' },
                { id: 'Rajkot', en: 'Rajkot', gu: 'રાજકોટ', hi: 'राजकोट' },
                { id: 'Sabarkantha', en: 'Sabarkantha', gu: 'સાબરકાંઠા', hi: 'साबरकांठा' },
                { id: 'Surat', en: 'Surat', gu: 'સુરત', hi: 'सूरत' },
                { id: 'Surendranagar', en: 'Surendranagar', gu: 'સુરેન્દ્રનગર', hi: 'सुरेंद्रनगर' },
                { id: 'Tapi', en: 'Tapi', gu: 'તાપી', hi: 'तापी' },
                { id: 'Vadodara', en: 'Vadodara', gu: 'વડોદરા', hi: 'वडोदरा' },
                { id: 'Valsad', en: 'Valsad', gu: 'વલસાડ', hi: 'वलसाड' }
            ]
        };

        // --- Visual Crossing Weather Code Map (Crucial for Translations) ---
        // Codes based on Visual Crossing icon strings
        const weatherCodeMap = {
            'clear-day': { en: 'Clear Sky', gu: 'સ્વચ્છ આકાશ', hi: 'साफ आसमान', icon: 'fa-sun text-yellow-500' },
            'clear-night': { en: 'Clear Night', gu: 'સ્વચ્છ રાત', hi: 'साफ रात', icon: 'fa-moon text-blue-300' },
            'partly-cloudy-day': { en: 'Partly Cloudy', gu: 'આંશિક વાદળછાયું', hi: 'आंशिक रूप से बादल', icon: 'fa-cloud-sun text-yellow-400' },
            'partly-cloudy-night': { en: 'Partly Cloudy Night', gu: 'આંશિક વાદળછાયું', hi: 'आंशिक रूप से बादल', icon: 'fa-cloud-moon text-gray-400' },
            'cloudy': { en: 'Cloudy', gu: 'વાદળછાયું', hi: 'बादल छाए हुए', icon: 'fa-cloud text-gray-500' },
            'fog': { en: 'Fog', gu: 'ધૂમ્મસ', hi: 'कोहरा', icon: 'fa-smog text-gray-400' },
            'wind': { en: 'Windy', gu: 'પવન', hi: 'हवादार', icon: 'fa-wind text-gray-400' },
            'rain': { en: 'Rain', gu: 'વરસાદ', hi: 'बारिश', icon: 'fa-cloud-showers-heavy text-blue-500' },
            'snow': { en: 'Snow', gu: 'બરફ', hi: 'बर्फ', icon: 'fa-snowflake text-blue-200' },
            'showers-day': { en: 'Showers', gu: 'ઝાપટા', hi: 'बौछारें', icon: 'fa-cloud-sun-rain text-blue-400' },
            'showers-night': { en: 'Showers Night', gu: 'ઝાપટા', hi: 'बौछारें', icon: 'fa-cloud-moon-rain text-blue-400' },
            'thunder-rain': { en: 'Thunderstorm', gu: 'વાવાઝોડું', hi: 'आंधी', icon: 'fa-bolt text-yellow-600' },
            'thunder-showers-day': { en: 'Thunderstorm', gu: 'વાવાઝોડું', hi: 'आंधी', icon: 'fa-bolt text-yellow-600' },
            'thunder-showers-night': { en: 'Thunderstorm', gu: 'વાવાઝોડું', hi: 'आंधी', icon: 'fa-bolt text-yellow-600' }
        };

        // Fallback for unmapped codes
        const defaultWeather = {
            en: 'Unknown',
            gu: 'અજ્ઞાત',
            hi: 'अज्ञात',
            icon: 'fa-cloud text-gray-300'
        };
        
        // --- API KEY ---
        const API_KEY = '12c5d559d7c44638880183516261603';

        // --- State Management ---
        const state = {
            location: localStorage.getItem('agri_location') || null,
            lat: localStorage.getItem('agri_lat') || null,
            lon: localStorage.getItem('agri_lon') || null,
            crop: localStorage.getItem('agri_crop') || '',
            lang: 'en',
            weatherData: null,
            isLoading: false
        };

        // --- Open-Meteo API Functions ---

        async function getCoordinates(city) {
            // Geocoding API (Open-Meteo)
            try {
                const response = await fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${city}&count=1&country=IN&language=en&format=json`);
                const data = await response.json();
                if (data.results && data.results.length > 0) {
                    return {
                        lat: data.results[0].latitude,
                        lon: data.results[0].longitude,
                        name: data.results[0].name
                    };
                }
                throw new Error('City not found');
            } catch (error) {
                console.error("Geocoding Error:", error);
                throw error; // Re-throw for showError logic
            }
        }

        async function getWeather(lat, lon) {
            // Forecast API (WeatherAPI)
            try {
                const url = `https://api.weatherapi.com/v1/forecast.json?key=${API_KEY}&q=${lat},${lon}&days=7&aqi=no&alerts=no`;
                const response = await fetch(url);
                if (!response.ok) throw new Error('Weather fetch failed');
                return await response.json();
            } catch (error) {
                console.error("Weather Fetch Error:", error);
                throw error;
            }
        }

        // --- Error Handling ---
        function showError(msg) {
            const container = document.getElementById('errorContainer');
            const msgEl = document.getElementById('errorMessage');
            const errorTitle = translations[state.lang]['errorTitle'] || 'Error';

            container.classList.remove('hidden');
            container.querySelector('p.font-bold').innerText = errorTitle;
            msgEl.innerText = msg || translations[state.lang]['errorDefault'];

            // Auto-hide after 5 seconds
            setTimeout(() => {
                container.classList.add('hidden');
            }, 5000);
        }

        function hideError() {
            document.getElementById('errorContainer').classList.add('hidden');
        }

        // --- UI Rendering ---

        function renderWeather() {
            const data = state.weatherData;
            if (!data) return;

            const lang = state.lang;
            const current = data.current;
            const daily = data.forecast.forecastday;

            // 1. Current Conditions
            document.getElementById('tempDisplay').innerText = `${Math.round(current.temp_c)}°c`;
            document.getElementById('feelsLike').innerText = `${Math.round(current.feelslike_c)}°c`;
            document.getElementById('humidity').innerText = `${current.humidity}%`;
            document.getElementById('windSpeed').innerText = `${current.wind_kph} km/h`;

            // Rain Probability
            const rainPercent = daily[0].day.daily_chance_of_rain || 0;
            document.getElementById('rainProb').innerText = `${rainPercent}%`;

            // Translated Condition Text
            const conditionText = current.condition.text;
            document.getElementById('weatherConditionTag').innerText = conditionText;
            document.getElementById('conditionText').innerText = conditionText;

            // Icon Update
            const iconEl = document.getElementById('weatherIcon');
            // Safely overriding the FontAwesome structure with a background image of the WeatherAPI icon
            iconEl.className = `inline-block animate-pulse mb-6 drop-shadow-lg`;
            iconEl.style.width = '120px';
            iconEl.style.height = '120px';
            iconEl.style.backgroundImage = `url('https:${current.condition.icon}')`;
            iconEl.style.backgroundSize = 'contain';
            iconEl.style.backgroundRepeat = 'no-repeat';
            iconEl.style.backgroundPosition = 'center';

            // 2. Date & Time (Localized)
            const now = new Date();
            const timeStr = now.toLocaleTimeString(lang === 'gu' ? 'gu-IN' : (lang === 'hi' ? 'hi-IN' : 'en-US'));
            const dateStr = now.toLocaleDateString(lang === 'gu' ? 'gu-IN' : (lang === 'hi' ? 'hi-IN' : 'en-US'));
            const updateLabel = translations[lang]['updated'];
            document.getElementById('currentDate').innerText = `${updateLabel} ${dateStr} ${timeStr}`;
            
            // Translate Location Name if matched in stateDistricts
            const districtObj = stateDistricts['Gujarat'].find(d => d.id === state.location || d.en === state.location);
            const translatedLocation = districtObj ? (districtObj[lang] || districtObj.en) : state.location;
            document.getElementById('displayLocation').innerText = translatedLocation;

            // Crop Focus Text logic removed
            
            // 3. Forecast Rendering
            const forecastContainer = document.getElementById('forecastContainer');
            forecastContainer.innerHTML = '';

            // We loop through 7 days provided by WeatherAPI.
            for (let i = 0; i < 7; i++) {
                const dayData = daily[i];
                if (!dayData) continue;
                
                const dateRaw = dayData.date;
                const maxTemp = Math.round(dayData.day.maxtemp_c);
                const conditionStr = dayData.day.condition.text;
                const iconUrl = dayData.day.condition.icon;

                // Format Date
                const fDateObj = new Date(dateRaw);
                const fDateStr = fDateObj.toLocaleDateString(lang === 'gu' ? 'gu-IN' : (lang === 'hi' ? 'hi-IN' : 'en-US'), {
                    weekday: 'short',
                    day: 'numeric'
                });

                forecastContainer.innerHTML += `
                    <div class="min-w-[120px] bg-white p-4 rounded-2xl shadow-sm text-center border border-gray-100 flex-shrink-0 transition-transform hover:scale-105">
                        <p class="text-sm font-bold text-gray-500 mb-2">${fDateStr}</p>
                        <img src="https:${iconUrl}" alt="${conditionStr}" class="mx-auto w-12 h-12 mb-2 drop-shadow-sm">
                        <p class="text-xs text-gray-400 mb-1 h-8 overflow-hidden">${conditionStr}</p>
                        <p class="text-xl font-black text-gray-800">${maxTemp}°</p>
                    </div>
                 `;
            }

            // 4. Advisories
            renderAdvisories(current, rainPercent, lang);
        }

        function renderAdvisories(current, rainPercent, lang) {
            const rules = [];

            // Helper for translated strings
            const t = (en, gu, hi) => lang === 'gu' ? gu : (lang === 'hi' ? hi : en);

            // General Rules
            if (rainPercent > 0 || current.condition.text.toLowerCase().includes('rain') || current.condition.text.toLowerCase().includes('shower')) {
                rules.push({
                    text: t("Rain expected. Postpone irrigation.", "વરસાદની આગાહી છે. પિયત મુલતવી રાખો.", "बारिश की उम्मीद है। सिंचाई स्थगित करें।"),
                    type: "warning"
                });
            } else {
                if (current.temp_c > 35) {
                    rules.push({
                        text: t("High heat. Irrigate in early morning.", "વધારે ગરમી છે. વહેલી સવારે પિયત આપો.", "अधिक गर्मी है। सुबह जल्दी सिंचाई करें।"),
                        type: "warning"
                    });
                } else {
                    rules.push({
                        text: t("Conditions good for field work.", "ખેતીકામ માટે વાતાવરણ સાનુકૂળ છે.", "खेत के काम के लिए मौसम अनुकूल है।"),
                        type: "success"
                    });
                }
            }

            if (current.wind_kph > 20) {
                rules.push({
                    text: t("High winds. Avoid spraying.", "વધારે પવન છે. દવાનો છંટકાવ ટાળો.", "तेज हवाएं। छिड़काव से बचें।"),
                    type: "danger"
                });
            }

            if (current.humidity > 75) {
                rules.push({
                    text: t("High humidity. Check for fungus.", "ભેજનું પ્રમાણ વધારે છે. ફૂગ માટે તપાસો.", "उच्च आर्द्रता। कवक की जाँच करें।"),
                    type: "info"
                });
            }

            // Crop specific logic removed

            // Render Lists
            const quickContainer = document.getElementById('quickAdvise');
            quickContainer.innerHTML = '';
            rules.slice(0, 2).forEach(item => {
                quickContainer.innerHTML += `<div class="bg-white/20 p-3 rounded-lg backdrop-blur-sm border border-white/30"><p class="font-medium text-sm leading-snug">${item.text}</p></div>`;
            });

            const mainList = document.getElementById('mainAdvisoryList');
            mainList.innerHTML = '';
            rules.forEach(item => {
                let colorClass = "bg-green-100 text-green-700";
                let iconClass = "fa-check-circle";
                if (item.type === 'warning') {
                    colorClass = "bg-yellow-100 text-yellow-700";
                    iconClass = "fa-exclamation-triangle";
                }
                if (item.type === 'danger') {
                    colorClass = "bg-red-100 text-red-700";
                    iconClass = "fa-times-circle";
                }
                if (item.type === 'info') {
                    colorClass = "bg-blue-100 text-blue-700";
                    iconClass = "fa-info-circle";
                }

                mainList.innerHTML += `
                    <li class="flex gap-4 items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="w-8 h-8 rounded-full ${colorClass} flex items-center justify-center flex-shrink-0 mt-1"><i class="fas ${iconClass}"></i></div>
                        <p class="text-gray-700 font-medium leading-relaxed">${item.text}</p>
                    </li>`;
            });
        }

        // --- Logic Control ---

        async function fetchWeather(forceRefresh = false) {
            if (state.isLoading) return;

            // Check Cache (5 min)
            if (!forceRefresh) {
                const lastFetch = localStorage.getItem('agri_last_fetch');
                const cachedDataStr = localStorage.getItem('agri_weather_data');
                if (lastFetch && cachedDataStr) {
                    const elapsed = Date.now() - parseInt(lastFetch);
                    if (elapsed < 5 * 60 * 1000) { // 5 minutes
                        // Use Cache
                        state.weatherData = JSON.parse(cachedDataStr);
                        renderWeather();
                        return; // EXIT EARLY
                    }
                }
            }

            // UI Loading State
            state.isLoading = true;
            hideError();
            const refreshBtn = document.getElementById('refreshBtn');
            const refreshIcon = document.getElementById('refreshIcon');
            const btnText = refreshBtn.querySelector('span');

            refreshIcon.classList.add('fa-spin');
            btnText.innerText = translations[state.lang]['loading'];
            refreshBtn.classList.add('opacity-75', 'cursor-not-allowed');

            try {
                // If location changed or coords missing, get coords first
                if (forceRefresh || !state.lat || !state.lon) {
                    const coords = await getCoordinates(state.location);
                    if (coords) {
                        state.lat = coords.lat;
                        state.lon = coords.lon;
                        state.location = coords.name; // Use canonical name
                        localStorage.setItem('agri_location', state.location);
                        localStorage.setItem('agri_lat', state.lat);
                        localStorage.setItem('agri_lon', state.lon);
                    } else {
                        // Error already handled/thrown in getCoordinates
                        // Just stop
                        return;
                    }
                }

                // Get Weather
                const data = await getWeather(state.lat, state.lon);
                if (data) {
                    state.weatherData = data;
                    // Save to Cache
                    localStorage.setItem('agri_weather_data', JSON.stringify(data));
                    localStorage.setItem('agri_last_fetch', Date.now());

                    renderWeather();
                }

            } catch (error) {
                console.error("Workflow failed", error);
                showError(translations[state.lang]['errorDefault']);
            } finally {
                // Reset UI State
                state.isLoading = false;
                refreshIcon.classList.remove('fa-spin');
                btnText.innerText = translations[state.lang]['btnRefresh'];
                refreshBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        }

        function changeLang(lang) {
            localStorage.setItem('agricare_lang', lang);
            state.lang = lang;

            // Update Buttons logic...
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow', 'text-emerald-700');
                btn.classList.add('text-gray-500');
            });
            document.querySelectorAll(`button[data-lang-key="${lang}"]`).forEach(btn => {
                btn.classList.add('bg-white', 'shadow', 'text-emerald-700');
                btn.classList.remove('text-gray-500');
            });

            // Update Static Text
            document.querySelectorAll('[data-lang]').forEach(el => {
                const key = el.getAttribute('data-lang');
                if (translations[lang] && translations[lang][key]) {
                    el.innerText = translations[lang][key];
                }
            });

            // Update Body Font
            const body = document.body;
            body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') body.classList.add('gu-text');
            if (lang === 'hi') body.classList.add('hi-text');

            // RE-RENDER Weather to update dynamic translations
            if (state.weatherData) {
                renderWeather();
            }

            // RE-RENDER dropdown if visible
            populateCities();
        }

        // --- Mobile Menu Logic ---
        const mobileMenu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        const menuBtn = document.getElementById('mobileMenuBtn');

        if (menuBtn) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                overlay.classList.add('opacity-100');
            });
        }

        function closeMobileMenu() {
            if (mobileMenu) mobileMenu.classList.add('translate-x-full');
            if (overlay) overlay.classList.add('hidden');
        }

        if (overlay) {
            overlay.addEventListener('click', closeMobileMenu);
        }

        function resetLocation() {
            localStorage.removeItem('agri_location');
            localStorage.removeItem('agri_lat');
            localStorage.removeItem('agri_lon');
            localStorage.removeItem('agri_crop');
            // Clear cache too
            localStorage.removeItem('agri_weather_data');
            localStorage.removeItem('agri_last_fetch');
            window.location.reload();
        }

        // --- Init ---
        // Access Control Logic
        if (!state.location) {
            document.getElementById('accessModal').classList.remove('hidden');
        } else {
            fetchWeather();
        }

        // --- Dynamic City Dropdown Logic ---
        const citySelect = document.getElementById('citySelect');

        function populateCities() {
            // Hardcoded to Gujarat
            const districts = stateDistricts['Gujarat'] || [];
            
            // "Select City" placeholder translation
            const selectPlaceholderInfo = {
                en: 'Select City / District',
                gu: 'શહેર / જિલ્લો પસંદ કરો',
                hi: 'शहर / जिला चुनें'
            };
            const placeholderText = selectPlaceholderInfo[state.lang] || selectPlaceholderInfo.en;

            // Retain previously selected value (if any)
            const currentValue = citySelect.value;

            citySelect.innerHTML = `<option value="">${placeholderText}</option>`;
            districts.forEach(district => {
                const displayName = district[state.lang] || district.en;
                const isSelected = currentValue === district.id ? 'selected' : '';
                citySelect.innerHTML += `<option value="${district.id}" ${isSelected}>${displayName}</option>`;
            });
        }

        // Initial Population
        populateCities();

        // Form Handler
        document.getElementById('weatherForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const city = citySelect.value; // Get value from SELECT, not INPUT
            const crop = document.getElementById('cropSelect').value;

            if (city) {
                state.location = city;
                state.crop = crop;
                localStorage.setItem('agri_location', city);
                localStorage.setItem('agri_crop', crop);
                document.getElementById('accessModal').classList.add('hidden');
                fetchWeather(); // This will trigger geocoding
            } else {
                alert("Please select a city.");
            }
        });

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

        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const initialLang = urlParams.get('lang') || localStorage.getItem('agricare_lang') || 'en';
            changeLang(initialLang);
        });
    </script>
</body>

</html>