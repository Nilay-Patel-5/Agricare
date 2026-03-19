<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Executive Command Center | AgriCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        .premium-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.05), 0 8px 10px -6px rgb(0 0 0 / 0.05);
        }

        .sidebar-link {
            transition: all 0.2s ease-in-out;
            border-radius: 1rem;
            margin: 4px 12px;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2);
        }

        .sidebar-link:not(.active):hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        @keyframes pulse-emerald {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .status-pulse {
            animation: pulse-emerald 2s infinite;
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden text-slate-700 bg-slate-50">

    <!-- Premium Admin Sidebar -->
    <aside class="w-72 bg-slate-950 flex flex-col hidden lg:flex text-slate-400 shrink-0">
        <div class="px-8 py-10">
            <a href="../frontend/index.php" class="flex items-center gap-3 group">
                <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white text-xl shadow-xl shadow-emerald-900/40 group-hover:rotate-12 transition-transform duration-300">
                    <i class="fas fa-leaf"></i>
                </div>
                <div>
                    <span class="text-2xl font-black text-white tracking-tighter block leading-tight">AgriCare</span>
                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest pl-0.5">Admin Elite</span>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scrollbar space-y-1">
            <p class="px-8 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 mt-2">Management</p>

            <a href="admin.php" class="sidebar-link active flex items-center gap-4 px-5 py-3.5">
                <i class="fas fa-chart-line w-5"></i>
                <span class="font-bold text-sm tracking-tight">Executive Summary</span>
            </a>

            <a href="admin_users.php" class="sidebar-link flex items-center gap-4 px-5 py-3.5">
                <i class="fas fa-users-viewfinder w-5"></i>
                <span class="font-bold text-sm tracking-tight">Farmer Registry</span>
            </a>

            <a href="admin_subsidies.php" class="sidebar-link flex items-center gap-4 px-5 py-3.5">
                <i class="fas fa-hand-holding-heart w-5"></i>
                <span class="font-bold text-sm tracking-tight">Subsidy Programs</span>
            </a>

            <a href="admin_market.php" class="sidebar-link flex items-center gap-4 px-5 py-3.5">
                <i class="fas fa-arrow-trend-up w-5"></i>
                <span class="font-bold text-sm tracking-tight">Mandi Intelligence</span>
            </a>

            <p class="px-8 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 mt-8">System</p>

            <a href="admin_analytics.php" class="sidebar-link flex items-center gap-4 px-5 py-3.5">
                <i class="fas fa-brain w-5"></i>
                <span class="font-bold text-sm tracking-tight">AI Diagnostics</span>
            </a>

            <a href="admin_settings.php" class="sidebar-link flex items-center gap-4 px-5 py-3.5">
                <i class="fas fa-sliders w-5"></i>
                <span class="font-bold text-sm tracking-tight">System Settings</span>
            </a>
        </nav>

        <div class="p-8 border-t border-slate-900">
            <div class="bg-slate-900/50 rounded-2xl p-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                        <i class="fas fa-circle-dot animate-pulse"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-white uppercase tracking-tight">Node: MH-01</p>
                        <p class="text-[10px] text-slate-500 font-bold">Latency: 24ms</p>
                    </div>
                </div>
            </div>

            <a href="#" onclick="logout()" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl border-2 border-slate-900 hover:border-red-500/50 hover:text-red-500 transition-all font-black text-[10px] uppercase tracking-widest bg-transparent">
                <i class="fas fa-power-off"></i>
                <span>Secure Termination</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <!-- Topbar -->
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 py-6 px-10 flex justify-between items-center shrink-0 z-30">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="w-2 h-8 bg-emerald-500 rounded-full block"></span>
                    Executive Command Center
                </h2>
            </div>

            <div class="flex items-center gap-8">
                <div class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-full border border-slate-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 status-pulse"></span>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Network Secure</span>
                </div>

                <div class="flex items-center gap-4 pl-8 border-l border-slate-100">
                    <div class="text-right hidden md:block">
                        <p id="adminNameDisplay" class="text-sm font-black text-slate-900 tracking-tight leading-none mb-1">Elite Operator</p>
                        <p class="text-[10px] text-emerald-600 font-black uppercase tracking-[0.1em]">Root Privileges</p>
                    </div>
                    <div class="w-12 h-12 bg-slate-950 rounded-2xl p-0.5 group cursor-pointer overflow-hidden shadow-lg shadow-slate-200">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=0f172a&color=fff" class="w-full h-full rounded-[14px] object-cover group-hover:scale-110 transition-transform" alt="Admin">
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Widgets -->
        <div class="flex-1 overflow-y-auto px-10 py-10 custom-scrollbar">
            <div class="mb-12 flex justify-between items-end">
                <div>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Platform Pulse</h2>
                    <p class="text-slate-500 font-medium">Real-time performance metrics and farmer engagement statistics.</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="window.location.reload()" class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-slate-200 flex items-center justify-center text-slate-400 hover:text-emerald-500 transition-colors">
                        <i class="fas fa-rotate"></i>
                    </button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Farmers -->
                <div class="premium-card p-8 rounded-[2.5rem] relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 text-[10rem] text-slate-50 rotate-12 opacity-50"><i class="fas fa-users-rectangle"></i></div>
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-xl shadow-blue-200">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <h3 class="font-black text-slate-400 uppercase tracking-[0.15em] text-[10px] mb-2">Total Registrations</h3>
                    <div class="flex items-baseline gap-3">
                        <p id="stat-farmers" class="text-4xl font-black text-slate-900 tracking-tight">0</p>
                        <span class="text-[10px] px-2 py-1 bg-blue-50 text-blue-600 font-black rounded-lg">+12%</span>
                    </div>
                </div>

                <!-- Subsidies -->
                <div class="premium-card p-8 rounded-[2.5rem] relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 text-[10rem] text-slate-50 rotate-12 opacity-50"><i class="fas fa-handshake-angle"></i></div>
                    <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-xl shadow-emerald-200">
                        <i class="fas fa-hand-holding-dollar text-xl"></i>
                    </div>
                    <h3 class="font-black text-slate-400 uppercase tracking-[0.15em] text-[10px] mb-2">Active Programs</h3>
                    <div class="flex items-baseline gap-3">
                        <p id="stat-subsidies" class="text-4xl font-black text-slate-900 tracking-tight">0</p>
                        <span class="text-[10px] px-2 py-1 bg-emerald-50 text-emerald-600 font-black rounded-lg">LIVE</span>
                    </div>
                </div>

                <!-- Markets -->
                <div class="premium-card p-8 rounded-[2.5rem] relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 text-[10rem] text-slate-50 rotate-12 opacity-50"><i class="fas fa-building-wheat"></i></div>
                    <div class="w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center text-white mb-6 shadow-xl shadow-orange-200">
                        <i class="fas fa-store text-xl"></i>
                    </div>
                    <h3 class="font-black text-slate-400 uppercase tracking-[0.15em] text-[10px] mb-2">Mandi Sync Nodes</h3>
                    <div class="flex items-baseline gap-3">
                        <p id="stat-markets" class="text-4xl font-black text-slate-900 tracking-tight">0</p>
                        <span class="text-[10px] px-2 py-1 bg-orange-50 text-orange-600 font-black rounded-lg">ON-AIR</span>
                    </div>
                </div>

                <!-- AI Scans -->
                <div class="premium-card p-8 rounded-[2.5rem] relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 text-[10rem] text-slate-50 rotate-12 opacity-50"><i class="fas fa-microscope"></i></div>
                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-xl shadow-indigo-200">
                        <i class="fas fa-robot text-xl"></i>
                    </div>
                    <h3 class="font-black text-slate-400 uppercase tracking-[0.15em] text-[10px] mb-2">Neural Load Scans</h3>
                    <div class="flex items-baseline gap-3">
                        <p id="stat-scans" class="text-4xl font-black text-slate-900 tracking-tight">0</p>
                        <span class="text-[10px] px-2 py-1 bg-indigo-50 text-indigo-600 font-black rounded-lg">GPT-4o</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="premium-card rounded-[2.5rem] overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-white">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Farmer Activity Log</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Real-time Stream</p>
                    </div>
                    <a href="admin_users.php" class="px-6 py-2.5 bg-slate-100 rounded-xl text-xs font-black text-slate-600 hover:bg-emerald-600 hover:text-white transition-all tracking-tight uppercase">Registry Full View</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Deployment ID</th>
                                <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Farmer Profile</th>
                                <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Operational Zone</th>
                                <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">System Role</th>
                                <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Registered</th>
                            </tr>
                        </thead>
                        <tbody id="recentUsersTable" class="divide-y divide-slate-50">
                            <!-- Injected by JS -->
                            <tr>
                                <td colspan="5" class="p-20 text-center"><i class="fas fa-circle-notch fa-spin text-3xl text-emerald-500 mb-4 block mx-auto"></i>
                                    <p class="font-black text-slate-400 uppercase text-[10px] tracking-widest">Accessing Secure Records...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        async function fetchStats() {
            try {
                const res = await fetch('../backend/admin_stats_api.php');
                const data = await res.json();

                document.getElementById('stat-farmers').innerText = data.farmers.toLocaleString();
                document.getElementById('stat-subsidies').innerText = data.subsidies;
                document.getElementById('stat-markets').innerText = data.markets;
                document.getElementById('stat-scans').innerText = data.scans.toLocaleString();

                const tbody = document.getElementById('recentUsersTable');
                if (data.recentUsers.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="p-20 text-center text-slate-300 font-black italic">DATABASE_EMPTY: No recent records found.</td></tr>';
                    return;
                }

                tbody.innerHTML = data.recentUsers.map(u => `
                    <tr class="hover:bg-slate-50 group transition-all duration-300">
                        <td class="py-6 px-8 text-sm font-black text-slate-400">
                            <span class="bg-slate-100 px-3 py-1 rounded-lg">#${u.id.toString().padStart(4, '0')}</span>
                        </td>
                        <td class="py-6 px-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden shrink-0 border-2 border-white shadow-sm">
                                    <img src="https://ui-avatars.com/api/?name=${u.name}&background=f1f5f9&color=64748b" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 group-hover:text-emerald-600 transition-colors">${u.name}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">${u.phone || 'NO_COMMS'}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-6 px-8">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-location-dot text-emerald-500/50 text-xs"></i>
                                <span class="text-sm font-bold text-slate-600">${u.district || 'GLOBAL'}</span>
                            </div>
                        </td>
                        <td class="py-6 px-8">
                            <span class="bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border border-emerald-100 inline-flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                ${u.role.toUpperCase()}
                            </span>
                        </td>
                        <td class="py-6 px-8 text-right">
                            <p class="text-xs font-black text-slate-400">${new Date(u.created_at).toLocaleDateString([], {day:'2-digit', month:'short', year:'numeric'})}</p>
                            <p class="text-[10px] text-slate-300 font-bold">${new Date(u.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</p>
                        </td>
                    </tr>
                `).join('');

            } catch (err) {
                console.error("Critical System Error:", err);
            }
        }

        function updateAdminInfo() {
            const userData = JSON.parse(sessionStorage.getItem('agricare_user'));
            if (userData && userData.role === 'admin') {
                document.getElementById('adminNameDisplay').innerText = userData.name || 'System Operator';
            } else if (!userData) {
                window.location.href = '../frontend/login.php';
            }
        }

        function logout() {
            sessionStorage.removeItem('agricare_user');
            window.location.href = '../frontend/login.php';
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateAdminInfo();
            fetchStats();
        });
    </script>
</body>

</html>