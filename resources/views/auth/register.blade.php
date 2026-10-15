@extends('layouts.auth')

@section('title', 'Register - SIMARSIP')

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
        <h2 class="text-[15px] font-bold text-gray-900 text-center mt-5 mb-5">Buat Akun Baru</h2>

        {{-- Form --}}
        <form method="POST" action="/register" class="space-y-3.5">
            @csrf

            {{-- Nama Lengkap --}}
            <div>
                <label for="name" class="block text-[11px] font-semibold text-gray-800 mb-1">Nama Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </div>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap sesuai identitas" class="w-full pl-9 pr-3.5 py-2 text-xs text-gray-800 bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0D2B68] focus:border-[#0D2B68] placeholder:text-gray-400 placeholder:text-xs" required>
                </div>
            </div>

            {{-- Institusi / Perusahaan --}}
            <div>
                <label for="institution" class="block text-[11px] font-semibold text-gray-800 mb-1">Institusi / Perusahaan</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.75a1.5 1.5 0 011.5-1.5h1.5a1.5 1.5 0 011.5 1.5V21"/>
                        </svg>
                    </div>
                    <input type="text" id="institution" name="institution" placeholder="Nama instansi atau organisasi" class="w-full pl-9 pr-3.5 py-2 text-xs text-gray-800 bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0D2B68] focus:border-[#0D2B68] placeholder:text-gray-400 placeholder:text-xs">
                </div>
            </div>

            {{-- Email Kerja --}}
            <div>
                <label for="email" class="block text-[11px] font-semibold text-gray-800 mb-1">Email Kerja</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                    </div>
                    <input type="email" id="email" name="email" placeholder="alamat@instansi.go.id" class="w-full pl-9 pr-3.5 py-2 text-xs text-gray-800 bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0D2B68] focus:border-[#0D2B68] placeholder:text-gray-400 placeholder:text-xs" required>
                </div>
            </div>

            {{-- Password & Konfirmasi Password (Side by Side) --}}
            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label for="password" class="block text-[11px] font-semibold text-gray-800 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" placeholder="••••••••" class="w-full pl-9 pr-2.5 py-2 text-xs text-gray-800 bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0D2B68] focus:border-[#0D2B68] placeholder:text-gray-400 placeholder:text-xs" required>
                    </div>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-[11px] font-semibold text-gray-800 mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                            </svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" class="w-full pl-9 pr-2.5 py-2 text-xs text-gray-800 bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0D2B68] focus:border-[#0D2B68] placeholder:text-gray-400 placeholder:text-xs" required>
                    </div>
                </div>
            </div>

            {{-- Terms Checkbox --}}
            <div class="flex items-start gap-2 pt-0.5">
                <input type="checkbox" id="terms" name="terms" class="mt-0.5 w-3.5 h-3.5 rounded border-gray-300 text-[#0D2B68] focus:ring-[#0D2B68]/20" required>
                <label for="terms" class="text-[10.5px] text-gray-500 leading-snug">
                    Saya menyetujui <a href="#" class="text-[#1E56C9] hover:underline font-medium">Syarat dan Ketentuan</a> serta <a href="#" class="text-[#1E56C9] hover:underline font-medium">Kebijakan Privasi</a> yang berlaku untuk pengelolaan arsip digital.
                </label>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2.5 bg-[#173A7A] hover:bg-[#0D2B68] text-white text-xs font-semibold rounded-lg transition-colors shadow-sm mt-1">
                Daftar Akun
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </button>
        </form>

        {{-- Divider --}}
        <div class="my-5 border-t border-gray-100"></div>

        {{-- Login Link --}}
        <p class="text-center text-[11px] text-gray-500">
            Sudah punya akun?
            <a href="/login" class="text-[#1E56C9] hover:underline font-semibold ml-1">Login di sini</a>
        </p>
    </div>
</div>
@endsection
