@extends('layouts.admin')

@section('title', 'Pengaturan Peran & Hak Akses - SIMARSIP')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
        <a href="#" class="hover:text-slate-600 transition-colors">Pengaturan</a>
        <span class="text-slate-300">›</span>
        <span class="text-blue-600 font-semibold">Peran & Hak Akses</span>
    </div>

    {{-- Title and Top Buttons --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#0b1f51] tracking-tight">Pengaturan Peran & Hak Akses</h1>
            <p class="text-xs text-slate-500 mt-1">Konfigurasikan kontrol akses berbasis peran (RBAC) untuk integritas data instansi.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <button onclick="handleResetPermissions()" class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm">
                Reset
            </button>
            <button onclick="handleSavePermissions()" class="px-5 py-2.5 bg-[#0b1f51] hover:bg-[#122b6c] text-white rounded-xl text-xs font-bold transition-all shadow-sm active:scale-[0.98]">
                Simpan Perubahan
            </button>
        </div>
    </div>

    {{-- Main Layout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Left Column: Role Selector & Summary (4 cols) --}}
        <div class="lg:col-span-4 space-y-5">
            {{-- Role List Box matching exact screenshot with divider and left bar accent --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">PILIH PERAN</h2>
                </div>
                <div class="divide-y divide-slate-100" id="rolesContainer">
                    {{-- Admin Sistem --}}
                    <div onclick="selectRole('admin')" id="role-btn-admin" class="role-row flex items-center gap-3.5 px-4 py-3.5 cursor-pointer transition-all border-l-4 border-l-[#0b1f51] bg-[#f1f5f9]">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 text-blue-700">
                            <span class="material-icons-outlined text-2xl">shield</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="role-title text-xs font-bold text-blue-700 leading-tight">Admin Sistem</h3>
                            <p class="text-[11px] text-slate-400 truncate mt-0.5">Akses penuh infrastruktur</p>
                        </div>
                    </div>

                    {{-- Staff --}}
                    <div onclick="selectRole('staff')" id="role-btn-staff" class="role-row flex items-center gap-3.5 px-4 py-3.5 cursor-pointer transition-all border-l-4 border-l-transparent bg-white hover:bg-slate-50">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 text-slate-400">
                            <span class="material-icons-outlined text-2xl">badge</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="role-title text-xs font-bold text-slate-800 leading-tight">Staff</h3>
                            <p class="text-[11px] text-slate-400 truncate mt-0.5">Operasional arsip harian</p>
                        </div>
                    </div>

                    {{-- Auditor --}}
                    <div onclick="selectRole('auditor')" id="role-btn-auditor" class="role-row flex items-center gap-3.5 px-4 py-3.5 cursor-pointer transition-all border-l-4 border-l-transparent bg-white hover:bg-slate-50">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 text-slate-400">
                            <span class="material-icons-outlined text-2xl">verified_user</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="role-title text-xs font-bold text-slate-800 leading-tight">Auditor</h3>
                            <p class="text-[11px] text-slate-400 truncate mt-0.5">Pemeriksaan & Kepatuhan</p>
                        </div>
                    </div>

                    {{-- Pimpinan --}}
                    <div onclick="selectRole('pimpinan')" id="role-btn-pimpinan" class="role-row flex items-center gap-3.5 px-4 py-3.5 cursor-pointer transition-all border-l-4 border-l-transparent bg-white hover:bg-slate-50">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 text-slate-400">
                            <span class="material-icons-outlined text-2xl">person_outline</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="role-title text-xs font-bold text-slate-800 leading-tight">Pimpinan</h3>
                            <p class="text-[11px] text-slate-400 truncate mt-0.5">Persetujuan & Laporan</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Peran Card matching exact screenshots --}}
            <div class="bg-[#1e3a8a] text-white rounded-2xl p-5 shadow-sm space-y-4 relative overflow-hidden">
                <div class="flex items-center gap-2">
                    <span class="material-icons-outlined text-blue-200 text-lg">info</span>
                    <h2 class="text-xs font-black tracking-wider uppercase text-blue-100">RINGKASAN PERAN</h2>
                </div>
                <p id="roleSummaryDescription" class="text-xs text-blue-100/90 leading-relaxed min-h-[60px]">
                    Peran Admin Sistem memiliki wewenang tertinggi dalam hierarki SIMARSIP. Bertanggung jawab atas pemeliharaan sistem, manajemen basis data, dan konfigurasi keamanan tingkat lanjut.
                </p>
                <div class="pt-3 border-t border-blue-400/30 flex items-center justify-between text-xs">
                    <span class="text-blue-200">Total Pengguna:</span>
                    <span id="roleUserCount" class="font-bold text-white tracking-wide">3 Personil</span>
                </div>
            </div>

            {{-- Tambah Peran Baru Button --}}
            <button onclick="showCreateRoleModal()" class="w-full py-3 px-4 bg-[#1877F2] hover:bg-[#1264cc] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98]">
                <span class="material-icons-outlined text-base">add</span>
                <span>Tambah Peran Baru</span>
            </button>
        </div>

        {{-- Right Column: Permissions Toggles Panel (8 cols) --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Section 1: Manajemen Arsip --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2.5">
                    <span class="material-icons-outlined text-blue-700 text-lg">folder_open</span>
                    <h2 class="text-xs font-black text-slate-800 tracking-wide uppercase">Manajemen Arsip</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-5 w-32">NAMA IZIN</th>
                                <th class="py-3 px-4">DESKRIPSI OPERASIONAL</th>
                                <th class="py-3 px-5 text-right w-24">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-5 font-bold text-slate-800">Create</td>
                                <td class="py-3.5 px-4 text-slate-600 text-[11px]">Menambah entri dokumen baru dan mengunggah lampiran fisik digital.</td>
                                <td class="py-3.5 px-5 text-right">
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="perm_create" onchange="markPermDirty()" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-5 font-bold text-slate-800">View</td>
                                <td class="py-3.5 px-4 text-slate-600 text-[11px]">Melihat metadata dokumen dan melakukan preview file tersimpan.</td>
                                <td class="py-3.5 px-5 text-right">
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="perm_view" onchange="markPermDirty()" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-5 font-bold text-slate-800">Edit</td>
                                <td class="py-3.5 px-4 text-slate-600 text-[11px]">Memperbarui informasi metadata dan mengganti versi dokumen.</td>
                                <td class="py-3.5 px-5 text-right">
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="perm_edit" onchange="markPermDirty()" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-5 font-bold text-slate-800">Delete</td>
                                <td class="py-3.5 px-4 text-slate-600 text-[11px]">Memindahkan dokumen ke 'Recycle Bin' (Soft Delete).</td>
                                <td class="py-3.5 px-5 text-right">
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="perm_delete" onchange="markPermDirty()" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-5 font-bold text-slate-800">Restore</td>
                                <td class="py-3.5 px-4 text-slate-600 text-[11px]">Memulihkan data dari tempat sampah ke lokasi asal.</td>
                                <td class="py-3.5 px-5 text-right">
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="perm_restore" onchange="markPermDirty()" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Section 2: Two Cards Grid (Persetujuan & Audit/Laporan) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Persetujuan --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 space-y-3.5">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                        <span class="material-icons-outlined text-blue-700 text-lg">fact_check</span>
                        <h2 class="text-xs font-black text-slate-800 uppercase tracking-wide">Persetujuan</h2>
                    </div>
                    {{-- Approve Box --}}
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/40 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold text-slate-800">Approve</h3>
                            <p class="text-[11px] text-slate-500">Menyetujui draf arsip</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="perm_approve" onchange="markPermDirty()" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    {{-- Reject Box --}}
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/40 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold text-slate-800">Reject</h3>
                            <p class="text-[11px] text-slate-500">Menolak/Mengembalikan draf</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="perm_reject" onchange="markPermDirty()" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                {{-- Audit & Laporan --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 space-y-3.5">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                        <span class="material-icons-outlined text-blue-700 text-lg">bar_chart</span>
                        <h2 class="text-xs font-black text-slate-800 uppercase tracking-wide">Audit & Laporan</h2>
                    </div>
                    {{-- View Audit Log --}}
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/40 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold text-slate-800">View Audit Log</h3>
                            <p class="text-[11px] text-slate-500">Melihat rekam jejak sistem</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="perm_audit" onchange="markPermDirty()" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    {{-- Export Reports --}}
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/40 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold text-slate-800">Export Reports</h3>
                            <p class="text-[11px] text-slate-500">Unduh laporan statistik</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="perm_export" onchange="markPermDirty()" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Section 3: Manajemen User --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 space-y-3.5">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="material-icons-outlined text-blue-700 text-lg">group</span>
                    <h2 class="text-xs font-black text-slate-800 uppercase tracking-wide">Manajemen User</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Create User --}}
                    <div class="p-3.5 rounded-xl border border-red-100 bg-red-50/30 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-icons-outlined text-lg">person_add</span>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800">Create User</h3>
                                <p class="text-[11px] font-medium text-rose-500">Akses Administratif Sensitif</p>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="perm_create_user" onchange="markPermDirty()" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    {{-- Edit Roles --}}
                    <div class="p-3.5 rounded-xl border border-red-100 bg-red-50/30 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-icons-outlined text-lg">key</span>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800">Edit Roles</h3>
                                <p class="text-[11px] font-medium text-rose-500">Izin Konfigurasi Global</p>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="perm_edit_roles" onchange="markPermDirty()" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Role Modal --}}
<div id="createRoleModal" class="modal-overlay" onclick="if(event.target===this)closeCreateRoleModal()">
    <div class="modal-content max-w-md w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-outlined text-lg">admin_panel_settings</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Tambah Peran Baru</h3>
                    <p class="text-xs text-slate-400">Buat peran hierarki baru dengan template izin</p>
                </div>
            </div>
            <button onclick="closeCreateRoleModal()" class="p-1 hover:bg-slate-100 rounded-lg text-slate-400">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>
        <form onsubmit="handleCreateRoleSubmit(event)" class="mt-4 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Peran</label>
                <input type="text" id="newRoleTitle" required placeholder="Contoh: Manajer Operasional, Koordinator Unit..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Ringkas</label>
                <textarea id="newRoleDesc" rows="3" required placeholder="Deskripsikan wewenang dan tanggung jawab peran ini..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Preset Izin Awal</label>
                <select id="newRolePreset" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="staff">Mirip Staff (Operasional Arsip)</option>
                    <option value="auditor">Mirip Auditor (Pemeriksaan & Laporan)</option>
                    <option value="pimpinan">Mirip Pimpinan (Persetujuan Arsip)</option>
                    <option value="minimal">Minimal (Hanya Lihat Dokumen)</option>
                </select>
            </div>
            <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeCreateRoleModal()" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-[#1877F2] hover:bg-[#1264cc] text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                    Simpan Peran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // State management for roles and permissions matching exact screenshots
    const rolesDatabase = {
        admin: {
            title: 'Admin Sistem',
            subtitle: 'Akses penuh infrastruktur',
            icon: 'shield',
            description: 'Peran Admin Sistem memiliki wewenang tertinggi dalam hierarki SIMARSIP. Bertanggung jawab atas pemeliharaan sistem, manajemen basis data, dan konfigurasi keamanan tingkat lanjut.',
            users: '3 Personil',
            permissions: {
                create: true, view: true, edit: true, delete: true, restore: true,
                approve: true, reject: true, audit: true, export: true,
                create_user: true, edit_roles: true
            }
        },
        staff: {
            title: 'Staff',
            subtitle: 'Operasional arsip harian',
            icon: 'badge',
            description: 'Peran Staff memiliki wewenang untuk pengelolaan arsip harian, input data, dan manipulasi dokumen dasar dalam SIMARSIP.',
            users: '12 Personil',
            permissions: {
                create: true, view: true, edit: true, delete: false, restore: false,
                approve: false, reject: false, audit: false, export: false,
                create_user: false, edit_roles: false
            }
        },
        auditor: {
            title: 'Auditor',
            subtitle: 'Pemeriksaan & Kepatuhan',
            icon: 'verified_user',
            description: 'Peran Auditor berfokus pada pengawasan, verifikasi kepatuhan, dan pemantauan jejak audit sistem untuk memastikan transparansi data.',
            users: '5 Personil',
            permissions: {
                create: false, view: true, edit: false, delete: false, restore: false,
                approve: true, reject: true, audit: true, export: true,
                create_user: false, edit_roles: false
            }
        },
        pimpinan: {
            title: 'Pimpinan',
            subtitle: 'Persetujuan & Laporan',
            icon: 'person_outline',
            description: 'Peran Pimpinan memiliki wewenang strategis untuk memberikan persetujuan dokumen, melihat laporan ringkasan, dan memantau performa instansi secara keseluruhan.',
            users: '2 Personil',
            permissions: {
                create: false, view: true, edit: false, delete: false, restore: false,
                approve: true, reject: true, audit: true, export: true,
                create_user: false, edit_roles: false
            }
        }
    };

    const persistedRoles = @json($roles->mapWithKeys(fn ($role) => [
        strtolower($role->name) => [
            'id' => $role->id,
            'title' => $role->name,
            'description' => $role->description,
            'users' => $role->users_count . ' Personil',
            'permissions' => $role->permissions->pluck('izin')->values(),
        ],
    ]));

    Object.entries(persistedRoles).forEach(([roleKey, persistedRole]) => {
        if (!rolesDatabase[roleKey]) return;

        const permissionNames = new Set(persistedRole.permissions);
        rolesDatabase[roleKey].id = persistedRole.id;
        rolesDatabase[roleKey].title = persistedRole.title;
        rolesDatabase[roleKey].description = persistedRole.description || rolesDatabase[roleKey].description;
        rolesDatabase[roleKey].users = persistedRole.users;
        rolesDatabase[roleKey].permissions = {
            create: permissionNames.has('arsip.create'),
            view: permissionNames.has('arsip.view'),
            edit: permissionNames.has('arsip.edit'),
            delete: permissionNames.has('arsip.delete'),
            restore: permissionNames.has('arsip.restore'),
            approve: permissionNames.has('arsip.approve'),
            reject: permissionNames.has('arsip.reject'),
            audit: permissionNames.has('arsip.audit'),
            export: permissionNames.has('arsip.export'),
            create_user: permissionNames.has('users.create'),
            edit_roles: permissionNames.has('roles.edit'),
        };
    });

    let activeRoleId = 'admin';
    let isDirty = false;

    // Apply role permissions to UI toggles
    function applyRoleToUI(roleKey) {
        const role = rolesDatabase[roleKey];
        if (!role) return;

        // Description & user count
        document.getElementById('roleSummaryDescription').textContent = role.description;
        document.getElementById('roleUserCount').textContent = role.users;

        // Toggles
        const p = role.permissions;
        document.getElementById('perm_create').checked = !!p.create;
        document.getElementById('perm_view').checked = !!p.view;
        document.getElementById('perm_edit').checked = !!p.edit;
        document.getElementById('perm_delete').checked = !!p.delete;
        document.getElementById('perm_restore').checked = !!p.restore;

        document.getElementById('perm_approve').checked = !!p.approve;
        document.getElementById('perm_reject').checked = !!p.reject;

        document.getElementById('perm_audit').checked = !!p.audit;
        document.getElementById('perm_export').checked = !!p.export;

        document.getElementById('perm_create_user').checked = !!p.create_user;
        document.getElementById('perm_edit_roles').checked = !!p.edit_roles;

        isDirty = false;
    }

    // Role selection with exact active bar and background styling
    function selectRole(roleKey) {
        if (isDirty) {
            if (!confirm(`Ada perubahan pada peran ${rolesDatabase[activeRoleId].title} yang belum disimpan. Pindah tanpa menyimpan?`)) {
                return;
            }
        }

        activeRoleId = roleKey;

        // Reset all role rows to inactive style
        document.querySelectorAll('.role-row').forEach(row => {
            row.classList.remove('border-l-[#0b1f51]', 'bg-[#f1f5f9]');
            row.classList.add('border-l-transparent', 'bg-white');

            const title = row.querySelector('.role-title');
            if (title) {
                title.classList.remove('text-blue-700');
                title.classList.add('text-slate-800');
            }

            const iconContainer = row.querySelector('div:first-child');
            if (iconContainer) {
                iconContainer.classList.remove('text-blue-700');
                iconContainer.classList.add('text-slate-400');
            }
        });

        // Set active role row style
        const activeRow = document.getElementById(`role-btn-${roleKey}`);
        if (activeRow) {
            activeRow.classList.remove('border-l-transparent', 'bg-white');
            activeRow.classList.add('border-l-[#0b1f51]', 'bg-[#f1f5f9]');

            const title = activeRow.querySelector('.role-title');
            if (title) {
                title.classList.remove('text-slate-800');
                title.classList.add('text-blue-700');
            }

            const iconContainer = activeRow.querySelector('div:first-child');
            if (iconContainer) {
                iconContainer.classList.remove('text-slate-400');
                iconContainer.classList.add('text-blue-700');
            }
        }

        applyRoleToUI(roleKey);
        showToast(`Memuat konfigurasi peran: ${rolesDatabase[roleKey].title}`, 'info');
    }

    function markPermDirty() {
        isDirty = true;
    }

    // Save changes
    function handleSavePermissions() {
        const role = rolesDatabase[activeRoleId];
        if (!role || !role.id) {
            showToast('Peran belum tersedia untuk disimpan.', 'error');
            return;
        }

        role.permissions = {
            create: document.getElementById('perm_create').checked,
            view: document.getElementById('perm_view').checked,
            edit: document.getElementById('perm_edit').checked,
            delete: document.getElementById('perm_delete').checked,
            restore: document.getElementById('perm_restore').checked,
            approve: document.getElementById('perm_approve').checked,
            reject: document.getElementById('perm_reject').checked,
            audit: document.getElementById('perm_audit').checked,
            export: document.getElementById('perm_export').checked,
            create_user: document.getElementById('perm_create_user').checked,
            edit_roles: document.getElementById('perm_edit_roles').checked
        };

        const permissions = Object.entries(role.permissions)
            .filter(([, enabled]) => enabled)
            .map(([permission]) => ({
                create: 'arsip.create',
                view: 'arsip.view',
                edit: 'arsip.edit',
                delete: 'arsip.delete',
                restore: 'arsip.restore',
                approve: 'arsip.approve',
                reject: 'arsip.reject',
                audit: 'arsip.audit',
                export: 'arsip.export',
                create_user: 'users.create',
                edit_roles: 'roles.edit',
            }[permission]))
            .filter(Boolean);

        fetch(`/admin/roles/${role.id}/permissions`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ permissions }),
        }).then(response => {
            if (!response.ok) throw new Error('Hak akses gagal disimpan.');
            isDirty = false;
            showToast(`Perubahan hak akses untuk "${role.title}" berhasil disimpan!`, 'success');
        }).catch(error => showToast(error.message, 'error'));
    }

    // Reset permissions
    function handleResetPermissions() {
        if (confirm(`Kembalikan hak akses "${rolesDatabase[activeRoleId].title}" ke konfigurasi standar?`)) {
            applyRoleToUI(activeRoleId);
            handleSavePermissions();
        }
    }

    // Create Role Modal
    function showCreateRoleModal() {
        document.getElementById('createRoleModal').classList.add('active');
    }
    function closeCreateRoleModal() {
        document.getElementById('createRoleModal').classList.remove('active');
    }

    function handleCreateRoleSubmit(e) {
        e.preventDefault();
        const title = document.getElementById('newRoleTitle').value.trim();
        const desc = document.getElementById('newRoleDesc').value.trim();
        const preset = document.getElementById('newRolePreset').value;

        if (!title) return;

        fetch("{{ route('admin.roles.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ name: title, description: desc, preset }),
        }).then(response => {
            if (!response.ok) throw new Error('Peran gagal disimpan. Nama peran mungkin sudah digunakan.');
            return response;
        }).then(() => {
            closeCreateRoleModal();
            showToast(`Peran "${title}" berhasil disimpan.`, 'success');
            setTimeout(() => window.location.reload(), 700);
        }).catch(error => showToast(error.message, 'error'));

    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        applyRoleToUI('admin');
    });
</script>
@endpush
