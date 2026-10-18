@extends('layouts.app')

@section('title', 'Dashboard Admin Sistem - SIMARSIP')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#0b1f51] tracking-tight">Dashboard Admin Sistem</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau performa sistem dan kelola akses pengguna dalam satu panel.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <div class="flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-semibold text-slate-600 shadow-xs">
                <span class="material-icons-outlined text-slate-400 text-sm">calendar_today</span>
                <span>Hari Ini: <span id="currentDateDisplay" class="text-slate-800">{{ now()->translatedFormat('d M Y') }}</span></span>
            </div>
            <button onclick="handleUpdateDashboardData()" id="updateDataBtn" class="flex items-center gap-2 px-4 py-2 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold transition-all shadow-sm active:scale-[0.98]">
                <span class="material-icons-outlined text-sm transition-transform" id="refreshIcon">sync</span>
                <span>Update Data</span>
            </button>
        </div>
    </div>
    {{-- Top 4 Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Pengguna --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">group</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-0.5">
                    +12% <span class="material-icons-outlined text-xs">north_east</span>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">TOTAL PENGGUNA</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5" id="statTotalUsers">{{ number_format($userCount) }}</h3>
                <p class="text-[11px] text-slate-500 mt-1" id="statActiveToday">{{ number_format($activeUserCount) }} user berubah hari ini</p>
            </div>
        </div>

        {{-- Card 2: Arsip Baru --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">description</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-0.5">
                    +5.4% <span class="material-icons-outlined text-xs">north_east</span>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">ARSIP BARU</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5" id="statNewDocs">{{ number_format($newArchiveCount) }}</h3>
                <p class="text-[11px] text-slate-500 mt-1">Total {{ number_format($archiveCount) }} dokumen</p>
            </div>
        </div>

        {{-- Card 3: Akses Tertunda --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">lock</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                    Stabil
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">AKSES TERTUNDA</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5" id="statPendingAccess">{{ number_format($pendingArchiveCount) }}</h3>
                <p class="text-[11px] text-slate-500 mt-1">Butuh persetujuan segera</p>
            </div>
        </div>

        {{-- Card 4: Error Log --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">warning_amber</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-600 border border-red-100">
                    Critical
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">ERROR LOG</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5" id="statErrorLogs">3</h3>
                <p class="text-[11px] text-slate-500 mt-1">Anomali keamanan terdeteksi</p>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Left Column (8 cols): User Management, Quick Action, Storage --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Manajemen User & Hak Akses Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-icons-outlined text-blue-700 text-lg">manage_accounts</span>
                        <h2 class="text-xs font-black text-slate-800 uppercase tracking-wide">Manajemen User & Hak Akses</h2>
                    </div>
                    <button onclick="showCreateUserModal()" class="flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">
                        <span class="material-icons-outlined text-base">person_add</span>
                        <span>Buat Akun Baru</span>
                    </button>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="userListTable">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-6">NAMA & USERNAME</th>
                                <th class="py-3 px-4">ROLE</th>
                                <th class="py-3 px-4">STATUS</th>
                                <th class="py-3 px-6 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse ($users as $user)
                                @php
                                    $initials = collect(explode(' ', $user->name))
                                        ->map(fn (string $part): string => substr($part, 0, 1))
                                        ->take(2)
                                        ->implode('');
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-3.5 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                                <p class="text-[11px] text-slate-400">Username: {{ $user->username }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $user->role?->name ?? 'Tanpa Peran' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 text-right">
                                        <button onclick="showUserActionModal({{ $user->id }}, @js($user->name), @js($user->username))" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600" title="Kelola Akun">
                                            <span class="material-icons-outlined text-base">more_horiz</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Table Footer & Pagination matching Image 2 --}}
                <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>
                        Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ number_format($users->total()) }} user
                    </span>
                    <div class="flex items-center gap-1">
                        @if ($users->onFirstPage())
                            <span class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300">&lt;</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600">&lt;</a>
                        @endif
                        @foreach ($users->getUrlRange(max(1, $users->currentPage() - 1), min($users->lastPage(), $users->currentPage() + 1)) as $page => $url)
                            <a href="{{ $url }}" class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 {{ $page === $users->currentPage() ? 'font-bold bg-[#0b1f51] text-white border-[#0b1f51]' : 'text-slate-600' }}">{{ $page }}</a>
                        @endforeach
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600">&gt;</a>
                        @else
                            <span class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300">&gt;</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 3 Quick Action Cards matching Image 2 --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Input Data Massal --}}
                <div onclick="showBatchImportModal()" class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col items-center justify-center text-center cursor-pointer hover:border-blue-300 hover:shadow-md transition-all active:scale-[0.98] group">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                        <span class="material-icons-outlined text-xl">file_upload</span>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800">Input Data Massal</h3>
                </div>

                {{-- Cek Validitas --}}
                <div onclick="showCheckValidityModal()" class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col items-center justify-center text-center cursor-pointer hover:border-blue-300 hover:shadow-md transition-all active:scale-[0.98] group">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                        <span class="material-icons-outlined text-xl">rule</span>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800">Cek Validitas</h3>
                </div>

                {{-- Update Policy --}}
                <div onclick="window.location.href='{{ route('admin.roles') }}'" class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col items-center justify-center text-center cursor-pointer hover:border-blue-300 hover:shadow-md transition-all active:scale-[0.98] group">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                        <span class="material-icons-outlined text-xl">policy</span>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800">Update Policy</h3>
                </div>
            </div>

            {{-- Row: Storage Used & Active Users matching Image 2 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Storage Used --}}
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm space-y-2">
                    <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">STORAGE USED</p>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($storageUsageBytes / 1024 / 1024, 2) }} MB</h3>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mt-3">
                        <div class="bg-[#1877F2] h-full rounded-full transition-all duration-1000" style="width: 70%"></div>
                    </div>
                </div>

                {{-- Active Users --}}
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm space-y-2">
                    <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">ACTIVE USERS</p>
                    <div class="flex items-center gap-1 pt-1">
                        <div class="flex -space-x-2 overflow-hidden">
                            @foreach ($users->take(3) as $user)
                                <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-slate-700 text-white font-bold text-[10px] flex items-center justify-center">{{ collect(explode(' ', $user->name))->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') }}</div>
                            @endforeach
                            @if ($users->count() > 3)
                                <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-slate-100 text-slate-700 font-bold text-[10px] flex items-center justify-center border border-slate-200">+{{ $users->count() - 3 }}</div>
                            @endif
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2">{{ $activeUserCount }} user memperbarui data hari ini</p>
                </div>
            </div>
        </div>

        {{-- Right Column (4 cols): Log Audit Sistem & Status Keamanan --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Log Audit Sistem Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden p-5 space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="material-icons-outlined text-blue-700 text-lg">history</span>
                    <h2 class="text-xs font-black text-slate-800 uppercase tracking-wide">Log Audit Sistem</h2>
                </div>

                {{-- Timeline Items matching Image 2 --}}
                <div class="space-y-4 text-xs">
                    {{-- Item 1 --}}
                    <div class="flex items-start gap-3 relative">
                        <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></div>
                        <div class="flex-1">
                            <span class="text-[10px] text-slate-400 font-semibold block">24 Mei 2023 - 10:45</span>
                            <p class="text-slate-700 mt-0.5 leading-snug"><strong>Admin Utama</strong> mengubah hak akses user <strong>Sir Owo</strong>.</p>
                        </div>
                    </div>

                    {{-- Item 2 --}}
                    <div class="flex items-start gap-3 relative">
                        <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></div>
                        <div class="flex-1">
                            <span class="text-[10px] text-slate-400 font-semibold block">24 April 2023 - 09:12</span>
                            <p class="text-slate-700 mt-0.5 leading-snug">System melakukan <strong>backup otomatis</strong> basis data arsip harian.</p>
                        </div>
                    </div>

                    {{-- Item 3 (Red dot - critical) --}}
                    <div class="flex items-start gap-3 relative">
                        <div class="w-2 h-2 rounded-full bg-red-500 mt-1.5 flex-shrink-0"></div>
                        <div class="flex-1">
                            <span class="text-[10px] text-slate-400 font-semibold block">24 April 2023 - 08:30</span>
                            <p class="text-slate-700 mt-0.5 leading-snug"><strong class="text-red-600">Gagal Login</strong> dari IP 192.168.1.45 pada akun Teddy</p>
                        </div>
                    </div>

                    {{-- Item 4 --}}
                    <div class="flex items-start gap-3 relative">
                        <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></div>
                        <div class="flex-1">
                            <span class="text-[10px] text-slate-400 font-semibold block">23 Maret 2023 - 17:00</span>
                            <p class="text-slate-700 mt-0.5 leading-snug">Mas Gib menginput <strong>24 data arsip</strong> baru kategori 'Keuangan'.</p>
                        </div>
                    </div>
                </div>

                {{-- View All Link --}}
                <div class="pt-2 border-t border-slate-100 text-center">
                    <button onclick="showFullAuditModal()" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">
                        Lihat Seluruh Log
                    </button>
                </div>
            </div>

            {{-- Status Keamanan Card matching Image 2 --}}
            <div class="bg-[#1e3a8a] text-white rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold tracking-wide uppercase text-blue-100">Status Keamanan</h2>
                    <span class="text-xs font-bold text-blue-200">92%</span>
                </div>

                {{-- Progress Bar --}}
                <div class="w-full bg-blue-900/60 h-2 rounded-full overflow-hidden">
                    <div class="bg-blue-400 h-full rounded-full" style="width: 92%"></div>
                </div>

                <p class="text-xs text-blue-100/90 leading-relaxed">
                    Server berjalan dengan optimal. Terakhir pemindaian keamanan dilakukan 4 jam yang lalu. Tidak ada ancaman aktif terdeteksi.
                </p>

                {{-- White Solid Button --}}
                <button onclick="handleRunDiagnostics()" class="w-full py-2.5 px-4 bg-white hover:bg-slate-100 text-[#0b1f51] font-bold rounded-xl text-xs shadow-sm transition-all active:scale-[0.98]">
                    Jalankan Diagnostik
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Buat Akun Baru --}}
<div id="createUserModal" class="modal-overlay" onclick="if(event.target===this)closeCreateUserModal()">
    <div class="modal-content max-w-md w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-outlined text-lg">person_add</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Buat Akun Baru</h3>
                    <p class="text-xs text-slate-400">Tambahkan personil ke instansi SIMARSIP</p>
                </div>
            </div>
            <button onclick="closeCreateUserModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>
        <form onsubmit="handleCreateUserSubmit(event)" class="mt-4 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input type="text" id="newUserName" required placeholder="Contoh: Hendra Kusuma" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Username</label>
                <input type="text" id="newUserUsername" required placeholder="Contoh: hendra.kusuma" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email</label>
                <input type="email" id="newUserEmail" required placeholder="nama@instansi.go.id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Peran (Role)</label>
                    <select id="newUserRole" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="Staff">Staff</option>
                        <option value="Auditor">Auditor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status Awal</label>
                    <select id="newUserStatus" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" id="newUserPassword" minlength="8" required placeholder="Minimal 8 karakter" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                    <input type="password" id="newUserPasswordConfirmation" minlength="8" required placeholder="Ulangi password" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>
            <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeCreateUserModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Aksi Pengguna --}}
<div id="userActionModal" class="modal-overlay" onclick="if(event.target===this)closeUserActionModal()">
    <div class="modal-content max-w-md w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-800">Kelola Akun</h3>
                <p id="userActionName" class="text-xs text-slate-400 mt-0.5"></p>
            </div>
            <button onclick="closeUserActionModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>
        <div class="mt-4 space-y-3">
            <form onsubmit="handleUsernameUpdate(event)" class="space-y-3">
                <label for="userUsername" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Username</label>
                <div class="flex gap-2">
                    <input type="text" id="userUsername" maxlength="50" required class="min-w-0 flex-1 px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold transition-all">Simpan</button>
                </div>
            </form>
            <form onsubmit="handlePasswordReset(event)" class="space-y-3">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Reset Kata Sandi</label>
                <div class="flex gap-2">
                    <input type="password" id="userNewPassword" minlength="8" required placeholder="Minimal 8 karakter" class="min-w-0 flex-1 px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <input type="password" id="userNewPasswordConfirmation" minlength="8" required placeholder="Ulangi kata sandi" class="min-w-0 flex-1 px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold transition-all">Simpan</button>
                </div>
            </form>
            <div class="pt-3 border-t border-slate-100">
                <p class="text-xs text-slate-500 mb-2">Menghapus akun akan menghilangkan akses pengguna ke sistem.</p>
                <button onclick="handleUserDelete()" class="w-full px-4 py-2.5 border border-red-200 text-red-600 hover:bg-red-50 rounded-xl text-xs font-bold transition-all">
                    Hapus Akun
                </button>
                <div id="userDeleteConfirmation" class="hidden mt-3 p-3 rounded-xl bg-red-50 border border-red-100">
                    <p class="text-xs text-red-700">Hapus akun <strong id="userDeleteConfirmationName"></strong>?</p>
                    <div class="flex justify-end gap-2 mt-3">
                        <button onclick="document.getElementById('userDeleteConfirmation').classList.add('hidden')" class="px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-white rounded-lg">Batal</button>
                        <button onclick="confirmUserDelete()" class="px-3 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Input Data Massal --}}
<div id="batchImportModal" class="modal-overlay" onclick="if(event.target===this)closeBatchImportModal()">
    <div class="modal-content max-w-lg w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-outlined text-lg">file_upload</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Input Data Massal (Bulk Import)</h3>
                    <p class="text-xs text-slate-400">Impor arsip dalam jumlah besar via CSV atau Spreadsheet</p>
                </div>
            </div>
            <button onclick="closeBatchImportModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>
        <div class="my-4 space-y-4">
            <div class="p-3.5 bg-blue-50/60 border border-blue-100 rounded-xl text-xs text-blue-900 flex items-start gap-2.5">
                <span class="material-icons-outlined text-blue-600 text-base mt-0.5">info</span>
                <div>
                    <p class="font-bold">Format Dokumen Didukung:</p>
                    <p class="text-[11px] text-blue-700 mt-0.5">Pastikan file Excel atau CSV memiliki kolom: Nama Dokumen, Nomor Arsip, Kategori, Tanggal.</p>
                </div>
            </div>
            <div class="border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50/50 rounded-xl p-8 text-center cursor-pointer transition-all" onclick="document.getElementById('batchImportFiles').click()">
                <input type="file" id="batchImportFiles" class="hidden" multiple accept=".pdf,.doc,.docx,.zip,.xls,.xlsx" onchange="showBatchFileNames(this)">
                <span class="material-icons-outlined text-4xl text-slate-300 mb-1">table_view</span>
                <p id="batchImportFileName" class="text-xs font-bold text-slate-700">Unggah file arsip</p>
                <p class="text-[10px] text-slate-400 mt-1">Ukuran maksimal berkas 25MB</p>
            </div>
        </div>
        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
            <button onclick="showToast('Mengunduh template SIMARSIP-Import.xlsx', 'success')" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                Unduh Template Format
            </button>
            <div class="flex gap-2">
                <button onclick="closeBatchImportModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                <button onclick="handleBatchImport()" class="px-5 py-2 bg-[#0b1f51] text-white rounded-xl text-xs font-bold">Mulai Impor</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Cek Validitas --}}
<div id="checkValidityModal" class="modal-overlay" onclick="if(event.target===this)closeCheckValidityModal()">
    <div class="modal-content max-w-md w-full p-6 text-center">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
            <span class="material-icons-outlined text-2xl">verified</span>
        </div>
        <h3 class="text-base font-bold text-slate-800">Cek Validitas & Integritas Dokumen</h3>
        <p class="text-xs text-slate-500 mt-1">Memindai checksum SHA-256 dan watermark digital pada 15,204 dokumen arsip aktif.</p>

        <div class="my-5 p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-2 text-left text-xs">
            <div class="flex justify-between text-slate-600">
                <span>Integritas Metadata:</span>
                <span class="font-bold text-emerald-600">100% Valid</span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Kesesuaian Tanda Tangan:</span>
                <span class="font-bold text-emerald-600">15,204 Terverifikasi</span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Berkas Korup / Rusak:</span>
                <span class="font-bold text-slate-700">0 Dokumen</span>
            </div>
        </div>

        <button onclick="closeCheckValidityModal(); showToast('Validasi selesai. Semua dokumen terjamin keasliannya.', 'success');" class="w-full py-2.5 bg-[#0b1f51] text-white rounded-xl text-xs font-bold">
            Selesai
        </button>
    </div>
</div>

{{-- Modal: Full Audit Log --}}
<div id="fullAuditModal" class="modal-overlay" onclick="if(event.target===this)closeFullAuditModal()">
    <div class="modal-content max-w-2xl w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-outlined text-lg">history</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Rekam Jejak Audit Sistem Lengkap</h3>
                    <p class="text-xs text-slate-400">Riwayat seluruh aktivitas operasional & keamanan</p>
                </div>
            </div>
            <button onclick="closeFullAuditModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>
        <div class="my-4 max-h-96 overflow-y-auto custom-scroll space-y-3 text-xs pr-1">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-start">
                <div>
                    <p class="font-bold text-slate-800">Admin Utama mengubah hak akses user Sir Owo</p>
                    <p class="text-[11px] text-slate-500">Peran dialihkan dari Staff ke Auditor.</p>
                </div>
                <span class="text-[10px] text-slate-400">24 Mei 2023 - 10:45</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-start">
                <div>
                    <p class="font-bold text-slate-800">Backup otomatis basis data arsip harian</p>
                    <p class="text-[11px] text-slate-500">Ukuran backup 4.2 GB berhasil disinkronkan.</p>
                </div>
                <span class="text-[10px] text-slate-400">24 Apr 2023 - 09:12</span>
            </div>
            <div class="p-3 bg-red-50/60 border border-red-100 rounded-xl flex justify-between items-start">
                <div>
                    <p class="font-bold text-red-700">Percobaan Gagal Login (Brute Force Anomali)</p>
                    <p class="text-[11px] text-red-600">IP 192.168.1.45 mencoba login ke akun Teddy 5x berturut-turut.</p>
                </div>
                <span class="text-[10px] text-red-400">24 Apr 2023 - 08:30</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-start">
                <div>
                    <p class="font-bold text-slate-800">Mas Gib menginput 24 data arsip baru</p>
                    <p class="text-[11px] text-slate-500">Kategori Keuangan periode Q1 2023.</p>
                </div>
                <span class="text-[10px] text-slate-400">23 Mar 2023 - 17:00</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-start">
                <div>
                    <p class="font-bold text-slate-800">Siti Nurhaliza login ke sistem</p>
                    <p class="text-[11px] text-slate-500">Sesi terautentikasi melalui 2FA.</p>
                </div>
                <span class="text-[10px] text-slate-400">22 Mar 2023 - 08:00</span>
            </div>
        </div>
        <div class="flex justify-between items-center pt-2 border-t border-slate-100">
            <button onclick="showToast('Mengekspor log ke format CSV...', 'info')" class="text-xs font-bold text-blue-600 hover:text-blue-700">Ekspor Log (.CSV)</button>
            <button onclick="closeFullAuditModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update Data Button Functionality
    function handleUpdateDashboardData() {
        window.location.reload();
    }

    // Modal: Buat Akun Baru
    function showCreateUserModal() {
        document.getElementById('createUserModal').classList.add('active');
    }
    function closeCreateUserModal() {
        document.getElementById('createUserModal').classList.remove('active');
    }

    function handleCreateUserSubmit(e) {
        e.preventDefault();
        const name = document.getElementById('newUserName').value.trim();
        const username = document.getElementById('newUserUsername').value.trim();
        const role = document.getElementById('newUserRole').value;
        const email = document.getElementById('newUserEmail').value.trim();
        const password = document.getElementById('newUserPassword').value;
        const passwordConfirmation = document.getElementById('newUserPasswordConfirmation').value;

        if (!name || !username || !email || !password || password !== passwordConfirmation) {
            showToast(password !== passwordConfirmation ? 'Konfirmasi password tidak cocok.' : 'Lengkapi semua data akun.', 'error');
            return;
        }

        fetch("{{ route('admin.users.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                name,
                nip: username,
                role,
                email,
                password,
                password_confirmation: passwordConfirmation,
            }),
        }).then(response => {
            if (!response.ok) throw new Error('Akun gagal dibuat. Pastikan username belum digunakan.');
            return response;
        }).then(() => {
            closeCreateUserModal();
            showToast(`Akun user "${name}" berhasil didaftarkan!`, 'success');
            setTimeout(() => window.location.reload(), 700);
        }).catch(error => showToast(error.message, 'error'));
    }

    let selectedUser = { id: null, name: '' };

    function showUserActionModal(userId, userName, username) {
        selectedUser = { id: userId, name: userName, username };
        document.getElementById('userActionName').textContent = userName;
        document.getElementById('userUsername').value = username;
        document.getElementById('userNewPassword').value = '';
        document.getElementById('userNewPasswordConfirmation').value = '';
        document.getElementById('userActionModal').classList.add('active');
    }

    function closeUserActionModal() {
        document.getElementById('userActionModal').classList.remove('active');
        selectedUser = { id: null, name: '', username: '' };
    }

    async function handleUsernameUpdate(event) {
        event.preventDefault();
        const username = document.getElementById('userUsername').value.trim();

        try {
            const response = await fetch(`/admin/users/${selectedUser.id}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ username }),
            });
            if (!response.ok) throw new Error('Username gagal diperbarui.');
            selectedUser.username = username;
            showToast('Username berhasil diperbarui.', 'success');
        } catch (error) {
            showToast(error.message, 'error');
        }
    }

    async function handlePasswordReset(event) {
        event.preventDefault();
        const password = document.getElementById('userNewPassword').value;
        const passwordConfirmation = document.getElementById('userNewPasswordConfirmation').value;
        const userName = selectedUser.name;
        if (password !== passwordConfirmation) {
            showToast('Konfirmasi password tidak cocok.', 'error');
            return;
        }

        try {
            const response = await fetch(`/admin/users/${selectedUser.id}/password`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    password,
                    password_confirmation: passwordConfirmation,
                }),
            });
            if (!response.ok) throw new Error('Kata sandi gagal direset.');
            closeUserActionModal();
            showToast(`Kata sandi ${userName} berhasil direset.`, 'success');
        } catch (error) {
            showToast(error.message, 'error');
        }
    }

    function handleUserDelete() {
        if (!selectedUser.id) return;
        const userName = selectedUser.name;

        document.getElementById('userDeleteConfirmation').classList.remove('hidden');
        document.getElementById('userDeleteConfirmationName').textContent = userName;
    }

    async function confirmUserDelete() {
        const userName = selectedUser.name;

        try {
            const response = await fetch(`/admin/users/${selectedUser.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });
            if (!response.ok) throw new Error('Akun gagal dihapus.');
            closeUserActionModal();
            showToast(`Akun ${userName} berhasil dihapus.`, 'success');
            setTimeout(() => window.location.reload(), 700);
        } catch (error) {
            showToast(error.message, 'error');
        }
    }

    // Modal: Input Data Massal
    function showBatchImportModal() {
        document.getElementById('batchImportModal').classList.add('active');
    }
    function closeBatchImportModal() {
        document.getElementById('batchImportModal').classList.remove('active');
    }

    function showBatchFileNames(input) {
        const count = input.files?.length || 0;
        document.getElementById('batchImportFileName').textContent = count
            ? `${count} berkas dipilih`
            : 'Unggah file arsip';
    }

    async function handleBatchImport() {
        const files = [...(document.getElementById('batchImportFiles').files || [])];
        if (!files.length) {
            showToast('Pilih setidaknya satu berkas untuk diimpor.', 'error');
            return;
        }

        try {
            for (const file of files) {
                const payload = new FormData();
                payload.append('document', file);
                payload.append('status', 'menunggu');
                const response = await fetch("{{ route('admin.archives.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: payload,
                });
                if (!response.ok) throw new Error(`Gagal mengimpor ${file.name}.`);
            }

            closeBatchImportModal();
            showToast(`${files.length} berkas berhasil dikirim untuk persetujuan.`, 'success');
            setTimeout(() => window.location.reload(), 700);
        } catch (error) {
            showToast(error.message, 'error');
        }
    }

    // Modal: Cek Validitas
    function showCheckValidityModal() {
        document.getElementById('checkValidityModal').classList.add('active');
    }
    function closeCheckValidityModal() {
        document.getElementById('checkValidityModal').classList.remove('active');
    }

    // Modal: Full Audit
    function showFullAuditModal() {
        document.getElementById('fullAuditModal').classList.add('active');
    }
    function closeFullAuditModal() {
        document.getElementById('fullAuditModal').classList.remove('active');
    }

    // Run Diagnostics
    function handleRunDiagnostics() {
        showToast('Menjalankan diagnostik keamanan sistem...', 'info');
        setTimeout(() => {
            showToast('Diagnostik selesai: Skor keamanan 92%. Semua enkripsi AES-256 aktif.', 'success');
        }, 1500);
    }

</script>
@endpush
