@extends('layouts.pimpinan')

@section('title', 'Antrean Persetujuan - SIMARSIP')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-black text-[#0b1f51] tracking-tight">Antrean Persetujuan</h1>
        <p class="text-xs text-slate-500 mt-1">Tinjau dan kelola permintaan persetujuan arsip digital instansi.</p>
    </div>

    {{-- Top 3 Stat Cards matching Image 3 --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- Card 1: MENUNGGU PERSETUJUAN --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">MENUNGGU PERSETUJUAN</p>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight mt-1" id="statPendingCount">12</h2>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0">
                <span class="material-icons-outlined text-2xl">folder_shared</span>
            </div>
        </div>

        {{-- Card 2: DISETUJUI HARI INI --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">DISETUJUI HARI INI</p>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight mt-1" id="statApprovedCount">45</h2>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                <span class="material-icons-outlined text-2xl">check_circle</span>
            </div>
        </div>

        {{-- Card 3: PERLU REVISI --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">PERLU REVISI</p>
                <h2 class="text-3xl font-black text-rose-600 tracking-tight mt-1" id="statRevisionCount">3</h2>
            </div>
            <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                <span class="material-icons-outlined text-2xl">error_outline</span>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Left Section (8 cols): Daftar Permintaan Table --}}
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-xs font-black text-slate-800 uppercase tracking-wide">Daftar Permintaan</h2>
                <button onclick="filterApprovalTable('all')" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">
                    Lihat Semua
                </button>
            </div>

            {{-- Table matching Image 3 --}}
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
                    <tbody class="divide-y divide-slate-100 text-xs" id="approvalItemsBody">
                        {{-- Row 1: Laporan_Keuangan_Q1_2024.pdf --}}
                        <tr class="hover:bg-slate-50/50 transition-colors approval-row" id="row-doc-1">
                            <td class="py-4 px-6 cursor-pointer" onclick="openDocModal('Laporan_Keuangan_Q1_2024.pdf', 'Divisi Keuangan', 'MENDESAK', 'Ref: ARS-2024-001 • 12.4 MB')">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons-outlined text-lg">picture_as_pdf</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 hover:text-blue-600 transition-colors">Laporan_Keuangan_Q1_2024.pdf</p>
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
                                <button onclick="handleApproveRow('row-doc-1', 'Laporan_Keuangan_Q1_2024.pdf')" class="px-4 py-1.5 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-lg text-xs font-bold transition-all shadow-xs active:scale-[0.98]">
                                    Setujui
                                </button>
                            </td>
                        </tr>

                        {{-- Row 2: Mou_Kerjasama_Stategis_V2.docx --}}
                        <tr class="hover:bg-slate-50/50 transition-colors approval-row" id="row-doc-2">
                            <td class="py-4 px-6 cursor-pointer" onclick="openDocModal('Mou_Kerjasama_Stategis_V2.docx', 'Biro Hukum', 'NORMAL', 'Ref: LEG-2024-042 • 1.2 MB')">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons-outlined text-lg">article</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 hover:text-blue-600 transition-colors">Mou_Kerjasama_Stategis_V2.docx</p>
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
                                <button onclick="handleApproveRow('row-doc-2', 'Mou_Kerjasama_Stategis_V2.docx')" class="px-4 py-1.5 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-lg text-xs font-bold transition-all shadow-xs active:scale-[0.98]">
                                    Setujui
                                </button>
                            </td>
                        </tr>

                        {{-- Row 3: Scan_Sertifikat_Aset_K09.jpg --}}
                        <tr class="hover:bg-slate-50/50 transition-colors approval-row" id="row-doc-3">
                            <td class="py-4 px-6 cursor-pointer" onclick="openDocModal('Scan_Sertifikat_Aset_K09.jpg', 'Aset & Sarpras', 'RENDAH', 'Ref: AST-2024-089 • 4.5 MB')">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons-outlined text-lg">image</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 hover:text-blue-600 transition-colors">Scan_Sertifikat_Aset_K09.jpg</p>
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
                                <button onclick="handleApproveRow('row-doc-3', 'Scan_Sertifikat_Aset_K09.jpg')" class="px-4 py-1.5 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-lg text-xs font-bold transition-all shadow-xs active:scale-[0.98]">
                                    Setujui
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Section (4 cols): Aktivitas Terkini --}}
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-4">
            <h2 class="text-xs font-black text-slate-800 uppercase tracking-wide">AKTIVITAS TERKINI</h2>

            <div class="space-y-4 text-xs" id="approvalTimeline">
                {{-- Item 1: Login Berhasil --}}
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-800">Login Berhasil</h4>
                        <p class="text-[11px] text-slate-500">Anda masuk dari IP 192.168.1.1</p>
                        <span class="text-[10px] text-slate-400 mt-0.5 block">30 menit yang lalu</span>
                    </div>
                </div>

                {{-- Item 2: Mengecek Data --}}
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-800">Mengecek Data</h4>
                        <p class="text-[11px] text-slate-500">Arsip Kepegawaian #294 dibuka</p>
                        <span class="text-[10px] text-slate-400 mt-0.5 block">1 jam yang lalu</span>
                    </div>
                </div>

                {{-- Item 3: Penolakan Dokumen --}}
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-rose-500 mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-800">Penolakan Dokumen</h4>
                        <p class="text-[11px] text-slate-500">Anda menolak 'Sertifikat_Lahan_Pusat'</p>
                        <span class="text-[10px] text-slate-400 mt-0.5 block">2 jam yang lalu</span>
                    </div>
                </div>

                {{-- Item 4: Approval Berhasil --}}
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></div>
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

{{-- Detail & Action Modal for Document --}}
<div id="detailDocModal" class="modal-overlay" onclick="if(event.target===this)closeDocModal()">
    <div class="modal-content max-w-lg w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-outlined text-lg">description</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800" id="modalDocTitle">Detail Dokumen</h3>
                    <p class="text-[11px] text-slate-400" id="modalDocRef">Ref Dokumen</p>
                </div>
            </div>
            <button onclick="closeDocModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>
        <div class="my-4 space-y-3 text-xs">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                <div class="flex justify-between">
                    <span class="text-slate-500">Unit Pengirim:</span>
                    <span class="font-bold text-slate-800" id="modalDocSender">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Prioritas:</span>
                    <span class="font-bold text-rose-600" id="modalDocPriority">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Tanda Tangan Digital:</span>
                    <span class="font-bold text-emerald-600">Terverifikasi (BSrE)</span>
                </div>
            </div>
            <div>
                <label class="block text-slate-600 font-bold mb-1">Catatan Persetujuan / Revisi (Opsional):</label>
                <textarea id="modalDocNotes" rows="2" placeholder="Tuliskan catatan atau alasan apabila ada catatan khusus..." class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
            </div>
        </div>
        <div class="flex items-center justify-between pt-3 border-t border-slate-100 gap-2">
            <button onclick="handleRejectModal()" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-bold transition-all">
                Tolak Dokumen
            </button>
            <div class="flex gap-2">
                <button onclick="handleRevisionModal()" class="px-3 py-2 border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-xl text-xs font-bold transition-all">
                    Minta Revisi
                </button>
                <button onclick="handleApproveModal()" class="px-4 py-2 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                    Setujui Sekarang
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeModalDoc = '';

    function handleApproveRow(rowId, docName) {
        const row = document.getElementById(rowId);
        if (row) {
            row.style.opacity = '0.4';
            row.style.pointerEvents = 'none';
            const btn = row.querySelector('button');
            if (btn) {
                btn.className = "px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold";
                btn.textContent = "Disetujui ✓";
            }
        }

        // Stats updates
        const pendingEl = document.getElementById('statPendingCount');
        const approvedEl = document.getElementById('statApprovedCount');
        if (pendingEl) pendingEl.textContent = Math.max(0, parseInt(pendingEl.textContent) - 1);
        if (approvedEl) approvedEl.textContent = parseInt(approvedEl.textContent) + 1;

        // Badge update
        const badge = document.getElementById('sidebarBadgeCount');
        if (badge) {
            const count = parseInt(badge.textContent) || 12;
            if (count > 0) badge.textContent = count - 1;
        }

        // Add to timeline
        addToTimeline('Approval Berhasil', `Menyetujui dokumen ${docName}`, 'emerald');

        showToast(`Dokumen "${docName}" berhasil disetujui!`, 'success');
    }

    function openDocModal(name, sender, priority, ref) {
        activeModalDoc = name;
        document.getElementById('modalDocTitle').textContent = name;
        document.getElementById('modalDocRef').textContent = ref;
        document.getElementById('modalDocSender').textContent = sender;
        document.getElementById('modalDocPriority').textContent = priority;
        document.getElementById('detailDocModal').classList.add('active');
    }

    function closeDocModal() {
        document.getElementById('detailDocModal').classList.remove('active');
    }

    function handleApproveModal() {
        closeDocModal();
        handleApproveRow('row-doc-1', activeModalDoc);
    }

    function handleRejectModal() {
        closeDocModal();
        addToTimeline('Penolakan Dokumen', `Menolak berkas ${activeModalDoc}`, 'rose');
        showToast(`Dokumen "${activeModalDoc}" ditolak.`, 'error');
    }

    function handleRevisionModal() {
        closeDocModal();
        const revEl = document.getElementById('statRevisionCount');
        if (revEl) revEl.textContent = parseInt(revEl.textContent) + 1;
        addToTimeline('Permintaan Revisi', `Meminta revisi berkas ${activeModalDoc}`, 'amber');
        showToast(`Permintaan revisi dikirimkan ke unit pengirim.`, 'info');
    }

    function addToTimeline(title, desc, color) {
        const timeline = document.getElementById('approvalTimeline');
        const item = document.createElement('div');
        item.className = 'flex items-start gap-3 bg-slate-50 p-2 rounded-xl transition-all';
        const dotColor = color === 'emerald' ? 'bg-emerald-500' : (color === 'rose' ? 'bg-rose-500' : 'bg-amber-500');
        item.innerHTML = `
            <div class="w-2 h-2 rounded-full ${dotColor} mt-1.5 flex-shrink-0"></div>
            <div class="flex-1">
                <h4 class="font-bold text-slate-800">${title}</h4>
                <p class="text-[11px] text-slate-500">${desc}</p>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Baru saja</span>
            </div>
        `;
        timeline.insertBefore(item, timeline.firstChild);
    }

    function filterApprovalTable(type) {
        showToast('Menampilkan seluruh antrean persetujuan (12 dokumen)', 'info');
    }
</script>
@endpush
