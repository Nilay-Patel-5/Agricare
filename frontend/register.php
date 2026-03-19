<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | AgriCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .role-specific {
            display: none;
        }

        .role-specific.active {
            display: block;
            animation: fadeInUp 0.4s ease-out;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-emerald-50 via-white to-teal-50 h-screen flex items-center justify-center overflow-hidden">

    <div class="flex w-full h-screen bg-white overflow-hidden">
        <!-- Left Side: Image & Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-emerald-900 overflow-hidden items-end justify-start">
            <div class="absolute inset-0 bg-emerald-900/30 mix-blend-multiply z-10"></div>
            <img src="assets/images/Crop.jpg" alt="Crop" class="absolute inset-0 w-full h-full object-cover object-top z-0">

            <div class="relative z-20 text-white p-12 w-full bg-gradient-to-t from-emerald-900 via-emerald-900/80 to-transparent pt-32">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white text-xl shadow-lg border border-white/30">
                        <i class="fas fa-wheat-awn"></i>
                    </div>
                    <span class="text-2xl font-black text-white tracking-tight" data-lang="logo">AgriCare</span>
                </div>
                <h1 class="text-4xl font-black mb-3 tracking-tight drop-shadow-lg" data-lang="title">Join AgriCare</h1>
                <p class="text-lg text-emerald-50 max-w-lg font-medium drop-shadow-md leading-relaxed" data-lang="subtitle">Create your account to access crop recommendations & more</p>
            </div>
        </div>

        <!-- Right Side: Register Form -->
        <div class="w-full lg:w-1/2 flex flex-col p-8 sm:p-12 relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-teal-50">
            <!-- Decoration -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-yellow-200 rounded-full blur-3xl opacity-50 z-0"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-emerald-200 rounded-full blur-3xl opacity-50 z-0"></div>

            <div class="relative z-10 flex flex-col h-full h-[80vh] min-h-[500px]">

                <!-- Top Navigation (Back & Lang) -->
                <div class="flex justify-between items-center mb-6">
                    <a href="index.php" class="inline-flex items-center gap-2 text-gray-500 hover:text-emerald-600 transition-colors font-semibold text-sm">
                        <i class="fas fa-arrow-left"></i>
                        <span data-lang="backToHome">Back to Home</span>
                    </a>

                    <div class="flex bg-gray-100/70 p-1 rounded-full shadow-sm">
                        <button onclick="changeLang('en')" class="lang-btn px-4 py-2 text-sm font-bold rounded-full mx-0.5 transition-all text-gray-600 hover:bg-emerald-100" data-lang-key="en">EN</button>
                        <button onclick="changeLang('gu')" class="lang-btn px-4 py-2 text-sm font-bold rounded-full mx-0.5 transition-all text-gray-600 hover:bg-emerald-100" data-lang-key="gu">ગુ</button>
                        <button onclick="changeLang('hi')" class="lang-btn px-4 py-2 text-sm font-bold rounded-full mx-0.5 transition-all text-gray-600 hover:bg-emerald-100" data-lang-key="hi">हिं</button>
                    </div>
                </div>

                <p class="text-sm text-center text-gray-500 font-medium mb-6">
                    <span data-lang="haveAccount">Already have an account?</span>
                    <a href="login.php" class="text-emerald-600 hover:text-emerald-700 font-bold underline decoration-2 underline-offset-4 transition-all" data-lang="logIn">Log in</a>
                </p>

                <!-- Role Toggle -->
                <div class="flex justify-center mb-6">
                    <div class="bg-gray-100/50 p-1.5 rounded-2xl inline-flex w-full max-w-sm">
                        <button onclick="toggleRole('farmer')" id="reg-btn-farmer"
                            class="flex-1 py-3 px-3 text-sm font-black rounded-xl bg-white shadow-sm text-emerald-700 transition-all border-2 border-emerald-200 flex items-center justify-center gap-2">
                            <i class="fas fa-tractor text-emerald-500"></i><span data-lang="farmer">FARMER</span>
                        </button>
                        <button onclick="toggleRole('admin')" id="reg-btn-admin"
                            class="flex-1 py-3 px-3 text-sm font-black rounded-xl text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all border-2 border-transparent flex items-center justify-center gap-2">
                            <i class="fas fa-user-shield text-gray-400"></i><span data-lang="admin">ADMIN</span>
                        </button>
                    </div>
                </div>

                <form class="flex-1 flex flex-col min-h-0 relative z-10" action="#" method="POST" id="registerForm" onsubmit="handleRegister(event)">
                    <input type="hidden" name="role" id="reg-role-input" value="farmer">

                    <!-- Scrollable Content Area -->
                    <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">

                        <!-- COMMON FIELDS (Always visible) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <!-- 1. User ID -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                    <i class="fas fa-id-badge text-emerald-500"></i><span data-lang="userIdLbl">User ID</span> <span class="text-red-500">*</span>
                                </label>
                                <input id="user_id" name="user_id" type="text" required maxlength="12"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm"
                                    placeholder="JAY001 (Unique ID)">
                            </div>

                            <!-- 2. Full Name -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                    <i class="fas fa-user text-emerald-500"></i><span data-lang="fullNameLbl">Full Name</span> <span class="text-red-500">*</span>
                                </label>
                                <input id="full_name" name="full_name" type="text" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm"
                                    placeholder="Jaykumar Ramanbhai Patel">
                            </div>

                            <!-- 3. Email -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                    <i class="fas fa-envelope text-emerald-500"></i><span data-lang="emailLbl">Email</span>
                                </label>
                                <input id="email" name="email" type="email"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm"
                                    placeholder="jay.patel@agricare.in">
                            </div>

                            <!-- 4. Phone Number -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                    <i class="fas fa-phone text-emerald-500"></i><span data-lang="phoneLbl">Phone Number</span> <span class="text-red-500">*</span>
                                </label>
                                <input id="phone_no" name="phone_no" type="tel" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm"
                                    placeholder="9876543210" oninput="validatePhone()">
                                <p id="phone-reg-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="phoneRegError">Please enter a valid 10-digit mobile number.</p>
                            </div>
                        </div>

                        <!-- FARMER SPECIFIC FIELDS -->
                        <div id="farmer-fields" class="role-specific active mb-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <!-- District -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                        <i class="fas fa-map text-emerald-500"></i><span data-lang="districtLbl">District</span> <span class="text-red-500">*</span>
                                    </label>
                                    <select id="district" name="district" required onchange="updateCities()"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm bg-white">
                                        <option value="">-- Select District --</option>
                                        <option>Ahmedabad</option>
                                        <option>Amreli</option>
                                        <option>Anand</option>
                                        <option>Arvalli</option>
                                        <option>Banaskantha</option>
                                        <option>Bharuch</option>
                                        <option>Bhavnagar</option>
                                        <option>Botad</option>
                                        <option>Chhota Udaipur</option>
                                        <option>Dahod</option>
                                        <option>Dang</option>
                                        <option>Devbhoomi Dwarka</option>
                                        <option>Gandhinagar</option>
                                        <option>Gir Somnath</option>
                                        <option>Jamnagar</option>
                                        <option>Junagadh</option>
                                        <option>Kheda</option>
                                        <option>Kutch</option>
                                        <option>Mahisagar</option>
                                        <option>Mehsana</option>
                                        <option>Morbi</option>
                                        <option>Narmada</option>
                                        <option>Navsari</option>
                                        <option>Panchmahal</option>
                                        <option>Patan</option>
                                        <option>Porbandar</option>
                                        <option>Rajkot</option>
                                        <option>Sabarkantha</option>
                                        <option>Surat</option>
                                        <option>Surendranagar</option>
                                        <option>Tapi</option>
                                        <option>Vadodara</option>
                                        <option>Valsad</option>
                                    </select>
                                </div>

                                <!-- City -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                        <i class="fas fa-city text-emerald-500"></i><span data-lang="cityLbl">City/Area</span> <span class="text-red-500">*</span>
                                    </label>
                                    <select id="city" name="city" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm bg-white">
                                        <option value="">-- Select District First --</option>
                                    </select>
                                </div>

                                <!-- Pincode -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                        <i class="fas fa-map-pin text-emerald-500"></i><span data-lang="pincodeLbl">Pincode</span> <span class="text-red-500">*</span>
                                    </label>
                                    <input id="pincode" name="pincode" type="text" required maxlength="6"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm"
                                        placeholder="392001">
                                </div>

                                <!-- Preferred Language -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                        <i class="fas fa-language text-emerald-500"></i><span data-lang="prefLangLbl">Preferred Language</span> <span class="text-red-500">*</span>
                                    </label>
                                    <select id="pref_lang" name="pref_lang" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm bg-white">
                                        <option value="" data-lang="selectLang">-- Select Language --</option>
                                        <option value="en" data-lang="langEn">English</option>
                                        <option value="gu" data-lang="langGu">Gujarati</option>
                                        <option value="hi" data-lang="langHi">Hindi</option>
                                    </select>
                                </div>

                                <!-- PIN -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                        <i class="fas fa-lock text-emerald-500"></i><span data-lang="pin">6-Digit PIN</span> <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="pin" name="pin" type="password" required maxlength="6" pattern="\d{6}" inputmode="numeric"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm pr-10"
                                            placeholder="••••••" oninput="validatePin()">
                                        <button type="button" onclick="togglePin()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-emerald-600 transition-colors focus:outline-none">
                                            <i id="pin-icon" class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                    <p id="pin-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="pinError">Please enter exactly 6 digits.</p>
                                </div>

                            </div>
                        </div>

                        <!-- ADMIN SPECIFIC FIELDS -->
                        <div id="admin-fields" class="role-specific mb-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <!-- Admin Email (mandatory) -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                        <i class="fas fa-envelope text-emerald-500"></i><span data-lang="adminEmailLbl">Email Address</span> <span class="text-red-500">*</span>
                                    </label>
                                    <input id="admin_email" name="admin_email" type="email"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm"
                                        placeholder="admin@agricare.in" oninput="validateAdminEmail()">
                                    <p id="admin-email-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="emailError">Please enter a valid email address.</p>
                                </div>

                                <!-- Admin Preferred Language -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                        <i class="fas fa-language text-emerald-500"></i><span data-lang="prefLangLbl">Preferred Language</span> <span class="text-red-500">*</span>
                                    </label>
                                    <select id="admin_pref_lang" name="admin_pref_lang" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm bg-white">
                                        <option value="" data-lang="selectLang">-- Select Language --</option>
                                        <option value="en" data-lang="langEn">English</option>
                                        <option value="gu" data-lang="langGu">Gujarati</option>
                                        <option value="hi" data-lang="langHi">Hindi</option>
                                    </select>
                                </div>

                                <!-- Admin Password -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                                        <i class="fas fa-lock text-emerald-500"></i><span data-lang="adminPwdLbl">Password</span> <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="admin_password" name="admin_password" type="password"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-sm shadow-sm pr-10"
                                            placeholder="••••••••" oninput="validateAdminPassword()">
                                        <button type="button" onclick="toggleAdminPassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-emerald-600 transition-colors focus:outline-none">
                                            <i id="admin-pwd-icon" class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                    <p id="admin-pwd-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="adminPwdError">Password must be at least 7 chars, include 1 uppercase, 1 lowercase, 1 number, and 1 special character.</p>
                                </div>

                            </div>
                        </div>

                    </div> <!-- End Scrollable Content -->

                    <!-- Submit Button (Sticky at bottom) -->
                    <div class="pt-4 mt-auto border-t border-gray-100">
                        <button type="submit" id="submit-btn"
                            class="group relative w-full flex items-center justify-center gap-3 py-3.5 px-6 border border-transparent text-base font-black rounded-xl text-white bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 shadow-xl hover:shadow-emerald-500/50 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
                            <i class="fas fa-user-plus"></i>
                            <span data-lang="createAccount">CREATE ACCOUNT</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentRole = 'farmer';
        let passwordVisible = false;

        // Language
        const translations = {
            en: {
                logo: 'AgriCare',
                title: 'Join AgriCare',
                subtitle: 'Create your account to access crop recommendations & more',
                haveAccount: 'Already have an account?',
                farmer: 'FARMER',
                admin: 'ADMIN',
                createAccount: 'CREATE ACCOUNT',
                pin: '6-Digit PIN',
                pinError: 'Please enter exactly 6 digits.',
                backToHome: 'Back to Home',
                logIn: 'Log in',
                userIdLbl: 'User ID',
                fullNameLbl: 'Full Name',
                emailLbl: 'Email',
                phoneLbl: 'Phone Number',
                districtLbl: 'District',
                cityLbl: 'City/Area',
                pincodeLbl: 'Pincode',
                prefLangLbl: 'Preferred Language',
                selectLang: '-- Select Language --',
                langEn: 'English',
                langGu: 'Gujarati',
                langHi: 'Hindi',
                phoneRegError: 'Please enter a valid 10-digit mobile number.',
                adminEmailLbl: 'Email Address',
                emailError: 'Please enter a valid email address.',
                adminPwdLbl: 'Password',
                adminPwdError: 'Password must be at least 7 chars, include 1 uppercase, 1 lowercase, 1 number, and 1 special character.'
            },
            gu: {
                logo: 'એગ્રીકેર',
                title: 'એગ્રીકેર સાથે જોડાઓ',
                subtitle: 'પાક સલાહ અને વધુ મેળવવા ખાતું બનાવો',
                haveAccount: 'ખાતું છે?',
                farmer: 'ખેડૂત',
                admin: 'એડમિન',
                createAccount: 'ખાતું બનાવો',
                pin: '6-અંકનો પિન',
                pinError: 'કૃપા કરીને બરાબર 6 અંકો દાખલ કરો.',
                backToHome: 'હોમ પર પાછા જાઓ',
                logIn: 'પ્રવેશ કરો',
                userIdLbl: 'યુઝર આઈડી',
                fullNameLbl: 'પૂરું નામ',
                emailLbl: 'ઈમેલ',
                phoneLbl: 'મોબાઈલ નંબર',
                districtLbl: 'જિલ્લો',
                cityLbl: 'શહેર/વિસ્તાર',
                pincodeLbl: 'પીનકોડ',
                prefLangLbl: 'પસંદગીની ભાષા',
                selectLang: '-- ભાષા પસંદ કરો --',
                langEn: 'અંગ્રેજી',
                langGu: 'ગુજરાતી',
                langHi: 'હિન્દી',
                phoneRegError: 'કૃપા કરીને માન્ય 10-અંકનો મોબાઈલ નંબર દાખલ કરો.',
                adminEmailLbl: 'ઈમેલ એડ્રેસ',
                emailError: 'કૃપા કરીને માન્ય ઈમેઈલ સરનામું દાખલ કરો.',
                adminPwdLbl: 'પાસવર્ડ',
                adminPwdError: 'પાસવર્ડમાં ઓછામાં ઓછા 7 અક્ષરો, 1 અપરકેસ, 1 લોઅરકેસ, 1 નંબર અને 1 વિશિષ્ટ અક્ષર હોવા જોઈએ.'
            },
            hi: {
                logo: 'एग्रीकेर',
                title: 'एग्रीकेर जॉइन करें',
                subtitle: 'फसल सलाह और बहुत कुछ प्राप्त करने के लिए अपना खाता बनाएं',
                haveAccount: 'खाता है?',
                farmer: 'किसान',
                admin: 'एडमिन',
                createAccount: 'खाता बनाएं',
                pin: '6-अंकों का पिन',
                pinError: 'कृपया ठीक 6 अंक दर्ज करें।',
                backToHome: 'होम पर वापस जाएं',
                logIn: 'लॉग इन करें',
                userIdLbl: 'यूज़र आईडी',
                fullNameLbl: 'पूरा नाम',
                emailLbl: 'ईमेल',
                phoneLbl: 'फ़ोन नंबर',
                districtLbl: 'ज़िला',
                cityLbl: 'शहर/क्षेत्र',
                pincodeLbl: 'पिनकोड',
                prefLangLbl: 'पसंदीदा भाषा',
                selectLang: '-- भाषा चुनें --',
                langEn: 'अंग्रेज़ी',
                langGu: 'गुजराती',
                langHi: 'हिंदी',
                phoneRegError: 'कृपया एक मान्य 10-अंकीय मोबाइल नंबर दर्ज करें।',
                adminEmailLbl: 'ईमेल पता',
                emailError: 'कृपया एक मान्य ईमेल पता दर्ज करें।',
                adminPwdLbl: 'पासवर्ड',
                adminPwdError: 'पासवर्ड कम से कम 7 अक्षरों का होना चाहिए, जिसमें 1 बड़ा अक्षर, 1 छोटा अक्षर, 1 नंबर और 1 विशेष अक्षर शामिल हो।'
            }
        };

        function changeLang(lang) {
            localStorage.setItem('agricare_lang', lang);

            // Update language switcher buttons
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('bg-emerald-500', 'text-white', 'shadow-md');
                btn.classList.add('text-gray-600', 'hover:bg-emerald-100');
            });
            const activeBtn = document.querySelector(`[data-lang-key="${lang}"]`);
            if (activeBtn) {
                activeBtn.classList.add('bg-emerald-500', 'text-white', 'shadow-md');
                activeBtn.classList.remove('text-gray-600', 'hover:bg-emerald-100');
            }

            document.querySelectorAll('[data-lang]').forEach(el => {
                const key = el.getAttribute('data-lang');
                if (translations[lang] && translations[lang][key]) {
                    el.textContent = translations[lang][key];
                }
            });
            document.body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') document.body.classList.add('gu-text');
            if (lang === 'hi') document.body.classList.add('hi-text');
        }

        // District → City map (major talukas/villages per Gujarat district)
        const districtCityMap = {
            'Ahmedabad': ['Ahmedabad City', 'Dholka', 'Dhandhuka', 'Sanand', 'Viramgam', 'Bavla', 'Mandal', 'Detroj-Rampura'],
            'Amreli': ['Amreli', 'Rajula', 'Savarkundla', 'Lathi', 'Bagasara', 'Jafrabad', 'Khambha', 'Dhari'],
            'Anand': ['Anand', 'Vallabh Vidyanagar', 'Nadiad', 'Petlad', 'Borsad', 'Khambhat', 'Sojitra', 'Umreth'],
            'Arvalli': ['Modasa', 'Bhiloda', 'Bayad', 'Dhansura', 'Malpur', 'Meghraj'],
            'Banaskantha': ['Palanpur', 'Deesa', 'Dhanera', 'Tharad', 'Vav', 'Radhanpur', 'Vadgam', 'Danta'],
            'Bharuch': ['Bharuch', 'Ankleshwar', 'Jambusar', 'Amod', 'Jagadia', 'Valia', 'Jhagadia', 'Hansot'],
            'Bhavnagar': ['Bhavnagar', 'Palitana', 'Sihor', 'Mahuva', 'Talaja', 'Gariadhar', 'Ghogha', 'Vallabhipur'],
            'Botad': ['Botad', 'Gadhada', 'Barwala', 'Ranpur'],
            'Chhota Udaipur': ['Chhota Udaipur', 'Kawant', 'Naswadi', 'Pavi Jetpur', 'Sankheda', 'Bodeli'],
            'Dahod': ['Dahod', 'Godhra', 'Devgadh Baria', 'Limkheda', 'Fatepura', 'Jhalod', 'Garbada'],
            'Dang': ['Ahwa', 'Waghai', 'Subir', 'Ahwa Bazaar'],
            'Devbhoomi Dwarka': ['Khambhalia', 'Dwarka', 'Kalyanpur', 'Bhanvad'],
            'Gandhinagar': ['Gandhinagar', 'Mansa', 'Kalol', 'Dehgam'],
            'Gir Somnath': ['Veraval', 'Somnath', 'Una', 'Kodinar', 'Sutrapada', 'Talala'],
            'Jamnagar': ['Jamnagar', 'Dhrol', 'Jamjodhpur', 'Jodiya', 'Kalavad', 'Lalpur'],
            'Junagadh': ['Junagadh', 'Keshod', 'Visavadar', 'Mangrol', 'Vanthali', 'Mendarda', 'Bhesan'],
            'Kheda': ['Nadiad', 'Kapadvanj', 'Matar', 'Mahemdabad', 'Mahudha', 'Thasra', 'Vaso'],
            'Kutch': ['Bhuj', 'Gandhidham', 'Anjar', 'Mundra', 'Mandvi', 'Rapar', 'Abdasa', 'Nakhatrana'],
            'Mahisagar': ['Godhra', 'Lunawada', 'Santrampur', 'Virpur', 'Khanpur', 'Kadana'],
            'Mehsana': ['Mehsana', 'Visnagar', 'Unjha', 'Kadi', 'Patan', 'Becharaji', 'Satlasana'],
            'Morbi': ['Morbi', 'Tankara', 'Wankaner', 'Halvad', 'Maliya'],
            'Narmada': ['Rajpipla', 'Nandod', 'Dediapada', 'Garudeshwar', 'Sagbara', 'Tilakwada'],
            'Navsari': ['Navsari', 'Gandevi', 'Chikhli', 'Vansda', 'Bansda', 'Jalalpore'],
            'Panchmahal': ['Godhra', 'Halol', 'Kalol', 'Shehera', 'Lunawada', 'Morwa', 'Ghoghamba'],
            'Patan': ['Patan', 'Sidhpur', 'Chanasma', 'Harij', 'Santalpur', 'Radhanpur'],
            'Porbandar': ['Porbandar', 'Ranavav', 'Kutiyana'],
            'Rajkot': ['Rajkot', 'Gondal', 'Jetpur', 'Jasdan', 'Wankaner', 'Paddhari', 'Lodhika'],
            'Sabarkantha': ['Himmatnagar', 'Idar', 'Talod', 'Prantij', 'Khedbrahma', 'Vadali', 'Poshina'],
            'Surat': ['Surat City', 'Bardoli', 'Olpad', 'Mandvi', 'Mahuva', 'Kamrej', 'Mangrol', 'Palsana'],
            'Surendranagar': ['Surendranagar', 'Wadhwan', 'Chotila', 'Dhrangadhra', 'Halvad', 'Limbdi', 'Muli'],
            'Tapi': ['Vyara', 'Surat', 'Nizar', 'Ukai', 'Dolvan', 'Songadh', 'Valod'],
            'Vadodara': ['Vadodara', 'Anklav', 'Dabhoi', 'Karjan', 'Padra', 'Waghodia', 'Savli', 'Shinor'],
            'Valsad': ['Valsad', 'Vapi', 'Pardi', 'Umbergaon', 'Dharampur', 'Kaprada']
        };

        function updateCities() {
            const district = document.getElementById('district').value;
            const citySelect = document.getElementById('city');
            citySelect.innerHTML = '<option value="">-- Select City/Area --</option>';
            if (district && districtCityMap[district]) {
                districtCityMap[district].forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c;
                    opt.textContent = c;
                    citySelect.appendChild(opt);
                });
            }
        }

        // Role Toggle
        function toggleRole(role) {
            currentRole = role;
            document.getElementById('reg-role-input').value = role;

            ['farmer', 'admin'].forEach(r => {
                const btn = document.getElementById(`reg-btn-${r}`);
                if (r === role) {
                    btn.classList.add('bg-white', 'shadow-sm', 'text-emerald-700', 'border-emerald-200');
                    btn.classList.remove('text-gray-500', 'hover:text-gray-700', 'border-transparent');
                } else {
                    btn.classList.remove('bg-white', 'shadow-sm', 'text-emerald-700', 'border-emerald-200');
                    btn.classList.add('text-gray-500', 'hover:text-gray-700', 'border-transparent');
                }
            });

            document.querySelectorAll('.role-specific').forEach(field => field.classList.remove('active'));
            document.getElementById(`${role}-fields`).classList.add('active');

            // --- FIX: Dynamic Required attributes to prevent "not focusable" errors ---
            // Farmer-specific required fields
            const farmerInputs = ['district', 'city', 'pincode', 'pref_lang', 'pin'];
            farmerInputs.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.required = (role === 'farmer');
            });

            // Admin-specific required fields
            const adminInputs = ['admin_email', 'admin_pref_lang', 'admin_password'];
            adminInputs.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.required = (role === 'admin');
            });
        }

        // Farmer phone validation
        function validatePhone() {
            const phone = document.getElementById('phone_no').value;
            const error = document.getElementById('phone-reg-error');
            if (phone.length > 0 && !/^[0-9]{10}$/.test(phone)) {
                error.classList.remove('hidden');
            } else {
                error.classList.add('hidden');
            }
        }

        // Farmer PIN validation
        function validatePin() {
            const pin = document.getElementById('pin').value;
            const error = document.getElementById('pin-error');
            if (pin.length > 0 && !/^\d{6}$/.test(pin)) {
                error.classList.remove('hidden');
            } else {
                error.classList.add('hidden');
            }
        }

        // Admin Email validation
        function validateAdminEmail() {
            const email = document.getElementById('admin_email').value;
            const error = document.getElementById('admin-email-error');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.length > 0 && !emailRegex.test(email)) {
                error.classList.remove('hidden');
            } else {
                error.classList.add('hidden');
            }
        }

        // Admin Password validation
        function validateAdminPassword() {
            const pwd = document.getElementById('admin_password').value;
            const error = document.getElementById('admin-pwd-error');
            const adminRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{7,}$/;
            if (pwd.length > 0 && !adminRegex.test(pwd)) {
                error.classList.remove('hidden');
            } else {
                error.classList.add('hidden');
            }
        }

        // PIN Toggle
        function togglePin() {
            const pin = document.getElementById('pin');
            const icon = document.getElementById('pin-icon');
            passwordVisible = !passwordVisible;
            pin.type = passwordVisible ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !passwordVisible);
            icon.classList.toggle('fa-eye-slash', passwordVisible);
        }

        // Admin Password Toggle
        function toggleAdminPassword() {
            const pwd = document.getElementById('admin_password');
            const icon = document.getElementById('admin-pwd-icon');
            const isVisible = pwd.type === 'text';
            pwd.type = isVisible ? 'password' : 'text';
            icon.classList.toggle('fa-eye', isVisible);
            icon.classList.toggle('fa-eye-slash', !isVisible);
        }

        // Form Submit
        function handleRegister(e) {
            e.preventDefault();
            let hasError = false;

            if (currentRole === 'farmer') {
                // Phone validation
                const phone = document.getElementById('phone_no').value;
                if (!/^[0-9]{10}$/.test(phone)) {
                    document.getElementById('phone-reg-error').classList.remove('hidden');
                    hasError = true;
                } else {
                    document.getElementById('phone-reg-error').classList.add('hidden');
                }

                // PIN validation
                const pin = document.getElementById('pin').value;
                if (!/^\d{6}$/.test(pin)) {
                    document.getElementById('pin-error').classList.remove('hidden');
                    hasError = true;
                } else {
                    document.getElementById('pin-error').classList.add('hidden');
                }

                if (hasError) return;

            } else {
                // Admin email validation
                const email = document.getElementById('admin_email').value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    document.getElementById('admin-email-error').classList.remove('hidden');
                    hasError = true;
                } else {
                    document.getElementById('admin-email-error').classList.add('hidden');
                }

                // Admin password validation
                const pwd = document.getElementById('admin_password').value;
                const adminRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{7,}$/;
                if (!adminRegex.test(pwd)) {
                    document.getElementById('admin-pwd-error').classList.remove('hidden');
                    hasError = true;
                } else {
                    document.getElementById('admin-pwd-error').classList.add('hidden');
                }

                if (hasError) return;
            }

            const formData = {
                user_id: document.getElementById('user_id').value,
                full_name: document.getElementById('full_name').value,
                email: currentRole === 'admin' ? document.getElementById('admin_email').value : document.getElementById('email').value,
                phone_no: currentRole === 'farmer' ? document.getElementById('phone_no').value : '',
                role: currentRole,
                pincode: document.getElementById('pincode')?.value || '',
                district: document.getElementById('district')?.value || '',
                city: document.getElementById('city')?.value || '',
                pin: document.getElementById('pin')?.value || '',
                pref_lang: currentRole === 'farmer' ? (document.getElementById('pref_lang')?.value || '') : (document.getElementById('admin_pref_lang')?.value || ''),
                password: currentRole === 'admin' ? document.getElementById('admin_password').value : ''
            };

            const btn = document.getElementById('submit-btn');
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';
            btn.disabled = true;

            fetch("../backend/register_api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(formData)
            })
            .then(async res => {
                const text = await res.text();
                try {
                    return JSON.parse(text);
                } catch(e) {
                    console.error("Server raw response:", text);
                    throw new Error("Invalid server response format.");
                }
            })
            .then(data => {
                if (data.success) {
                    alert(`✅ Account created successfully!\nWelcome ${data.user.name}`);
                    window.location.href = 'login.php';
                } else {
                    alert(`❌ Error: ${data.message || 'Unknown error'}`);
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error("Registration error:", err);
                alert(`❌ Connection Failed: ${err.message || "Server offline"}`);
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        }

        const urlParams = new URLSearchParams(window.location.search);
        const initialLang = urlParams.get('lang') || localStorage.getItem('agricare_lang') || 'en';
        changeLang(initialLang);
    </script>
</body>

</html>