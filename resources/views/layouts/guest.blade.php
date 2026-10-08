<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIMARSIP - Sistem Manajemen Arsip Digital. Kelola arsip perusahaan Anda lebih cepat, aman, dan terorganisir dengan platform digital terdepan.">
    <meta name="keywords" content="arsip digital, manajemen dokumen, SIMARSIP, sistem arsip, document management">
    <title>@yield('title', 'SIMARSIP - Sistem Manajemen Arsip Digital')</title>

    {{-- Google Fonts - Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Heroicons via CDN for icons --}}
    <script src="https://unpkg.com/@phosphor-icons/web@2.0.3"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    @stack('scripts')
</body>
</html>
