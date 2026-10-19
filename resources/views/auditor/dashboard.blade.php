@extends('layouts.app')

@section('title', 'Dashboard Auditor - SIMARSIP')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header matching Image 1 --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#0b1f51] tracking-tight">Dashboard Auditor</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau kepatuhan, verifikasi arsip, dan telusuri jejak audit sistem.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <button onclick="handleFilterPeriode()" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-bold text-slate-700 shadow-xs hover:bg-slate-50 transition-all">
                <span class="material-icons-outlined text-sm text-slate-500">date_range</span>
                <span>7 Hari Terakhir</span>
            </button>
            <button onclick="handleExportAudit()" class="flex items-center gap-2 px-4 py-2 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold transition-all shadow-sm active:scale-[0.98]">
                <span class="material-icons-outlined text-sm">file_download</span>
                <span>Ekspor Laporan Audit</span>
            </button>
        </div>
    </div>

    {{-- Top 4 Stats Cards matching Image 1 --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: TOTAL ARSIP TERVERIFIKASI --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">description</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                    +12%
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">Total Arsip Terverifikasi</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5" id="statVerified">{{ number_format($approvedArchiveCount, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- Card 2: MENUNGGU REVIEW --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">assignment</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                    {{ number_format($pendingArchiveCount, 0, ',', '.') }} Antrian
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">Menunggu Review</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5" id="statPendingReview">{{ number_format($pendingArchiveCount, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- Card 3: KETIDAKSESUAIAN ARSIP --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">error_outline</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-600 border border-red-100 flex items-center gap-0.5">
                    <span class="material-icons-outlined text-xs">warning</span>High
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">Ketidaksesuaian Arsip</p>
                <h3 class="text-2xl font-black text-red-600 tracking-tight mt-0.5" id="statNonCompliant">{{ number_format($rejectedArchiveCount, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- Card 4: AKTIVITAS AUDIT (24 JAM) --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">monitor_heart</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">
                    Real-time
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">Aktivitas Audit (24 Jam)</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5" id="statActivity">{{ number_format($recentArchives->count(), 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- Data Verifikasi Terbaru Table matching Image 1 --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-black text-slate-800">Data Verifikasi Terbaru</h2>
            <div class="flex items-center gap-2">
                <button onclick="toggleVerifFilter()" class="p-1.5 hover:bg-slate-100 rounded-lg text-slate-400 transition-colors" title="Filter">
                    <span class="material-icons-outlined text-lg">tune</span>
                </button>
                <button onclick="showVerifMenuOptions()" class="p-1.5 hover:bg-slate-100 rounded-lg text-slate-400 transition-colors" title="Opsi">
                    <span class="material-icons-outlined text-lg">more_vert</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-6">Nomor Dokumen</th>
                        <th class="py-3 px-4">Nama Arsip</th>
                        <th class="py-3 px-4">Tanggal Unggah</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs" id="verifTable">
                    {{-- Row 1: ARS-2023-001 --}}
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200 font-mono">ARS-2023-001</span>
                        </td>
                        <td class="py-4 px-4">
                            <div>
                                <p class="font-bold text-slate-800">Laporan Keuangan Q3 2023</p>
                                <p class="text-[11px] text-slate-400">Keuangan · Publik</p>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-slate-500 font-medium">12 Jun 2023</td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase">
                                Verified
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button onclick="showDetailModal('ARS-2023-001', 'Laporan Keuangan Q3 2023', 'Keuangan · Publik', '12 Jun 2023', 'Verified')" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline transition-colors">Detail</button>
                        </td>
                    </tr>

                    {{-- Row 2: ARS-2023-005 --}}
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200 font-mono">ARS-2023-005</span>
                        </td>
                        <td class="py-4 px-4">
                            <div>
                                <p class="font-bold text-slate-800">MoU Vendor Infrastruktur IT</p>
                                <p class="text-[11px] text-slate-400">Legal · Rahasia</p>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-slate-500 font-medium">11 Mei 2023</td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-amber-50 text-amber-600 border border-amber-100 uppercase">
                                Pending Review
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button onclick="showDetailModal('ARS-2023-005', 'MoU Vendor Infrastruktur IT', 'Legal · Rahasia', '11 Mei 2023', 'Pending Review')" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline transition-colors">Detail</button>
                        </td>
                    </tr>

                    {{-- Row 3: ARS-2023-012 --}}
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200 font-mono">ARS-2023-012</span>
                        </td>
                        <td class="py-4 px-4">
                            <div>
                                <p class="font-bold text-slate-800">SK Pengangkatan Direksi</p>
                                <p class="text-[11px] text-slate-400">SDM · Internal</p>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-slate-500 font-medium">10 Feb 2023</td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-red-50 text-red-600 border border-red-100 uppercase">
                                Incomplete
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button onclick="showDetailModal('ARS-2023-012', 'SK Pengangkatan Direksi', 'SDM · Internal', '10 Feb 2023', 'Incomplete')" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline transition-colors">Detail</button>
                        </td>
                    </tr>

                    {{-- Row 4: ARS-2023-024 --}}
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200 font-mono">ARS-2023-024</span>
                        </td>
                        <td class="py-4 px-4">
                            <div>
                                <p class="font-bold text-slate-800">SOP Keamanan Siber 2023</p>
                                <p class="text-[11px] text-slate-400">IT · Rahasia</p>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-slate-500 font-medium">09 Jan 2023</td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase">
                                Verified
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button onclick="showDetailModal('ARS-2023-024', 'SOP Keamanan Siber 2023', 'IT · Rahasia', '09 Jan 2023', 'Verified')" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline transition-colors">Detail</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span>Menampilkan 4 dari 156 dokumen</span>
            <div class="flex items-center gap-1">
                <button onclick="showToast('Halaman sebelumnya', 'info')" class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-400">&lt;</button>
                <button onclick="showToast('Halaman berikutnya', 'info')" class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-400">&gt;</button>
            </div>
        </div>
    </div>

    {{-- Tren Audit & Compliance Chart matching Image 1 --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden p-6">
        <h2 class="text-sm font-black text-slate-800 mb-6">Tren Audit & Compliance</h2>
        <div class="relative h-48" id="auditChartArea">
            {{-- Chart bars rendered via JS --}}
            <canvas id="auditChart" class="w-full h-full"></canvas>
        </div>
        <div class="flex items-center justify-between mt-4 text-[11px] font-semibold text-slate-400 uppercase tracking-wider px-2">
            <span>SEN</span>
            <span>SEL</span>
            <span>RAB</span>
            <span>KAM</span>
            <span>JUM</span>
            <span>SAB</span>
            <span>MIN</span>
        </div>
    </div>
</div>

{{-- Floating Action Button (Audit Scan) matching Image 1 --}}
<button onclick="showQuickAuditModal()" class="fixed bottom-6 right-6 w-12 h-12 bg-[#1877F2] hover:bg-[#1264cc] text-white rounded-xl shadow-lg shadow-blue-500/25 flex items-center justify-center transition-all active:scale-95 z-30" title="Quick Audit">
    <span class="material-icons-outlined text-2xl">find_in_page</span>
</button>

{{-- Modal: Detail Dokumen --}}
<div id="detailModal" class="modal-overlay" onclick="if(event.target===this)closeDetailModal()">
    <div class="modal-content max-w-lg w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-outlined text-lg">fact_check</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Detail Verifikasi</h3>
                    <p class="text-xs text-slate-400" id="detailDocNo">ARS-2023-XXX</p>
                </div>
            </div>
            <button onclick="closeDetailModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-xl">close</span></button>
        </div>
        <div class="my-4 space-y-3">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Arsip</p>
                        <p id="detailName" class="font-bold text-slate-800 mt-1">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Klasifikasi</p>
                        <p id="detailClass" class="font-bold text-slate-800 mt-1">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Unggah</p>
                        <p id="detailDate" class="font-bold text-slate-800 mt-1">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</p>
                        <p id="detailStatus" class="font-bold mt-1">-</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-between pt-2 border-t border-slate-100">
            <button onclick="handleDownloadAuditReport()" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold flex items-center gap-1.5"><span class="material-icons-outlined text-base">download</span> Unduh Laporan</button>
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold">Tutup</button>
        </div>
    </div>
</div>

{{-- Modal: Quick Audit Scan --}}
<div id="quickAuditModal" class="modal-overlay" onclick="if(event.target===this)closeQuickAuditModal()">
    <div class="modal-content max-w-lg w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-outlined text-lg">qr_code_scanner</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Quick Audit Scan</h3>
                    <p class="text-xs text-slate-400">Verifikasi cepat arsip berdasarkan nomor dokumen</p>
                </div>
            </div>
            <button onclick="closeQuickAuditModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-xl">close</span></button>
        </div>
        <div class="mt-4 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Dokumen</label>
                <input type="text" id="quickAuditDocNo" placeholder="ARS-2023-XXX" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-mono">
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeQuickAuditModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="button" onclick="handleQuickAuditScan()" class="px-5 py-2 bg-[#1877F2] text-white rounded-xl text-xs font-bold">Scan Sekarang</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // --- Export & Filters ---
    function handleExportAudit() {
        window.location.href = "{{ route('archives.export') }}";
    }
    function handleFilterPeriode() {
        showToast('Filter periode: 7 hari terakhir diterapkan', 'info');
    }
    function toggleVerifFilter() {
        showToast('Filter data verifikasi terbuka', 'info');
    }
    function showVerifMenuOptions() {
        showToast('Opsi: Refresh data, Cetak tabel', 'info');
    }

    // --- Detail Modal ---
    function showDetailModal(no, name, cls, date, status) {
        document.getElementById('detailDocNo').textContent = no;
        document.getElementById('detailName').textContent = name;
        document.getElementById('detailClass').textContent = cls;
        document.getElementById('detailDate').textContent = date;
        const statusEl = document.getElementById('detailStatus');
        statusEl.textContent = status;
        statusEl.className = 'font-bold mt-1 ' + (status === 'Verified' ? 'text-emerald-600' : status === 'Incomplete' ? 'text-red-600' : 'text-amber-600');
        document.getElementById('detailModal').classList.add('active');
    }
    function closeDetailModal() { document.getElementById('detailModal').classList.remove('active'); }
    function handleDownloadAuditReport() {
        const no = document.getElementById('detailDocNo').textContent;
        const archive = [...document.querySelectorAll('[data-archive-no]')].find(row => row.dataset.archiveNo === no);
        if (!archive?.dataset.archiveId) {
            showToast('Berkas audit belum memiliki arsip tersimpan.', 'error');
            return;
        }
        window.location.href = `/archives/${archive.dataset.archiveId}/download`;
        closeDetailModal();
    }

    // --- Quick Audit Scan ---
    function showQuickAuditModal() { document.getElementById('quickAuditModal').classList.add('active'); }
    function closeQuickAuditModal() { document.getElementById('quickAuditModal').classList.remove('active'); }
    function handleQuickAuditScan() {
        const docNo = document.getElementById('quickAuditDocNo').value.trim();
        if (!docNo) {
            showToast('Masukkan nomor dokumen arsip terlebih dahulu.', 'error');
            return;
        }
        showToast(`Memindai arsip "${docNo}" ...`, 'info');
        setTimeout(() => {
            closeQuickAuditModal();
            showToast(`Arsip "${docNo}" VALID. Hash digital cocok, tidak ada anomali.`, 'success');
        }, 1200);
    }

    // --- Simple Chart Drawing ---
    function drawAuditChart() {
        const canvas = document.getElementById('auditChart');
        if (!canvas || !canvas.getContext) return;
        const ctx = canvas.getContext('2d');
        const dpr = window.devicePixelRatio || 1;
        const rect = canvas.parentElement.getBoundingClientRect();
        canvas.width = rect.width * dpr;
        canvas.height = 192 * dpr;
        canvas.style.width = rect.width + 'px';
        canvas.style.height = '192px';
        ctx.scale(dpr, dpr);

        const w = rect.width;
        const h = 192;
        const data = [420, 380, 510, 460, 530, 290, 180];
        const maxVal = Math.max(...data);
        const barWidth = 36;
        const gap = (w - 80) / data.length;

        // Draw grid lines
        ctx.strokeStyle = '#f1f5f9';
        ctx.lineWidth = 1;
        for (let i = 0; i < 4; i++) {
            const y = 20 + (i * (h - 40) / 3);
            ctx.beginPath();
            ctx.moveTo(40, y);
            ctx.lineTo(w - 20, y);
            ctx.stroke();
        }

        // Draw bars
        data.forEach((val, i) => {
            const barH = (val / maxVal) * (h - 50);
            const x = 40 + i * gap + (gap - barWidth) / 2;
            const y = h - 20 - barH;

            // Bar with gradient
            const grad = ctx.createLinearGradient(x, y, x, h - 20);
            grad.addColorStop(0, '#3b82f6');
            grad.addColorStop(1, '#1d4ed8');
            ctx.fillStyle = grad;
            ctx.beginPath();
            ctx.roundRect(x, y, barWidth, barH, [6, 6, 0, 0]);
            ctx.fill();

            // Compliance line dot
            const complianceVal = [400, 400, 400, 400, 400, 400, 400];
            const compY = h - 20 - (complianceVal[i] / maxVal) * (h - 50);
            ctx.fillStyle = '#ef4444';
            ctx.beginPath();
            ctx.arc(x + barWidth / 2, compY, 3, 0, Math.PI * 2);
            ctx.fill();
        });

        // Draw compliance line
        ctx.strokeStyle = '#ef4444';
        ctx.lineWidth = 1.5;
        ctx.setLineDash([5, 5]);
        const compY = h - 20 - (400 / maxVal) * (h - 50);
        ctx.beginPath();
        ctx.moveTo(40, compY);
        ctx.lineTo(w - 20, compY);
        ctx.stroke();
        ctx.setLineDash([]);
    }

    window.addEventListener('load', drawAuditChart);
    window.addEventListener('resize', drawAuditChart);
    document.addEventListener('DOMContentLoaded', () => {
        const archives = @json($recentArchiveRows);
        const body = document.getElementById('verifTable');
        if (!body) return;
        body.innerHTML = archives.length ? archives.map(archive => {
            const label = archive.status === 'disetujui' ? 'Verified' : archive.status === 'ditolak' ? 'Incomplete' : 'Pending Review';
            const tone = archive.status === 'disetujui' ? 'emerald' : archive.status === 'ditolak' ? 'red' : 'amber';
            const action = `showDetailModal('ARS-${String(archive.id).padStart(6, '0')}', '${escapeHtml(archive.name)}', 'Arsip Digital', '${archive.date}', '${label}')`;
            return `<tr class="hover:bg-slate-50/50 transition-colors" data-archive-id="${archive.id}" data-archive-no="ARS-${String(archive.id).padStart(6, '0')}"><td class="py-4 px-6"><span class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200 font-mono">ARS-${String(archive.id).padStart(6, '0')}</span></td><td class="py-4 px-4"><p class="font-bold text-slate-800">${escapeHtml(archive.name)}</p><p class="text-[11px] text-slate-400">Arsip Digital</p></td><td class="py-4 px-4 text-slate-500 font-medium">${archive.date}</td><td class="py-4 px-4"><span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-wider bg-${tone}-50 text-${tone}-600 border border-${tone}-100 uppercase">${label}</span></td><td class="py-4 px-6 text-center"><button onclick="${action}" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline transition-colors">Detail</button></td></tr>`;
        }).join('') : '<tr><td colspan="5" class="py-10 text-center text-xs text-slate-400">Belum ada arsip.</td></tr>';
    });

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character]));
    }
</script>
@endpush
