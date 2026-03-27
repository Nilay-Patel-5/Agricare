<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Console | AgriCare</title>
    <link rel="stylesheet" href="../frontend/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5); }
        .sidebar-gradient { background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); }
        .active-nav { background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        @keyframes pulse-soft { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        .pulse-animation { animation: pulse-soft 3s infinite; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body class="bg-slate-50 overflow-hidden font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-72">
                <div class="flex flex-col h-0 flex-1 sidebar-gradient shadow-2xl relative">
                    <div class="absolute top-0 left-0 w-full h-32 bg-emerald-500/10 blur-3xl rounded-full"></div>
                    <div class="flex-1 flex flex-col pt-8 pb-4 overflow-y-auto custom-scrollbar relative z-10">
                        <div class="flex items-center flex-shrink-0 px-8 mb-10">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-emerald-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-xl mr-4">
                                <i class="fas fa-wheat-awn"></i>
                            </div>
                            <span class="text-2xl font-black text-white tracking-tight">AgriCare</span>
                        </div>
                        <nav class="mt-5 flex-1 px-4 space-y-2">
                            <button onclick="switchTab('overview')" id="nav-overview" class="nav-item active-nav text-white group flex items-center px-4 py-3.5 text-sm font-bold rounded-2xl w-full transition-all text-left">
                                <i class="fas fa-chart-line mr-4 h-5 w-5 text-emerald-400"></i> Dashboard
                            </button>
                            <button onclick="switchTab('users')" id="nav-users" class="nav-item text-slate-400 hover:bg-white/5 hover:text-white group flex items-center px-4 py-3.5 text-sm font-bold rounded-2xl w-full transition-all text-left">
                                <i class="fas fa-users mr-4 h-5 w-5 text-emerald-400"></i> Farmers
                            </button>
                            <button onclick="switchTab('subsidies')" id="nav-subsidies" class="nav-item text-slate-400 hover:bg-white/5 hover:text-white group flex items-center px-4 py-3.5 text-sm font-bold rounded-2xl w-full transition-all text-left">
                                <i class="fas fa-hand-holding-heart mr-4 h-5 w-5 text-emerald-400"></i> Subsidies
                            </button>
                            <button onclick="switchTab('market')" id="nav-market" class="nav-item text-slate-400 hover:bg-white/5 hover:text-white group flex items-center px-4 py-3.5 text-sm font-bold rounded-2xl w-full transition-all text-left">
                                <i class="fas fa-store mr-4 h-5 w-5 text-emerald-400"></i> Market
                            </button>
                            <button onclick="switchTab('pesticides')" id="nav-pesticides" class="nav-item text-slate-400 hover:bg-white/5 hover:text-white group flex items-center px-4 py-3.5 text-sm font-bold rounded-2xl w-full transition-all text-left">
                                <i class="fas fa-vial mr-4 h-5 w-5 text-emerald-400"></i> Pesticides
                            </button>

                        </nav>
                    </div>
                    <div class="flex-shrink-0 flex p-6 pb-10 relative z-10">
                        <a href="../frontend/login.php" class="flex items-center w-full group p-4 bg-red-500/10 hover:bg-red-500/20 rounded-2xl transition-all border border-red-500/20">
                            <i class="fas fa-sign-out-alt h-5 w-5 text-red-400 mr-4"></i>
                            <span class="text-xs font-black text-white group-hover:text-red-200 uppercase tracking-widest leading-none">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-0 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-slate-200 rounded-full blur-[150px] -mr-64 -mt-64 z-0"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-emerald-100/50 rounded-full blur-[130px] -ml-48 -mb-48 z-0"></div>

            <!-- Header -->
            <header class="relative z-10 bg-white/60 backdrop-blur-2xl border-b border-slate-200 px-8 py-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                   <h2 id="page-title" class="text-2xl font-black text-slate-900 tracking-tight">Dashboard</h2>
                   <p id="page-subtitle" class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Admin Console</p>
                </div>
                <div class="flex flex-wrap items-center gap-4 lg:gap-6">
                    <div class="flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-widest pulse-animation">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> System Live
                    </div>
                    <button class="flex items-center gap-4 p-2 pr-6 glass-card rounded-2xl border border-white shadow-sm hover:translate-y-[-2px] transition-all group">
                        <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                            <span id="header-initials">AD</span>
                        </div>
                        <div class="text-left hidden sm:block">
                            <p id="header-name" class="text-xs font-black text-slate-900 leading-none">Admin</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Super Admin</p>
                        </div>
                    </button>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 relative overflow-y-auto custom-scrollbar focus:outline-none p-8 z-10 space-y-8">

                <!-- Overview Tab -->
                <div id="tab-overview" class="tab-content active space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                           <div class="flex items-center justify-between mb-4">
                               <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center text-sky-600">
                                   <i class="fas fa-users text-xl"></i>
                               </div>
                               <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full uppercase tracking-widest">Live</span>
                           </div>
                           <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-1">Total Farmers</h4>
                           <p class="text-3xl font-black text-slate-900" id="stat-farmers">—</p>
                           <p class="text-xs text-slate-500 mt-2">Active registered users</p>
                        </div>
                        <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                           <div class="flex items-center justify-between mb-4">
                               <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                                   <i class="fas fa-hand-holding-heart text-xl"></i>
                               </div>
                               <span class="text-[9px] font-black text-slate-400 bg-slate-50 px-2 py-1 rounded-full uppercase tracking-widest">Active</span>
                           </div>
                           <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-1">Subsidies</h4>
                           <p class="text-3xl font-black text-slate-900" id="stat-subsidies">—</p>
                           <p class="text-xs text-slate-500 mt-2">Government schemes</p>
                        </div>
                        <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                           <div class="flex items-center justify-between mb-4">
                               <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                                   <i class="fas fa-store text-xl"></i>
                               </div>
                               <span class="text-[9px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-full uppercase tracking-widest">Markets</span>
                           </div>
                           <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-1">Market Coverage</h4>
                           <p class="text-3xl font-black text-slate-900" id="stat-markets">—</p>
                           <p class="text-xs text-slate-500 mt-2">States/districts covered</p>
                        </div>
                        <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                           <div class="flex items-center justify-between mb-4">
                               <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                                   <i class="fas fa-seedling text-xl"></i>
                               </div>
                               <span class="text-[9px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-full uppercase tracking-widest">Scans</span>
                           </div>
                           <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-1">Crop Scans</h4>
                           <p class="text-3xl font-black text-slate-900" id="stat-scans">—</p>
                           <p class="text-xs text-slate-500 mt-2">Hectares monitored</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-4 px-2">
                                <h3 class="text-xl font-black text-slate-900 tracking-tight">Recent Farmer Registrations</h3>
                                <a href="admin_users.php" class="text-xs font-bold text-emerald-600 uppercase tracking-widest hover:underline">View All →</a>
                            </div>
                            <div class="glass-card rounded-2xl border border-white overflow-hidden shadow-lg">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-slate-50/50 border-b border-slate-100">
                                        <tr>
                                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Farmer</th>
                                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">District</th>
                                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Phone</th>
                                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recent-users" class="divide-y divide-slate-100">
                                        <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400"><i class="fas fa-spinner fa-spin"></i></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                                <h4 class="text-lg font-black text-slate-900 mb-4 tracking-tight">Quick Actions</h4>
                                <div class="space-y-2">
                                    <button type="button" onclick="switchTab('users')" class="block w-full text-left p-3 bg-sky-50 hover:bg-sky-100 rounded-xl text-sky-700 font-bold text-xs transition-all">
                                        <i class="fas fa-users mr-2"></i> Manage Farmers
                                    </button>
                                    <button type="button" onclick="switchTab('subsidies')" class="block w-full text-left p-3 bg-emerald-50 hover:bg-emerald-100 rounded-xl text-emerald-700 font-bold text-xs transition-all">
                                        <i class="fas fa-hand-holding-heart mr-2"></i> Subsidies
                                    </button>
                                    <button type="button" onclick="switchTab('market')" class="block w-full text-left p-3 bg-amber-50 hover:bg-amber-100 rounded-xl text-amber-700 font-bold text-xs transition-all">
                                        <i class="fas fa-store mr-2"></i> Market Data
                                    </button>
                                    <button type="button" onclick="switchTab('pesticides')" class="block w-full text-left p-3 bg-red-50 hover:bg-red-100 rounded-xl text-red-700 font-bold text-xs transition-all">
                                        <i class="fas fa-vial mr-2"></i> Pesticides
                                    </button>
                                </div>
                            </div>

                            <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                                <h4 class="text-lg font-black text-slate-900 mb-4 tracking-tight">System Status</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-600">Database</span>
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded-full">Connected</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-600">API Server</span>
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded-full">Running</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-600">Data Sync</span>
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded-full">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Tab -->
                <div id="tab-users" class="tab-content space-y-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Farmer Management</h3>
                        <span id="users-total-count" class="self-start px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl font-bold text-xs">0 Farmers</span>
                    </div>
                    <div class="glass-card p-6 rounded-2xl border border-white shadow-lg">
                        <div class="flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
                            <div class="relative flex-1 max-w-xl">
                                <i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input id="users-search" type="text" placeholder="Search farmer, phone, district, city..." class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            </div>
                            <select id="users-district-filter" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 min-w-[220px]">
                                <option value="">All Districts</option>
                            </select>
                        </div>
                    </div>
                    <div class="glass-card rounded-2xl border border-white overflow-hidden shadow-lg">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Farmer</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Phone</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">District</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">City</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Language</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Joined</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table" class="divide-y divide-slate-100">
                                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-400"><i class="fas fa-spinner fa-spin"></i></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Subsidies Tab -->
                <div id="tab-subsidies" class="tab-content space-y-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Subsidy Programs</h3>
                        <div class="flex flex-wrap items-center gap-3">
                            <span id="subsidies-total-count" class="self-start px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl font-bold text-xs">0 Schemes</span>
                            <button type="button" onclick="openSubsidyModal()" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold text-xs hover:bg-emerald-700 transition-all">
                                <i class="fas fa-plus mr-2"></i> Add Subsidy
                            </button>
                        </div>
                    </div>
                    <div class="glass-card p-6 rounded-2xl border border-white shadow-lg">
                        <div class="flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
                            <div class="relative flex-1 max-w-xl">
                                <i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input id="subsidies-search" type="text" placeholder="Search subsidy name or description..." class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            </div>
                            <select id="subsidies-category-filter" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 min-w-[220px]">
                                <option value="">All Categories</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="glass-card p-5 rounded-2xl border border-white shadow-lg">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Categories</p>
                            <p id="subsidies-stats-categories" class="text-3xl font-black text-slate-900">0</p>
                        </div>
                        <div class="glass-card p-5 rounded-2xl border border-white shadow-lg">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Recently Updated</p>
                            <p id="subsidies-stats-recent" class="text-3xl font-black text-slate-900">0</p>
                        </div>
                        <div class="glass-card p-5 rounded-2xl border border-white shadow-lg">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Latest Update</p>
                            <p id="subsidies-stats-latest" class="text-sm font-black text-slate-900 pt-2">-</p>
                        </div>
                    </div>
                    <div class="glass-card rounded-2xl border border-white overflow-hidden shadow-lg">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Scheme</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Category</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Benefit</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Apply</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Updated</th>
                                    </tr>
                                </thead>
                                <tbody id="subsidies-table" class="divide-y divide-slate-100">
                                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400"><i class="fas fa-spinner fa-spin"></i></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Market Tab -->
                <div id="tab-market" class="tab-content space-y-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Market Intelligence</h3>
                        <span id="market-total-count" class="self-start px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl font-bold text-xs">0 Records</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="glass-card p-5 rounded-2xl border border-white shadow-lg">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Markets</p>
                            <p id="market-stats-markets" class="text-3xl font-black text-slate-900">0</p>
                        </div>
                        <div class="glass-card p-5 rounded-2xl border border-white shadow-lg">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Commodities</p>
                            <p id="market-stats-commodities" class="text-3xl font-black text-slate-900">0</p>
                        </div>
                        <div class="glass-card p-5 rounded-2xl border border-white shadow-lg">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Districts</p>
                            <p id="market-stats-districts" class="text-3xl font-black text-slate-900">0</p>
                        </div>
                    </div>
                    <div class="glass-card p-6 rounded-2xl border border-white shadow-lg">
                        <div class="flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
                            <div class="relative flex-1 max-w-xl">
                                <i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input id="market-search" type="text" placeholder="Search commodity, market, district..." class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            </div>
                            <select id="market-district-filter" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 min-w-[220px]">
                                <option value="">All Districts</option>
                            </select>
                        </div>
                        <p id="market-sync-status" class="mt-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Syncing market data...</p>
                    </div>
                    <div class="glass-card rounded-2xl border border-white overflow-hidden shadow-lg">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Commodity</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Market</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">District</th>
                                        <th class="py-4 px-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Min</th>
                                        <th class="py-4 px-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Max</th>
                                        <th class="py-4 px-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Modal</th>
                                        <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                                    </tr>
                                </thead>
                                <tbody id="market-table" class="divide-y divide-slate-100">
                                    <tr><td colspan="7" class="px-6 py-10 text-center text-slate-400"><i class="fas fa-spinner fa-spin"></i></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pesticides Tab -->
                <div id="tab-pesticides" class="tab-content space-y-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Pesticide Management</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="glass-card p-5 rounded-2xl border border-white shadow-lg">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Mappings</p>
                            <p id="pesticide-stats-mappings" class="text-3xl font-black text-slate-900">0</p>
                        </div>
                        <div class="glass-card p-5 rounded-2xl border border-white shadow-lg">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Unique Pests</p>
                            <p id="pesticide-stats-pests" class="text-3xl font-black text-slate-900">0</p>
                        </div>
                        <div class="glass-card p-5 rounded-2xl border border-white shadow-lg">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">High Effectiveness</p>
                            <p id="pesticide-stats-high" class="text-3xl font-black text-slate-900">0</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                            <div class="glass-card rounded-2xl border border-white overflow-hidden shadow-lg">
                                <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <h4 class="text-lg font-black text-slate-900">Pest-to-Pesticide Mapping</h4>
                                    <button onclick="openPesticideModal()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition-all">
                                        <i class="fas fa-plus mr-2"></i> Add Mapping
                                    </button>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-slate-50 border-b border-slate-100">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Pest Name</th>
                                                <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Pesticide</th>
                                                <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Target Pests</th>
                                                <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Effectiveness</th>
                                                <th class="px-6 py-3 text-center text-[9px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pesticide-table" class="divide-y divide-slate-100">
                                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400"><i class="fas fa-spinner fa-spin"></i></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                                <h4 class="text-lg font-black text-slate-900 mb-4">Pesticide Registry</h4>
                                <div class="space-y-2">
                                    <button onclick="openPesticideRegistryModal()" class="w-full p-3 bg-emerald-50 hover:bg-emerald-100 rounded-xl text-emerald-700 font-bold text-xs transition-all">
                                        <i class="fas fa-plus mr-2"></i> Add Pesticide
                                    </button>
                                    <div id="pesticide-count" class="p-3 bg-slate-50 rounded-xl text-center">
                                        <p class="text-2xl font-black text-slate-900">—</p>
                                        <p class="text-xs text-slate-500 mt-1">Total Pesticides</p>
                                    </div>
                                    <div id="pesticide-price-band" class="p-3 bg-slate-50 rounded-xl text-center">
                                        <p class="text-sm font-black text-slate-900">-</p>
                                        <p class="text-xs text-slate-500 mt-1">Latest Price Range</p>
                                    </div>
                                </div>
                            </div>

                            <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                                <h4 class="text-lg font-black text-slate-900 mb-4">Pest Categories</h4>
                                <div id="pest-categories" class="space-y-2">
                                    <div class="p-2 bg-slate-50 rounded-lg text-xs text-slate-600"><i class="fas fa-spinner fa-spin mr-2"></i>Loading...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Tab -->
                <div id="tab-analytics" class="tab-content space-y-8">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Analytics & Insights</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                           <div class="flex items-center justify-between mb-4">
                               <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center text-sky-600">
                                   <i class="fas fa-users text-xl"></i>
                               </div>
                               <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full uppercase tracking-widest">Live</span>
                           </div>
                           <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-1">Total Farmers</h4>
                           <p class="text-3xl font-black text-slate-900" id="analytics-farmers">—</p>
                        </div>
                        <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                           <div class="flex items-center justify-between mb-4">
                               <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                                   <i class="fas fa-hand-holding-heart text-xl"></i>
                               </div>
                               <span class="text-[9px] font-black text-slate-400 bg-slate-50 px-2 py-1 rounded-full uppercase tracking-widest">Active</span>
                           </div>
                           <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-1">Subsidies</h4>
                           <p class="text-3xl font-black text-slate-900" id="analytics-subsidies">—</p>
                        </div>
                        <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                           <div class="flex items-center justify-between mb-4">
                               <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                                   <i class="fas fa-store text-xl"></i>
                               </div>
                               <span class="text-[9px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-full uppercase tracking-widest">Markets</span>
                           </div>
                           <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-1">Market Coverage</h4>
                           <p class="text-3xl font-black text-slate-900" id="analytics-markets">—</p>
                        </div>
                        <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                           <div class="flex items-center justify-between mb-4">
                               <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                                   <i class="fas fa-seedling text-xl"></i>
                               </div>
                               <span class="text-[9px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-full uppercase tracking-widest">Scans</span>
                           </div>
                           <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-1">Crop Scans</h4>
                           <p class="text-3xl font-black text-slate-900" id="analytics-scans">—</p>
                        </div>
                    </div>
                    <div class="glass-card rounded-2xl border border-white overflow-hidden shadow-lg p-6">
                        <h4 class="text-lg font-black text-slate-900 mb-4">Recent Activity</h4>
                        <div class="space-y-3">
                            <div class="flex gap-3 p-3 bg-slate-50 rounded-xl">
                                <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">New Farmer Registered</p>
                                    <p class="text-xs text-slate-400">Latest onboarding activity</p>
                                </div>
                            </div>
                            <div class="flex gap-3 p-3 bg-slate-50 rounded-xl">
                                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Market Data Updated</p>
                                    <p class="text-xs text-slate-400">APMC prices synchronized</p>
                                </div>
                            </div>
                            <div class="flex gap-3 p-3 bg-slate-50 rounded-xl">
                                <div class="w-1.5 h-1.5 bg-amber-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Subsidy Program Added</p>
                                    <p class="text-xs text-slate-400">New government scheme published</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <div id="subsidy-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="glass-card w-full max-w-2xl rounded-3xl shadow-2xl p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-black text-slate-900">Add Subsidy</h3>
                <button type="button" onclick="closeSubsidyModal()" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="subsidy-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Scheme Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Category</label>
                        <input type="text" name="category" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20" required>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"></textarea>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Benefits</label>
                    <textarea name="benefits" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"></textarea>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Eligibility</label>
                    <textarea name="eligibility" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Apply Link</label>
                        <input type="url" name="apply_link" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                            <option value="Live">Live</option>
                            <option value="Active">Active</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeSubsidyModal()" class="px-5 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold text-sm hover:bg-slate-200">Cancel</button>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700">Save Subsidy</button>
                </div>
            </form>
        </div>
    </div>

    <div id="pesticide-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="glass-card w-full max-w-xl rounded-3xl shadow-2xl p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-black text-slate-900">Add Pest Mapping</h3>
                <button type="button" onclick="closeModal('pesticide-modal')" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="pesticide-mapping-form" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Pest Name</label>
                    <input type="text" name="pest_name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Pesticide</label>
                    <select id="mapping-pesticide-id" name="pesticide_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20" required></select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Effectiveness</label>
                    <select name="effectiveness" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="High">High</option>
                        <option value="Moderate">Moderate</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('pesticide-modal')" class="px-5 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold text-sm hover:bg-slate-200">Cancel</button>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700">Save Mapping</button>
                </div>
            </form>
        </div>
    </div>

    <div id="pesticide-registry-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="glass-card w-full max-w-2xl rounded-3xl shadow-2xl p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-black text-slate-900">Add Pesticide</h3>
                <button type="button" onclick="closeModal('pesticide-registry-modal')" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="pesticide-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Brand</label>
                        <input type="text" name="brand" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20" required>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Target Pests</label>
                    <input type="text" name="target_pests" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Price Range</label>
                        <input type="text" name="price_range" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Usage Instructions</label>
                        <input type="text" name="usage_instructions" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('pesticide-registry-modal')" class="px-5 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold text-sm hover:bg-slate-200">Cancel</button>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700">Save Pesticide</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let allUsers = [];
        let allSubsidies = [];
        let allMarketData = [];

        const langMap = { en: 'English', gu: 'Gujarati', hi: 'Hindi' };

        function escHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        }

        function formatDate(value) {
            if (!value) return '-';
            const date = new Date(value);
            return Number.isNaN(date.getTime()) ? escHtml(value) : date.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
        }

        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active-nav'));
            
            // Show selected tab
            document.getElementById('tab-' + tabName).classList.add('active');
            document.getElementById('nav-' + tabName).classList.add('active-nav');
            
            // Update header
            const titles = {
                'overview': 'Dashboard',
                'users': 'Farmer Management',
                'subsidies': 'Subsidy Programs',
                'market': 'Market Intelligence',
                'pesticides': 'Pesticide Management',
                'analytics': 'Analytics & Insights'
            };
            document.getElementById('page-title').textContent = titles[tabName] || 'Dashboard';
        }

        async function loadDashboardData() {
            try {
                const res = await fetch('../backend/admin_stats_api.php');
                const data = await res.json();
                
                // Overview tab
                document.getElementById('stat-farmers').textContent = data.farmers.toLocaleString('en-IN');
                document.getElementById('stat-subsidies').textContent = data.subsidies.toLocaleString('en-IN');
                document.getElementById('stat-markets').textContent = data.markets.toLocaleString('en-IN');
                document.getElementById('stat-scans').textContent = data.scans.toLocaleString('en-IN');
                
                // Analytics tab
                document.getElementById('analytics-farmers').textContent = data.farmers.toLocaleString('en-IN');
                document.getElementById('analytics-subsidies').textContent = data.subsidies.toLocaleString('en-IN');
                document.getElementById('analytics-markets').textContent = data.markets.toLocaleString('en-IN');
                document.getElementById('analytics-scans').textContent = data.scans.toLocaleString('en-IN');
                
                // Render recent users
                const tbody = document.getElementById('recent-users');
                if (data.recentUsers && data.recentUsers.length > 0) {
                    tbody.innerHTML = data.recentUsers.map(u => `
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 font-bold text-xs">${escHtml((u.name || '?').charAt(0))}</div>
                                    <span class="text-sm font-bold text-slate-800">${escHtml(u.name || '-')}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-500 font-medium">${u.district || '—'}</td>
                            <td class="px-8 py-5 text-sm text-slate-500 font-medium">—</td>
                            <td class="px-8 py-5 text-sm text-slate-500 font-medium">${formatDate(u.created_at)}</td>
                        </tr>
                    `).join('');
                    Array.from(tbody.querySelectorAll('tr')).forEach((row, index) => {
                        const cells = row.querySelectorAll('td');
                        if (cells[1]) cells[1].textContent = data.recentUsers[index].district || '-';
                        if (cells[2]) cells[2].textContent = data.recentUsers[index].phone || '-';
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">No recent farmers found</td></tr>';
                }
            } catch (e) {
                console.error('Error loading dashboard:', e);
            }
        }

        async function loadUsersData() {
            try {
                const res = await fetch('../backend/admin_users_api.php');
                const data = await res.json();
                allUsers = Array.isArray(data.users) ? data.users : [];

                const districts = [...new Set(allUsers.map(user => user.district).filter(Boolean))].sort();
                document.getElementById('users-district-filter').innerHTML = '<option value="">All Districts</option>' + districts.map(d => `<option value="${escHtml(d)}">${escHtml(d)}</option>`).join('');

                renderUsersTable(allUsers);
            } catch (e) {
                console.error('Error loading users:', e);
                document.getElementById('users-table').innerHTML = '<tr><td colspan="6" class="px-6 py-10 text-center text-red-400">Failed to load farmer data</td></tr>';
            }
        }

        function renderUsersTable(users) {
            document.getElementById('users-total-count').textContent = `${users.length} Farmer${users.length === 1 ? '' : 's'}`;
            const tbody = document.getElementById('users-table');

            if (!users.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-10 text-center text-slate-400">No farmers found</td></tr>';
                return;
            }

            tbody.innerHTML = users.map(user => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-xs">${escHtml((user.name || '?').charAt(0))}</div>
                            <div>
                                <p class="font-bold text-slate-800">${escHtml(user.name || '-')}</p>
                                <p class="text-[11px] text-slate-400">${escHtml(user.email || '-')}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">${escHtml(user.phone || '-')}</td>
                    <td class="px-6 py-4 text-slate-600">${escHtml(user.district || '-')}</td>
                    <td class="px-6 py-4 text-slate-600">${escHtml(user.city || '-')}</td>
                    <td class="px-6 py-4 text-slate-600">${escHtml(langMap[user.pref_lang] || user.pref_lang || '-')}</td>
                    <td class="px-6 py-4 text-slate-600">${formatDate(user.created_at)}</td>
                </tr>
            `).join('');
        }

        function filterUsersTable() {
            const query = document.getElementById('users-search').value.toLowerCase();
            const district = document.getElementById('users-district-filter').value;

            renderUsersTable(allUsers.filter(user => {
                const matchesQuery = !query || [user.name, user.email, user.phone, user.district, user.city].some(value => String(value || '').toLowerCase().includes(query));
                const matchesDistrict = !district || user.district === district;
                return matchesQuery && matchesDistrict;
            }));
        }

        async function loadSubsidiesData() {
            try {
                const res = await fetch('../backend/get_subsidies.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ category: 'All', search: '' })
                });
                const data = await res.json();
                allSubsidies = Array.isArray(data) ? data : [];

                const categories = [...new Set(allSubsidies.map(item => item.category).filter(Boolean))].sort();
                document.getElementById('subsidies-category-filter').innerHTML = '<option value="">All Categories</option>' + categories.map(category => `<option value="${escHtml(category)}">${escHtml(category)}</option>`).join('');

                renderSubsidiesTable(allSubsidies);
            } catch (e) {
                console.error('Error loading subsidies:', e);
                document.getElementById('subsidies-table').innerHTML = '<tr><td colspan="5" class="px-6 py-10 text-center text-red-400">Failed to load subsidy data</td></tr>';
            }
        }

        function renderSubsidiesTable(items) {
            document.getElementById('subsidies-total-count').textContent = `${items.length} Scheme${items.length === 1 ? '' : 's'}`;
            const tbody = document.getElementById('subsidies-table');

            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">No subsidies found</td></tr>';
                return;
            }

            tbody.innerHTML = items.map(item => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-800">${escHtml(item.name || '-')}</td>
                    <td class="px-6 py-4"><span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold">${escHtml(item.category || '-')}</span></td>
                    <td class="px-6 py-4 text-slate-600">${escHtml(item.benefits || item.description || '-')}</td>
                    <td class="px-6 py-4 text-slate-600">${item.apply_link ? `<a href="${escHtml(item.apply_link)}" target="_blank" rel="noopener" class="font-bold text-emerald-700 hover:underline">Open Link</a>` : '-'}</td>
                    <td class="px-6 py-4 text-slate-600">${formatDate(item.last_updated)}</td>
                </tr>
            `).join('');

            document.getElementById('subsidies-stats-categories').textContent = new Set(items.map(item => item.category).filter(Boolean)).size.toLocaleString('en-IN');
            document.getElementById('subsidies-stats-recent').textContent = items.filter(item => {
                if (!item.last_updated) return false;
                const ts = new Date(item.last_updated).getTime();
                return !Number.isNaN(ts) && (Date.now() - ts) <= (7 * 24 * 60 * 60 * 1000);
            }).length.toLocaleString('en-IN');

            const latestItem = [...items].sort((a, b) => new Date(b.last_updated || 0) - new Date(a.last_updated || 0))[0];
            document.getElementById('subsidies-stats-latest').textContent = latestItem ? formatDate(latestItem.last_updated) : '-';
        }

        function filterSubsidiesTable() {
            const query = document.getElementById('subsidies-search').value.toLowerCase();
            const category = document.getElementById('subsidies-category-filter').value;

            renderSubsidiesTable(allSubsidies.filter(item => {
                const matchesQuery = !query || [item.name, item.description, item.name_gu, item.name_hi, item.description_gu, item.description_hi].some(value => String(value || '').toLowerCase().includes(query));
                const matchesCategory = !category || item.category === category;
                return matchesQuery && matchesCategory;
            }));
        }

        async function loadMarketModuleData() {
            try {
                const res = await fetch('../backend/get_market.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ districts: [], markets: [], commodities: [] })
                });
                const data = await res.json();
                allMarketData = Array.isArray(data) ? data : (Array.isArray(data.rows) ? data.rows : []);

                const districts = [...new Set(allMarketData.map(item => item.district).filter(Boolean))].sort();
                document.getElementById('market-district-filter').innerHTML = '<option value="">All Districts</option>' + districts.map(d => `<option value="${escHtml(d)}">${escHtml(d)}</option>`).join('');

                updateMarketStats(allMarketData);
                renderMarketTable(allMarketData);

                const targetDate = data.target_date || null;
                const syncLabel = targetDate === data.today
                    ? `Showing today's market data • ${formatDate(targetDate)}`
                    : `Showing latest available market data • ${formatDate(targetDate)}${data.today ? ` • today is ${formatDate(data.today)}` : ''}`;
                document.getElementById('market-sync-status').textContent = data.synced_at
                    ? `${syncLabel} • last synced ${formatDate(data.synced_at)}`
                    : syncLabel;
            } catch (e) {
                console.error('Error loading market data:', e);
                document.getElementById('market-table').innerHTML = '<tr><td colspan="7" class="px-6 py-10 text-center text-red-400">Failed to load market data</td></tr>';
                document.getElementById('market-sync-status').textContent = 'Market sync failed';
            }
        }

        function updateMarketStats(items) {
            document.getElementById('market-total-count').textContent = `${items.length} Record${items.length === 1 ? '' : 's'}`;
            document.getElementById('market-stats-markets').textContent = new Set(items.map(item => item.market).filter(Boolean)).size.toLocaleString('en-IN');
            document.getElementById('market-stats-commodities').textContent = new Set(items.map(item => item.commodity).filter(Boolean)).size.toLocaleString('en-IN');
            document.getElementById('market-stats-districts').textContent = new Set(items.map(item => item.district).filter(Boolean)).size.toLocaleString('en-IN');
        }

        function renderMarketTable(items) {
            const tbody = document.getElementById('market-table');

            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-10 text-center text-slate-400">No market records found</td></tr>';
                return;
            }

            tbody.innerHTML = items.map(item => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-800">${escHtml(item.commodity || '-')}</td>
                    <td class="px-6 py-4 text-slate-600">${escHtml(item.market || '-')}</td>
                    <td class="px-6 py-4 text-slate-600">${escHtml(item.district || '-')}</td>
                    <td class="px-6 py-4 text-right text-slate-600">${Number(item.min || 0).toLocaleString('en-IN')}</td>
                    <td class="px-6 py-4 text-right text-slate-600">${Number(item.max || 0).toLocaleString('en-IN')}</td>
                    <td class="px-6 py-4 text-right text-slate-600">${Number(item.modal || 0).toLocaleString('en-IN')}</td>
                    <td class="px-6 py-4 text-slate-600">${formatDate(item.arrival_date)}</td>
                </tr>
            `).join('');
        }

        function filterMarketTable() {
            const query = document.getElementById('market-search').value.toLowerCase();
            const district = document.getElementById('market-district-filter').value;
            const filtered = allMarketData.filter(item => {
                const matchesQuery = !query || [item.commodity, item.market, item.district].some(value => String(value || '').toLowerCase().includes(query));
                const matchesDistrict = !district || item.district === district;
                return matchesQuery && matchesDistrict;
            });

            updateMarketStats(filtered);
            renderMarketTable(filtered);
        }

        function openSubsidyModal() {
            document.getElementById('subsidy-modal').classList.remove('hidden');
        }

        function closeSubsidyModal() {
            document.getElementById('subsidy-modal').classList.add('hidden');
        }

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        async function submitSubsidyForm(event) {
            event.preventDefault();
            const form = event.target;
            const payload = Object.fromEntries(new FormData(form).entries());

            const res = await fetch('../backend/admin_subsidies_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();

            if (!res.ok || !data.success) {
                alert(data.message || 'Failed to save subsidy.');
                return;
            }

            form.reset();
            closeSubsidyModal();
            await loadSubsidiesData();
            await loadDashboardData();
        }

        // Pesticide Management Functions
        async function loadPesticideData() {
            try {
                const res = await fetch('../backend/admin_pesticides_api.php');
                const data = await res.json();
                const mappingSelect = document.getElementById('mapping-pesticide-id');
                if (mappingSelect) {
                    mappingSelect.innerHTML = (data.pesticides || []).map(p => `<option value="${p.id}">${p.brand} (${p.name})</option>`).join('');
                }
                
                const tbody = document.getElementById('pesticide-table');
                if (data.mappings && data.mappings.length > 0) {
                    tbody.innerHTML = data.mappings.map(m => `
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-900">${m.pest_name}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <p class="font-bold text-slate-900">${m.brand} (${m.name})</p>
                                <p class="text-xs text-slate-500">${m.price_range || '-'}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">${m.target_pests || '-'}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full">${m.effectiveness}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="deletePesticideMapping(${m.mapping_id})" class="text-slate-400 hover:text-red-500 transition-colors">
                                    <i class="fas fa-trash-can text-sm"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No mappings found</td></tr>';
                }

                document.getElementById('pesticide-count').innerHTML = `
                    <p class="text-2xl font-black text-slate-900">${data.pesticides ? data.pesticides.length : 0}</p>
                    <p class="text-xs text-slate-500 mt-1">Total Pesticides</p>
                `;
                const latestPrice = data.mappings && data.mappings.length > 0 ? (data.mappings[0].price_range || '-') : '-';
                document.getElementById('pesticide-price-band').innerHTML = `
                    <p class="text-sm font-black text-slate-900">${latestPrice}</p>
                    <p class="text-xs text-slate-500 mt-1">Latest Price Range</p>
                `;

                const pests = [...new Set(data.mappings.map(m => m.pest_name))];
                document.getElementById('pesticide-stats-mappings').textContent = (data.mappings ? data.mappings.length : 0).toLocaleString('en-IN');
                document.getElementById('pesticide-stats-pests').textContent = pests.length.toLocaleString('en-IN');
                document.getElementById('pesticide-stats-high').textContent = (data.mappings || []).filter(m => String(m.effectiveness || '').toLowerCase() === 'high').length.toLocaleString('en-IN');
                document.getElementById('pest-categories').innerHTML = pests.length > 0 
                    ? pests.map(p => `<div class="p-2 bg-slate-50 rounded-lg text-xs text-slate-600"><i class="fas fa-bug mr-2"></i>${p}</div>`).join('')
                    : '<div class="p-2 bg-slate-50 rounded-lg text-xs text-slate-400">No pests mapped</div>';
            } catch (e) {
                console.error('Error loading pesticide data:', e);
            }
        }

        function openPesticideModal() {
            openModal('pesticide-modal');
        }

        function openPesticideRegistryModal() {
            openModal('pesticide-registry-modal');
        }

        async function submitPesticideForm(event) {
            event.preventDefault();
            const form = event.target;
            const payload = Object.fromEntries(new FormData(form).entries());
            payload.action = 'add_pesticide';

            const res = await fetch('../backend/admin_pesticides_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();

            if (!res.ok || data.status !== 'success') {
                alert(data.message || 'Failed to save pesticide.');
                return;
            }

            form.reset();
            closeModal('pesticide-registry-modal');
            await loadPesticideData();
        }

        async function submitPesticideMappingForm(event) {
            event.preventDefault();
            const form = event.target;
            const payload = Object.fromEntries(new FormData(form).entries());
            payload.action = 'add_mapping';

            const res = await fetch('../backend/admin_pesticides_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();

            if (!res.ok || data.status !== 'success') {
                alert(data.message || 'Failed to save mapping.');
                return;
            }

            form.reset();
            closeModal('pesticide-modal');
            await loadPesticideData();
        }

        async function deletePesticideMapping(id) {
            if (confirm('Delete this mapping?')) {
                const res = await fetch('../backend/admin_pesticides_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_mapping', id })
                });
                const data = await res.json();
                if (!res.ok || data.status !== 'success') {
                    alert(data.message || 'Failed to delete mapping.');
                    return;
                }
                await loadPesticideData();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const overviewUsersLink = document.querySelector('a[href="admin_users.php"]');
            if (overviewUsersLink) {
                overviewUsersLink.addEventListener('click', function(event) {
                    event.preventDefault();
                    switchTab('users');
                });
            }

            document.getElementById('users-search').addEventListener('input', filterUsersTable);
            document.getElementById('users-district-filter').addEventListener('change', filterUsersTable);
            document.getElementById('subsidies-search').addEventListener('input', filterSubsidiesTable);
            document.getElementById('subsidies-category-filter').addEventListener('change', filterSubsidiesTable);
            document.getElementById('market-search').addEventListener('input', filterMarketTable);
            document.getElementById('market-district-filter').addEventListener('change', filterMarketTable);
            document.getElementById('subsidy-form').addEventListener('submit', submitSubsidyForm);
            document.getElementById('pesticide-form').addEventListener('submit', submitPesticideForm);
            document.getElementById('pesticide-mapping-form').addEventListener('submit', submitPesticideMappingForm);

            loadDashboardData();
            loadUsersData();
            loadSubsidiesData();
            loadMarketModuleData();
            loadPesticideData();
            setInterval(loadDashboardData, 30000);
            setInterval(loadUsersData, 60000);
            setInterval(loadSubsidiesData, 60000);
            setInterval(loadMarketModuleData, 300000);
            setInterval(loadPesticideData, 60000);
        });
    </script>
</body>
</html>
