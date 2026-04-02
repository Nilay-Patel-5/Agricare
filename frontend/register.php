<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | AgriCare</title>
    <link rel="stylesheet" href="./output.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&family=Noto+Sans+Gujarati:wght@400;500;700&family=Noto+Sans+Devanagari:wght@400;500;700&display=swap" rel="stylesheet">
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .slider-dropdown { position: relative; }
        .slider-dropdown .sd-panel {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0;
            background: white; border: 1px solid #d1fae5; border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(16,185,129,0.12);
            z-index: 50; max-height: 0; overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.4,0,0.2,1), opacity 0.25s;
            opacity: 0;
        }
        .slider-dropdown.open .sd-panel { max-height: 220px; opacity: 1; }
        .sd-panel .sd-search {
            width: 100%; padding: 8px 14px; border: none; border-bottom: 1px solid #d1fae5;
            outline: none; font-size: 0.8rem; border-radius: 1rem 1rem 0 0; background: #f0fdf4;
        }
        .sd-panel .sd-list { overflow-y: auto; max-height: 170px; }
        .sd-panel .sd-item {
            padding: 9px 14px; font-size: 0.85rem; cursor: pointer; color: #1f2937;
            transition: background 0.15s;
        }
        .sd-item:hover, .sd-item.active { background: #ecfdf5; color: #059669; font-weight: 600; }
        .sd-trigger {
            appearance: none; border-radius: 1rem; width: 100%; padding: 12px 40px 12px 16px;
            border: 1px solid #e5e7eb; color: #1f2937; background: white;
            font-size: 0.875rem; cursor: pointer; text-align: left;
            transition: border-color 0.2s, box-shadow 0.2s;
            display: flex; align-items: center; justify-content: space-between;
        }
        .sd-trigger:focus, .slider-dropdown.open .sd-trigger {
            outline: none; border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
        }
        .sd-trigger .sd-arrow { transition: transform 0.3s; color: #6b7280; font-size: 0.75rem; }
        .slider-dropdown.open .sd-trigger .sd-arrow { transform: rotate(180deg); }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div id="progressBar"></div>
    <div class="flex w-full h-screen bg-white overflow-hidden">
        <div class="hidden lg:flex lg:w-1/2 relative bg-emerald-900 overflow-hidden items-end justify-start">
            <div class="absolute inset-0 bg-emerald-900/30 mix-blend-multiply z-10"></div>
            <img src="assets/images/Crop.jpg" alt="Crop" class="absolute inset-0 w-full h-full object-cover object-top z-0">
            <div class="relative z-20 text-white p-12 w-full bg-gradient-to-t from-emerald-900 via-emerald-900/80 to-transparent pt-32">
                <h1 class="text-4xl font-black mb-3 tracking-tight drop-shadow-lg" data-lang="heroTitle">Join AgriCare</h1>
                <p class="text-lg text-emerald-50 max-w-lg font-medium drop-shadow-md leading-relaxed" data-lang="heroSubtitle">Create your farmer account to access weather updates, market prices, and crop guidance.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-teal-50">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-yellow-200 rounded-full blur-3xl opacity-50 z-0"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-emerald-200 rounded-full blur-3xl opacity-50 z-0"></div>

            <div class="max-w-xl w-full space-y-6 relative z-10 glass-card p-6 sm:p-8 rounded-[2rem] border border-white/60 shadow-xl">
                <div class="text-center relative z-10">
                    <div class="flex justify-start mb-2">
                        <a href="index.php" class="inline-flex items-center gap-2 text-gray-500 hover:text-emerald-600 transition-colors font-semibold text-sm">
                            <i class="fas fa-arrow-left"></i>
                            <span data-lang="backToHome">Back to Home</span>
                        </a>
                    </div>

                    <a href="index.php" class="inline-flex items-center gap-3 group mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-emerald-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-wheat-awn"></i>
                        </div>
                        <span class="text-2xl font-black text-gray-800 tracking-tight" data-lang="logo">AgriCare</span>
                    </a>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight" data-lang="createAccount">Create Account</h2>
                    <p class="mt-1 text-sm text-gray-500 font-medium">
                        <span data-lang="alreadyHaveAccount">Already have an account?</span>
                        <a href="login.php" class="text-emerald-600 hover:text-emerald-700 font-bold underline decoration-2 underline-offset-4" data-lang="logIn">Log in</a>
                    </p>
                </div>

                <div class="flex justify-center mb-4 relative z-10">
                    <div class="flex bg-gray-100/70 p-1 rounded-full shadow-sm">
                        <button type="button" onclick="changeLang('en')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 transition-all active:bg-emerald-500 active:text-white active:shadow-md" data-lang-key="en">EN</button>
                        <button type="button" onclick="changeLang('gu')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 hover:bg-emerald-100 transition-all" data-lang-key="gu">&#2711;&#2753;</button>
                        <button type="button" onclick="changeLang('hi')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 hover:bg-emerald-100 transition-all" data-lang-key="hi">&#2361;&#2367;&#2306;</button>
                    </div>
                </div>

                <form id="registerForm" class="mt-4 space-y-4 relative z-10" onsubmit="handleRegister(event)">
                    <input type="hidden" id="reg-role-input" value="farmer">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1" data-label="labelFullName"><span data-lang="labelFullName">Full Name</span> <span class="text-red-500">*</span></label>
                            <input id="full_name" type="text" required
                                class="appearance-none rounded-2xl block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all sm:text-sm"
                                placeholder="Nilay Rajeshbhai Patel" data-lang-placeholder="placeholderFullName" oninput="validateFullName()">
                            <p id="full-name-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="fullNameError">Enter full name in Indian format using only letters and spaces, like Nilay Rajeshbhai Patel.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1"><span data-lang="labelEmail">Email</span></label>
                            <input id="email" type="email"
                                class="appearance-none rounded-2xl block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all sm:text-sm"
                                placeholder="name@example.com">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1"><span data-lang="labelPhone">Phone Number</span> <span class="text-red-500">*</span></label>
                            <input id="phone_no" type="tel" required
                                class="appearance-none rounded-2xl block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all sm:text-sm"
                                placeholder="9876543210" oninput="validatePhone()">
                            <p id="phone-reg-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="phoneError">Please enter a valid 10-digit mobile number.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1"><span data-lang="labelDistrict">District</span> <span class="text-red-500">*</span></label>
                            <input type="hidden" id="district" required>
                            <div class="slider-dropdown" id="dd-district">
                                <button type="button" class="sd-trigger" onclick="toggleDropdown('dd-district')">
                                    <span id="dd-district-label" class="text-gray-400">Select District</span>
                                    <i class="sd-arrow fas fa-chevron-down"></i>
                                </button>
                                <div class="sd-panel">
                                    <input type="text" class="sd-search" placeholder="Search district..." oninput="filterList('dd-district', this.value)">
                                    <div class="sd-list" id="dd-district-list"></div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1"><span data-lang="labelCity">City/Area</span> <span class="text-red-500">*</span></label>
                            <input type="hidden" id="city" required>
                            <div class="slider-dropdown" id="dd-city">
                                <button type="button" class="sd-trigger" onclick="toggleDropdown('dd-city')">
                                    <span id="dd-city-label" class="text-gray-400">Select District First</span>
                                    <i class="sd-arrow fas fa-chevron-down"></i>
                                </button>
                                <div class="sd-panel">
                                    <input type="text" class="sd-search" placeholder="Search city..." oninput="filterList('dd-city', this.value)">
                                    <div class="sd-list" id="dd-city-list"></div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1"><span data-lang="labelPincode">Pincode</span> <span class="text-red-500">*</span></label>
                            <input id="pincode" type="text" required maxlength="6"
                                class="appearance-none rounded-2xl block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all sm:text-sm"
                                placeholder="392001">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1"><span data-lang="labelPrefLang">Preferred Language</span> <span class="text-red-500">*</span></label>
                            <input type="hidden" id="pref_lang" required>
                            <div class="slider-dropdown" id="dd-lang">
                                <button type="button" class="sd-trigger" onclick="toggleDropdown('dd-lang')">
                                    <span id="dd-lang-label" class="text-gray-400">Select Language</span>
                                    <i class="sd-arrow fas fa-chevron-down"></i>
                                </button>
                                <div class="sd-panel">
                                    <div class="sd-list" id="dd-lang-list"></div>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1"><span data-lang="labelPin">6-Digit PIN</span> <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input id="pin" type="password" inputmode="numeric" required maxlength="6"
                                    class="appearance-none rounded-2xl block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all sm:text-sm pr-10"
                                    placeholder="******" oninput="validatePin()">
                                <button type="button" onclick="togglePin()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-emerald-600 transition-colors focus:outline-none">
                                    <i id="pin-icon" class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <p id="pin-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="pinError">Please enter exactly 6 digits.</p>
                        </div>
                    </div>

                    <div>
                        <button id="submit-btn" type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-2xl text-white bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 shadow-lg hover:shadow-emerald-500/30 hover:-translate-y-0.5 transition-all">
                            <span data-lang="submitBtn">Create Account</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let pinVisible = false;

        const translations = {
            en: {
                backToHome: 'Back to Home',
                logo: 'AgriCare',
                createAccount: 'Create Account',
                alreadyHaveAccount: 'Already have an account?',
                logIn: 'Log in',
                labelFullName: 'Full Name',
                labelEmail: 'Email (Optional)',
                labelPhone: 'Phone Number',
                labelDistrict: 'District',
                labelCity: 'City/Area',
                labelPincode: 'Pincode',
                labelPrefLang: 'Preferred Language',
                labelPin: '6-Digit PIN',
                placeholderFullName: 'Nilay Rajeshbhai Patel',
                fullNameError: 'Enter full name in Indian format using only letters and spaces, like Nilay Rajeshbhai Patel.',
                placeholderDistrict: 'Select District',
                placeholderCity: 'Select District First',
                placeholderLang: 'Select Language',
                phoneError: 'Please enter a valid 10-digit mobile number.',
                pinError: 'Please enter exactly 6 digits.',
                submitBtn: 'Create Account',
                heroTitle: 'Join AgriCare',
                heroSubtitle: 'Create your farmer account to access weather updates, market prices, and crop guidance.'
            },
            gu: {
                backToHome: 'હોમ પર પાછા જાઓ',
                logo: 'એગ્રીકેર',
                createAccount: 'ખાતું બનાવો',
                alreadyHaveAccount: 'પહેલેથી ખાતું છે?',
                logIn: 'લૉગ ઇન કરો',
                labelFullName: 'પૂરું નામ',
                labelEmail: 'ઈમેલ (વૈકલ્પિક)',
                labelPhone: 'મોબાઈલ નંબર',
                labelDistrict: 'જિલ્લો',
                labelCity: 'શહેર/વિસ્તાર',
                labelPincode: 'પિનકોડ',
                labelPrefLang: 'પ્રિય ભાષા',
                labelPin: '6-અંકનો પિન',
                placeholderFullName: 'નિલય રાજેશભાઈ પટેલ',
                fullNameError: 'ફક્ત અક્ષરો અને જગ્યાઓનો ઉપયોગ કરીને ભારતીય ફોર્મેટમાં પૂરું નામ દાખલ કરો, જેમ કે નિલય રાજેશભાઈ પટેલ.',
                placeholderDistrict: 'જિલ્લો પસંદ કરો',
                placeholderCity: 'પહેલા જિલ્લો પસંદ કરો',
                placeholderLang: 'ભાષા પસંદ કરો',
                phoneError: 'કૃપા કરીને માન્ય 10-અંકનો મોબાઈલ નંબર દાખલ કરો.',
                pinError: 'કૃપા કરીને બરાબર 6 અંક દાખલ કરો.',
                submitBtn: 'ખાતું બનાવો',
                heroTitle: 'એગ્રીકેરમાં જોડાઓ',
                heroSubtitle: 'હવામાન અપડેટ, બજારના ભાવ અને પાક માર્ગદર્શન મેળવવા માટે તમારું ખેડૂત ખાતું બનાવો.'
            },
            hi: {
                backToHome: 'होम पर वापस जाएं',
                logo: 'एग्रीकेर',
                createAccount: 'खाता बनाएं',
                alreadyHaveAccount: 'पहले से खाता है?',
                logIn: 'लॉग इन करें',
                labelFullName: 'पूरा नाम',
                labelEmail: 'ईमेल (वैकल्पિક)',
                labelPhone: 'फ़ोन नंबर',
                labelDistrict: 'जिला',
                labelCity: 'शहर/क्षेत्र',
                labelPincode: 'पिनकोड',
                labelPrefLang: 'पसंदीदा भाषा',
                labelPin: '6-अंकों का पिन',
                placeholderFullName: 'निलय राजेशभाई पटेल',
                fullNameError: 'केवल अक्षरों और रिक्त स्थान का उपयोग करके भारतीय प्रारूप में पूरा नाम दर्ज करें, जैसे निलय राजेशभाई पटेल।',
                placeholderDistrict: 'जिला चुनें',
                placeholderCity: 'पहले जिला चुनें',
                placeholderLang: 'भाषा चुनें',
                phoneError: 'कृपया एक मान्य 10-अंकीय मोबाइल नंबर दर्ज करें।',
                pinError: 'कृपया ठीक 6 अंक दर्ज करें।',
                submitBtn: 'खाता बनाएं',
                heroTitle: 'एग्रीकेर से जुड़ें',
                heroSubtitle: 'मौसम अपडेट, बाजार मूल्य और फसल मार्गदर्शन प्राप्त करने के लिए अपना किसान खाता बनाएं।'
            }
        };

        function changeLang(lang) {
            localStorage.setItem('agricare_lang', lang);
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('bg-emerald-500', 'text-white');
                btn.classList.add('hover:bg-emerald-100');
            });

            const activeBtn = document.querySelector(`[data-lang-key="${lang}"]`);
            if (activeBtn) {
                activeBtn.classList.add('bg-emerald-500', 'text-white');
                activeBtn.classList.remove('hover:bg-emerald-100');
            }

            const t = translations[lang];
            document.querySelectorAll('[data-lang]').forEach(el => {
                const key = el.getAttribute('data-lang');
                if (t[key]) el.textContent = t[key];
            });

            // Update labels
            document.querySelectorAll('[data-label]').forEach(el => {
                const key = el.getAttribute('data-label');
                if (t[key]) el.childNodes[0].textContent = t[key] + ' ';
            });

            // Update placeholders for inputs
            document.querySelectorAll('[data-lang-placeholder]').forEach(el => {
                const key = el.getAttribute('data-lang-placeholder');
                if (t[key]) el.placeholder = t[key];
            });

            // Update placeholders and selections dynamically
            const dVal = document.getElementById('district').value;
            const districtLabel = document.getElementById('dd-district-label');
            if (dVal) districtLabel.textContent = typeof tTerm === 'function' ? tTerm(dVal) : dVal;
            else districtLabel.textContent = t.placeholderDistrict;

            const cVal = document.getElementById('city').value;
            const cityLabel = document.getElementById('dd-city-label');
            if (cVal) cityLabel.textContent = typeof tTerm === 'function' ? tTerm(cVal) : cVal;
            else cityLabel.textContent = t.placeholderCity;

            const lVal = document.getElementById('pref_lang').value;
            const langLabel = document.getElementById('dd-lang-label');
            if (lVal && typeof languages !== 'undefined') {
                const lObj = languages.find(l => l.value === lVal);
                if (lObj) langLabel.textContent = lObj[lang] || lObj.en;
            } else {
                langLabel.textContent = t.placeholderLang;
            }

            if (typeof renderList === 'function' && typeof districts !== 'undefined') {
                renderList(document.getElementById('dd-district-list'), districts, name => selectItem('dd-district', name));
                if (dVal) renderList(document.getElementById('dd-city-list'), districtCityMap[dVal] || [], n => selectItem('dd-city', n));
            }

            if (typeof languages !== 'undefined') {
                const langListEl = document.getElementById('dd-lang-list');
                if (langListEl) {
                    langListEl.innerHTML = '';
                    languages.forEach(langObj => {
                        const div = document.createElement('div');
                        div.className = 'sd-item';
                        div.textContent = langObj[lang] || langObj.en;
                        div.onclick = () => {
                            document.getElementById('pref_lang').value = langObj.value;
                            const curLang = localStorage.getItem('agricare_lang') || 'en';
                            document.getElementById('dd-lang-label').textContent = langObj[curLang] || langObj.en;
                            document.getElementById('dd-lang-label').classList.remove('text-gray-400');
                            document.getElementById('dd-lang').classList.remove('open');
                        };
                        langListEl.appendChild(div);
                    });
                }
            }

            document.body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') document.body.classList.add('gu-text');
            if (lang === 'hi') document.body.classList.add('hi-text');
        }

        const transMap = {
            'Ahmedabad': { gu: 'અમદાવાદ', hi: 'अहमदाबाद' }, 'Ahmedabad City': { gu: 'અમદાવાદ શહેર', hi: 'अहमदाबाद शहर' }, 'Dholka': { gu: 'ધોળકા', hi: 'धोलका' }, 'Sanand': { gu: 'સાણંદ', hi: 'साणंद' }, 'Viramgam': { gu: 'વિરમગામ', hi: 'विरमगाम' },
            'Amreli': { gu: 'અમરેલી', hi: 'अमरेली' }, 'Rajula': { gu: 'રાજુલા', hi: 'राजुला' }, 'Savarkundla': { gu: 'સાવરકુંડલા', hi: 'सावरकुंडला' }, 'Dhari': { gu: 'ધારી', hi: 'धारी' },
            'Anand': { gu: 'આણંદ', hi: 'आणंद' }, 'Petlad': { gu: 'પેટલાદ', hi: 'पेटलाद' }, 'Borsad': { gu: 'બોરસદ', hi: 'बोरसद' }, 'Umreth': { gu: 'ઉમરેઠ', hi: 'उमरेठ' },
            'Arvalli': { gu: 'અરવલ્લી', hi: 'अरवल्ली' }, 'Modasa': { gu: 'મોડાસા', hi: 'मोडासा' }, 'Bhiloda': { gu: 'ભિલોડા', hi: 'भिलोड़ा' }, 'Bayad': { gu: 'બાયડ', hi: 'बायड' },
            'Banaskantha': { gu: 'બનાસકાંઠા', hi: 'बनासकांठा' }, 'Palanpur': { gu: 'પાલનપુર', hi: 'पालनपुर' }, 'Deesa': { gu: 'ડીસા', hi: 'डीसा' }, 'Dhanera': { gu: 'ધાનેરા', hi: 'धानेरा' }, 'Tharad': { gu: 'થરાદ', hi: 'थराद' },
            'Bharuch': { gu: 'ભરૂચ', hi: 'भरूच' }, 'Ankleshwar': { gu: 'અંકલેશ્વર', hi: 'अंकलेश्वर' }, 'Jambusar': { gu: 'જંબુસર', hi: 'जंबूसर' },
            'Bhavnagar': { gu: 'ભાવનગર', hi: 'भावनगर' }, 'Palitana': { gu: 'પાલીતાણા', hi: 'पालीताना' }, 'Mahuva': { gu: 'મહુવા', hi: 'महुवा' },
            'Botad': { gu: 'બોટાદ', hi: 'बोटाद' }, 'Gadhada': { gu: 'ગઢડા', hi: 'गढ़ड़ा' }, 'Barwala': { gu: 'બરવાળા', hi: 'बरवाला' },
            'Chhota Udaipur': { gu: 'છોટા ઉદેપુર', hi: 'छोटा उदयपुर' }, 'Kawant': { gu: 'કવાંટ', hi: 'कवांट' }, 'Bodeli': { gu: 'બોડેલી', hi: 'बोडेली' },
            'Dahod': { gu: 'દાહોદ', hi: 'दाहोद' }, 'Limkheda': { gu: 'લીમખેડા', hi: 'लीमखेड़ा' }, 'Jhalod': { gu: 'ઝાલોદ', hi: 'झालोद' },
            'Dang': { gu: 'ડાંગ', hi: 'डांग' }, 'Ahwa': { gu: 'આહવા', hi: 'आहवा' }, 'Waghai': { gu: 'વઘઇ', hi: 'वघई' }, 'Subir': { gu: 'સુબીર', hi: 'सुबीर' },
            'Devbhoomi Dwarka': { gu: 'દેવભૂમિ દ્વારકા', hi: 'देवभूमि द्वारका' }, 'Khambhalia': { gu: 'ખંભાળિયા', hi: 'खंभालिया' }, 'Dwarka': { gu: 'દ્વારકા', hi: 'द्वारका' }, 'Bhanvad': { gu: 'ભાણવડ', hi: 'भाणवड' },
            'Gandhinagar': { gu: 'ગાંધીનગર', hi: 'गांधीनगर' }, 'Mansa': { gu: 'માણસા', hi: 'माणसा' }, 'Kalol': { gu: 'કલોલ', hi: 'कलोल' },
            'Gir Somnath': { gu: 'ગીર સોમનાથ', hi: 'गिर सोमनाथ' }, 'Veraval': { gu: 'વેરાવળ', hi: 'वेरावल' }, 'Una': { gu: 'ઊના', hi: 'ऊना' }, 'Kodinar': { gu: 'કોડીનાર', hi: 'कोडिनार' },
            'Jamnagar': { gu: 'જામનગર', hi: 'जामनगर' }, 'Dhrol': { gu: 'ધ્રોલ', hi: 'ध्रोल' }, 'Kalavad': { gu: 'કાલાવાડ', hi: 'कालावड' },
            'Junagadh': { gu: 'જૂનાગઢ', hi: 'जूनागढ़' }, 'Keshod': { gu: 'કેશોદ', hi: 'केशाेद' }, 'Mangrol': { gu: 'માંગરોળ', hi: 'मांगरोल' },
            'Kheda': { gu: 'ખેડા', hi: 'खेड़ा' }, 'Nadiad': { gu: 'નડિયાદ', hi: 'नडियाद' }, 'Kapadvanj': { gu: 'કપડવંજ', hi: 'कपडवंज' }, 'Matar': { gu: 'માતર', hi: 'मातर' },
            'Kutch': { gu: 'કચ્છ', hi: 'कच्छ' }, 'Bhuj': { gu: 'ભુજ', hi: 'भुज' }, 'Gandhidham': { gu: 'ગાંધીધામ', hi: 'गांधीधाम' }, 'Mandvi': { gu: 'માંડવી', hi: 'मांडवी' },
            'Mahisagar': { gu: 'મહીસાગર', hi: 'महीसागर' }, 'Lunawada': { gu: 'લુણાવાડા', hi: 'लुणावाडा' }, 'Santrampur': { gu: 'સંતરામપુર', hi: 'संतरामपुर' }, 'Kadana': { gu: 'કડાણા', hi: 'कड़ाना' },
            'Mehsana': { gu: 'મહેસાણા', hi: 'मेहसाणा' }, 'Visnagar': { gu: 'વિસનગર', hi: 'विसनगर' }, 'Unjha': { gu: 'ઊંઝા', hi: 'ऊंझा' },
            'Morbi': { gu: 'મોરબી', hi: 'मोरबी' }, 'Tankara': { gu: 'ટંકારા', hi: 'टंकारा' }, 'Halvad': { gu: 'હળવદ', hi: 'हलवद' },
            'Narmada': { gu: 'નર્મદા', hi: 'नर्मदा' }, 'Rajpipla': { gu: 'રાજપીપળા', hi: 'राजपीपला' }, 'Dediapada': { gu: 'ડેડીયાપાડા', hi: 'डेडियापाडा' }, 'Sagbara': { gu: 'સાગબારા', hi: 'सागबारा' },
            'Navsari': { gu: 'નવસારી', hi: 'नवसारी' }, 'Gandevi': { gu: 'ગણદેવી', hi: 'गणदेवी' }, 'Chikhli': { gu: 'ચીખલી', hi: 'चिखली' },
            'Panchmahal': { gu: 'પંચમહાલ', hi: 'पंचमहाल' }, 'Godhra': { gu: 'ગોધરા', hi: 'गोधरा' }, 'Halol': { gu: 'હાલોલ', hi: 'हालोल' },
            'Patan': { gu: 'પાટણ', hi: 'पाटन' }, 'Sidhpur': { gu: 'સિદ્ધપુર', hi: 'सिद्धपुर' }, 'Harij': { gu: 'હારીજ', hi: 'हारीज' },
            'Porbandar': { gu: 'પોરબંદર', hi: 'पोरबंदर' }, 'Ranavav': { gu: 'રાણાવાવ', hi: 'राणावाव' }, 'Kutiyana': { gu: 'કુતિયાણા', hi: 'कुतियाणा' },
            'Rajkot': { gu: 'રાજકોટ', hi: 'राजकोट' }, 'Gondal': { gu: 'ગોંડલ', hi: 'गोंडल' }, 'Jetpur': { gu: 'જેતપુર', hi: 'जेतपुर' },
            'Sabarkantha': { gu: 'સાબરકાંઠા', hi: 'साबरकांठा' }, 'Himmatnagar': { gu: 'હિંમતનગર', hi: 'हिम्मतनगर' }, 'Idar': { gu: 'ઈડર', hi: 'ईडर' }, 'Talod': { gu: 'તલોદ', hi: 'तलोद' },
            'Surat': { gu: 'સુરત', hi: 'सूरत' }, 'Surat City': { gu: 'સુરત શહેર', hi: 'सूरत शहर' }, 'Bardoli': { gu: 'બારડોલી', hi: 'बारडोली' }, 'Kamrej': { gu: 'કામરેજ', hi: 'कामरेज' },
            'Surendranagar': { gu: 'સુરેન્દ્રનગર', hi: 'सुरेंद्रनगर' }, 'Wadhwan': { gu: 'વઢવાણ', hi: 'वढवाण' }, 'Limbdi': { gu: 'લીંબડી', hi: 'लींबडी' },
            'Tapi': { gu: 'તાપી', hi: 'तापी' }, 'Vyara': { gu: 'વ્યારા', hi: 'व्यारा' }, 'Nizar': { gu: 'નિઝર', hi: 'निझर' }, 'Songadh': { gu: 'સોનગઢ', hi: 'सोनगढ़' },
            'Vadodara': { gu: 'વડોદરા', hi: 'वडोदरा' }, 'Dabhoi': { gu: 'ડભોઈ', hi: 'डभोई' }, 'Padra': { gu: 'પાદરા', hi: 'पादरा' },
            'Valsad': { gu: 'વલસાડ', hi: 'वलसाड' }, 'Vapi': { gu: 'વાપી', hi: 'वापी' }, 'Pardi': { gu: 'પારડી', hi: 'पारडी' }
        };

        function tTerm(name) {
            const lang = localStorage.getItem('agricare_lang') || 'en';
            if (lang === 'en') return name;
            return (transMap[name] && transMap[name][lang]) ? transMap[name][lang] : name;
        }

        const districtCityMap = {
            'Ahmedabad': ['Ahmedabad City', 'Dholka', 'Sanand', 'Viramgam'],
            'Amreli': ['Amreli', 'Rajula', 'Savarkundla', 'Dhari'],
            'Anand': ['Anand', 'Petlad', 'Borsad', 'Umreth'],
            'Arvalli': ['Modasa', 'Bhiloda', 'Bayad'],
            'Banaskantha': ['Palanpur', 'Deesa', 'Dhanera', 'Tharad'],
            'Bharuch': ['Bharuch', 'Ankleshwar', 'Jambusar'],
            'Bhavnagar': ['Bhavnagar', 'Palitana', 'Mahuva'],
            'Botad': ['Botad', 'Gadhada', 'Barwala'],
            'Chhota Udaipur': ['Chhota Udaipur', 'Kawant', 'Bodeli'],
            'Dahod': ['Dahod', 'Limkheda', 'Jhalod'],
            'Dang': ['Ahwa', 'Waghai', 'Subir'],
            'Devbhoomi Dwarka': ['Khambhalia', 'Dwarka', 'Bhanvad'],
            'Gandhinagar': ['Gandhinagar', 'Mansa', 'Kalol'],
            'Gir Somnath': ['Veraval', 'Una', 'Kodinar'],
            'Jamnagar': ['Jamnagar', 'Dhrol', 'Kalavad'],
            'Junagadh': ['Junagadh', 'Keshod', 'Mangrol'],
            'Kheda': ['Nadiad', 'Kapadvanj', 'Matar'],
            'Kutch': ['Bhuj', 'Gandhidham', 'Mandvi'],
            'Mahisagar': ['Lunawada', 'Santrampur', 'Kadana'],
            'Mehsana': ['Mehsana', 'Visnagar', 'Unjha'],
            'Morbi': ['Morbi', 'Tankara', 'Halvad'],
            'Narmada': ['Rajpipla', 'Dediapada', 'Sagbara'],
            'Navsari': ['Navsari', 'Gandevi', 'Chikhli'],
            'Panchmahal': ['Godhra', 'Halol', 'Kalol'],
            'Patan': ['Patan', 'Sidhpur', 'Harij'],
            'Porbandar': ['Porbandar', 'Ranavav', 'Kutiyana'],
            'Rajkot': ['Rajkot', 'Gondal', 'Jetpur'],
            'Sabarkantha': ['Himmatnagar', 'Idar', 'Talod'],
            'Surat': ['Surat City', 'Bardoli', 'Kamrej'],
            'Surendranagar': ['Surendranagar', 'Wadhwan', 'Limbdi'],
            'Tapi': ['Vyara', 'Nizar', 'Songadh'],
            'Vadodara': ['Vadodara', 'Dabhoi', 'Padra'],
            'Valsad': ['Valsad', 'Vapi', 'Pardi']
        };

        const districts = Object.keys(districtCityMap);

        function renderList(listEl, items, onSelect) {
            listEl.innerHTML = '';
            items.forEach(name => {
                const div = document.createElement('div');
                div.className = 'sd-item';
                div.textContent = tTerm(name);
                div.onclick = () => onSelect(name);
                listEl.appendChild(div);
            });
        }

        function toggleDropdown(id) {
            document.querySelectorAll('.slider-dropdown.open').forEach(el => { if (el.id !== id) el.classList.remove('open'); });
            document.getElementById(id).classList.toggle('open');
        }

        function filterList(ddId, query) {
            const listEl = document.getElementById(ddId + '-list');
            const items = ddId === 'dd-district' ? districts : (districtCityMap[document.getElementById('district').value] || []);
            renderList(listEl, items.filter(n => tTerm(n).toLowerCase().includes(query.toLowerCase()) || n.toLowerCase().includes(query.toLowerCase())),
                name => selectItem(ddId, name));
        }

        function selectItem(ddId, name) {
            const hiddenId = ddId === 'dd-district' ? 'district' : 'city';
            document.getElementById(hiddenId).value = name;
            document.getElementById(ddId + '-label').textContent = tTerm(name);
            document.getElementById(ddId + '-label').classList.remove('text-gray-400');
            document.getElementById(ddId).classList.remove('open');
            if (ddId === 'dd-district') {
                document.getElementById('city').value = '';
                document.getElementById('dd-city-label').textContent = 'Select City/Area';
                document.getElementById('dd-city-label').classList.add('text-gray-400');
                renderList(document.getElementById('dd-city-list'), districtCityMap[name] || [], n => selectItem('dd-city', n));
            }
        }

        // init district list
        renderList(document.getElementById('dd-district-list'), districts, name => selectItem('dd-district', name));

        // init language list
        const languages = [
            { value: 'en', en: 'English', gu: 'અંગ્રેજી', hi: 'अंग्रेज़ी' },
            { value: 'gu', en: 'Gujarati', gu: 'ગુજરાતી', hi: 'गुजराती' },
            { value: 'hi', en: 'Hindi', gu: 'હિન્દી', hi: 'हिंदी' }
        ];
        (function() {
            const listEl = document.getElementById('dd-lang-list');
            languages.forEach(langObj => {
                const div = document.createElement('div');
                div.className = 'sd-item';
                const curLang = localStorage.getItem('agricare_lang') || 'en';
                div.textContent = langObj[curLang] || langObj.en;
                div.onclick = () => {
                    document.getElementById('pref_lang').value = langObj.value;
                    const cLang = localStorage.getItem('agricare_lang') || 'en';
                    document.getElementById('dd-lang-label').textContent = langObj[cLang] || langObj.en;
                    document.getElementById('dd-lang-label').classList.remove('text-gray-400');
                    document.getElementById('dd-lang').classList.remove('open');
                };
                listEl.appendChild(div);
            });
        })();

        // close on outside click
        document.addEventListener('click', e => {
            if (!e.target.closest('.slider-dropdown')) {
                document.querySelectorAll('.slider-dropdown.open').forEach(el => el.classList.remove('open'));
            }
        });

        function updateCities() {}

        function validateFullName() {
            const value = document.getElementById('full_name').value.trim();
            const valid = /^[\p{L}]+(?:\s+[\p{L}]+)+$/u.test(value) && value.length >= 3 && value.length <= 60;
            const empty = value.length === 0;
            document.getElementById('full-name-error').classList.toggle('hidden', valid || empty);
        }

        function validatePhone() {
            const valid = /^[0-9]{10}$/.test(document.getElementById('phone_no').value);
            const empty = document.getElementById('phone_no').value.length === 0;
            document.getElementById('phone-reg-error').classList.toggle('hidden', valid || empty);
        }

        function validatePin() {
            const valid = /^\d{6}$/.test(document.getElementById('pin').value);
            const empty = document.getElementById('pin').value.length === 0;
            document.getElementById('pin-error').classList.toggle('hidden', valid || empty);
        }

        function togglePin() {
            pinVisible = !pinVisible;
            document.getElementById('pin').type = pinVisible ? 'text' : 'password';
            document.getElementById('pin-icon').className = pinVisible ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
        }

        function handleRegister(event) {
            event.preventDefault();
            const fullName = document.getElementById('full_name').value.trim();
            const phone = document.getElementById('phone_no').value;
            const pin = document.getElementById('pin').value;
            validateFullName();
            validatePhone();
            validatePin();

            if (!/^[\p{L}]+(?:\s+[\p{L}]+)+$/u.test(fullName) || fullName.length < 3 || fullName.length > 60 || !/^[0-9]{10}$/.test(phone) || !/^\d{6}$/.test(pin)) {
                return;
            }

            const btn = document.getElementById('submit-btn');
            const original = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';

            fetch('../backend/register_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    full_name: document.getElementById('full_name').value,
                    email: document.getElementById('email').value,
                    phone_no: phone,
                    role: 'farmer',
                    district: document.getElementById('district').value,
                    city: document.getElementById('city').value,
                    pincode: document.getElementById('pincode').value,
                    pref_lang: document.getElementById('pref_lang').value,
                    pin: pin
                })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Registration failed');
                }
                alert(`Account created successfully.\nWelcome ${data.user.name}`);
                window.location.href = 'login.php';
            })
            .catch(err => {
                alert(`Error: ${err.message}`);
                btn.disabled = false;
                btn.innerHTML = original;
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const initialLang = localStorage.getItem('agricare_lang') || 'en';
            changeLang(initialLang);
        });

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
