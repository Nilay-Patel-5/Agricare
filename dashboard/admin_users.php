<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmer Registry | AgriCare Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .card { background: white; border-radius: 1.5rem; border: 1px solid #f1f5f9; }
        ::-webkit-scrollbar { width: 4px; } ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="flex h-screen overflow-hidden bg-slate-50 text-slate-700">

<?php include '_sidebar.php'; ?>

<main class="flex-1 flex flex-col h-screen overflow-hidden">
    <header class="bg-white border-b border-slate-100 px-8 py-5 flex justify-between items-center shrink-0">
        <div class="flex items-center gap-3">
            <span class="w-1.5 h-7 bg-blue-500 rounded-full"></span>
            <h1 class="text-xl font-black text-slate-900">Farmer Registry</h1>
        </div>
        <div class="w-9 h-9 bg-slate-900 rounded-xl flex items-center justify-center text-white text-sm">
            <i class="fas fa-user-shield"></i>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto px-8 py-8">

        <!-- Search & Filter Bar -->
        <div class="card p-4 mb-6 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
            <div class="relative flex-1 sm:flex-none sm:w-80">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input id="searchInput" type="text" placeholder="Search by name, phone, district..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                    oninput="filterTable()">
            </div>
            <div class="flex gap-2 items-center flex-wrap">
                <select id="districtFilter" onchange="filterTable()" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 min-w-max">
                    <option value="">All Districts</option>
                </select>
                <span id="totalCount" class="text-xs font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">0 farmers</span>
            </div>
        </div>

        <!-- Table -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">#</th>
                            <th class="py-3 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Farmer</th>
                            <th class="py-3 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Phone</th>
                            <th class="py-3 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">District</th>
                            <th class="py-3 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">City</th>
                            <th class="py-3 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Language</th>
                            <th class="py-3 px-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Joined</th>
                            <th class="py-3 px-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody id="usersTable">
                        <tr><td colspan="8" class="py-16 text-center text-slate-300"><i class="fas fa-circle-notch fa-spin text-2xl"></i></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
let allUsers = [];

const langMap = { en: '🇬🇧 English', gu: '🇮🇳 Gujarati', hi: '🇮🇳 Hindi' };

async function loadUsers() {
    try {
        const res = await fetch('../backend/admin_users_api.php');
        const data = await res.json();
        allUsers = data.users || [];

        // Populate district filter
        const districts = [...new Set(allUsers.map(u => u.district).filter(Boolean))].sort();
        const sel = document.getElementById('districtFilter');
        districts.forEach(d => { const o = document.createElement('option'); o.value = d; o.textContent = d; sel.appendChild(o); });

        renderTable(allUsers);
    } catch(e) {
        document.getElementById('usersTable').innerHTML = '<tr><td colspan="8" class="py-8 text-center text-red-400">Failed to load farmers.</td></tr>';
    }
}

function renderTable(users) {
    document.getElementById('totalCount').textContent = users.length + ' farmer' + (users.length !== 1 ? 's' : '');
    const tbody = document.getElementById('usersTable');
    if (!users.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="py-12 text-center text-slate-400">No farmers found.</td></tr>';
        return;
    }
    tbody.innerHTML = users.map((u, i) => `
        <tr class="border-t border-slate-50 hover:bg-slate-50 transition-colors" data-id="${u.id}">
            <td class="py-4 px-6 text-xs text-slate-400 font-bold">#${String(u.id).padStart(4,'0')}</td>
            <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(u.name)}&background=ecfdf5&color=059669&size=36" class="w-9 h-9 rounded-xl shrink-0">
                    <div>
                        <p class="font-bold text-slate-800 leading-tight">${escHtml(u.name)}</p>
                        <p class="text-[10px] text-slate-400">${escHtml(u.email || '—')}</p>
                    </div>
                </div>
            </td>
            <td class="py-4 px-6 text-slate-600">${escHtml(u.phone || '—')}</td>
            <td class="py-4 px-6 text-slate-600">${escHtml(u.district || '—')}</td>
            <td class="py-4 px-6 text-slate-600">${escHtml(u.city || '—')}</td>
            <td class="py-4 px-6 text-xs text-slate-500">${langMap[u.pref_lang] || u.pref_lang || '—'}</td>
            <td class="py-4 px-6 text-xs text-slate-400">${new Date(u.created_at).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'})}</td>
            <td class="py-4 px-6 text-right">
                <button onclick="deleteUser(${u.id}, '${escHtml(u.name)}')" class="text-slate-300 hover:text-red-500 transition-colors p-1.5 rounded-lg hover:bg-red-50" title="Delete">
                    <i class="fas fa-trash-can text-xs"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const d = document.getElementById('districtFilter').value;
    const filtered = allUsers.filter(u =>
        (!q || [u.name, u.phone, u.district, u.email].some(v => v && v.toLowerCase().includes(q))) &&
        (!d || u.district === d)
    );
    renderTable(filtered);
}

async function deleteUser(id, name) {
    if (!confirm(`Delete farmer "${name}"? This cannot be undone.`)) return;
    try {
        const res = await fetch('../backend/admin_users_api.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const data = await res.json();
        if (data.success) {
            allUsers = allUsers.filter(u => u.id !== id);
            filterTable();
        } else {
            alert(data.message || 'Failed to delete.');
        }
    } catch(e) { alert('Error deleting user.'); }
}

function escHtml(v) {
    return String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c]);
}

loadUsers();
</script>
</body>
</html>
