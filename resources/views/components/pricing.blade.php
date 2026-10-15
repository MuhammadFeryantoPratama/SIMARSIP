{{-- Pricing Section --}}
<section id="harga" class="py-16 sm:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0D2B68] tracking-tight mb-2.5">
                Pilih Paket Sesuai Kebutuhan
            </h2>
            <p class="text-xs sm:text-sm text-gray-500">
                Skalabilitas harga yang fleksibel untuk berbagai ukuran organisasi.
            </p>
        </div>

        {{-- Pricing Grid --}}
        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto items-stretch">
            {{-- Starter Plan --}}
            <div class="bg-white border border-gray-200/90 rounded-xl p-7 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Starter</h3>
                    <p class="text-[11px] text-gray-500 mb-4">Untuk tim kecil</p>

                    <div class="mb-6">
                        <span class="text-3xl font-bold text-gray-900">Gratis</span>
                    </div>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2.5 text-xs text-gray-600">
                            <span class="text-[#1E56C9] font-bold">✓</span>
                            Hingga 5 Pengguna
                        </li>
                        <li class="flex items-center gap-2.5 text-xs text-gray-600">
                            <span class="text-[#1E56C9] font-bold">✓</span>
                            Penyimpanan 5GB
                        </li>
                        <li class="flex items-center gap-2.5 text-xs text-gray-600">
                            <span class="text-[#1E56C9] font-bold">✓</span>
                            Pencarian Dasar
                        </li>
                    </ul>
                </div>

                <a href="/subscriptions?plan=starter" class="w-full block text-center py-2 px-3 border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                    Pilih Starter
                </a>
            </div>

            {{-- Enterprise Plan (Deep Dark Navy) --}}
            <div class="relative bg-[#0D2B68] rounded-xl p-7 text-white flex flex-col justify-between shadow-xl">
                {{-- Top Badge --}}
                <div class="absolute -top-3 right-6">
                    <span class="bg-[#1E56C9] text-white text-[10px] font-semibold px-2.5 py-0.5 rounded-full shadow">
                        Paling Populer
                    </span>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-white">Enterprise</h3>
                    <p class="text-[11px] text-blue-200 mb-4">Untuk instansi & perusahaan besar</p>

                    <div class="mb-6">
                        <span class="text-3xl font-bold text-white">Kustom</span>
                    </div>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2.5 text-xs text-blue-100">
                            <span class="text-blue-300 font-bold">✓</span>
                            Pengguna Tak Terbatas
                        </li>
                        <li class="flex items-center gap-2.5 text-xs text-blue-100">
                            <span class="text-blue-300 font-bold">✓</span>
                            Fitur Keamanan Kustom
                        </li>
                        <li class="flex items-center gap-2.5 text-xs text-blue-100">
                            <span class="text-blue-300 font-bold">✓</span>
                            Fitur Lengkap & API Akses
                        </li>
                        <li class="flex items-center gap-2.5 text-xs text-blue-100">
                            <span class="text-blue-300 font-bold">✓</span>
                            Prioritas Dukungan 24/7
                        </li>
                    </ul>
                </div>

                <a href="/subscriptions?plan=enterprise" class="w-full block text-center py-2 px-3 bg-[#1E56C9] hover:bg-[#1A4BB0] text-xs font-semibold text-white rounded-md transition-colors shadow">
                    Hubungi Penjualan
                </a>
            </div>

            {{-- Professional Plan --}}
            <div class="bg-white border border-gray-200/90 rounded-xl p-7 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Professional</h3>
                    <p class="text-[11px] text-gray-500 mb-4">Untuk departemen menengah</p>

                    <div class="mb-6 flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-gray-900">Rp 2.5jt</span>
                        <span class="text-xs text-gray-500">/bln</span>
                    </div>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2.5 text-xs text-gray-600">
                            <span class="text-[#1E56C9] font-bold">✓</span>
                            Hingga 25 Pengguna
                        </li>
                        <li class="flex items-center gap-2.5 text-xs text-gray-600">
                            <span class="text-[#1E56C9] font-bold">✓</span>
                            Penyimpanan 100GB
                        </li>
                        <li class="flex items-center gap-2.5 text-xs text-gray-600">
                            <span class="text-[#1E56C9] font-bold">✓</span>
                            Alur Persetujuan Khusus
                        </li>
                    </ul>
                </div>

                <a href="/subscriptions?plan=professional" class="w-full block text-center py-2 px-3 border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                    Pilih Pro
                </a>
            </div>
        </div>
    </div>
</section>
