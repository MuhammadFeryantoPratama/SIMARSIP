@extends('layouts.pimpinan')

@section('title', 'Dashboard Pimpinan - SIMARSIP')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#0b1f51] tracking-tight">Dashboard Pimpinan</h1>
            <p class="text-xs text-slate-500 mt-1">Selamat datang kembali. Berikut ringkasan performa arsip digital hari ini.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <button onclick="handleChangePeriod()" class="flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-semibold text-slate-700 shadow-xs hover:bg-slate-50 transition-all">
                <span class="material-icons-outlined text-slate-400 text-sm">calendar_today</span>
                <span id="periodText">Mei 2024</span>
            </button>
            <button onclick="handleExportReport()" class="flex items-center gap-2 px-4 py-2 bg-[#1877F2] hover:bg-[#1264cc] text-white rounded-xl text-xs font-bold transition-all shadow-sm active:scale-[0.98]">
                <span class="material-icons-outlined text-sm">download</span>
                <span>Export Laporan</span>
            </button>
        </div>
    </div>

    {{-- Top 3 Stat Cards matching Image 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        {{-- Card 1: TOTAL ARSIP DIGITAL (6 cols) --}}
        <div class="lg:col-span-6 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">TOTAL ARSIP DIGITAL</p>
                <div class="flex items-baseline gap-3 mt-2">
                    <h2 class="text-4xl font-black text-slate-800 tracking-tight" id="statTotalArsip">128,492</h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-0.5">
                        <span class="material-icons-outlined text-xs">trending_up</span> +12.5%
                    </span>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 italic mt-6">Pertumbuhan arsip dalam 30 hari terakhir</p>
        </div>

        {{-- Card 2: Dokumen Masuk (3 cols) --}}
        <div class="lg:col-span-3 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-icons-outlined text-xl">description</span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-700">Dokumen Masuk</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-1" id="statDokumenMasuk">1,204</h3>
                <p class="text-[11px] text-slate-400 mt-1">24 menunggu persetujuan</p>
            </div>
        </div>

        {{-- Card 3: Storage Terpakai (3 cols) --}}
        <div class="lg:col-span-3 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <span class="material-icons-outlined text-xl">format_list_bulleted</span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-700">Storage Terpakai</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-1">2.4 TB</h3>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mt-2.5">
                    <div class="bg-[#1877F2] h-full rounded-full" style="width: 65%"></div>
                </div>
                <p class="text-[11px] text-slate-400 mt-2">65% dari kuota tersedia</p>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Left Section (8 cols): Approval Queue & Visual Charts --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Antrean Persetujuan (Approval Queue) Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-xs font-black text-slate-800 uppercase tracking-wide">Antrean Persetujuan (Approval Queue)</h2>
                    <a href="{{ route('pimpinan.persetujuan') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">
                        Lihat Semua
                    </a>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-6">NAMA DOKUMEN</th>
                                <th class="py-3 px-4">PENGIRIM</th>
                                <th class="py-3 px-4">PRIORITAS</th>
                                <th class="py-3 px-6 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs" id="dashboardApprovalTable">
                            {{-- Row 1: Laporan_Keuangan_Q1_2024.pdf --}}
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                            <span class="material-icons-outlined text-lg">picture_as_pdf</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">Laporan_Keuangan_Q1_2024.pdf</p>
                                            <p class="text-[11px] text-slate-400">Ref: ARS-2024-001 • 12.4 MB</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-slate-700">Divisi Keuangan</p>
                                    <p class="text-[11px] text-slate-400">10 menit yang lalu</p>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-rose-50 text-rose-600 border border-rose-100 uppercase">
                                        MENDESAK
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <button onclick="quickApprove(this, 'Laporan_Keuangan_Q1_2024.pdf')" class="px-3 py-1.5 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-lg text-xs font-bold transition-all shadow-xs">
                                        Setujui
                                    </button>
                                </td>
                            </tr>

                            {{-- Row 2: Mou_Kerjasama_Stategis_V2.docx --}}
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                            <span class="material-icons-outlined text-lg">article</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">Mou_Kerjasama_Stategis_V2.docx</p>
                                            <p class="text-[11px] text-slate-400">Ref: LEG-2024-042 • 1.2 MB</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-slate-700">Biro Hukum</p>
                                    <p class="text-[11px] text-slate-400">2 jam yang lalu</p>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-blue-50 text-blue-600 border border-blue-100 uppercase">
                                        NORMAL
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <button onclick="quickApprove(this, 'Mou_Kerjasama_Stategis_V2.docx')" class="px-3 py-1.5 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-lg text-xs font-bold transition-all shadow-xs">
                                        Setujui
                                    </button>
                                </td>
                            </tr>

                            {{-- Row 3: Scan_Sertifikat_Aset_K09.jpg --}}
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                            <span class="material-icons-outlined text-lg">image</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">Scan_Sertifikat_Aset_K09.jpg</p>
                                            <p class="text-[11px] text-slate-400">Ref: AST-2024-089 • 4.5 MB</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-slate-700">Aset & Sarpras</p>
                                    <p class="text-[11px] text-slate-400">Kemarin, 16:45</p>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-slate-100 text-slate-600 border border-slate-200 uppercase">
                                        RENDAH
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <button onclick="quickApprove(this, 'Scan_Sertifikat_Aset_K09.jpg')" class="px-3 py-1.5 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-lg text-xs font-bold transition-all shadow-xs">
                                        Setujui
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2 Bottom Visual Cards: Aktivitas Departemen & Komposisi Dokumen --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Card 1: Aktivitas Departemen (Bar Chart) --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-4">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wide">Aktivitas Departemen</h3>

                    {{-- Bar Chart matching visual in screenshot --}}
                    <div class="h-44 flex items-end justify-between gap-3 pt-6 px-2">
                        {{-- KUAN --}}
                        <div class="flex-1 flex flex-col items-center gap-2 group cursor-pointer">
                            <div class="w-full bg-slate-200/80 group-hover:bg-blue-600 rounded-t-lg transition-all" style="height: 65%" title="Keuangan: 65%"></div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">KUAN</span>
                        </div>
                        {{-- HKM --}}
                        <div class="flex-1 flex flex-col items-center gap-2 group cursor-pointer">
                            <div class="w-full bg-slate-200/80 group-hover:bg-blue-600 rounded-t-lg transition-all" style="height: 35%" title="Hukum: 35%"></div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">HKM</span>
                        </div>
                        {{-- SDM --}}
                        <div class="flex-1 flex flex-col items-center gap-2 group cursor-pointer">
                            <div class="w-full bg-slate-200/80 group-hover:bg-blue-600 rounded-t-lg transition-all" style="height: 50%" title="SDM: 50%"></div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">SDM</span>
                        </div>
                        {{-- UMUM --}}
                        <div class="flex-1 flex flex-col items-center gap-2 group cursor-pointer">
                            <div class="w-full bg-slate-200/80 group-hover:bg-blue-600 rounded-t-lg transition-all" style="height: 85%" title="Umum: 85%"></div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">UMUM</span>
                        </div>
                        {{-- TI --}}
                        <div class="flex-1 flex flex-col items-center gap-2 group cursor-pointer">
                            <div class="w-full bg-slate-200/80 group-hover:bg-blue-600 rounded-t-lg transition-all" style="height: 25%" title="TI: 25%"></div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">TI</span>
                        </div>
                        {{-- ASET --}}
                        <div class="flex-1 flex flex-col items-center gap-2 group cursor-pointer">
                            <div class="w-full bg-slate-200/80 group-hover:bg-blue-600 rounded-t-lg transition-all" style="height: 45%" title="Aset & Sarpras: 45%"></div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">ASET</span>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Komposisi Dokumen (Donut Chart) --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-4">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wide">Komposisi Dokumen</h3>

                    <div class="flex flex-col items-center justify-center py-2">
                        {{-- SVG Donut Chart with center text --}}
                        <div class="relative w-36 h-36 flex items-center justify-center">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                {{-- Base track --}}
                                <circle cx="50" cy="50" r="38" fill="none" stroke="#e2e8f0" stroke-width="14" />
                                {{-- Surat Dinas (72%) --}}
                                <circle cx="50" cy="50" r="38" fill="none" stroke="#1e3a8a" stroke-width="14"
                                        stroke-dasharray="238.76" stroke-dashoffset="66.85" stroke-linecap="butt" />
                                {{-- Kontrak (15%) --}}
                                <circle cx="50" cy="50" r="38" fill="none" stroke="#2563eb" stroke-width="14"
                                        stroke-dasharray="238.76" stroke-dashoffset="202.9" class="origin-center rotate-[260deg]" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                <span class="text-2xl font-black text-slate-800 tracking-tight leading-none">72%</span>
                                <span class="text-[11px] text-slate-400 font-semibold mt-1">Surat</span>
                            </div>
                        </div>

                        {{-- Legend matching Screenshot --}}
                        <div class="grid grid-cols-2 gap-x-6 gap-y-1.5 mt-5 text-[11px] font-semibold text-slate-600">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-[#1e3a8a]"></span>
                                <span>Surat Dinas</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-[#2563eb]"></span>
                                <span>Kontrak</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-slate-800"></span>
                                <span>Sertifikat</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <span>Lainnya</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Section (4 cols): Status Sistem & Aktivitas Terkini --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Status Sistem (Dark Navy Card) --}}
            <div class="bg-[#0b1f51] text-white rounded-2xl p-6 shadow-sm space-y-4">
                <h2 class="text-xs font-black tracking-wider uppercase text-blue-100">Status Sistem</h2>

                <div class="space-y-3 text-xs border-y border-white/10 py-3.5">
                    <div class="flex items-center justify-between">
                        <span class="text-blue-200/80">Konektivitas Cloud</span>
                        <span class="inline-flex items-center gap-1.5 font-bold text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Stabil
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-200/80">Backup Terakhir</span>
                        <span class="font-bold text-white">Hari ini, 04:00</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-200/80">User Online</span>
                        <span class="font-bold text-white">42 Aktif</span>
                    </div>
                </div>

                <button onclick="showAuditModal()" class="w-full py-2.5 px-4 bg-[#1a3880] hover:bg-[#23479e] text-white font-bold rounded-xl text-xs transition-all shadow-xs">
                    Buka Log Audit Lengkap
                </button>
            </div>

            {{-- Aktivitas Terkini Card matching Image 1 --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-4">
                <h2 class="text-xs font-black text-slate-800 uppercase tracking-wide">AKTIVITAS TERKINI</h2>

                <div class="space-y-4 text-xs" id="timelineList">
                    {{-- Item 1: Login Berhasil --}}
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="material-icons-outlined text-sm">public</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800">Login Berhasil</h4>
                            <p class="text-[11px] text-slate-500">Anda masuk dari IP 192.168.1.1</p>
                            <span class="text-[10px] text-slate-400 mt-0.5 block">30 menit yang lalu</span>
                        </div>
                    </div>

                    {{-- Item 2: Mengecek Data --}}
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="material-icons-outlined text-sm">visibility</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800">Mengecek Data</h4>
                            <p class="text-[11px] text-slate-500">Arsip Kepegawaian #294 dibuka</p>
                            <span class="text-[10px] text-slate-400 mt-0.5 block">1 jam yang lalu</span>
                        </div>
                    </div>

                    {{-- Item 3: Penolakan Dokumen --}}
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="material-icons-outlined text-sm">remove_circle_outline</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800">Penolakan Dokumen</h4>
                            <p class="text-[11px] text-slate-500">Anda menolak 'Sertifikat_Lahan_Pusat'</p>
                            <span class="text-[10px] text-slate-400 mt-0.5 block">2 jam yang lalu</span>
                        </div>
                    </div>

                    {{-- Item 4: Approval Berhasil --}}
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="material-icons-outlined text-sm">check_circle</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800">Approval Berhasil</h4>
                            <p class="text-[11px] text-slate-500">Menyetujui 3 dokumen pengadaan</p>
                            <span class="text-[10px] text-slate-400 mt-0.5 block">4 jam yang lalu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Floating Action Button matching Image 1 --}}
<button onclick="toggleQuickMenu()" class="fixed bottom-6 right-6 w-12 h-12 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-2xl shadow-lg flex items-center justify-center transition-all active:scale-95 z-30" title="Menu Cepat">
    <span class="material-icons-outlined text-2xl">menu</span>
</button>

{{-- Audit Log Modal --}}
<div id="auditLogModal" class="modal-overlay" onclick="if(event.target===this)closeAuditModal()">
    <div class="modal-content max-w-xl w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Log Audit Sistem Terperinci</h3>
            <button onclick="closeAuditModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>
        <div class="my-4 space-y-3 text-xs max-h-80 overflow-y-auto custom-scroll pr-1">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between">
                <div>
                    <p class="font-bold text-slate-800">Sinkronisasi Cloud Selesai</p>
                    <p class="text-[11px] text-slate-500">Node AWS Jakarta - 128,492 checksum valid</p>
                </div>
                <span class="text-[10px] text-slate-400">Hari ini, 04:00</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between">
                <div>
                    <p class="font-bold text-slate-800">Enkripsi Basis Data Diperbarui</p>
                    <p class="text-[11px] text-slate-500">Kunci AES-256 dirotasi oleh sistem</p>
                </div>
                <span class="text-[10px] text-slate-400">Kemarin, 23:59</span>
            </div>
        </div>
        <div class="flex justify-end pt-2 border-t border-slate-100">
            <button onclick="closeAuditModal()" class="px-4 py-2 bg-[#0b1f51] text-white rounded-xl text-xs font-bold">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function handleChangePeriod() {
        const month = prompt("Pilih periode laporan (Bulan Tahun):", "Mei 2024");
        if (month) {
            document.getElementById('periodText').textContent = month;
            showToast(`Periode diubah ke: ${month}`, 'info');
        }
    }

    function handleExportReport() {
        showToast('Membuat berkas Laporan Eksekutif Pimpinan (PDF)...', 'info');
        setTimeout(() => {
            showToast('Laporan berhasil diekspor: SIMARSIP-Laporan-Eksekutif.pdf', 'success');
        }, 1500);
    }

    function quickApprove(button, docName) {
        button.disabled = true;
        button.className = "px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold transition-all shadow-xs";
        button.textContent = "Disetujui ✓";

        // Add to timeline
        const timeline = document.getElementById('timelineList');
        const newItem = document.createElement('div');
        newItem.className = 'flex items-start gap-3 bg-emerald-50/50 p-2 rounded-xl transition-all';
        newItem.innerHTML = `
            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                <span class="material-icons-outlined text-sm">check_circle</span>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-slate-800">Approval Berhasil</h4>
                <p class="text-[11px] text-slate-500">${docName} telah disetujui</p>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Baru saja</span>
            </div>
        `;
        timeline.insertBefore(newItem, timeline.firstChild);

        // Update badge
        const badge = document.getElementById('sidebarBadgeCount');
        if (badge) {
            const current = parseInt(badge.textContent.trim()) || 12;
            if (current > 1) badge.textContent = current - 1;
        }

        showToast(`Dokumen "${docName}" berhasil disetujui!`, 'success');
    }

    function showAuditModal() {
        document.getElementById('auditLogModal').classList.add('active');
    }
    function closeAuditModal() {
        document.getElementById('auditLogModal').classList.remove('active');
    }

    function toggleQuickMenu() {
        showToast('Menu Cepat: Menampilkan ringkasan akses Pimpinan', 'info');
    }
</script>
@endpush
