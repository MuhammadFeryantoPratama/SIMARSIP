{{-- Navbar Component --}}
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 shadow-[0_1px_3px_rgba(0,0,0,0.03)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">
            {{-- Logo --}}
            <a href="/" class="flex items-center">
                <span class="text-xl sm:text-2xl font-black text-[#0D2B68] tracking-tight">SIMARSIP</span>
            </a>

            {{-- Desktop Navigation Links --}}
            <div class="hidden md:flex items-center gap-8 text-sm">
                <a href="#beranda" class="font-semibold text-[#1E56C9] relative py-2 border-b-2 border-[#1E56C9]">
                    Beranda
                </a>
                <a href="#fitur" class="font-medium text-gray-500 hover:text-[#0D2B68] transition-colors py-2 border-b-2 border-transparent">
                    Fitur
                </a>
                <a href="#harga" class="font-medium text-gray-500 hover:text-[#0D2B68] transition-colors py-2 border-b-2 border-transparent">
                    Harga
                </a>
                <a href="#tentang" class="font-medium text-gray-500 hover:text-[#0D2B68] transition-colors py-2 border-b-2 border-transparent">
                    Tentang Kami
                </a>
                <a href="#kontak" class="font-medium text-gray-500 hover:text-[#0D2B68] transition-colors py-2 border-b-2 border-transparent">
                    Kontak
                </a>
            </div>

            {{-- Right Auth Buttons --}}
            <div class="hidden md:flex items-center gap-5">
                <a href="/login" class="text-sm font-semibold text-[#1E56C9] hover:text-[#0D2B68] transition-colors">
                    Log in
                </a>
                <a href="/register" class="inline-flex items-center justify-center text-sm font-semibold text-white bg-[#0D2B68] hover:bg-[#091F4B] px-5 py-2 rounded-md transition-all shadow-sm active:scale-95">
                    Register
                </a>
            </div>

            {{-- Mobile Menu Hamburger --}}
            <button id="mobile-menu-btn" class="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100 transition-colors" onclick="toggleMobileMenu()" aria-label="Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-100 shadow-md px-4 py-3 space-y-2">
        <a href="#beranda" class="block px-3 py-2 text-sm font-semibold text-[#1E56C9] bg-blue-50/60 rounded">Beranda</a>
        <a href="#fitur" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded">Fitur</a>
        <a href="#harga" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded">Harga</a>
        <a href="#tentang" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded">Tentang Kami</a>
        <a href="#kontak" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded">Kontak</a>
        <div class="pt-3 border-t border-gray-100 flex flex-col gap-2">
            <a href="/login" class="w-full text-center py-2 text-sm font-semibold text-[#1E56C9]">Log in</a>
            <a href="/register" class="w-full text-center py-2 text-sm font-semibold text-white bg-[#0D2B68] rounded">Register</a>
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.add('hidden');
        });
    });
</script>
