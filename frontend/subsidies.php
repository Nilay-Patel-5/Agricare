<?php
header("Location: ../dashboard/farmer.php?mod=subsidies");
exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Subsidies | AgriCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
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

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .desktop-container {
            max-width: 1400px;
            margin: 0 auto;
        }

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

        .subsidy-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .subsidy-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .category-pill.active {
            background-color: #10b981;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.4);
        }

        .status-live {
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
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
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all"
                        data-lang="home">🏠 Home</a>
                    <a href="subsidies.php"
                        class="text-emerald-700 bg-emerald-50 font-bold px-6 py-3 rounded-full transition-all"
                        data-lang="subsidies">💰 Subsidies</a>
                    <a href="market.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all"
                        data-lang="market">📈 Market</a>
                    <a href="weather.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all"
                        data-lang="weather">🌤️ Weather</a>
                    <a href="about.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all"
                        data-lang="contact">ℹ️ About</a>
                    <a href="disease_detection.php"
                        class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all"
                        data-lang="sol_disease">🩺 Detection</a>
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
                            data-lang-key="hi">हिં</button>
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
    </header>

    <main class="pt-32 pb-20 lg:pt-48 lg:pb-32 desktop-container px-6 lg:px-12 min-h-screen">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl lg:text-6xl font-black text-gray-900 mb-6" data-lang="subsidy_hero_title">
                Government <span class="text-emerald-600">Subsidies</span> for You
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto" data-lang="subsidy_hero_desc">
                Discover the latest financial aids, schemes, and benefits provided by the state and central government
                for farmers.
            </p>
        </div>

        <!-- Search & Filter -->
        <div
            class="bg-white p-6 rounded-3xl shadow-xl border border-gray-100 mb-12 flex flex-col md:flex-row gap-6 items-center justify-between">
            <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto no-scrollbar">
                <button onclick="setCategory('All')"
                    class="category-pill active px-6 py-2 rounded-full font-bold whitespace-nowrap transition-all border border-emerald-100 hover:bg-emerald-50"
                    data-lang="cat_all">All Schemes</button>
                <button onclick="setCategory('Income Support')"
                    class="category-pill px-6 py-2 rounded-full font-bold whitespace-nowrap transition-all border border-emerald-100 hover:bg-emerald-50"
                    data-lang="cat_income">Income Support</button>
                <button onclick="setCategory('Irrigation')"
                    class="category-pill px-6 py-2 rounded-full font-bold whitespace-nowrap transition-all border border-emerald-100 hover:bg-emerald-50"
                    data-lang="cat_irrigation">Irrigation</button>
                <button onclick="setCategory('Equipment')"
                    class="category-pill px-6 py-2 rounded-full font-bold whitespace-nowrap transition-all border border-emerald-100 hover:bg-emerald-50"
                    data-lang="cat_equipment">Equipment</button>
            </div>
            <div class="relative w-full md:w-72">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="subsidySearch" onkeyup="handleSearch(this.value)" placeholder="Search schemes..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    data-lang="search_placeholder">
            </div>
        </div>

        <!-- Subsidies Grid -->
        <div id="subsidiesArea" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Loading States -->
            <div class="col-span-full py-20 flex flex-col items-center justify-center">
                <i class="fas fa-spinner fa-spin text-4xl text-emerald-600 mb-4"></i>
                <p class="text-gray-500 font-bold">Loading available subsidies...</p>
            </div>
        </div>
    </main>

    <div class="desktop-container px-6 lg:px-12">
        <div class="grid lg:grid-cols-4 gap-12 mb-16 text-left">
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
            </div>
            <div>
                <h4 class="text-xl font-bold mb-6 border-l-4 border-emerald-500 pl-4" data-lang="solutions">Solutions
                </h4>
                <ul class="space-y-4 text-gray-400">
                    <li><a href="subsidies.php" class="hover:text-emerald-400 transition-colors"
                            data-lang="sol_subsidies">Subsidy Portal</a></li>
                    <li><a href="market.php" class="hover:text-emerald-400 transition-colors"
                            data-lang="sol_market">Market Connect</a></li>
                    <li><a href="weather.php" class="hover:text-emerald-400 transition-colors"
                            data-lang="sol_weather">Weather Station</a></li>
                    <li><a href="disease_detection.php" class="hover:text-emerald-400 transition-colors"
                            data-lang="sol_disease">Disease Identification</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xl font-bold mb-6 border-l-4 border-orange-500 pl-4" data-lang="contact">ℹ️ About</h4>
                <ul class="space-y-4 text-gray-400">
                    <li data-lang="location">⚲ Location: Gujarat, India</li>
                    <li data-lang="email">✉ Contact: project@agricare.demo</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 flex justify-center items-center text-gray-500 text-sm">
            <p data-lang="copyright">© 2026 AgriCare. Made with ❤️ for Farmers.</p>
        </div>
    </div>

    <script>
        let currentLang = localStorage.getItem('agricare_lang') || 'en';
        let currentCategory = 'All';
        let currentSearch = '';

        const translations = {
            en: {
                logo: 'AgriCare',
                tagline: "Gujarat's Smart Farming Platform",
                home: '🏠 Home',
                subsidies: '💰 Subsidies',
                market: '📈 Market',
                weather: '🌤️ Weather',
                contact: 'ℹ️ About',
                loginBtn: 'Login',
                registerBtn: 'Register',
                subsidy_hero_title: 'Government <span class="text-emerald-600">Subsidies</span> for You',
                subsidy_hero_desc: 'Discover the latest financial aids, schemes, and benefits provided by the state and central government for farmers.',
                cat_all: 'All Schemes',
                cat_income: 'Income Support',
                cat_irrigation: 'Irrigation',
                cat_equipment: 'Equipment',
                search_placeholder: 'Search schemes...',
                copyright: '© 2026 AgriCare. Made with ❤️ for Farmers.',
                apply_now: 'Apply Now',
                view_details: 'View Details',
                benefit_label: 'Benefit:',
                eligibility_label: 'Eligibility:',
                live_status: 'Live',
                upcoming_status: 'Upcoming',
                solutions: 'Solutions',
                sol_subsidies: 'Subsidy Portal',
                sol_market: 'Market Connect',
                sol_weather: 'Weather Station',
                location: '⚲ Location: Gujarat, India',
                email: '✉ Contact: project@agricare.demo',
                'footer-desc': 'Empowering farmers with data, connecting markets with transparency, and building a sustainable future.'
            },
            gu: {
                logo: 'એગ્રીકેર',
                tagline: "ગુજરાતનું સ્માર્ટ કૃષિ પ્લેટફોર્મ",
                home: '🏠 મુખ્ય',
                subsidies: '💰 સબસિડી',
                market: '📈 બજાર',
                weather: '🌤️ હવામાન',
                contact: 'ℹ️ પરિચય',
                loginBtn: 'લોગિન',
                registerBtn: 'નોંધણી',
                subsidy_hero_title: 'તમારા માટે સરકારી <span class="text-emerald-600">સબસિડી</span>',
                subsidy_hero_desc: 'રાજ્ય અને કેન્દ્ર સરકાર દ્વારા ખેડૂતો માટે આપવામાં આવતી નવીનતમ નાણાકીય સહાય, યોજનાઓ અને લાભો શોધો.',
                cat_all: 'તમામ યોજનાઓ',
                cat_income: 'આવક સહાય',
                cat_irrigation: 'સિંચાઈ',
                cat_equipment: 'સાધનો',
                search_placeholder: 'યોજનાઓ શોધો...',
                copyright: '© 2026 એગ્રીકેર. ખેડૂતો માટે ❤️ થી બનાવેલ.',
                apply_now: 'અત્યારે અરજી કરો',
                view_details: 'વિગતો જુઓ',
                benefit_label: 'લાભ:',
                eligibility_label: 'પાત્રતા:',
                live_status: 'ચાલુ',
                upcoming_status: 'આગામી',
                solutions: 'ઉકેલો',
                sol_subsidies: 'સબસિડી પોર્ટલ',
                sol_market: 'બજાર જોડાણ',
                sol_weather: 'હવામાન સ્ટેશન',
                location: '⚲ સ્થળ: ગુજરાત, ભારત',
                email: '✉ સંપર્ક: project@agricare.demo',
                'footer-desc': 'ખેડૂતોને સશક્તિકરણ, પારદર્શિતા સાથે બજારોને જોડવું અને ટકાઉ ભવિષ્યનું નિર્માણ.'
            },
            hi: {
                logo: 'एग्रीकेयर',
                tagline: "गुजरात का स्मार्ट कृषि-मंच",
                home: '🏠 होम',
                subsidies: '💰 सब्सिडी',
                market: '📈 बाज़ार',
                weather: '🌤️ मौसम',
                contact: 'ℹ️ परिचय',
                loginBtn: 'लॉगिन',
                registerBtn: 'रजिस्टर',
                subsidy_hero_title: 'आपके लिए सरकारी <span class="text-emerald-600">सब्सिडी</span>',
                subsidy_hero_desc: 'किसानों के लिए राज्य और केंद्र सरकार દ્વારા प्रदान की जाने वाली नवीनतम वित्तीय सहायता, योजनाओं और लाभों की खोज करें।',
                cat_all: 'सभी योजनाएं',
                cat_income: 'आय सहायता',
                cat_irrigation: 'सिंचाई',
                cat_equipment: 'उपकरण',
                search_placeholder: 'योजनाएं खोजें...',
                copyright: '© 2026 एग्रीकेयर। किसानों के लिए ❤️ से बनाया गया।',
                apply_now: 'अभी आवेदन करें',
                view_details: 'विवरण देखें',
                benefit_label: 'लाभ:',
                eligibility_label: 'पात्रता:',
                live_status: 'सक्रिय',
                upcoming_status: 'आगामी',
                solutions: 'समाधान',
                sol_subsidies: 'सब्सिडी पोर्टल',
                sol_market: 'बाज़ार संपर्क',
                sol_weather: 'मौसम स्टेशन',
                location: '⚲ स्थान: गुजरात, भारत',
                email: '✉ संपर्क: project@agricare.demo',
                'footer-desc': 'किसानों को डेटा के साथ सशक्त बनाना और एक स्थायी भविष्य का निर्माण करना।'
            }
        };

        function changeLang(lang) {
            currentLang = lang;
            localStorage.setItem('agricare_lang', lang);
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

            const body = document.body;
            body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') body.classList.add('gu-text');
            if (lang === 'hi') body.classList.add('hi-text');

            fetchSubsidies();
        }

        async function fetchSubsidies() {
            const area = document.getElementById('subsidiesArea');
            try {
                const res = await fetch("../backend/get_subsidies.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ category: currentCategory, search: currentSearch })
                });
                const data = await res.json();
                const subsidies = Array.isArray(data) ? data : [];

                if (subsidies.length === 0) {
                    area.innerHTML = `<div class="col-span-full py-20 text-center">
                        <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500 font-bold">No subsidies found matching your criteria.</p>
                    </div>`;
                    return;
                }

                area.innerHTML = subsidies.map(sub => {
                    const name = currentLang === 'en' ? sub.name : (currentLang === 'gu' ? sub.name_gu : sub.name_hi);
                    const desc = currentLang === 'en' ? sub.description : (currentLang === 'gu' ? sub.description_gu : sub.description_hi);
                    const benefit = currentLang === 'en' ? sub.benefits : (currentLang === 'gu' ? sub.benefits_gu : sub.benefits_hi);
                    const eligibility = currentLang === 'en' ? sub.eligibility : (currentLang === 'gu' ? sub.eligibility_gu : sub.eligibility_hi);
                    const statusClass = sub.status === 'Live' ? 'bg-green-100 text-green-700 status-live' : 'bg-orange-100 text-orange-700';
                    const statusText = sub.status === 'Live' ? translations[currentLang].live_status : translations[currentLang].upcoming_status;

                    return `
                        <div class="subsidy-card bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-lg flex flex-col h-full">
                            <div class="flex justify-between items-start mb-6">
                                <span class="${statusClass} text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-widest">
                                    ${statusText}
                                </span>
                                <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1 rounded-lg">
                                    ${sub.category}
                                </span>
                            </div>
                            <h3 class="text-2xl font-black text-gray-800 mb-4 leading-tight">${name}</h3>
                            <p class="text-gray-600 mb-6 line-clamp-3">${desc}</p>
                            
                            <div class="space-y-4 mb-8 mt-auto">
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shrink-0">
                                        <i class="fas fa-gift"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray-400 uppercase tracking-tighter">${translations[currentLang].benefit_label}</div>
                                        <div class="text-sm font-bold text-gray-700">${benefit}</div>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 shrink-0">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray-400 uppercase tracking-tighter">${translations[currentLang].eligibility_label}</div>
                                        <div class="text-sm font-bold text-gray-700">${eligibility}</div>
                                    </div>
                                </div>
                            </div>

                            <a href="${sub.apply_link}" target="_blank" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-center shadow-lg transition-all flex items-center justify-center gap-3">
                                ${translations[currentLang].apply_now} <i class="fas fa-external-link-alt text-xs"></i>
                            </a>
                        </div>
                    `;
                }).join('');

            } catch (err) {
                area.innerHTML = `<div class="col-span-full py-20 text-center text-red-500">
                    <i class="fas fa-exclamation-circle text-4xl mb-4"></i>
                    <p class="font-bold">Failed to load subsidies. Please try again later.</p>
                </div>`;
            }
        }

        function setCategory(cat) {
            currentCategory = cat;
            document.querySelectorAll('.category-pill').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            fetchSubsidies();
        }

        let searchTimeout;
        function handleSearch(val) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = val;
                fetchSubsidies();
            }, 500);
        }

        window.onscroll = function () {
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

        document.addEventListener('DOMContentLoaded', () => {
            changeLang(currentLang);
        });
    </script>
</body>

</html>
