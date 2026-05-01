<?php
require_once __DIR__ . '/../backend/demo_admin_data.php';
$dashboardSubsidyFallback = admin_demo_subsidies();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Farmer Dashboard | AgriCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/output.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&family=Noto+Sans+Gujarati:wght@400;500;700&family=Noto+Sans+Devanagari:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../frontend/js/translations.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
        }

        .gu-text {
            font-family: 'Noto Sans Gujarati', sans-serif;
        }

        .hi-text {
            font-family: 'Noto Sans Devanagari', sans-serif;
        }

        .sidebar-link {
            transition: all 0.3s;
        }

        .sidebar-link.active {
            background-color: #ecfdf5;
            color: #047857;
            border-right: 4px solid #10b981;
            font-weight: bold;
        }

        .sidebar-link:hover:not(.active) {
            background-color: #f3f4f6;
        }

        .current-month-indicator {
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
            border: 2px solid #10b981;
        }


        .timeline-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .process-card {
            position: relative;
            padding-left: 2rem;
            border-left: 2px solid #e5e7eb;
            margin-left: 1rem;
            padding-bottom: 2rem;
        }

        .process-card:last-child {
            border-left: 2px solid transparent;
            padding-bottom: 0;
        }

        .process-dot {
            position: absolute;
            left: -0.55rem;
            top: 0;
            width: 1rem;
            height: 1rem;
            background: white;
            border: 3px solid #10b981;
            border-radius: 9999px;
            z-index: 10;
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden">
    <div id="progressBar"></div>

    <!-- Sidebar -->
    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col hidden lg:flex">
        <div class="p-6 border-b border-gray-100">
            <a href="../frontend/index.php" class="flex items-center gap-3 group">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-emerald-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg">
                    <i class="fas fa-wheat-awn"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-gray-800 tracking-tight block" data-lang="appName">AgriCare</span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest"
                        data-lang="farmerPortal">Farmer Portal</span>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-4" id="sidebarNav">
            <div class="px-6 mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2" data-lang="menu">Menu</p>
            </div>

            <a href="#" onclick="switchModule('calendar', this)"
                class="sidebar-link active flex items-center gap-3 px-6 py-3 text-gray-600">
                <i class="fas fa-calendar-alt w-5 text-center"></i>
                <span data-lang="cropCalendar">Crop Calendar</span>
            </a>
            <a href="#" onclick="switchModule('disease', this)"
                class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-600">
                <i class="fas fa-microscope w-5 text-center"></i>
                <span data-lang="disease">Disease Detector</span>
            </a>
            <a href="#" onclick="switchModule('subsidies', this)"
                class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-600">
                <i class="fas fa-hand-holding-dollar w-5 text-center"></i>
                <span data-lang="subsidies">Government Subsidies</span>
            </a>
            <a href="#" onclick="switchModule('feedback', this)"
                class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-600">
                <i class="fas fa-comment-dots w-5 text-center"></i>
                <span data-lang="feedback_nav">Feedback</span>
            </a>
            <a href="#" onclick="switchModule('profile', this)" class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-600">
                <i class="fas fa-user-circle w-5 text-center"></i>
                <span data-lang="profile">My Profile</span>
            </a>
        </nav>

        <div class="p-6 border-t border-gray-100">
            <a href="#" onclick="logout()"
                class="flex items-center gap-3 text-gray-500 hover:text-red-600 transition-colors font-bold text-sm">
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
                <button class="lg:hidden text-gray-500 hover:text-emerald-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-2xl font-black text-gray-800 hidden sm:block" data-lang="cropCalendar">Crop Calendar
                </h2>
            </div>

            <a href="#" onclick="switchModule('profile')" class="flex items-center gap-4 group hover:bg-emerald-50 py-1.5 px-3 rounded-2xl transition-all border border-transparent hover:border-emerald-100">
                <p id="userNameDisplay" class="text-sm font-bold text-gray-800 group-hover:text-emerald-700">Farmer User</p>
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold border-2 border-white shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-user"></i>
                </div>
            </a>
        </header>

        <!-- Scrollable Dashboard Content -->
        <!-- Module: Crop Calendar -->
        <div id="module-calendar" class="module-view flex-1 overflow-y-auto px-6 lg:px-10 py-8">
            <div class="mb-8">
                <h2 class="text-3xl font-black text-gray-900 mb-2" data-lang="headerTitle">Seasonal Activity Plan</h2>
                <p class="text-gray-600 font-medium max-w-2xl" data-lang="headerDesc">
                    Select your crop to view detailed month-by-month guidance for sowing, irrigation, fertilizer
                    management, and harvesting.
                </p>
            </div>

            <div class="grid lg:grid-cols-4 gap-8">
                <!-- Sidebar: Crop Selection -->
                <div class="lg:col-span-1 pr-0 lg:pr-4">
                    <h3 class="font-bold text-gray-400 uppercase tracking-widest text-sm mb-4" data-lang="selectCrop">
                        Select Crop</h3>
                    <div class="space-y-3" id="cropSelector">
                        <!-- Javascript will populate buttons here -->
                    </div>
                </div>

                <!-- Main Content: The Calendar -->
                <div class="lg:col-span-3">
                <div
                    class="flex justify-between items-center mb-6 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div>
                        <h2 class="text-2xl font-black text-emerald-800 tracking-tight" id="activeCropName">
                            Loading...</h2>
                        <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-widest"
                            id="activeCropSeason">Season</p>
                    </div>
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-2xl shrink-0"
                        id="activeCropIcon">
                        🌾
                    </div>
                </div>

                <div class="space-y-4" id="calendarGrid">
                    <!-- Timeline will be populated here -->
                </div>
            </div>
        </div>
    </div>

        <!-- Module: Disease Detector -->
        <div id="module-disease" class="module-view hidden flex-1 overflow-y-auto px-6 lg:px-10 py-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 mb-2" data-lang="diseaseTitle">Smart Disease Detection
                    </h2>
                    <p class="text-gray-600 font-medium max-w-2xl" data-lang="disease_subtitle">Identify plant diseases
                        instantly using our advanced AI model.</p>
                </div>
                <!-- Local AI Status Marker -->
                <div id="api-status"
                    class="bg-white px-4 py-2 rounded-xl flex items-center gap-3 border border-gray-100 shadow-sm">
                    <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse" id="status-pulse"></div>
                    <p id="status-label" class="text-[10px] font-black uppercase tracking-widest text-emerald-600">AI
                        Online</p>
                </div>
            </div>

            <div class="grid lg:grid-cols-12 gap-8 h-full">
                <!-- Upload Section -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 h-full flex flex-col">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-camera text-emerald-500"></i> <span data-lang="upload_ready">Ready to
                                Scan?</span>
                        </h3>

                        <!-- Drop Zone -->
                        <div id="drop-zone"
                            class="flex-1 border-4 border-dashed border-gray-100 rounded-[2rem] flex flex-col items-center justify-center p-8 cursor-pointer hover:border-emerald-200 hover:bg-emerald-50/30 transition-all text-center group">
                            <input type="file" id="file-input" class="hidden" accept="image/*">
                            <i
                                class="fas fa-cloud-upload-alt text-4xl text-gray-200 group-hover:text-emerald-300 mb-4 transition-colors"></i>
                            <p class="text-gray-400 font-bold text-sm" data-lang="drag_drop">Drag leaf photo here or
                                click to browse</p>
                        </div>

                        <!-- Image Preview -->
                        <div id="preview-wrapper" class="hidden flex-1">
                            <div class="relative rounded-2xl overflow-hidden h-64 shadow-inner mb-4">
                                <img id="image-preview" src="" alt="Preview" class="w-full h-full object-cover">
                                <button onclick="resetScan()"
                                    class="absolute top-3 right-3 w-10 h-10 bg-red-500 text-white rounded-xl shadow-lg hover:bg-red-600 transition-colors flex items-center justify-center">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button id="analyze-btn" disabled
                            class="w-full mt-6 bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 text-white font-bold py-4 rounded-xl shadow-lg disabled:opacity-50 disabled:grayscale transition-all flex items-center justify-center gap-3">
                            <i class="fas fa-bolt"></i>
                            <span id="btn-text" data-lang="btn_analyze">Analyze Health</span>
                        </button>
                    </div>
                </div>

                <!-- Result Area -->
                <div class="lg:col-span-7">
                    <div id="empty-state"
                        class="bg-emerald-50/30 h-full min-h-[400px] rounded-[2rem] border-4 border-dotted border-emerald-100 flex flex-col items-center justify-center text-center p-8">
                        <i class="fas fa-qrcode text-6xl text-emerald-100 mb-6"></i>
                        <h3 class="text-xl font-black text-emerald-200" data-lang="awaiting_scan">Awaiting Prediction
                            Data</h3>
                    </div>

                    <div id="result-panel" class="hidden space-y-6">
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                            <div class="flex justify-between items-start mb-6">
                                <h3 class="text-2xl font-black text-gray-900" data-lang="diagnosis_report">Diagnosis
                                    Report</h3>
                                <div id="confidence-pill"
                                    class="px-4 py-1.5 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-lg border border-emerald-100">
                                    0% CONFIDENCE
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row gap-6 mb-8">
                                <div class="md:w-1/3 h-48 rounded-2xl overflow-hidden shadow-md">
                                    <img id="result-image-preview" src="" class="w-full h-full object-cover">
                                </div>
                                <div class="md:w-2/3 flex flex-col justify-center">
                                    <span id="plant-type"
                                        class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em]">SPECIES</span>
                                    <h4 id="disease-name" class="text-3xl font-black text-gray-800 leading-tight mb-2">
                                        Leaf Spot</h4>
                                    <p id="disease-desc"
                                        class="text-sm text-gray-500 font-medium leading-relaxed italic line-clamp-3">
                                        Loading descriptive health metrics...</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100">
                                    <div class="flex items-center gap-3 mb-3">
                                        <i class="fas fa-tint text-blue-500"></i>
                                        <span class="text-[10px] font-black text-blue-700 uppercase"
                                            data-lang="irrigation">Watering</span>
                                    </div>
                                    <p id="irrigation-text" class="text-xs text-gray-600 font-medium">Updating advice...
                                    </p>
                                </div>
                                <div class="p-5 bg-orange-50/50 rounded-2xl border border-orange-100">
                                    <div class="flex items-center gap-3 mb-3">
                                        <i class="fas fa-medkit text-orange-500"></i>
                                        <span class="text-[10px] font-black text-orange-700 uppercase"
                                            data-lang="treatment">Treatment</span>
                                    </div>
                                    <p id="treatment-text" class="text-xs text-gray-600 font-medium">Updating advice...
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Confidence Bar -->
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                            <div class="flex justify-between items-end mb-3 px-2">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest"
                                    data-lang="model_confidence">AI Probability</span>
                                <span id="confidence-text" class="text-xl font-black text-emerald-600">0%</span>
                            </div>
                            <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div id="confidence-bar" class="h-full bg-emerald-500 transition-all duration-1000 w-0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- --- SUBSIIDY MODULE --- -->
        <div id="module-subsidies" class="module-view hidden flex-1 overflow-y-auto px-6 lg:px-10 py-8">
            <div class="mb-10 text-center">
                <h3 class="text-4xl font-black text-gray-900 mb-4" data-lang="subsidies">Government Subsidies</h3>
                <p class="text-lg text-gray-600" data-lang="subsidy_hero_desc">Explore available financial aids and
                    schemes for your agriculture needs.</p>
            </div>

            <!-- Filters -->
            <div class="space-y-6 mb-8">
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterSubsidies('All')" data-cat="All" class="subsidy-tab bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-md transition-all active:scale-95" data-lang="cat_all">All Schemes</button>
                    <button onclick="filterSubsidies('Irrigation')" data-cat="Irrigation" class="subsidy-tab bg-white text-gray-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all active:scale-95 border border-gray-100" data-lang="cat_irrigation">Irrigation</button>
                    <button onclick="filterSubsidies('Equipment')" data-cat="Equipment" class="subsidy-tab bg-white text-gray-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all active:scale-95 border border-gray-100" data-lang="cat_equipment">Equipment</button>
                    <button onclick="filterSubsidies('Training')" data-cat="Training" class="subsidy-tab bg-white text-gray-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all active:scale-95 border border-gray-100" data-lang="cat_training">Training</button>
                    <button onclick="filterSubsidies('Seed & Fertilizer')" data-cat="Seed & Fertilizer" class="subsidy-tab bg-white text-gray-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all active:scale-95 border border-gray-100" data-lang="cat_seeds">Seed & Fertilizer</button>
                    <button onclick="filterSubsidies('Financial')" data-cat="Financial" class="subsidy-tab bg-white text-gray-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all active:scale-95 border border-gray-100" data-lang="cat_financial">Financial</button>
                    <button onclick="filterSubsidies('Insurance')" data-cat="Insurance" class="subsidy-tab bg-white text-gray-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all active:scale-95 border border-gray-100" data-lang="cat_insurance">Insurance</button>
                </div>

                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="subsidySearch" onkeyup="searchSubsidies(this.value)"
                            placeholder="Search schemes..."
                            class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none">
                    </div>
                </div>
            </div>

            <div id="subsidiesList" class="grid lg:grid-cols-2 gap-6 pb-12">
                <div class="col-span-full py-20 text-center">
                    <i class="fas fa-spinner fa-spin text-3xl text-emerald-600 mb-4"></i>
                    <p class="font-bold text-gray-500">Retrieving schemes...</p>
                </div>
            </div>
        </div>

        <!-- Module: Feedback -->
        <div id="module-feedback" class="module-view hidden flex-1 overflow-y-auto px-6 lg:px-10 py-8">
            <div class="max-w-2xl mx-auto">
                <div class="mb-10 text-center">
                    <h3 class="text-4xl font-black text-gray-900 mb-4" data-lang="feedback_title">Send Feedback</h3>
                    <p class="text-lg text-gray-600" data-lang="feedback_subtitle">Your feedback helps us improve AgriCare for all farmers.</p>
                </div>

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <form id="feedbackForm" onsubmit="submitFeedback(event)" class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" data-lang="feedback_subject_label">Subject</label>
                            <input type="text" id="feedbackSubject" required
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all"
                                data-placeholder="feedback_subject_ph"
                                placeholder="e.g., App Improvement">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" data-lang="feedback_rating_label">Rating</label>
                            <div class="flex flex-wrap gap-4">
                                <input type="radio" id="rating5" name="rating" value="5" class="hidden" checked>
                                <input type="radio" id="rating4" name="rating" value="4" class="hidden">
                                <input type="radio" id="rating3" name="rating" value="3" class="hidden">
                                <input type="radio" id="rating2" name="rating" value="2" class="hidden">
                                <input type="radio" id="rating1" name="rating" value="1" class="hidden">

                                <div class="flex flex-col items-center gap-1 group cursor-pointer" onclick="selectRating(5)">
                                    <div id="star-box-5" class="rating-star-box w-12 h-12 flex items-center justify-center rounded-xl bg-emerald-500 text-white transition-all shadow-sm">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="rating-label text-[9px] font-black text-gray-400 uppercase tracking-tighter" data-lang="rating_5">Excellent</span>
                                </div>
                                <div class="flex flex-col items-center gap-1 group cursor-pointer" onclick="selectRating(4)">
                                    <div id="star-box-4" class="rating-star-box w-12 h-12 flex items-center justify-center rounded-xl bg-gray-50 text-gray-300 transition-all shadow-sm">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="rating-label text-[9px] font-black text-gray-400 uppercase tracking-tighter" data-lang="rating_4">Good</span>
                                </div>
                                <div class="flex flex-col items-center gap-1 group cursor-pointer" onclick="selectRating(3)">
                                    <div id="star-box-3" class="rating-star-box w-12 h-12 flex items-center justify-center rounded-xl bg-gray-50 text-gray-300 transition-all shadow-sm">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="rating-label text-[9px] font-black text-gray-400 uppercase tracking-tighter" data-lang="rating_3">Average</span>
                                </div>
                                <div class="flex flex-col items-center gap-1 group cursor-pointer" onclick="selectRating(2)">
                                    <div id="star-box-2" class="rating-star-box w-12 h-12 flex items-center justify-center rounded-xl bg-gray-50 text-gray-300 transition-all shadow-sm">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="rating-label text-[9px] font-black text-gray-400 uppercase tracking-tighter" data-lang="rating_2">Poor</span>
                                </div>
                                <div class="flex flex-col items-center gap-1 group cursor-pointer" onclick="selectRating(1)">
                                    <div id="star-box-1" class="rating-star-box w-12 h-12 flex items-center justify-center rounded-xl bg-gray-50 text-gray-300 transition-all shadow-sm">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="rating-label text-[9px] font-black text-gray-400 uppercase tracking-tighter" data-lang="rating_1">Bad</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" data-lang="feedback_message_label">Your Message</label>
                            <textarea id="feedbackMessage" rows="5" required
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all"
                                data-placeholder="feedback_message_ph"
                                placeholder="Describe your experience or suggest features..."></textarea>
                        </div>
                        <button type="submit" id="feedbackSubmitBtn"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg transition-all active:scale-95 flex items-center justify-center gap-3">
                            <i class="fas fa-paper-plane"></i>
                            <span data-lang="feedback_submit">Submit Feedback</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Module: Profile -->
        <div id="module-profile" class="module-view hidden flex-1 overflow-y-auto px-6 lg:px-10 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="text-3xl font-black text-gray-900 mb-2" data-lang="profileHeader">Profile Settings</h2>
                    <p class="text-gray-600 font-medium" data-lang="profileSubheader">Manage your account information and preferences.</p>
                </div>

                <!-- Display Mode -->
                <div id="profile-view-mode" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-32 relative">
                        <div class="absolute -bottom-12 left-10">
                            <div class="w-24 h-24 bg-white rounded-3xl shadow-xl flex items-center justify-center text-emerald-600 text-4xl font-black">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="pt-16 pb-8 px-10">
                        <div class="flex flex-wrap justify-between items-start gap-4 mb-10">
                            <div>
                                <h3 id="profile-display-name" class="text-3xl font-black text-gray-900 mb-1">Loading...</h3>
                                <p class="text-sm font-bold text-emerald-600 uppercase tracking-widest" data-lang="farmerPortal">Farmer Portal</p>
                            </div>
                            <button onclick="toggleProfileMode('edit')" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2.5 rounded-2xl font-bold text-sm shadow-lg shadow-emerald-200 transition-all active:scale-95 flex items-center gap-2">
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
                <div id="profile-edit-mode" class="hidden bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 mb-8">
                    <form id="profile-form" onsubmit="handleProfileUpdate(event)" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1" data-lang="fullName">Full Name</label>
                                <input type="text" name="name" id="input-name" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1" data-lang="emailAddress">Email Address</label>
                                <input type="email" name="email" id="input-email" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800">
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
                                <select name="district" id="input-district" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800">
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
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1" data-lang="changePin">Change PIN (Empty to keep)</label>
                                <input type="password" name="pin" id="input-pin" pattern="\d{6}" maxlength="6" placeholder="••••••" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none font-bold text-gray-800">
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" id="profile-save-btn" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-black py-4 rounded-2xl shadow-xl transition-all active:scale-95 flex items-center justify-center gap-3">
                                <i class="fas fa-check-circle"></i> <span data-lang="saveChanges">Save Changes</span>
                            </button>
                            <button type="button" onclick="toggleProfileMode('view')" class="px-8 bg-gray-100 hover:bg-gray-200 text-gray-500 font-bold rounded-2xl transition-all" data-lang="cancel">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Data Configuration
        const cropLibrary = {
            wheat: {
                id: 'wheat',
                icon: '🌾',
                season: {
                    en: 'Rabi (Winter)',
                    gu: 'રવિ (શિયાળુ)',
                    hi: 'रबी (सर्दियों)'
                },
                name: {
                    en: 'Wheat',
                    gu: 'ઘઉં',
                    hi: 'गेहूँ'
                },
                schedule: {
                    10: {
                        type: 'prepare',
                        icon: 'tractor',
                        color: 'orange',
                        text: {
                            en: 'Field Preparation & Sowing',
                            gu: 'ખેતરની તૈયારી અને વાવણી',
                            hi: 'खेत की तैयारी एवं बुवाई'
                        }
                    },
                    11: {
                        type: 'water',
                        icon: 'tint',
                        color: 'blue',
                        text: {
                            en: 'First Irrigation (CRI Stage)',
                            gu: 'પ્રથમ પિયત (CRI તબક્કો)',
                            hi: 'पहली सिंचाई (CRI अवस्था)'
                        }
                    },
                    12: {
                        type: 'fertilizer',
                        icon: 'leaf',
                        color: 'emerald',
                        text: {
                            en: 'Top Dressing Nitrogen',
                            gu: 'નાઈટ્રોજન ખાતર આપવું',
                            hi: 'यूरिया का छिड़काव'
                        }
                    },
                    0: {
                        type: 'care',
                        icon: 'bug',
                        color: 'purple',
                        text: {
                            en: 'Weed & Pest Control',
                            gu: 'નીંદણ અને જીવાત નિયંત્રણ',
                            hi: 'खरपतवार एवं कीट नियंत्रण'
                        }
                    },
                    1: {
                        type: 'water',
                        icon: 'tint',
                        color: 'blue',
                        text: {
                            en: 'Flowering Stage Irrigation',
                            gu: 'ફૂલ અવસ્થાએ પિયત',
                            hi: 'फूल आने पर सिंचाई'
                        }
                    },
                    2: {
                        type: 'harvest',
                        icon: 'sun',
                        color: 'amber',
                        text: {
                            en: 'Grain Filling',
                            gu: 'દાણા ભરવાનો સમય',
                            hi: 'दाना भरने का समय'
                        }
                    },
                    3: {
                        type: 'harvest',
                        icon: 'scythe',
                        color: 'red',
                        text: {
                            en: 'Harvesting',
                            gu: 'લણણી',
                            hi: 'कटाई'
                        }
                    }
                }
            },
            cotton: {
                id: 'cotton',
                icon: '☁️',
                season: {
                    en: 'Kharif (Monsoon)',
                    gu: 'ખરીફ (ચોમાસુ)',
                    hi: 'खरीफ (मानसून)'
                },
                name: {
                    en: 'Cotton',
                    gu: 'કપાસ',
                    hi: 'कपास'
                },
                schedule: {
                    4: {
                        type: 'prepare',
                        icon: 'tractor',
                        color: 'orange',
                        text: {
                            en: 'Deep Ploughing & Prep',
                            gu: 'ઊંડી ખેડ અને તૈયારી',
                            hi: 'गहरी जुताई और तैयारी'
                        }
                    },
                    5: {
                        type: 'prepare',
                        icon: 'seedling',
                        color: 'emerald',
                        text: {
                            en: 'Pre-monsoon Sowing',
                            gu: 'ચોમાસા પહેલા વાવણી',
                            hi: 'मानसून पूर्व बुवाई'
                        }
                    },
                    6: {
                        type: 'care',
                        icon: 'leaf',
                        color: 'emerald',
                        text: {
                            en: 'Weeding & Thinning',
                            gu: 'નીંદણ અને પારવણી',
                            hi: 'खरपतवार नियंत्रण'
                        }
                    },
                    7: {
                        type: 'fertilizer',
                        icon: 'flask',
                        color: 'purple',
                        text: {
                            en: 'Fertilizer Application',
                            gu: 'ખાતરનો ઉપયોગ',
                            hi: 'उर्वरक का प्रयोग'
                        }
                    },
                    8: {
                        type: 'care',
                        icon: 'bug',
                        color: 'red',
                        text: {
                            en: 'Pest Scouting (Bollworm)',
                            gu: 'જીવાત નિરીક્ષણ (ઇયળ)',
                            hi: 'कीट निरीक्षण (इल्ली)'
                        }
                    },
                    9: {
                        type: 'water',
                        icon: 'tint',
                        color: 'blue',
                        text: {
                            en: 'Square & Boll Formation',
                            gu: 'ઝીંડવા બેસવાનો સમય',
                            hi: 'टिंडे बनने का समय'
                        }
                    },
                    10: {
                        type: 'harvest',
                        icon: 'box',
                        color: 'amber',
                        text: {
                            en: 'First Picking',
                            gu: 'પ્રથમ વીણી',
                            hi: 'पहली चुनाई'
                        }
                    },
                    11: {
                        type: 'harvest',
                        icon: 'box',
                        color: 'amber',
                        text: {
                            en: 'Second Picking',
                            gu: 'બીજી વીણી',
                            hi: 'दूसरी चुनाई'
                        }
                    }
                }
            }
        };

        const monthNames = {
            en: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            gu: ['જાન્યુઆરી', 'ફેબ્રુઆરી', 'માર્ચ', 'એપ્રિલ', 'મે', 'જૂન', 'જુલાઈ', 'ઓગસ્ટ', 'સપ્ટેમ્બર', 'ઓક્ટોબર', 'નવેમ્બર', 'ડિસેમ્બર'],
            hi: ['जनवरी', 'फरवरी', 'मार्च', 'अप्रैल', 'मई', 'जून', 'जुलाई', 'अगस्त', 'सितंबर', 'अक्टूबर', 'नवंबर', 'दिसंबर']
        };

        const dashboardTranslations = {
            en: {
                appName: 'AgriCare',
                farmerPortal: 'Farmer Portal',
                menu: 'Menu',
                cropCalendar: 'Crop Calendar',
                marketPrices: 'Mandi Prices',
                weather: 'Weather Forecast',
                disease: 'Disease Detector',
                subsidies: 'Govt Subsidies',
                logout: 'Logout',
                profile: 'My Profile',
                headerTitle: 'Seasonal Activity Plan',
                headerDesc: 'Select your crop to view detailed month-by-month guidance for sowing, irrigation, fertilizer management, and harvesting.',
                selectCrop: 'Select Crop',
                noActivity: 'Rest Period / Field Prep',
                diseaseTitle: 'AI Health Scan',
                disease_subtitle: 'Upload a clear photo of the leaf for an instant AI-powered diagnosis and treatment plan.',
                upload_ready: 'Scan Ready?',
                drag_drop: 'Click or Drag Crop Photo',
                btn_analyze: 'Start Neural Analysis',
                diagnosis_report: 'Health Report',
                model_confidence: 'Scan Accuracy',
                irrigation: 'Watering Plan',
                treatment: 'Medication',
                awaiting_scan: 'Awaiting Upload',
                status_online: 'AI ONLINE',
                status_offline: 'AI OFFLINE',
                subsidy_hero_desc: 'Discover the latest financial aids, schemes, and benefits provided by the state and central government for farmers.',
                apply_now: 'Apply Now',
                view_details: 'View Details',
                chatAssistant: 'Chat Assistant',
                askAgriCare: 'Ask AgriCare',
                benefit_label: 'Key Benefit',
                eligibility_label: 'Eligibility',
                live_status: 'Live',
                upcoming_status: 'Upcoming',
                processTimeline: 'Process Timeline',
                status: 'Status',
                planned_op: 'Planned Operation',
                duration: 'Duration',
                days: 'Days',
                impact: 'Impact',
                high_yield: 'High Yield',
                cat_all: 'All Schemes',
                cat_irrigation: 'Irrigation',
                cat_equipment: 'Equipment',
                cat_training: 'Training',
                cat_seeds: 'Seed & Fertilizer',
                cat_financial: 'Financial',
                cat_insurance: 'Insurance',
                day: 'Day',
                feedback_nav: 'Feedback',
                feedback_title: 'Send Feedback',
                feedback_subtitle: 'Your feedback helps us improve AgriCare for all farmers.',
                feedback_subject_label: 'Subject',
                feedback_rating_label: 'Overall Rating',
                feedback_message_label: 'Your Message',
                feedback_submit: 'Submit Feedback',
                feedback_subject_ph: 'e.g., App Improvement',
                feedback_message_ph: 'Describe your experience or suggest features...',
                rating_5: 'Excellent',
                rating_4: 'Good',
                rating_3: 'Average',
                rating_2: 'Poor',
                rating_1: 'Bad'
            },
            gu: {
                appName: 'એગ્રીકેર',
                farmerPortal: 'ખેડૂત પોર્ટલ',
                menu: 'મેનુ',
                cropCalendar: 'પાક કેલેન્ડર',
                marketPrices: 'મંડીના ભાવો',
                weather: 'હવામાન આગાહી',
                disease: 'રોગ નિદાન',
                subsidies: 'સરકારી સહાય',
                logout: 'લોગઆઉટ',
                profile: 'મારી પ્રોફાઇલ',
                headerTitle: 'મોસમી પ્રવૃત્તિ યોજના',
                headerDesc: 'વાવણી, સિંચાઈ, ખાતર વ્યવસ્થાપન અને લણણી માટે વિગતવાર મહિના મુજબનું માર્ગદર્શન જોવા માટે તમારો પાક પસંદ કરો.',
                selectCrop: 'પાક પસંદ કરો',
                noActivity: 'આરામનો સમય / ખેતરની તૈયારી',
                diseaseTitle: 'AI પાક તપાસ',
                disease_subtitle: 'ત્વરિત AI-આધારિત નિદાન અને સારવાર યોજના માટે પાંદડાનો સ્પષ્ટ ફોટો અપલોડ કરો.',
                upload_ready: 'તપાસ માટે તૈયાર?',
                drag_drop: 'ફોટો અહીં ક્લિક કરો અથવા ખેંચો',
                btn_analyze: 'તપાસ શરૂ કરો',
                diagnosis_report: 'તપાસ રિપોર્ટ',
                model_confidence: 'ચોકસાઈ',
                irrigation: 'પિયત યોજના',
                treatment: 'સારવાર',
                awaiting_scan: 'અપલોડની રાહ જોઈ રહ્યા છીએ',
                status_online: 'AI ઓનલાઇન',
                status_offline: 'AI ઓફલાઇન',
                subsidy_hero_desc: 'તમારી કૃષિ જરૂરિયાતો માટે ઉપલબ્ધ સરકારી સહાય અને યોજનાઓ વિશે જાણો.',
                apply_now: 'અરજી કરો',
                view_details: 'વિગતો જુઓ',
                benefit_label: 'લાભ:',
                eligibility_label: 'પાત્રતા:',
                live_status: 'ચાલુ',
                upcoming_status: 'આગામી',
                chatAssistant: 'ચેટ સહાયક',
                askAgriCare: 'એગ્રીકેરને પૂછો',
                processTimeline: 'પ્રક્રિયા સમયરેખા',
                status: 'સ્થિતિ',
                planned_op: 'આયોજિત કામગીરી',
                duration: 'સમયગાળો',
                days: 'દિવસો',
                impact: 'અસર',
                high_yield: 'વધુ ઉપજ',
                cat_all: 'બધી યોજનાઓ',
                cat_irrigation: 'સિંચાઈ',
                cat_equipment: 'સાધનો',
                cat_training: 'તાલીમ',
                cat_seeds: 'બિયારણ અને ખાતર',
                cat_financial: 'નાણાકીય સહાય',
                cat_insurance: 'વીમો',
                day: 'દિવસ',
                feedback_nav: 'પ્રતિસાદ',
                feedback_title: 'પ્રતિસાદ મોકલો',
                feedback_subtitle: 'તમારો પ્રતિસાદ અમને એગ્રીકેરને સુધારવામાં મદદ કરે છે.',
                feedback_subject_label: 'વિષય',
                feedback_rating_label: 'રેટિંગ',
                feedback_message_label: 'તમારો સંદેશ',
                feedback_submit: 'પ્રતિસાદ સબમિટ કરો',
                feedback_subject_ph: 'દા.ત., એપ્લિકેશન સુધારો',
                feedback_message_ph: 'તમારા અનુભવનું વર્ણન કરો અથવા સુવિધાઓ સૂચવો...',
                rating_5: 'ઉત્તમ',
                rating_4: 'સારું',
                rating_3: 'સાધારણ',
                rating_2: 'નબળું',
                rating_1: 'ખરાબ'
            },
            hi: {
                appName: 'एग्रीकेयर',
                farmerPortal: 'किसान पोर्टल',
                menu: 'मेनू',
                cropCalendar: 'फसल कैलेंडर',
                marketPrices: 'मंडी भाव',
                weather: 'मौसम पूर्वानुमान',
                disease: 'रोग पहचान',
                subsidies: 'सरकारी सब्सिडी',
                logout: 'लॉगआउट',
                profile: 'मेरी प्रोफाइल',
                headerTitle: 'मौसमी गतिविधि योजना',
                headerDesc: 'बुवाई, सिंचाई, उर्वरक प्रबंधन और कटाई के लिए विस्तृत महीनेवार मार्गदर्शन देखने के लिए अपनी फसल का चयन करें।',
                selectCrop: 'फसल चुनें',
                noActivity: 'आराम की अवधि / खेत की तैयारी',
                diseaseTitle: 'AI हेल्थ स्कैन',
                disease_subtitle: 'त्वरित AI-आधारित निदान और उपचार योजना के लिए पत्ते की स्पष्ट फोटो अपलोड करें।',
                upload_ready: 'स्कैन के लिए तैयार?',
                drag_drop: 'फोटो यहाँ क्लिक करें या खींचें',
                btn_analyze: 'न्यूरल विश्लेषण शुरू करें',
                diagnosis_report: 'जाँच रिपोर्ट',
                model_confidence: 'स्कैन सटीकता',
                irrigation: 'सिंचाई योजना',
                treatment: 'उपचार',
                awaiting_scan: 'अपलोड की प्रतीक्षा है',
                status_online: 'AI ऑनलाइन',
                status_offline: 'AI ऑफलाइन',
                subsidy_hero_desc: 'अपनी कृषि आवश्यकताओं के लिए उपलब्ध सरकारी सहायता और योजनाओं के बारे में जानें।',
                apply_now: 'आवेदन करें',
                view_details: 'विवरण देखें',
                benefit_label: 'लाभ:',
                eligibility_label: 'पात्रता:',
                live_status: 'सक्रिय',
                upcoming_status: 'आगामी',
                chatAssistant: 'चैट सहायक',
                askAgriCare: 'एग्रीकेयर से पूछें',
                processTimeline: 'प्रक्रिया समयरेखा',
                status: 'स्थिति',
                planned_op: 'नियोजित कार्य',
                duration: 'अवधि',
                days: 'दिन',
                impact: 'प्रभाव',
                high_yield: 'उच्च उपज',
                cat_all: 'सभी योजनाएं',
                cat_irrigation: 'सिंचाई',
                cat_equipment: 'उपकरण',
                cat_training: 'प्रशिक्षण',
                cat_insurance: 'बीमा',
                day: 'दिन',
                feedback_nav: 'फीडबैक',
                feedback_title: 'फीडबैक भेजें',
                feedback_subtitle: 'आपकी प्रतिक्रिया हमें एग्रीकेयर को बेहतर बनाने में मदद करती है।',
                feedback_subject_label: 'विषय',
                feedback_rating_label: 'रेटिंग',
                feedback_message_label: 'आपका संदेश',
                feedback_submit: 'फीडबैक भेजें',
                feedback_subject_ph: 'जैसे, ऐप में सुधार',
                feedback_message_ph: 'अपने अनुभव का वर्णन करें या सुविधाओं का सुझाव दें...',
                rating_5: 'उत्कृष्ट',
                rating_4: 'अच्छा',
                rating_3: 'सामान्य',
                rating_2: 'खराब',
                rating_1: 'बहुत खराब'
            }
        };

        // Merge dashboard-specific translations into the global dictionary (from translations.js)
        if (typeof translations !== 'undefined') {
            for (const lang in dashboardTranslations) {
                if (!translations[lang]) translations[lang] = {};
                Object.assign(translations[lang], dashboardTranslations[lang]);
            }
        } else {
            // Fallback if translations.js is somehow not loaded
            window.translations = dashboardTranslations;
        }

        // Safety: Ensure translations is globally accessible even if defined as const in another script
        const safeTranslations = typeof translations !== 'undefined' ? translations : window.translations;
        
        let currentLang = localStorage.getItem('agricare_lang') || 'en';
        // Ensure the current language exists in our dictionary
        if (!safeTranslations[currentLang]) currentLang = 'en';

        // Helper to get translated string safely
        function t(key) {
            return (safeTranslations[currentLang] && safeTranslations[currentLang][key]) || key;
        }
        let activeCropIndex = 0;
        let cropsData = [];

        function updateUserInfo() {
            const userData = JSON.parse(sessionStorage.getItem('agricare_user') || localStorage.getItem('agricare_user') || 'null');
            if (userData) {
                document.getElementById('userNameDisplay').innerText = userData.name || 'Farmer User';
            }
        }

        async function fetchCrops() {
            const userStr = sessionStorage.getItem('agricare_user') || localStorage.getItem('agricare_user');
            let district = '';
            let city = '';
            if (userStr) {
                const user = JSON.parse(userStr);
                district = user.district || '';
                city = user.city || '';
            }

            try {
                const response = await fetch(`../backend/get_crops.php?district=${encodeURIComponent(district)}&city=${encodeURIComponent(city)}`);
                const data = await response.json();
                cropsData = Array.isArray(data) ? data : [];
                activeCropIndex = 0;
                renderSidebar();
                renderCalendar();
            } catch (err) {
                console.error("Failed to fetch crops:", err);
                cropsData = [];
                renderSidebar();
            }
        }

        function renderSidebar() {
            const container = document.getElementById('cropSelector');
            container.innerHTML = '';

            if (!Array.isArray(cropsData) || cropsData.length === 0) {
                container.innerHTML = '<div class="p-4 text-gray-400 text-sm font-bold">Crop data unavailable.</div>';
                return;
            }

            const wrapper = document.createElement('div');
            wrapper.className = 'relative';
            
            const selectEl = document.createElement('select');
            selectEl.className = 'w-full appearance-none bg-white border border-gray-200 text-gray-700 text-lg py-4 px-5 pr-12 rounded-2xl font-bold shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer hover:border-emerald-300 transition-colors';
            
            selectEl.onchange = (e) => {
                activeCropIndex = parseInt(e.target.value);
                renderCalendar();
            };

            cropsData.forEach((crop, index) => {
                const opt = document.createElement('option');
                opt.value = index;
                opt.textContent = `${crop.icon} ${crop[`name_${currentLang}`]}`;
                if (index === activeCropIndex) {
                    opt.selected = true;
                }
                selectEl.appendChild(opt);
            });

            wrapper.appendChild(selectEl);

            const arrow = document.createElement('div');
            arrow.className = 'pointer-events-none absolute inset-y-0 right-0 flex items-center pr-5 text-emerald-500';
            arrow.innerHTML = '<i class="fas fa-chevron-down text-lg"></i>';
            wrapper.appendChild(arrow);

            container.appendChild(wrapper);
        }

        function renderCalendar() {
            if (!Array.isArray(cropsData) || cropsData.length === 0) return;

            const crop = cropsData[activeCropIndex];
            document.getElementById('activeCropName').innerText = crop[`name_${currentLang}`];
            document.getElementById('activeCropSeason').innerText = crop[`season_${currentLang}`];
            document.getElementById('activeCropIcon').innerText = crop.icon;

            const grid = document.getElementById('calendarGrid');
            grid.innerHTML = '';

            if (!crop.schedule || crop.schedule.length === 0) {
                grid.innerHTML = `
                    <div class="bg-white rounded-[2rem] p-12 text-center border border-gray-100 shadow-sm">
                        <i class="fas fa-calendar-day text-5xl text-gray-100 mb-4"></i>
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">${t('noActivity')}</p>
                    </div>
                `;
                return;
            }

            // Sort schedule by start_day
            const sortedSchedule = [...crop.schedule].sort((a, b) => a.start_day - b.start_day);

            sortedSchedule.forEach((activity, index) => {
                const processCard = document.createElement('div');
                processCard.className = 'process-card';

                processCard.innerHTML = `
                    <div class="process-dot"></div>
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                        <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-${activity.activity_color}-50 rounded-2xl flex items-center justify-center text-${activity.activity_color}-600">
                                    <i class="fas fa-${activity.activity_icon}"></i>
                                </div>
                                <h4 class="text-xl font-black text-gray-900">${activity[`task_${currentLang}`]}</h4>
                            </div>
                            <div class="timeline-badge">
                                ${t('day')} ${activity.start_day} - ${activity.end_day}
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-2xl">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">${t('status')}</p>
                                <p class="text-sm font-bold text-gray-800">${t('planned_op')}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">${t('duration')}</p>
                                <p class="text-sm font-bold text-gray-800">${activity.end_day - activity.start_day} ${t('days')}</p>
                            </div>
                             <div class="bg-gray-50 p-4 rounded-2xl">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">${t('impact')}</p>
                                <p class="text-sm font-bold text-emerald-600">${t('high_yield')}</p>
                            </div>
                        </div>
                    </div>
                `;
                grid.appendChild(processCard);
            });
        }

        function changeLang(lang) {
            currentLang = lang;
            localStorage.setItem('agricare_lang', lang);

            document.querySelectorAll('[data-lang]').forEach(el => {
                const key = el.getAttribute('data-lang');
                if (safeTranslations[lang] && safeTranslations[lang][key]) el.innerText = safeTranslations[lang][key];
            });

            document.querySelectorAll('[data-placeholder]').forEach(el => {
                const key = el.getAttribute('data-placeholder');
                if (safeTranslations[lang] && safeTranslations[lang][key]) el.placeholder = safeTranslations[lang][key];
            });

            document.body.classList.remove('gu-text', 'hi-text');
            if (lang === 'gu') document.body.classList.add('gu-text');
            if (lang === 'hi') document.body.classList.add('hi-text');

            renderSidebar();
            renderCalendar();
        }

        function logout() {
            sessionStorage.removeItem('agricare_user');
            localStorage.removeItem('agricare_user');
            window.location.href = '../frontend/login.php';
        }

        const AI_HEALTH_ENDPOINT = '../backend/ai_health.php';
        const AI_PREDICT_ENDPOINT = '../backend/ai_predict.php';

        function switchModule(modId, el = null) {
            // Find sidebar element if not provided
            if (!el) {
                const links = document.querySelectorAll('.sidebar-link');
                links.forEach(link => {
                    const onclickStr = link.getAttribute('onclick') || "";
                    if (onclickStr.includes(`'${modId}'`)) el = link;
                });
            }

            // Update Sidebar UI
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('active');
            });
            if (el) el.classList.add('active');

            // Switch Views
            document.querySelectorAll('.module-view').forEach(view => {
                view.classList.add('hidden');
            });
            const view = document.getElementById(`module-${modId}`);
            if (view) view.classList.remove('hidden');

            // Update Topbar Title
            const titleMap = {
                calendar: t('cropCalendar'),
                disease: t('disease'),
                subsidies: t('subsidies'),
                feedback: t('feedback_nav'),
                profile: t('profile')
            };
            const topbarTitle = document.querySelector('header h2');
            if (topbarTitle) topbarTitle.innerText = titleMap[modId] || 'Dashboard';

            if (modId === 'subsidies') fetchSubsidies();
            if (modId === 'profile') fetchProfile();
        }

        // Handle URL parameters for modules
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const mod = urlParams.get('mod');
            if (mod) switchModule(mod);
        });

        // --- Subsidies Logic ---
        const subsidyFallbackData = <?php echo json_encode($dashboardSubsidyFallback, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        let allSubsidies = [];
        async function fetchSubsidies() {
            const container = document.getElementById('subsidiesList');
            container.innerHTML = `<div class="col-span-full py-20 text-center"><i class="fas fa-spinner fa-spin text-3xl text-emerald-500 mb-4"></i><p class="text-gray-500 font-bold">Fetching latest schemes...</p></div>`;

            try {
                const res = await fetch('../backend/get_subsidies.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ category: 'All', search: '' })
                });
                if (!res.ok) {
                    throw new Error(`Subsidy request failed with status ${res.status}`);
                }

                const contentType = res.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    throw new Error('Subsidy response was not JSON');
                }

                const data = await res.json();
                allSubsidies = Array.isArray(data) && data.length ? data : subsidyFallbackData;
                renderSubsidies(allSubsidies);
            } catch (err) {
                console.error("Failed to load subsidies", err);
                allSubsidies = subsidyFallbackData;
                renderSubsidies(allSubsidies);
            }
        }

        function filterSubsidies(category) {
            const tabs = document.querySelectorAll('.subsidy-tab');
            tabs.forEach(t => {
                if (t.dataset.cat === category) {
                    t.classList.add('bg-emerald-600', 'text-white');
                    t.classList.remove('bg-white', 'text-gray-600');
                } else {
                    t.classList.remove('bg-emerald-600', 'text-white');
                    t.classList.add('bg-white', 'text-gray-600');
                }
            });

            if (category === 'All') {
                renderSubsidies(allSubsidies);
            } else {
                const filtered = allSubsidies.filter(s => s.category === category);
                renderSubsidies(filtered);
            }
        }

        function searchSubsidies(q) {
            const filtered = allSubsidies.filter(s =>
                s.name.toLowerCase().includes(q.toLowerCase()) ||
                (s.name_gu && s.name_gu.toLowerCase().includes(q.toLowerCase())) ||
                (s.name_hi && s.name_hi.toLowerCase().includes(q.toLowerCase()))
            );
            renderSubsidies(filtered);
        }

        function renderSubsidies(list) {
            const container = document.getElementById('subsidiesList');
            if (!list || list.length === 0) {
                container.innerHTML = `<div class="col-span-full py-20 text-center bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-100"><p class="text-gray-400 font-bold">No schemes found matching your criteria.</p></div>`;
                return;
            }

            container.innerHTML = list.map(s => `
                <div class="group bg-white p-7 rounded-[2.5rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full -mr-16 -mt-16 opacity-50 transition-transform group-hover:scale-110"></div>
                    
                    <div class="flex justify-between items-start mb-6 relative z-10">
                        <span class="px-4 py-1.5 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm">${t({'All': 'cat_all', 'Irrigation': 'cat_irrigation', 'Equipment': 'cat_equipment', 'Training': 'cat_training', 'Seed & Fertilizer': 'cat_seeds', 'Financial': 'cat_financial', 'Insurance': 'cat_insurance'}[s.category]) || s.category}</span>
                        <div class="flex items-center gap-1.5">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-emerald-500 font-black text-[10px] tracking-tight uppercase">${s.status === 'Live' ? t('live_status') : (t('upcoming_status') || s.status || 'Active')}</span>
                        </div>
                    </div>

                    <h4 class="text-xl font-black text-gray-900 mb-3 leading-tight group-hover:text-emerald-700 transition-colors">${s[`name_${currentLang}`] || t(s.name)}</h4>
                    <p class="text-sm text-gray-500 font-medium line-clamp-2 mb-6 leading-relaxed">${s[`description_${currentLang}`] || t(s.description)}</p>
                    
                    <div class="space-y-3 mb-8">
                        <div class="flex flex-col gap-1.5 p-4 bg-gray-50 rounded-2xl border border-gray-100/50">
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest leading-none">${t('benefit_label')}</span>
                            <span class="text-sm font-bold text-gray-800 leading-tight">${s[`benefits_${currentLang}`] || t(s.benefits) || '--'}</span>
                        </div>
                    </div>

                    <a href="${s.apply_link}" target="_blank" class="flex items-center justify-center gap-2 w-full bg-gray-900 text-white py-4 rounded-2xl font-black hover:bg-emerald-600 shadow-lg hover:shadow-emerald-200 transition-all active:scale-95 relative z-10">
                        <span>${t('apply_now')}</span>
                        <i class="fas fa-external-link-alt text-xs opacity-50"></i>
                    </a>
                </div>
            `).join('');
        }

        // --- AI Disease Detection Logic ---
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const previewWrap = document.getElementById('preview-wrapper');
        const previewImg = document.getElementById('image-preview');
        const analyzeBtn = document.getElementById('analyze-btn');

        if (dropZone) {
            dropZone.onclick = () => fileInput.click();
            fileInput.onchange = (e) => handleFiles(e.target.files);
            dropZone.ondragover = (e) => {
                e.preventDefault();
                dropZone.classList.add('bg-emerald-50');
            };
            dropZone.ondragleave = () => dropZone.classList.remove('bg-emerald-50');
            dropZone.ondrop = (e) => {
                e.preventDefault();
                handleFiles(e.dataTransfer.files);
            };
        }

        function handleFiles(files) {
            if (!files[0]) return;
            const file = files[0];

            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                dropZone.classList.add('hidden');
                previewWrap.classList.remove('hidden');
                analyzeBtn.disabled = false;
            };
            reader.readAsDataURL(file);
        }

        function resetScan() {
            fileInput.value = '';
            previewImg.src = '';
            previewWrap.classList.add('hidden');
            dropZone.classList.remove('hidden');
            analyzeBtn.disabled = true;
            document.getElementById('result-panel').classList.add('hidden');
            document.getElementById('empty-state').classList.remove('hidden');
        }

        if (analyzeBtn) {
            analyzeBtn.onclick = async () => {
                const file = fileInput.files[0];
                if (!file) return;

                analyzeBtn.disabled = true;
                document.getElementById('btn-text').textContent = "Neural Mapping...";
                try {
                    const userData = JSON.parse(sessionStorage.getItem('agricare_user'));
                    const fd = new FormData();
                    fd.append('image', file);
                    fd.append('lang', currentLang);
                    if (userData && userData.id) fd.append('user_id', userData.id);

                    const res = await fetch(AI_PREDICT_ENDPOINT, {
                        method: 'POST',
                        body: fd
                    });

                    // Always parse JSON first (even on error responses)
                    let data;
                    try {
                        data = await res.json();
                    } catch (parseErr) {
                        throw new Error('Server returned invalid response. Check server logs.');
                    }

                    if (data.error) {
                        alert("⚠️ " + data.error);
                        analyzeBtn.disabled = false;
                        document.getElementById('btn-text').textContent = t('btn_analyze');
                        return;
                    }

                    if (!res.ok) {
                        throw new Error(data.error || 'Local model prediction failed');
                    }

                    showResults(data);
                } catch (err) {
                    console.error("AI Analysis Error:", err);
                    alert("Neural engine is taking longer than expected. Please check your internet connection.");
                    analyzeBtn.disabled = false;
                    document.getElementById('btn-text').textContent = t('btn_analyze');
                }
            };
        }

        function showResults(data) {
            document.getElementById('empty-state').classList.add('hidden');
            document.getElementById('result-panel').classList.remove('hidden');

            document.getElementById('result-image-preview').src = previewImg.src;
            document.getElementById('disease-name').textContent = data.label;
            const plantName = data.plant || data.plant_type || 'Plant';
            document.getElementById('plant-type').textContent = plantName + " SPECIES";
            document.getElementById('disease-desc').textContent = data.info.desc;
            document.getElementById('irrigation-text').textContent = data.info.irrigation;
            document.getElementById('treatment-text').textContent = data.info.treatment;

            const conf = data.confidence.toFixed(1);
            document.getElementById('confidence-text').textContent = conf + "%";
            document.getElementById('confidence-pill').textContent = conf + "% CONFIDENCE";
            document.getElementById('confidence-bar').style.width = Math.max(10, conf) + "%";

            analyzeBtn.disabled = false;
            document.getElementById('btn-text').textContent = t('btn_analyze');
        }

        // Health Checker
        async function checkApi() {
            try {
                const label = document.getElementById('status-label');
                const pulse = document.getElementById('status-pulse');
                const res = await fetch(AI_HEALTH_ENDPOINT, {
                    signal: AbortSignal.timeout(6000)
                });
                if (res.ok) {
                    label.innerText = t('status_online');
                    label.className = "text-[10px] font-black uppercase tracking-widest text-emerald-600";
                    pulse.className = "w-3 h-3 rounded-full bg-emerald-500 animate-pulse";
                } else {
                    label.innerText = t('status_offline');
                    label.className = "text-[10px] font-black uppercase tracking-widest text-orange-500";
                    pulse.className = "w-3 h-3 rounded-full bg-orange-400";
                }
            } catch {
                const label = document.getElementById('status-label');
                const pulse = document.getElementById('status-pulse');
                if (label) label.innerText = t('status_offline');
                if (pulse) pulse.className = "w-3 h-3 rounded-full bg-orange-400";
            }
        }
        checkApi();
        setInterval(() => {
            if (!document.hidden) {
                checkApi();
            }
        }, 60000);

        // --- PROFILE MODULE LOGIC ---
        let userProfile = null;
        const districts = [
            'Ahmedabad', 'Amreli', 'Anand', 'Arvalli', 'Banaskantha', 'Bharuch', 'Bhavnagar',
            'Botad', 'Chhota Udaipur', 'Dahod', 'Dang', 'Devbhoomi Dwarka', 'Gandhinagar',
            'Gir Somnath', 'Jamnagar', 'Junagadh', 'Kheda', 'Kutch', 'Mahisagar', 'Mehsana',
            'Morbi', 'Narmada', 'Navsari', 'Panchmahal', 'Patan', 'Porbandar', 'Rajkot',
            'Sabarkantha', 'Surat', 'Surendranagar', 'Tapi', 'Vadodara', 'Valsad'
        ];

        function toggleProfileMode(mode) {
            document.getElementById('profile-view-mode').classList.toggle('hidden', mode === 'edit');
            document.getElementById('profile-edit-mode').classList.toggle('hidden', mode === 'view');
            if (mode === 'edit') fillProfileForm();
        }

        async function fetchProfile() {
            const userStr = sessionStorage.getItem('agricare_user') || localStorage.getItem('agricare_user');
            const userData = userStr ? JSON.parse(userStr) : null;
            if (!userData || !userData.id) return;

            try {
                const res = await fetch(`../backend/profile_api.php?id=${userData.id}`);
                const data = await res.json();
                if (data.success) {
                    userProfile = data.user;
                    updateProfileUI();
                }
            } catch (err) { console.error("Profile fetch error", err); }
        }

        function updateProfileUI() {
            if(!userProfile) return;
            document.getElementById('profile-display-name').innerText = userProfile.name;
            document.getElementById('display-phone').innerText = userProfile.phone;
            document.getElementById('display-email').innerText = userProfile.email || '--';
            document.getElementById('display-district').innerText = userProfile.district;
            document.getElementById('display-city').innerText = userProfile.city;
            document.getElementById('display-pincode').innerText = userProfile.pincode;
            document.getElementById('display-lang').innerText = userProfile.pref_lang === 'gu' ? 'ગુજરાતી' : (userProfile.pref_lang === 'hi' ? 'हिन्दी' : 'English');
            document.getElementById('userNameDisplay').innerText = userProfile.name;
        }

        function fillProfileForm() {
            if(!userProfile) return;
            document.getElementById('input-name').value = userProfile.name;
            document.getElementById('input-email').value = userProfile.email || '';
            document.getElementById('input-phone').value = userProfile.phone;
            document.getElementById('input-lang').value = userProfile.pref_lang;
            document.getElementById('input-city').value = userProfile.city;
            document.getElementById('input-pincode').value = userProfile.pincode;
            
            // Populate districts if empty
            const distSelect = document.getElementById('input-district');
            if (distSelect.options.length <= 1) {
                distSelect.innerHTML = '';
                districts.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d;
                    opt.textContent = d;
                    distSelect.appendChild(opt);
                });
            }
            distSelect.value = userProfile.district;
        }

        async function handleProfileUpdate(e) {
            e.preventDefault();
            const btn = document.getElementById('profile-save-btn');
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
                    if (payload.pin) delete userProfile.pin;
                    
                    // Update session storage
                    const currentStr = sessionStorage.getItem('agricare_user') || localStorage.getItem('agricare_user');
                    const currentData = currentStr ? JSON.parse(currentStr) : {};
                    const updatedData = { ...currentData, name: payload.name, pref_lang: payload.pref_lang, district: payload.district, city: payload.city };
                    
                    if (localStorage.getItem('agricare_user')) localStorage.setItem('agricare_user', JSON.stringify(updatedData));
                    sessionStorage.setItem('agricare_user', JSON.stringify(updatedData));
                    
                    changeLang(payload.pref_lang);
                    updateProfileUI();
                    toggleProfileMode('view');
                    alert("Profile updated successfully!");
                } else { alert(data.message); }
            } catch (err) { alert("Update failed."); }
            finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        }
    </script>

    <!-- Tailwind Class Harvester (Ensures dynamic classes are included in the CSS bundle) -->
    <div class="hidden bg-emerald-500 bg-teal-500 bg-blue-500 bg-orange-400 bg-red-500 text-emerald-600 text-teal-600 text-blue-600 text-orange-600 text-red-600 scale-110 shadow-lg"></div>

    <script>
        function selectRating(val) {
            // Update hidden inputs
            document.querySelectorAll('input[name="rating"]').forEach(input => {
                input.checked = (parseInt(input.value) === val);
            });

            // Update UI
            document.querySelectorAll('.rating-star-box').forEach(box => {
                box.style.backgroundColor = '';
                box.style.color = '';
                box.className = "rating-star-box w-12 h-12 flex items-center justify-center rounded-xl bg-gray-50 text-gray-300 transition-all shadow-sm";
            });
            document.querySelectorAll('.rating-label').forEach(label => {
                label.style.color = '';
                label.className = "rating-label text-[9px] font-black text-gray-400 uppercase tracking-tighter";
            });

            const activeBox = document.getElementById(`star-box-${val}`);
            const activeLabel = activeBox.nextElementSibling;
            
            const hexColors = {
                5: '#10b981', // emerald-500
                4: '#14b8a6', // teal-500
                3: '#3b82f6', // blue-500
                2: '#fb923c', // orange-400
                1: '#ef4444'  // red-500
            };
            const textHexColors = {
                5: '#059669',
                4: '#0d9488',
                3: '#2563eb',
                2: '#ea580c',
                1: '#dc2626'
            };

            if (activeBox) {
                activeBox.style.backgroundColor = hexColors[val];
                activeBox.style.color = '#ffffff';
                activeBox.className = `rating-star-box w-12 h-12 flex items-center justify-center rounded-xl transition-all shadow-lg scale-110`;
                if (activeLabel) {
                    activeLabel.style.color = textHexColors[val];
                    activeLabel.className = `rating-label text-[9px] font-black uppercase tracking-tighter`;
                }
            }
        }

        async function submitFeedback(e) {
            e.preventDefault();
            const btn = document.getElementById('feedbackSubmitBtn');
            const subject = document.getElementById('feedbackSubject').value;
            const message = document.getElementById('feedbackMessage').value;
            const rating = document.querySelector('input[name="rating"]:checked').value;

            btn.disabled = true;
            btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Submitting...`;

            try {
                const res = await fetch('../backend/submit_feedback_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ subject, message, rating })
                });
                const data = await res.json();
                
                if (data.success) {
                    alert('✅ ' + t('feedback_success'));
                    document.getElementById('feedbackForm').reset();
                } else {
                    alert('❌ ' + data.message);
                }
            } catch (err) {
                console.error(err);
                alert('❌ Server error. Please try again.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-paper-plane"></i> ${t('feedback_submit')}`;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateUserInfo();
            changeLang(currentLang);
            fetchCrops();
        });
    </script>
    <!-- Floating AI Chat Button -->
    <a href="../frontend/chatbot.php" 
       class="fixed bottom-8 right-8 w-16 h-16 bg-emerald-600 text-white rounded-full shadow-2xl flex items-center justify-center hover:bg-emerald-700 hover:scale-110 transition-all z-50 group">
        <i class="fas fa-robot text-2xl"></i>
        <span class="absolute right-20 bg-gray-900 text-white px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-xl" data-lang="askAgriCare">
            Ask AgriCare
        </span>
    </a>

    <script>
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



