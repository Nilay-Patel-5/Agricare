<?php
// frontend/qna.php - Frequently Asked Questions page for AgriCare
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Q&A | AgriCare Gujarat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="output.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&family=Noto+Sans+Gujarati:wght@400;500;700&family=Noto+Sans+Devanagari:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        .gu-text { font-family: 'Noto Sans Gujarati', sans-serif; }
        .hi-text { font-family: 'Noto Sans Devanagari', sans-serif; }
        
        .qna-accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, padding 0.3s ease;
        }
        
        .qna-accordion-item.active .qna-accordion-content {
            max-height: 500px;
            padding-bottom: 1.5rem;
        }
        
        .qna-accordion-item.active .toggle-icon {
            transform: rotate(180deg);
        }

        .category-btn.active {
            background-color: #10b981;
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-icon-inside {
            position: absolute;
            left: 1.5rem;
            z-index: 10;
            color: #9ca3af;
            pointer-events: none;
        }

        .search-input-field {
            width: 100%;
            padding: 1.25rem 2rem 1.25rem 4rem !important;
            border-radius: 9999px;
            background-color: white;
            border: 2px solid transparent;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            font-size: 1.125rem;
            transition: all 0.3s ease;
        }

        .search-input-field:focus {
            outline: none;
            border-color: #10b981;
        }

        .search-wrapper:focus-within .search-icon-inside {
            color: #10b981;
        }
    </style>
</head>

<body class="bg-gray-50 overflow-x-hidden">

    <!-- Header (Consistent with index.php) -->
    <header class="fixed top-0 w-full bg-white/95 backdrop-blur-3xl shadow-lg border-b-4 border-emerald-500 z-50 transition-all duration-500">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="flex items-center gap-4 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 via-orange-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg group-hover:scale-110 transition-all duration-300">
                        <i class="fas fa-wheat-awn"></i>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-2xl lg:text-3xl font-black text-gray-800 tracking-tight" data-lang="logo">AgriCare</h1>
                        <p class="text-xs font-bold text-emerald-600 tracking-wider uppercase hidden sm:block" data-lang="tagline">Gujarat's Smart Farming Platform</p>
                    </div>
                </a>

                <nav class="hidden xl:flex items-center gap-1">
                    <a href="index.php" class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all" data-lang="home">🏠 Home</a>
                    <a href="market.php" class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all" data-lang="market">📈 Market</a>
                    <a href="weather.php" class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all" data-lang="weather">🌤️ Weather</a>
                    <a href="qna.php" class="text-emerald-600 bg-emerald-50 font-bold px-6 py-3 rounded-full transition-all" data-lang="qna_nav">❓ Q&A</a>
                    <a href="about.php" class="text-gray-600 hover:text-emerald-600 font-bold px-6 py-3 rounded-full hover:bg-emerald-50 transition-all" data-lang="contact">ℹ️ About</a>
                </nav>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex bg-gray-100 p-1 rounded-xl">
                        <button onclick="changeLang('en')" class="lang-btn px-3 py-1 text-sm font-bold rounded-lg transition-all" data-lang-key="en">EN</button>
                        <button onclick="changeLang('gu')" class="lang-btn px-3 py-1 text-sm font-bold rounded-lg transition-all" data-lang-key="gu">ગુ</button>
                        <button onclick="changeLang('hi')" class="lang-btn px-3 py-1 text-sm font-bold rounded-lg transition-all" data-lang-key="hi">हिं</button>
                    </div>
                    <a href="login.php" class="hidden lg:inline-flex text-emerald-700 font-bold py-3 px-6" data-lang="loginBtn">Login</a>
                    <a href="register.php" class="hidden lg:inline-flex bg-gradient-to-r from-emerald-500 to-emerald-700 text-white font-bold py-3 px-8 rounded-full shadow-lg" data-lang="registerBtn">Register</a>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-32 pb-20">
        <div class="max-w-4xl mx-auto px-6">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl lg:text-6xl font-black text-gray-900 mb-6" data-lang="qna_title">Frequently Asked <span class="text-emerald-600">Questions</span></h1>
                <p class="text-xl text-gray-600" data-lang="qna_subtitle">Find quick answers to common questions about AgriCare and its features.</p>
            </div>

            <!-- Search Bar -->
            <div class="search-wrapper mb-10">
                <i class="fas fa-search search-icon-inside text-xl transition-colors"></i>
                <input type="text" id="qnaSearch" onkeyup="filterQuestions()" 
                    data-lang="qna_search_placeholder" placeholder="Search for a question..." 
                    class="search-input-field">
            </div>

            <!-- Categories -->
            <div class="flex flex-wrap gap-3 mb-12 justify-center">
                <button onclick="filterCategory('all')" class="category-btn active px-6 py-3 rounded-full font-bold transition-all border-2 border-gray-100 hover:border-emerald-200" data-lang="qna_cat_all">All Questions</button>
                <button onclick="filterCategory('market')" class="category-btn px-6 py-3 rounded-full font-bold transition-all border-2 border-gray-100 hover:border-emerald-200" data-lang="qna_cat_market">Market Data</button>
                <button onclick="filterCategory('disease')" class="category-btn px-6 py-3 rounded-full font-bold transition-all border-2 border-gray-100 hover:border-emerald-200" data-lang="qna_cat_disease">Disease Detection</button>
                <button onclick="filterCategory('general')" class="category-btn px-6 py-3 rounded-full font-bold transition-all border-2 border-gray-100 hover:border-emerald-200" data-lang="qna_cat_general">General Help</button>
            </div>

            <!-- FAQ Accordion -->
            <div id="qnaList" class="space-y-4">
                <!-- Q1 -->
                <div class="qna-accordion-item bg-white rounded-3xl shadow-md overflow-hidden border border-gray-100" data-category="general">
                    <button onclick="toggleAccordion(this)" class="w-full px-8 py-6 flex justify-between items-center text-left hover:bg-gray-50 transition-colors">
                        <span class="text-xl font-bold text-gray-800" data-lang="qna_q1">What is AgriCare?</span>
                        <i class="fas fa-chevron-down toggle-icon text-emerald-500 transition-transform duration-300"></i>
                    </button>
                    <div class="qna-accordion-content px-8">
                        <p class="text-gray-600 text-lg leading-relaxed" data-lang="qna_a1">AgriCare is a comprehensive digital platform designed for Gujarat's farmers. It provides real-time market prices (Mandi), hyper-local weather alerts, AI-based crop disease identification, and information on government subsidies.</p>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="qna-accordion-item bg-white rounded-3xl shadow-md overflow-hidden border border-gray-100" data-category="general">
                    <button onclick="toggleAccordion(this)" class="w-full px-8 py-6 flex justify-between items-center text-left hover:bg-gray-50 transition-colors">
                        <span class="text-xl font-bold text-gray-800" data-lang="qna_q2">Is AgriCare free to use?</span>
                        <i class="fas fa-chevron-down toggle-icon text-emerald-500 transition-transform duration-300"></i>
                    </button>
                    <div class="qna-accordion-content px-8">
                        <p class="text-gray-600 text-lg leading-relaxed" data-lang="qna_a2">Yes, AgriCare is a free platform dedicated to empowering farmers with technology and data to improve their crop yields and income.</p>
                    </div>
                </div>

                <!-- Q3 -->
                <div class="qna-accordion-item bg-white rounded-3xl shadow-md overflow-hidden border border-gray-100" data-category="market">
                    <button onclick="toggleAccordion(this)" class="w-full px-8 py-6 flex justify-between items-center text-left hover:bg-gray-50 transition-colors">
                        <span class="text-xl font-bold text-gray-800" data-lang="qna_q3">How often is the market data updated?</span>
                        <i class="fas fa-chevron-down toggle-icon text-emerald-500 transition-transform duration-300"></i>
                    </button>
                    <div class="qna-accordion-content px-8">
                        <p class="text-gray-600 text-lg leading-relaxed" data-lang="qna_a3">Our market data is synchronized hourly with the official government records (Data.gov.in). Most Mandis update their arrival prices once a day.</p>
                    </div>
                </div>

                <!-- Q4 -->
                <div class="qna-accordion-item bg-white rounded-3xl shadow-md overflow-hidden border border-gray-100" data-category="market">
                    <button onclick="toggleAccordion(this)" class="w-full px-8 py-6 flex justify-between items-center text-left hover:bg-gray-50 transition-colors">
                        <span class="text-xl font-bold text-gray-800" data-lang="qna_q4">Why can't I find a specific Mandi/Market?</span>
                        <i class="fas fa-chevron-down toggle-icon text-emerald-500 transition-transform duration-300"></i>
                    </button>
                    <div class="qna-accordion-content px-8">
                        <p class="text-gray-600 text-lg leading-relaxed" data-lang="qna_a4">We cover all major APMCs in Gujarat. If a specific small yard is missing, it might be because they haven't uploaded their data to the central government portal yet.</p>
                    </div>
                </div>

                <!-- Q5 -->
                <div class="qna-accordion-item bg-white rounded-3xl shadow-md overflow-hidden border border-gray-100" data-category="disease">
                    <button onclick="toggleAccordion(this)" class="w-full px-8 py-6 flex justify-between items-center text-left hover:bg-gray-50 transition-colors">
                        <span class="text-xl font-bold text-gray-800" data-lang="qna_q5">How accurate is the Crop Disease Detector?</span>
                        <i class="fas fa-chevron-down toggle-icon text-emerald-500 transition-transform duration-300"></i>
                    </button>
                    <div class="qna-accordion-content px-8">
                        <p class="text-gray-600 text-lg leading-relaxed" data-lang="qna_a5">Our AI model is trained on thousands of plant images and has an accuracy of over 90% for supported crops. However, for critical decisions, we always recommend consulting a local agricultural expert.</p>
                    </div>
                </div>

                <!-- Q6 -->
                <div class="qna-accordion-item bg-white rounded-3xl shadow-md overflow-hidden border border-gray-100" data-category="disease">
                    <button onclick="toggleAccordion(this)" class="w-full px-8 py-6 flex justify-between items-center text-left hover:bg-gray-50 transition-colors">
                        <span class="text-xl font-bold text-gray-800" data-lang="qna_q6">What should I do if the AI cannot identify the disease?</span>
                        <i class="fas fa-chevron-down toggle-icon text-emerald-500 transition-transform duration-300"></i>
                    </button>
                    <div class="qna-accordion-content px-8">
                        <p class="text-gray-600 text-lg leading-relaxed" data-lang="qna_a6">Ensure you take a clear, well-lit photo of a single leaf. Avoid blurry images or photos containing multiple types of plants. If it still fails, the specific crop or disease might not be in our database yet.</p>
                    </div>
                </div>

                <!-- Q7 -->
                <div class="qna-accordion-item bg-white rounded-3xl shadow-md overflow-hidden border border-gray-100" data-category="general">
                    <button onclick="toggleAccordion(this)" class="w-full px-8 py-6 flex justify-between items-center text-left hover:bg-gray-50 transition-colors">
                        <span class="text-xl font-bold text-gray-800" data-lang="qna_q7">How do I change the application language?</span>
                        <i class="fas fa-chevron-down toggle-icon text-emerald-500 transition-transform duration-300"></i>
                    </button>
                    <div class="qna-accordion-content px-8">
                        <p class="text-gray-600 text-lg leading-relaxed" data-lang="qna_a7">You can switch between English, Gujarati, and Hindi anytime using the language toggle buttons located at the top right of the navigation bar.</p>
                    </div>
                </div>

                <div id="noResults" class="hidden text-center py-12">
                    <div class="text-6xl mb-4 text-gray-200"><i class="fas fa-search"></i></div>
                    <p class="text-xl text-gray-500" data-lang="qna_no_results">No questions match your search.</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-900 text-white pt-20 pb-10">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
            <div class="flex justify-center items-center text-gray-500 text-sm">
                <p data-lang="copyright">© 2026 AgriCare. Made with ❤️ for Farmers.</p>
            </div>
        </div>
    </footer>

    <script src="js/translations.js"></script>
    <script>
        function toggleAccordion(btn) {
            const item = btn.parentElement;
            const isActive = item.classList.contains('active');
            
            // Close all others
            document.querySelectorAll('.qna-accordion-item').forEach(i => i.classList.remove('active'));
            
            // Toggle current
            if (!isActive) {
                item.classList.add('active');
            }
        }

        function filterCategory(cat) {
            // Update buttons
            document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const items = document.querySelectorAll('.qna-accordion-item');
            items.forEach(item => {
                if (cat === 'all' || item.getAttribute('data-category') === cat) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            checkEmpty();
        }

        function filterQuestions() {
            const query = document.getElementById('qnaSearch').value.toLowerCase().trim();
            const items = document.querySelectorAll('.qna-accordion-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            checkEmpty();
        }

        function checkEmpty() {
            const items = document.querySelectorAll('.qna-accordion-item');
            const visible = Array.from(items).some(i => i.style.display !== 'none');
            const noResults = document.getElementById('noResults');
            
            if (!visible) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        function changeLang(lang) {
            localStorage.setItem('agricare_lang', lang);
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow', 'text-emerald-700');
                btn.classList.add('text-gray-500');
            });
            document.querySelectorAll(`button[data-lang-key="${lang}"]`).forEach(btn => {
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
            
            // Update search placeholder
            const searchInput = document.getElementById('qnaSearch');
            if (translations[lang] && translations[lang]['qna_search_placeholder']) {
                searchInput.placeholder = translations[lang]['qna_search_placeholder'];
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const initialLang = localStorage.getItem('agricare_lang') || 'en';
            changeLang(initialLang);
        });
    </script>
</body>

</html>
