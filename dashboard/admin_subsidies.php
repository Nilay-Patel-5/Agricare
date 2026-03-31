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

<body class="flex h-screen overflow-hidden bg-slate-50 text-slate-700">

    <?php include '_sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 lg:px-10 flex justify-between items-center shrink-0 shadow-sm z-10">
            <div class="flex items-center gap-4">
                <button class="lg:hidden text-gray-500 hover:text-emerald-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-2xl font-black text-gray-800 hidden sm:block">Command Center</h2>
            </div>

            <div class="flex items-center gap-6">
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

        <div class="flex-1 overflow-y-auto px-6 lg:px-10 py-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <p class="text-gray-500 font-bold uppercase tracking-widest text-xs mb-1">Administration</p>
                    <h2 class="text-3xl font-black text-gray-900">Manage Subsidies</h2>
                </div>
                <button onclick="document.getElementById('subsidyModal').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold shadow-md transition-colors">
                    + Publish Subsidy
                </button>
            </div>

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

    <div id="subsidyModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl p-8">
            <h3 class="text-xl font-black text-slate-900 mb-6">Publish New Subsidy</h3>
            <form id="subsidyForm" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block">Scheme Name *</label>
                        <input type="text" name="name" required placeholder="e.g. PM Kisan Samman Nidhi" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block">Category *</label>
                        <select name="category" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                            <option value="">Select...</option>
                            <option value="Financial">Financial</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Seed & Fertilizer">Seed &amp; Fertilizer</option>
                            <option value="Insurance">Insurance</option>
                            <option value="Irrigation">Irrigation</option>
                            <option value="Training">Training</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block">Status</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                            <option value="Live">Live</option>
                            <option value="Draft">Draft</option>
                            <option value="Expired">Expired</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block">Description</label>
                        <textarea name="description" rows="2" placeholder="Brief description of the scheme..." class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none text-sm"></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block">Benefits</label>
                        <textarea name="benefits" rows="2" placeholder="What benefits does this scheme provide?" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none text-sm"></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block">Eligibility</label>
                        <input type="text" name="eligibility" placeholder="Who is eligible?" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 mb-1 block">Apply Link (URL)</label>
                        <input type="url" name="apply_link" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                    </div>
                </div>
                <div id="subsidyFormMsg" class="hidden text-xs font-bold px-3 py-2 rounded-xl"></div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('subsidyModal').classList.add('hidden')" class="flex-1 py-3 text-xs font-black uppercase text-slate-400 hover:text-slate-600">Cancel</button>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase rounded-xl transition-colors">Publish</button>
                </div>
            </form>
        </div>
    </div>

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
                if (data.error) throw new Error(data.error);

                allSubsidies = Array.isArray(data) ? data : [];

                const categories = [...new Set(allSubsidies.map((s) => s.category).filter(Boolean))];
                const sel = document.getElementById('categoryFilter');
                sel.innerHTML = '<option value="">All Categories</option>' + categories.sort().map((c) => `<option value="${c}">${c}</option>`).join('');

                renderTable(allSubsidies);
            } catch (e) {
                console.error('Error loading subsidies:', e);
                document.getElementById('subsidies-table').innerHTML = '<tr><td colspan="5" class="py-8 text-center text-red-400">Failed to load subsidy data: ' + e.message + '</td></tr>';
            }
        }

        function renderTable(data) {
            const tbody = document.getElementById('subsidies-table');

            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="py-12 text-center text-gray-400">No subsidies available</td></tr>';
                return;
            }

            tbody.innerHTML = data.map((s) => {
                const name = s.name || '-';
                const category = s.category || '-';
                const description = s.description || '-';
                const status = s.status || 'Live';
                const lastUpdated = s.last_updated ? new Date(s.last_updated).toLocaleDateString('en-IN') : '-';

                return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-gray-900 font-medium">${name}</td>
                    <td class="py-4 px-6 text-gray-600">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-full">${category}</span>
                    </td>
                    <td class="py-4 px-6 text-gray-600 max-w-xs truncate">${description}</td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full">${status}</span>
                    </td>
                    <td class="py-4 px-6 text-center text-gray-500">${lastUpdated}</td>
                </tr>
            `;
            }).join('');
        }

        function getFilteredSubsidies() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const c = document.getElementById('categoryFilter').value;

            return allSubsidies.filter((s) => {
                const matchesSearch = (s.name || '').toLowerCase().includes(q) || (s.description || '').toLowerCase().includes(q);
                const matchesCategory = !c || (s.category || '').toLowerCase() === c.toLowerCase();
                return matchesSearch && matchesCategory;
            });
        }

        document.getElementById('searchInput').addEventListener('input', () => {
            renderTable(getFilteredSubsidies());
        });

        document.getElementById('categoryFilter').addEventListener('change', () => {
            renderTable(getFilteredSubsidies());
        });

        loadSubsidies();
        setInterval(loadSubsidies, 60000);

        document.getElementById('subsidyForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('[type=submit]');
            const msgEl = document.getElementById('subsidyFormMsg');
            btn.textContent = 'Publishing...';
            btn.disabled = true;

            try {
                const formData = new FormData(e.target);
                const obj = Object.fromEntries(formData.entries());
                const userStr = localStorage.getItem('agricare_user') || '{}';
                const res = await fetch('../backend/admin_subsidies_api.php', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-User-Data': userStr
                    },
                    body: JSON.stringify(obj)
                });
                const result = await res.json();

                if (result.success) {
                    msgEl.textContent = 'Success: ' + result.message;
                    msgEl.className = 'text-xs font-bold px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700';
                    e.target.reset();
                    loadSubsidies();
                    setTimeout(() => document.getElementById('subsidyModal').classList.add('hidden'), 1200);
                } else {
                    msgEl.textContent = result.message || 'Error.';
                    msgEl.className = 'text-xs font-bold px-3 py-2 rounded-xl bg-red-50 text-red-600';
                }

                msgEl.classList.remove('hidden');
            } catch (err) {
                msgEl.textContent = 'Network error. Please try again.';
                msgEl.className = 'text-xs font-bold px-3 py-2 rounded-xl bg-red-50 text-red-600';
                msgEl.classList.remove('hidden');
            }

            btn.textContent = 'Publish';
            btn.disabled = false;
        });
    </script>
</body>

</html>
