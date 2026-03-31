<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pesticide Core Management | AgriCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        .premium-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); }
    </style>
</head>
<body class="flex h-screen bg-slate-50 overflow-hidden">
    <?php include '_sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white/80 border-b py-6 px-10 flex justify-between items-center">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Disease & Pesticide Registry</h2>
            <div class="flex gap-4">
                <button onclick="openModal('pestModal')" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest px-6 py-3 rounded-xl shadow-lg shadow-emerald-600/20 transition-all">Add Diagnostic Entry</button>
                <button onclick="openModal('medicineModal')" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-black uppercase tracking-widest px-6 py-3 rounded-xl shadow-lg shadow-slate-900/20 transition-all">New Stock Product</button>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto px-10 py-10">
            <div class="grid lg:grid-cols-3 gap-8 mb-12">
                <div class="premium-card p-6 rounded-3xl">
                   <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Core Knowledge Hub</p>
                   <div id="mappingList" class="space-y-4">
                       <!-- JS population -->
                   </div>
                </div>

                <div class="lg:col-span-2 premium-card rounded-3xl overflow-hidden p-0">
                    <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pest to Pesticide Mapping Engine</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50">
                                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    <th class="py-4 px-6">Identified Pest Name</th>
                                    <th class="py-4 px-6">Assigned Solution</th>
                                    <th class="py-4 px-6">Effectiveness</th>
                                    <th class="py-4 px-6 text-right">Ops</th>
                                </tr>
                            </thead>
                            <tbody id="mappingTable">
                                <!-- JS population -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal: New Pesticide -->
    <div id="medicineModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 transform transition-all scale-100">
            <h3 class="text-xl font-black text-slate-900 mb-6">Register New Pesticide</h3>
            <form id="pesticideForm" class="space-y-4">
                <input type="hidden" name="action" value="add_pesticide">
                <div>
                   <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Brand Name</label>
                   <input type="text" name="brand" placeholder="e.g. Confidor" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none" required>
                </div>
                <div>
                   <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Generic Subgroup</label>
                   <input type="text" name="name" placeholder="e.g. Imidacloprid 17.8% SL" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none" required>
                </div>
                <div>
                   <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Target Pests (Comma Sep)</label>
                   <input type="text" name="target_pests" placeholder="Aphids, Thrips..." class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
                <div>
                   <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Price Bracket</label>
                   <input type="text" name="price_range" placeholder="Rs. 400 - 600" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
                <div>
                   <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Administration Instructions</label>
                   <textarea name="usage_instructions" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl h-24 focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal('medicineModal')" class="flex-1 py-3 text-xs font-black uppercase text-slate-400">Abort</button>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white text-xs font-black uppercase rounded-xl">Commit to Registry</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: New Mapping -->
    <div id="pestModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 transform transition-all scale-100">
            <h2 class="text-xl font-black text-slate-900 mb-6">Link AI Detection to Solution</h2>
            <form id="mappingForm" class="space-y-4">
                <input type="hidden" name="action" value="add_mapping">
                <div>
                   <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Pest Name (AI Output Name)</label>
                   <input type="text" name="pest_name" placeholder="e.g., Early Blight" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none" required>
                </div>
                <div>
                   <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Select Recommended Pesticide</label>
                   <select id="pesticideSelect" name="pesticide_id" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none" required></select>
                </div>
                <div>
                   <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Effectiveness Rating</label>
                   <select name="effectiveness" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                       <option value="High">High (Recommended)</option>
                       <option value="Moderate">Moderate</option>
                       <option value="Organic">Organic Fix</option>
                   </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal('pestModal')" class="flex-1 py-3 text-xs font-black uppercase text-slate-400">Discard</button>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white text-xs font-black uppercase rounded-xl">Deploy Mapping</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function loadData() {
            const userStr = localStorage.getItem('agricare_user') || '{}';
            const res = await fetch('../backend/admin_pesticides_api.php', {
                headers: { 'X-User-Data': userStr }
            });
            const data = await res.json();
            
            const tbody = document.getElementById('mappingTable');
            tbody.innerHTML = data.mappings.map(m => `
                <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                    <td class="py-6 px-6"><p class="text-sm font-black text-slate-700 uppercase tracking-tight">${m.pest_name}</p></td>
                    <td class="py-6 px-6">
                        <p class="text-sm font-black text-slate-900">${m.brand}</p>
                        <p class="text-[10px] font-bold text-slate-400">${m.name}</p>
                    </td>
                    <td class="py-6 px-6">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-full border border-emerald-100">${m.effectiveness}</span>
                    </td>
                    <td class="py-6 px-6 text-right">
                        <button onclick="deleteMapping(${m.mapping_id})" class="text-slate-300 hover:text-red-500 transition-colors"><i class="fas fa-trash-can"></i></button>
                    </td>
                </tr>
            `).join('');

            const select = document.getElementById('pesticideSelect');
            select.innerHTML = data.pesticides.map(p => `
                <option value="${p.id}">${p.brand} (${p.name})</option>
            `).join('');

            // Pests summary card
            const pestNames = [...new Set(data.mappings.map(m => m.pest_name))];
            document.getElementById('mappingList').innerHTML = pestNames.map(p => `
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-colors cursor-default group">
                    <span class="text-xs font-bold text-slate-600 group-hover:text-emerald-700">${p}</span>
                    <i class="fas fa-chevron-right text-[8px] text-slate-300"></i>
                </div>
            `).join('') || '<p class="text-xs italic text-slate-300 text-center py-4">Knowledge Base Empty</p>';
        }

        async function deleteMapping(id) {
            if (!confirm("De-link this solution?")) return;
            const userStr = localStorage.getItem('agricare_user') || '{}';
            const res = await fetch('../backend/admin_pesticides_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-User-Data': userStr
                },
                body: JSON.stringify({action: 'delete_mapping', id: id})
            });
            loadData();
        }

        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        async function handleForm(event, modalId) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const obj = Object.fromEntries(formData.entries());
            const userStr = localStorage.getItem('agricare_user') || '{}';
            const res = await fetch('../backend/admin_pesticides_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-User-Data': userStr
                },
                body: JSON.stringify(obj)
            });
            const result = await res.json();
            if(result.status === 'success') {
                closeModal(modalId);
                loadData();
                event.target.reset();
            }
        }

        document.getElementById('pesticideForm').onsubmit = (e) => handleForm(e, 'medicineModal');
        document.getElementById('mappingForm').onsubmit = (e) => handleForm(e, 'pestModal');
        
        loadData();
    </script>
</body>
</html>
