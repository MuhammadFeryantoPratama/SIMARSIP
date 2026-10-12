@extends('layouts.pimpinan')

@section('title', 'Daftar Arsip - SIMARSIP')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header matching Image 2 --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#0b1f51] tracking-tight">Daftar Arsip</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola dan akses semua dokumen kelembagaan yang telah diarsipkan.</p>
        </div>
        <div>
            <button onclick="showCreateArsipModal()" class="flex items-center gap-2 px-4 py-2.5 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold transition-all shadow-sm active:scale-[0.98]">
                <span class="material-icons-outlined text-base">add</span>
                <span>Tambah Arsip Baru</span>
            </button>
        </div>
    </div>

    {{-- Search & Controls Bar matching Image 2 --}}
    <div class="space-y-3">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            {{-- Search Bar --}}
            <div class="relative flex-1 w-full">
                <span class="material-icons-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                <input type="text" id="searchInput" oninput="filterArsipTable()"
                       placeholder="Cari berdasarkan nama dokumen, kategori, atau nomor arsip..."
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200/90 hover:border-slate-300 focus:border-blue-500 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/15 transition-all shadow-xs">
            </div>

            {{-- Filter & Ekspor buttons --}}
            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <button onclick="toggleFilterDrawer()" id="filterToggleBtn" class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200/90 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-xs">
                    <span class="material-icons-outlined text-base text-slate-500">tune</span>
                    <span>Filter</span>
                </button>
                <button onclick="handleExportDocuments()" class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200/90 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-xs">
                    <span class="material-icons-outlined text-base text-slate-500">file_download</span>
                    <span>Ekspor</span>
                </button>
            </div>
        </div>

        {{-- Filter Drawer --}}
        <div id="filterDrawer" class="hidden bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kategori</label>
                    <select id="filterCategorySelect" onchange="filterArsipTable()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua Kategori</option>
                        <option value="Keuangan">Keuangan</option>
                        <option value="Legal">Legal</option>
                        <option value="Operasional">Operasional</option>
                        <option value="SDM">SDM</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Dokumen</label>
                    <select id="filterStatusSelect" onchange="filterArsipTable()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua Status</option>
                        <option value="Finalized">Finalized</option>
                        <option value="Draft">Draft</option>
                        <option value="Archived">Archived</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Rentang Tahun</label>
                    <select id="filterYearSelect" onchange="filterArsipTable()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua Tahun</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button onclick="resetArsipFilters()" class="px-3 py-1.5 text-xs text-slate-500 hover:text-slate-800 font-semibold">Reset Filter</button>
                <button onclick="toggleFilterDrawer()" class="px-3.5 py-1.5 bg-[#0b1f51] text-white rounded-lg text-xs font-bold">Tutup</button>
            </div>
        </div>
    </div>

    {{-- Document Table Card matching Image 2 --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="mainArsipTable">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-6">NAMA DOKUMEN</th>
                        <th class="py-3 px-4">KATEGORI</th>
                        <th class="py-3 px-4">TANGGAL DIARSIPKAN</th>
                        <th class="py-3 px-4">STATUS</th>
                        <th class="py-3 px-6 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs" id="arsipTableBody">
                    {{-- Row 1: Laporan Keuangan Q3 2023.pdf --}}
                    <tr class="hover:bg-slate-50/50 transition-colors arsip-data-row" data-name="Laporan Keuangan Q3 2023.pdf" data-no="INV-2023-001-A" data-category="Keuangan" data-status="Finalized" data-date="15 Okt 2023">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3.5">
                                <span class="material-icons-outlined text-slate-400 text-xl flex-shrink-0">description</span>
                                <div>
                                    <p class="font-bold text-slate-800">Laporan Keuangan Q3 2023.pdf</p>
                                    <p class="text-[11px] text-slate-400">INV-2023-001-A</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                Keuangan
                            </span>
                        </td>
                        <td class="py-4 px-4 text-slate-600 font-medium">15 Okt 2023</td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Finalized
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2 text-slate-400">
                                <button onclick="previewArsip('Laporan Keuangan Q3 2023.pdf', 'INV-2023-001-A', 'Keuangan', 'Finalized', '15 Okt 2023')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Lihat Pratinjau">
                                    <span class="material-icons-outlined text-lg">visibility</span>
                                </button>
                                <button onclick="downloadArsip('Laporan Keuangan Q3 2023.pdf')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Unduh Berkas">
                                    <span class="material-icons-outlined text-lg">download</span>
                                </button>
                                <button onclick="editArsip('Laporan Keuangan Q3 2023.pdf', 'INV-2023-001-A', 'Keuangan', 'Finalized')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Ubah Metadata">
                                    <span class="material-icons-outlined text-lg">edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 2: Draft Perjanjian Kerja Sama Vendor.docx --}}
                    <tr class="hover:bg-slate-50/50 transition-colors arsip-data-row" data-name="Draft Perjanjian Kerja Sama Vendor.docx" data-no="LGL-2023-089" data-category="Legal" data-status="Draft" data-date="12 Nov 2023">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3.5">
                                <span class="material-icons-outlined text-slate-400 text-xl flex-shrink-0">article</span>
                                <div>
                                    <p class="font-bold text-slate-800">Draft Perjanjian Kerja Sama Vendor.docx</p>
                                    <p class="text-[11px] text-slate-400">LGL-2023-089</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                Legal
                            </span>
                        </td>
                        <td class="py-4 px-4 text-slate-600 font-medium">12 Nov 2023</td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2 text-slate-400">
                                <button onclick="previewArsip('Draft Perjanjian Kerja Sama Vendor.docx', 'LGL-2023-089', 'Legal', 'Draft', '12 Nov 2023')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Lihat Pratinjau">
                                    <span class="material-icons-outlined text-lg">visibility</span>
                                </button>
                                <button onclick="downloadArsip('Draft Perjanjian Kerja Sama Vendor.docx')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Unduh Berkas">
                                    <span class="material-icons-outlined text-lg">download</span>
                                </button>
                                <button onclick="editArsip('Draft Perjanjian Kerja Sama Vendor.docx', 'LGL-2023-089', 'Legal', 'Draft')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Ubah Metadata">
                                    <span class="material-icons-outlined text-lg">edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 3: Arsip Proyek Gedung Baru 2022.zip --}}
                    <tr class="hover:bg-slate-50/50 transition-colors arsip-data-row" data-name="Arsip Proyek Gedung Baru 2022.zip" data-no="OPS-2022-ALL" data-category="Operasional" data-status="Archived" data-date="01 Jan 2023">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3.5">
                                <span class="material-icons-outlined text-slate-400 text-xl flex-shrink-0">folder_zip</span>
                                <div>
                                    <p class="font-bold text-slate-800">Arsip Proyek Gedung Baru 2022.zip</p>
                                    <p class="text-[11px] text-slate-400">OPS-2022-ALL</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                Operasional
                            </span>
                        </td>
                        <td class="py-4 px-4 text-slate-600 font-medium">01 Jan 2023</td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Archived
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2 text-slate-400">
                                <button onclick="previewArsip('Arsip Proyek Gedung Baru 2022.zip', 'OPS-2022-ALL', 'Operasional', 'Archived', '01 Jan 2023')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Lihat Pratinjau">
                                    <span class="material-icons-outlined text-lg">visibility</span>
                                </button>
                                <button onclick="downloadArsip('Arsip Proyek Gedung Baru 2022.zip')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Unduh Berkas">
                                    <span class="material-icons-outlined text-lg">download</span>
                                </button>
                                <button onclick="editArsip('Arsip Proyek Gedung Baru 2022.zip', 'OPS-2022-ALL', 'Operasional', 'Archived')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Ubah Metadata">
                                    <span class="material-icons-outlined text-lg">edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 4: Kebijakan Karyawan v2.1.pdf --}}
                    <tr class="hover:bg-slate-50/50 transition-colors arsip-data-row" data-name="Kebijakan Karyawan v2.1.pdf" data-no="HRD-POL-021" data-category="SDM" data-status="Finalized" data-date="20 Okt 2023">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3.5">
                                <span class="material-icons-outlined text-slate-400 text-xl flex-shrink-0">verified_user</span>
                                <div>
                                    <p class="font-bold text-slate-800">Kebijakan Karyawan v2.1.pdf</p>
                                    <p class="text-[11px] text-slate-400">HRD-POL-021</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                SDM
                            </span>
                        </td>
                        <td class="py-4 px-4 text-slate-600 font-medium">20 Okt 2023</td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Finalized
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2 text-slate-400">
                                <button onclick="previewArsip('Kebijakan Karyawan v2.1.pdf', 'HRD-POL-021', 'SDM', 'Finalized', '20 Okt 2023')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Lihat Pratinjau">
                                    <span class="material-icons-outlined text-lg">visibility</span>
                                </button>
                                <button onclick="downloadArsip('Kebijakan Karyawan v2.1.pdf')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Unduh Berkas">
                                    <span class="material-icons-outlined text-lg">download</span>
                                </button>
                                <button onclick="editArsip('Kebijakan Karyawan v2.1.pdf', 'HRD-POL-021', 'SDM', 'Finalized')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Ubah Metadata">
                                    <span class="material-icons-outlined text-lg">edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer matching Image 2 --}}
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span id="arsipCountInfo">Menampilkan 1-4 dari 48 dokumen</span>
            <div class="flex items-center gap-1" id="arsipPaginationGroup">
                <button onclick="switchArsipPage(1)" class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-400">&lt;</button>
                <button onclick="switchArsipPage(1)" id="arsipPage1Btn" class="w-7 h-7 flex items-center justify-center rounded-lg font-bold bg-[#0b1f51] text-white">1</button>
                <button onclick="switchArsipPage(2)" id="arsipPage2Btn" class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600">2</button>
                <button onclick="switchArsipPage(3)" id="arsipPage3Btn" class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600">3</button>
                <span class="px-1 text-slate-400">...</span>
                <button onclick="switchArsipPage(12)" id="arsipPage12Btn" class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600">12</button>
                <button onclick="switchArsipPage(2)" class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600">&gt;</button>
            </div>
        </div>
    </div>
</div>

{{-- Modals for Adding / Previewing / Editing --}}
<div id="createArsipModal" class="modal-overlay" onclick="if(event.target===this)closeCreateArsipModal()">
    <div class="modal-content max-w-lg w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Tambah Arsip Baru</h3>
            <button onclick="closeCreateArsipModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-xl">close</span></button>
        </div>
        <form onsubmit="handleCreateArsipSubmit(event)" class="mt-4 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Dokumen</label>
                <input type="text" id="newDocName" required placeholder="Contoh: Rencana Strategis Tahunan 2024.pdf" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Arsip</label>
                    <input type="text" id="newDocNo" required placeholder="REN-2024-001" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori</label>
                    <select id="newDocCategory" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="Keuangan">Keuangan</option>
                        <option value="Legal">Legal</option>
                        <option value="Operasional">Operasional</option>
                        <option value="SDM">SDM</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeCreateArsipModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-[#0b1f51] text-white rounded-xl text-xs font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="previewArsipModal" class="modal-overlay" onclick="if(event.target===this)closePreviewArsipModal()">
    <div class="modal-content max-w-lg w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-800">Pratinjau Berkas Arsip</h3>
            <button onclick="closePreviewArsipModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400"><span class="material-icons-outlined text-lg">close</span></button>
        </div>
        <div class="my-4 space-y-4">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-outlined text-2xl">picture_as_pdf</span>
                </div>
                <div>
                    <h4 id="previewTitle" class="text-xs font-bold text-slate-800">Nama Dokumen</h4>
                    <p id="previewNo" class="text-[11px] text-slate-400">Nomor Arsip</p>
                </div>
            </div>
        </div>
        <div class="flex justify-between pt-2 border-t border-slate-100">
            <button onclick="downloadArsip(document.getElementById('previewTitle').textContent)" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold flex items-center gap-1.5"><span class="material-icons-outlined text-base">download</span> Unduh Salinan</button>
            <button onclick="closePreviewArsipModal()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleFilterDrawer() {
        document.getElementById('filterDrawer').classList.toggle('hidden');
    }
    function filterArsipTable() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const cat = document.getElementById('filterCategorySelect').value;
        const status = document.getElementById('filterStatusSelect').value;
        const rows = document.querySelectorAll('.arsip-data-row');
        let count = 0;
        rows.forEach(r => {
            const name = (r.dataset.name || '').toLowerCase();
            const category = r.dataset.category || '';
            const rowStatus = r.dataset.status || '';
            let match = true;
            if (query && !name.includes(query)) match = false;
            if (cat && category !== cat) match = false;
            if (status && rowStatus !== status) match = false;
            r.style.display = match ? '' : 'none';
            if (match) count++;
        });
        document.getElementById('arsipCountInfo').textContent = `Menampilkan ${count} dokumen`;
    }
    function resetArsipFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterCategorySelect').value = '';
        document.getElementById('filterStatusSelect').value = '';
        filterArsipTable();
    }
    function showCreateArsipModal() { document.getElementById('createArsipModal').classList.add('active'); }
    function closeCreateArsipModal() { document.getElementById('createArsipModal').classList.remove('active'); }
    function handleCreateArsipSubmit(e) {
        e.preventDefault();
        closeCreateArsipModal();
        showToast('Arsip berhasil ditambahkan!', 'success');
    }
    function previewArsip(name, no) {
        document.getElementById('previewTitle').textContent = name;
        document.getElementById('previewNo').textContent = no;
        document.getElementById('previewArsipModal').classList.add('active');
    }
    function closePreviewArsipModal() { document.getElementById('previewArsipModal').classList.remove('active'); }
    function downloadArsip(name) { showToast(`Mengunduh ${name}...`, 'success'); }
    function editArsip(name) { showToast(`Mengedit metadata ${name}...`, 'info'); }
    function handleExportDocuments() { showToast('Mengekspor arsip ke Excel...', 'success'); }
    function switchArsipPage(p) { showToast(`Halaman ${p}`, 'info'); }
</script>
@endpush
