<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langganan & Portal Akses - SIMARSIP</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-[#F8FAFC] text-gray-900 flex flex-col justify-between relative overflow-x-hidden">

    {{-- Subtle ambient blur on top-right (matching Figma screenshot) --}}
    <div class="fixed top-0 right-0 w-[500px] h-[400px] rounded-full pointer-events-none overflow-hidden -z-10"
         style="background: radial-gradient(circle, rgba(215, 226, 246, 0.6) 0%, rgba(235, 242, 253, 0.3) 40%, transparent 70%); filter: blur(60px);">
    </div>

    <div>
        {{-- Navbar --}}
        <header class="bg-white border-b border-gray-100 sticky top-0 z-30">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    {{-- Left: Brand & Tabs --}}
                    <div class="flex items-center gap-8">
                        <a href="/" class="text-xl font-black text-[#0D2B68] tracking-tight">
                            SIMARSIP
                        </a>

                        <nav class="flex items-center gap-6 text-sm">
                            <a href="/subscriptions" class="font-semibold text-[#1E56C9] py-5 border-b-2 border-[#1E56C9]">
                                Subscriptions
                            </a>
                            <a href="#settings" onclick="openSettingsModal()" class="font-medium text-gray-500 hover:text-gray-900 py-5 transition-colors">
                                Settings
                            </a>
                        </nav>
                    </div>

                    {{-- Right: Actions & User Avatar --}}
                    <div class="flex items-center gap-4">
                        {{-- Notification Bell --}}
                        <button type="button" onclick="showNotification('Tidak ada notifikasi baru.')" class="text-gray-500 hover:text-[#0D2B68] p-1.5 rounded-full hover:bg-gray-50 transition-colors" title="Notifikasi">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                            </svg>
                        </button>

                        {{-- Help Icon --}}
                        <button type="button" onclick="showNotification('Pusat Bantuan SIMARSIP siap melayani Anda 24/7.')" class="text-gray-500 hover:text-[#0D2B68] p-1.5 rounded-full hover:bg-gray-50 transition-colors" title="Bantuan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M12 18h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </button>

                        {{-- User Avatar --}}
                        <div class="relative">
                            <button type="button" onclick="toggleUserDropdown()" class="flex items-center rounded-full focus:outline-none focus:ring-2 focus:ring-[#0D2B68]/20">
                                <img src="{{ asset('images/user-avatar.jpg') }}" alt="User Avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200 shadow-xs">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Page Header --}}
            <div class="mb-6">
                <h1 class="text-2xl sm:text-[1.75rem] font-bold text-gray-900 tracking-tight">
                    Langganan & Portal Akses
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Kelola paket Anda dan akses ruang kerja SIMARSIP.
                </p>
            </div>

            @php
                $plan = request('plan', 'enterprise');

                $plansData = [
                    'starter' => [
                        'name' => 'Starter',
                        'status' => 'Aktif',
                        'pembaruan' => 'Gratis (Aktif Selamanya)',
                        'biaya' => 'Gratis',
                        'features' => [
                            'Hingga 5 Pengguna',
                            'Penyimpanan 5GB',
                            'Pencarian Dasar'
                        ]
                    ],
                    'professional' => [
                        'name' => 'Professional',
                        'status' => 'Aktif',
                        'pembaruan' => '15 November 2026',
                        'biaya' => 'Rp 2.5jt /bln',
                        'features' => [
                            'Hingga 25 Pengguna',
                            'Penyimpanan 100GB',
                            'Alur Persetujuan Khusus'
                        ]
                    ],
                    'enterprise' => [
                        'name' => 'Enterprise',
                        'status' => 'Aktif',
                        'pembaruan' => '15 November 2026',
                        'biaya' => 'Kustom',
                        'features' => [
                            'Pengguna Tak Terbatas',
                            'Penyimpanan 100GB',
                            'Dukungan 24/7'
                        ]
                    ],
                ];

                $currentPlan = $plansData[$plan] ?? $plansData['enterprise'];
            @endphp

            {{-- Card Paket Aktif --}}
            <div class="bg-white rounded-xl border border-gray-200/90 shadow-xs p-6 sm:p-7 relative transition-all">
                {{-- Top: Title & Badge --}}
                <div class="flex items-center gap-2.5 mb-5">
                    <h2 class="text-base font-bold text-[#0D2B68]">
                        Paket Aktif: <span id="plan-name-text">{{ $currentPlan['name'] }}</span>
                    </h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-[#DCFCE7] text-[#16A34A]">
                        {{ $currentPlan['status'] }}
                    </span>
                </div>

                {{-- Middle Grid: Pembaruan & Biaya + Features --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    {{-- Left: Pembaruan & Feature List --}}
                    <div class="md:col-span-5 space-y-3">
                        <div>
                            <div class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-1">
                                PEMBARUAN
                            </div>
                            <div class="text-xs sm:text-sm font-semibold text-gray-900" id="plan-renewal-text">
                                {{ $currentPlan['pembaruan'] }}
                            </div>
                        </div>

                        {{-- Feature List --}}
                        <div class="space-y-1.5 pt-1" id="plan-features-list">
                            @foreach ($currentPlan['features'] as $feature)
                                <div class="flex items-center gap-2 text-xs text-gray-700">
                                    <svg class="w-3.5 h-3.5 text-[#1E56C9] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Center: Biaya --}}
                    <div class="md:col-span-3">
                        <div class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-1">
                            BIAYA
                        </div>
                        <div class="text-xs sm:text-sm font-semibold text-gray-900" id="plan-cost-text">
                            {{ $currentPlan['biaya'] }}
                        </div>
                    </div>

                    {{-- Right: Actions --}}
                    <div class="md:col-span-4 flex items-center justify-start md:justify-end gap-2.5">
                        <button type="button" onclick="openBillingModal()" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors shadow-2xs">
                            Kelola Tagihan
                        </button>
                        <button type="button" onclick="openUpgradeModal()" class="px-4 py-2 text-xs font-semibold text-white bg-[#173A7A] hover:bg-[#0D2B68] rounded-md transition-colors shadow-2xs">
                            Upgrade Paket
                        </button>
                    </div>
                </div>
            </div>

            {{-- 2 Portal Akses Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                {{-- Card 1: Dashboard Analitik --}}
                <div class="bg-white rounded-xl border border-gray-200/90 shadow-xs overflow-hidden flex items-stretch justify-between min-h-[150px] hover:border-gray-300 transition-all group">
                    <div class="p-6 flex flex-col justify-between flex-1">
                        <div>
                            {{-- Icon --}}
                            <div class="w-9 h-9 bg-[#173A7A] rounded-lg flex items-center justify-center text-white mb-3 shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                                </svg>
                            </div>

                            <h3 class="text-sm sm:text-[15px] font-bold text-gray-900">
                                Dashboard Analitik
                            </h3>
                            <p class="text-[11px] text-gray-500 mt-1 max-w-[280px] leading-relaxed">
                                Lihat ringkasan statistik, tren upload, dan aktivitas sistem.
                            </p>
                        </div>

                        <a href="#dashboard" onclick="openPortalModal('Dashboard Analitik', 'Akses modul analitik dokumen dan statistik aktivitas pengguna.')" class="inline-flex items-center gap-1 text-xs font-semibold text-[#1E56C9] hover:underline pt-3 group-hover:translate-x-0.5 transition-transform">
                            Buka Dashboard
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Right Illustration Box (matching Figma screenshot) --}}
                    <div class="w-[30%] bg-[#EEF4FB] rounded-l-2xl my-2 mr-2 flex items-center justify-center relative overflow-hidden">
                        {{-- Subtle chart art --}}
                        <div class="opacity-40 flex items-end gap-1.5 h-12">
                            <div class="w-2.5 bg-[#1E56C9] h-5 rounded-t-xs"></div>
                            <div class="w-2.5 bg-[#1E56C9] h-8 rounded-t-xs"></div>
                            <div class="w-2.5 bg-[#1E56C9] h-12 rounded-t-xs"></div>
                            <div class="w-2.5 bg-[#1E56C9] h-7 rounded-t-xs"></div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Manajemen Arsip --}}
                <div class="bg-white rounded-xl border border-gray-200/90 shadow-xs overflow-hidden flex items-stretch justify-between min-h-[150px] hover:border-gray-300 transition-all group">
                    <div class="p-6 flex flex-col justify-between flex-1">
                        <div>
                            {{-- Icon --}}
                            <div class="w-9 h-9 bg-[#173A7A] rounded-lg flex items-center justify-center text-white mb-3 shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                                </svg>
                            </div>

                            <h3 class="text-sm sm:text-[15px] font-bold text-gray-900">
                                Manajemen Arsip
                            </h3>
                            <p class="text-[11px] text-gray-500 mt-1 max-w-[280px] leading-relaxed">
                                Kelola, cari, dan setujui dokumen digital institusi Anda.
                            </p>
                        </div>

                        <a href="#arsip" onclick="openPortalModal('Manajemen Arsip', 'Akses modul penomoran, pengarsipan, pencarian dokumen, dan approval surat digital.')" class="inline-flex items-center gap-1 text-xs font-semibold text-[#1E56C9] hover:underline pt-3 group-hover:translate-x-0.5 transition-transform">
                            Kelola File
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Right Illustration Box (matching Figma screenshot) --}}
                    <div class="w-[30%] bg-[#EEF4FB] rounded-l-2xl my-2 mr-2 flex items-center justify-center relative overflow-hidden">
                        {{-- Subtle folder art --}}
                        <div class="opacity-40 flex flex-col items-center">
                            <svg class="w-10 h-10 text-[#1E56C9]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.5 21a3 3 0 003-3v-4.5a3 3 0 00-3-3h-1.5V9a3 3 0 00-3-3h-3.75l-1.5-2.25A2.25 2.25 0 007.5 3H4.5A3 3 0 001.5 6v12a3 3 0 003 3h15z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-100 mt-12 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-8">
                {{-- Brand Info --}}
                <div class="md:col-span-4 space-y-3">
                    <span class="text-lg font-black text-[#0D2B68] tracking-tight">SIMARSIP</span>
                    <p class="text-xs text-gray-500 leading-relaxed max-w-sm">
                        Sistem Manajemen Arsip yang dirancang untuk keamanan, efisiensi, dan keandalan tingkat enterprise.
                    </p>
                    <p class="text-xs text-gray-400">
                        © 2026 SIMARSIP. Sistem Manajemen Arsip.
                    </p>
                </div>

                {{-- Tautan Cepat --}}
                <div class="md:col-span-3 space-y-2.5">
                    <h4 class="text-xs font-bold text-gray-900 tracking-wider">Tautan Cepat</h4>
                    <ul class="space-y-1.5 text-xs text-gray-500">
                        <li><a href="/#beranda" class="hover:text-[#0D2B68] transition-colors">Produk</a></li>
                        <li><a href="/#fitur" class="hover:text-[#0D2B68] transition-colors">Fitur</a></li>
                        <li><a href="/#tentang" class="hover:text-[#0D2B68] transition-colors">Solusi</a></li>
                        <li><a href="/#harga" class="hover:text-[#0D2B68] transition-colors">Harga</a></li>
                    </ul>
                </div>

                {{-- Sumber Daya --}}
                <div class="md:col-span-3 space-y-2.5">
                    <h4 class="text-xs font-bold text-gray-900 tracking-wider">Sumber Daya</h4>
                    <ul class="space-y-1.5 text-xs text-gray-500">
                        <li><a href="#" class="hover:text-[#0D2B68] transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-[#0D2B68] transition-colors">API</a></li>
                    </ul>
                </div>

                {{-- Perusahaan --}}
                <div class="md:col-span-2 space-y-2.5">
                    <h4 class="text-xs font-bold text-gray-900 tracking-wider">Perusahaan</h4>
                    <ul class="space-y-1.5 text-xs text-gray-500">
                        <li><a href="/#tentang" class="hover:text-[#0D2B68] transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-[#0D2B68] transition-colors">Karir</a></li>
                        <li><a href="/#kontak" class="hover:text-[#0D2B68] transition-colors">Kontak</a></li>
                        <li><a href="#" class="hover:text-[#0D2B68] transition-colors">Privasi</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    {{-- Modal 1: Upgrade / Ganti Paket --}}
    <div id="upgrade-modal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Pilih & Upgrade Paket Langganan</h3>
                    <p class="text-xs text-gray-500">Pilih paket yang paling sesuai dengan kebutuhan kapasitas institusi Anda.</p>
                </div>
                <button type="button" onclick="closeUpgradeModal()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 my-6">
                {{-- Starter --}}
                <div class="border border-gray-200 rounded-xl p-4 flex flex-col justify-between hover:border-[#1E56C9] transition-all">
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">Starter</h4>
                        <div class="text-xl font-bold text-gray-900 my-2">Gratis</div>
                        <ul class="text-[11px] text-gray-600 space-y-1.5 mb-4">
                            <li>✓ 5 Pengguna</li>
                            <li>✓ 5GB Storage</li>
                            <li>✓ Pencarian Dasar</li>
                        </ul>
                    </div>
                    <button type="button" onclick="selectPlan('starter')" class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold rounded-md transition-colors">
                        Pilih Starter
                    </button>
                </div>

                {{-- Professional --}}
                <div class="border border-gray-200 rounded-xl p-4 flex flex-col justify-between hover:border-[#1E56C9] transition-all">
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">Professional</h4>
                        <div class="text-xl font-bold text-gray-900 my-2">Rp 2.5jt<span class="text-xs text-gray-500 font-normal">/bln</span></div>
                        <ul class="text-[11px] text-gray-600 space-y-1.5 mb-4">
                            <li>✓ 25 Pengguna</li>
                            <li>✓ 100GB Storage</li>
                            <li>✓ Approval Khusus</li>
                        </ul>
                    </div>
                    <button type="button" onclick="selectPlan('professional')" class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold rounded-md transition-colors">
                        Pilih Pro
                    </button>
                </div>

                {{-- Enterprise --}}
                <div class="border-2 border-[#173A7A] rounded-xl p-4 bg-[#F8FAFC] flex flex-col justify-between relative shadow-sm">
                    <span class="absolute -top-2.5 right-3 bg-[#1E56C9] text-white text-[9px] font-bold px-2 py-0.5 rounded-full">POPULER</span>
                    <div>
                        <h4 class="text-sm font-bold text-[#0D2B68]">Enterprise</h4>
                        <div class="text-xl font-bold text-[#0D2B68] my-2">Kustom</div>
                        <ul class="text-[11px] text-gray-600 space-y-1.5 mb-4">
                            <li>✓ Unlimited User</li>
                            <li>✓ 100GB Storage</li>
                            <li>✓ Dukungan 24/7</li>
                        </ul>
                    </div>
                    <button type="button" onclick="selectPlan('enterprise')" class="w-full py-2 bg-[#173A7A] hover:bg-[#0D2B68] text-white text-xs font-semibold rounded-md transition-colors">
                        Pilih Enterprise
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal 2: Kelola Tagihan --}}
    <div id="billing-modal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-900">Kelola Tagihan & Faktur</h3>
                <button type="button" onclick="closeBillingModal()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="py-4 space-y-4">
                <div class="bg-gray-50 p-4 rounded-xl space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Nomor Kontrak:</span>
                        <span class="font-semibold text-gray-900">SIM-2026-ENT-0089</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Metode Pembayaran:</span>
                        <span class="font-semibold text-gray-900">Bank Transfer / SPK Kedinasan</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Status Faktur:</span>
                        <span class="font-semibold text-green-600">Lunas (Aktif s.d 15 Nov 2026)</span>
                    </div>
                </div>

                <h4 class="text-xs font-bold text-gray-800">Riwayat Faktur Terakhir</h4>
                <div class="border border-gray-200 rounded-lg overflow-hidden text-xs">
                    <div class="flex items-center justify-between p-3 border-b border-gray-100 bg-white">
                        <div>
                            <div class="font-semibold text-gray-900">INV-2025-11-001</div>
                            <div class="text-[10px] text-gray-400">15 Nov 2025 • Paket Enterprise Tahunan</div>
                        </div>
                        <button type="button" onclick="showNotification('Mengunduh faktur PDF...')" class="text-[#1E56C9] font-medium hover:underline">
                            Unduh PDF
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 flex justify-end">
                <button type="button" onclick="closeBillingModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold rounded-md transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Notification Toast --}}
    <div id="toast" class="fixed bottom-6 right-6 z-50 hidden bg-[#0D2B68] text-white text-xs px-4 py-3 rounded-lg shadow-xl flex items-center gap-2">
        <span id="toast-text">Notifikasi</span>
    </div>

    {{-- Interactive JavaScript --}}
    <script>
        function openUpgradeModal() {
            document.getElementById('upgrade-modal').classList.remove('hidden');
        }
        function closeUpgradeModal() {
            document.getElementById('upgrade-modal').classList.add('hidden');
        }
        function openBillingModal() {
            document.getElementById('billing-modal').classList.remove('hidden');
        }
        function closeBillingModal() {
            document.getElementById('billing-modal').classList.add('hidden');
        }

        function selectPlan(planKey) {
            window.location.href = '/subscriptions?plan=' + planKey;
        }

        function showNotification(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-text').innerText = msg;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        function openPortalModal(title, desc) {
            showNotification('Membuka ' + title + '...');
        }

        function openSettingsModal() {
            showNotification('Menu Pengaturan Sistem SIMARSIP.');
        }

        function toggleUserDropdown() {
            showNotification('Akun Admin SIMARSIP terhubung.');
        }
    </script>
</body>
</html>
