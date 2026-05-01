<?php
// dashboard/admin_feedback.php - View farmer feedbacks
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Feedback | AgriCare Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        .rating-star { color: #fbbf24; }
        .rating-star.empty { color: #e5e7eb; }
    </style>
</head>
<body class="bg-slate-50 overflow-hidden font-sans">
    <div class="flex h-screen overflow-hidden">
        
        <?php include '_sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-0 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-slate-200 rounded-full blur-[150px] -mr-64 -mt-64 z-0"></div>
            
            <!-- Header -->
            <header class="relative z-10 bg-white/60 backdrop-blur-2xl border-b border-slate-200 px-8 py-5 flex items-center justify-between">
                <div>
                   <h2 class="text-2xl font-black text-slate-900 tracking-tight">User Feedback</h2>
                   <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Voice of the Farmers</p>
                </div>
                <button onclick="loadFeedbacks()" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </header>

            <!-- Content -->
            <main class="flex-1 relative overflow-y-auto p-8 z-10">
                <div class="glass-card rounded-2xl border border-white shadow-lg overflow-hidden">
                    <div id="feedback-container" class="divide-y divide-slate-100">
                        <div class="p-20 text-center text-slate-400">
                            <i class="fas fa-circle-notch fa-spin text-3xl mb-4"></i>
                            <p class="font-bold">Loading feedbacks...</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        async function loadFeedbacks() {
            const container = document.getElementById('feedback-container');
            try {
                const res = await fetch('../backend/get_feedbacks_api.php');
                const data = await res.json();
                
                if (data.success) {
                    if (data.feedbacks && data.feedbacks.length > 0) {
                        container.innerHTML = data.feedbacks.map(f => {
                            const date = new Date(f.created_at).toLocaleString('en-IN', {
                                day: '2-digit', month: 'short', year: 'numeric',
                                hour: '2-digit', minute: '2-digit'
                            });
                            
                            let stars = '';
                            if (f.rating) {
                                for(let i=1; i<=5; i++) {
                                    stars += `<i class="fas fa-star rating-star ${i <= f.rating ? '' : 'empty'}"></i>`;
                                }
                            }

                            return `
                                <div class="p-6 hover:bg-slate-50/50 transition-colors">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700 font-black text-xs">
                                                ${(f.farmer_name || 'U').charAt(0)}
                                            </div>
                                            <div>
                                                <h4 class="font-black text-slate-900 leading-tight">${f.farmer_name || 'Unknown User'}</h4>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">${f.farmer_phone || 'No Phone'}</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-col md:items-end">
                                            <div class="flex gap-1 mb-1">${stars}</div>
                                            <span class="text-[10px] font-bold text-slate-400">${date}</span>
                                        </div>
                                    </div>
                                    <div class="md:pl-12">
                                        <h5 class="text-sm font-bold text-slate-800 mb-1">${f.subject}</h5>
                                        <p class="text-sm text-slate-600 leading-relaxed">${f.message}</p>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        container.innerHTML = `
                            <div class="p-20 text-center text-slate-400">
                                <i class="fas fa-comment-slash text-5xl mb-4 opacity-20"></i>
                                <p class="font-bold">No feedback received yet.</p>
                            </div>
                        `;
                    }
                } else {
                    container.innerHTML = `<div class="p-10 text-center text-red-500 font-bold">${data.message}</div>`;
                }
            } catch (err) {
                console.error(err);
                container.innerHTML = `<div class="p-10 text-center text-red-500 font-bold">Error loading feedbacks.</div>`;
            }
        }

        document.addEventListener('DOMContentLoaded', loadFeedbacks);
    </script>
</body>
</html>
