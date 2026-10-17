@php
    $user = auth()->user();
    $roleName = strtolower((string) $user?->role?->name);
    $roleKey = str_contains($roleName, 'admin')
        ? 'admin'
        : (str_contains($roleName, 'pimpin') || str_contains($roleName, 'validator')
            ? 'pimpinan'
            : (str_contains($roleName, 'audit') ? 'auditor' : 'staff'));
    $roleLabels = [
        'admin' => 'Admin Sistem',
        'staff' => 'Staff Arsip',
        'pimpinan' => 'Manajemen Arsip Digital',
        'auditor' => 'Manajemen Arsip Digital',
    ];
    $dashboardRoute = $roleKey.'.dashboard';
    $archiveRoute = $roleKey.'.arsip';
    $uploadRoute = $roleKey === 'admin'
        ? route('admin.archives.store')
        : ($roleKey === 'staff' ? route('staff.archives.upload') : null);
    $canUpload = $uploadRoute !== null;
    $initials = collect(explode(' ', (string) $user?->name))
        ->filter()
        ->map(fn (string $part): string => substr($part, 0, 1))
        ->take(2)
        ->implode('');
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMARSIP')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f8fafc] text-slate-800">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-[230px] bg-white border-r border-slate-200 flex flex-col shrink-0 z-20">
            <div class="px-5 py-5 flex items-center gap-3">
                <div class="w-9 h-9 bg-[#0b1f51] rounded-lg flex items-center justify-center text-white shadow-sm shrink-0">
                    <span class="material-icons-outlined text-xl">account_balance</span>
                </div>
                <div>
                    <h1 class="font-black text-base tracking-wider text-[#0b1f51] leading-none">SIMARSIP</h1>
                    <p class="text-[9px] font-bold text-slate-400 tracking-wider uppercase mt-1">{{ $roleLabels[$roleKey] }}</p>
                </div>
            </div>

            <nav class="flex-1 px-3 py-3 space-y-1">
                <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs($dashboardRoute) ? 'bg-[#1877F2] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-icons-outlined text-lg">grid_view</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route($archiveRoute) }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs($archiveRoute) ? 'bg-[#1877F2] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-icons-outlined text-lg">folder_open</span>
                    <span>Arsip Digital</span>
                </a>
                @if ($roleKey === 'admin')
                    <a href="{{ route('admin.roles') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('admin.roles') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        <span class="material-icons-outlined text-lg">settings</span>
                        <span>Pengaturan</span>
                    </a>
                @elseif ($roleKey === 'pimpinan')
                    <a href="{{ route('pimpinan.persetujuan') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('pimpinan.persetujuan') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        <span class="flex items-center gap-3"><span class="material-icons-outlined text-lg">fact_check</span><span>Persetujuan</span></span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black bg-red-500 text-white">{{ $pendingArchiveCount ?? 0 }}</span>
                    </a>
                @endif
            </nav>

            <div class="p-4 border-t border-slate-200/80 space-y-3">
                @if ($canUpload)
                    <button onclick="showUploadModal()" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-lg text-xs font-semibold shadow-sm transition-all active:scale-[0.98]">
                        <span class="material-icons-outlined text-base">add</span>
                        <span>Upload Dokumen</span>
                    </button>
                @endif
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

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0 z-10">
                <div class="relative w-full max-w-lg">
                    <span class="material-icons-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                    <input type="text" id="globalSearchInput" onkeydown="handleGlobalSearch(event)" placeholder="Mencari data arsip, user, atau log..." class="w-full pl-10 pr-4 py-2 bg-slate-50/70 hover:bg-slate-100/60 focus:bg-white border border-slate-200 focus:border-blue-500 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/15 transition-all">
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="toggleNotifications()" class="relative p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all" title="Notifikasi">
                        <span class="material-icons-outlined text-xl">notifications_none</span>
                        @if (($pendingArchiveCount ?? 0) > 0)
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                        @endif
                    </button>
                    <button onclick="showHelpModal()" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all" title="Bantuan">
                        <span class="material-icons-outlined text-xl">help_outline</span>
                    </button>
                    <div class="h-7 w-px bg-slate-200"></div>
                    <div class="relative">
                        <button onclick="toggleProfileDropdown()" class="flex items-center gap-3 py-1.5 px-2 rounded-xl hover:bg-slate-100 transition-all text-left">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-slate-700 to-slate-900 text-white flex items-center justify-center font-bold text-xs ring-2 ring-slate-100 shrink-0">{{ $initials ?: 'U' }}</div>
                            <div class="hidden md:block">
                                <p class="text-xs font-bold text-slate-800 leading-tight">{{ $user?->name }}</p>
                                <p class="text-[10px] text-slate-400 leading-tight">{{ $user?->role?->name }}</p>
                            </div>
                        </button>
                        <div id="profileDropdown" class="dropdown-menu">
                            <div class="p-2">
                                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                                    <p class="text-xs font-bold text-slate-800">{{ $user?->name }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $user?->email }}</p>
                                </div>
                                <a href="{{ route($dashboardRoute) }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                    <span class="material-icons-outlined text-base">dashboard</span> Dashboard
                                </a>
                                @if ($roleKey === 'admin')
                                    <a href="{{ route('admin.roles') }}" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                                        <span class="material-icons-outlined text-base">settings</span> Pengaturan Sistem
                                    </a>
                                @endif
                                <hr class="my-1 border-slate-100">
                                <button onclick="handleLogout()" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 rounded-lg transition-all text-left">
                                    <span class="material-icons-outlined text-base">logout</span> Keluar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto custom-scroll p-8">
                @yield('content')
                @include('components.flash')
            </main>
        </div>
    </div>

    @if ($canUpload)
        <div id="uploadModal" class="modal-overlay" onclick="if(event.target===this)closeUploadModal()">
            <div class="modal-content max-w-lg w-full p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center"><span class="material-icons-outlined text-lg">cloud_upload</span></div>
                        <div><h3 class="text-base font-bold text-slate-800">Upload Dokumen Baru</h3><p class="text-xs text-slate-400">Simpan berkas ke arsip digital</p></div>
                    </div>
                    <button onclick="closeUploadModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-xl">close</span></button>
                </div>
                <form onsubmit="handleUniversalUpload(event)" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Dokumen</label>
                        <input type="text" id="uploadDocName" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori</label>
                        <select id="uploadDocCategory" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            @foreach (($categories ?? collect()) as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">File Berkas</label>
                        <div onclick="document.getElementById('fileUploadInput').click()" class="border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50/50 rounded-xl p-6 text-center cursor-pointer transition-all">
                            <input type="file" id="fileUploadInput" onchange="handleFileSelected(this)" class="hidden" accept=".pdf,.doc,.docx,.zip,.xls,.xlsx">
                            <span class="material-icons-outlined text-4xl text-slate-300 mb-1">upload_file</span>
                            <p id="fileUploadText" class="text-xs font-semibold text-slate-600">Klik untuk memilih berkas</p>
                            <p class="text-[10px] text-slate-400 mt-1">PDF, DOCX, ZIP, XLSX hingga 50MB</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2.5 pt-2 border-t border-slate-100">
                        <button type="button" onclick="closeUploadModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-[#0b1f51] text-white rounded-xl text-xs font-bold">Upload Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div id="helpModal" class="modal-overlay" onclick="if(event.target===this)closeHelpModal()">
        <div class="modal-content max-w-lg w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-800">Pusat Bantuan</h3>
                <button onclick="closeHelpModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-xl">close</span></button>
            </div>
            <div class="my-4 space-y-3 text-xs text-slate-600">
                <p>Anda masuk sebagai <strong>{{ $user?->role?->name }}</strong>. Gunakan menu Arsip Digital untuk melihat dokumen yang tersedia.</p>
                @if ($roleKey === 'staff')
                    <p>Dokumen yang diunggah staff berstatus <strong>Menunggu</strong> sampai ditinjau Pimpinan.</p>
                @elseif ($roleKey === 'pimpinan')
                    <p>Buka menu Persetujuan untuk meninjau arsip yang menunggu keputusan.</p>
                @elseif ($roleKey === 'auditor')
                    <p>Gunakan daftar arsip untuk menelusuri dan mengunduh dokumen yang tersedia.</p>
                @else
                    <p>Admin dapat mengelola arsip, pengguna, peran, dan hak akses sistem.</p>
                @endif
            </div>
            <div class="pt-2 border-t border-slate-100 flex justify-end"><button onclick="closeHelpModal()" class="px-4 py-2 bg-[#0b1f51] text-white rounded-xl text-xs font-bold">Mengerti</button></div>
        </div>
    </div>

    <div id="notifPanel" class="modal-overlay" onclick="if(event.target===this)toggleNotifications()">
        <div class="modal-content max-w-md w-full p-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-800">Notifikasi</h3>
                <button onclick="toggleNotifications()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-lg">close</span></button>
            </div>
            <div class="my-4 text-xs text-slate-600">
                @if (($pendingArchiveCount ?? 0) > 0)
                    <p class="p-3 bg-amber-50 border border-amber-100 rounded-xl">{{ $pendingArchiveCount }} arsip menunggu pemeriksaan.</p>
                @else
                    <p class="p-3 bg-slate-50 border border-slate-100 rounded-xl">Tidak ada notifikasi baru.</p>
                @endif
            </div>
            <div class="pt-2 border-t border-slate-100 flex justify-end"><button onclick="toggleNotifications()" class="px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold">Tutup</button></div>
        </div>
    </div>

    <div id="toast" class="toast"><span id="toastIcon" class="material-icons-outlined text-lg">check_circle</span><span id="toastMessage">Pesan notifikasi</span></div>

    <script>
        function toggleProfileDropdown() { document.getElementById('profileDropdown').classList.toggle('show'); }
        function toggleNotifications() { document.getElementById('notifPanel').classList.toggle('active'); }
        function showHelpModal() { document.getElementById('helpModal').classList.add('active'); }
        function closeHelpModal() { document.getElementById('helpModal').classList.remove('active'); }
        function showUploadModal() { document.getElementById('uploadModal')?.classList.add('active'); }
        function closeUploadModal() { document.getElementById('uploadModal')?.classList.remove('active'); }
        function handleFileSelected(input) {
            if (input.files?.[0]) {
                document.getElementById('fileUploadText').textContent = `File dipilih: ${input.files[0].name}`;
                if (!document.getElementById('uploadDocName').value) document.getElementById('uploadDocName').value = input.files[0].name;
            }
        }
        function handleUniversalUpload(event) {
            event.preventDefault();
            const file = document.getElementById('fileUploadInput').files[0];
            if (!file) { showToast('Pilih berkas sebelum mengunggah.', 'error'); return; }
            const payload = new FormData();
            payload.append('document', file);
            payload.append('title', document.getElementById('uploadDocName').value.trim());
            payload.append('category_id', document.getElementById('uploadDocCategory').value);
            fetch(@js($uploadRoute), { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }, body: payload })
                .then(response => { if (!response.ok) throw new Error('Dokumen gagal diunggah.'); return response; })
                .then(() => { closeUploadModal(); showToast('Dokumen berhasil diunggah.', 'success'); setTimeout(() => window.location.reload(), 700); })
                .catch(error => showToast(error.message, 'error'));
        }
        function handleGlobalSearch(event) {
            if (event.key === 'Enter') {
                const query = event.target.value.trim();
                window.location.href = `{{ route($archiveRoute) }}${query ? `?search=${encodeURIComponent(query)}` : ''}`;
            }
        }
        function handleLogout() {
            if (!confirm('Keluar dari sesi?')) return;
            fetch("{{ route('logout') }}", { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } })
                .then(() => { window.location.href = "{{ route('login') }}"; });
        }
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            document.getElementById('toastMessage').textContent = message;
            document.getElementById('toastIcon').textContent = type === 'success' ? 'check_circle' : (type === 'error' ? 'error_outline' : 'info');
            toast.className = `toast ${type}`;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3200);
        }
    </script>
    @stack('scripts')
</body>
</html>
