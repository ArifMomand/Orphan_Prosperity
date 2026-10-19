{{--
    resources/views/causes/index.blade.php
    Causes listing page — matches home.blade.php's Tailwind styling exactly.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Causes | Orphan Prosperity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-800 antialiased">

    @php
        $navLinks = [
            ['Home', '/', '/'],
            ['Causes', '/causes', 'causes*'],
            ['About Us', '/about', 'about*'],
            ['Aman ID', '/aman-id', 'aman-id*'],
            ['Amin ', '/pioneer', 'pioneer*'],

            ['Contact Us', '/contact', 'contact*'],
        ];
    @endphp

    {{-- ============ NAVBAR ============ --}}
    <header id="site-header"
            class="site-header sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">

        {{-- Scroll progress bar --}}
        <div id="scroll-progress"
             class="absolute left-0 bottom-0 h-[3px] w-0 bg-gradient-to-r from-blue-500 via-sky-400 to-indigo-500"></div>

        <nav class="nav-inner max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center shrink-0">
                <img src="{{ asset('images/new-logo.jpg') }}"
                     class="site-logo w-auto object-contain"
                     alt="Orphan Prosperity Logo">
            </a>

            <ul class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-700">
                @foreach ($navLinks as [$label, $path, $match])
                    @php $active = request()->is($match); @endphp
                    <li>
                        <a href="{{ url($path) }}"
                           class="relative py-1 transition-colors hover:text-blue-600
                                  {{ $active ? 'text-blue-600' : '' }}
                                  after:content-[''] after:absolute after:left-0 after:-bottom-1 after:h-0.5
                                  after:bg-gradient-to-r after:from-blue-600 after:to-indigo-500 after:rounded-full
                                  after:transition-all after:duration-300
                                  {{ $active ? 'after:w-full' : 'after:w-0 hover:after:w-full' }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <a href="{{ url('/donate') }}"
               class="hidden md:inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600
                      hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full
                      shadow-md shadow-blue-600/25 transition-all duration-300 hover:scale-105 hover:shadow-lg shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 21s-7-4.4-9.4-8.6C.9 9.3 2 5.8 5 5c2-.5 4 .4 5 2.2L12 9l2-1.8c1-1.8 3-2.7 5-2.2 3 .8 4.1 4.3 2.4 7.4C19 16.6 12 21 12 21z"/>
                </svg>
                Donate
            </a>

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
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
                <li class="pt-2">
                    <a href="{{ url('/donate') }}"
                       class="block text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold px-5 py-2.5 rounded-full transition">
                        Donate
                    </a>
                </li>
            </ul>
        </div>
    </header>

    @once
    <style>
        /* Navbar scroll behaviour */
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
            // ---- Mobile menu ----
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

            // ---- Scroll behaviour ----
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
                        header.classList.add('is-hidden');      // scrolling down
                    } else if (y < lastY - 4 || y <= 120) {
                        header.classList.remove('is-hidden');   // scrolling up
                    }
                }

                lastY = y;
                ticking = false;
            }

            window.addEventListener('scroll', function () {
                if (!ticking) {
                    requestAnimationFrame(onScroll);
                    ticking = true;
                }
            }, { passive: true });

            onScroll();
        });
    </script>
    @endonce

    {{-- ============ HERO ============ --}}
    {{-- Background: save causes-hero.svg as public/images/causes-hero.svg (vector, stays sharp on any screen) --}}
    <section class="relative bg-gray-900 min-h-[380px] flex items-end overflow-hidden">
        <img src="{{ asset('images/causes-hero.svg') }}"
             alt=""
             class="absolute inset-0 w-full h-full object-cover object-right">
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/20 to-transparent"></div>

        <div class="relative max-w-7xl mx-auto px-6 pb-16 pt-24 w-full">
            <div class="flex gap-4 mb-6">
                <span class="px-6 py-2.5 rounded-full bg-white/90 text-gray-900 text-sm font-semibold animate-[fadeInUp_0.6s_ease-out_both]">
                    {{ count($causes) }} Active Causes
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight max-w-2xl animate-[fadeInUp_0.7s_ease-out_0.15s_both]
                       bg-gradient-to-r from-white via-sky-200 to-blue-400 bg-clip-text text-transparent">
                Pick a Cause,<br>See It Through
            </h1>
        </div>
    </section>

    @once
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .filter-btn.active { background-color: #2563eb; color: #fff; border-color: #2563eb; }
        .cause-card { transition: opacity .3s ease, transform .3s ease; }
        .cause-card.hidden-filtered { display: none; }
    </style>
    @endonce

    {{-- ============ FILTER + GRID ============ --}}
    <section class="max-w-7xl mx-auto px-6 py-16">
        <div class="flex flex-wrap gap-2 mb-10" id="filterRow">
            <button type="button" class="filter-btn active px-5 py-2.5 rounded-full border border-gray-200 text-sm font-medium text-gray-700 transition" data-category="all">All</button>
            @foreach ($causes->pluck('category')->unique() as $category)
                <button type="button" class="filter-btn px-5 py-2.5 rounded-full border border-gray-200 text-sm font-medium text-gray-700 transition" data-category="{{ $category }}">{{ $category }}</button>
            @endforeach
        </div>

        <div class="grid md:grid-cols-3 gap-8" id="causeGrid">
            @foreach ($causes as $cause)
                <div class="cause-card rounded-lg overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition" data-category="{{ $cause->category }}">
                    <img src="{{ $cause->imageUrl() }}"
                         alt="{{ $cause->title }}" class="w-full h-48 object-cover">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">{{ $cause->category }}</span>
                            <span class="text-xs text-gray-400">{{ $cause->location }}</span>
                        </div>
                        <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $cause->title }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ $cause->summary }}</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $cause->percentFunded() }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mb-4">
                            <span>Goal: ${{ number_format($cause->goal) }}</span>
                            <span>{{ $cause->donors }} donors</span>
                        </div>
                        <a href="{{ url('/causes/' . $cause->slug) }}"
                           class="block text-center bg-gray-900 hover:bg-black text-white text-sm font-medium py-2.5 rounded-md transition">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="text-center text-gray-400 text-sm mt-10 hidden" id="emptyState">No causes in this category right now.</p>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-gray-900 text-gray-300 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-10">
            <div>
                <div class="text-white text-xl font-bold mb-3">Orphan<span class="text-blue-500">Prosperity</span></div>
                <p class="text-sm text-gray-400 mb-4">
                    Incident luctus porta amet lectus at ultrices nec sed amet. Sed id egestas.
                </p>
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
                <h4 class="text-white font-semibold mb-3">Useful Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">FAQ</a></li>
                    <li><a href="#" class="hover:text-white">Forums</a></li>
                    <li><a href="#" class="hover:text-white">Reports</a></li>
                    <li><a href="#" class="hover:text-white">Terms of Use</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Gallery</h4>
                <div class="grid grid-cols-3 gap-2">
                    @for ($i = 1; $i <= 6; $i++)
                        <img src="https://picsum.photos/seed/footer-gallery-{{ $i }}/100/100"
                             alt="Gallery thumb {{ $i }}" class="w-full h-14 object-cover rounded">
                    @endfor
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 mt-12 pt-6 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-400">
            <div class="flex gap-3">
                <a href="https://wa.me/000000000000" target="_blank" rel="noopener" aria-label="WhatsApp"
                   class="group w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center
                          transition-all duration-300 hover:bg-green-500 hover:scale-110 hover:-translate-y-1">
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.29-1.39a9.9 9.9 0 0 0 4.75 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.96 6.45 17.5 2 12.04 2zm5.8 14.19c-.24.68-1.41 1.3-1.95 1.35-.5.05-1.02.24-3.42-.71-2.9-1.15-4.77-4.09-4.92-4.28-.14-.19-1.18-1.57-1.18-3 0-1.42.75-2.12 1.02-2.41.27-.29.58-.36.78-.36.19 0 .39 0 .56.01.18.01.42-.07.65.5.24.58.82 2 .89 2.14.07.14.12.31.02.5-.09.19-.14.31-.28.48-.14.16-.29.36-.42.49-.14.14-.28.29-.12.57.16.29.72 1.2 1.55 1.94 1.07.95 1.96 1.25 2.25 1.39.29.14.46.12.63-.07.17-.19.72-.84.92-1.13.19-.29.38-.24.63-.14.26.09 1.66.79 1.94.93.29.14.48.21.55.33.07.13.07.71-.17 1.39z"/>
                    </svg>
                </a>
                <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram"
                   class="group w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center
                          transition-all duration-300 hover:bg-pink-500 hover:scale-110 hover:-translate-y-1">
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41 1.27-.06 1.65-.07 4.85-.07M12 0C8.74 0 8.33.01 7.05.07 5.78.13 4.9.33 4.14.63c-.79.31-1.46.72-2.13 1.38A5.9 5.9 0 0 0 .63 4.14C.33 4.9.13 5.78.07 7.05.01 8.33 0 8.74 0 12s.01 3.67.07 4.95c.06 1.27.26 2.15.56 2.91.31.79.72 1.46 1.38 2.13.66.66 1.34 1.07 2.13 1.38.76.3 1.64.5 2.91.56C8.33 23.99 8.74 24 12 24s3.67-.01 4.95-.07c1.27-.06 2.15-.26 2.91-.56.79-.31 1.46-.72 2.13-1.38.66-.66 1.07-1.34 1.38-2.13.3-.76.5-1.64.56-2.91.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95c-.06-1.27-.26-2.15-.56-2.91a5.9 5.9 0 0 0-1.38-2.13A5.9 5.9 0 0 0 19.86.63C19.1.33 18.22.13 16.95.07 15.67.01 15.26 0 12 0z"/>
                        <path d="M12 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84zm0 10.16A4 4 0 1 1 16 12a4 4 0 0 1-4 4zM18.41 4.15a1.44 1.44 0 1 0 1.44 1.44 1.44 1.44 0 0 0-1.44-1.44z"/>
                    </svg>
                </a>
                <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube"
                   class="group w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center
                          transition-all duration-300 hover:bg-red-600 hover:scale-110 hover:-translate-y-1">
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.5 6.19a3.02 3.02 0 0 0-2.12-2.14C19.51 3.5 12 3.5 12 3.5s-7.51 0-9.38.55A3.02 3.02 0 0 0 .5 6.19 31.6 31.6 0 0 0 0 12a31.6 31.6 0 0 0 .5 5.81 3.02 3.02 0 0 0 2.12 2.14c1.87.55 9.38.55 9.38.55s7.51 0 9.38-.55a3.02 3.02 0 0 0 2.12-2.14A31.6 31.6 0 0 0 24 12a31.6 31.6 0 0 0-.5-5.81zM9.6 15.6V8.4L15.8 12z"/>
                    </svg>
                </a>
                <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook"
                   class="group w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center
                          transition-all duration-300 hover:bg-blue-600 hover:scale-110 hover:-translate-y-1">
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 12.06C22 6.51 17.52 2 12 2S2 6.51 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.51 1.49-3.9 3.77-3.9 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.89h2.78l-.44 2.91h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94z"/>
                    </svg>
                </a>
            </div>
            <div>&copy; {{ date('Y') }} Orphan Prosperity. All rights reserved.</div>
        </div>
    </footer>


    <script>
        const filterButtons = Array.from(document.querySelectorAll('.filter-btn'));
        const cards = Array.from(document.querySelectorAll('.cause-card'));
        const emptyState = document.getElementById('emptyState');

        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const category = btn.dataset.category;
                let visibleCount = 0;

                cards.forEach(card => {
                    const matches = category === 'all' || card.dataset.category === category;
                    card.classList.toggle('hidden-filtered', !matches);
                    if (matches) visibleCount++;
                });

                emptyState.classList.toggle('hidden', visibleCount > 0);
            });
        });
    </script>
</body>
</html>