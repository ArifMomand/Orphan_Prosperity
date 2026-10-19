@php
    $navLinks = [
        ['Home', '/', '/'],
        ['Causes', '/causes', 'causes*'],
        ['About Us', '/about', 'about*'],
        ['Aman ID', '/aman-id', 'aman-id*'],
        ['Amin (Volunteer)', '/pioneer', 'pioneer*'],
        ['Contact Us', '/contact', 'contact*'],
    ];
@endphp

<header id="site-header"
        class="site-header sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">

    {{-- Scroll progress bar --}}
    <div id="scroll-progress"
         class="absolute start-0 bottom-0 h-[3px] w-0 bg-gradient-to-r from-blue-500 via-sky-400 to-indigo-500 rtl:bg-gradient-to-l"></div>

    <nav class="nav-inner max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center shrink-0">
            <img src="{{ asset('images/new-logo.jpg') }}" class="site-logo w-auto object-contain" alt="Orphan Prosperity Logo">
        </a>

        <ul class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-700">
            @foreach ($navLinks as [$label, $path, $match])
                @php $active = request()->is($match); @endphp
                <li>
                    <a href="{{ url($path) }}"
                       class="relative py-1 transition-colors hover:text-blue-600
                              {{ $active ? 'text-blue-600' : '' }}
                              after:content-[''] after:absolute after:start-0 after:-bottom-1 after:h-0.5
                              after:bg-gradient-to-r after:from-blue-600 after:to-indigo-500 after:rounded-full
                              after:transition-all after:duration-300
                              {{ $active ? 'after:w-full' : 'after:w-0 hover:after:w-full' }}">
                        {{ __($label) }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="hidden md:flex items-center gap-3 shrink-0">
            @include('partials.lang-switcher')

            <a href="{{ url('/donate') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600
                      hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full
                      shadow-md shadow-blue-600/25 transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 21s-7-4.4-9.4-8.6C.9 9.3 2 5.8 5 5c2-.5 4 .4 5 2.2L12 9l2-1.8c1-1.8 3-2.7 5-2.2 3 .8 4.1 4.3 2.4 7.4C19 16.6 12 21 12 21z"/>
                </svg>
                {{ __('Donate') }}
            </a>
        </div>

        <button id="mobile-menu-btn" type="button" aria-label="Toggle menu" aria-expanded="false"
                class="md:hidden relative w-9 h-9 flex flex-col items-center justify-center gap-1.5 shrink-0">
            <span class="hamburger-line block w-6 h-0.5 bg-gray-800 rounded transition-all duration-300"></span>
            <span class="hamburger-line block w-6 h-0.5 bg-gray-800 rounded transition-all duration-300"></span>
            <span class="hamburger-line block w-6 h-0.5 bg-gray-800 rounded transition-all duration-300"></span>
        </button>
    </nav>

    <div id="mobile-menu"
         class="md:hidden max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-white border-t border-gray-100">
        <ul class="flex flex-col px-6 py-4 gap-1 text-sm font-medium text-gray-700">
            @foreach ($navLinks as [$label, $path, $match])
                <li>
                    <a href="{{ url($path) }}"
                       class="block py-2.5 transition-colors hover:text-blue-600 {{ request()->is($match) ? 'text-blue-600 font-semibold' : '' }}">
                        {{ __($label) }}
                    </a>
                </li>
            @endforeach
            <li class="pt-2">
                <a href="{{ url('/donate') }}"
                   class="block text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold px-5 py-2.5 rounded-full transition">
                    {{ __('Donate') }}
                </a>
            </li>
            <li class="pt-2">@include('partials.lang-switcher', ['mobile' => true])</li>
        </ul>
    </div>
</header>

@once
<style>
    .site-header { transition: transform .35s ease, box-shadow .3s ease, background-color .3s ease; }
    .site-header .nav-inner { transition: padding .3s ease; }
    .site-header .site-logo { height: 3rem; transition: height .3s ease; }
    .site-header.is-scrolled { box-shadow: 0 8px 24px -12px rgba(15, 23, 42, .3); }
    .site-header.is-scrolled .nav-inner { padding-top: .4rem; padding-bottom: .4rem; }
    .site-header.is-scrolled .site-logo { height: 2.4rem; }
    .site-header.is-hidden { transform: translateY(-100%); }
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

        menu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                menu.classList.remove('open');
                menu.style.maxHeight = '0px';
                btn.setAttribute('aria-expanded', 'false');
                lines[0].style.transform = 'none';
                lines[1].style.opacity = '1';
                lines[2].style.transform = 'none';
            });
        });

        // Set to false if you don't want the navbar to hide while scrolling down.
        const HIDE_ON_SCROLL = true;

        const header = document.getElementById('site-header');
        const bar = document.getElementById('scroll-progress');
        let lastY = window.scrollY;
        let ticking = false;

        function onScroll() {
            const y = window.scrollY;
            const max = document.documentElement.scrollHeight - window.innerHeight;

            bar.style.width = (max > 0 ? (y / max) * 100 : 0) + '%';
            header.classList.toggle('is-scrolled', y > 10);

            if (HIDE_ON_SCROLL && !menu.classList.contains('open')) {
                if (y > lastY + 4 && y > 120) {
                    header.classList.add('is-hidden');
                } else if (y < lastY - 4 || y <= 120) {
                    header.classList.remove('is-hidden');
                }
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
@endonce