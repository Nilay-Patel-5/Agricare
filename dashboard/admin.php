<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | AgriCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
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
            background-color: #e5e7eb;
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden text-gray-800">

    <!-- Admin Sidebar -->
    <aside class="w-72 bg-gray-900 border-r border-gray-800 flex flex-col hidden lg:flex text-gray-300">
        <div class="p-6 border-b border-gray-800 bg-gray-950">
            <a href="../frontend/index.php" class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-white tracking-tight block">AgriCare</span>
                    <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Admin Control</span>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-6">
            <p class="px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4">Core Modules</p>

            <a href="admin.php" class="sidebar-link active flex items-center gap-4 px-6 py-3 border-transparent"
                style="background-color: #064e3b; color: #34d399; border-right: 4px solid #10b981;">
                <i class="fas fa-chart-pie w-5 text-center"></i>
                <span>Overview</span>
            </a>
            <a href="admin_users.php"
                class="sidebar-link flex items-center gap-4 px-6 py-3 hover:bg-gray-800 border-r-4 border-transparent hover:text-white transition-colors">
                <i class="fas fa-users w-5 text-center"></i>
                <span>User Management</span>
            </a>
            <a href="admin_subsidies.php"
                class="sidebar-link flex items-center gap-4 px-6 py-3 hover:bg-gray-800 border-r-4 border-transparent hover:text-white transition-colors">
                <i class="fas fa-hand-holding-dollar w-5 text-center"></i>
                <span>Manage Subsidies</span>
            </a>
            <a href="admin_market.php"
                class="sidebar-link flex items-center gap-4 px-6 py-3 hover:bg-gray-800 border-r-4 border-transparent hover:text-white transition-colors">
                <i class="fas fa-store w-5 text-center"></i>
                <span>Market Control</span>
            </a>
            <a href="admin_analytics.php"
                class="sidebar-link flex items-center gap-4 px-6 py-3 hover:bg-gray-800 border-r-4 border-transparent hover:text-white transition-colors">
                <i class="fas fa-robot w-5 text-center"></i>
                <span>AI Analytics</span>
            </a>
        </nav>

        <div class="p-6 border-t border-gray-800 bg-gray-950">
            <a href="#" onclick="logout()"
                class="flex items-center gap-3 text-gray-500 hover:text-red-500 transition-colors font-bold text-sm">
                <i class="fas fa-sign-out-alt"></i>
                <span>Secure Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Topbar -->
        <header
            class="bg-white border-b border-gray-200 py-4 px-6 lg:px-10 flex justify-between items-center shrink-0 shadow-sm z-10">
            <div class="flex items-center gap-4">
                <button class="lg:hidden text-gray-500 hover:text-emerald-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-2xl font-black text-gray-800 hidden sm:block">Command Center</h2>
            </div>

            <div class="flex items-center gap-6">
                <div class="relative">
                    <i
                        class="fas fa-bell text-gray-400 text-xl hover:text-emerald-600 cursor-pointer transition-colors"></i>
                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                    </span>
                </div>

                <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                    <div class="text-right hidden md:block">
                        <p id="adminNameDisplay" class="text-sm font-bold text-gray-800 leading-tight">System Admin</p>
                        <p class="text-[10px] text-emerald-600 font-black uppercase tracking-widest">Master Access</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center text-white shadow-md">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Widgets -->
        <div class="flex-1 overflow-y-auto px-6 lg:px-10 py-8">
            <div class="mb-8">
                <p class="text-gray-500 font-bold uppercase tracking-widest text-xs mb-1">Today's Briefing</p>
                <h2 class="text-3xl font-black text-gray-900">Platform Overview</h2>
            </div>

            <!-- Stats Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full border-b-4 border-b-blue-500 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 opacity-5 text-9xl text-blue-500"><i class="fas fa-users"></i>
                    </div>
                    <div
                        class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 mb-4 z-10">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-500 uppercase tracking-widest text-xs z-10">Total Farmers</h3>
                    <div class="flex items-baseline gap-2 z-10">
                        <p class="text-3xl font-black text-gray-900 mt-1">15,234</p>
                        <span class="text-xs font-bold text-green-500"><i class="fas fa-arrow-up"></i> 12%</span>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full border-b-4 border-b-emerald-500 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 opacity-5 text-9xl text-emerald-500"><i
                            class="fas fa-hand-holding-dollar"></i></div>
                    <div
                        class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mb-4 z-10">
                        <i class="fas fa-hand-holding-dollar text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-500 uppercase tracking-widest text-xs z-10">Active Subsidies</h3>
                    <div class="flex items-baseline gap-2 z-10">
                        <p class="text-3xl font-black text-gray-900 mt-1">12</p>
                        <span class="text-xs font-bold text-gray-400">Live programs</span>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full border-b-4 border-b-orange-500 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 opacity-5 text-9xl text-orange-500"><i
                            class="fas fa-store"></i></div>
                    <div
                        class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 mb-4 z-10">
                        <i class="fas fa-store text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-500 uppercase tracking-widest text-xs z-10">Market Checkpoints</h3>
                    <div class="flex items-baseline gap-2 z-10">
                        <p class="text-3xl font-black text-gray-900 mt-1">923</p>
                        <span class="text-xs font-bold text-gray-400">Gujarat APMCs</span>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full border-b-4 border-b-purple-500 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 opacity-5 text-9xl text-purple-500"><i
                            class="fas fa-microscope"></i></div>
                    <div
                        class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 mb-4 z-10">
                        <i class="fas fa-microscope text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-500 uppercase tracking-widest text-xs z-10">AI Scans Run</h3>
                    <div class="flex items-baseline gap-2 z-10">
                        <p class="text-3xl font-black text-gray-900 mt-1">45,892</p>
                        <span class="text-xs font-bold text-green-500"><i class="fas fa-arrow-up"></i> 8%</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-black text-gray-800">Recent User Registrations</h3>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Last 24 hours</p>
                    </div>
                    <button
                        class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors shadow-sm">View
                        All Users</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white">
                                <th
                                    class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                    Farmer ID</th>
                                <th
                                    class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                    Name / Phone</th>
                                <th
                                    class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                    District</th>
                                <th
                                    class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                    Verified</th>
                                <th
                                    class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="py-4 px-6 border-b border-gray-50 text-sm font-bold text-gray-600">FRM-9942
                                </td>
                                <td class="py-4 px-6 border-b border-gray-50">
                                    <p
                                        class="text-sm font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">
                                        Rajesh Patel</p>
                                    <p class="text-xs text-gray-500 font-medium">+91 98765 43210</p>
                                </td>
                                <td class="py-4 px-6 border-b border-gray-50 text-sm font-medium text-gray-600">
                                    Ahmedabad</td>
                                <td class="py-4 px-6 border-b border-gray-50">
                                    <span
                                        class="bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md flex items-center w-max gap-1">
                                        <i class="fas fa-check-circle"></i> YES
                                    </span>
                                </td>
                                <td class="py-4 px-6 border-b border-gray-50 text-right">
                                    <button class="text-gray-400 hover:text-emerald-600 transition-colors"><i
                                            class="fas fa-ellipsis-v px-2"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="py-4 px-6 border-b border-gray-50 text-sm font-bold text-gray-600">FRM-9943
                                </td>
                                <td class="py-4 px-6 border-b border-gray-50">
                                    <p
                                        class="text-sm font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">
                                        Amit Shah</p>
                                    <p class="text-xs text-gray-500 font-medium">+91 91234 56789</p>
                                </td>
                                <td class="py-4 px-6 border-b border-gray-50 text-sm font-medium text-gray-600">Surat
                                </td>
                                <td class="py-4 px-6 border-b border-gray-50">
                                    <span
                                        class="bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md flex items-center w-max gap-1">
                                        <i class="fas fa-check-circle"></i> YES
                                    </span>
                                </td>
                                <td class="py-4 px-6 border-b border-gray-50 text-right">
                                    <button class="text-gray-400 hover:text-emerald-600 transition-colors"><i
                                            class="fas fa-ellipsis-v px-2"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="py-4 px-6 border-b border-gray-50 text-sm font-bold text-gray-600">FRM-9944
                                </td>
                                <td class="py-4 px-6 border-b border-gray-50">
                                    <p
                                        class="text-sm font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">
                                        Bhavin Desai</p>
                                    <p class="text-xs text-gray-500 font-medium">+91 99887 76655</p>
                                </td>
                                <td class="py-4 px-6 border-b border-gray-50 text-sm font-medium text-gray-600">Rajkot
                                </td>
                                <td class="py-4 px-6 border-b border-gray-50">
                                    <span
                                        class="bg-orange-100 text-orange-700 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md flex items-center w-max gap-1">
                                        <i class="fas fa-clock"></i> PENDING
                                    </span>
                                </td>
                                <td class="py-4 px-6 border-b border-gray-50 text-right">
                                    <button class="text-gray-400 hover:text-emerald-600 transition-colors"><i
                                            class="fas fa-ellipsis-v px-2"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script>
        function updateAdminInfo() {
            const userData = JSON.parse(sessionStorage.getItem('agricare_user'));
            if (userData && userData.role === 'admin') {
                document.getElementById('adminNameDisplay').innerText = userData.name || 'Admin User';
            } else if (!userData) {
                window.location.href = '../frontend/login.php';
            }
        }

        function logout() {
            sessionStorage.removeItem('agricare_user');
            window.location.href = '../frontend/login.php';
        }

        document.addEventListener('DOMContentLoaded', updateAdminInfo);
    </script>
</body>
</html>