<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | AgriCare</title>
    <link rel="stylesheet" href="./output.css">
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
    <div id="progressBar"></div>

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
                        <button type="button" onclick="changeLang('en')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 transition-all active:bg-emerald-500 active:text-white active:shadow-md" data-lang-key="en">EN</button>
                        <button type="button" onclick="changeLang('gu')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 hover:bg-emerald-100 transition-all" data-lang-key="gu">ગુ</button>
                        <button type="button" onclick="changeLang('hi')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 hover:bg-emerald-100 transition-all" data-lang-key="hi">हिं</button>
                    </div>
                </div>

                <form class="mt-4 space-y-4 relative z-10" action="#" method="POST" onsubmit="handleLogin(event)">
                    <input type="hidden" name="role" id="role-input" value="farmer">
                    <div class="space-y-3">
                        <div>
                            <label id="identifier-label" data-lang="unifiedIdentifier"
                                class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Email or Phone Number</label>
                            <input id="identifier-input" name="identifier" type="text" autocomplete="username" required
                                class="appearance-none rounded-2xl relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all sm:text-sm"
                                placeholder="Enter Email or Phone no" data-lang-placeholder="unifiedPlaceholder" oninput="handleIdentifierInput()">
                            <p id="phone-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="phoneError">Enter a valid 10-digit mobile number starting with 6, 7, 8, or 9.</p>
                            <p id="email-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="emailError">Enter a valid admin email (example: name@agricare.admin)</p>
                        </div>
                        <div>
                            <label id="password-label" for="password"
                                class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1" data-lang="password">6-Digit PIN</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" inputmode="numeric" required disabled maxlength="6" pattern="\d{6}" oninput="handlePasswordInput()"
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

                    <!-- Captcha Section -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1" data-lang="captchaLabel">Security Check</label>
                        <div class="flex gap-2 relative">
                            <div class="bg-gray-200 border border-gray-300 rounded-2xl px-4 py-3 flex items-center justify-center font-mono font-bold text-gray-800 tracking-widest text-lg min-w-[120px] select-none italic relative overflow-hidden group">
                                <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 4px, #000 4px, #000 5px);"></div>
                                <span id="captcha-display" class="relative z-10 mix-blend-difference text-gray-800">.....</span>
                                <button type="button" onclick="loadCaptcha()" class="absolute right-1 top-1 text-gray-500 hover:text-emerald-700 hover:bg-white bg-white/70 backdrop-blur-sm rounded p-1 opacity-0 group-hover:opacity-100 transition-all z-20 shadow-sm" title="Regenerate CAPTCHA">
                                    <i class="fas fa-sync-alt text-xs"></i>
                                </button>
                            </div>
                            <input type="text" id="captcha-input" required autocomplete="off"
                                class="appearance-none rounded-2xl relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all sm:text-sm font-mono tracking-widest"
                                placeholder="ENTER CODE" data-lang-placeholder="captchaPlaceholder">
                        </div>
                        <p id="captcha-error" class="hidden text-xs text-red-500 mt-1 font-bold ml-1" data-lang="captchaError">Incorrect CAPTCHA. Please try again.</p>
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
                unifiedIdentifier: 'Email or Phone no',
                unifiedPlaceholder: 'Enter Email or Phone no',
                enterPin: 'Enter 6-digit PIN',
                enterPwd: 'Enter Password',
                phoneNumber: 'Phone Number',
                emailAddress: 'Email Address',
                password: 'Password',
                adminPasswordError: 'Password must be at least 7 chars, include 1 uppercase, 1 lowercase, 1 number, and 1 special char.',
                pin: '6-Digit PIN',
                phoneError: 'Enter a valid 10-digit mobile number starting with 6, 7, 8, or 9.',
                emailError: 'Enter a valid admin email (example: name@agricare.admin)',
                rememberMe: 'Remember me',
                signIn: 'SIGN IN',
                captchaLabel: 'Security Check',
                captchaError: 'Incorrect answer. Please try again.',
                captchaPlaceholder: 'ENTER CODE'
            },
            gu: {
                heroTitle: 'ગુજરાતના ખેડૂતોનું સશક્તિકરણ',
                heroSubtitle: 'વાસ્તવિક સમયના બજાર ભાવ, હવામાનની જાણકારી અને ખાસ તમારા માટે તૈયાર કરાતી પાકની નિષ્ણાત સલાહ મેળવવા એગ્રીકેર સાથે જોડાઓ.',
                backToHome: 'હોમ પર પાછા જાઓ',
                logo: 'એગ્રીકેર',
                welcomeBack: 'પાછા સ્વાગત છે',
                newToAgricare: 'એગ્રીકેરમાં નવા છો?',
                createAccount: 'ખાતું બનાવો',
                farmer: 'ખેડૂત',
                admin: 'એડમિન',
                unifiedIdentifier: 'ઈમેલ અથવા મોબાઈલ નંબર',
                unifiedPlaceholder: 'ઈમેલ અથવા મોબાઈલ નંબર દાખલ કરો',
                enterPin: '6-અંકનો પિન દાખલ કરો',
                enterPwd: 'પાસવર્ડ દાખલ કરો',
                phoneNumber: 'મોબાઈલ નંબર',
                emailAddress: 'ઈમેલ એડ્રેસ',
                password: 'પાસવર્ડ',
                adminPasswordError: 'પાસવર્ડમાં ઓછામાં ઓછા 7 અક્ષરો, 1 અપરકેસ, 1 લોઅરકેસ, 1 નંબર અને 1 વિશિષ્ટ અક્ષર હોવા જોઈએ.',
                pin: '6-અંકનો પિન',
                phoneError: 'કૃપા કરીને 6, 7, 8, અથવા 9 થી શરૂ થતો માન્ય 10-અંકનો મોબાઈલ નંબર દાખલ કરો.',
                emailError: 'કૃપા કરીને માન્ય એડમિન ઈમેલ દાખલ કરો (ઉદાહરણ: name@agricare.admin)',
                rememberMe: 'મને યાદ રાખો',
                signIn: 'પ્રવેશ કરો',
                captchaLabel: 'સુરક્ષા તપાસ',
                captchaError: 'ખોટો જવાબ. કૃપા કરીને ફરી પ્રયાસ કરો.',
                captchaPlaceholder: 'કોડ દાખલ કરો'
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
                unifiedIdentifier: 'ईमेल या फ़ोन नंबर',
                unifiedPlaceholder: 'ईमेल या फ़ोन नंबर दर्ज करें',
                enterPin: '6-अंकीय पिन दर्ज करें',
                enterPwd: 'पासवर्ड दर्ज करें',
                phoneNumber: 'फ़ोन नंबर',
                emailAddress: 'ईमेल पता',
                password: 'पासवर्ड',
                adminPasswordError: 'पासवर्ड कम से कम 7 अक्षरों का होना चाहिए, जिसमें 1 बड़ा अक्षर, 1 छोटा अक्षर, 1 नंबर और 1 विशेष अक्षर शामिल होना चाहिए।',
                pin: '6-अंकों का पिन',
                phoneError: 'कृपया 6, 7, 8, या 9 से शुरू होने वाला एक मान्य 10-अंकीय मोबाइल नंबर दर्ज करें।',
                emailError: 'कृपया एक मान्य एडमिन ईमेल दर्ज करें (उदाहरण: name@agricare.admin)',
                rememberMe: 'मुझे याद रखें',
                signIn: 'प्रवेश करें',
                captchaLabel: 'सुरक्षा जाँच',
                captchaError: 'गलत जवाब। कृपया पुनः प्रयास करें।',
                captchaPlaceholder: 'कोड दर्ज करें'
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

            document.querySelectorAll('[data-lang-placeholder]').forEach(el => {
                const key = el.getAttribute('data-lang-placeholder');
                if (translations[lang][key]) el.placeholder = translations[lang][key];
            });

            // Re-apply correct input label based on current role selection if needed
            handleIdentifierInput();

            document.body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') document.body.classList.add('gu-text');
            if (lang === 'hi') document.body.classList.add('hi-text');
        }

        function handlePasswordInput() {
            const role = document.getElementById('role-input').value;
            if (role === 'farmer') {
                const pwdInput = document.getElementById('password');
                const sanitized = pwdInput.value.replace(/\D/g, '').slice(0, 6);
                if (pwdInput.value !== sanitized) {
                    pwdInput.value = sanitized;
                }
            }
        }

        function handleIdentifierInput() {
            let identifierField = document.getElementById('identifier-input');
            let identifier = identifierField.value.trim();
            const lang = localStorage.getItem('agricare_lang') || 'en';
            
            // Limit numeric input
            if (/^\d/.test(identifier)) {
                // If it starts with a number, strictly enforce numbers only and length 10
                const sanitized = identifier.replace(/\D/g, '').slice(0, 10);
                if (identifier !== sanitized) {
                    identifierField.value = sanitized;
                    identifier = sanitized;
                }
            }

            const pwdLabel = document.getElementById('password-label');
            const pwdInput = document.getElementById('password');
            const pwdToggleBtn = document.getElementById('toggle-pwd-btn');
            const pwdIcon = document.getElementById('pwd-icon');

            // Empty input acts as either, but we default to Admin format visually to allow typing anything
            const isNumeric = /^\d+$/.test(identifier) && identifier.length > 0;
            const isFarmer = isNumeric;
            document.getElementById('role-input').value = isFarmer ? 'farmer' : 'admin';

            document.getElementById('phone-error').classList.add('hidden');
            document.getElementById('email-error').classList.add('hidden');
            document.getElementById('admin-password-error').classList.add('hidden');

            if (isFarmer) {
                pwdLabel.textContent = translations[lang].pin;
                pwdInput.type = 'password';
                pwdInput.inputMode = 'numeric';
                pwdInput.maxLength = 6;
                pwdInput.pattern = '\\d{6}';
                pwdInput.placeholder = translations[lang].enterPin || '••••••';
                pwdToggleBtn.classList.remove('hidden');
                if (pwdIcon.classList.contains('fa-eye-slash') && pwdInput.type === 'password') {
                     pwdIcon.classList.remove('fa-eye-slash');
                     pwdIcon.classList.add('fa-eye');
                }
                
                // Unlock PIN only if fully 10 digits and starts with 6-9
                const phoneRegex = /^[6-9]\d{9}$/;
                if (phoneRegex.test(identifier)) {
                    pwdInput.disabled = false;
                    document.getElementById('phone-error').classList.add('hidden');
                } else {
                    pwdInput.disabled = true;
                    if (identifier.length > 0) {
                        document.getElementById('phone-error').classList.remove('hidden');
                    }
                }
            } else {
                pwdLabel.textContent = translations[lang].password;
                pwdInput.type = 'password';
                pwdInput.removeAttribute('inputMode');
                pwdInput.removeAttribute('maxLength');
                pwdInput.removeAttribute('pattern');
                pwdInput.placeholder = translations[lang].enterPwd || '••••••••';
                pwdToggleBtn.classList.remove('hidden');

                // Admin email format check
                const emailRegex = /^[a-zA-Z0-9._]+@agricare\.admin$/;
                if (emailRegex.test(identifier)) {
                    pwdInput.disabled = false;
                    document.getElementById('email-error').classList.add('hidden');
                } else {
                    pwdInput.disabled = true;
                    if (identifier.length > 0) {
                        document.getElementById('email-error').classList.remove('hidden');
                    }
                }
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

        function handleLogin(e) {
            e.preventDefault();
            const identifier = document.getElementById('identifier-input').value.trim();
            const lang = localStorage.getItem('agricare_lang') || 'en';
            const role = /^\d+$/.test(identifier) ? 'farmer' : 'admin';

            if (role === 'farmer') {
                const phoneRegex = /^[6-9]\d{9}$/;
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
                const emailRegex = /^[a-zA-Z0-9._]+@agricare\.admin$/;
                if (!emailRegex.test(identifier)) {
                    document.getElementById('email-error').classList.remove('hidden');
                    return; // Stop form submission
                }

                // Admin Password validation
                const pwd = document.getElementById('password').value;
                const adminRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{7,}$/;
                if (!adminRegex.test(pwd)) {
                    document.getElementById('admin-password-error').classList.remove('hidden');
                    return; // Stop form submission
                }
            }

            const captchaInput = document.getElementById('captcha-input').value;
            if (!captchaInput) {
                document.getElementById('captcha-error').classList.remove('hidden');
                return;
            }
            document.getElementById('captcha-error').classList.add('hidden');

            // Prepare data for backend
            const loginData = {
                role: role,
                identifier: identifier,
                password: document.getElementById('password').value,
                captcha: captchaInput
            };

            const btn = e.target.querySelector('button[type="submit"]');
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing in...';
            btn.disabled = true;

            fetch("../backend/login_api.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(loginData)
            })
            .then(async res => {
                const data = await res.json().catch(() => ({
                    success: false,
                    message: `Unexpected server response (${res.status})`
                }));

                if (!res.ok) {
                    throw new Error(data.message || `Request failed with status ${res.status}`);
                }

                return data;
            })
            .then(data => {
                if (data.success) {
                    sessionStorage.setItem('agricare_user', JSON.stringify(data.user));
                    
                    if (document.getElementById('remember-me').checked) {
                        localStorage.setItem('agricare_user', JSON.stringify(data.user));
                    } else {
                        localStorage.removeItem('agricare_user'); // act as session-only
                    }
                    
                    localStorage.setItem('agricare_lang', data.user.pref_lang || lang);
                    window.location.href = `../dashboard/${role}.php`;
                } else {
                    if (data.message && data.message.includes("CAPTCHA")) {
                        document.getElementById('captcha-error').textContent = data.message;
                        document.getElementById('captcha-error').classList.remove('hidden');
                        document.getElementById('captcha-input').value = '';
                    } else {
                        alert(`Login Failed: ${data.message}`);
                    }
                    loadCaptcha(); // Always reload CAPTCHA on failure
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error("Login error:", err);
                alert(`Login failed: ${err.message}`);
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        }

        document.getElementById('identifier-input').addEventListener('blur', handleIdentifierInput);

        document.getElementById('password').addEventListener('input', function() {
            if (document.getElementById('role-input').value === 'admin') {
                document.getElementById('admin-password-error').classList.add('hidden');
            }
        });

        function loadCaptcha() {
            fetch("../backend/captcha_api.php")
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('captcha-display').textContent = data.captcha;
                        document.getElementById('captcha-input').value = '';
                    }
                })
                .catch(err => console.error("Error loading captcha:", err));
        }

        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const initialLang = urlParams.get('lang') || localStorage.getItem('agricare_lang') || 'en';
            changeLang(initialLang);
            handleIdentifierInput();
            loadCaptcha();
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
