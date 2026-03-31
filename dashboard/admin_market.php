<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Market Control | AgriCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/output.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; }
        .sidebar-link { transition: all 0.3s; }
        .sidebar-link.active { background-color: #ecfdf5; color: #047857; border-right: 4px solid #10b981; font-weight: bold; }
        .sidebar-link:hover:not(.active) { background-color: #e5e7eb; }
        .stat-card { transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    </style>
</head>

<body class="flex h-screen overflow-hidden bg-slate-50 text-slate-700">

    <?php include '_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Topbar -->
        <header class="bg-white border-b border-gray-200 py-4 px-6 lg:px-10 flex justify-between items-center shrink-0 shadow-sm z-10">
            <div class="flex items-center gap-4">
                <button class="lg:hidden text-gray-500 hover:text-emerald-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-2xl font-black text-gray-800 hidden sm:block">Market Control</h2>
            </div>
            
            <div class="flex items-center gap-6">
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
                    <h2 class="text-3xl font-black text-gray-900">Market Intelligence</h2>
                </div>
                <button onclick="loadMarketData()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold shadow-md transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Sync APMC Data
                </button>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="stat-card bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Markets</p>
                            <h3 class="text-3xl font-black text-gray-900" id="total-markets">—</h3>
                        </div>
                        <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600">
                            <i class="fas fa-store text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Commodities Tracked</p>
                            <h3 class="text-3xl font-black text-gray-900" id="total-commodities">—</h3>
                        </div>
                        <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">
                            <i class="fas fa-seedling text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Districts Covered</p>
                            <h3 class="text-3xl font-black text-gray-900" id="total-districts">—</h3>
                        </div>
                        <div class="w-14 h-14 bg-sky-100 rounded-2xl flex items-center justify-center text-sky-600">
                            <i class="fas fa-map-marked-alt text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Market Data Table -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-[0.24em]">Live Market Prices</p>
                        <h3 class="text-xl font-black text-gray-900 mt-1">APMC Mandi Price Data</h3>
                    </div>
                    <div class="flex gap-2">
                        <input type="text" id="searchInput" placeholder="Search commodities..." class="px-4 py-2 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm">
                        <select id="districtFilter" class="px-4 py-2 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm">
                            <option value="">All Districts</option>
                        </select>
                        <button onclick="loadMarketData()" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Commodity</th>
                                <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Market</th>
                                <th class="py-4 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">District</th>
                                <th class="py-4 px-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Min Price</th>
                                <th class="py-4 px-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Max Price</th>
                                <th class="py-4 px-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Modal Price</th>
                                <th class="py-4 px-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                            </tr>
                        </thead>
                        <tbody id="market-table" class="divide-y divide-gray-100">
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-400">
                                    <i class="fas fa-circle-notch fa-spin text-2xl"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        let allMarketData = [];

        async function loadMarketData() {
            try {
                const res = await fetch('../backend/get_market.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ districts: [], markets: [], commodities: [] })
                });
                const data = await res.json();
                
                // The API returns { success: true, rows: [...] }
                allMarketData = (data && data.rows) ? data.rows : [];
                
                // Update stats
                const markets = [...new Set(allMarketData.map(m => m.market).filter(Boolean))];
                const commodities = [...new Set(allMarketData.map(m => m.commodity).filter(Boolean))];
                const districts = [...new Set(allMarketData.map(m => m.district).filter(Boolean))];
                
                document.getElementById('total-markets').textContent = markets.length.toLocaleString('en-IN');
                document.getElementById('total-commodities').textContent = commodities.length.toLocaleString('en-IN');
                document.getElementById('total-districts').textContent = districts.length.toLocaleString('en-IN');
                
                // Populate district filter
                const sel = document.getElementById('districtFilter');
                sel.innerHTML = '<option value="">All Districts</option>' + districts.sort().map(d => `<option value="${d}">${d}</option>`).join('');
                
                renderTable(allMarketData);
            } catch (e) {
                console.error('Error loading market data:', e);
                document.getElementById('market-table').innerHTML = '<tr><td colspan="7" class="py-8 text-center text-red-400">Failed to load market data</td></tr>';
            }
        }

        function renderTable(data) {
            const tbody = document.getElementById('market-table');
            
            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="py-12 text-center text-gray-400">No market data available</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(m => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-gray-900 font-medium">${m.commodity || '—'}</td>
                    <td class="py-4 px-6 text-gray-600">${m.market || '—'}</td>
                    <td class="py-4 px-6 text-gray-600">${m.district || '—'}</td>
                    <td class="py-4 px-6 text-right text-gray-600">₹${m.min.toLocaleString('en-IN')}</td>
                    <td class="py-4 px-6 text-right text-gray-600">₹${m.max.toLocaleString('en-IN')}</td>
                    <td class="py-4 px-6 text-right text-emerald-600 font-bold">₹${m.modal.toLocaleString('en-IN')}</td>
                    <td class="py-4 px-6 text-center text-gray-500">${m.arrival_date || '—'}</td>
                </tr>
            `).join('');
        }

        // Filter functionality
        document.getElementById('searchInput').addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase();
            const d = document.getElementById('districtFilter').value;
            
            const filtered = allMarketData.filter(m => {
                const matchesSearch = (m.commodity || '').toLowerCase().includes(q) || (m.market || '').toLowerCase().includes(q);
                const matchesDistrict = !d || (m.district || '').toLowerCase() === d.toLowerCase();
                return matchesSearch && matchesDistrict;
            });
            
            renderTable(filtered);
        });

        document.getElementById('districtFilter').addEventListener('change', () => {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const d = document.getElementById('districtFilter').value;
            
            const filtered = allMarketData.filter(m => {
                const matchesSearch = (m.commodity || '').toLowerCase().includes(q) || (m.market || '').toLowerCase().includes(q);
                const matchesDistrict = !d || (m.district || '').toLowerCase() === d.toLowerCase();
                return matchesSearch && matchesDistrict;
            });
            
            renderTable(filtered);
        });

        loadMarketData();
        
        // Auto-refresh every 60 seconds
        setInterval(loadMarketData, 60000);
    </script>
</body>
</html>
