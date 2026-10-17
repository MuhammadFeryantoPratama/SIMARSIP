<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMARSIP - Staff')</title>

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
        {{-- Sidebar Staff matching Screenshot --}}
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
                {{-- Dashboard --}}
                <a href="{{ route('staff.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('staff.dashboard') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-icons-outlined text-lg">grid_view</span>
                    <span>Dashboard</span>
                </a>

                {{-- Arsip Digital --}}
                <a href="{{ route('staff.arsip') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('staff.arsip') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
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

            {{-- Bottom Sidebar matching Image --}}
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

                    {{-- User Profile --}}
                    <div class="relative">
                        <button onclick="toggleProfileDropdown()" class="flex items-center gap-3 py-1.5 px-2 rounded-xl hover:bg-slate-100 transition-all text-left">
                            <div class="w-9 h-9 rounded-full overflow-hidden bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-xs ring-2 ring-blue-200 flex-shrink-0">
                                <img src="{{ asset('images/user-avatar.jpg') }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover" onerror="this.remove(); document.getElementById('staffInitials').classList.remove('hidden');">
                                <span id="staffInitials" class="hidden">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                            </div>
                            <div class="hidden md:block">
                                <p class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                                <p class="text-[10px] text-slate-400 leading-tight">{{ auth()->user()->role?->name }}</p>
                            </div>
                        </button>

                        <div id="profileDropdown" class="dropdown-menu">
                            <div class="p-2">
                                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                                    <p class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-400">{{ auth()->user()->email }} • {{ auth()->user()->role?->name }}</p>
                                </div>
                                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                    <span class="material-icons-outlined text-base">admin_panel_settings</span> Beralih ke Admin Sistem
                                </a>
                                <a href="{{ route('pimpinan.dashboard') }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                    <span class="material-icons-outlined text-base">supervisor_account</span> Beralih ke Pimpinan
                                </a>
                                <a href="{{ route('auditor.dashboard') }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                    <span class="material-icons-outlined text-base">verified_user</span> Beralih ke Auditor
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
                @include('components.flash')
            </main>
        </div>
    </div>

    {{-- Toast Notification --}}
    <div id="toast" class="toast">
        <span id="toastIcon" class="material-icons-outlined text-lg">check_circle</span>
        <span id="toastMessage">Pesan notifikasi</span>
    </div>

    {{-- Universal Upload Modal --}}
    <div id="uploadModal" class="modal-overlay" onclick="if(event.target===this)closeUploadModal()">
        <div class="modal-content max-w-lg w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <span class="material-icons-outlined text-lg">cloud_upload</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Upload Berkas Arsip</h3>
                        <p class="text-xs text-slate-400">Tambah dokumen operasional baru ke sistem</p>
                    </div>
                </div>
                <button onclick="closeUploadModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-xl">close</span></button>
            </div>
            <form onsubmit="handleUniversalStaffUpload(event)" class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Dokumen</label>
                    <input type="text" id="staffUploadName" required placeholder="SK-Rektor-2023-XXX.pdf" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Departemen</label>
                        <select id="staffUploadDept" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="Akademik">Akademik</option>
                            <option value="Keuangan">Keuangan</option>
                            <option value="Hukum & Humas">Hukum & Humas</option>
                            <option value="SDM">SDM</option>
                            <option value="Aset & Sarpras">Aset & Sarpras</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ukuran File</label>
                        <input type="text" id="staffUploadSize" placeholder="2.4 MB" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori</label>
                    <select id="staffUploadCategory" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50/50 rounded-xl p-6 text-center cursor-pointer transition-all" onclick="document.getElementById('hiddenFileInput').click()">
                    <input type="file" id="hiddenFileInput" class="hidden" onchange="staffFileChosen(this)">
                    <span class="material-icons-outlined text-3xl text-slate-300">upload_file</span>
                    <p id="staffFileChosenText" class="text-xs font-semibold text-slate-600 mt-1">Pilih berkas dokumen (PDF, DOCX, XLSX)</p>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeUploadModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold shadow-sm">Upload Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Help Center Modal --}}
    <div id="helpModal" class="modal-overlay" onclick="if(event.target===this)closeHelpModal()">
        <div class="modal-content max-w-lg w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-800">Bantuan Operasional Staff</h3>
                <button onclick="closeHelpModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-xl">close</span></button>
            </div>
            <div class="my-4 space-y-3 text-xs text-slate-600">
                <p>Sebagai <strong>Staff Khusus</strong>, Anda memiliki hak akses untuk mengunggah dokumen baru, memvalidasi nomor resi berkas, serta mengelola arsip harian.</p>
                <p>Dokumen yang baru diunggah akan berstatus <strong>PENDING</strong> sampai diverifikasi atau langsung <strong>TERVERIFIKASI</strong> sesuai aturan divisi.</p>
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
                <h3 class="text-sm font-bold text-slate-800">Notifikasi Staff</h3>
                <button onclick="toggleNotifications()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-lg">close</span></button>
            </div>
            <div class="space-y-2.5 my-4 text-xs">
                <div class="p-3 bg-blue-50/70 border border-blue-100 rounded-xl">
                    <p class="font-bold text-slate-800">8 Dokumen Menunggu Persetujuan</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Berkas telah diteruskan ke pimpinan.</p>
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
        function showUploadModal() { document.getElementById('uploadModal').classList.add('active'); }
        function closeUploadModal() { document.getElementById('uploadModal').classList.remove('active'); }
        function handleLogout() {
            if (confirm('Keluar dari sesi Staff Teddy?')) {
                fetch("{{ route('logout') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                }).then(() => { window.location.href = "{{ route('login') }}"; });
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
        function staffFileChosen(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                document.getElementById('staffFileChosenText').textContent = file.name;
                if (!document.getElementById('staffUploadName').value) {
                    document.getElementById('staffUploadName').value = file.name;
                }
                document.getElementById('staffUploadSize').value = (file.size/1024/1024).toFixed(1) + ' MB';
            }
        }
        function handleUniversalStaffUpload(e) {
            e.preventDefault();
            const name = document.getElementById('staffUploadName').value;
            const dept = document.getElementById('staffUploadDept').value;
            const file = document.getElementById('hiddenFileInput').files[0];
            if (!file) {
                showToast('Pilih berkas sebelum mengunggah.', 'error');
                return;
            }

            const payload = new FormData();
            payload.append('document', file);
            payload.append('title', name);
            payload.append('category_id', document.getElementById('staffUploadCategory').value);

            fetch("{{ route('staff.archives.upload') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: payload,
            }).then(response => {
                if (!response.ok) throw new Error('Upload gagal.');
                return response;
            }).then(() => {
                closeUploadModal();
                showToast(`Berkas "${name}" berhasil dikirim untuk persetujuan.`, 'success');
                setTimeout(() => window.location.reload(), 700);
            }).catch(error => showToast(error.message, 'error'));
        }
        function handleGlobalSearch(e) {
            if (e.key === 'Enter') {
                const query = e.target.value.trim();
                if (query) {
                    window.location.href = `{{ route('staff.arsip') }}?search=${encodeURIComponent(query)}`;
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
