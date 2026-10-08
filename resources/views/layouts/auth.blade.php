<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMARSIP')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-white relative overflow-hidden flex items-center justify-center px-4 py-8">
    {{-- Background side blur gradients matching Figma --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        {{-- Top-Right soft periwinkle/slate-blue blur --}}
        <div class="absolute -top-20 -right-16 w-[480px] h-[480px] rounded-full"
             style="background: radial-gradient(circle, rgba(206, 214, 232, 0.9) 0%, rgba(218, 224, 240, 0.5) 40%, rgba(255, 255, 255, 0) 70%); filter: blur(35px);">
        </div>

        {{-- Bottom-Left soft sky blue blur --}}
        <div class="absolute -bottom-20 -left-16 w-[480px] h-[480px] rounded-full"
             style="background: radial-gradient(circle, rgba(200, 220, 244, 0.95) 0%, rgba(215, 232, 250, 0.5) 40%, rgba(255, 255, 255, 0) 70%); filter: blur(35px);">
        </div>
    </div>

    {{-- Page Content --}}
    <div class="relative z-10 w-full flex items-center justify-center">
        @yield('content')
    </div>
</body>
</html>
