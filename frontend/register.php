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

        .slider-dropdown {
            position: relative;
        }

        .slider-dropdown .sd-panel {

            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;

            background: white;
            border: 1px solid #d1fae5;
            border-radius: 1rem;

            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.12);

            z-index: 50;
            max-height: 0;
            overflow: hidden;

            transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s;

            opacity: 0;

        }

        .slider-dropdown.open .sd-panel {
            max-height: 220px;
            opacity: 1;
        }

        .sd-panel .sd-search {

            width: 100%;
            padding: 8px 14px;
            border: none;
            border-bottom: 1px solid #d1fae5;

            outline: none;
            font-size: 0.8rem;
            border-radius: 1rem 1rem 0 0;
            background: #f0fdf4;

        }

        .sd-panel .sd-list {
            overflow-y: auto;
            max-height: 170px;
        }

        .sd-panel .sd-item {

            padding: 9px 14px;
            font-size: 0.85rem;
            cursor: pointer;
            color: #1f2937;

            transition: background 0.15s;

        }

        .sd-item:hover,
        .sd-item.active {
            background: #ecfdf5;
            color: #059669;
            font-weight: 600;
        }

        .sd-trigger {

            appearance: none;
            border-radius: 1rem;
            width: 100%;
            padding: 12px 40px 12px 16px;

            border: 1px solid #e5e7eb;
            color: #1f2937;
            background: white;

            font-size: 0.875rem;
            cursor: pointer;
            text-align: left;

            transition: border-color 0.2s, box-shadow 0.2s;

            display: flex;
            align-items: center;
            justify-content: space-between;

        }

        .sd-trigger:focus,
        .slider-dropdown.open .sd-trigger {

            outline: none;
            border-color: #10b981;

            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);

        }

        .sd-trigger .sd-arrow {
            transition: transform 0.3s;
            color: #6b7280;
            font-size: 0.75rem;
        }

        .slider-dropdown.open .sd-trigger .sd-arrow {
            transform: rotate(180deg);
        }
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

                                placeholder="name@example.com" oninput="validateEmail()" onblur="validateEmail(true)">
                            <p id="email-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1"></p>

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

                                    <span id="dd-district-label" data-lang="placeholderDistrict" class="text-gray-400">Select District</span>

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

                                <button type="button" id="city-trigger" class="sd-trigger opacity-50 cursor-not-allowed" onclick="toggleDropdown('dd-city')">

                                    <span id="dd-city-label" data-lang="placeholderCity" class="text-gray-400">Select District First</span>

                                    <i class="sd-arrow fas fa-chevron-down"></i>

                                </button>

                                <div class="sd-panel">

                                    <input type="text" class="sd-search" placeholder="Search city..." oninput="filterList('dd-city', this.value)">

                                    <div class="sd-list" id="dd-city-list"></div>

                                </div>

                            </div>

                            <p id="location-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="locationError">Please select both District and City/Area.</p>

                        </div>



                        <div>

                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1"><span data-lang="labelPincode">Pincode</span> <span class="text-red-500">*</span></label>

                            <input id="pincode" type="text" required maxlength="6"

                                class="appearance-none rounded-2xl block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all sm:text-sm"

                                placeholder="392001" oninput="validatePincode()" onblur="validatePincode(true)">
                            <p id="pincode-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1"></p>

                        </div>



                        <div class="md:col-span-2">

                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1"><span data-lang="labelPrefLang">Preferred Language</span> <span class="text-red-500">*</span></label>

                            <input type="hidden" id="pref_lang" required>

                            <div class="slider-dropdown" id="dd-lang">

                                <button type="button" class="sd-trigger" onclick="toggleDropdown('dd-lang')">

                                    <span id="dd-lang-label" data-lang="placeholderLang" class="text-gray-400">Select Language</span>

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

                locationError: 'Please select both District and City/Area.',

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

                labelEmail: 'ઇમેઇલ (વૈકલ્પિક)',

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

                locationError: 'કૃપા કરીને જિલ્લો અને શહેર બંને પસંદ કરો.',

                submitBtn: 'ખાતું બનાવો',

                heroTitle: 'એગ્રીકેરમાં જોડાઓ',

                heroSubtitle: 'હવામાનની જાણકારી, બજારના ભાવ અને પાક માર્ગદર્શન મેળવવા માટે તમારું ખેડૂત ખાતું બનાવો.'

            },

            hi: {

                backToHome: 'होम पर वापस जाएं',

                logo: 'एग्रीकेर',

                createAccount: 'खाता बनाएं',

                alreadyHaveAccount: 'पहले से खाता है?',

                logIn: 'लॉग इन करें',

                labelFullName: 'पूरा नाम',

                labelEmail: 'ईमेल (वैकल्पिक)',

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

                placeholderLang: 'भाषा चुनें',

                phoneError: 'कृपया एक मान्य 10-अंकीय मोबाइल नंबर दर्ज करें।',

                pinError: 'कृपया ठीक 6 अंक दर्ज करें।',

                locationError: 'कृपया जिला और शहर दोनों का चयन करें।',

                submitBtn: 'खाता बनाएं',

                heroTitle: 'एग्रीकेर से जुड़ें',

                heroSubtitle: 'मौसम की जानकारी, बाजार मूल्य और फसल मार्गदर्शन प्राप्त करने के लिए अपना किसान खाता बनाएं।'

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

                if (lObj) langLabel.textContent = translations[lang].placeholderLang || 'Select Language';
            }

            // Re-render dropdowns lists to update language dynamically
            if (typeof renderList === 'function') {
                if (typeof districts !== 'undefined') {
                    renderList(document.getElementById('dd-district-list'), districts, name => selectItem('dd-district', name));
                }
                const dVal = document.getElementById('district').value;
                if (typeof districtCityMap !== 'undefined' && dVal) {
                    renderList(document.getElementById('dd-city-list'), districtCityMap[dVal] || [], name => selectItem('dd-city', name));
                }
            }

            if (typeof languages !== 'undefined') {
                const ll = document.getElementById('dd-lang-list');
                if (ll) {
                    ll.innerHTML = '';
                    languages.forEach(langObj => {
                        const div = document.createElement('div');
                        div.className = 'sd-item';
                        div.textContent = langObj[lang] || langObj.en;
                        div.onclick = () => {
                            document.getElementById('pref_lang').value = langObj.value;
                            document.getElementById('dd-lang-label').textContent = langObj[lang] || langObj.en;
                            document.getElementById('dd-lang').classList.remove('open');
                        };
                        ll.appendChild(div);
                    });
                }
            }
        }

        const transMap = {
            'Ahmedabad': { gu: 'અમદાવાદ', hi: 'अहमदाबाद' }, 'Ahmedabad City': { gu: 'અમદાવાદ શહેર', hi: 'अहमदाबाद शहर' }, 'Daskroi': { gu: 'દસક્રોઈ', hi: 'दसक्रोई' }, 'Dholka': { gu: 'ધોળકા', hi: 'धोलका' }, 'Sanand': { gu: 'સાણંદ', hi: 'साणंद' }, 'Bavla': { gu: 'બાવળા', hi: 'बावला' }, 'Mandal': { gu: 'માંડલ', hi: 'मांडल' }, 'Detroj-Rampura': { gu: 'દેત્રોજ-રામપુરા', hi: 'देत्रोज-रामपुरा' }, 'Viramgam': { gu: 'વિરમગામ', hi: 'विरमगाम' },
            'Amreli': { gu: 'અમરેલી', hi: 'अमरेली' }, 'Bagasara': { gu: 'બગસરા', hi: 'बगसरा' }, 'Dhari': { gu: 'ધારી', hi: 'धारी' }, 'Jafrabad': { gu: 'જાફરાબાદ', hi: 'जाफराबाद' }, 'Khambha': { gu: 'ખાંભા', hi: 'खांभा' }, 'Kunkavav': { gu: 'કુંકાવાવ', hi: 'कुंकावाव' }, 'Lathi': { gu: 'લાઠી', hi: 'लाठी' }, 'Rajula': { gu: 'રાજુલા', hi: 'राजुला' }, 'Savarkundla': { gu: 'સાવરકુંડલા', hi: 'सावरकुंडला' },
            'Anand': { gu: 'આણંદ', hi: 'आणंद' }, 'Anklav': { gu: 'આંકલાવ', hi: 'आंकलाव' }, 'Borsad': { gu: 'બોરસદ', hi: 'बोरसद' }, 'Khambhat': { gu: 'ખંભાત', hi: 'खंभात' }, 'Petlad': { gu: 'પેટલાદ', hi: 'पेटलाद' }, 'Sojitra': { gu: 'સોજિત્રા', hi: 'सोजित्रा' }, 'Tarapur': { gu: 'તારાપુર', hi: 'तारापुर' }, 'Umreth': { gu: 'ઉમરેઠ', hi: 'उमरेठ' },
            'Aravalli': { gu: 'અરવલ્લી', hi: 'अरवल्ली' }, 'Bayad': { gu: 'બાયડ', hi: 'बायड' }, 'Bhiloda': { gu: 'ભિલોડા', hi: 'भिलोड़ा' }, 'Dhansura': { gu: 'ધનસુરા', hi: 'धनसुरा' }, 'Malpur': { gu: 'માલપુર', hi: 'मालपुर' }, 'Meghraj': { gu: 'મેઘરજ', hi: 'मेघराज' }, 'Modasa': { gu: 'મોડાસા', hi: 'मोडासा' },
            'Banaskantha': { gu: 'બનાસકાંઠા', hi: 'बनासकांठा' }, 'Amirgadh': { gu: 'અમીરગઢ', hi: 'अमीरगढ़' }, 'Bhabhar': { gu: 'ભાભર', hi: 'भाभर' }, 'Danta': { gu: 'દાંતા', hi: 'दांता' }, 'Dantiwada': { gu: 'દાંતીવાડા', hi: 'दांतीवाड़ा' }, 'Deesa': { gu: 'ડીસા', hi: 'डीसा' }, 'Deodar': { gu: 'દિયોદર', hi: 'दियोदर' }, 'Dhanera': { gu: 'ધાનેરા', hi: 'धानेरा' }, 'Kankrej': { gu: 'કાંકરેજ', hi: 'कांकरेज' }, 'Lakhani': { gu: 'લાખણી', hi: 'लाखणी' }, 'Palanpur': { gu: 'પાલનપુર', hi: 'पालनपुर' }, 'Suigam': { gu: 'સુઈગામ', hi: 'सुईगाम' }, 'Tharad': { gu: 'થરાદ', hi: 'थराद' }, 'Vadgam': { gu: 'વડગામ', hi: 'वडगाम' },
            'Bharuch': { gu: 'ભરૂચ', hi: 'भरूच' }, 'Ankleshwar': { gu: 'અંકલેશ્વર', hi: 'अंकलेश्वर' }, 'Amod': { gu: 'આમોદ', hi: 'आमोद' }, 'Hansot': { gu: 'હાંસોટ', hi: 'हांसोट' }, 'Jambusar': { gu: 'જંબુસર', hi: 'जंबूसर' }, 'Jhagadia': { gu: 'ઝઘડિયા', hi: 'झगड़िया' }, 'Netrang': { gu: 'નેત્રંગ', hi: 'नेत्रंग' }, 'Vagra': { gu: 'વાગરા', hi: 'वागरा' }, 'Valia': { gu: 'વાલિયા', hi: 'वालिया' },
            'Bhavnagar': { gu: 'ભાવનગર', hi: 'भावनगर' }, 'Botad': { gu: 'બોટાદ', hi: 'बोटाद' }, 'Gadhada': { gu: 'ગઢડા', hi: 'गढ़ड़ा' }, 'Ghogha': { gu: 'ઘોઘા', hi: 'घोघा' }, 'Jesar': { gu: 'જેસર', hi: 'जेसर' }, 'Mahuva': { gu: 'મહુવા', hi: 'महुवा' }, 'Palitana': { gu: 'પાલીતાણા', hi: 'पालीताना' }, 'Sihor': { gu: 'સિહોર', hi: 'सिहोर' }, 'Talaja': { gu: 'તળાજા', hi: 'तलाजा' }, 'Umrala': { gu: 'ઉમરાળા', hi: 'उमराला' },
            'Barwala': { gu: 'બરવાળા', hi: 'बरवाला' }, 'Ranpur': { gu: 'રાણપુર', hi: 'राणपुर' },
            'Chhota Udepur': { gu: 'છોટા ઉદેપુર', hi: 'छोटा उदयपुर' }, 'Bodeli': { gu: 'બોડેલી', hi: 'बोडेली' }, 'Jetpur Pavi': { gu: 'જેતપુર પાવી', hi: 'जेतपुर पावी' }, 'Kavant': { gu: 'કવાંટ', hi: 'कवांट' }, 'Naswadi': { gu: 'નસવાડી', hi: 'नसवाड़ी' }, 'Sankheda': { gu: 'સંખેડા', hi: 'संखेडा' },
            'Dahod': { gu: 'દાહોદ', hi: 'दाहोद' }, 'Devgadh Baria': { gu: 'દેવગઢ બારિયા', hi: 'देवगढ़ बारिया' }, 'Dhanpur': { gu: 'ધાનપુર', hi: 'धानपुर' }, 'Fatepura': { gu: 'ફતેપુરા', hi: 'फतेपुरा' }, 'Garbada': { gu: 'ગરબાડા', hi: 'गरबाडा' }, 'Jhalod': { gu: 'ઝાલોદ', hi: 'झालोद' }, 'Limkheda': { gu: 'લીમખેડા', hi: 'लीमखेड़ा' },
            'Dang': { gu: 'ડાંગ', hi: 'डांग' }, 'Ahwa': { gu: 'આહવા', hi: 'आहवा' }, 'Subir': { gu: 'સુબીર', hi: 'सुबीर' }, 'Waghai': { gu: 'વઘઇ', hi: 'वघई' },
            'Devbhoomi Dwarka': { gu: 'દેવભૂમિ દ્વારકા', hi: 'देवभूमि द्वारका' }, 'Dwarka': { gu: 'દ્વારકા', hi: 'द्वारका' }, 'Bhanvad': { gu: 'ભાણવડ', hi: 'भाणवड' }, 'Kalyanpur': { gu: 'કલ્યાણપુર', hi: 'कल्याणपुर' }, 'Khambhalia': { gu: 'ખંભાળિયા', hi: 'खंभालिया' },
            'Gandhinagar': { gu: 'ગાંધીનગર', hi: 'गांधीनगर' }, 'Dehgam': { gu: 'દહેગામ', hi: 'दहेगाam' }, 'Kalol': { gu: 'કલોલ', hi: 'कलोल' }, 'Mansa': { gu: 'માણસા', hi: 'माणसा' },
            'Gir Somnath': { gu: 'ગીર સોમનાથ', hi: 'गिर सोमनाथ' }, 'Veraval': { gu: 'વેરાવળ', hi: 'वेरावल' }, 'Kodinar': { gu: 'કોડીનાર', hi: 'कोडिनार' }, 'Talala': { gu: 'તાલાલા', hi: 'तालाला' }, 'Sutrapada': { gu: 'સુત્રાપાડા', hi: 'सुत्रापाडा' }, 'Una': { gu: 'ઊના', hi: 'ऊना' }, 'Gir Gadhada': { gu: 'ગીર ગઢડા', hi: 'गिर गढड़ा' },
            'Jamnagar': { gu: 'જામનગર', hi: 'जामनगर' }, 'Dhrol': { gu: 'ધ્રોલ', hi: 'ध्रोल' }, 'Jamjodhpur': { gu: 'જામજોધપુર', hi: 'जामजोधपुर' }, 'Jodiya': { gu: 'જોડિયા', hi: 'जोडिया' }, 'Kalavad': { gu: 'કાલાવાડ', hi: 'कालावड' }, 'Lalpur': { gu: 'લાલપુર', hi: 'लालपुर' },
            'Junagadh': { gu: 'જૂનાગઢ', hi: 'जूनागढ़' }, 'Bhesan': { gu: 'ભેંસાણ', hi: 'भेसाण' }, 'Keshod': { gu: 'કેશોદ', hi: 'केशाेद' }, 'Manavadar': { gu: 'માણાવદર', hi: 'माणावदर' }, 'Mangrol': { gu: 'માંગરોળ', hi: 'मांगरोल' }, 'Maliya Hatina': { gu: 'માળિયા હાટીના', hi: 'मालिया हाटीणा' }, 'Mendarda': { gu: 'મેંદરડા', hi: 'मेंदरडा' }, 'Vanthali': { gu: 'વંથલી', hi: 'वंथली' }, 'Visavadar': { gu: 'વિસાવદર', hi: 'विसावदर' },
            'Kheda': { gu: 'ખેડા', hi: 'खेड़ा' }, 'Nadiad': { gu: 'નડિયાદ', hi: 'नडियाद' }, 'Kapadvanj': { gu: 'કપડવંજ', hi: 'कपडवंज' }, 'Kathlal': { gu: 'કઠલાલ', hi: 'कठलाल' }, 'Mahudha': { gu: 'મહુધા', hi: 'महुधा' }, 'Matar': { gu: 'માતર', hi: 'मातर' }, 'Mehmedabad': { gu: 'મહેમદાવાદ', hi: 'मेहमदाबाद' }, 'Thasra': { gu: 'ઠાસરા', hi: 'ठासरा' }, 'Galteshwar': { gu: 'ગળતેશ્વર', hi: 'गळतेश्वर' },
            'Kutch': { gu: 'કચ્છ', hi: 'कच्छ' }, 'Bhuj': { gu: 'ભુજ', hi: 'भुज' }, 'Abdasa': { gu: 'અબડાસા', hi: 'अबडासा' }, 'Anjar': { gu: 'અંજાર', hi: 'अंजार' }, 'Bhachau': { gu: 'ભચાઉ', hi: 'भचाऊ' }, 'Gandhidham': { gu: 'ગાંધીધામ', hi: 'गांधीधाम' }, 'Lakhpat': { gu: 'લખપત', hi: 'लखपत' }, 'Mandvi': { gu: 'માંડવી', hi: 'मांडवी' }, 'Mundra': { gu: 'મુંદ્રા', hi: 'मुंद्रा' }, 'Nakhatrana': { gu: 'નખત્રાણા', hi: 'नखत्राणा' }, 'Rapar': { gu: 'રાપર', hi: 'રાपर' },
            'Mahisagar': { gu: 'મહીસાગર', hi: 'महीसागर' }, 'Balasinor': { gu: 'બાલાસિનોર', hi: 'बालासिनोर' }, 'Kadana': { gu: 'કડાણા', hi: 'कड़ाना' }, 'Khanpur': { gu: 'ખાનપુર', hi: 'खानपुर' }, 'Lunawada': { gu: 'લુણાવાડા', hi: 'लुणावाडा' }, 'Santrampur': { gu: 'સંતરામપુર', hi: 'संतरामपुर' }, 'Virpur': { gu: 'વીરપુર', hi: 'वीरपुर' },
            'Mehsana': { gu: 'મહેસાણા', hi: 'मेहसाणा' }, 'Becharaji': { gu: 'બેચરાજી', hi: 'बेचराजी' }, 'Jotana': { gu: 'જોટાણા', hi: 'जोटाणा' }, 'Kadi': { gu: 'કડી', hi: 'कड़ी' }, 'Kheralu': { gu: 'ખેરાલુ', hi: 'खेरालू' }, 'Satlasana': { gu: 'સતલાસણા', hi: 'सतलासणा' }, 'Unjha': { gu: 'ઊંઝા', hi: 'ऊंझा' }, 'Vadnagar': { gu: 'વડનગર', hi: 'वडनगर' }, 'Vijapur': { gu: 'વિજાપુર', hi: 'विजापुर' },
            'Morbi': { gu: 'મોરબી', hi: 'मोरबी' }, 'Halvad': { gu: 'હળવદ', hi: 'हलवद' }, 'Maliya': { gu: 'માળિયા', hi: 'मालिया' }, 'Tankara': { gu: 'ટંકારા', hi: 'टंकारा' }, 'Wankaner': { gu: 'વાંકાનેર', hi: 'वांकानेर' },
            'Narmada': { gu: 'નર્મદા', hi: 'नर्मदा' }, 'Rajpipla': { gu: 'રાજપીપળા', hi: 'राजपीपला' }, 'Dediapada': { gu: 'ડેડીયાપાડા', hi: 'डेडियापाडा' }, 'Garudeshwar': { gu: 'ગરૂડેશ્વર', hi: 'गरुडेश्वर' }, 'Nandod': { gu: 'નાંદોદ', hi: 'नांदोद' }, 'Sagbara': { gu: 'સાગબારા', hi: 'सागबारा' }, 'Tilakwada': { gu: 'તિલકવાડા', hi: 'तिलकवाडा' },
            'Navsari': { gu: 'નવસારી', hi: 'नवसारी' }, 'Chikhli': { gu: 'ચીખલી', hi: 'चिखली' }, 'Gandevi': { gu: 'ગણદેવી', hi: 'गणदेवी' }, 'Jalalpore': { gu: 'જલાલપોર', hi: 'जलालपोर' }, 'Khergam': { gu: 'ખેરગામ', hi: 'खेरगाम' }, 'Vansda': { gu: 'વાંસદા', hi: 'वांसदा' },
            'Panchmahal': { gu: 'પંચમહાલ', hi: 'पंचमहाल' }, 'Godhra': { gu: 'ગોધરા', hi: 'गोधरा' }, 'Halol': { gu: 'હાલોલ', hi: 'हालोल' }, 'Jambughoda': { gu: 'જાંબુઘોડા', hi: 'जांबुघोडा' }, 'Morwa Hadaf': { gu: 'મોરવા હડફ', hi: 'मोरवा हडफ' }, 'Shehera': { gu: 'શહેરા', hi: 'शहेरा' },
            'Patan': { gu: 'પાટણ', hi: 'पाटन' }, 'Chanasma': { gu: 'ચાણસ્મા', hi: 'चाणस्मा' }, 'Harij': { gu: 'હારીજ', hi: 'हारीज' }, 'Radhanpur': { gu: 'રાધનપુર', hi: 'राधनपुर' }, 'Sami': { gu: 'સમી', hi: 'समी' }, 'Santalpur': { gu: 'સાંતલપુર', hi: 'सांतलपुर' }, 'Saraswati': { gu: 'સરસ્વતી', hi: 'सरस्वती' }, 'Siddhpur': { gu: 'સિદ્ધપુર', hi: 'सिद्धपुर' },
            'Porbandar': { gu: 'પોરબંદર', hi: 'पोरबंदर' }, 'Kutiyana': { gu: 'કુતિયાણા', hi: 'कुतियाणा' }, 'Ranavav': { gu: 'રાણાવાવ', hi: 'राणावाव' },
            'Rajkot': { gu: 'રાજકોટ', hi: 'राजकोट' }, 'Dhoraji': { gu: 'ધોરાજી', hi: 'धोराजी' }, 'Gondal': { gu: 'ગોંડલ', hi: 'गोंडल' }, 'Jamkandorna': { gu: 'જામકંડોરણા', hi: 'जामकंडोरणा' }, 'Jasdan': { gu: 'જસદણ', hi: 'जसदण' }, 'Jetpur': { gu: 'જેતપુર', hi: 'जेतपुर' }, 'Kotda Sangani': { gu: 'કોટડાસાંગાણી', hi: 'कोटडासांगाणी' }, 'Lodhika': { gu: 'લોધિકા', hi: 'लोधिका' }, 'Paddhari': { gu: 'પડધરી', hi: 'पडधरी' }, 'Upleta': { gu: 'ઉપલેટા', hi: 'उपलेटा' }, 'Vinchhiya': { gu: 'વીંછિયા', hi: 'वीछिया' },
            'Sabarkantha': { gu: 'સાબરકાંઠા', hi: 'साबरकांठा' }, 'Himmatnagar': { gu: 'હિંમતનગર', hi: 'हिम्मतनगर' }, 'Idar': { gu: 'ઈડર', hi: 'ईडर' }, 'Khedbrahma': { gu: 'ખેડબ્રહ્મા', hi: 'खेडब्रह्मा' }, 'Posina': { gu: 'પોશીના', hi: 'पोशिना' }, 'Prantij': { gu: 'પ્રાંતિજ', hi: 'प्रांतिज' }, 'Talod': { gu: 'તલોદ', hi: 'तलोद' }, 'Vadali': { gu: 'વડાલી', hi: 'वडाली' }, 'Vijaynagar': { gu: 'વિજયનગર', hi: 'विजयनगर' },
            'Surat': { gu: 'સુરત', hi: 'सूरत' }, 'Surat City': { gu: 'સુરત શહેર', hi: 'सूरत शहर' }, 'Bardoli': { gu: 'બારડોલી', hi: 'बारडोली' }, 'Choryasi': { gu: 'ચોર્યાસી', hi: 'चोर्यासी' }, 'Kamrej': { gu: 'કામરેજ', hi: 'कामरेज' }, 'Mangrol': { gu: 'માંગરોળ', hi: 'मांगरोल' }, 'Olpad': { gu: 'ઓલપાડ', hi: 'ओलपाड' }, 'Palsana': { gu: 'પલસાણા', hi: 'पलसाणा' }, 'Umarpada': { gu: 'ઉમરપાડા', hi: 'उमरपाडा' },
            'Surendranagar': { gu: 'સુરેન્દ્રનગર', hi: 'सुरेंद्रनगर' }, 'Chotila': { gu: 'ચોટીલા', hi: 'चोटीला' }, 'Dasada': { gu: 'દસાડા', hi: 'दसाडा' }, 'Dhrangadhra': { gu: 'ધ્રાંગધ્રા', hi: 'ध्रांगध्रा' }, 'Lakhtar': { gu: 'લખતર', hi: 'लखतर' }, 'Limbdi': { gu: 'લીંબડી', hi: 'लींबडी' }, 'Muli': { gu: 'મૂળી', hi: 'मूली' }, 'Sayla': { gu: 'સાયલા', hi: 'सायला' }, 'Wadhwan': { gu: 'વઢવાણ', hi: 'वढवाण' },
            'Tapi': { gu: 'તાપી', hi: 'तापी' }, 'Vyara': { gu: 'વ્યારા', hi: 'व्यारा' }, 'Dolvan': { gu: 'દોલવણ', hi: 'दोलवण' }, 'Kukarmunda': { gu: 'કુકરમુંડા', hi: 'कुकरमुंडा' }, 'Nizar': { gu: 'નિઝર', hi: 'निझर' }, 'Songadh': { gu: 'સોનગઢ', hi: 'सोनगढ़' }, 'Uchchhal': { gu: 'ઉચ્છલ', hi: 'उच्छल' },
            'Vadodara': { gu: 'વડોદરા', hi: 'वडोदरा' }, 'Dabhoi': { gu: 'ડભોઈ', hi: 'डभोई' }, 'Desar': { gu: 'ડેસર', hi: 'डेसर' }, 'Karjan': { gu: 'કરજણ', hi: 'करजण' }, 'Padra': { gu: 'પાદરા', hi: 'पादरा' }, 'Savli': { gu: 'સાવલી', hi: 'सावली' }, 'Sinor': { gu: 'શિનોર', hi: 'शिनोर' }, 'Vaghodia': { gu: 'વાઘોડિયા', hi: 'वाघोडिया' },
            'Valsad': { gu: 'વલસાડ', hi: 'वलसाड' }, 'Dharampur': { gu: 'ધરમપુર', hi: 'धरमपुर' }, 'Kaprada': { gu: 'કપરાડા', hi: 'कपराडा' }, 'Pardi': { gu: 'પારડી', hi: 'पारडी' }, 'Umbergaon': { gu: 'ઉમરગામ', hi: 'उमरगाम' }, 'Vapi': { gu: 'વાપી', hi: 'वापी' }
        };

        function tTerm(name) {

                const lang = localStorage.getItem('agricare_lang') || 'en';

                if (lang === 'en') return name;

                return (transMap[name] && transMap[name][lang]) ? transMap[name][lang] : name;

            }



            const districtCityMap = {

                'Ahmedabad': ['Ahmedabad City', 'Daskroi', 'Dholka', 'Sanand', 'Bavla', 'Mandal', 'Detroj-Rampura', 'Viramgam'],

                'Amreli': ['Amreli', 'Bagasara', 'Dhari', 'Jafrabad', 'Khambha', 'Kunkavav', 'Lathi', 'Rajula', 'Savarkundla'],

                'Anand': ['Anand', 'Anklav', 'Borsad', 'Khambhat', 'Petlad', 'Sojitra', 'Tarapur', 'Umreth'],

                'Aravalli': ['Bayad', 'Bhiloda', 'Dhansura', 'Malpur', 'Meghraj', 'Modasa'],

                'Banaskantha': ['Amirgadh', 'Bhabhar', 'Danta', 'Dantiwada', 'Deesa', 'Deodar', 'Dhanera', 'Kankrej', 'Lakhani', 'Palanpur', 'Suigam', 'Tharad', 'Vadgam'],

                'Bharuch': ['Bharuch', 'Ankleshwar', 'Amod', 'Hansot', 'Jambusar', 'Jhagadia', 'Netrang', 'Vagra', 'Valia'],

                'Bhavnagar': ['Bhavnagar', 'Botad', 'Gadhada', 'Ghogha', 'Jesar', 'Mahuva', 'Palitana', 'Sihor', 'Talaja', 'Umrala'],

                'Botad': ['Botad', 'Barwala', 'Gadhada', 'Ranpur'],

                'Chhota Udepur': ['Bodeli', 'Chhota Udepur', 'Jetpur Pavi', 'Kavant', 'Naswadi', 'Sankheda'],

                'Dahod': ['Dahod', 'Devgadh Baria', 'Dhanpur', 'Fatepura', 'Garbada', 'Jhalod', 'Limkheda'],

                'Dang': ['Ahwa', 'Subir', 'Waghai'],

                'Devbhoomi Dwarka': ['Dwarka', 'Bhanvad', 'Kalyanpur', 'Khambhalia'],

                'Gandhinagar': ['Gandhinagar', 'Dehgam', 'Kalol', 'Mansa'],

                'Gir Somnath': ['Veraval', 'Kodinar', 'Talala', 'Sutrapada', 'Una', 'Gir Gadhada'],

                'Jamnagar': ['Jamnagar', 'Dhrol', 'Jamjodhpur', 'Jodiya', 'Kalavad', 'Lalpur'],

                'Junagadh': ['Junagadh', 'Bhesan', 'Keshod', 'Manavadar', 'Mangrol', 'Maliya Hatina', 'Mendarda', 'Vanthali', 'Visavadar'],

                'Kheda': ['Nadiad', 'Kapadvanj', 'Kathlal', 'Mahudha', 'Matar', 'Mehmedabad', 'Thasra', 'Galteshwar'],

                'Kutch': ['Bhuj', 'Abdasa', 'Anjar', 'Bhachau', 'Gandhidham', 'Lakhpat', 'Mandvi', 'Mundra', 'Nakhatrana', 'Rapar'],

                'Mahisagar': ['Balasinor', 'Kadana', 'Khanpur', 'Lunawada', 'Santrampur', 'Virpur'],

                'Mehsana': ['Mehsana', 'Becharaji', 'Jotana', 'Kadi', 'Kheralu', 'Satlasana', 'Unjha', 'Vadnagar', 'Vijapur'],

                'Morbi': ['Morbi', 'Halvad', 'Maliya', 'Tankara', 'Wankaner'],

                'Narmada': ['Rajpipla', 'Dediapada', 'Garudeshwar', 'Nandod', 'Sagbara', 'Tilakwada'],

                'Navsari': ['Navsari', 'Chikhli', 'Gandevi', 'Jalalpore', 'Khergam', 'Vansda'],

                'Panchmahal': ['Godhra', 'Halol', 'Jambughoda', 'Kalol', 'Morwa Hadaf', 'Shehera'],

                'Patan': ['Patan', 'Chanasma', 'Harij', 'Radhanpur', 'Sami', 'Santalpur', 'Saraswati', 'Siddhpur'],

                'Porbandar': ['Porbandar', 'Kutiyana', 'Ranavav'],

                'Rajkot': ['Rajkot', 'Dhoraji', 'Gondal', 'Jamkandorna', 'Jasdan', 'Jetpur', 'Kotda Sangani', 'Lodhika', 'Paddhari', 'Upleta', 'Vinchhiya'],

                'Sabarkantha': ['Himmatnagar', 'Idar', 'Khedbrahma', 'Posina', 'Prantij', 'Talod', 'Vadali', 'Vijaynagar'],

                'Surat': ['Surat City', 'Bardoli', 'Choryasi', 'Kamrej', 'Mandvi', 'Mangrol', 'Olpad', 'Palsana', 'Umarpada'],

                'Surendranagar': ['Surendranagar', 'Chotila', 'Dasada', 'Dhrangadhra', 'Lakhtar', 'Limbdi', 'Muli', 'Sayla', 'Wadhwan'],

                'Tapi': ['Vyara', 'Dolvan', 'Kukarmunda', 'Nizar', 'Songadh', 'Uchchhal'],

                'Vadodara': ['Vadodara', 'Dabhoi', 'Desar', 'Karjan', 'Padra', 'Savli', 'Sinor', 'Vaghodia'],

                'Valsad': ['Valsad', 'Dharampur', 'Kaprada', 'Pardi', 'Umbergaon', 'Vapi']

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

                if (id === 'dd-city' && !document.getElementById('district').value) {

                    return; // Disabled until district is selected

                }

                document.querySelectorAll('.slider-dropdown.open').forEach(el => {
                    if (el.id !== id) el.classList.remove('open');
                });

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

                    document.getElementById('dd-city-label').textContent = translations[localStorage.getItem('agricare_lang') || 'en'].placeholderCity || 'Select City/Area';

                    document.getElementById('dd-city-label').classList.add('text-gray-400');



                    const cityBtn = document.getElementById('city-trigger');

                    if (cityBtn) {

                        cityBtn.classList.remove('opacity-50', 'cursor-not-allowed');

                    }



                    renderList(document.getElementById('dd-city-list'), districtCityMap[name] || [], n => selectItem('dd-city', n));

                }

            }



            // init district list

            renderList(document.getElementById('dd-district-list'), districts, name => selectItem('dd-district', name));



            // init language list

            const languages = [

                {
                    value: 'en',
                    en: 'English',
                    gu: 'અંગ્રેજી',
                    hi: 'अंग्रेज़ी'
                },

                {
                    value: 'gu',
                    en: 'Gujarati',
                    gu: 'ગુજરાતી',
                    hi: 'गुजराती'
                },

                {
                    value: 'hi',
                    en: 'Hindi',
                    gu: 'હિન્દી',
                    hi: 'हिंदी'
                }

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



            function validateFullName(isBlur = false) {
                const el = document.getElementById('full_name');
                const errorEl = document.getElementById('full-name-error');
                
                // Real-time auto-formatting logic
                let rawValue = el.value;
                // 1. Remove non-alphabetic/space characters
                let cleanedValue = rawValue.replace(/[^\p{L}\s]/gu, '');
                // 2. Prevent consecutive spaces while typing
                cleanedValue = cleanedValue.replace(/\s{2,}/g, ' ');
                // 3. Auto-capitalize the first letter of each word
                cleanedValue = cleanedValue.split(' ').map(word => {
                    if (word.length > 0) return word.charAt(0).toUpperCase() + word.slice(1);
                    return word;
                }).join(' ');
                
                // Apply formatting immediately
                if (rawValue !== cleanedValue) {
                    const start = el.selectionStart;
                    const end = el.selectionEnd;
                    el.value = cleanedValue;
                    el.setSelectionRange(start, end);
                }
                
                let val = el.value;
                if (isBlur) {
                    val = val.trim();
                    el.value = val;
                }
                
                const lang = localStorage.getItem('agricare_lang') || 'en';
                const empty = val.length === 0;
                
                el.classList.remove('border-red-500', 'border-green-500', 'ring-red-500/20', 'ring-green-500/20');
                
                if (empty) {
                    errorEl.classList.add('hidden');
                    return false;
                }
                
                // Validations
                let errorMsg = '';
                const parts = val.split(' ');
                
                if (val.length < 3 || val.length > 50) {
                    errorMsg = lang === 'gu' ? 'નામ 3 અને 50 અક્ષરોની વચ્ચે હોવું જોઈએ' : 
                               lang === 'hi' ? 'नाम 3 और 50 वर्णों के बीच होना चाहिए' : 
                               'Name must be between 3 and 50 characters';
                } else if (parts.length < 2) {
                    errorMsg = lang === 'gu' ? 'કૃપા કરીને તમારું પૂરું નામ દાખલ કરો (પ્રથમ અને છેલ્લું નામ)' : 
                               lang === 'hi' ? 'कृपया अपना पूरा नाम दर्ज करें (प्रथम और अंतिम नाम)' : 
                               'Please enter your full name (first and last name required)';
                } else if (parts.some(p => p.length < 2)) {
                    errorMsg = lang === 'gu' ? 'નામના દરેક ભાગમાં ઓછામાં ઓછા 2 અક્ષર હોવા જોઈએ' : 
                               lang === 'hi' ? 'नाम के प्रत्येक भाग में कम से कम 2 अक्षर होने चाहिए' : 
                               'Each part of the name must be at least 2 characters';
                } else if (!/^[\p{L}]{2,}(?:\s[\p{L}]{2,})+$/u.test(val)) {
                    errorMsg = lang === 'gu' ? 'નામમાં માત્ર અક્ષરો અને જગ્યાઓ હોવી જોઈએ' : 
                               lang === 'hi' ? 'नाम में केवल अक्षर और रिक्त स्थान होने चाहिए' : 
                               'Name should contain only letters and spaces';
                }
                
                if (errorMsg) {
                    errorEl.textContent = errorMsg;
                    errorEl.classList.remove('hidden');
                    el.classList.add('border-red-500', 'ring-red-500/20');
                    return false;
                } else {
                    errorEl.classList.add('hidden');
                    el.classList.add('border-green-500', 'ring-green-500/20');
                    return true;
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const nameInput = document.getElementById('full_name');
                if(nameInput) {
                    nameInput.addEventListener('blur', () => validateFullName(true));
                }
            });

                        let emailCheckTimeout = null;
            let isEmailOriginalValid = false;
            
            function validateEmail(isBlur = false) {
                const el = document.getElementById('email');
                const errorEl = document.getElementById('email-error');
                const lang = localStorage.getItem('agricare_lang') || 'en';
                
                let rawValue = el.value;
                if (rawValue.includes(' ')) {
                    const errorMsg = lang === 'gu' ? 'ઇમેઇલમાં ખાલી જગ્યાઓ હોઈ શકે નહીં' : 
                                     lang === 'hi' ? 'ईमेल में रिक्त स्थान नहीं हो सकते' : 
                                     'Email cannot contain spaces';
                    errorEl.textContent = errorMsg;
                    errorEl.classList.remove('hidden');
                    el.classList.add('border-red-500', 'ring-red-500/20');
                    el.classList.remove('border-green-500', 'ring-green-500/20');
                    
                    // remove spaces dynamically
                    const start = el.selectionStart;
                    const end = el.selectionEnd;
                    rawValue = rawValue.replace(/\s/g, '');
                    el.value = rawValue;
                    if (start) el.setSelectionRange(start - 1, end - 1);
                    return false;
                }
                
                let val = rawValue.toLowerCase().trim();
                
                if (rawValue !== val && !rawValue.includes(' ')) {
                    const start = el.selectionStart;
                    const end = el.selectionEnd;
                    el.value = val;
                    if (start) el.setSelectionRange(start, end);
                }
                
                el.classList.remove('border-red-500', 'border-green-500', 'ring-red-500/20', 'ring-green-500/20');
                
                if (val.length === 0) {
                    errorEl.classList.add('hidden');
                    isEmailOriginalValid = true;
                    return true; // Optional field is true if empty
                }
                
                let errorMsg = '';
                const formatRegex = /^[a-zA-Z0-9._]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/;
                
                if (val.length < 5 || val.length > 100 || !formatRegex.test(val)) {
                    errorMsg = lang === 'gu' ? 'અમાન્ય ઇમેઇલ ફોર્મેટ (ઉદાહરણ: user@gmail.com)' : 
                               lang === 'hi' ? 'अमान्य ईमेल प्रारूप (उदाहरण: user@gmail.com)' : 
                               'Invalid email format (example: user@gmail.com)';
                }
                
                if (errorMsg) {
                    errorEl.textContent = errorMsg;
                    errorEl.classList.remove('hidden');
                    el.classList.add('border-red-500', 'ring-red-500/20');
                    isEmailOriginalValid = false;
                    return false;
                }
                
                errorEl.classList.add('hidden');
                el.classList.add('border-green-500', 'ring-green-500/20');
                isEmailOriginalValid = true;
                
                if (isBlur) {
                    // Start duplicate checking
                    checkDuplicateEmail(val, lang);
                }
                
                return true; // syntactically valid
            }
            
            
            let isPincodeRegisteredValid = false;
            async function checkPincodeDistrict(val, lang) {
                const el = document.getElementById('pincode');
                const errorEl = document.getElementById('pincode-error');
                const selDist = document.getElementById('district').value;
                
                if (!selDist) {
                    // Tell user to pick district first
                    const m = lang === 'gu' ? 'કૃપા કરીને પહેલા જિલ્લો પસંદ કરો' : lang === 'hi' ? 'कृपया पहले जिला चुनें' : 'Please select District first before verifying Pincode.';
                    errorEl.textContent = m;
                    errorEl.classList.remove('hidden');
                    return;
                }
                
                errorEl.textContent = lang === 'gu' ? 'ચકાસણી...' : lang === 'hi' ? 'सत्यापन हो रहा है...' : 'Verifying location...';
                errorEl.classList.remove('hidden');
                errorEl.classList.remove('text-red-500');
                errorEl.classList.add('text-gray-500');
                
                try {
                    const response = await fetch('../backend/check_pincode_api.php?pincode=' + encodeURIComponent(val));
                    const data = await response.json();
                    
                    errorEl.classList.add('text-red-500');
                    errorEl.classList.remove('text-gray-500');
                    
                    if (data.success && data.districts) {
                        // Check if the selected district from our form is natively contained in the postal districts map loosely
                        const match = data.districts.some(d => d.toLowerCase().includes(selDist.toLowerCase()) || selDist.toLowerCase().includes(d.toLowerCase()));
                        if (match) {
                            isPincodeRegisteredValid = true;
                            errorEl.classList.add('hidden');
                            el.classList.add('border-green-500', 'ring-green-500/20');
                        } else {
                            isPincodeRegisteredValid = false;
                            const m = lang === 'gu' ? 'પિનકોડ પસંદ કરેલ જિલ્લા સાથે મેળ ખાતો નથી' : lang === 'hi' ? 'पिनकोड चयनित जिले से मेल नहीं खाता' : `Pincode operates under ${data.districts[0]}. Does not match ${selDist}.`;
                            errorEl.textContent = m;
                            el.classList.remove('border-green-500', 'ring-green-500/20');
                            el.classList.add('border-red-500', 'ring-red-500/20');
                        }
                    } else {
                        isPincodeRegisteredValid = false;
                        const errorMsg = lang === 'gu' ? 'પિનકોડ મળ્યો નથી' : lang === 'hi' ? 'पिनकोड नहीं मिला' : 'Pincode not found in Indian Postal DB.';
                        errorEl.textContent = errorMsg;
                        el.classList.remove('border-green-500', 'ring-green-500/20');
                        el.classList.add('border-red-500', 'ring-red-500/20');
                    }
                } catch (e) {
                    errorEl.classList.add('hidden'); // allow pass if our server errors out securely.
                    isPincodeRegisteredValid = true; 
                }
            }

            function validatePincode(isBlur = false) {
                const el = document.getElementById('pincode');
                let val = el.value;
                const lang = localStorage.getItem('agricare_lang') || 'en';
                const errorEl = document.getElementById('pincode-error');
                
                // Strip non-digits and enforce length of 6
                const sanitized = val.replace(/\D/g, '').slice(0, 6);
                if (val !== sanitized) {
                    el.value = sanitized;
                    val = sanitized;
                }
                
                el.classList.remove('border-red-500', 'border-green-500', 'ring-red-500/20', 'ring-green-500/20');
                
                if (val.length === 0) {
                    errorEl.classList.add('hidden');
                    return false;
                }
                
                if (val.length < 6) {
                    errorEl.textContent = lang === 'gu' ? 'અમાન્ય પિનકોડ (6 અંકો હોવા જોઈએ)' : lang === 'hi' ? 'अमान्य पिनकोड (6 अंक होने चाहिए)' : 'Invalid pincode format (must be 6 digits)';
                    errorEl.classList.remove('hidden');
                    el.classList.add('border-red-500', 'ring-red-500/20');
                    isPincodeRegisteredValid = false;
                    return false;
                }
                
                errorEl.classList.add('hidden');
                
                if (val.length === 6 && isBlur) {
                    checkPincodeDistrict(val, lang);
                }
                return true;
            }
let isEmailRegistered = false;
            async function checkDuplicateEmail(val, lang) {
                if (val.length === 0) {
                    isEmailRegistered = false;
                    return;
                }
                const el = document.getElementById('email');
                const errorEl = document.getElementById('email-error');
                
                try {
                    const response = await fetch('../backend/check_email_api.php?e=' + encodeURIComponent(val));
                    const data = await response.json();
                    
                    if (data.exists) {
                        isEmailRegistered = true;
                        const errorMsg = lang === 'gu' ? 'ઇમેઇલ પહેલેથી જ નોંધાયેલ છે' : 
                                         lang === 'hi' ? 'ईमेल पहले से ही पंजीकृत है' : 
                                         'Email already registered';
                        errorEl.textContent = errorMsg;
                        errorEl.classList.remove('hidden');
                        el.classList.remove('border-green-500', 'ring-green-500/20');
                        el.classList.add('border-red-500', 'ring-red-500/20');
                    } else {
                        isEmailRegistered = false;
                        errorEl.classList.add('hidden');
                        el.classList.add('border-green-500', 'ring-green-500/20');
                    }
                } catch (e) {
                    console.error("Duplicate check failed.");
                }
            }

            function validatePhone() {
                const phoneField = document.getElementById('phone_no');
                let identifier = phoneField.value;

                // Stop text typing - limit to numeric and max 10
                const sanitized = identifier.replace(/\D/g, '').slice(0, 10);
                if (identifier !== sanitized) {
                    phoneField.value = sanitized;
                    identifier = sanitized;
                }

                const valid = /^[6-9]\d{9}$/.test(identifier);
                const empty = identifier.length === 0;

                const errorEl = document.getElementById('phone-reg-error');
                phoneField.classList.remove('border-red-500', 'border-green-500', 'ring-red-500/20', 'ring-green-500/20');
                
                if (empty) {
                    errorEl.classList.add('hidden');
                    return false;
                } else if (!valid) {
                    errorEl.classList.remove('hidden');
                    phoneField.classList.add('border-red-500', 'ring-red-500/20');
                    return false;
                } else {
                    errorEl.classList.add('hidden');
                    phoneField.classList.add('border-green-500', 'ring-green-500/20');
                    return true;
                }
            }



            function validatePin() {
                const pinField = document.getElementById('pin');
                let val = pinField.value;

                // Stop text typing - limit to numeric and max 6
                const sanitized = val.replace(/\D/g, '').slice(0, 6);
                if (val !== sanitized) {
                    pinField.value = sanitized;
                    val = sanitized;
                }

                const valid = /^\d{6}$/.test(val);
                const empty = val.length === 0;

                const errorEl = document.getElementById('pin-error');
                pinField.classList.remove('border-red-500', 'border-green-500', 'ring-red-500/20', 'ring-green-500/20');
                
                if (empty) {
                    errorEl.classList.add('hidden');
                    return false;
                } else if (!valid) {
                    errorEl.classList.remove('hidden');
                    pinField.classList.add('border-red-500', 'ring-red-500/20');
                    return false;
                } else {
                    errorEl.classList.add('hidden');
                    pinField.classList.add('border-green-500', 'ring-green-500/20');
                    return true;
                }
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

                const isNameValid = validateFullName(true);

                validatePhone();

                validatePin();



                if (!isNameValid || !/^[6-9]\d{9}$/.test(phone) || !/^\d{6}$/.test(pin)) {

                    return;

                }



                const dist = document.getElementById('district').value;

                const city = document.getElementById('city').value;

                if (!dist || !city) {

                    document.getElementById('location-error').classList.remove('hidden');

                    return;

                } else {

                    document.getElementById('location-error').classList.add('hidden');

                }



                const btn = document.getElementById('submit-btn');

                const original = btn.innerHTML;

                btn.disabled = true;

                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';



                fetch('../backend/register_api.php', {

                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json'
                        },

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