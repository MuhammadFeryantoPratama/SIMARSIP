<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMARSIP - Auditor')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .modal-overlay {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px); z-index: 100;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: all 0.25s ease;
        }
        .modal-overlay.active { opacity: 1; pointer-events: auto; }
        .modal-content {
            background: white; border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
            transform: scale(0.95) translateY(10px);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .modal-overlay.active .modal-content { transform: scale(1) translateY(0); }

        .toast {
            position: fixed; bottom: 24px; right: 24px; z-index: 200;
            padding: 12px 20px; border-radius: 14px; color: white;
            font-weight: 500; font-size: 14px; display: flex; align-items: center; gap: 10px;
            transform: translateY(100px) scale(0.9); opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.25);
        }
        .toast.show { transform: translateY(0) scale(1); opacity: 1; }
        .toast.success { background: #059669; }
        .toast.error { background: #dc2626; }
        .toast.info { background: #0b1f51; }

        .dropdown-menu {
            position: absolute; top: calc(100% + 8px); right: 0;
            background: white; border-radius: 14px; min-width: 220px;
            box-shadow: 0 20px 40px -8px rgba(15, 23, 42, 0.18); border: 1px solid #e2e8f0;
            opacity: 0; pointer-events: none; transform: translateY(-8px);
            transition: all 0.2s ease; z-index: 50;
        }
        .dropdown-menu.show { opacity: 1; pointer-events: auto; transform: translateY(0); }
    </style>
</head>
<body class="font-sans antialiased bg-[#f8fafc] text-slate-800">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar Auditor matching Screenshot --}}
        <aside class="w-[230px] bg-white border-r border-slate-200 flex flex-col flex-shrink-0 z-20">
            {{-- Logo Header --}}
            <div class="px-5 py-5 flex items-center gap-3">
                <div class="w-9 h-9 bg-[#0b1f51] rounded-lg flex items-center justify-center text-white shadow-sm flex-shrink-0">
                    <span class="material-icons-outlined text-xl">account_balance</span>
                </div>
                <div>
                    <h1 class="font-black text-base tracking-wider text-[#0b1f51] leading-none">SIMARSIP</h1>
                    <p class="text-[9px] font-bold text-slate-400 tracking-wider uppercase mt-1">Manajemen Arsip Digital</p>
                </div>
            </div>

            {{-- Navigation links --}}
            <nav class="flex-1 px-3 py-3 space-y-1">
                {{-- Dashboard --}}
                <a href="{{ route('auditor.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('auditor.dashboard') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-icons-outlined text-lg">grid_view</span>
                    <span>Dashboard</span>
                </a>

                {{-- Arsip Digital --}}
                <a href="{{ route('auditor.arsip') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('auditor.arsip') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-icons-outlined text-lg">folder_open</span>
                    <span>Arsip Digital</span>
                </a>

                {{-- Pengaturan --}}
                <a href="{{ route('admin.roles') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-50">
                    <span class="material-icons-outlined text-lg">settings</span>
                    <span>Pengaturan</span>
                </a>
            </nav>

            {{-- Bottom Sidebar --}}
            <div class="p-4 border-t border-slate-200/80 space-y-1">
                <button onclick="showHelpModal()" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all text-left">
                    <span class="material-icons-outlined text-base text-slate-400">help_outline</span>
                    <span>Bantuan</span>
                </button>
                <button onclick="handleLogout()" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 transition-all text-left">
                    <span class="material-icons-outlined text-base text-slate-400">logout</span>
                    <span>Keluar</span>
                </button>
            </div>
        </aside>

        {{-- Main Area --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Top Header --}}
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0 z-10">
                {{-- Search Bar --}}
                <div class="relative w-full max-w-lg">
                    <span class="material-icons-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                    <input type="text" id="globalSearchInput" onkeydown="handleGlobalSearch(event)"
                           placeholder="Mencari data arsip, user, atau log..."
                           class="w-full pl-10 pr-4 py-2 bg-slate-50/70 hover:bg-slate-100/60 focus:bg-white border border-slate-200 focus:border-blue-500 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/15 transition-all">
                </div>

                {{-- Header Actions --}}
                <div class="flex items-center gap-3">
                    {{-- Notification --}}
                    <button onclick="toggleNotifications()" class="relative p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all" title="Notifikasi">
                        <span class="material-icons-outlined text-xl">notifications_none</span>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    {{-- Help --}}
                    <button onclick="showHelpModal()" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all" title="Bantuan">
                        <span class="material-icons-outlined text-xl">help_outline</span>
                    </button>

                    {{-- Divider --}}
                    <div class="h-8 w-px bg-slate-200 mx-1"></div>

                    {{-- User Profile: Rafskuy (Auditor) matching Image --}}
                    <div class="relative">
                        <button onclick="toggleProfileDropdown()" class="flex items-center gap-3 py-1.5 px-2 rounded-xl hover:bg-slate-100 transition-all text-left">
                            <div class="w-9 h-9 rounded-full overflow-hidden bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs ring-2 ring-amber-200 flex-shrink-0">
                                <img src="{{ asset('images/user-avatar.jpg') }}" alt="Rafskuy" class="w-full h-full object-cover" onerror="this.remove(); document.getElementById('auditorInitials').classList.remove('hidden');">
                                <span id="auditorInitials" class="hidden">RF</span>
                            </div>
                            <div class="hidden md:block">
                                <p class="text-xs font-bold text-slate-800 leading-tight">Rafskuy</p>
                                <p class="text-[10px] text-slate-400 leading-tight">Auditor</p>
                            </div>
                        </button>

                        <div id="profileDropdown" class="dropdown-menu">
                            <div class="p-2">
                                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                                    <p class="text-xs font-bold text-slate-800">Rafskuy</p>
                                    <p class="text-[10px] text-slate-400">rafskuy@simarsip.go.id • Auditor</p>
                                </div>
                                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                    <span class="material-icons-outlined text-base">admin_panel_settings</span> Beralih ke Admin Sistem
                                </a>
                                <a href="{{ route('pimpinan.dashboard') }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                    <span class="material-icons-outlined text-base">supervisor_account</span> Beralih ke Pimpinan
                                </a>
                                <a href="{{ route('staff.dashboard') }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                    <span class="material-icons-outlined text-base">person</span> Beralih ke Staff
                                </a>
                                <hr class="my-1 border-slate-100">
                                <button onclick="handleLogout()" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 rounded-lg transition-all text-left">
                                    <span class="material-icons-outlined text-base">logout</span> Keluar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Content Scrollable Area --}}
            <main class="flex-1 overflow-y-auto custom-scroll p-8">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Toast Notification --}}
    <div id="toast" class="toast">
        <span id="toastIcon" class="material-icons-outlined text-lg">check_circle</span>
        <span id="toastMessage">Pesan notifikasi</span>
    </div>

    {{-- Help Center Modal --}}
    <div id="helpModal" class="modal-overlay" onclick="if(event.target===this)closeHelpModal()">
        <div class="modal-content max-w-lg w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-800">Bantuan Auditor</h3>
                <button onclick="closeHelpModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-xl">close</span></button>
            </div>
            <div class="my-4 space-y-3 text-xs text-slate-600">
                <p>Sebagai <strong>Auditor</strong>, Anda memiliki hak akses untuk memverifikasi arsip digital, meninjau kepatuhan dokumen, dan menelusuri jejak audit sistem.</p>
                <p>Dokumen dengan status <strong>Pending Review</strong> memerlukan tindakan verifikasi dari Anda. Klik <strong>Review</strong> untuk melihat detail dan mengambil keputusan.</p>
                <p>Gunakan fitur <strong>Ekspor Laporan Audit</strong> untuk menghasilkan rekap audit dalam format Excel atau PDF.</p>
            </div>
            <div class="pt-2 border-t border-slate-100 flex justify-end">
                <button onclick="closeHelpModal()" class="px-4 py-2 bg-[#0b1f51] text-white rounded-xl text-xs font-bold">Mengerti</button>
            </div>
        </div>
    </div>

    {{-- Notifications Drawer --}}
    <div id="notifPanel" class="modal-overlay" onclick="if(event.target===this)toggleNotifications()">
        <div class="modal-content max-w-md w-full p-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-800">Notifikasi Auditor</h3>
                <button onclick="toggleNotifications()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-lg">close</span></button>
            </div>
            <div class="space-y-2.5 my-4 text-xs">
                <div class="p-3 bg-red-50/70 border border-red-100 rounded-xl">
                    <p class="font-bold text-slate-800">8 Ketidaksesuaian Baru Ditemukan</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Arsip pada divisi Keuangan memerlukan review segera.</p>
                </div>
                <div class="p-3 bg-amber-50/70 border border-amber-100 rounded-xl">
                    <p class="font-bold text-slate-800">42 Dokumen Menunggu Antrian Review</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Prioritaskan verifikasi dokumen klasifikasi Rahasia.</p>
                </div>
                <div class="p-3 bg-blue-50/70 border border-blue-100 rounded-xl">
                    <p class="font-bold text-slate-800">Laporan Audit Mingguan Tersedia</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Rekap 7 hari terakhir telah siap diunduh.</p>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 flex justify-end">
                <button onclick="toggleNotifications()" class="px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function toggleProfileDropdown() { document.getElementById('profileDropdown').classList.toggle('show'); }
        function toggleNotifications() { document.getElementById('notifPanel').classList.toggle('active'); }
        function showHelpModal() { document.getElementById('helpModal').classList.add('active'); }
        function closeHelpModal() { document.getElementById('helpModal').classList.remove('active'); }
        function handleLogout() {
            if (confirm('Keluar dari sesi Auditor Rafskuy?')) {
                window.location.href = "{{ route('login') }}";
            }
        }
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            const msgEl = document.getElementById('toastMessage');
            const iconEl = document.getElementById('toastIcon');
            msgEl.textContent = message;
            toast.className = `toast ${type}`;
            if (type === 'success') iconEl.textContent = 'check_circle';
            else if (type === 'error') iconEl.textContent = 'error_outline';
            else iconEl.textContent = 'info';
            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); }, 3200);
        }
        function handleGlobalSearch(e) {
            if (e.key === 'Enter') {
                const query = e.target.value.trim();
                if (query) {
                    window.location.href = `{{ route('auditor.arsip') }}?search=${encodeURIComponent(query)}`;
                }
            }
        }
        document.addEventListener('click', function(e) {
            const profileDropdown = document.getElementById('profileDropdown');
            const profileBtn = e.target.closest('[onclick="toggleProfileDropdown()"]');
            if (profileDropdown && !profileBtn && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('show');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
