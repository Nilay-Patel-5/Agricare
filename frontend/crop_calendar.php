<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Crop Calendar | AgriCare</title>
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

        .desktop-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .current-month-indicator {
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
            border: 2px solid #10b981;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Top Nav -->
    <header class="glass-card sticky top-0 z-50 border-b border-gray-100 shadow-sm transition-all duration-500">
        <div class="desktop-container px-6 lg:px-12 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-4 group">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-yellow-400 via-orange-500 to-emerald-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black text-gray-800" data-lang="title">Crop Calendar</h1>
                </div>
            </a>
            <div class="flex items-center gap-4">
                <div class="flex bg-gray-100 p-1 rounded-xl">
                    <button onclick="changeLang('en')"
                        class="lang-btn px-3 py-1 text-sm font-bold rounded-lg transition-all active bg-white shadow text-emerald-700">EN</button>
                    <button onclick="changeLang('gu')"
                        class="lang-btn px-3 py-1 text-sm font-bold rounded-lg text-gray-500 hover:text-emerald-700 transition-all">ગુ</button>
                    <button onclick="changeLang('hi')"
                        class="lang-btn px-3 py-1 text-sm font-bold rounded-lg text-gray-500 hover:text-emerald-700 transition-all">हिं</button>
                </div>
                <a href="index.php"
                    class="hidden sm:flex items-center font-bold text-emerald-600 hover:text-emerald-700 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> <span data-lang="homeBtn">Back to Home</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow desktop-container px-6 lg:px-12 py-12 w-full">
        <!-- Dashboard Header -->
        <div class="mb-12">
            <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mb-4" data-lang="headerTitle">Seasonal Activity
                Plan</h2>
            <p class="text-gray-600 font-medium max-w-2xl" data-lang="headerDesc">
                Select your crop to view detailed month-by-month guidance for sowing, irrigation, fertilizer management,
                and harvesting.
            </p>
        </div>

        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar: Crop Selection -->
            <div class="lg:col-span-1 border-r border-gray-200 pr-0 lg:pr-8">
                <h3 class="font-bold text-gray-400 uppercase tracking-widest text-sm mb-6" data-lang="selectCrop">Select
                    Crop</h3>
                <div class="space-y-4" id="cropSelector">
                    <!-- Javascript will populate buttons here -->
                </div>
            </div>

            <!-- Main Content: The Calendar -->
            <div class="lg:col-span-3">
                <div
                    class="flex justify-between items-center mb-8 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div>
                        <h2 class="text-2xl font-black text-emerald-800 tracking-tight" id="activeCropName">Loading...
                        </h2>
                        <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-widest" id="activeCropSeason">
                            Season</p>
                    </div>
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-3xl shrink-0"
                        id="activeCropIcon">
                        🌾
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6" id="calendarGrid">
                    <!-- Javascript will populate months here -->
                </div>
            </div>
        </div>
    </main>

    <script>
        // Data Configuration
        const cropsData = {
            wheat: {
                id: 'wheat',
                icon: '🌾',
                season: { en: 'Rabi (Winter)', gu: 'રવિ (શિયાળુ)', hi: 'रबी (सर्दियों)' },
                name: { en: 'Wheat', gu: 'ઘઉં', hi: 'गेहूँ' },
                schedule: {
                    10: { type: 'prepare', icon: 'tractor', color: 'orange', text: { en: 'Field Preparation & Sowing', gu: 'ખેતરની તૈયારી અને વાવણી', hi: 'खेत की तैयारी एवं बुवाई' } },
                    11: { type: 'water', icon: 'tint', color: 'blue', text: { en: 'First Irrigation (CRI Stage)', gu: 'પ્રથમ પિયત (CRI તબક્કો)', hi: 'पहली सिंचाई (CRI अवस्था)' } },
                    12: { type: 'fertilizer', icon: 'leaf', color: 'emerald', text: { en: 'Top Dressing Nitrogen', gu: 'નાઈટ્રોજન ખાતર આપવું', hi: 'यूरिया का छिड़काव' } },
                    0: { type: 'care', icon: 'bug', color: 'purple', text: { en: 'Weed & Pest Control', gu: 'નીંદણ અને જીવાત નિયંત્રણ', hi: 'खरपतवार एवं कीट नियंत्रण' } },
                    1: { type: 'water', icon: 'tint', color: 'blue', text: { en: 'Flowering Stage Irrigation', gu: 'ફૂલ અવસ્થાએ પિયત', hi: 'फूल आने पर सिंचाई' } },
                    2: { type: 'harvest', icon: 'sun', color: 'amber', text: { en: 'Grain Filling', gu: 'દાણા ભરવાનો સમય', hi: 'दाना भरने का समय' } },
                    3: { type: 'harvest', icon: 'scythe', color: 'red', text: { en: 'Harvesting', gu: 'લણણી', hi: 'कटाई' } }
                }
            },
            cotton: {
                id: 'cotton',
                icon: '☁️',
                season: { en: 'Kharif (Monsoon)', gu: 'ખરીફ (ચોમાસુ)', hi: 'खरीफ (मानसून)' },
                name: { en: 'Cotton', gu: 'કપાસ', hi: 'कपास' },
                schedule: {
                    4: { type: 'prepare', icon: 'tractor', color: 'orange', text: { en: 'Deep Ploughing & Prep', gu: 'ઊંડી ખેડ અને તૈયારી', hi: 'गहरी जुताई और तैयारी' } },
                    5: { type: 'prepare', icon: 'seedling', color: 'emerald', text: { en: 'Pre-monsoon Sowing', gu: 'ચોમાસા પહેલા વાવણી', hi: 'मानसून पूर्व बुवाई' } },
                    6: { type: 'care', icon: 'leaf', color: 'emerald', text: { en: 'Weeding & Thinning', gu: 'નીંદણ અને પારવણી', hi: 'खरपतवार नियंत्रण' } },
                    7: { type: 'fertilizer', icon: 'flask', color: 'purple', text: { en: 'Fertilizer Application', gu: 'ખાતરનો ઉપયોગ', hi: 'उर्वरक का प्रयोग' } },
                    8: { type: 'care', icon: 'bug', color: 'red', text: { en: 'Pest Scouting (Bollworm)', gu: 'જીવાત નિરીક્ષણ (ઇયળ)', hi: 'कीट निरीक्षण (इल्ली)' } },
                    9: { type: 'water', icon: 'tint', color: 'blue', text: { en: 'Square & Boll Formation', gu: 'ઝીંડવા બેસવાનો સમય', hi: 'टिंडे बनने का समय' } },
                    10: { type: 'harvest', icon: 'box', color: 'amber', text: { en: 'First Picking', gu: 'પ્રથમ વીણી', hi: 'पहली चुनाई' } },
                    11: { type: 'harvest', icon: 'box', color: 'amber', text: { en: 'Second Picking', gu: 'બીજી વીણી', hi: 'दूसरी चुनाई' } }
                }
            }
        };

        const monthNames = {
            en: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            gu: ['જાન્યુઆરી', 'ફેબ્રુઆરી', 'માર્ચ', 'એપ્રિલ', 'મે', 'જૂન', 'જુલાઈ', 'ઓગસ્ટ', 'સપ્ટેમ્બર', 'ઓક્ટોબર', 'નવેમ્બર', 'ડિસેમ્બર'],
            hi: ['जनवरी', 'फरवरी', 'मार्च', 'अप्रैल', 'मई', 'जून', 'जुलाई', 'अगस्त', 'सितंबर', 'अक्टूबर', 'नवंबर', 'दिसंबर']
        };

        const translations = {
            en: {
                title: 'Crop Calendar',
                homeBtn: 'Back to Home',
                headerTitle: 'Seasonal Activity Plan',
                headerDesc: 'Select your crop to view detailed month-by-month guidance for sowing, irrigation, fertilizer management, and harvesting.',
                selectCrop: 'Select Crop',
                noActivity: 'Rest Period / Field Prep'
            },
            gu: {
                title: 'પાક કેલેન્ડર',
                homeBtn: 'મુખ્ય પૃષ્ઠ પર પાછા ફરો',
                headerTitle: 'મોસમી પ્રવૃત્તિ યોજના',
                headerDesc: 'વાવણી, સિંચાઈ, ખાતર વ્યવસ્થાપન અને લણણી માટે વિગતવાર મહિના મુજબનું માર્ગદર્શન જોવા માટે તમારો પાક પસંદ કરો.',
                selectCrop: 'પાક પસંદ કરો',
                noActivity: 'આરામનો સમય / ખેતરની તૈયારી'
            },
            hi: {
                title: 'फसल कैलेंडर',
                homeBtn: 'होम पर वापस जाएं',
                headerTitle: 'मौसमी गतिविधि योजना',
                headerDesc: 'बुवाई, सिंचाई, उर्वरक प्रबंधन और कटाई के लिए विस्तृत महीनेवार मार्गदर्शन देखने के लिए अपनी फसल का चयन करें।',
                selectCrop: 'फसल चुनें',
                noActivity: 'आराम की अवधि / खेत की तैयारी'
            }
        };

        let currentLang = localStorage.getItem('agricare_lang') || 'en';
        let activeCrop = 'wheat';

        function renderSidebar() {
            const container = document.getElementById('cropSelector');
            container.innerHTML = '';

            Object.values(cropsData).forEach(crop => {
                const isActive = crop.id === activeCrop;
                const btn = document.createElement('button');
                btn.className = `w-full text-left px-6 py-4 rounded-2xl font-bold transition-all flex items-center gap-4 ${isActive ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-gray-600 hover:bg-emerald-50 border border-gray-100'}`;
                btn.innerHTML = `<span class="text-2xl">${crop.icon}</span> <span>${crop.name[currentLang]}</span>`;
                btn.onclick = () => {
                    activeCrop = crop.id;
                    renderSidebar();
                    renderCalendar();
                };
                container.appendChild(btn);
            });
        }

        function renderCalendar() {
            const crop = cropsData[activeCrop];
            document.getElementById('activeCropName').innerText = crop.name[currentLang];
            document.getElementById('activeCropSeason').innerText = crop.season[currentLang];
            document.getElementById('activeCropIcon').innerText = crop.icon;

            const grid = document.getElementById('calendarGrid');
            grid.innerHTML = '';

            const currentMonthIndex = new Date().getMonth();

            for (let i = 0; i < 12; i++) {
                const isCurrentMonth = i === currentMonthIndex;
                const activity = crop.schedule[i];

                const card = document.createElement('div');
                card.className = `bg-white rounded-3xl p-6 border transition-all ${isCurrentMonth ? 'current-month-indicator' : 'border-gray-100 shadow-sm hover:shadow-md'}`;

                let activityHtml = '';
                if (activity) {
                    activityHtml = `
                        <div class="mt-4 flex items-start gap-4 p-4 rounded-2xl bg-${activity.color}-50">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-${activity.color}-600 shadow-sm shrink-0">
                                <i class="fas fa-${activity.icon}"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm leading-tight">${activity.text[currentLang]}</p>
                            </div>
                        </div>
                    `;
                } else {
                    activityHtml = `
                        <div class="mt-4 p-4 text-center">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">${translations[currentLang].noActivity}</p>
                        </div>
                    `;
                }

                card.innerHTML = `
                    <div class="flex justify-between items-center">
                        <h4 class="text-xl font-black text-gray-900">${monthNames[currentLang][i]}</h4>
                        ${isCurrentMonth ? `<span class="bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md">Current</span>` : ''}
                    </div>
                    ${activityHtml}
                `;
                grid.appendChild(card);
            }
        }

        function changeLang(lang) {
            currentLang = lang;
            localStorage.setItem('agricare_lang', lang);

            // Buttons UI update
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow', 'text-emerald-700');
                btn.classList.add('text-gray-500');
            });
            document.querySelectorAll(`button[onclick="changeLang('${lang}')"]`).forEach(btn => {
                btn.classList.add('bg-white', 'shadow', 'text-emerald-700');
                btn.classList.remove('text-gray-500');
            });

            // Text translations
            document.querySelectorAll('[data-lang]').forEach(el => {
                const key = el.getAttribute('data-lang');
                if (translations[lang][key]) el.innerText = translations[lang][key];
            });

            // Fonts
            document.body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') document.body.classList.add('gu-text');
            if (lang === 'hi') document.body.classList.add('hi-text');

            renderSidebar();
            renderCalendar();
        }

        document.addEventListener('DOMContentLoaded', () => {
            changeLang(currentLang);
        });
    </script>
</body>

</html>