<?php
// dashboard/_sidebar.php - shared admin sidebar
$currentPage = basename($_SERVER['PHP_SELF']);
function navLink($href, $icon, $label, $current) {
    $active = basename($href) === $current;
    $cls = $active
        ? 'flex items-center gap-4 px-5 py-3.5 rounded-xl bg-emerald-600 text-white font-bold text-sm shadow-lg shadow-emerald-900/20 mx-3'
        : 'flex items-center gap-4 px-5 py-3.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-bold text-sm transition-colors mx-3';
    return "<a href=\"$href\" class=\"$cls\"><i class=\"fas fa-$icon w-5\"></i><span>$label</span></a>";
}
?>
<aside class="w-64 bg-slate-950 flex flex-col text-slate-400 shrink-0 hidden lg:flex">
    <div class="px-6 py-8">
        <a href="../frontend/index.php" class="flex items-center gap-3 group">
            <div class="w-11 h-11 bg-emerald-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg shadow-emerald-900/40 group-hover:scale-110 transition-transform">
                <i class="fas fa-wheat-awn"></i>
            </div>
            <div>
                <span class="text-xl font-black text-white block leading-tight">AgriCare</span>
                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Admin Panel</span>
            </div>
        </a>
    </div>

    <nav class="flex-1 space-y-1 pb-4">
        <p class="px-6 text-[9px] font-black text-slate-600 uppercase tracking-[0.2em] mb-2">Management</p>
        <?= navLink('admin.php',           'chart-line',          'Dashboard',        $currentPage) ?>
        <?= navLink('admin_users.php',     'users',               'Farmer Registry',  $currentPage) ?>
        <?= navLink('admin_subsidies.php', 'hand-holding-heart',  'Subsidies',        $currentPage) ?>
        <?= navLink('admin_market.php',    'arrow-trend-up',      'Market Data',      $currentPage) ?>
        <?= navLink('admin_pesticides.php','vial-virus',          'Pesticides',       $currentPage) ?>
        <?= navLink('admin_feedback.php',  'comment-dots',        'User Feedback',    $currentPage) ?>
    </nav>

    <div class="p-6 border-t border-slate-900">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-xl bg-slate-800 flex items-center justify-center text-emerald-400">
                <i class="fas fa-user-shield text-sm"></i>
            </div>
            <div>
                <p id="sidebarAdminName" class="text-xs font-black text-white leading-tight">Admin</p>
                <p class="text-[9px] text-emerald-500 font-bold uppercase tracking-wider">Root Access</p>
            </div>
        </div>
        <button onclick="adminLogout()" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl border border-slate-800 hover:border-red-500/50 hover:text-red-400 transition-all text-[10px] font-black uppercase tracking-widest">
            <i class="fas fa-power-off"></i> Logout
        </button>
    </div>
</aside>
<script>
function adminLogout() {
    sessionStorage.removeItem('agricare_user');
    localStorage.removeItem('agricare_user');
    window.location.href = '../frontend/login.php';
}
(function() {
    const u = JSON.parse(sessionStorage.getItem('agricare_user') || localStorage.getItem('agricare_user') || 'null');
    if (!u || u.role !== 'admin') { window.location.href = '../frontend/login.php'; return; }
    const el = document.getElementById('sidebarAdminName');
    if (el) el.textContent = u.name || 'Admin';
})();
</script>
