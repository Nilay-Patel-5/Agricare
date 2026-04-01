<?php
require_once __DIR__ . '/../backend/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Profile | AgriCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/output.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&family=Noto+Sans+Gujarati:wght@400;500;700&family=Noto+Sans+Devanagari:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
        .gu-text { font-family: 'Noto Sans Gujarati', sans-serif; }
        .hi-text { font-family: 'Noto Sans Devanagari', sans-serif; }
        .sidebar-link { transition: all 0.3s; }
        .sidebar-link.active { background-color: #ecfdf5; color: #047857; border-right: 4px solid #10b981; font-weight: bold; }
        .sidebar-link:hover:not(.active) { background-color: #f3f4f6; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); }
    </style>
</head>

<body class="flex h-screen overflow-hidden">
    <div id="progressBar"></div>

    <!-- Sidebar (Same as farmer.php) -->
    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col hidden lg:flex">
        <div class="p-6 border-b border-gray-100">
            <a href="../frontend/index.php" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-emerald-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg">
                    <i class="fas fa-wheat-awn"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-gray-800 tracking-tight block" data-lang="appName">AgriCare</span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest" data-lang="farmerPortal">Farmer Portal</span>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-4" id="sidebarNav">
            <div class="px-6 mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2" data-lang="menu">Menu</p>
            </div>
            <a href="farmer.php" class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-600">
                <i class="fas fa-calendar-alt w-5 text-center"></i>
                <span data-lang="cropCalendar">Crop Calendar</span>
            </a>
            <a href="farmer.php" class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-600">
                <i class="fas fa-microscope w-5 text-center"></i>
                <span data-lang="disease">Disease Detector</span>
            </a>
            <a href="farmer.php" class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-600">
                <i class="fas fa-hand-holding-dollar w-5 text-center"></i>
                <span data-lang="subsidies">Government Subsidies</span>
            </a>
            <a href="profile.php" class="sidebar-link active flex items-center gap-3 px-6 py-3 text-gray-600">
                <i class="fas fa-user-circle w-5 text-center"></i>
                <span data-lang="profile">My Profile</span>
            </a>
        </nav>

        <div class="p-6 border-t border-gray-100">
            <a href="#" onclick="logout()" class="flex items-center gap-3 text-gray-500 hover:text-red-600 transition-colors font-bold text-sm">
                <i class="fas fa-sign-out-alt"></i>
                <span data-lang="logout">Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
        <!-- Topbar -->
        <header class="bg-white border-b border-gray-100 py-4 px-6 lg:px-10 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <button class="lg:hidden text-gray-500 hover:text-emerald-600" onclick="toggleSidebar()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-2xl font-black text-gray-800" data-lang="profile">My Profile</h2>
            </div>

            <div class="flex items-center gap-4">
                <p id="userNameDisplay" class="text-sm font-bold text-gray-800">Farmer User</p>
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold border-2 border-white shadow-sm">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </header>

        <!-- Profile Content -->
        <div class="flex-1 overflow-y-auto px-6 lg:px-10 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <h2 class="text-3xl font-black text-gray-900 mb-2" data-lang="profileHeader">Profile Settings</h2>
                    <p class="text-gray-600 font-medium" data-lang="profileSubheader">Manage your account information and preferences.</p>
                </div>

                <!-- Display Mode -->
                <div id="view-mode" class="glass-card rounded-[2.5rem] shadow-xl overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-32 relative">
                        <div class="absolute -bottom-12 left-10">
                            <div class="w-24 h-24 bg-white rounded-3xl shadow-xl flex items-center justify-center text-emerald-600 text-4xl font-black">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="pt-16 pb-8 px-10">
                        <div class="flex justify-between items-start mb-10">
                            <div>
                                <h3 id="display-name" class="text-3xl font-black text-gray-900 mb-1">Loading...</h3>
                                <p id="display-role" class="text-sm font-bold text-emerald-600 uppercase tracking-widest" data-lang="farmerPortal">Farmer Portal</p>
                            </div>
                            <button onclick="toggleMode('edit')" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2.5 rounded-2xl font-bold text-sm shadow-lg shadow-emerald-200 transition-all active:scale-95 flex items-center gap-2">
                                <i class="fas fa-edit"></i> <span data-lang="editProfile">Edit Profile</span>
                            </button>
                        </div>

                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" data-lang="phoneNumber">Phone Number</p>
                                    <p id="display-phone" class="text-gray-800 font-bold">--</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" data-lang="emailAddress">Email Address</p>
                                    <p id="display-email" class="text-gray-800 font-bold">--</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" data-lang="preferredLang">Preferred Language</p>
                                    <p id="display-lang" class="text-gray-800 font-bold">--</p>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" data-lang="district">District</p>
                                    <p id="display-district" class="text-gray-800 font-bold">--</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" data-lang="city">City / Village</p>
                                    <p id="display-city" class="text-gray-800 font-bold">--</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" data-lang="pincode">Pincode</p>
                                    <p id="display-pincode" class="text-gray-800 font-bold">--</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Mode -->
                <div id="edit-mode" class="hidden glass-card rounded-[2.5rem] shadow-xl overflow-hidden mb-8">
                    <form id="profile-form" onsubmit="handleUpdate(event)" class="p-10">
                        <h3 class="text-2xl font-black text-gray-900 mb-8 flex items-center gap-3">
                            <i class="fas fa-sliders text-emerald-500"></i> <span data-lang="editProfile">Edit Profile</span>
                        </h3>

                        <div class="grid md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1" data-lang="fullName">Full Name</label>
                                <input type="text" name="name" id="input-name" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1" data-lang="emailAddress">Email Address</label>
                                <input type="email" name="email" id="input-email" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1" data-lang="phoneNumber">Phone Number</label>
                                <input type="tel" name="phone" id="input-phone" pattern="\d{10}" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1" data-lang="preferredLang">Preferred Language</label>
                                <select name="pref_lang" id="input-lang" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800 appearance-none">
                                    <option value="en">English</option>
                                    <option value="gu">ગુજરાતી (Gujarati)</option>
                                    <option value="hi">हिन्दी (Hindi)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1" data-lang="district">District</label>
                                <select name="district" id="input-district" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800 search-select">
                                    <!-- Populated by JS -->
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1" data-lang="city">City / Village</label>
                                <input type="text" name="city" id="input-city" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1" data-lang="pincode">Pincode</label>
                                <input type="text" name="pincode" id="input-pincode" pattern="\d{6}" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1" data-lang="changePin">Change PIN (Leave blank to keep current)</label>
                                <input type="password" name="pin" id="input-pin" pattern="\d{6}" maxlength="6" placeholder="••••••" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800">
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" id="save-btn" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-emerald-200 transition-all active:scale-95 flex items-center justify-center gap-3">
                                <i class="fas fa-check-circle"></i> <span data-lang="saveChanges">Save Changes</span>
                            </button>
                            <button type="button" onclick="toggleMode('view')" class="px-8 bg-gray-100 hover:bg-gray-200 text-gray-500 font-bold rounded-2xl transition-all" data-lang="cancel">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Success Toast -->
    <div id="toast" class="fixed bottom-10 right-10 z-50 transform translate-y-20 opacity-0 transition-all duration-500">
        <div class="bg-emerald-800 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-4">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <p class="font-black text-sm" data-lang="success">Update Successful!</p>
                <p class="text-[10px] opacity-80" data-lang="profileUpdated">Your profile has been saved.</p>
            </div>
        </div>
    </div>

    <script>
        const translations = {
            en: {
                appName: 'AgriCare',
                farmerPortal: 'Farmer Portal',
                menu: 'Menu',
                cropCalendar: 'Crop Calendar',
                disease: 'Disease Detector',
                subsidies: 'Govt Subsidies',
                profile: 'My Profile',
                logout: 'Logout',
                profileHeader: 'Profile Settings',
                profileSubheader: 'Manage your account information and preferences.',
                editProfile: 'Edit Profile',
                fullName: 'Full Name',
                emailAddress: 'Email Address',
                phoneNumber: 'Phone Number',
                preferredLang: 'Preferred Language',
                district: 'District',
                city: 'City / Village',
                pincode: 'Pincode',
                changePin: 'Change PIN',
                saveChanges: 'Save Changes',
                cancel: 'Cancel',
                success: 'Update Successful!',
                profileUpdated: 'Your profile has been saved.',
                error: 'Error'
            },
            gu: {
                appName: 'એગ્રીકેર',
                farmerPortal: 'ખેડૂત પોર્ટલ',
                menu: 'મેનુ',
                cropCalendar: 'પાક કેલેન્ડર',
                disease: 'રોગ નિદાન',
                subsidies: 'સરકારી સબસિડી',
                profile: 'મારી પ્રોફાઇલ',
                logout: 'લોગઆઉટ',
                profileHeader: 'પ્રોફાઇલ સેટિંગ્સ',
                profileSubheader: 'તમારી એકાઉન્ટ માહિતી અને પસંદગીઓ મેનેજ કરો.',
                editProfile: 'પ્રોફાઇલ સંપાદિત કરો',
                fullName: 'પૂરું નામ',
                emailAddress: 'ઈમેલ એડ્રેસ',
                phoneNumber: 'મોબાઈલ નંબર',
                preferredLang: 'પસંદગીની ભાષા',
                district: 'જિલ્લો',
                city: 'શહેર / ગામ',
                pincode: 'પિનકોડ',
                changePin: 'પિન બદલો',
                saveChanges: 'ફેરફારો સાચવો',
                cancel: 'રદ કરો',
                success: 'અપડેટ સફળ!',
                profileUpdated: 'તમારી પ્રોફાઇલ સાચવવામાં આવી છે.',
                error: 'ભૂલ'
            },
            hi: {
                appName: 'एग्रीकेयर',
                farmerPortal: 'किसान पोर्टल',
                menu: 'मेनू',
                cropCalendar: 'फसल कैलेंडर',
                disease: 'रोग पहचान',
                subsidies: 'सरकारी सब्सिडी',
                profile: 'मेरी प्रोफाइल',
                logout: 'लॉगआउट',
                profileHeader: 'प्रोफ़ाइल सेटिंग्स',
                profileSubheader: 'अपनी खाता जानकारी और प्राथमिकताएं प्रबंधित करें।',
                editProfile: 'प्रोफ़ाइल संपादित करें',
                fullName: 'पूरा नाम',
                emailAddress: 'ईमेल पता',
                phoneNumber: 'फ़ोन नंबर',
                preferredLang: 'पसंदीदा भाषा',
                district: 'ज़िला',
                city: 'शहर / गांव',
                pincode: 'पिनकोड',
                changePin: 'पिन बदलें',
                saveChanges: 'परिवर्तन सहेजें',
                cancel: 'रद्द करें',
                success: 'अपडेट सफल!',
                profileUpdated: 'आपकी प्रोफ़ाइल सहेजी गई है।',
                error: 'त्रुटि'
            }
        };

        const districts = [
            'Ahmedabad', 'Amreli', 'Anand', 'Arvalli', 'Banaskantha', 'Bharuch', 'Bhavnagar',
            'Botad', 'Chhota Udaipur', 'Dahod', 'Dang', 'Devbhoomi Dwarka', 'Gandhinagar',
            'Gir Somnath', 'Jamnagar', 'Junagadh', 'Kheda', 'Kutch', 'Mahisagar', 'Mehsana',
            'Morbi', 'Narmada', 'Navsari', 'Panchmahal', 'Patan', 'Porbandar', 'Rajkot',
            'Sabarkantha', 'Surat', 'Surendranagar', 'Tapi', 'Vadodara', 'Valsad'
        ];

        let currentLang = localStorage.getItem('agricare_lang') || 'en';
        let userProfile = null;

        function applyTranslations() {
            document.querySelectorAll('[data-lang]').forEach(el => {
                const key = el.getAttribute('data-lang');
                if (translations[currentLang][key]) el.innerText = translations[currentLang][key];
            });
            document.body.className = `flex h-screen overflow-hidden ${currentLang === 'gu' ? 'gu-text' : (currentLang === 'hi' ? 'hi-text' : '')}`;
        }

        function toggleMode(mode) {
            document.getElementById('view-mode').classList.toggle('hidden', mode === 'edit');
            document.getElementById('edit-mode').classList.toggle('hidden', mode === 'view');
            if (mode === 'edit') fillForm();
        }

        async function fetchProfile() {
            const userData = JSON.parse(sessionStorage.getItem('agricare_user') || localStorage.getItem('agricare_user') || '{}');
            if (!userData.id) {
                window.location.href = '../frontend/login.php';
                return;
            }

            try {
                const res = await fetch(`../backend/profile_api.php?id=${userData.id}`);
                const data = await res.json();
                if (data.success) {
                    userProfile = data.user;
                    updateUI();
                }
            } catch (err) {
                console.error("Fetch profile failed", err);
            }
        }

        function updateUI() {
            document.getElementById('display-name').innerText = userProfile.name;
            document.getElementById('display-phone').innerText = userProfile.phone;
            document.getElementById('display-email').innerText = userProfile.email || '--';
            document.getElementById('display-district').innerText = userProfile.district;
            document.getElementById('display-city').innerText = userProfile.city;
            document.getElementById('display-pincode').innerText = userProfile.pincode;
            document.getElementById('display-lang').innerText = userProfile.pref_lang === 'gu' ? 'ગુજરાતી' : (userProfile.pref_lang === 'hi' ? 'हिन्दी' : 'English');
            
            document.getElementById('userNameDisplay').innerText = userProfile.name;
        }

        function fillForm() {
            document.getElementById('input-name').value = userProfile.name;
            document.getElementById('input-email').value = userProfile.email;
            document.getElementById('input-phone').value = userProfile.phone;
            document.getElementById('input-lang').value = userProfile.pref_lang;
            document.getElementById('input-district').value = userProfile.district;
            document.getElementById('input-city').value = userProfile.city;
            document.getElementById('input-pincode').value = userProfile.pincode;
        }

        async function handleUpdate(e) {
            e.preventDefault();
            const btn = document.getElementById('save-btn');
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            const formData = new FormData(e.target);
            const payload = Object.fromEntries(formData.entries());
            payload.id = userProfile.id;

            try {
                const res = await fetch('../backend/profile_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.success) {
                    userProfile = { ...userProfile, ...payload };
                    if (payload.pin) delete userProfile.pin; // Clean up local copy
                    
                    // Sync with storage
                    const userData = JSON.parse(localStorage.getItem('agricare_user') || '{}');
                    userData.name = payload.name;
                    userData.pref_lang = payload.pref_lang;
                    localStorage.setItem('agricare_user', JSON.stringify(userData));
                    sessionStorage.setItem('agricare_user', JSON.stringify(userData));
                    
                    currentLang = payload.pref_lang;
                    localStorage.setItem('agricare_lang', currentLang);
                    
                    updateUI();
                    applyTranslations();
                    toggleMode('view');
                    showToast();
                } else {
                    alert(data.message);
                }
            } catch (err) {
                alert("Update failed. Please check your connection.");
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        }

        function showToast() {
            const toast = document.getElementById('toast');
            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }

        function logout() {
            sessionStorage.clear();
            localStorage.removeItem('agricare_user');
            window.location.href = '../frontend/login.php';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            // Populate districts
            const distSelect = document.getElementById('input-district');
            districts.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d;
                opt.textContent = d;
                distSelect.appendChild(opt);
            });

            applyTranslations();
            fetchProfile();
        });

        // Universal Scroll Progress
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
            }
        }, true);
    </script>
</body>
</html>
