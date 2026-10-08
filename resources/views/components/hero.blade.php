{{-- Hero Section --}}
<section id="beranda" class="pt-24 sm:pt-32 pb-16 sm:pb-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            {{-- Left Content (Col 6) --}}
            <div class="lg:col-span-6 space-y-5">
                <h1 class="text-3xl sm:text-4xl lg:text-[2.6rem] font-bold text-[#0D2B68] leading-[1.25] tracking-tight">
                    Kelola Arsip Lebih Cepat, Aman, dan Terorganisir
                </h1>

                <p class="text-sm sm:text-base text-gray-500 leading-relaxed max-w-lg">
                    SIMARSIP membantu institusi dan perusahaan mengelola dokumen digital, mempercepat pencarian arsip, serta meningkatkan keamanan data dalam satu platform terintegrasi.
                </p>

                <div class="flex flex-wrap items-center gap-3.5 pt-2">
                    <a href="/register" class="inline-flex items-center justify-center px-6 py-2.5 text-xs sm:text-sm font-semibold text-white bg-[#0D2B68] hover:bg-[#091F4B] rounded-md transition-all shadow-sm active:scale-95">
                        Mulai Gratis
                    </a>
                    <a href="#tentang" class="inline-flex items-center justify-center px-6 py-2.5 text-xs sm:text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md transition-all active:scale-95">
                        Lihat Demo
                    </a>
                </div>
            </div>

            {{-- Right Content (Col 6): Digital Archive Management Illustration --}}
            <div class="lg:col-span-6 flex justify-center lg:justify-end">
                <div class="w-full max-w-lg">
                    <img
                        src="{{ asset('images/hero-illustration.png') }}"
                        alt="Digital Archive Management"
                        class="w-full h-auto object-contain"
                        loading="eager"
                    >
                </div>
            </div>
        </div>
    </div>
</section>
