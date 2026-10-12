@extends('layouts.admin')

@section('title', 'Daftar Arsip Digital - SIMARSIP')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
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

    {{-- Search & Controls Bar matching Image 3 --}}
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

        {{-- Filter Drawer (Expandable) --}}
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

    {{-- Document Table Card matching Image 3 --}}
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
                        <td class="py-4 px-4 text-slate-600 font-medium">
                            15 Okt 2023
                        </td>
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
                        <td class="py-4 px-4 text-slate-600 font-medium">
                            12 Nov 2023
                        </td>
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
                        <td class="py-4 px-4 text-slate-600 font-medium">
                            01 Jan 2023
                        </td>
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
                        <td class="py-4 px-4 text-slate-600 font-medium">
                            20 Okt 2023
                        </td>
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

        {{-- Pagination Footer matching Image 3 exactly --}}
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

{{-- Modal: Tambah Arsip Baru --}}
<div id="createArsipModal" class="modal-overlay" onclick="if(event.target===this)closeCreateArsipModal()">
    <div class="modal-content max-w-lg w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-outlined text-lg">post_add</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Tambah Arsip Baru</h3>
                    <p class="text-xs text-slate-400">Katalogkan dokumen instansi baru</p>
                </div>
            </div>
            <button onclick="closeCreateArsipModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>
        <form onsubmit="handleCreateArsipSubmit(event)" class="mt-4 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Dokumen Lengkap</label>
                <input type="text" id="newDocName" required placeholder="Contoh: Rencana Strategis Tahunan 2024.pdf" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Arsip</label>
                    <input type="text" id="newDocNo" required placeholder="REN-2024-001" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori</label>
                    <select id="newDocCategory" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="Keuangan">Keuangan</option>
                        <option value="Legal">Legal</option>
                        <option value="Operasional">Operasional</option>
                        <option value="SDM">SDM</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Pengarsipan</label>
                    <input type="date" id="newDocDate" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status Awal</label>
                    <select id="newDocStatus" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="Finalized">Finalized</option>
                        <option value="Draft">Draft</option>
                        <option value="Archived">Archived</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lampiran Berkas (Digital)</label>
                <div class="border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50/50 rounded-xl p-4 text-center cursor-pointer" onclick="showToast('Memilih lampiran berkas...', 'info')">
                    <span class="material-icons-outlined text-2xl text-slate-300">attach_file</span>
                    <p class="text-xs font-semibold text-slate-600 mt-1">Pilih berkas digital untuk diarsipkan</p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeCreateArsipModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold shadow-sm">
                    Simpan ke Arsip
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Preview Arsip --}}
<div id="previewArsipModal" class="modal-overlay" onclick="if(event.target===this)closePreviewArsipModal()">
    <div class="modal-content max-w-lg w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="material-icons-outlined text-blue-600 text-lg">visibility</span>
                <h3 class="text-sm font-bold text-slate-800">Pratinjau Berkas Arsip</h3>
            </div>
            <button onclick="closePreviewArsipModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
                <span class="material-icons-outlined text-lg">close</span>
            </button>
        </div>
        <div class="my-4 space-y-4">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-outlined text-2xl">picture_as_pdf</span>
                </div>
                <div>
                    <h4 id="previewTitle" class="text-xs font-bold text-slate-800">Nama Dokumen</h4>
                    <p id="previewNo" class="text-[11px] text-slate-400">Nomor Arsip</p>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span id="previewCategory" class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700">Kategori</span>
                        <span id="previewStatus" class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">Status</span>
                    </div>
                </div>
            </div>
            <div class="p-3 bg-slate-50/60 rounded-xl border border-slate-100 text-xs space-y-1.5 text-slate-600">
                <div class="flex justify-between">
                    <span>Tanggal Arsip:</span>
                    <span id="previewDate" class="font-bold text-slate-800">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Enkripsi Hash:</span>
                    <span class="font-mono text-[10px] text-slate-500">SHA256: 8f4e2b9c0d1a...</span>
                </div>
                <div class="flex justify-between">
                    <span>Lokasi Storage:</span>
                    <span class="text-slate-800 font-medium">Cloud Vault Node 2</span>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
            <button onclick="downloadArsip(document.getElementById('previewTitle').textContent)" class="flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all">
                <span class="material-icons-outlined text-base">download</span> Unduh Salinan
            </button>
            <button onclick="closePreviewArsipModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- Modal: Edit Arsip --}}
<div id="editArsipModal" class="modal-overlay" onclick="if(event.target===this)closeEditArsipModal()">
    <div class="modal-content max-w-md w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="material-icons-outlined text-blue-600 text-lg">edit</span>
                <h3 class="text-base font-bold text-slate-800">Ubah Metadata Arsip</h3>
            </div>
            <button onclick="closeEditArsipModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>
        <form onsubmit="handleEditArsipSubmit(event)" class="mt-4 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Dokumen</label>
                <input type="text" id="editDocName" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Arsip</label>
                <input type="text" id="editDocNo" required readonly class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-400 bg-slate-50 cursor-not-allowed">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori</label>
                    <select id="editDocCategory" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="Keuangan">Keuangan</option>
                        <option value="Legal">Legal</option>
                        <option value="Operasional">Operasional</option>
                        <option value="SDM">SDM</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status</label>
                    <select id="editDocStatus" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="Finalized">Finalized</option>
                        <option value="Draft">Draft</option>
                        <option value="Archived">Archived</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeEditArsipModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold">Simpan Pembaruan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeEditRow = null;

    // Filter drawer toggle
    function toggleFilterDrawer() {
        const drawer = document.getElementById('filterDrawer');
        const btn = document.getElementById('filterToggleBtn');
        drawer.classList.toggle('hidden');
        if (!drawer.classList.contains('hidden')) {
            btn.classList.add('bg-blue-50', 'border-blue-300', 'text-blue-700');
        } else {
            btn.classList.remove('bg-blue-50', 'border-blue-300', 'text-blue-700');
        }
    }

    // Live filtering
    function filterArsipTable() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const cat = document.getElementById('filterCategorySelect').value;
        const status = document.getElementById('filterStatusSelect').value;
        const year = document.getElementById('filterYearSelect').value;

        const rows = document.querySelectorAll('.arsip-data-row');
        let matchCount = 0;

        rows.forEach(row => {
            const name = (row.dataset.name || '').toLowerCase();
            const no = (row.dataset.no || '').toLowerCase();
            const category = row.dataset.category || '';
            const rowStatus = row.dataset.status || '';
            const date = row.dataset.date || '';

            let matches = true;
            if (query && !name.includes(query) && !no.includes(query) && !category.toLowerCase().includes(query)) {
                matches = false;
            }
            if (cat && category !== cat) matches = false;
            if (status && rowStatus !== status) matches = false;
            if (year && !date.includes(year)) matches = false;

            row.style.display = matches ? '' : 'none';
            if (matches) matchCount++;
        });

        document.getElementById('arsipCountInfo').textContent = `Menampilkan ${matchCount} dokumen`;
    }

    function resetArsipFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterCategorySelect').value = '';
        document.getElementById('filterStatusSelect').value = '';
        document.getElementById('filterYearSelect').value = '';
        filterArsipTable();
        showToast('Filter pencarian direset.', 'info');
    }

    // Modal: Tambah Arsip Baru
    function showCreateArsipModal() {
        document.getElementById('newDocDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('createArsipModal').classList.add('active');
    }
    function closeCreateArsipModal() {
        document.getElementById('createArsipModal').classList.remove('active');
    }

    function handleCreateArsipSubmit(e) {
        e.preventDefault();
        const name = document.getElementById('newDocName').value.trim();
        const no = document.getElementById('newDocNo').value.trim();
        const cat = document.getElementById('newDocCategory').value;
        const dateRaw = document.getElementById('newDocDate').value;
        const status = document.getElementById('newDocStatus').value;

        const dateFormatted = new Date(dateRaw).toLocaleDateString('id-ID', {
            day: 'numeric', month: 'short', year: 'numeric'
        });

        appendNewArsipRow({
            name: name,
            no: no,
            category: cat,
            date: dateFormatted,
            status: status
        });

        closeCreateArsipModal();
        document.getElementById('newDocName').value = '';
        document.getElementById('newDocNo').value = '';

        showToast(`Arsip "${name}" berhasil ditambahkan!`, 'success');
    }

    // Append helper
    function appendNewArsipRow(data) {
        const tbody = document.getElementById('arsipTableBody');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50/50 transition-colors arsip-data-row bg-blue-50/20';
        tr.dataset.name = data.name;
        tr.dataset.no = data.no;
        tr.dataset.category = data.category;
        tr.dataset.status = data.status;
        tr.dataset.date = data.date;

        let statusBadge = '';
        if (data.status === 'Finalized') {
            statusBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Finalized</span>`;
        } else if (data.status === 'Draft') {
            statusBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft</span>`;
        } else {
            statusBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-100"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Archived</span>`;
        }

        tr.innerHTML = `
            <td class="py-4 px-6">
                <div class="flex items-center gap-3.5">
                    <span class="material-icons-outlined text-slate-400 text-xl flex-shrink-0">description</span>
                    <div>
                        <p class="font-bold text-slate-800">${data.name}</p>
                        <p class="text-[11px] text-slate-400">${data.no}</p>
                    </div>
                </div>
            </td>
            <td class="py-4 px-4">
                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    ${data.category}
                </span>
            </td>
            <td class="py-4 px-4 text-slate-600 font-medium">${data.date}</td>
            <td class="py-4 px-4">${statusBadge}</td>
            <td class="py-4 px-6 text-center">
                <div class="flex items-center justify-center gap-2 text-slate-400">
                    <button onclick="previewArsip('${data.name}', '${data.no}', '${data.category}', '${data.status}', '${data.date}')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Lihat Pratinjau">
                        <span class="material-icons-outlined text-lg">visibility</span>
                    </button>
                    <button onclick="downloadArsip('${data.name}')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Unduh Berkas">
                        <span class="material-icons-outlined text-lg">download</span>
                    </button>
                    <button onclick="editArsip('${data.name}', '${data.no}', '${data.category}', '${data.status}')" class="p-1.5 hover:bg-slate-100 hover:text-blue-600 rounded-lg transition-colors" title="Ubah Metadata">
                        <span class="material-icons-outlined text-lg">edit</span>
                    </button>
                </div>
            </td>
        `;

        tbody.insertBefore(tr, tbody.firstChild);
        filterArsipTable();
    }

    // Modal: Preview Arsip
    function previewArsip(name, no, category, status, date) {
        document.getElementById('previewTitle').textContent = name;
        document.getElementById('previewNo').textContent = no;
        document.getElementById('previewCategory').textContent = category;
        document.getElementById('previewStatus').textContent = status;
        document.getElementById('previewDate').textContent = date;
        document.getElementById('previewArsipModal').classList.add('active');
    }
    function closePreviewArsipModal() {
        document.getElementById('previewArsipModal').classList.remove('active');
    }

    // Modal: Edit Arsip
    function editArsip(name, no, category, status) {
        document.getElementById('editDocName').value = name;
        document.getElementById('editDocNo').value = no;
        document.getElementById('editDocCategory').value = category;
        document.getElementById('editDocStatus').value = status;

        // find matching row
        document.querySelectorAll('.arsip-data-row').forEach(row => {
            if (row.dataset.no === no) activeEditRow = row;
        });

        document.getElementById('editArsipModal').classList.add('active');
    }
    function closeEditArsipModal() {
        document.getElementById('editArsipModal').classList.remove('active');
    }

    function handleEditArsipSubmit(e) {
        e.preventDefault();
        const newName = document.getElementById('editDocName').value.trim();
        const newCat = document.getElementById('editDocCategory').value;
        const newStat = document.getElementById('editDocStatus').value;

        if (activeEditRow) {
            activeEditRow.dataset.name = newName;
            activeEditRow.dataset.category = newCat;
            activeEditRow.dataset.status = newStat;

            activeEditRow.querySelector('td:nth-child(1) p.font-bold').textContent = newName;
            activeEditRow.querySelector('td:nth-child(2) span').textContent = newCat;

            const statCell = activeEditRow.querySelector('td:nth-child(4)');
            if (newStat === 'Finalized') {
                statCell.innerHTML = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Finalized</span>`;
            } else if (newStat === 'Draft') {
                statCell.innerHTML = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft</span>`;
            } else {
                statCell.innerHTML = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-100"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Archived</span>`;
            }
        }

        closeEditArsipModal();
        showToast(`Metadata berkas "${newName}" berhasil diperbarui!`, 'success');
    }

    // Download file
    function downloadArsip(name) {
        showToast(`Memulai unduhan berkas: "${name}"...`, 'info');
        setTimeout(() => {
            showToast(`Berkas "${name}" berhasil diunduh.`, 'success');
        }, 1200);
    }

    // Export Documents
    function handleExportDocuments() {
        const type = prompt("Pilih format ekspor berkas arsip:\n1. Excel Spreadsheet (.XLSX)\n2. PDF Report (.PDF)\n3. Comma Separated (.CSV)\n\nKetik nomor pilihan:", "1");
        if (type === '1') {
            showToast("Mengekspor seluruh arsip ke SIMARSIP-Katalog.xlsx...", "success");
        } else if (type === '2') {
            showToast("Membuat berkas laporan resmi SIMARSIP-Katalog.pdf...", "success");
        } else if (type === '3') {
            showToast("Mengekspor data ke format CSV...", "success");
        }
    }

    // Pagination
    function switchArsipPage(pageNo) {
        document.querySelectorAll('#arsipPaginationGroup button').forEach(b => {
            if (b.id && b.id.startsWith('arsipPage')) {
                b.className = 'w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600';
            }
        });
        const activeBtn = document.getElementById(`arsipPage${pageNo}Btn`);
        if (activeBtn) {
            activeBtn.className = 'w-7 h-7 flex items-center justify-center rounded-lg font-bold bg-[#0b1f51] text-white';
        }
        document.getElementById('arsipCountInfo').textContent = `Menampilkan ${(pageNo-1)*4 + 1}-${Math.min(pageNo*4, 48)} dari 48 dokumen`;
        showToast(`Memuat arsip halaman ${pageNo}...`, 'info');
    }

    // Parse URL params for pre-filled search
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const searchQ = urlParams.get('search');
        if (searchQ) {
            document.getElementById('searchInput').value = searchQ;
            filterArsipTable();
        }
    });
</script>
@endpush
