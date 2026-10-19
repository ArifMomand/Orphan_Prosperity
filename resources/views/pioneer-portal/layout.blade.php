<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Orphan Prosperity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen">

    <header class="bg-white shadow-sm">
        <nav class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/new-logo.jpg') }}" class="h-12 w-auto object-contain" alt="Orphan Prosperity Logo">
            </a>
            <div class="flex items-center gap-3 text-sm font-medium">
                <a href="{{ url('/causes') }}" class="text-gray-700 hover:text-blue-600">Causes</a>
                @auth('pioneer')
                    <form method="POST" action="{{ route('pioneer.logout') }}">
                        @csrf
                        <button class="border border-gray-300 hover:border-red-400 hover:text-red-600 px-4 py-2 rounded-full transition">Log out</button>
                    </form>
                @endauth
            </div>
        </nav>
    </header>

    @if (session('success'))
        <div class="max-w-3xl mx-auto mt-6 px-6">
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
        </div>
    @endif

    @yield('content')
</body>
</html>