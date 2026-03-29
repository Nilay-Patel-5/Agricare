<?php
// dashboard/admin.php - Dashboard Overview
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AgriCare | Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }

        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
        .pulse-animation { animation: pulse 2s infinite; }
    </style>
</head>
<body class="bg-slate-50 overflow-hidden font-sans">
    <div id="progressBar"></div>
    <div class="flex h-screen overflow-hidden">
        
        <?php include '_sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-0 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-slate-200 rounded-full blur-[150px] -mr-64 -mt-64 z-0"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-emerald-100/50 rounded-full blur-[130px] -ml-48 -mb-48 z-0"></div>

            <!-- Header -->
            <header class="relative z-10 bg-white/60 backdrop-blur-2xl border-b border-slate-200 px-8 py-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                   <h2 class="text-2xl font-black text-slate-900 tracking-tight">System Overview</h2>
                   <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Live Data Control</p>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-widest pulse-animation">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> System Live
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 relative overflow-y-auto custom-scrollbar p-8 z-10 space-y-8">

                <!-- Stats Grid -->
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
                       <p class="text-xs text-slate-500 mt-2">Verified registrations</p>
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
                       <p class="text-xs text-slate-500 mt-2">Published schemes</p>
                    </div>

                    <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                       <div class="flex items-center justify-between mb-4">
                           <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                               <i class="fas fa-store text-xl"></i>
                           </div>
                           <span class="text-[9px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-full uppercase tracking-widest">Markets</span>
                       </div>
                       <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-1">Market Data</h4>
                       <p class="text-3xl font-black text-slate-900" id="stat-markets">—</p>
                       <p class="text-xs text-slate-500 mt-2">Regional mandi connectivity</p>
                    </div>

                    <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                       <div class="flex items-center justify-between mb-4">
                           <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                               <i class="fas fa-microscope text-xl"></i>
                           </div>
                           <span class="text-[9px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-full uppercase tracking-widest">AI Scans</span>
                       </div>
                       <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-1">Diagnostics</h4>
                       <p class="text-3xl font-black text-slate-900" id="stat-scans">—</p>
                       <p class="text-xs text-slate-500 mt-2">Pest detections processed</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Recent Activity -->
                    <div class="lg:col-span-2">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-4 px-2">
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">Recent Onboarding</h3>
                            <a href="admin_users.php" class="text-xs font-bold text-emerald-600 uppercase tracking-widest hover:underline hover:text-emerald-700 transition-colors">Farmer Registry →</a>
                        </div>
                        <div class="glass-card rounded-2xl border border-white overflow-hidden shadow-lg">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Farmer</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">District</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Contact</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-users-table" class="divide-y divide-slate-100">
                                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400"><i class="fas fa-circle-notch fa-spin text-xl"></i></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Quick Tools -->
                    <div class="space-y-6">
                        <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                            <h4 class="text-lg font-black text-slate-900 mb-4 tracking-tight">Module Shortcuts</h4>
                            <div class="space-y-2">
                                <a href="admin_users.php" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-emerald-50 rounded-xl text-slate-700 hover:text-emerald-700 font-bold text-xs transition-all border border-transparent hover:border-emerald-100">
                                    <i class="fas fa-user-friends w-5 text-sky-500"></i> Manage Farmer Base
                                </a>
                                <a href="admin_subsidies.php" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-emerald-50 rounded-xl text-slate-700 hover:text-emerald-700 font-bold text-xs transition-all border border-transparent hover:border-emerald-100">
                                    <i class="fas fa-money-bill-transfer w-5 text-emerald-500"></i> Control Subsidies
                                </a>
                                <a href="admin_market.php" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-emerald-50 rounded-xl text-slate-700 hover:text-emerald-700 font-bold text-xs transition-all border border-transparent hover:border-emerald-100">
                                    <i class="fas fa-magnifying-glass-chart w-5 text-amber-500"></i> Market Intelligence
                                </a>
                                <a href="admin_pesticides.php" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-emerald-50 rounded-xl text-slate-700 hover:text-emerald-700 font-bold text-xs transition-all border border-transparent hover:border-emerald-100">
                                    <i class="fas fa-flask-vial w-5 text-red-500"></i> Pesticide Core
                                </a>
                            </div>
                        </div>

                        <div class="glass-card p-6 rounded-2xl shadow-lg border border-white">
                            <h4 class="text-lg font-black text-slate-900 mb-4 tracking-tight">System Status</h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-600">Database</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Operational</span>
                                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full pulse-animation"></div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-600">Market Sync</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Active</span>
                                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-600">AI Model</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Ready</span>
                                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        async function loadOverview() {
            try {
                const res = await fetch('../backend/admin_stats_api.php');
                const data = await res.json();
                
                // Stats
                document.getElementById('stat-farmers').textContent = data.farmers.toLocaleString('en-IN');
                document.getElementById('stat-subsidies').textContent = data.subsidies.toLocaleString('en-IN');
                document.getElementById('stat-markets').textContent = data.markets.toLocaleString('en-IN');
                document.getElementById('stat-scans').textContent = (data.scans || 0).toLocaleString('en-IN');
                
                // Recent Users
                const tbody = document.getElementById('recent-users-table');
                if (data.recentUsers && data.recentUsers.length > 0) {
                    tbody.innerHTML = data.recentUsers.map(u => `
                        <tr class="hover:bg-slate-50 transition-colors border-t border-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 font-black text-[10px] uppercase shadow-sm">
                                        ${(u.name || 'F').charAt(0)}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 leading-tight">${u.name}</p>
                                        <p class="text-[9px] text-slate-400">${u.email || ''}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600 font-bold">${u.district || '—'}</td>
                            <td class="px-6 py-4 text-xs text-slate-400 font-medium">${u.phone || '—'}</td>
                            <td class="px-6 py-4 text-xs text-slate-400 font-medium">
                                ${new Date(u.created_at).toLocaleDateString('en-IN', {day:'2-digit', month:'short'})}
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" class="py-12 text-center text-slate-400">No recent signups</td></tr>';
                }
            } catch (err) {
                console.error('Failed to load stats:', err);
            }
        }
        
        loadOverview();
        setInterval(loadOverview, 300000); // 5 min refresh

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
