<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>About Us | AgriCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&family=Noto+Sans+Gujarati:wght@400;500;700&family=Noto+Sans+Devanagari:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .gu-text {
            font-family: 'Noto Sans Gujarati', sans-serif;
        }

        .hi-text {
            font-family: 'Noto Sans Devanagari', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .lang-btn.active {
            background-color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            color: #047857;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Header -->
    <header class="fixed top-0 w-full bg-white/95 backdrop-blur-3xl shadow-lg border-b-4 border-emerald-500 z-50 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="flex items-center gap-4 group">
                    <div class="w-14 h-14 lg:w-16 lg:h-16 bg-gradient-to-br from-yellow-400 via-orange-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg group-hover:scale-110 transition-all duration-300">
                        <i class="fas fa-wheat-awn"></i>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-2xl lg:text-3xl font-black text-gray-800 tracking-tight" data-lang="logo">AgriCare</h1>
                        <p class="text-xs font-bold text-emerald-600 tracking-wider uppercase hidden sm:block" data-lang="tagline">Gujarat's Smart Farming Platform</p>
                    </div>
                </a>

                <nav class="hidden xl:flex items-center gap-1">
                    <a href="index.php" class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all hover:underline hover:underline-offset-4 focus:underline focus:underline-offset-4 active:underline active:underline-offset-4 decoration-2" data-lang="home">🏠 Home</a>
                    <a href="market.php" class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all hover:underline hover:underline-offset-4 focus:underline focus:underline-offset-4 active:underline active:underline-offset-4 decoration-2" data-lang="market">📈 Market</a>
                    <a href="weather.php" class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all hover:underline hover:underline-offset-4 focus:underline focus:underline-offset-4 active:underline active:underline-offset-4 decoration-2" data-lang="weather">🌤️ Weather</a>
                    <a href="about.php" class="text-emerald-700 bg-emerald-50 font-bold px-6 py-3 rounded-full transition-all decoration-2" data-lang="contact">ℹ️ About</a>
                </nav>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex bg-gray-100 p-1 rounded-xl">
                        <button onclick="changeLang('en')" class="lang-btn px-3 py-1 text-sm font-bold rounded-lg transition-all active bg-white shadow text-emerald-700" data-lang-key="en">EN</button>
                        <button onclick="changeLang('gu')" class="lang-btn px-3 py-1 text-sm font-bold rounded-lg text-gray-500 hover:text-emerald-700 transition-all" data-lang-key="gu">ગુ</button>
                        <button onclick="changeLang('hi')" class="lang-btn px-3 py-1 text-sm font-bold rounded-lg text-gray-500 hover:text-emerald-700 transition-all" data-lang-key="hi">हिं</button>
                    </div>

                    <a href="login.php" data-lang="loginBtn" class="hidden lg:inline-flex text-emerald-700 font-bold py-3 px-6 hover:text-emerald-900 transition-all">Login</a>
                    <a href="register.php" class="hidden lg:inline-flex bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all" data-lang="registerBtn">Register</a>

                    <button id="mobileMenuBtn" class="xl:hidden text-2xl text-gray-700 p-2">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobileMenuOverlay" class="xl:hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden transition-opacity"></div>
        <div id="mobileMenu" class="xl:hidden fixed right-0 top-0 h-full w-80 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50 p-6 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-black text-emerald-800" data-lang="menu_title">Menu</h2>
                <button onclick="closeMobileMenu()" class="text-2xl text-gray-500 hover:text-red-500"><i class="fas fa-times"></i></button>
            </div>

            <div class="flex gap-2 mb-8 justify-center bg-gray-50 p-3 rounded-xl">
                <button onclick="changeLang('en')" class="flex-1 py-2 font-bold rounded bg-white shadow text-emerald-600 text-center">EN</button>
                <button onclick="changeLang('gu')" class="flex-1 py-2 font-bold rounded hover:bg-white text-gray-600 text-center">ગુ</button>
                <button onclick="changeLang('hi')" class="flex-1 py-2 font-bold rounded hover:bg-white text-gray-600 text-center">हिं</button>
            </div>

            <nav class="space-y-4">
                <a href="index.php" onclick="closeMobileMenu()" class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b" data-lang="home">🏠 Home</a>
                <a href="market.php" onclick="closeMobileMenu()" class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b" data-lang="market">📈 Market</a>
                <a href="weather.php" onclick="closeMobileMenu()" class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b" data-lang="weather">🌤️ Weather</a>
                <a href="about.php" onclick="closeMobileMenu()" class="block text-lg font-semibold text-emerald-600 py-2 border-b bg-emerald-50 rounded pl-2" data-lang="contact">ℹ️ About</a>
                <a href="login.php" data-lang="loginBtn" class="block text-lg font-semibold text-gray-700 hover:text-emerald-600 py-2 border-b">Login</a>
                <a href="register.php" data-lang="registerBtn" class="block bg-emerald-600 text-white font-bold py-3 rounded-xl text-center mt-6 shadow-lg">Register Now</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-6 lg:px-12 max-w-7xl mx-auto">

        <div class="text-center mb-16">
            <span class="inline-block bg-emerald-100 text-emerald-800 font-bold px-4 py-1 rounded-full mb-4 text-sm uppercase tracking-wider" data-lang="ourStory">Our Story</span>
            <h1 class="text-4xl lg:text-6xl font-black text-gray-900 mb-6" data-lang="aboutTitle">Empowering Agriculture</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed" data-lang="aboutSubtitle">
                AgriCare is dedicated to transforming the lives of farmers in Gujarat through cutting-edge technology, real-time data, and community-driven support.
            </p>
        </div>

        <div class="max-w-4xl mx-auto mb-24 space-y-12">
            <!-- Extended Description -->
            <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100/50">
                <h2 class="text-2xl font-bold text-gray-900 mb-4" data-lang="whoWeAreTitle">Who We Are</h2>
                <p class="text-gray-600 leading-relaxed text-lg" data-lang="whoWeAreDesc">
                    Founded in 2026, AgriCare started with a simple belief: technology should serve those who feed the nation. We are a team of agricultural experts, data scientists, and engineers working together to solve the complex challenges faced by farmers in Gujarat. From unpredictable weather patterns to market volatility, we provide the insights needed to make informed decisions. Our platform connects traditional wisdom with modern science, creating a synergy that boosts productivity while preserving the essence of farming.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-emerald-50 p-8 rounded-[2rem]">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 text-xl mb-4">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2" data-lang="missionTitle">Our Mission</h3>
                    <p class="text-gray-600 leading-relaxed" data-lang="missionDesc">
                        To bridge the gap between traditional farming and modern technology, ensuring every farmer has access to the tools they need to prosper.
                    </p>
                </div>
                <div class="bg-orange-50 p-8 rounded-[2rem]">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 text-xl mb-4">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2" data-lang="visionTitle">Our Vision</h3>
                    <p class="text-gray-600 leading-relaxed" data-lang="visionDesc">
                        A future where agriculture is sustainable, profitable, and respected as the backbone of our economy.
                    </p>
                </div>
            </div>
        </div>

        <!-- Values Section -->
        <div class="bg-emerald-50 rounded-[3rem] p-12 text-center">
            <h2 class="text-3xl font-black text-gray-900 mb-12" data-lang="valuesTitle">Core Values</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="text-4xl text-emerald-500 mb-4"><i class="fas fa-hand-holding-heart"></i></div>
                    <h3 class="text-xl font-bold mb-2" data-lang="value1Title">Integrity</h3>
                    <p class="text-gray-500 text-sm" data-lang="value1Desc">Honest and transparent in all our dealings.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="text-4xl text-emerald-500 mb-4"><i class="fas fa-lightbulb"></i></div>
                    <h3 class="text-xl font-bold mb-2" data-lang="value2Title">Innovation</h3>
                    <p class="text-gray-500 text-sm" data-lang="value2Desc">Constantly improving with new solutions.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="text-4xl text-emerald-500 mb-4"><i class="fas fa-users"></i></div>
                    <h3 class="text-xl font-bold mb-2" data-lang="value3Title">Community</h3>
                    <p class="text-gray-500 text-sm" data-lang="value3Desc">Growing together with every farmer.</p>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
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
        const translations = {
            en: {
                tagline: "Gujarat's Smart Farming Platform",
                home: '🏠 Home',
                market: '📈 Market',
                weather: '🌤️ Weather',
                contact: 'ℹ️ About',
                loginBtn: 'Login',
                registerBtn: 'Register',
                menu_title: 'Menu',
                logo: 'AgriCare',
                backHome: 'Back to Home',
                aboutTitle: 'Empowering Agriculture',
                aboutSubtitle: 'AgriCare is dedicated to transforming the lives of farmers in Gujarat through cutting-edge technology, real-time data, and community-driven support.',
                ourStory: 'Our Story',
                whoWeAreTitle: 'Who We Are',
                whoWeAreDesc: 'Founded in 2026, AgriCare started with a simple belief: technology should serve those who feed the nation. We are a team of agricultural experts, data scientists, and engineers working together to solve the complex challenges faced by farmers in Gujarat. From unpredictable weather patterns to market volatility, we provide the insights needed to make informed decisions. Our platform connects traditional wisdom with modern science, creating a synergy that boosts productivity while preserving the essence of farming.',
                missionTitle: 'Our Mission',
                missionDesc: 'To bridge the gap between traditional farming and modern technology, ensuring every farmer has access to the tools they need to prosper.',
                visionTitle: 'Our Vision',
                visionDesc: 'A future where agriculture is sustainable, profitable, and respected as the backbone of our economy.',
                valuesTitle: 'Core Values',
                value1Title: 'Integrity',
                value1Desc: 'Honest and transparent in all our dealings.',
                value2Title: 'Innovation',
                value2Desc: 'Constantly improving with new solutions.',
                value3Title: 'Community',
                value3Desc: 'Growing together with every farmer.',
                'footer-desc': 'Empowering farmers with data, connecting markets with transparency, and building a sustainable future.',
                solutions: 'Solutions',
                sol_advisory: 'Crop Advisory',
                sol_disease: 'Crop Disease Identification',
                sol_market: 'Market Connect',
                sol_weather: 'Weather Station',
                location: '⚲ Location: Gujarat, India',
                email: '✉ Contact: project@agricare.demo',
                phone: '🕻 Phone: Available on request',
                copyright: '© 2026 AgriCare. Made with ❤️ for Farmers.'
            },
            gu: {
                tagline: "ગુજરાતનું સ્માર્ટ કૃષિ પ્લેટફોર્મ",
                home: '🏠 મુખ્ય',
                market: '📈 બજાર',
                weather: '🌤️ હવામાન',
                contact: 'ℹ️ પરિચય',
                loginBtn: 'લોગિન',
                registerBtn: 'નોંધણી',
                menu_title: 'મેનુ',
                logo: 'એગ્રીકેર',
                backHome: 'ઘરે પાછા',
                aboutTitle: 'કૃષિ સશક્તિકરણ',
                aboutSubtitle: 'એગ્રીકેર અત્યાધુનિક ટેકનોલોજી, રીઅલ-ટાઇમ ડેટા અને સમુદાય આધારિત સપોર્ટ દ્વારા ગુજરાતના ખેડૂતોના જીવનમાં પરિવર્તન લાવવા માટે સમર્પિત છે.',
                ourStory: 'અમારી વાર્તા',
                whoWeAreTitle: 'અમે કોણ છીએ',
                whoWeAreDesc: '2026 માં સ્થપાયેલ, એગ્રીકેર એક સાદી માન્યતા સાથે શરૂ થયું: ટેકનોલોજીએ જે રાષ્ટ્રને પોષણ આપે છે તેમની સેવા કરવી જોઈએ. અમે કૃષિ નિષ્ણાતો, ડેટા વૈજ્ઞાનિકો અને એન્જિનિયરોની એક ટીમ છીએ જે ગુજરાતના ખેડૂતો દ્વારા સામનો કરવામાં આવતા જટિલ પડકારોને ઉકેલવા માટે સાથે મળીને કામ કરીએ છીએ. અણધાર્યા હવામાનની પેટર્નથી માંડીને બજારની અસ્થિરતા સુધી, અમે જાણકાર નિર્ણયો લેવા માટે જરૂરી સૂઝ પૂરી પાડીએ છીએ. અમારું પ્લેટફોર્મ આધુનિક વિજ્ઞાન સાથે પરંપરાગત શાણપણને જોડે છે, એક સુમેળ બનાવે છે જે ખેતીના સારને સાચવીને ઉત્પાદકતા વધારે છે.',
                missionTitle: 'અમારું મિશન',
                missionDesc: 'પરંપરાગત ખેતી અને આધુનિક ટેકનોલોજી વચ્ચેના અંતરને દૂર કરવું, દરેક ખેડૂતને સમૃદ્ધ થવા માટે જરૂરી સાધનોની ખાતરી કરવી.',
                visionTitle: 'અમારું વિઝન',
                visionDesc: 'એવું ભવિષ્ય જ્યાં કૃષિ ટકાઉ, નફાકારક અને આપણી અર્થવ્યવસ્થાની કરોડરજ્જુ તરીકે સન્માનિત હોય.',
                valuesTitle: 'મૂળભૂત મૂલ્યો',
                value1Title: 'પ્રામાણિકતા',
                value1Desc: 'અમારા તમામ વ્યવહારોમાં પ્રામાણિક અને પારદર્શક.',
                value2Title: 'નવીનતા',
                value2Desc: 'નવા ઉકેલો સાથે સતત સુધારો.',
                value3Title: 'સમુદાય',
                value3Desc: 'દરેક ખેડૂત સાથે મળીને આગળ વધવું.',
                'footer-desc': 'ખેડૂતોને સશક્તિકરણ, પારદર્શિતા સાથે બજારોને જોડવું અને ટકાઉ ભવિષ્યનું નિર્માણ.',
                solutions: 'ઉકેલો',
                sol_advisory: 'પાક સલાહ',
                sol_disease: 'પાક રોગ ઓળખ',
                sol_market: 'બજાર જોડાણ',
                sol_weather: 'હવામાન સ્ટેશન',
                location: '⚲ સ્થળ: ગુજરાત, ભારત',
                email: '✉ સંપર્ક: project@agricare.demo',
                phone: '🕻 ફોન: વિનંતી પર ઉપલબ્ધ',
                copyright: '© 2026 એગ્રીકેર. ખેડૂતો માટે ❤️ થી બનાવેલ.'
            },
            hi: {
                tagline: "गुजरात का स्मार्ट कृषि-मंच",
                home: '🏠 होम',
                market: '📈 बाज़ार',
                weather: '🌤️ मौसम',
                contact: 'ℹ️ परिचय',
                loginBtn: 'लॉगिन',
                registerBtn: 'रजिस्टर',
                menu_title: 'मेनू',
                logo: 'एग्रीकेर',
                backHome: 'घर वापस',
                aboutTitle: 'कृषि सशक्तिकरण',
                aboutSubtitle: 'एग्रीकेर अत्याधुनिक तकनीक, रीयल-टाइम डेटा और समुदाय-संचालित समर्थन के माध्यम से गुजरात के किसानों के जीवन को बदलने के लिए समर्पित है।',
                ourStory: 'हमारी कहानी',
                whoWeAreTitle: 'हम कौन हैं',
                whoWeAreDesc: '2026 में स्थापित, एग्रीकेर एक साधारण विश्वास के साथ शुरू हुआ: तकनीक को उन लोगों की सेवा करनी चाहिए जो राष्ट्र का पेट भरते हैं। हम कृषि विशेषज्ञों, डेटा वैज्ञानिकों और इंजीनियरों की एक टीम हैं जो गुजरात के किसानों द्वारा सामना की जाने वाली जटिल चुनौतियों को हल करने के लिए मिलकर काम कर रहे हैं। अप्रत्याशित मौसम के मिजाज से लेकर बाजार की अस्थिरता तक, हम सूचित निर्णय लेने के लिए आवश्यक अंतर्दृष्टि प्रदान करते हैं। हमारा मंच आधुनिक विज्ञान के साथ पारंपरिक ज्ञान को जोड़ता है, एक तालमेल बनाता है जो खेती के सार को संरक्षित करते हुए उत्पादकता को बढ़ाता है।',
                missionTitle: 'हमारा मिशन',
                missionDesc: 'पारंपरिक खेती और आधुनिक तकनीक के बीच की खाई को पाटना, यह सुनिश्चित करना कि हर किसान के पास समृद्ध होने के लिए आवश्यक उपकरण हों।',
                visionTitle: 'हमारा विज़न',
                visionDesc: 'एक ऐसा भविष्य जहां कृषि टिकाऊ, लाभदायक और हमारी अर्थव्यवस्था की रीढ़ के रूप में सम्मानित हो।',
                valuesTitle: 'मूल मूल्य',
                value1Title: 'ईमानदारी',
                value1Desc: 'हमारे सभी लेन-देन में ईमानदार और पारदर्शी।',
                value2Title: 'नवाचार',
                value2Desc: 'नए समाधानों के साथ निरंतर सुधार।',
                value3Title: 'समुदाय',
                value3Desc: 'हर किसान के साथ मिलकर आगे बढ़ना।',
                'footer-desc': 'किसानों को डेटा के साथ सशक्त बनाना और एक स्थायी भविष्य का निर्माण करना।',
                solutions: 'समाधान',
                sol_advisory: 'फसल सलाह',
                sol_disease: 'फसल रोग पहचान',
                sol_market: 'बाज़ार संपर्क',
                sol_weather: 'मौसम स्टेशन',
                location: '⚲ स्थान: गुजरात, भारत',
                email: '✉ संपर्क: project@agricare.demo',
                phone: '🕻 फोन: अनुरोध पर उपलब्ध',
                copyright: '© 2026 एग्रीकेर। किसानों के लिए ❤️ से बनाया गया।'
            }
        };

        function changeLang(lang) {
            localStorage.setItem('agricare_lang', lang);
            // Remove active classes from all buttons
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow', 'text-emerald-700');
                btn.classList.add('text-gray-500');
            });

            // Add active class to selected lang buttons
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

            // Update URL implementation (optional, but good for consistency)
            const url = new URL(window.location);
            url.searchParams.set('lang', lang);
            window.history.pushState({}, '', url);
        }

        // Mobile Menu Logic
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
            if (mobileMenu) {
                mobileMenu.classList.add('translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        if (overlay) overlay.addEventListener('click', closeMobileMenu);

        // Initialize from URL or default
        const urlParams = new URLSearchParams(window.location.search);
        const initialLang = urlParams.get('lang') || localStorage.getItem('agricare_lang') || 'en';
        changeLang(initialLang);
    </script>
</body>

</html>