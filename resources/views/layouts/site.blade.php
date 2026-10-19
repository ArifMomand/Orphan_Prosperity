<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Orphan Prosperity')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-800 antialiased">

    @php
        $navLinks = [
            ['Home', '/', '/'],
            ['Causes', '/causes', 'causes*'],
            ['About Us', '/about', 'about*'],
            ['Pioneer', '/pioneer', 'pioneer*'],
            ['Contact Us', '/contact', 'contact*'],
        ];
    @endphp

    {{-- ============ NAVBAR ============ --}}
    <header id="site-header" class="site-header sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div id="scroll-progress" class="absolute left-0 bottom-0 h-[3px] w-0 bg-gradient-to-r from-blue-500 via-sky-400 to-indigo-500"></div>

        <nav class="nav-inner max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center shrink-0">
                <img src="{{ asset('images/new-logo.jpg') }}" class="site-logo w-auto object-contain" alt="Orphan Prosperity Logo">
            </a>

            <ul class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-700">
                @foreach ($navLinks as [$label, $path, $match])
                    @php $active = request()->is($match); @endphp
                    <li>
                        <a href="{{ url($path) }}"
                           class="relative py-1 transition-colors hover:text-blue-600 {{ $active ? 'text-blue-600' : '' }}
                                  after:content-[''] after:absolute after:left-0 after:-bottom-1 after:h-0.5
                                  after:bg-gradient-to-r after:from-blue-600 after:to-indigo-500 after:rounded-full
                                  after:transition-all after:duration-300 {{ $active ? 'after:w-full' : 'after:w-0 hover:after:w-full' }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <a href="{{ url('/donate') }}"
               class="hidden md:inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700
                      text-white text-sm font-semibold px-5 py-2.5 rounded-full shadow-md shadow-blue-600/25 transition-all duration-300 hover:scale-105 hover:shadow-lg shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21s-7-4.4-9.4-8.6C.9 9.3 2 5.8 5 5c2-.5 4 .4 5 2.2L12 9l2-1.8c1-1.8 3-2.7 5-2.2 3 .8 4.1 4.3 2.4 7.4C19 16.6 12 21 12 21z"/></svg>
                Donate
            </a>

            <button id="mobile-menu-btn" type="button" aria-label="Toggle menu" aria-expanded="false"
                    class="md:hidden relative w-9 h-9 flex flex-col items-center justify-center gap-1.5 shrink-0">
                <span class="hamburger-line block w-6 h-0.5 bg-gray-800 rounded transition-all duration-300"></span>
                <span class="hamburger-line block w-6 h-0.5 bg-gray-800 rounded transition-all duration-300"></span>
                <span class="hamburger-line block w-6 h-0.5 bg-gray-800 rounded transition-all duration-300"></span>
            </button>
        </nav>

        <div id="mobile-menu" class="md:hidden max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-white border-t border-gray-100">
            <ul class="flex flex-col px-6 py-4 gap-1 text-sm font-medium text-gray-700">
                @foreach ($navLinks as [$label, $path, $match])
                    <li><a href="{{ url($path) }}" class="block py-2.5 transition-colors hover:text-blue-600 {{ request()->is($match) ? 'text-blue-600 font-semibold' : '' }}">{{ $label }}</a></li>
                @endforeach
                <li class="pt-2">
                    <a href="{{ url('/donate') }}" class="block text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold px-5 py-2.5 rounded-full transition">Donate</a>
                </li>
            </ul>
        </div>
    </header>

    <style>
        .site-header { transition: transform .35s ease, box-shadow .3s ease; }
        .site-header .nav-inner { transition: padding .3s ease; }
        .site-header .site-logo { height: 3rem; transition: height .3s ease; }
        .site-header.is-scrolled { box-shadow: 0 8px 24px -12px rgba(15, 23, 42, .3); }
        .site-header.is-scrolled .nav-inner { padding-top: .4rem; padding-bottom: .4rem; }
        .site-header.is-scrolled .site-logo { height: 2.4rem; }
        .site-header.is-hidden { transform: translateY(-100%); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            const lines = btn.querySelectorAll('.hamburger-line');

            btn.addEventListener('click', function () {
                const isOpen = menu.classList.toggle('open');
                btn.setAttribute('aria-expanded', isOpen);
                if (isOpen) {
                    menu.style.maxHeight = menu.scrollHeight + 'px';
                    lines[0].style.transform = 'translateY(8px) rotate(45deg)';
                    lines[1].style.opacity = '0';
                    lines[2].style.transform = 'translateY(-8px) rotate(-45deg)';
                } else {
                    menu.style.maxHeight = '0px';
                    lines[0].style.transform = 'none';
                    lines[1].style.opacity = '1';
                    lines[2].style.transform = 'none';
                }
            });

            const HIDE_ON_SCROLL = true;
            const header = document.getElementById('site-header');
            const bar = document.getElementById('scroll-progress');
            let lastY = window.scrollY, ticking = false;

            function onScroll() {
                const y = window.scrollY;
                const max = document.documentElement.scrollHeight - window.innerHeight;
                bar.style.width = (max > 0 ? (y / max) * 100 : 0) + '%';
                header.classList.toggle('is-scrolled', y > 10);
                if (HIDE_ON_SCROLL && !menu.classList.contains('open')) {
                    if (y > lastY + 4 && y > 120) header.classList.add('is-hidden');
                    else if (y < lastY - 4 || y <= 120) header.classList.remove('is-hidden');
                }
                lastY = y;
                ticking = false;
            }
            window.addEventListener('scroll', function () {
                if (!ticking) { requestAnimationFrame(onScroll); ticking = true; }
            }, { passive: true });
            onScroll();
        });
    </script>

    @yield('content')

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-gray-900 text-gray-300 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-10">
            <div>
                <div class="text-white text-xl font-bold mb-3">Orphan<span class="text-blue-500">Prosperity</span></div>
                <p class="text-sm text-gray-400 mb-4">Incident luctus porta amet lectus at ultrices nec sed amet. Sed id egestas.</p>
                <p class="text-sm text-gray-400">📍 Chelsoton 7th district, Kabul, Afghanistan</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">About Us</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ url('/about') }}" class="hover:text-white">Our Story</a></li>
                    <li><a href="{{ url('/causes') }}" class="hover:text-white">Causes</a></li>
                    <li><a href="{{ url('/pioneer') }}" class="hover:text-white">Volunteers</a></li>
                    <li><a href="{{ url('/contact') }}" class="hover:text-white">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Help Someone</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('people.index') }}" class="hover:text-white">People in Need</a></li>
                    <li><a href="{{ route('aid.index') }}" class="hover:text-white">Direct Aid</a></li>
                    <li><a href="{{ url('/donate') }}" class="hover:text-white">Donate</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Gallery</h4>
                <div class="grid grid-cols-3 gap-2">
                    @for ($i = 1; $i <= 6; $i++)
                        <img src="https://picsum.photos/seed/footer-gallery-{{ $i }}/100/100" alt="Gallery thumb {{ $i }}" class="w-full h-14 object-cover rounded">
                    @endfor
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 mt-12 pt-6 border-t border-gray-800 text-sm text-gray-400 text-center md:text-left">
            &copy; {{ date('Y') }} Orphan Prosperity. All rights reserved.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
