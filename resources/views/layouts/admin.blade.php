<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMARSIP - Admin')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Admin custom scrollbars */
        .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Toggle switch */
        .toggle-switch { position: relative; width: 44px; height: 24px; cursor: pointer; display: inline-block; vertical-align: middle; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
        .toggle-slider {
            position: absolute; inset: 0; background: #cbd5e1;
            border-radius: 9999px; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .toggle-slider::before {
            content: ''; position: absolute; width: 18px; height: 18px;
            left: 3px; bottom: 3px; background: white;
            border-radius: 50%; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(0,0,0,0.25);
        }
        .toggle-switch input:checked + .toggle-slider { background: #0b1f51; }
        .toggle-switch input:checked + .toggle-slider::before { transform: translateX(20px); }

        /* Modals */
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

        /* Toast system */
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

        /* Dropdown */
        .dropdown-menu {
            position: absolute; top: calc(100% + 8px); right: 0;
            background: white; border-radius: 14px; min-width: 210px;
            box-shadow: 0 20px 40px -8px rgba(15, 23, 42, 0.18); border: 1px solid #e2e8f0;
            opacity: 0; pointer-events: none; transform: translateY(-8px);
            transition: all 0.2s ease; z-index: 50;
        }
        .dropdown-menu.show { opacity: 1; pointer-events: auto; transform: translateY(0); }
    </style>
</head>
<body class="font-sans antialiased bg-[#f8fafc] text-slate-800">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar matching the exact white UI in screenshots --}}
        <aside class="w-[230px] bg-white border-r border-slate-200 flex flex-col flex-shrink-0 z-20">
            {{-- Logo Header --}}
            <div class="px-5 py-5 flex items-center gap-3">
                <div class="w-9 h-9 bg-[#0b1f51] rounded-lg flex items-center justify-center text-white shadow-sm flex-shrink-0">
                    <span class="material-icons-outlined text-xl">account_balance</span>
                </div>
                <div>
                    <h1 class="font-black text-base tracking-wider text-[#0b1f51] leading-none">SIMARSIP</h1>
                    <p class="text-[9px] font-bold text-slate-400 tracking-wider uppercase mt-1">ADMIN SISTEM</p>
                </div>
            </div>

            {{-- Navigation links --}}
            <nav class="flex-1 px-3 py-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-icons-outlined text-lg">grid_view</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.arsip') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('admin.arsip') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-icons-outlined text-lg">folder_open</span>
                    <span>Arsip Digital</span>
                </a>

                <a href="{{ route('admin.roles') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('admin.roles') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-icons-outlined text-lg">settings</span>
                    <span>Pengaturan</span>
                </a>
            </nav>

            {{-- Bottom Sidebar --}}
            <div class="p-4 border-t border-slate-200/80 space-y-3">
                {{-- Dark Navy Upload Button --}}
                <button onclick="showUploadModal()" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-lg text-xs font-semibold shadow-sm transition-all active:scale-[0.98]">
                    <span class="material-icons-outlined text-base">add</span>
                    <span>Upload Dokumen</span>
                </button>

                {{-- Secondary links --}}
                <div class="space-y-0.5 pt-1">
                    <button onclick="showHelpModal()" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all text-left">
                        <span class="material-icons-outlined text-base text-slate-400">help_outline</span>
                        <span>Bantuan</span>
                    </button>
                    <button onclick="handleLogout()" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 transition-all text-left">
                        <span class="material-icons-outlined text-base text-slate-400">logout</span>
                        <span>Keluar</span>
                    </button>
                </div>
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

                    {{-- User Profile Dropdown --}}
                    <div class="relative">
                        <button onclick="toggleProfileDropdown()" class="flex items-center gap-3 py-1.5 px-2 rounded-xl hover:bg-slate-100 transition-all text-left">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-slate-700 to-slate-900 text-white flex items-center justify-center font-bold text-xs ring-2 ring-slate-100 flex-shrink-0">
                                <span>PB</span>
                            </div>
                            <div class="hidden md:block">
                                <p class="text-xs font-bold text-slate-800 leading-tight">Prabskuy</p>
                                <p class="text-[10px] text-slate-400 leading-tight">Sistem Administrator</p>
                            </div>
                        </button>

                        <div id="profileDropdown" class="dropdown-menu">
                            <div class="p-2">
                                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                                    <p class="text-xs font-bold text-slate-800">Prabskuy</p>
                                    <p class="text-[11px] text-slate-400">admin@simarsip.go.id</p>
                                </div>
                                <button onclick="showToast('Membuka profil...', 'info'); toggleProfileDropdown();" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all text-left">
                                    <span class="material-icons-outlined text-base">person_outline</span> Profil Saya
                                </button>
                                <a href="{{ route('pimpinan.dashboard') }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                    <span class="material-icons-outlined text-base">supervisor_account</span> Beralih ke Pimpinan
                                </a>
                                <a href="{{ route('staff.dashboard') }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                    <span class="material-icons-outlined text-base">person</span> Beralih ke Staff
                                </a>
                                <a href="{{ route('auditor.dashboard') }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                    <span class="material-icons-outlined text-base">verified_user</span> Beralih ke Auditor
                                </a>
                                <a href="{{ route('admin.roles') }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                    <span class="material-icons-outlined text-base">settings</span> Pengaturan Sistem
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

    {{-- Universal Upload Modal --}}
    <div id="uploadModal" class="modal-overlay" onclick="if(event.target===this)closeUploadModal()">
        <div class="modal-content max-w-lg w-full p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <span class="material-icons-outlined text-lg">cloud_upload</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Upload Dokumen Baru</h3>
                        <p class="text-xs text-slate-400">Simpan dan klasifikasikan berkas ke arsip digital</p>
                    </div>
                </div>
                <button onclick="closeUploadModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600 transition-all">
                    <span class="material-icons-outlined text-xl">close</span>
                </button>
            </div>
            <form id="globalUploadForm" onsubmit="handleUniversalUpload(event)" class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Dokumen</label>
                    <input type="text" id="uploadDocName" required placeholder="Contoh: Laporan Pertanggungjawaban Q4.pdf" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Arsip</label>
                        <input type="text" id="uploadDocNo" required placeholder="INV-2023-XXX" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori</label>
                        <select id="uploadDocCategory" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="Keuangan">Keuangan</option>
                            <option value="Legal">Legal</option>
                            <option value="Operasional">Operasional</option>
                            <option value="SDM">SDM</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">File Berkas</label>
                    <div id="dropZone" onclick="document.getElementById('fileUploadInput').click()" class="border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50/50 hover:bg-blue-50/30 rounded-xl p-6 text-center cursor-pointer transition-all">
                        <input type="file" id="fileUploadInput" onchange="handleFileSelected(this)" class="hidden" accept=".pdf,.doc,.docx,.zip,.xls,.xlsx">
                        <span class="material-icons-outlined text-4xl text-slate-300 mb-1">upload_file</span>
                        <p id="fileUploadText" class="text-xs font-semibold text-slate-600">Klik untuk memilih atau seret file ke sini</p>
                        <p class="text-[10px] text-slate-400 mt-1">Mendukung PDF, DOCX, ZIP, XLSX hingga 50MB</p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeUploadModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                        Upload & Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Notifications Drawer Modal --}}
    <div id="notifPanel" class="modal-overlay" onclick="if(event.target===this)toggleNotifications()">
        <div class="modal-content max-w-md w-full p-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="material-icons-outlined text-blue-600 text-lg">notifications</span>
                    <h3 class="text-sm font-bold text-slate-800">Notifikasi Sistem</h3>
                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold">4 Baru</span>
                </div>
                <button onclick="toggleNotifications()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600">
                    <span class="material-icons-outlined text-lg">close</span>
                </button>
            </div>
            <div class="space-y-2.5 my-4 max-h-80 overflow-y-auto custom-scroll pr-1">
                <div class="p-3 bg-blue-50/70 border border-blue-100 rounded-xl flex items-start gap-3">
                    <span class="material-icons-outlined text-blue-600 text-base mt-0.5">description</span>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-800">Arsip baru membutuhkan review</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Draft Perjanjian Kerja Sama Vendor diunggah oleh Staff.</p>
                        <span class="text-[10px] text-slate-400 mt-1 block">5 menit yang lalu</span>
                    </div>
                </div>
                <div class="p-3 bg-amber-50/70 border border-amber-100 rounded-xl flex items-start gap-3">
                    <span class="material-icons-outlined text-amber-600 text-base mt-0.5">lock_clock</span>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-800">18 Akses tertunda</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Memerlukan persetujuan administrator untuk aktivasi hak akses.</p>
                        <span class="text-[10px] text-slate-400 mt-1 block">30 menit yang lalu</span>
                    </div>
                </div>
                <div class="p-3 bg-emerald-50/70 border border-emerald-100 rounded-xl flex items-start gap-3">
                    <span class="material-icons-outlined text-emerald-600 text-base mt-0.5">cloud_done</span>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-800">Backup basis data harian selesai</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Integritas 100%, ukuran file 4.2 GB disimpan di cold storage.</p>
                        <span class="text-[10px] text-slate-400 mt-1 block">2 jam yang lalu</span>
                    </div>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 flex justify-between items-center">
                <button onclick="showToast('Semua notifikasi ditandai dibaca', 'info'); toggleNotifications();" class="text-xs text-slate-500 hover:text-slate-800 font-semibold">Tandai Dibaca</button>
                <button onclick="toggleNotifications()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold">Tutup</button>
            </div>
        </div>
    </div>

    {{-- Help Center Modal --}}
    <div id="helpModal" class="modal-overlay" onclick="if(event.target===this)closeHelpModal()">
        <div class="modal-content max-w-lg w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="material-icons-outlined text-blue-600 text-xl">help_outline</span>
                    <h3 class="text-base font-bold text-slate-800">Pusat Bantuan SIMARSIP</h3>
                </div>
                <button onclick="closeHelpModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
                    <span class="material-icons-outlined text-xl">close</span>
                </button>
            </div>
            <div class="my-4 space-y-3 text-xs text-slate-600">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="font-bold text-slate-800 mb-1">Panduan Hak Akses (RBAC)</p>
                    <p>Konfigurasikan peran seperti Admin, Staff, Auditor, dan Pimpinan di menu Pengaturan Peran & Hak Akses.</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="font-bold text-slate-800 mb-1">Pengelolaan Arsip Digital</p>
                    <p>Arsip dapat dicari, diklasifikasikan berdasarkan kategori, serta diekspor ke format pelaporan instansi.</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="font-bold text-slate-800 mb-1">Kontak Dukungan Teknis</p>
                    <p>Hubungi admin pusat di <strong>support@simarsip.go.id</strong> atau hotline (021) 7890-SIMARSIP.</p>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 flex justify-end">
                <button onclick="closeHelpModal()" class="px-4 py-2 bg-[#0b1f51] text-white rounded-xl text-xs font-bold">Mengerti</button>
            </div>
        </div>
    </div>

    {{-- Toast Notification Box --}}
    <div id="toast" class="toast">
        <span id="toastIcon" class="material-icons-outlined text-lg">check_circle</span>
        <span id="toastMessage">Pesan notifikasi</span>
    </div>

    {{-- Universal JS Functions --}}
    <script>
        // Profile Dropdown Toggle
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Notifications Modal Toggle
        function toggleNotifications() {
            const panel = document.getElementById('notifPanel');
            panel.classList.toggle('active');
        }

        // Help Modal
        function showHelpModal() {
            document.getElementById('helpModal').classList.add('active');
        }
        function closeHelpModal() {
            document.getElementById('helpModal').classList.remove('active');
        }

        // Upload Modal
        function showUploadModal() {
            document.getElementById('uploadModal').classList.add('active');
        }
        function closeUploadModal() {
            document.getElementById('uploadModal').classList.remove('active');
        }

        function handleFileSelected(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                document.getElementById('fileUploadText').textContent = `File dipilih: ${file.name} (${(file.size/1024/1024).toFixed(2)} MB)`;
                if (!document.getElementById('uploadDocName').value) {
                    document.getElementById('uploadDocName').value = file.name;
                }
            }
        }

        function handleUniversalUpload(e) {
            e.preventDefault();
            const docName = document.getElementById('uploadDocName').value;
            const category = document.getElementById('uploadDocCategory').value;
            closeUploadModal();
            showToast(`Dokumen "${docName}" berhasil diunggah ke kategori ${category}!`, 'success');
            // If on arsip page, append or refresh
            if (typeof appendNewArsipRow === 'function') {
                appendNewArsipRow({
                    name: docName,
                    no: document.getElementById('uploadDocNo').value || 'INV-2023-NEW',
                    category: category,
                    date: 'Hari ini',
                    status: 'Finalized'
                });
            }
        }

        // Global search bar
        function handleGlobalSearch(e) {
            if (e.key === 'Enter') {
                const query = e.target.value.trim();
                if (query) {
                    showToast(`Mencari: "${query}" di seluruh basis data...`, 'info');
                    if (window.location.pathname.indexOf('arsip') === -1) {
                        setTimeout(() => {
                            window.location.href = `{{ route('admin.arsip') }}?search=${encodeURIComponent(query)}`;
                        }, 500);
                    } else if (typeof filterArsip === 'function') {
                        document.getElementById('searchInput').value = query;
                        filterArsip();
                    }
                }
            }
        }

        // Logout
        function handleLogout() {
            if (confirm('Apakah Anda yakin ingin keluar dari sistem SIMARSIP?')) {
                showToast('Mengakhiri sesi...', 'info');
                setTimeout(() => {
                    window.location.href = "{{ route('login') }}";
                }, 600);
            }
        }

        // Global Toast System
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
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3200);
        }

        // Close dropdown when clicked outside
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
