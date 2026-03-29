<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AgriCare Assistant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="output.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; color: #111827; }

        /* Sidebar */
        aside { background: #ffffff; border-right: 1px solid #e5e7eb; }
        .history-group-title { font-size: 0.68rem; font-weight: 700; color: #9ca3af; padding: 0.875rem 0.5rem 0.25rem; letter-spacing: 0.08em; text-transform: uppercase; }
        .nav-item { transition: background 0.15s; color: #374151; border-radius: 0.625rem; }
        .nav-item:hover { background-color: #f3f4f6; }
        .nav-item.active { background-color: #ecfdf5; color: #059669; font-weight: 600; }

        /* Main area */
        main { background-color: #f9fafb; }

        /* Input box */
        #chatForm .input-box {
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 1.25rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 6px rgba(0,0,0,0.06);
        }
        #chatForm .input-box:focus-within {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16,185,129,0.12);
        }
        #chatInput { color: #111827; }
        #chatInput::placeholder { color: #9ca3af; }

        /* Action buttons in toolbar */
        .toolbar-btn { color: #9ca3af; transition: color 0.15s, background 0.15s; border-radius: 0.5rem; padding: 0.4rem; }
        .toolbar-btn:hover { color: #059669; background: #f0fdf4; }

        /* Send button */
        #sendBtn {
            background: #059669;
            color: white;
            border-radius: 0.75rem;
            width: 2.1rem; height: 2.1rem;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.15s, transform 0.1s;
            box-shadow: 0 2px 8px rgba(5,150,105,0.25);
        }
        #sendBtn:hover { background: #047857; transform: scale(1.05); }
        #sendBtn:disabled { background: #d1d5db; color: #9ca3af; box-shadow: none; transform: none; }

        /* Image preview */
        #imagePreviewContainer { background: #f3f4f6; border: 1px solid #e5e7eb; }

        /* Footer disclaimer */
        .disclaimer { color: #9ca3af; }

        /* Top-right controls */
        .top-controls { color: #6b7280; }
        .top-controls button:hover { color: #059669; }

        /* Mobile header */
        .mobile-header { background: #ffffff; border-bottom: 1px solid #e5e7eb; color: #374151; }

        /* Prose links */
        .prose a { color: #059669; text-decoration: underline; }

        /* Sidebar bottom section */
        .sidebar-bottom { border-top: 1px solid #e5e7eb; }
        .profile-row:hover { background: #f3f4f6; border-radius: 0.625rem; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* Sidebar quick prompt buttons */
        .prompt-chip { 
            color: #374151; 
            border-radius: 0.625rem; 
            transition: background 0.15s;
        }
        .prompt-chip:hover { background: #f3f4f6; }

        /* New chat button */
        #newChatBtn { color: #374151; border-radius: 0.625rem; transition: background 0.15s; }
        #newChatBtn:hover { background: #f3f4f6; }

        /* Find shops button */
        #findShopsBtn { color: #059669; border-radius: 0.625rem; transition: background 0.15s; }
        #findShopsBtn:hover { background: #ecfdf5; }

        /* Sidebar tools border */
        .tools-border { border-bottom: 1px solid #e5e7eb; }

        /* Error bar */
        #chatError { background: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; }

        /* Model badge */
        #modelBadge { color: #9ca3af; }

        /* Input gradient bg  */
        .input-gradient { background: linear-gradient(to top, #f9fafb 70%, transparent); }
    </style>
</head>

<body class="h-screen w-screen flex overflow-hidden">
    <div id="progressBar"></div>

    <!-- Sidebar -->
    <aside class="w-[260px] flex-shrink-0 flex flex-col hidden md:flex">
        <!-- New Chat -->
        <div class="p-3 flex items-center justify-between">
            <button id="newChatBtn" class="flex-1 flex items-center gap-3 p-3 text-sm text-left">
                <i class="fas fa-plus text-emerald-600"></i>
                <span class="font-semibold" data-lang="newChat">New chat</span>
                <span class="ml-auto text-xs opacity-40"><i class="fas fa-pen-to-square"></i></span>
            </button>
        </div>

        <!-- Quick Tools -->
        <div class="px-3 pb-2 tools-border">
            <button id="findShopsBtn" class="w-full flex items-center gap-3 p-3 text-sm text-left font-medium">
                <i class="fas fa-map-marked-alt"></i> <span data-lang="findShops">Find Nearby Shops</span>
            </button>
            <div class="grid grid-cols-1 gap-1 mt-1">
                <button class="prompt-btn prompt-chip w-full flex items-center gap-3 p-2.5 text-xs text-left" data-prompt-key="mandiPrompt">
                    <i class="fas fa-chart-line text-emerald-500"></i> <span data-lang="mandiPrices">Mandi Prices</span>
                </button>
                <button class="prompt-btn prompt-chip w-full flex items-center gap-3 p-2.5 text-xs text-left" data-prompt-key="subsidyPrompt">
                    <i class="fas fa-hand-holding-dollar text-blue-500"></i> <span data-lang="subsidies">Subsidies</span>
                </button>
            </div>
        </div>

        <!-- History -->
        <div class="flex-1 overflow-y-auto px-3 py-2" id="sessionHistoryList">
            <!-- History populated by JS -->
        </div>

        <!-- Profile / Footer -->
        <div class="p-3 sidebar-bottom flex flex-col gap-1 text-sm">
            <div class="profile-row flex items-center gap-3 p-3 cursor-default">
                <div class="w-7 h-7 rounded-lg bg-emerald-600 flex items-center justify-center text-white text-xs">
                    <i class="fas fa-user-tractor"></i>
                </div>
                <div id="profileSummary" class="truncate flex-1 text-xs text-gray-600" data-lang="loadingProfile">Loading profile...</div>
            </div>
            <a href="../dashboard/farmer.php" class="flex items-center gap-3 p-3 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-emerald-700 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i> <span data-lang="toDashboard">To Dashboard</span>
            </a>
            <p id="modelBadge" class="px-3 py-1 text-[10px] mx-auto" data-lang="aiModelPending">AI model pending</p>
        </div>
    </aside>

    <!-- Main Chat Area -->
    <main class="flex-1 flex flex-col min-w-0 relative">
        <!-- Mobile header -->
        <header class="mobile-header h-12 flex items-center px-4 md:hidden">
            <button class="text-gray-400 hover:text-gray-700 mr-3"><i class="fas fa-bars"></i></button>
            <span class="font-semibold text-sm text-gray-800">AgriCare</span>
            <div class="ml-auto flex gap-4 text-gray-400">
                <button id="muteBtnMobile" class="text-xs hover:text-gray-700" title="Toggle AI Voice"><i class="fas fa-volume-up"></i></button>
                <button id="clearChatBtn" class="text-xs hover:text-red-500"><i class="fas fa-trash"></i></button>
            </div>
        </header>

        <!-- Desktop top-right controls -->
        <div class="top-controls hidden md:flex absolute top-4 right-4 gap-4 z-10 text-xs font-medium">
            <button id="muteBtnDesktop" class="hover:text-emerald-600 transition" title="Toggle AI voice reproduction">
                <i class="fas fa-volume-up mr-1"></i><span data-lang="voice">Voice</span> <span id="voiceStateLabel">On</span>
            </button>
            <button id="clearChatBtnDesktop" class="hover:text-red-500 transition" data-lang="clearChat">Clear chat</button>
        </div>

        <!-- Messages Container -->
        <div id="chatMessages" class="flex-1 overflow-y-auto w-full pb-36 pt-6 scroll-smooth">
            <!-- Populated by JS -->
        </div>

        <!-- Error Bar -->
        <div id="chatError" class="hidden absolute top-12 left-1/2 -translate-x-1/2 z-20 px-4 py-2 rounded-xl text-sm shadow-md backdrop-blur-sm max-w-sm truncate"></div>

        <!-- Input Area -->
        <div class="input-gradient absolute bottom-0 left-0 w-full pt-8 md:pt-12">
            <div class="max-w-3xl mx-auto px-4 pb-4 md:pb-6 relative">

                <form id="chatForm">
                    <div id="imagePreviewContainer" class="hidden absolute -top-16 left-4 p-1.5 rounded-xl border shadow-md">
                        <img id="imagePreview" src="" class="h-12 w-12 object-cover rounded-lg">
                        <button type="button" id="removeImgBtn" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] shadow">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="input-box relative flex items-center">
                        <div class="flex items-center pl-3 gap-1">
                            <input type="file" id="imageInput" class="hidden" accept="image/*">
                            <button type="button" id="imgBtn" class="toolbar-btn" title="Attach image">
                                <i class="fas fa-paperclip text-base"></i>
                            </button>
                            <button type="button" id="micBtn" class="toolbar-btn" title="Voice dictation">
                                <i class="fas fa-microphone text-base"></i>
                            </button>
                            <button type="button" id="muteBtnInput" class="toolbar-btn" title="Toggle AI Auto-Read">
                                <i class="fas fa-volume-up text-base"></i>
                            </button>
                        </div>

                        <textarea id="chatInput" rows="1"
                            class="flex-1 bg-transparent py-4 px-2 min-h-[56px] max-h-[200px] resize-none focus:outline-none text-[15px]"
                            data-placeholder-key="inputPlaceholder"
                            placeholder="Message AgriCare Assistant..."></textarea>

                        <div class="pr-3 flex items-center">
                            <button type="submit" id="sendBtn">
                                <i class="fas fa-arrow-up text-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="disclaimer mt-2 text-center text-[11px]" data-lang="disclaimer">
                    AgriBot can make mistakes. Please verify important agricultural information.
                </div>
            </div>
        </div>
    </main>

    <script src="js/chatbot.js"></script>
    <script>
        // ── Chat Page Translations ──────────────────────────────────────────
        const CHAT_TRANSLATIONS = {
            en: {
                newChat: 'New chat',
                findShops: 'Find Nearby Shops',
                mandiPrices: 'Mandi Prices',
                subsidies: 'Subsidies',
                loadingProfile: 'Loading profile...',
                toDashboard: 'To Dashboard',
                aiModelPending: 'AI model pending',
                voice: 'Voice',
                clearChat: 'Clear chat',
                inputPlaceholder: 'Message AgriCare Assistant...',
                disclaimer: 'AgriBot can make mistakes. Please verify important agricultural information.',
                // JS dynamic strings (exposed globally)
                welcomeTitle: 'How can I help you farm today?',
                welcomeSubtitle: 'Ask about subsidies, crop market prices, daily tasks, or upload a photo of a pest for AI identification.',
                thinking: 'Thinking...',
                gettingLocation: '📍 Getting your location & finding nearby shops...',
                noHistoryYet: 'No history yet',
                pestDetected: 'Pest / Disease Detected',
                recommendedPesticides: '💊 Recommended Pesticides',
                nearbyShops: '🏪 Nearby Agricultural Shops',
                noShopsFound: 'No nearby shops found in your current district profile.',
                noPestMatch: 'No specific matches found in your local regional database.',
                nearbyShopsTitle: 'Nearby Shops',
                searchMoreShops: '🔍 Search More Shops on Google Maps',
                findShopsMsg: 'Find nearby agricultural shops 🗺️',
                mandiPrompt: 'Show recent mandi price guidance for my crop and district.',
                subsidyPrompt: 'Which subsidy can help with drip irrigation for my farm?',
            },
            gu: {
                newChat: 'નવી ચેટ',
                findShops: 'નજીકની દુકાનો શોધો',
                mandiPrices: 'મંડી ભાવ',
                subsidies: 'સબસિડી',
                loadingProfile: 'પ્રોફાઇલ લોડ થઈ રહ્યો છે...',
                toDashboard: 'ડૅશબોર્ડ પર જાઓ',
                aiModelPending: 'AI મોડેલ પ્રતિક્ષા',
                voice: 'અવાજ',
                clearChat: 'ચેટ સાફ કરો',
                inputPlaceholder: 'AgriCare સહાયકને સંદેશ...',
                disclaimer: 'AgriBot ભૂલ કરી શકે છે. કૃષિ માહિતી ચકાસો.',
                welcomeTitle: 'હું આજે ખેતીમાં કઈ રીતે મદદ કરી શકું?',
                welcomeSubtitle: 'સબસિડી, બજાર ભાવ, દૈનિક કાર્ય વિશે પૂછો અથવા AI ઓળખ માટે જીવાત ફોટો અપલોડ કરો.',
                thinking: 'વિચારી રહ્યો છું...',
                gettingLocation: '📍 સ્થાન મેળવી રહ્યા છીએ અને નજીકની દુકાનો શોધી રહ્યા છીએ...',
                noHistoryYet: 'હજી કોઈ ઇતિહાસ નથી',
                pestDetected: 'જીવાત / રોગ ઓળખ',
                recommendedPesticides: '💊 ભલામણ કરેલ જંતુનાશક',
                nearbyShops: '🏪 નજીકની કૃષિ દુકાનો',
                noShopsFound: 'તમારા જિલ્લામાં નજીકની કોઈ દુકાન નથી.',
                noPestMatch: 'તમારા સ્થાનિક ડેટાબેઝમાં કોઈ ચોક્કસ મેળ નથી.',
                nearbyShopsTitle: 'નજીકની દુકાનો',
                searchMoreShops: '🔍 Google Maps પર વધુ દુકાન શોધો',
                findShopsMsg: 'નજીકની કૃષિ દુકાનો શોધો 🗺️',
                mandiPrompt: 'મારા પાક અને જિલ્લા માટે તાજેતરના મંડી ભાવ બતાવો.',
                subsidyPrompt: 'ટપક સિંચાઈ માટે કઈ સબસિડી મારી ખેતી માટે ઉપયોગી?',
            },
            hi: {
                newChat: 'नई चैट',
                findShops: 'नजदीकी दुकानें खोजें',
                mandiPrices: 'मंडी भाव',
                subsidies: 'सब्सिडी',
                loadingProfile: 'प्रोफ़ाइल लोड हो रहा है...',
                toDashboard: 'डैशबोर्ड पर जाएं',
                aiModelPending: 'AI मॉडल प्रतीक्षित',
                voice: 'आवाज़',
                clearChat: 'चैट साफ करें',
                inputPlaceholder: 'AgriCare सहायक को संदेश...',
                disclaimer: 'AgriBot गलती कर सकता है। कृषि जानकारी सत्यापित करें।',
                welcomeTitle: 'आज मैं खेती में कैसे मदद कर सकता हूँ?',
                welcomeSubtitle: 'सब्सिडी, बाजार भाव, दैनिक कार्य के बारे में पूछें या AI पहचान के लिए कीट फोटो अपलोड करें।',
                thinking: 'सोच रहा हूँ...',
                gettingLocation: '📍 स्थान प्राप्त कर रहे हैं और नजदीकी दुकानें खोज रहे हैं...',
                noHistoryYet: 'अभी कोई इतिहास नहीं',
                pestDetected: 'कीट / रोग पहचान',
                recommendedPesticides: '💊 अनुशंसित कीटनाशक',
                nearbyShops: '🏪 नजदीकी कृषि दुकानें',
                noShopsFound: 'आपके जिले में कोई नजदीकी दुकान नहीं मिली।',
                noPestMatch: 'आपके स्थानीय डेटाबेस में कोई विशिष्ट मिलान नहीं।',
                nearbyShopsTitle: 'नजदीकी दुकानें',
                searchMoreShops: '🔍 Google Maps पर अधिक दुकानें खोजें',
                findShopsMsg: 'नजदीकी कृषि दुकानें खोजें 🗺️',
                mandiPrompt: 'मेरी फसल और जिले के लिए हालिया मंडी भाव दिखाएं।',
                subsidyPrompt: 'ड्रिप सिंचाई के लिए कौन सी सब्सिडी मेरी खेती में मदद करेगी?',
            }
        };

        function applyChatLang(lang) {
            const t = CHAT_TRANSLATIONS[lang] || CHAT_TRANSLATIONS['en'];

            // Static data-lang elements
            document.querySelectorAll('[data-lang]').forEach(el => {
                const key = el.getAttribute('data-lang');
                if (t[key] !== undefined) el.textContent = t[key];
            });

            // Textarea placeholder
            document.querySelectorAll('[data-placeholder-key]').forEach(el => {
                const key = el.getAttribute('data-placeholder-key');
                if (t[key]) el.placeholder = t[key];
            });

            // Prompt buttons - localised prompt text
            document.querySelectorAll('[data-prompt-key]').forEach(btn => {
                const key = btn.getAttribute('data-prompt-key');
                if (t[key]) btn.setAttribute('data-prompt', t[key]);
            });

            // Font for Gujarati / Hindi
            document.body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') document.body.classList.add('gu-text');
            if (lang === 'hi') document.body.classList.add('hi-text');

            // Expose to chatbot.js
            window.CHAT_T = t;
            window.CHAT_LANG = lang;
        }

        // Auto-detect language from user session or localStorage
        (function () {
            let lang = 'en';
            try {
                const user = JSON.parse(sessionStorage.getItem('agricare_user') || 'null');
                lang = (user && user.pref_lang) || localStorage.getItem('agricare_lang') || 'en';
            } catch (e) {}
            if (!['en', 'gu', 'hi'].includes(lang)) lang = 'en';
            applyChatLang(lang);
        })();

        // Universal Scroll Progress Bar
        document.addEventListener('scroll', function(e) {
            let target = e.target;
            if (target === document) target = document.documentElement;
            let winScroll = target.scrollTop;
            let height = target.scrollHeight - target.clientHeight;
            if (height <= 0) return;
            let scrolled = (winScroll / height) * 100;
            const bar = document.getElementById("progressBar");
            if (bar) {
                bar.style.width = scrolled + "%";
                bar.style.height = "5px";
                bar.style.background = "linear-gradient(90deg, #10b981, #f59e0b)";
                bar.style.position = "fixed";
                bar.style.top = "0";
                bar.style.left = "0";
                bar.style.zIndex = "9999";
                bar.style.transition = "width 0.1s";
            }
        }, true);
    </script>
</body>

</html>
