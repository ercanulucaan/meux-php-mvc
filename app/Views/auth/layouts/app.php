<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ $site_title }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Logo / Brand Section -->
        <div class="mb-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-3">
                <div
                    class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-slate-200">
                    M</div>
                <span class="text-2xl font-bold tracking-tight text-slate-900">{{ $site_title }}</span>
            </a>
        </div>

        <!-- Auth Content Card -->
        <div class="max-w-md w-full">
            {!! $content !!}
        </div>

        <!-- Minimal Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm text-slate-400">&copy; {{ date('Y') }} {{ $site_title }}. Tüm hakları saklıdır.</p>
        </div>
    </div>
</body>

</html>