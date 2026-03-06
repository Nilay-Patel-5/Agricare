<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | AgriCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap"
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="flex w-full h-screen bg-white overflow-hidden">
        <!-- Left Side: Image -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-emerald-900 overflow-hidden items-end justify-start">
            <div class="absolute inset-0 bg-emerald-900/30 mix-blend-multiply z-10"></div>
            <img src="assets/images/farmer.jpg" alt="Farmer" class="absolute inset-0 w-full h-full object-cover object-top z-0">

            <div class="relative z-20 text-white p-12 w-full bg-gradient-to-t from-emerald-900 via-emerald-900/80 to-transparent pt-32">
                <h1 class="text-4xl font-black mb-3 tracking-tight drop-shadow-lg" data-lang="heroTitle">Empowering Gujarat's Farmers</h1>
                <p class="text-lg text-emerald-50 max-w-lg font-medium drop-shadow-md leading-relaxed" data-lang="heroSubtitle">Join AgriCare to access real-time market prices, weather updates, and expert crop advisory tailored just for you.</p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-teal-50">
            <!-- Abstract Decoration -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-yellow-200 rounded-full blur-3xl opacity-50 z-0"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-emerald-200 rounded-full blur-3xl opacity-50 z-0"></div>

            <div class="max-w-md w-full space-y-6 relative z-10 glass-card p-6 sm:p-8 rounded-[2rem] border border-white/60 shadow-xl">
                <div class="text-center relative z-10">
                    <!-- Back to Home Link -->
                    <div class="flex justify-start mb-2">
                        <a href="index.php" class="inline-flex items-center gap-2 text-gray-500 hover:text-emerald-600 transition-colors font-semibold text-sm">
                            <i class="fas fa-arrow-left"></i>
                            <span data-lang="backToHome">Back to Home</span>
                        </a>
                    </div>

                    <a href="index.php" class="inline-flex items-center gap-3 group mb-2">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-emerald-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-wheat-awn"></i>
                        </div>
                        <span class="text-2xl font-black text-gray-800 tracking-tight" data-lang="logo">AgriCare</span>
                    </a>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight" data-lang="welcomeBack">Welcome Back</h2>
                    <p class="mt-1 text-sm text-gray-500 font-medium">
                        <span data-lang="newToAgricare">New to AgriCare?</span> <a href="register.php"
                            class="text-emerald-600 hover:text-emerald-700 font-bold underline decoration-2 underline-offset-4" data-lang="createAccount">Create
                            account</a>
                    </p>
                </div>

                <!-- Language Switcher -->
                <div class="flex justify-center mb-4 relative z-10">
                    <div class="flex bg-gray-100/70 p-1 rounded-full shadow-sm">
                        <button onclick="changeLang('en')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 transition-all active:bg-emerald-500 active:text-white active:shadow-md" data-lang-key="en">EN</button>
                        <button onclick="changeLang('gu')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 hover:bg-emerald-100 transition-all" data-lang-key="gu">ગુ</button>
                        <button onclick="changeLang('hi')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 hover:bg-emerald-100 transition-all" data-lang-key="hi">हिं</button>
                    </div>
                </div>

                <!-- Role Selector -->
                <div class="flex justify-center space-x-1 bg-gray-100/50 p-1.5 rounded-2xl mb-4 relative z-10">
                    <button onclick="selectRole('farmer')" id="btn-farmer"
                        class="flex-1 py-2.5 text-xs font-bold rounded-xl bg-white shadow-sm text-emerald-700 transition-all">FARMER</button>
                    <button onclick="selectRole('admin')" id="btn-admin"
                        class="flex-1 py-2.5 text-xs font-bold rounded-xl text-gray-500 hover:text-gray-700 transition-all">ADMIN</button>
                </div>

                <form class="mt-4 space-y-4 relative z-10" action="#" method="GET" onsubmit="handleLogin(event)">
                    <input type="hidden" name="role" id="role-input" value="farmer">
                    <div class="space-y-3">
                        <div>
                            <label id="identifier-label"
                                class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Phone Number</label>
                            <input id="identifier-input" name="identifier" type="tel" autocomplete="tel" required
                                class="appearance-none rounded-2xl relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all sm:text-sm"
                                placeholder="e.g. 9876543210">
                            <p id="phone-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="phoneError">Please enter a valid 10-digit mobile number.</p>
                        </div>
                        <div>
                            <label id="password-label" for="password"
                                class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1" data-lang="password">6-Digit PIN</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" inputmode="numeric" required disabled maxlength="6" pattern="\d{6}"
                                    class="appearance-none rounded-2xl relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-100 pr-10"
                                    placeholder="••••••">
                                <button type="button" id="toggle-pwd-btn" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-emerald-600 transition-colors focus:outline-none z-20">
                                    <i id="pwd-icon" class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <p id="admin-password-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="adminPasswordError">Password must be at least 7 chars, include 1 uppercase, 1 lowercase, 1 number, and 1 special char.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox"
                                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded-lg">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-600 font-medium" data-lang="rememberMe"> Remember me </label>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-2xl text-white bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 shadow-lg hover:shadow-emerald-500/30 hover:-translate-y-0.5 transition-all">
                            <span data-lang="signIn">SIGN IN</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const translations = {
            en: {
                heroTitle: 'Empowering Gujarat\'s Farmers',
                heroSubtitle: 'Join AgriCare to access real-time market prices, weather updates, and expert crop advisory tailored just for you.',
                backToHome: 'Back to Home',
                logo: 'AgriCare',
                welcomeBack: 'Welcome Back',
                newToAgricare: 'New to AgriCare?',
                createAccount: 'Create account',
                farmer: 'FARMER',
                admin: 'ADMIN',
                phoneNumber: 'Phone Number',
                emailAddress: 'Email Address',
                password: 'Password',
                adminPasswordError: 'Password must be at least 7 chars, include 1 uppercase, 1 lowercase, 1 number, and 1 special char.',
                pin: '6-Digit PIN',
                phoneError: 'Please enter a valid 10-digit mobile number.',
                rememberMe: 'Remember me',
                signIn: 'SIGN IN'
            },
            gu: {
                heroTitle: 'ગુજરાતના ખેડૂતોનું સશક્તિકરણ',
                heroSubtitle: 'રીઅલ-ટાઇમ બજારના ભાવો, હવામાન અપડેટ્સ અને તમારા માટે તૈયાર કરેલી નિષ્ણાત પાકની સલાહ મેળવવા માટે એગ્રીકેરમાં જોડાઓ.',
                backToHome: 'હોમ પર પાછા જાઓ',
                logo: 'એગ્રીકેર',
                welcomeBack: 'પાછા સ્વાગત છે',
                newToAgricare: 'એગ્રીકેરમાં નવા છો?',
                createAccount: 'ખાતું બનાવો',
                farmer: 'ખેડૂત',
                admin: 'એડમિન',
                phoneNumber: 'મોબાઈલ નંબર',
                emailAddress: 'ઈમેલ એડ્રેસ',
                password: 'પાસવર્ડ',
                adminPasswordError: 'પાસવર્ડમાં ઓછામાં ઓછા 7 અક્ષરો, 1 અપરકેસ, 1 લોઅરકેસ, 1 નંબર અને 1 વિશિષ્ટ અક્ષર હોવા જોઈએ.',
                pin: '6-અંકનો પિન',
                phoneError: 'કૃપા કરીને માન્ય 10-અંકનો મોબાઈલ નંબર દાખલ કરો.',
                rememberMe: 'મને યાદ રાખો',
                signIn: 'પ્રવેશ કરો'
            },
            hi: {
                heroTitle: 'गुजरात के किसानों का सशक्तिकरण',
                heroSubtitle: 'वास्तविक समय के बाजार मूल्य, मौसम अपडेट और विशेष रूप से आपके लिए तैयार विशेषज्ञ फसल सलाह प्राप्त करने के लिए एग्रीकेर से जुड़ें।',
                backToHome: 'होम पर वापस जाएं',
                logo: 'एग्रीकेर',
                welcomeBack: 'वापसी पर स्वागत है',
                newToAgricare: 'एग्रीकेर में नए हैं?',
                createAccount: 'खाता बनाएं',
                farmer: 'किसान',
                admin: 'एडमिन',
                phoneNumber: 'फ़ोन नंबर',
                emailAddress: 'ईमेल पता',
                password: 'पासवर्ड',
                adminPasswordError: 'पासवर्ड कम से कम 7 अक्षरों का होना चाहिए, जिसमें 1 बड़ा अक्षर, 1 छोटा अक्षर, 1 नंबर और 1 विशेष अक्षर शामिल होना चाहिए।',
                pin: '6-अंकों का पिन',
                phoneError: 'कृपया एक मान्य 10-अंकीय मोबाइल नंबर दर्ज करें।',
                rememberMe: 'मुझे याद रखें',
                signIn: 'साइन इन'
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

            document.querySelectorAll('[data-lang]').forEach(el => {
                const key = el.getAttribute('data-lang');
                if (translations[lang][key]) el.textContent = translations[lang][key];
            });

            // Specific role labels
            document.getElementById('btn-farmer').textContent = translations[lang].farmer;
            document.getElementById('btn-admin').textContent = translations[lang].admin;

            // Re-apply correct input label based on current role selection
            const role = document.getElementById('role-input').value;
            const label = document.getElementById('identifier-label');
            const pwdLabel = document.getElementById('password-label');
            if (role === 'farmer') {
                label.textContent = translations[lang].phoneNumber;
                pwdLabel.textContent = translations[lang].pin;
            } else {
                label.textContent = translations[lang].emailAddress;
                pwdLabel.textContent = translations[lang].password;
            }

            document.body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') document.body.classList.add('gu-text');
            if (lang === 'hi') document.body.classList.add('hi-text');
        }

        function selectRole(role) {
            // Update active button style
            document.querySelectorAll('button[id^="btn-"]').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow-sm', 'text-emerald-700');
                btn.classList.add('text-gray-500', 'hover:text-gray-700');
            });
            const activeBtn = document.getElementById(`btn-${role}`);
            activeBtn.classList.remove('text-gray-500', 'hover:text-gray-700');
            activeBtn.classList.add('bg-white', 'shadow-sm', 'text-emerald-700');
            document.getElementById('role-input').value = role;

            document.getElementById('phone-error').classList.add('hidden');

            const lang = localStorage.getItem('agricare_lang') || 'en';

            // Swap input field based on role
            const label = document.getElementById('identifier-label');
            const input = document.getElementById('identifier-input');
            const pwdLabel = document.getElementById('password-label');
            const pwdInput = document.getElementById('password');

            const pwdToggleBtn = document.getElementById('toggle-pwd-btn');
            const pwdIcon = document.getElementById('pwd-icon');

            if (role === 'farmer') {
                label.textContent = translations[lang].phoneNumber;
                input.type = 'tel';
                input.name = 'phone';
                input.autocomplete = 'tel';
                input.placeholder = 'e.g. 9876543210';

                pwdLabel.textContent = translations[lang].pin;
                pwdInput.type = 'password';
                pwdInput.inputMode = 'numeric';
                pwdInput.maxLength = 6;
                pwdInput.pattern = '\\d{6}';
                pwdInput.placeholder = '••••••';
                pwdToggleBtn.classList.remove('hidden'); // Show eye icon for farmers
                pwdIcon.classList.remove('fa-eye-slash');
                pwdIcon.classList.add('fa-eye');

                // Ensure it gets validated immediately when switched
                validatePhoneInput();
            } else {
                label.textContent = translations[lang].emailAddress;
                input.type = 'email';
                input.name = 'email';
                input.autocomplete = 'email';
                input.placeholder = 'name@example.com';

                pwdLabel.textContent = translations[lang].password;
                pwdInput.type = 'password';
                pwdInput.removeAttribute('inputMode');
                pwdInput.removeAttribute('maxLength');
                pwdInput.removeAttribute('pattern');
                pwdInput.placeholder = '••••••••';
                pwdToggleBtn.classList.remove('hidden'); // Show eye icon for admins
                pwdIcon.classList.remove('fa-eye-slash');
                pwdIcon.classList.add('fa-eye');

                // Admins do not need the phone check
                pwdInput.disabled = false;
                document.getElementById('phone-error').classList.add('hidden');
                document.getElementById('admin-password-error').classList.add('hidden');
            }
        }

        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('password');
            const pwdIcon = document.getElementById('pwd-icon');

            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                pwdIcon.classList.remove('fa-eye');
                pwdIcon.classList.add('fa-eye-slash');
            } else {
                pwdInput.type = 'password';
                pwdIcon.classList.remove('fa-eye-slash');
                pwdIcon.classList.add('fa-eye');
            }
        }

        function validatePhoneInput() {
            const role = document.getElementById('role-input').value;
            if (role !== 'farmer') return;

            const identifier = document.getElementById('identifier-input').value;
            const pwdInput = document.getElementById('password');
            const errorElement = document.getElementById('phone-error');

            const phoneRegex = /^[0-9]{10}$/;

            if (identifier.length > 0) {
                if (phoneRegex.test(identifier)) {
                    errorElement.classList.add('hidden');
                    pwdInput.disabled = false; // Unlock PIN
                } else {
                    errorElement.classList.remove('hidden');
                    pwdInput.disabled = true; // Lock PIN
                    pwdInput.value = ''; // Clear PIN if they had one and changed the phone number
                }
            } else {
                // If empty, hide error but keep PIN locked
                errorElement.classList.add('hidden');
                pwdInput.disabled = true;
                pwdInput.value = '';
            }
        }

        function handleLogin(e) {
            e.preventDefault();
            const role = document.getElementById('role-input').value;
            const identifier = document.getElementById('identifier-input').value;
            const lang = localStorage.getItem('agricare_lang') || 'en';

            if (role === 'farmer') {
                const phoneRegex = /^[0-9]{10}$/;
                if (!phoneRegex.test(identifier)) {
                    document.getElementById('phone-error').classList.remove('hidden');
                    return; // Stop form submission
                }

                // PIN validation
                const pin = document.getElementById('password').value;
                const pinRegex = /^\d{6}$/;
                if (!pinRegex.test(pin)) {
                    alert(lang === 'gu' ? 'કૃપા કરીને 6-અંકનો પિન દાખલ કરો.' : (lang === 'hi' ? 'कृपया 6-अंकीय पिन दर्ज करें।' : 'Please enter a 6-digit PIN.'));
                    return;
                }
            } else {
                // Admin Password validation
                const pwd = document.getElementById('password').value;
                const adminRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{7,}$/;
                if (!adminRegex.test(pwd)) {
                    document.getElementById('admin-password-error').classList.remove('hidden');
                    return; // Stop form submission
                }
            }

            // Hide error if successful
            document.getElementById('phone-error').classList.add('hidden');
            document.getElementById('admin-password-error').classList.add('hidden');
            window.location.href = `../dashboard/${role}.html`;
        }

        document.getElementById('identifier-input').addEventListener('input', validatePhoneInput);
        document.getElementById('identifier-input').addEventListener('blur', validatePhoneInput);

        document.getElementById('password').addEventListener('input', function() {
            if (document.getElementById('role-input').value === 'admin') {
                document.getElementById('admin-password-error').classList.add('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const initialLang = urlParams.get('lang') || localStorage.getItem('agricare_lang') || 'en';
            changeLang(initialLang);

            // Run initial validation to lock PIN field by default
            validatePhoneInput();
        });
    </script>
</body>

</html>