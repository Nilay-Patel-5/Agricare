<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - User Management | AgriCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; }
        .sidebar-link { transition: all 0.3s; }
        .sidebar-link.active { background-color: #ecfdf5; color: #047857; border-right: 4px solid #10b981; font-weight: bold; }
        .sidebar-link:hover:not(.active) { background-color: #e5e7eb; }
    </style>
</head>

<body class="flex h-screen overflow-hidden text-gray-800">

    <!-- Admin Sidebar -->
    <aside class="w-72 bg-gray-900 border-r border-gray-800 flex flex-col hidden lg:flex text-gray-300">
        <div class="p-6 border-b border-gray-800 bg-gray-950">
            <a href="../frontend/index.php" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg">
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
            
            <a href="admin.php" class="sidebar-link flex items-center gap-4 px-6 py-3 hover:bg-gray-800 border-r-4 border-transparent hover:text-white transition-colors">
                <i class="fas fa-chart-pie w-5 text-center"></i>
                <span>Overview</span>
            </a>
            <a href="admin_users.php" class="sidebar-link active flex items-center gap-4 px-6 py-3 border-transparent" style="background-color: #064e3b; color: #34d399; border-right: 4px solid #10b981;">
                <i class="fas fa-users w-5 text-center"></i>
                <span>User Management</span>
            </a>
            <a href="admin_subsidies.php" class="sidebar-link flex items-center gap-4 px-6 py-3 hover:bg-gray-800 border-r-4 border-transparent hover:text-white transition-colors">
                <i class="fas fa-hand-holding-dollar w-5 text-center"></i>
                <span>Manage Subsidies</span>
            </a>
            <a href="admin_market.php" class="sidebar-link flex items-center gap-4 px-6 py-3 hover:bg-gray-800 border-r-4 border-transparent hover:text-white transition-colors">
                <i class="fas fa-store w-5 text-center"></i>
                <span>Market Control</span>
            </a>
            <a href="admin_analytics.php" class="sidebar-link flex items-center gap-4 px-6 py-3 hover:bg-gray-800 border-r-4 border-transparent hover:text-white transition-colors">
                <i class="fas fa-robot w-5 text-center"></i>
                <span>AI Analytics</span>
            </a>
        </nav>

        <div class="p-6 border-t border-gray-800 bg-gray-950">
            <a href="../frontend/login.php" class="flex items-center gap-3 text-gray-500 hover:text-red-500 transition-colors font-bold text-sm">
                <i class="fas fa-sign-out-alt"></i>
                <span>Secure Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Topbar -->
        <header class="bg-white border-b border-gray-200 py-4 px-6 lg:px-10 flex justify-between items-center shrink-0 shadow-sm z-10">
            <div class="flex items-center gap-4">
                <button class="lg:hidden text-gray-500 hover:text-emerald-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-2xl font-black text-gray-800 hidden sm:block">Command Center</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Notifications and User Profile (same as dashboard) -->
                <div class="relative">
                    <i class="fas fa-bell text-gray-400 text-xl hover:text-emerald-600 cursor-pointer transition-colors"></i>
                </div>
                
                <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-gray-800 leading-tight">System Admin</p>
                        <p class="text-[10px] text-emerald-600 font-black uppercase tracking-widest">Master Access</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center text-white shadow-md">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="flex-1 overflow-y-auto px-6 lg:px-10 py-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <p class="text-gray-500 font-bold uppercase tracking-widest text-xs mb-1">Administration</p>
                    <h2 class="text-3xl font-black text-gray-900">User Management</h2>
                </div>
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold shadow-md transition-colors">
                    + Add New User
                </button>
            </div>

            <!-- Page Specific Content -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[400px] flex items-center justify-center flex-col">
                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 mb-4">
                    <i class="fas fa-users-cog text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">User Directory Placeholder</h3>
                <p class="text-gray-500 max-w-md text-center">Here you can view, edit, suspend, and promote users across the entire AgriCare platform.</p>
            </div>
        </div>
    </main>
</body>
</html>
