<!DOCTYPE html>
<html lang="{{ $htmlLang }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('About Us') }} | Orphan Prosperity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.i18n-head')
</head>
<body class="bg-white text-gray-800 antialiased">

    {{-- ============ NAVBAR ============ --}}
    @include('partials.navbar')

    {{-- ============ HERO ============ --}}
    <section class="relative bg-gray-900 min-h-[420px] flex items-end overflow-hidden">
        <img src="{{ asset('images/inention.jpeg') }}"
             alt="Hero background"
             class="absolute inset-0 w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

        <div class="relative max-w-7xl mx-auto px-6 pb-16 pt-24 w-full">
            <div class="flex gap-4 mb-6">
                <a href="{{ url('/pioneer') }}"
                   class="px-6 py-2.5 rounded-full bg-white/90 text-gray-900 text-sm font-semibold
                          transition-all duration-300 hover:bg-white hover:scale-105 hover:shadow-lg
                          animate-[fadeInUp_0.6s_ease-out_both]">
                    {{ __('Meet the Team') }}
                </a>
                <a href="{{ url('/causes') }}"
                   class="px-6 py-2.5 rounded-full bg-blue-600 text-white text-sm font-semibold
                          transition-all duration-300 hover:bg-blue-700 hover:scale-105 hover:shadow-lg
                          animate-[fadeInUp_0.6s_ease-out_0.1s_both]">
                    {{ __('Our Work') }}
                </a>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight max-w-xl
                       animate-[fadeInUp_0.7s_ease-out_0.2s_both]">
                {{ __('Built by People Who Show Up') }}
            </h1>
        </div>
    </section>

    @once
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @endonce

    {{-- ============ OUR STORY ============ --}}
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <img src="{{ asset('images/we.jpeg') }}" alt="Our story" class="rounded-lg w-full h-80 object-cover">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Our Story') }}</h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    {{ __('Orphan Prosperity started with a small group of volunteers handing out meals in a single neighborhood in Kabul.') }}
                    {{ __('Today we support orphans and vulnerable families with education, meals and care.') }}
                </p>
                <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm font-medium text-gray-700">
                    @foreach (['Founded in 2019', 'Active in 12 communities', '100% volunteer-verified', 'Transparent yearly reports'] as $point)
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 shrink-0 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs">✓</span>
                            {{ __($point) }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============ OUR JOURNEY (TIMELINE) ============ --}}
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-3xl font-bold text-gray-900">{{ __('Our Journey') }}</h2>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            @foreach ([
                ['year' => '2019', 'title' => 'Where It Began', 'text' => 'A handful of volunteers, one neighborhood, and a shared meal program.'],
                ['year' => '2021', 'title' => 'First Partner Homes', 'text' => 'Opened our first two partner care homes and began school sponsorships.'],
                ['year' => '2023', 'title' => 'Going Regional', 'text' => 'Expanded across 12 communities with a full-time volunteer program.'],
                ['year' => '2026', 'title' => 'Where We Are Now', 'text' => 'Hundreds of children supported and a growing team of Amins.'],
            ] as $milestone)
                <div class="rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition p-6">
                    <div class="text-blue-600 font-bold text-2xl mb-2">{{ $milestone['year'] }}</div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ __($milestone['title']) }}</h3>
                    <p class="text-sm text-gray-500">{{ __($milestone['text']) }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ STATS ============ --}}
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach ([
                ['1.2k+', 'Children Supported'],
                ['340+', 'Active Volunteers'],
                ['12', 'Communities Served'],
                ['98%', 'Donations to Programs'],
            ] as [$num, $label])
                <div>
                    <div class="text-3xl font-extrabold text-gray-900" dir="ltr">{{ $num }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ __($label) }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ TEAM ============ --}}
    @php
        // Names are wrapped in __() so you can add Dari/Pashto spellings to the JSON files whenever you like.
        $team = [
            ['name' => 'Sayed Subhan Sadat', 'role' => 'Founder of the Organization', 'img' => 'sabhan.jpg'],
            ['name' => 'Hayatullah Hilal',   'role' => 'Admin Officer',               'img' => 'heyat.png'],
            ['name' => 'Abdul Rahim Habibi', 'role' => 'Head of finance',             'img' => 'habibi.jpg'],
            ['name' => 'Ahmad Walid Lamha',  'role' => 'HR/ Procurement Officer',     'img' => 'walid.jpg'],
            ['name' => 'Ahmad Khalil Muradi','role' => 'Finance Manager',             'img' => 'khalil.jpg'],
            ['name' => 'Nadim Kakar',        'role' => 'Media Manager',               'img' => 'nadem.jpg'],
            ['name' => 'Ahmad Milad Lamha',  'role' => 'Graphic Design Manager',      'img' => 'milad.jpeg'],
        ];

        // Shown if an image file is missing, so the card never looks broken.
        $teamFallback = 'data:image/svg+xml;charset=UTF-8,' . rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 500"><rect width="400" height="500" fill="#eff6ff"/><circle cx="200" cy="190" r="70" fill="#bfdbfe"/><path d="M60 500c0-95 62-150 140-150s140 55 140 150z" fill="#bfdbfe"/></svg>'
        );
    @endphp

    <section class="team-section relative overflow-hidden py-20">
        <div class="team-blob team-blob-1"></div>
        <div class="team-blob team-blob-2"></div>

        <div class="relative max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">{{ __('Meet the Team') }}</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ __('The people who show up, every single day.') }}</p>
                </div>
                <a href="{{ url('/pioneer') }}"
                   class="border border-gray-300 hover:border-gray-900 text-sm font-medium px-5 py-2.5 rounded-full transition">
                    {{ __('Join the Team') }}
                </a>
            </div>

            <div class="team-grid">
                @foreach ($team as $i => $member)
                    <div class="team-item" style="--i: {{ $i }};">
                        <article class="team-card {{ $i === 0 ? 'is-featured' : '' }}">
                            <div class="team-inner">

                                @if ($i === 0)
                                    <span class="team-badge">★ {{ __('Founder') }}</span>
                                @endif

                                <img src="{{ asset('images/' . $member['img']) }}"
                                     onerror="this.onerror=null;this.src='{{ $teamFallback }}';"
                                     alt="{{ $member['name'] }}"
                                     class="team-img">

                                <div class="team-shade"></div>
                                <div class="team-shine"></div>

                                <div class="team-info">
                                    <h3 class="text-white font-semibold text-sm md:text-base leading-snug">{{ __($member['name']) }}</h3>
                                    <p class="text-blue-100 text-xs md:text-sm mt-0.5">{{ __($member['role']) }}</p>
                                    <span class="team-line"></span>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @once
    <style>
        @property --team-angle { syntax: '<angle>'; initial-value: 0deg; inherits: false; }

        .team-blob { position: absolute; border-radius: 9999px; filter: blur(64px); opacity: .45; pointer-events: none; animation: teamFloat 12s ease-in-out infinite; }
        .team-blob-1 { top: -4rem; left: -5rem; width: 20rem; height: 20rem; background: #bfdbfe; }
        .team-blob-2 { bottom: -5rem; right: -4rem; width: 22rem; height: 22rem; background: #bae6fd; animation-delay: -6s; }
        @keyframes teamFloat { 0%, 100% { transform: translate(0, 0) scale(1); } 50% { transform: translate(24px, -28px) scale(1.12); } }

        .team-grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 1.25rem; }
        .team-item { width: calc(50% - .625rem); opacity: 0; }
        @media (min-width: 768px)  { .team-grid { gap: 2rem; } .team-item { width: calc((100% - 4rem) / 3); } }
        @media (min-width: 1024px) { .team-item { width: calc((100% - 6rem) / 4); } }

        .team-item.in { opacity: 1; animation: teamItemIn .8s cubic-bezier(.22, 1, .36, 1) calc(var(--i, 0) * 90ms) backwards; }
        @keyframes teamItemIn { from { opacity: 0; transform: translateY(40px) scale(.95); } to { opacity: 1; transform: none; } }

        .team-card { position: relative; border-radius: 1rem; transform: perspective(900px) rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg)) translateY(var(--lift, 0px)); transition: transform .25s ease-out, box-shadow .4s ease; box-shadow: 0 6px 18px -8px rgba(15, 23, 42, .25); will-change: transform; }
        .team-card:hover { --lift: -8px; box-shadow: 0 28px 50px -18px rgba(37, 99, 235, .45); }

        .team-card::before { content: ''; position: absolute; inset: -3px; border-radius: calc(1rem + 3px); background: conic-gradient(from var(--team-angle), #2563eb, #38bdf8, #818cf8, #2563eb); opacity: 0; transition: opacity .5s ease; animation: teamSpin 4s linear infinite; }
        .team-card:hover::before, .team-card.is-featured::before { opacity: 1; }
        @keyframes teamSpin { to { --team-angle: 360deg; } }

        .team-inner { position: relative; z-index: 1; overflow: hidden; border-radius: 1rem; aspect-ratio: 4 / 5; background: #eff6ff; }

        .team-img { width: 100%; height: 100%; object-fit: cover; object-position: top; filter: grayscale(.3) saturate(.9); transform: scale(1.04); transition: transform .6s cubic-bezier(.22, 1, .36, 1), filter .6s ease; }
        .team-card:hover .team-img { filter: grayscale(0) saturate(1.15); transform: scale(1.12) translate(var(--tx, 0px), var(--ty, 0px)); }
        .team-item.in .team-img { animation: teamImgIn 1.1s cubic-bezier(.22, 1, .36, 1) calc(var(--i, 0) * 90ms + 150ms) backwards; }
        @keyframes teamImgIn { from { clip-path: inset(0 0 100% 0); transform: scale(1.3); } to { clip-path: inset(0 0 0 0); transform: scale(1.04); } }

        .team-shade { position: absolute; inset: 0; pointer-events: none; background: linear-gradient(to top, rgba(15, 23, 42, .88) 0%, rgba(15, 23, 42, .35) 38%, transparent 62%); }
        .team-shine { position: absolute; inset: 0; pointer-events: none; opacity: 0; transition: opacity .3s ease; background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(255, 255, 255, .30), transparent 48%); }
        .team-card:hover .team-shine { opacity: 1; }

        .team-info { position: absolute; left: 0; right: 0; bottom: 0; padding: 1rem; transform: translateY(6px); transition: transform .5s cubic-bezier(.22, 1, .36, 1); }
        .team-card:hover .team-info { transform: translateY(-2px); }
        .team-line { display: block; height: 2px; width: 2rem; margin-top: .6rem; border-radius: 9999px; background: linear-gradient(to right, #60a5fa, #7dd3fc); transition: width .6s cubic-bezier(.22, 1, .36, 1); }
        .team-card:hover .team-line { width: 100%; }

        .team-badge { position: absolute; top: .75rem; inset-inline-start: .75rem; z-index: 2; padding: .25rem .65rem; border-radius: 9999px; font-size: .7rem; font-weight: 600; letter-spacing: .03em; color: #fff; background: rgba(255, 255, 255, .18); border: 1px solid rgba(255, 255, 255, .35); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); }

        @media (hover: none) { .team-img { filter: none; } }

        @media (prefers-reduced-motion: reduce) {
            .team-item { opacity: 1; }
            .team-item.in, .team-item.in .team-img, .team-card::before, .team-blob { animation: none; }
            .team-card, .team-card:hover { transform: none; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const items = document.querySelectorAll('.team-item');

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.15 });
                items.forEach(function (item) { observer.observe(item); });
            } else {
                items.forEach(function (item) { item.classList.add('in'); });
            }

            const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
            const reduce   = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (!canHover || reduce) return;

            document.querySelectorAll('.team-card').forEach(function (card) {
                card.addEventListener('pointermove', function (e) {
                    const r = card.getBoundingClientRect();
                    const x = (e.clientX - r.left) / r.width;
                    const y = (e.clientY - r.top) / r.height;

                    card.style.setProperty('--ry', ((x - .5) * 14) + 'deg');
                    card.style.setProperty('--rx', ((.5 - y) * 14) + 'deg');
                    card.style.setProperty('--mx', (x * 100) + '%');
                    card.style.setProperty('--my', (y * 100) + '%');
                    card.style.setProperty('--tx', ((.5 - x) * 14) + 'px');
                    card.style.setProperty('--ty', ((.5 - y) * 14) + 'px');
                });

                card.addEventListener('pointerleave', function () {
                    card.style.setProperty('--rx', '0deg');
                    card.style.setProperty('--ry', '0deg');
                    card.style.setProperty('--tx', '0px');
                    card.style.setProperty('--ty', '0px');
                });
            });
        });
    </script>
    @endonce

    {{-- ============ FOOTER ============ --}}
    @include('partials.footer', ['gallery' => true])

</body>
</html>