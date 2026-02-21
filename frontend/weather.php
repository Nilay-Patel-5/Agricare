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
    </style>
</head>

<body class="text-gray-800">

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
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1" data-lang="labelState">State</label>
                    <select id="stateSelect" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium">
                        <option value="Gujarat">Gujarat</option>
                        <option value="Maharashtra">Maharashtra</option>
                        <option value="Rajasthan">Rajasthan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1" data-lang="labelCity">City / District</label>
                    <select id="citySelect" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium" required>
                        <!-- Populated via JS -->
                        <option value="">Select City</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1" data-lang="labelCrop">Select Crop (Optional)</label>
                    <select id="cropSelect" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium">
                        <option value="" data-lang="cropGeneral">-- General --</option>
                        <option value="Cotton" data-lang="cropCotton">Cotton (Kapas)</option>
                        <option value="Groundnut" data-lang="cropGroundnut">Groundnut (Magfali)</option>
                        <option value="Wheat" data-lang="cropWheat">Wheat (Gehu)</option>
                        <option value="Rice" data-lang="cropRice">Rice (Dangar)</option>
                        <option value="Sugarcane" data-lang="cropSugarcane">Sugarcane</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 mt-2">
                    <span data-lang="btnForecast">Get Forecast</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Header -->
    <header class="fixed top-0 w-full bg-white/95 backdrop-blur-md shadow-sm border-b-4 border-emerald-500 z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-4 flex justify-between items-center">
            <a href="index.html" class="flex items-center gap-4 group">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 via-orange-500 to-emerald-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg">
                    <i class="fas fa-wheat-awn"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-800 tracking-tight" data-lang="logo">AgriCare</h1>
                </div>
            </a>

            <div class="flex items-center gap-4">
                <!-- Language Switcher -->
                <div class="hidden sm:flex bg-gray-100 p-1 rounded-xl">
                    <button onclick="changeLang('en')" class="lang-btn px-3 py-1 text-sm font-bold rounded-lg transition-all active bg-white shadow text-emerald-700" data-lang-key="en">EN</button>
                    <button onclick="changeLang('gu')" class="lang-btn px-3 py-1 text-sm font-bold rounded-lg text-gray-500 hover:text-emerald-700 transition-all" data-lang-key="gu">ગુ</button>
                    <button onclick="changeLang('hi')" class="lang-btn px-3 py-1 text-sm font-bold rounded-lg text-gray-500 hover:text-emerald-700 transition-all" data-lang-key="hi">हिं</button>
                </div>

                <button onclick="resetLocation()" class="text-sm font-bold text-gray-500 hover:text-emerald-600 underline" data-lang="changeLoc">Change Location</button>
                <a href="index.html" class="flex items-center gap-2 text-emerald-600 font-bold hover:text-emerald-800 transition-colors">
                    <i class="fas fa-home"></i> <span data-lang="navHome">Home</span>
                </a>
            </div>
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

                <div class="mt-8 relative z-10">
                    <div class="bg-emerald-700/50 p-4 rounded-xl backdrop-blur-sm border border-emerald-500/30">
                        <p class="text-sm font-medium opacity-90 mb-1" data-lang="cropFocus">Crop Focus</p>
                        <div class="font-bold text-xl" id="cropFocus">General</div>
                    </div>
                </div>
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
        <div class="grid md:grid-cols-2 gap-8 slide-up" style="animation-delay: 0.4s;">
            <div class="bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100">
                <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-2 border-b border-gray-100 pb-4">
                    <i class="fas fa-clipboard-list text-orange-500"></i> <span data-lang="advisoryTitle">🌾 Agricultural Advisory</span>
                </h3>
                <ul class="space-y-4" id="mainAdvisoryList">
                    <!-- Dynamic List -->
                </ul>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100">
                <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-2 border-b border-gray-100 pb-4">
                    <i class="fas fa-leaf text-green-500"></i> <span data-lang="tipsTitle">Specific Tips:</span> <span id="cropTitle" class="text-emerald-600">General</span>
                </h3>
                <ul class="space-y-4" id="cropAdvisoryList">
                    <!-- Dynamic List -->
                </ul>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 text-center border-t-8 border-emerald-600">
        <p data-lang="copyright">© 2026 AgriCare. Made with ❤️ for Farmers.</p>
    </footer>

    <script>
        // --- Translations Dictionary ---
        const translations = {
            en: {
                logo: 'AgriCare',
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
                copyright: '© 2026 AgriCare. Made with ❤️ for Farmers.',
                loading: 'Loading...',
                updated: 'Updated:',
                justnow: 'Just now',
                errorTitle: 'Error',
                errorDefault: 'Something went wrong. Please check your connection or city name.'
            },
            gu: {
                logo: 'એગ્રીકેર',
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
                copyright: '© 2026 એગ્રીકેર. ખેડૂતો માટે ❤️ થી બનાવેલ.',
                loading: 'લોડિંગ...',
                updated: 'અપડેટ:',
                justnow: 'હમણાં જ',
                errorTitle: 'ભૂલ',
                errorDefault: 'કંઈક ખોટું થયું છે. કૃપા કરીને તમારું ઇન્ટરનેટ અથવા શહેરનું નામ તપાસો.'
            },
            hi: {
                logo: 'एग्रीकेयर',
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
                copyright: '© 2026 एग्रीकेयर। किसानों के लिए ❤️ से बनाया गया।',
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

        // --- District Data ---
        const stateDistricts = {
            'Gujarat': [
                'Ahmedabad', 'Amreli', 'Anand', 'Banaskantha', 'Bharuch', 'Bhavnagar', 'Botad',
                'Chhota Udaipur', 'Dahod', 'Dang', 'Devbhoomi Dwarka', 'Gandhinagar', 'Gir Somnath',
                'Jamnagar', 'Junagadh', 'Kheda', 'Kutch', 'Mahisagar', 'Mehsana', 'Morbi',
                'Narmada', 'Navsari', 'Panchmahal', 'Patan', 'Porbandar', 'Rajkot', 'Sabarkantha',
                'Surat', 'Surendranagar', 'Tapi', 'Vadodara', 'Valsad'
            ],
            'Maharashtra': [
                'Ahmednagar', 'Akola', 'Amravati', 'Aurangabad', 'Beed', 'Bhandara', 'Buldhana',
                'Chandrapur', 'Dhule', 'Gadchiroli', 'Gondia', 'Hingoli', 'Jalgaon', 'Jalna',
                'Kolhapur', 'Latur', 'Mumbai', 'Nagpur', 'Nanded', 'Nandurbar', 'Nashik',
                'Osmanabad', 'Palghar', 'Parbhani', 'Pune', 'Raigad', 'Ratnagiri', 'Sangli',
                'Satara', 'Sindhudurg', 'Solapur', 'Thane', 'Wardha', 'Washim', 'Yavatmal'
            ],
            'Rajasthan': [
                'Ajmer', 'Alwar', 'Banswara', 'Baran', 'Barmer', 'Bharatpur', 'Bhilwara', 'Bikaner',
                'Bundi', 'Chittorgarh', 'Churu', 'Dausa', 'Dholpur', 'Dungarpur', 'Hanumangarh',
                'Jaipur', 'Jaisalmer', 'Jalore', 'Jhalawar', 'Jhunjhunu', 'Jodhpur', 'Karauli',
                'Kota', 'Nagaur', 'Pali', 'Pratapgarh', 'Rajsamand', 'Sawai Madhopur', 'Sikar',
                'Sirohi', 'Sri Ganganagar', 'Tonk', 'Udaipur'
            ]
        };

        // --- WMO Weather Code Map (Crucial for Translations) ---
        // Codes: 0=Clear, 1-3=Cloudy, 45-48=Fog, 51-67=Drizzle/Rain, 71-77=Snow, 80-82=Showers, 95-99=Thunderstorm
        const weatherCodeMap = {
            0: {
                en: 'Clear Sky',
                gu: 'સ્વચ્છ આકાશ',
                hi: 'साफ आसमान',
                icon: 'fa-sun text-yellow-500'
            },
            1: {
                en: 'Mainly Clear',
                gu: 'મુખ્યત્વે સ્વચ્છ',
                hi: 'मुख्य रूप से साफ',
                icon: 'fa-cloud-sun text-yellow-400'
            },
            2: {
                en: 'Partly Cloudy',
                gu: 'આંશિક વાદળછાયું',
                hi: 'आंशिक रूप से बादल',
                icon: 'fa-cloud-sun text-gray-400'
            },
            3: {
                en: 'Overcast',
                gu: 'વાદળછાયું',
                hi: 'बादल छाए हुए',
                icon: 'fa-cloud text-gray-500'
            },
            45: {
                en: 'Fog',
                gu: 'ધૂમ્મસ',
                hi: 'कोहरा',
                icon: 'fa-smog text-gray-400'
            },
            48: {
                en: 'Depositing Rime Fog',
                gu: 'ગાઢ ધૂમ્મસ',
                hi: 'घना कोहरा',
                icon: 'fa-smog text-gray-400'
            },
            51: {
                en: 'Light Drizzle',
                gu: 'હળવો વરસાદ',
                hi: 'हल्की बूंदाबांदी',
                icon: 'fa-cloud-rain text-blue-300'
            },
            53: {
                en: 'Moderate Drizzle',
                gu: 'વરસાદ',
                hi: 'बूंदाबांदी',
                icon: 'fa-cloud-rain text-blue-400'
            },
            55: {
                en: 'Dense Drizzle',
                gu: 'ભારે વરસાદ',
                hi: 'घनी बूंदाबांदी',
                icon: 'fa-cloud-rain text-blue-500'
            },
            61: {
                en: 'Slight Rain',
                gu: 'હળવો વરસાદ',
                hi: 'हल्की बारिश',
                icon: 'fa-cloud-showers-heavy text-blue-400'
            },
            63: {
                en: 'Moderate Rain',
                gu: 'મધ્યમ વરસાદ',
                hi: 'मध्यम बारिश',
                icon: 'fa-cloud-showers-heavy text-blue-500'
            },
            65: {
                en: 'Heavy Rain',
                gu: 'ભારે વરસાદ',
                hi: 'भारी बारिश',
                icon: 'fa-cloud-showers-heavy text-blue-600'
            },
            80: {
                en: 'Light Showers',
                gu: 'હળવા ઝાપટા',
                hi: 'हल्की बौछारें',
                icon: 'fa-cloud-sun-rain text-blue-400'
            },
            81: {
                en: 'Moderate Showers',
                gu: 'મધ્યમ ઝાપટા',
                hi: 'मध्यम बौछारें',
                icon: 'fa-cloud-sun-rain text-blue-500'
            },
            82: {
                en: 'Violent Showers',
                gu: 'ભારે ઝાપટા',
                hi: 'तेज बौछारें',
                icon: 'fa-cloud-showers-water text-blue-700'
            },
            95: {
                en: 'Thunderstorm',
                gu: 'વાવાઝોડું',
                hi: 'आंधी',
                icon: 'fa-bolt text-yellow-600'
            },
            96: {
                en: 'Thunderstorm with Hail',
                gu: 'કરા સાથે વાવાઝોડું',
                hi: 'ओलावृष्टि के साथ आंधी',
                icon: 'fa-bolt text-red-500'
            },
            99: {
                en: 'Heavy Thunderstorm',
                gu: 'ભારે વાવાઝોડું',
                hi: 'भारी आंधी',
                icon: 'fa-bolt text-red-600'
            }
        };

        // Fallback for unmapped codes
        const defaultWeather = {
            en: 'Unknown',
            gu: 'અજ્ઞાત',
            hi: 'अज्ञात',
            icon: 'fa-question-circle text-gray-300'
        };

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
                const response = await fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${city}&count=1&language=en&format=json`);
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
            // Forecast API (Open-Meteo) - Free, No Key
            // Added precipitation_probability_max to daily forecast
            try {
                const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,rain,weather_code,wind_speed_10m&daily=weather_code,temperature_2m_max,temperature_2m_min,precipitation_sum,precipitation_probability_max&timezone=auto`;
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
            const daily = data.daily;

            // 1. Current Conditions
            // Get mapped text and icon based on weather code
            const wCode = current.weather_code;
            const wInfo = weatherCodeMap[wCode] || defaultWeather;

            document.getElementById('tempDisplay').innerText = `${Math.round(current.temperature_2m)}°c`;
            document.getElementById('feelsLike').innerText = `${Math.round(current.apparent_temperature)}°c`;
            document.getElementById('humidity').innerText = `${current.relative_humidity_2m}%`;
            document.getElementById('windSpeed').innerText = `${current.wind_speed_10m} km/h`;

            // Rain Probability (from Daily Max) - Check if exists, else fallback
            const rainPercent = (daily && daily.precipitation_probability_max && daily.precipitation_probability_max.length > 0) ?
                daily.precipitation_probability_max[0] :
                (current.precipitation > 0 ? 100 : 0);
            document.getElementById('rainProb').innerText = `${rainPercent}%`;

            // Translated Condition Text
            document.getElementById('weatherConditionTag').innerText = wInfo[lang];
            document.getElementById('conditionText').innerText = wInfo[lang];

            // Icon Update
            const iconEl = document.getElementById('weatherIcon');
            // Reset classes and add new ones
            iconEl.className = `fas weather-icon-lg animate-pulse ${wInfo.icon}`;

            // 2. Date & Time (Localized)
            const now = new Date();
            const timeStr = now.toLocaleTimeString(lang === 'gu' ? 'gu-IN' : (lang === 'hi' ? 'hi-IN' : 'en-US'));
            const dateStr = now.toLocaleDateString(lang === 'gu' ? 'gu-IN' : (lang === 'hi' ? 'hi-IN' : 'en-US'));
            const updateLabel = translations[lang]['updated'];
            document.getElementById('currentDate').innerText = `${updateLabel} ${dateStr} ${timeStr}`;
            document.getElementById('displayLocation').innerText = state.location;

            // Crop Focus Text - TRANSLATED
            const cropKey = cropKeyMap[state.crop] || 'cropGeneral';
            const cropName = translations[lang][cropKey] || state.crop;
            document.getElementById('cropFocus').innerText = cropName;
            document.getElementById('cropTitle').innerText = cropName;

            // 3. Forecast Rendering
            const forecastContainer = document.getElementById('forecastContainer');
            forecastContainer.innerHTML = '';

            // Open-Meteo returns arrays for daily data. We loop through 7 days.
            for (let i = 0; i < 7; i++) {
                // Skip past days if API returns them, usually index 0 is today
                const dateRaw = daily.time[i];
                const maxTemp = Math.round(daily.temperature_2m_max[i]);
                const fCode = daily.weather_code[i];
                const fInfo = weatherCodeMap[fCode] || defaultWeather;

                // Format Date
                const fDateObj = new Date(dateRaw);
                const fDateStr = fDateObj.toLocaleDateString(lang === 'gu' ? 'gu-IN' : (lang === 'hi' ? 'hi-IN' : 'en-US'), {
                    weekday: 'short',
                    day: 'numeric'
                });

                forecastContainer.innerHTML += `
                    <div class="min-w-[120px] bg-white p-4 rounded-2xl shadow-sm text-center border border-gray-100 flex-shrink-0 transition-transform hover:scale-105">
                        <p class="text-sm font-bold text-gray-500 mb-2">${fDateStr}</p>
                        <i class="fas ${fInfo.icon.replace('text-', 'text-')} text-2xl mb-2"></i>
                        <p class="text-xs text-gray-400 mb-1 h-8 overflow-hidden">${fInfo[lang]}</p>
                        <p class="text-xl font-black text-gray-800">${maxTemp}°</p>
                    </div>
                 `;
            }

            // 4. Advisories
            renderAdvisories(current, state.crop, lang);
        }

        function renderAdvisories(current, crop, lang) {
            const rules = [];
            const cropRules = [];

            // Helper for translated strings
            const t = (en, gu, hi) => lang === 'gu' ? gu : (lang === 'hi' ? hi : en);

            // General Rules
            if (current.precipitation > 0 || current.weather_code >= 51) {
                rules.push({
                    text: t("Rain expected. Postpone irrigation.", "વરસાદની આગાહી છે. પિયત મુલતવી રાખો.", "बारिश की उम्मीद है। सिंचाई स्थगित करें।"),
                    type: "warning"
                });
            } else {
                if (current.temperature_2m > 35) {
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

            if (current.wind_speed_10m > 20) {
                rules.push({
                    text: t("High winds. Avoid spraying.", "વધારે પવન છે. દવાનો છંટકાવ ટાળો.", "तेज हवाएं। छिड़काव से बचें।"),
                    type: "danger"
                });
            }

            if (current.relative_humidity_2m > 75) {
                rules.push({
                    text: t("High humidity. Check for fungus.", "ભેજનું પ્રમાણ વધારે છે. ફૂગ માટે તપાસો.", "उच्च आर्द्रता। कवक की जाँच करें।"),
                    type: "info"
                });
            }

            // Crop Specific (Simplified Logic for Demo)
            if (crop === 'Cotton') {
                if (current.relative_humidity_2m > 60) cropRules.push(t("Watch for bollworms.", "ઈયળના ઉપદ્રવ માટે ધ્યાન રાખો.", "बोल्वौर्म के लिए देखें।"));
                else cropRules.push(t("Ideal for cotton picking.", "કપાસ વીણવા માટે ઉત્તમ.", "कपास चुनने के लिए आदर्श।"));
            } else if (crop === 'Groundnut') {
                cropRules.push(t("Monitor soil moisture.", "જમીનમાં ભેજ ચકાસો.", "मिट्टी की नमी की जाँच करें।"));
            } else if (crop === 'Wheat') {
                if (current.temperature_2m > 30) cropRules.push(t("Heat stress risk.", "ગરમીથી પાકને નુકસાન થઈ શકે.", "गर्मी से फसल को नुकसान हो सकता है।"));
                else cropRules.push(t("Good growth temperature.", "વિકાસ માટે યોગ્ય તાપમાન.", "विकास के लिए अच्छा तापमान।"));
            } else {
                cropRules.push(t("Check for weeds regularly.", "નિયમિત નિંદામણ કરો.", "नियमित रूप से खरपतवार की जाँच करें।"));
            }

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

            const cropList = document.getElementById('cropAdvisoryList');
            cropList.innerHTML = '';
            cropRules.forEach(text => {
                cropList.innerHTML += `
                    <li class="flex gap-4 items-start p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <i class="fas fa-leaf text-emerald-500 mt-1"></i>
                        <p class="text-gray-700 font-medium leading-relaxed">${text}</p>
                    </li>`;
            });
        }

        // --- Logic Control ---

        async function fetchWeather(forceRefresh = false) {
            if (state.isLoading) return;

            // Check Cache (30 min)
            if (!forceRefresh) {
                const lastFetch = localStorage.getItem('agri_last_fetch');
                const cachedDataStr = localStorage.getItem('agri_weather_data');
                if (lastFetch && cachedDataStr) {
                    const elapsed = Date.now() - parseInt(lastFetch);
                    if (elapsed < 30 * 60 * 1000) { // 30 minutes
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
        const stateSelect = document.getElementById('stateSelect');
        const citySelect = document.getElementById('citySelect');

        function populateCities() {
            const selectedState = stateSelect.value;
            const districts = stateDistricts[selectedState] || [];

            citySelect.innerHTML = '<option value="">Select City / District</option>';
            districts.forEach(district => {
                citySelect.innerHTML += `<option value="${district}">${district}</option>`;
            });
        }

        // Event Listener for State Change
        stateSelect.addEventListener('change', populateCities);

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
    </script>
</body>

</html>