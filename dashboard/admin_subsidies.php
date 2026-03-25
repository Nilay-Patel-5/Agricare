<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Subsidies | AgriCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/output.css">

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
            <a href="admin_users.php" class="sidebar-link flex items-center gap-4 px-6 py-3 hover:bg-gray-800 border-r-4 border-transparent hover:text-white transition-colors">
                <i class="fas fa-users w-5 text-center"></i>
                <span>User Management</span>
            </a>
            <a href="admin_subsidies.php" class="sidebar-link active flex items-center gap-4 px-6 py-3 border-transparent" style="background-color: #064e3b; color: #34d399; border-right: 4px solid #10b981;">
                <i class="fas fa-hand-holding-dollar w-5 text-center"></i>
                <span>Manage Subsidies</span>
            </a>
            <a href="admin_market.php" class="sidebar-link flex items-center gap-4 px-6 py-3 hover:bg-gray-800 border-r-4 border-transparent hover:text-white transition-colors">
                <i class="fas fa-store w-5 text-center"></i>
                <span>Market Control</span>
            </a>
            <a href="admin_pesticides.php" class="sidebar-link flex items-center gap-4 px-6 py-3 hover:bg-gray-800 border-r-4 border-transparent hover:text-white transition-colors">
                <i class="fas fa-vial w-5 text-center"></i>
                <span>Pesticides</span>
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
                <!-- Notifications and User Profile -->
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
                    <h2 class="text-3xl font-black text-gray-900">Manage Subsidies</h2>
                </div>
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold shadow-md transition-colors">
                    + Publish Subsidy
                </button>
            </div>

            <!-- Page Specific Content -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.24em]">Government Schemes</p>
                        <h3 class="text-xl font-black text-gray-900 mt-1">Active Subsidy Programs</h3>
                    </div>
                    <div class="flex gap-2">
                        <input type="text" id="searchInput" placeholder="Search schemes..." class="px-4 py-2 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm">
                        <select id="categoryFilter" class="px-4 py-2 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm">
                            <option value="">All Categories</option>
                        </select>
                        <button onclick="loadSubsidies()" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Scheme Name</th>
                                <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Category</th>
                                <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Description</th>
                                <th class="py-4 px-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="py-4 px-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Last Updated</th>
                            </tr>
                        </thead>
                        <tbody id="subsidies-table" class="divide-y divide-gray-100">
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400">
                                    <i class="fas fa-circle-notch fa-spin text-2xl"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

    <script>
        let allSubsidies = [];

        async function loadSubsidies() {
            try {
                const res = await fetch('../backend/get_subsidies.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ category: 'All', search: '' })
                });
                const data = await res.json();
                
                allSubsidies = Array.isArray(data) ? data : [];
                
                // Get unique categories
                const categories = [...new Set(allSubsidies.map(s => s.category).filter(Boolean))];
                const sel = document.getElementById('categoryFilter');
                sel.innerHTML = '<option value="">All Categories</option>' + categories.sort().map(c => `<option value="${c}">${c}</option>`).join('');
                
                renderTable(allSubsidies);
            } catch (e) {
                console.error('Error loading subsidies:', e);
                document.getElementById('subsidies-table').innerHTML = '<tr><td colspan="5" class="py-8 text-center text-red-400">Failed to load subsidy data</td></tr>';
            }
        }

        function renderTable(data) {
            const tbody = document.getElementById('subsidies-table');
            
            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="py-12 text-center text-gray-400">No subsidies available</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(s => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-gray-900 font-medium">${s.name || '—'}</td>
                    <td class="py-4 px-6 text-gray-600">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-full">${s.category || '—'}</span>
                    </td>
                    <td class="py-4 px-6 text-gray-600 max-w-xs truncate">${s.description || '—'}</td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full">Active</span>
                    </td>
                    <td class="py-4 px-6 text-center text-gray-500">${new Date(s.last_updated).toLocaleDateString('en-IN')}</td>
                </tr>
            `).join('');
        }

        // Filter functionality
        document.getElementById('searchInput').addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase();
            const c = document.getElementById('categoryFilter').value;
            
            const filtered = allSubsidies.filter(s => {
                const matchesSearch = (s.name || '').toLowerCase().includes(q) || (s.description || '').toLowerCase().includes(q);
                const matchesCategory = !c || (s.category || '').toLowerCase() === c.toLowerCase();
                return matchesSearch && matchesCategory;
            });
            
            renderTable(filtered);
        });

        document.getElementById('categoryFilter').addEventListener('change', () => {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const c = document.getElementById('categoryFilter').value;
            
            const filtered = allSubsidies.filter(s => {
                const matchesSearch = (s.name || '').toLowerCase().includes(q) || (s.description || '').toLowerCase().includes(q);
                const matchesCategory = !c || (s.category || '').toLowerCase() === c.toLowerCase();
                return matchesSearch && matchesCategory;
            });
            
            renderTable(filtered);
        });

        loadSubsidies();
        
        // Auto-refresh every 60 seconds
        setInterval(loadSubsidies, 60000);
    </script>
</body>
</html>
