@extends('layouts.auth')

@section('title', 'Login - SIMARSIP')

@section('content')
<div class="w-full max-w-[430px]">
    {{-- Card --}}
    <div class="bg-white rounded-xl border border-gray-200/90 shadow-sm p-7 sm:p-8">
        {{-- Logo Icon --}}
        <div class="w-11 h-11 bg-[#0D2B68] rounded-xl flex items-center justify-center mx-auto mb-3.5 shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <circle cx="12" cy="10" r="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 15c0-1.38 1.12-2.2 2.5-2.2s2.5.82 2.5 2.2"/>
            </svg>
        </div>

        {{-- Brand --}}
        <h1 class="text-xl font-bold text-[#0D2B68] tracking-tight text-center">SIMARSIP</h1>
        <p class="text-[11px] text-gray-500 font-normal text-center mt-0.5">Digital Archive Management System</p>

        {{-- Heading --}}
        <div class="text-center mt-5 mb-5">
            <h2 class="text-[15px] font-bold text-gray-900">Masuk ke Akun Anda</h2>
            <p class="text-[11px] text-gray-400 mt-1">Silakan masukkan kredensial untuk mengakses dasbor.</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="/login" class="space-y-3.5">
            @csrf

            {{-- Email atau Username --}}
            <div>
                <label for="email" class="block text-[11px] font-semibold text-gray-800 mb-1">Email atau Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </div>
                    <input type="text" id="email" name="email" placeholder="Masukkan email atau username" class="w-full pl-9 pr-3.5 py-2 text-xs text-gray-800 bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0D2B68] focus:border-[#0D2B68] placeholder:text-gray-400 placeholder:text-xs" required>
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-[11px] font-semibold text-gray-800 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </div>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" class="w-full pl-9 pr-9 py-2 text-xs text-gray-800 bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0D2B68] focus:border-[#0D2B68] placeholder:text-gray-400 placeholder:text-xs" required>
                    {{-- Toggle Password Visibility --}}
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <svg id="eye-open" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg id="eye-closed" class="w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Remember Me & Forgot Password --}}
            <div class="flex items-center justify-between pt-0.5">
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-gray-300 text-[#0D2B68] focus:ring-[#0D2B68]/20">
                    <span class="text-[11px] text-gray-600">Remember Me</span>
                </label>
                <a href="#" class="text-[11px] text-[#1E56C9] hover:underline font-semibold">Lupa Password?</a>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2.5 bg-[#173A7A] hover:bg-[#0D2B68] text-white text-xs font-semibold rounded-lg transition-colors shadow-sm mt-1">
                Masuk
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                </svg>
            </button>
        </form>

        {{-- Divider --}}
        <div class="my-5 border-t border-gray-100"></div>

        {{-- Register Link --}}
        <p class="text-center text-[11px] text-gray-500">
            Belum punya akun?
            <a href="/register" class="text-[#1E56C9] hover:underline font-semibold ml-1">Daftar sekarang</a>
        </p>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>
@endsection
