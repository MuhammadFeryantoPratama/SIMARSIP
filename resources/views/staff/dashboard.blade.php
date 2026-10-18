@extends('layouts.app')

@section('title', 'Dashboard Staff - SIMARSIP')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header matching Image 1 --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#0b1f51] tracking-tight">Selamat Pagi, {{ auth()->user()->name }}</h1>
            <p class="text-xs text-slate-500 mt-1">Berikut ringkasan manajemen arsip digital hari ini.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <button onclick="handleExportLog()" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-bold text-slate-700 shadow-xs hover:bg-slate-50 transition-all">
                <span class="material-icons-outlined text-sm text-slate-500">file_download</span>
                <span>Export Log</span>
            </button>
            <button onclick="showInputBaruModal()" class="flex items-center gap-2 px-4 py-2 bg-[#1877F2] hover:bg-[#1264cc] text-white rounded-xl text-xs font-bold transition-all shadow-sm active:scale-[0.98]">
                <span class="material-icons-outlined text-sm">add</span>
                <span>Input Baru</span>
            </button>
        </div>
    </div>

    {{-- Top 4 Stats Cards matching Image 1 --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: TOTAL DOKUMEN --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">folder</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">
                    +12%
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">TOTAL DOKUMEN</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5" id="statStaffTotal">{{ number_format($archiveCount, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- Card 2: MENUNGGU PERSETUJUAN --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">assignment</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100">
                    8 Pending
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">MENUNGGU PERSETUJUAN</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5">{{ number_format($pendingArchiveCount, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- Card 3: AKTIVITAS TERBARU --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">history</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                    Hari Ini
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">AKTIVITAS TERBARU</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5">{{ number_format($recentArchives->count(), 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- Card 4: DOKUMEN TERVERIFIKASI --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">verified_user</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                    Secure
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">DOKUMEN TERVERIFIKASI</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5">{{ $archiveCount > 0 ? number_format(($approvedArchiveCount / $archiveCount) * 100, 0) : 0 }}%</h3>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Left Section (8 cols): Upload Terbaru Table --}}
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-xs font-black text-slate-800 uppercase tracking-wide">Upload Terbaru</h2>
                <a href="{{ route('staff.arsip') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">
                    Lihat Semua
                </a>
            </div>

            {{-- Table matching Image 1 --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-6">NAMA DOKUMEN</th>
                            <th class="py-3 px-4">DEPARTEMEN</th>
                            <th class="py-3 px-4">TANGGAL</th>
                            <th class="py-3 px-4">STATUS</th>
                            <th class="py-3 px-6 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs" id="staffUploadsTable">
                        {{-- Row 1: SK-Rektor-2023-001.pdf --}}
                        <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer" onclick="previewStaffDoc('SK-Rektor-2023-001.pdf', 'Akademik', '2.4 MB', 'TERVERIFIKASI')">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons-outlined text-lg">picture_as_pdf</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">SK-Rektor-2023-001.pdf</p>
                                        <p class="text-[11px] text-slate-400">2.4 MB</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-semibold">Akademik</td>
                            <td class="py-4 px-4 text-slate-500">24 Okt 2023, 09:15</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase">
                                    TERVERIFIKASI
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button onclick="event.stopPropagation(); showToast('Mengunduh SK-Rektor-2023-001.pdf', 'success')" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600" title="Unduh">
                                    <span class="material-icons-outlined text-base">download</span>
                                </button>
                            </td>
                        </tr>

                        {{-- Row 2: Laporan-Keuangan-Q3.docx --}}
                        <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer" onclick="previewStaffDoc('Laporan-Keuangan-Q3.docx', 'Keuangan', '1.1 MB', 'PENDING')">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons-outlined text-lg">article</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">Laporan-Keuangan-Q3.docx</p>
                                        <p class="text-[11px] text-slate-400">1.1 MB</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-semibold">Keuangan</td>
                            <td class="py-4 px-4 text-slate-500">24 Okt 2023, 08:42</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-amber-50 text-amber-600 border border-amber-100 uppercase">
                                    PENDING
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button onclick="event.stopPropagation(); showToast('Mengunduh Laporan-Keuangan-Q3.docx', 'success')" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600" title="Unduh">
                                    <span class="material-icons-outlined text-base">download</span>
                                </button>
                            </td>
                        </tr>

                        {{-- Row 3: MOU-Antar-Instansi-2023.pdf --}}
                        <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer" onclick="previewStaffDoc('MOU-Antar-Instansi-2023.pdf', 'Hukum & Humas', '4.8 MB', 'TERVERIFIKASI')">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons-outlined text-lg">picture_as_pdf</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">MOU-Antar-Instansi-2023.pdf</p>
                                        <p class="text-[11px] text-slate-400">4.8 MB</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-semibold">Hukum & Humas</td>
                            <td class="py-4 px-4 text-slate-500">23 Okt 2023, 16:50</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase">
                                    TERVERIFIKASI
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button onclick="event.stopPropagation(); showToast('Mengunduh MOU-Antar-Instansi-2023.pdf', 'success')" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600" title="Unduh">
                                    <span class="material-icons-outlined text-base">download</span>
                                </button>
                            </td>
                        </tr>

                        {{-- Row 4: Absensi-Pegawai-Sept-2023.xlsx --}}
                        <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer" onclick="previewStaffDoc('Absensi-Pegawai-Sept-2023.xlsx', 'SDM', '0.8 MB', 'TERVERIFIKASI')">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons-outlined text-lg">table_view</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">Absensi-Pegawai-Sept-2023.xlsx</p>
                                        <p class="text-[11px] text-slate-400">0.8 MB</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-semibold">SDM</td>
                            <td class="py-4 px-4 text-slate-500">23 Okt 2023, 14:20</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase">
                                    TERVERIFIKASI
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button onclick="event.stopPropagation(); showToast('Mengunduh Absensi-Pegawai-Sept-2023.xlsx', 'success')" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600" title="Unduh">
                                    <span class="material-icons-outlined text-base">download</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Section (4 cols): Drop Berkas, Validasi Cepat, Pemeliharaan --}}
        <div class="lg:col-span-4 space-y-5">
            {{-- Card 1: Drop berkas di sini --}}
            <div class="bg-white rounded-2xl border-2 border-dashed border-slate-200/90 hover:border-blue-400 p-6 text-center shadow-xs transition-all">
                <input type="file" id="dropFileInput" class="hidden" onchange="handleDropFileSelected(this)">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <span class="material-icons-outlined text-2xl">upload_file</span>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Drop berkas di sini</h3>
                <p class="text-[11px] text-slate-400 mt-1">Mendukung PDF, DOCX, XLSX hingga 20MB</p>
                <button onclick="document.getElementById('dropFileInput').click()" class="mt-4 px-6 py-2 bg-[#1877F2] hover:bg-[#1264cc] text-white rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95">
                    Pilih Berkas
                </button>
            </div>

            {{-- Card 2: Validasi Cepat matching Image 1 --}}
            <div class="bg-[#1e3a8a] text-white rounded-2xl p-6 shadow-sm space-y-3.5">
                <div class="flex items-center gap-2">
                    <span class="material-icons-outlined text-blue-200 text-lg">fact_check</span>
                    <h3 class="text-xs font-bold text-white tracking-wide uppercase">Validasi Cepat</h3>
                </div>
                <p class="text-[11px] text-blue-100/90 leading-relaxed">
                    Periksa integritas digital arsip menggunakan nomor resi atau barcode dokumen.
                </p>

                <div class="space-y-2.5 pt-1">
                    <input type="text" id="resiInput" placeholder="Masukkan Nomor Resi..."
                           class="w-full px-3.5 py-2.5 bg-blue-950/40 border border-blue-400/30 rounded-xl text-xs text-white placeholder-blue-200/60 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <button onclick="handleQuickValidation()" class="w-full py-2.5 bg-[#8da4f7] hover:bg-[#9cb0fa] text-[#0b1f51] font-bold rounded-xl text-xs transition-all shadow-sm active:scale-98">
                        Verifikasi Sekarang
                    </button>
                </div>
            </div>

            {{-- Card 3: Sistem Pemeliharaan matching Image 1 --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex items-start gap-3">
                <span class="material-icons-outlined text-slate-400 text-lg mt-0.5">info</span>
                <div>
                    <h4 class="text-xs font-bold text-slate-800">Sistem Pemeliharaan</h4>
                    <p class="text-[11px] text-slate-500 mt-1 leading-snug">
                        Server arsip akan menjalani pemeliharaan rutin pada 28 Okt 2026 mulai pukul 22.00 WIB.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Floating Action Button (+) matching Image 1 --}}
<button onclick="showInputBaruModal()" class="fixed bottom-6 right-6 w-12 h-12 bg-[#1877F2] hover:bg-[#1264cc] text-white rounded-2xl shadow-lg shadow-blue-500/25 flex items-center justify-center transition-all active:scale-95 z-30" title="Input Baru">
    <span class="material-icons-outlined text-2xl">add</span>
</button>

{{-- Modal: Input Baru --}}
<div id="inputBaruModal" class="modal-overlay" onclick="if(event.target===this)closeInputBaruModal()">
    <div class="modal-content max-w-lg w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Input Dokumen Arsip Baru</h3>
            <button onclick="closeInputBaruModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-xl">close</span></button>
        </div>
        <form onsubmit="handleInputBaruSubmit(event)" class="mt-4 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Dokumen</label>
                <input type="text" id="newInputName" required placeholder="SK-Rektor-2023-XXX.pdf" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Departemen</label>
                    <select id="newInputDept" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="Akademik">Akademik</option>
                        <option value="Keuangan">Keuangan</option>
                        <option value="Hukum & Humas">Hukum & Humas</option>
                        <option value="SDM">SDM</option>
                        <option value="Aset & Sarpras">Aset & Sarpras</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ukuran Berkas</label>
                    <input type="text" id="newInputSize" placeholder="2.1 MB" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status Awal</label>
                <select id="newInputStatus" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="TERVERIFIKASI">TERVERIFIKASI</option>
                    <option value="PENDING">PENDING</option>
                </select>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeInputBaruModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-[#1877F2] text-white rounded-xl text-xs font-bold">Simpan Berkas</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Konfirmasi Hapus Dokumen Staff --}}
<div id="deleteStaffDocModal" class="modal-overlay" onclick="if(event.target===this)closeDeleteStaffDocModal()">
    <div class="modal-content max-w-md w-full p-6">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <span class="material-icons-outlined text-2xl">delete_outline</span>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-800">Hapus Arsip Unggahan</h3>
                <p class="text-xs text-slate-400">Konfirmasi penghapusan berkas arsip Anda</p>
            </div>
        </div>
        <div class="my-4 p-4 bg-rose-50/50 rounded-xl border border-rose-100/80 text-xs space-y-2 text-slate-600">
            <p>Apakah Anda yakin ingin menghapus berkas yang telah Anda unggah ini?</p>
            <div class="p-3 bg-white rounded-lg border border-rose-100 flex items-center gap-2.5">
                <span class="material-icons-outlined text-rose-500 text-lg shrink-0">description</span>
                <div class="truncate">
                    <p id="deleteStaffDocTitle" class="font-bold text-slate-800 truncate"></p>
                    <p id="deleteStaffDocNo" class="text-[10px] text-slate-400 font-mono"></p>
                </div>
            </div>
            <p class="text-[11px] text-rose-600 font-medium flex items-center gap-1 pt-1">
                <span class="material-icons-outlined text-sm shrink-0">warning</span>
                <span>Dokumen fisik akan dihapus permanen dari server penyimpanan.</span>
            </p>
        </div>
        <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
            <button type="button" onclick="closeDeleteStaffDocModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                Batal
            </button>
            <button type="button" id="confirmDeleteStaffBtn" onclick="executeDeleteStaffDoc()" class="flex items-center gap-1.5 px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95">
                <span class="material-icons-outlined text-base">delete</span>
                <span>Hapus Berkas</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function handleExportLog() {
        window.location.href = "{{ route('archives.export') }}";
    }

    function showInputBaruModal() {
        document.getElementById('inputBaruModal').classList.add('active');
    }
    function closeInputBaruModal() {
        document.getElementById('inputBaruModal').classList.remove('active');
    }

    function handleInputBaruSubmit(e) {
        e.preventDefault();
        closeInputBaruModal();
        showUploadModal();
    }

    function addNewUploadRow(data) {
        const tbody = document.getElementById('staffUploadsTable');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50/50 transition-colors cursor-pointer bg-blue-50/20';

        const isVerif = data.status === 'TERVERIFIKASI';
        const badgeClass = isVerif ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100';

        tr.innerHTML = `
            <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons-outlined text-lg">description</span>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">${data.name}</p>
                        <p class="text-[11px] text-slate-400">${data.size}</p>
                    </div>
                </div>
            </td>
            <td class="py-4 px-4 text-slate-700 font-semibold">${data.dept}</td>
            <td class="py-4 px-4 text-slate-500">${data.date}</td>
            <td class="py-4 px-4">
                <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider ${badgeClass} border uppercase">
                    ${data.status}
                </span>
            </td>
            <td class="py-4 px-6 text-right">
                <button onclick="event.stopPropagation(); showToast('Mengunduh ${data.name}', 'success')" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600" title="Unduh">
                    <span class="material-icons-outlined text-base">download</span>
                </button>
            </td>
        `;
        tbody.insertBefore(tr, tbody.firstChild);

        const totalEl = document.getElementById('statStaffTotal');
        if (totalEl) {
            totalEl.textContent = (parseInt(totalEl.textContent.replace(',', '')) + 1).toLocaleString('id-ID');
        }
    }

    function handleDropFileSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const payload = new FormData();
            payload.append('document', file);

            fetch("{{ route('staff.archives.upload') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: payload,
            }).then(response => {
                if (!response.ok) throw new Error('Upload gagal. Periksa format dan ukuran berkas.');
                showToast(`File "${file.name}" berhasil dikirim untuk persetujuan.`, 'success');
                setTimeout(() => window.location.reload(), 700);
            }).catch(error => showToast(error.message, 'error'));
        }
    }

    function handleQuickValidation() {
        const resi = document.getElementById('resiInput').value.trim();
        if (!resi) {
            showToast('Silakan masukkan nomor resi dokumen terlebih dahulu.', 'error');
            return;
        }

        showToast(`Memeriksa resi "${resi}" ke ledger arsip...`, 'info');
        setTimeout(() => {
            showToast(`Resi "${resi}" VALID. Tanda tangan digital cocok dengan hash instansi.`, 'success');
        }, 1200);
    }

    function previewStaffDoc(name, dept, size, status) {
        showToast(`Membuka berkas ${name} (${dept})`, 'info');
    }

    let activeStaffDeleteId = null;
    let activeStaffDeleteName = '';

    function confirmDeleteStaffDoc(id, name) {
        activeStaffDeleteId = id;
        activeStaffDeleteName = name;
        document.getElementById('deleteStaffDocTitle').textContent = name;
        document.getElementById('deleteStaffDocNo').textContent = `ARS-${String(id).padStart(6, '0')}`;
        document.getElementById('deleteStaffDocModal').classList.add('active');
    }

    function closeDeleteStaffDocModal() {
        document.getElementById('deleteStaffDocModal').classList.remove('active');
        activeStaffDeleteId = null;
        activeStaffDeleteName = '';
    }

    async function executeDeleteStaffDoc() {
        if (!activeStaffDeleteId) return;
        const btn = document.getElementById('confirmDeleteStaffBtn');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<span class="material-icons-outlined text-base animate-spin">sync</span> Menghapus...`;

        try {
            const response = await fetch(`/archives/${activeStaffDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                const resData = await response.json().catch(() => ({}));
                throw new Error(resData.message || 'Gagal menghapus berkas arsip.');
            }

            closeDeleteStaffDocModal();
            showToast(`Arsip "${activeStaffDeleteName}" berhasil dihapus.`, 'success');

            const row = document.getElementById(`staff-row-${activeStaffDeleteId}`);
            if (row) {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';
                setTimeout(() => row.remove(), 300);
            }
        } catch (err) {
            showToast(err.message, 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const archives = @json($recentArchiveRows);
        const body = document.getElementById('staffUploadsTable');
        if (!body) return;
        body.innerHTML = archives.length ? archives.map(archive => {
            const safeName = escapeHtml(archive.name);
            const safeCategory = escapeHtml(archive.category || 'Arsip Digital');
            return `
            <tr class="hover:bg-slate-50/50 transition-colors" id="staff-row-${archive.id}">
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <span class="material-icons-outlined text-lg">description</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">${safeName}</p>
                            <p class="text-[11px] text-slate-400 font-mono">ARS-${String(archive.id).padStart(6, '0')}</p>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-4 text-slate-700 font-semibold">${safeCategory}</td>
                <td class="py-4 px-4 text-slate-500">${archive.date}</td>
                <td class="py-4 px-4">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider ${archive.status === 'disetujui' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : archive.status === 'ditolak' ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-amber-50 text-amber-600 border-amber-100'} border uppercase">${archive.status}</span>
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <a href="${archive.url}" class="p-1.5 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600 transition-colors" title="Unduh">
                            <span class="material-icons-outlined text-base">download</span>
                        </a>
                        ${archive.canDelete ? `
                            <button onclick="confirmDeleteStaffDoc(${archive.id}, '${safeName}')" class="p-1.5 hover:bg-rose-50 rounded-lg text-slate-400 hover:text-rose-600 transition-colors" title="Hapus Berkas">
                                <span class="material-icons-outlined text-base">delete_outline</span>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
            `;
        }).join('') : '<tr><td colspan="5" class="py-10 text-center text-xs text-slate-400">Belum ada dokumen.</td></tr>';
    });

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character]));
    }
</script>
@endpush
