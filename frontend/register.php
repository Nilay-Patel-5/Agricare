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

<body class="bg-gradient-to-br from-emerald-50 via-white to-teal-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-2xl w-full space-y-8 glass-card p-10 rounded-[2.5rem] shadow-2xl border border-white relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute -top-12 -left-12 w-40 h-40 bg-yellow-100 rounded-full blur-3xl opacity-60"></div>
        <div class="absolute -bottom-12 -right-12 w-40 h-40 bg-emerald-100 rounded-full blur-3xl opacity-60"></div>

        <!-- Header -->
        <div class="text-center relative z-10">
            <a href="index.php" class="inline-flex items-center gap-3 group mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-emerald-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-wheat-awn"></i>
                </div>
                <span class="text-2xl font-black text-gray-800 tracking-tight" data-lang="logo">AgriCare</span>
            </a>
            <h2 class="text-4xl font-black text-gray-900 tracking-tight" data-lang="title">Join AgriCare</h2>
            <p class="mt-2 text-lg text-gray-600 font-medium" data-lang="subtitle">
                Create your account to access live mandi prices, crop recommendations & more
            </p>
            <p class="text-sm text-gray-500 font-medium">
                <span data-lang="haveAccount">Already have an account?</span>
                <a href="login.php" class="text-emerald-600 hover:text-emerald-700 font-bold underline decoration-2 underline-offset-4 transition-all">Log in</a>
            </p>
        </div>

        <!-- Language Switcher -->
        <div class="flex justify-center mb-6 relative z-10">
            <div class="flex bg-gray-100/70 p-1 rounded-full shadow-sm">
                <button onclick="changeLang('en')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 transition-all active:bg-emerald-500 active:text-white active:shadow-md" data-lang-key="en">EN</button>
                <button onclick="changeLang('gu')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 hover:bg-emerald-100 transition-all" data-lang-key="gu">ગુ</button>
                <button onclick="changeLang('hi')" class="lang-btn px-3 py-2 text-xs font-bold rounded-full mx-0.5 hover:bg-emerald-100 transition-all" data-lang-key="hi">हिं</button>
            </div>
        </div>

        <!-- Role Toggle -->
        <div class="flex justify-center mb-8 relative z-10">
            <div class="bg-gray-100/50 p-1.5 rounded-2xl inline-flex w-full max-w-md">
                <button onclick="toggleRole('farmer')" id="reg-btn-farmer"
                    class="flex-1 py-3.5 px-4 text-sm font-black rounded-xl bg-white shadow-sm text-emerald-700 transition-all border-2 border-emerald-200 flex items-center justify-center gap-2">
                    <i class="fas fa-tractor text-emerald-500"></i><span data-lang="farmer">FARMER (ખેડૂત)</span>
                </button>
                <button onclick="toggleRole('buyer')" id="reg-btn-buyer"
                    class="flex-1 py-3.5 px-4 text-sm font-black rounded-xl text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all border-2 border-transparent flex items-center justify-center gap-2">
                    <i class="fas fa-store text-gray-400"></i><span data-lang="buyer">BUYER (વેપારી)</span>
                </button>
            </div>
        </div>

        <form class="mt-8 space-y-5 relative z-10" action="#" method="POST" id="registerForm" onsubmit="handleRegister(event)">
            <input type="hidden" name="role" id="reg-role-input" value="farmer">

            <!-- COMMON FIELDS -->
            <div class="space-y-5">
                <!-- 1. User ID -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                        <i class="fas fa-id-badge text-emerald-500"></i>User ID <span class="text-red-500">*</span>
                    </label>
                    <input id="user_id" name="user_id" type="text" required maxlength="12"
                        class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-3 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-base shadow-sm"
                        placeholder="JAY001 (Unique ID)">
                </div>

                <!-- 2. Full Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                        <i class="fas fa-user text-emerald-500"></i>Full Name <span class="text-red-500">*</span>
                    </label>
                    <input id="full_name" name="full_name" type="text" required
                        class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-3 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-base shadow-sm"
                        placeholder="Jaykumar Ramanbhai Patel">
                </div>

                <!-- 3. Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                        <i class="fas fa-envelope text-emerald-500"></i>Email
                    </label>
                    <input id="email" name="email" type="email"
                        class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-3 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-base shadow-sm"
                        placeholder="jay.patel@agricare.in">
                </div>

                <!-- 4. Phone Number -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                        <i class="fas fa-phone text-emerald-500"></i>Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input id="phone_no" name="phone_no" type="tel" required
                        class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-3 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-base shadow-sm"
                        placeholder="9876543210">
                </div>

                <!-- 5. Password -->
                <div class="relative">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                        <i class="fas fa-lock text-emerald-500"></i>Password <span class="text-red-500">*</span>
                    </label>
                    <input id="password" name="password" type="password" required minlength="8"
                        class="w-full pr-12 py-3.5 border-2 border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-3 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-base shadow-sm"
                        placeholder="Minimum 8 characters">
                    <button type="button" onclick="togglePassword()" class="absolute right-4 top-11 text-gray-400 hover:text-emerald-600 transition-all">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- FARMER SPECIFIC FIELDS -->
            <div id="farmer-fields" class="role-specific active space-y-5">
                <!-- 8. Pincode -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                            <i class="fas fa-map-pin text-emerald-500"></i>Pincode <span class="text-red-500">*</span>
                        </label>
                        <input id="pincode" name="pincode" type="text" required maxlength="6"
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-3 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-base shadow-sm"
                            placeholder="392001">
                    </div>
                </div>

                <!-- 11. City & 12. Village -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                            <i class="fas fa-city text-emerald-500"></i>City <span class="text-red-500">*</span>
                        </label>
                        <select id="district" name="district" required onchange="updateVillages()"
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-3 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-base shadow-sm bg-white">
                            <option value="">-- Select City --</option>
                            <option>Ahmedabad</option>
                            <option>Amreli</option>
                            <option>Anand</option>
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
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                            <i class="fas fa-home text-emerald-500"></i>Village <span class="text-red-500">*</span>
                        </label>
                        <select id="village" name="village" required
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-3 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all text-base shadow-sm bg-white">
                            <option value="">-- Select City First --</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- BUYER SPECIFIC FIELDS -->
            <div id="buyer-fields" class="role-specific space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                            <i class="fas fa-building text-orange-500"></i>Business Name <span class="text-red-500">*</span>
                        </label>
                        <input id="business_name" name="business_name" type="text" required
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-3 focus:ring-orange-500/30 focus:border-orange-500 transition-all text-base shadow-sm"
                            placeholder="Patel Agro Traders">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1 flex items-center gap-1">
                            <i class="fas fa-warehouse text-orange-500"></i>Storage Capacity
                        </label>
                        <input id="storage_capacity" name="storage_capacity" type="text"
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-3 focus:ring-orange-500/30 focus:border-orange-500 transition-all text-base shadow-sm"
                            placeholder="500 Tons">
                    </div>
                </div>
            </div>

            <!-- TERMS & CONDITIONS ✅ LINK FIXED HERE -->
            <div class="flex items-start pt-2">
                <div class="flex items-center h-5 mt-1">
                    <input id="terms" name="terms" type="checkbox" required
                        class="h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-2 border-gray-300 rounded-lg shadow-sm">
                </div>
                <div class="ml-4 text-sm">
                    <label for="terms" class="font-medium text-gray-700 select-none">
                        <span data-lang="terms">I agree to the</span>
                        <a href="terms.html" target="_blank"
                            class="text-emerald-600 hover:text-emerald-700 font-bold underline decoration-2 underline-offset-2 transition-all hover:underline-offset-4 hover:shadow-emerald-200">
                            Terms and Conditions
                        </a>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" id="submit-btn"
                    class="group relative w-full flex items-center justify-center gap-3 py-4 px-6 border border-transparent text-base font-black rounded-2xl text-white bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 shadow-xl hover:shadow-emerald-500/50 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <i class="fas fa-user-plus"></i>
                    <span data-lang="createAccount">CREATE ACCOUNT</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        let currentRole = 'farmer';
        let passwordVisible = false;

        // Language
        const translations = {
            en: {
                logo: 'AgriCare',
                title: 'Join AgriCare',
                subtitle: 'Create your account to access live mandi prices, crop recommendations & more',
                haveAccount: 'Already have an account?',
                farmer: 'FARMER (ખેડૂત)',
                buyer: 'BUYER (વેપારી)',
                terms: 'I agree to the',
                createAccount: 'CREATE ACCOUNT'
            },
            gu: {
                logo: 'એગ્રીસ્માર્ટ',
                title: 'એગ્રીસ્માર્ટ જોડાઓ',
                subtitle: 'લાઈવ મંડી ભાવ, પાક સલાહ મેળવો',
                haveAccount: 'ખાતું છે?',
                farmer: 'ખેડૂત',
                buyer: 'વેપારી',
                terms: 'મંજૂર છે',
                createAccount: 'ખાતું બનાવો'
            },
            hi: {
                logo: 'एग्रीस्मार्ट',
                title: 'एग्रीस्मार्ट जॉइन करें',
                subtitle: 'लाइव मंडी दाम, फसल सलाह प्राप्त करें',
                haveAccount: 'खाता है?',
                farmer: 'किसान',
                buyer: 'व्यापारी',
                terms: 'सहमत हूं',
                createAccount: 'खाता बनाएं'
            }
        };

        function changeLang(lang) {
            localStorage.setItem('agricare_lang', lang);
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('bg-emerald-500', 'text-white');
                btn.classList.add('hover:bg-emerald-100');
            });
            event.target.classList.add('bg-emerald-500', 'text-white');
            event.target.classList.remove('hover:bg-emerald-100');

            document.querySelectorAll('[data-lang]').forEach(el => {
                const key = el.getAttribute('data-lang');
                if (translations[lang][key]) el.textContent = translations[lang][key];
            });
            document.body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') document.body.classList.add('gu-text');
            if (lang === 'hi') document.body.classList.add('hi-text');
        }

        // City → Village map (major talukas/villages per Gujarat district)
        const cityVillageMap = {
            'Ahmedabad': ['Ahmedabad City', 'Dholka', 'Dhandhuka', 'Sanand', 'Viramgam', 'Bavla', 'Mandal', 'Detroj-Rampura'],
            'Amreli': ['Amreli', 'Rajula', 'Savarkundla', 'Lathi', 'Bagasara', 'Jafrabad', 'Khambha', 'Dhari'],
            'Anand': ['Anand', 'Vallabh Vidyanagar', 'Nadiad', 'Petlad', 'Borsad', 'Khambhat', 'Sojitra', 'Umreth'],
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
            'Sabarkantha': ['Himmatnagar', 'Idar', 'Modasa', 'Bayad', 'Talod', 'Prantij', 'Khedbrahma'],
            'Surat': ['Surat City', 'Bardoli', 'Olpad', 'Mandvi', 'Mahuva', 'Kamrej', 'Mangrol', 'Palsana'],
            'Surendranagar': ['Surendranagar', 'Wadhwan', 'Chotila', 'Dhrangadhra', 'Halvad', 'Limbdi', 'Muli'],
            'Tapi': ['Vyara', 'Surat', 'Nizar', 'Ukai', 'Dolvan', 'Songadh', 'Valod'],
            'Vadodara': ['Vadodara', 'Anklav', 'Dabhoi', 'Karjan', 'Padra', 'Waghodia', 'Savli', 'Shinor'],
            'Valsad': ['Valsad', 'Vapi', 'Pardi', 'Umbergaon', 'Dharampur', 'Kaprada']
        };

        function updateVillages() {
            const city = document.getElementById('district').value;
            const villageSelect = document.getElementById('village');
            villageSelect.innerHTML = '<option value="">-- Select Village --</option>';
            if (city && cityVillageMap[city]) {
                cityVillageMap[city].forEach(v => {
                    const opt = document.createElement('option');
                    opt.value = v;
                    opt.textContent = v;
                    villageSelect.appendChild(opt);
                });
            }
        }

        // Role Toggle
        function toggleRole(role) {
            currentRole = role;
            document.getElementById('reg-role-input').value = role;

            ['farmer', 'buyer'].forEach(r => {
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
        }

        // Password Toggle
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = event.target.querySelector('i');
            passwordVisible = !passwordVisible;
            pwd.type = passwordVisible ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !passwordVisible);
            icon.classList.toggle('fa-eye-slash', passwordVisible);
        }

        // Form Submit
        function handleRegister(e) {
            e.preventDefault();

            const formData = {
                user_id: document.getElementById('user_id').value,
                full_name: document.getElementById('full_name').value,
                email: document.getElementById('email').value,
                phone_no: document.getElementById('phone_no').value,
                password: document.getElementById('password').value,
                role: currentRole,
                pincode: document.getElementById('pincode')?.value || '',
                district: document.getElementById('district')?.value || '',
                village: document.getElementById('village')?.value || '',
                business_name: document.getElementById('business_name')?.value || '',
                storage_capacity: document.getElementById('storage_capacity')?.value || ''
            };

            localStorage.setItem('userRegistered', JSON.stringify(formData));

            const btn = document.getElementById('submit-btn');
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';
            btn.disabled = true;

            setTimeout(() => {
                alert(`✅ Account created successfully!\nWelcome ${formData.full_name} (${formData.role.toUpperCase()})`);
                window.location.href = 'login.php';
            }, 1500);
        }

        const urlParams = new URLSearchParams(window.location.search);
        const initialLang = urlParams.get('lang') || localStorage.getItem('agricare_lang') || 'en';
        changeLang(initialLang);
    </script>
</body>

</html>