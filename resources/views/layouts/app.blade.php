<!DOCTYPE html>
<html lang="{{ $htmlLang }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Orphan Prosperity')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.i18n-head')
    @stack('head')
</head>
<body class="bg-white text-gray-800 antialiased">
    @include('partials.navbar')
    @yield('content')
    @include('partials.footer')
    @stack('scripts')
</body>
</html>