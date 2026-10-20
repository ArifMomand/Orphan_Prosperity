{{--
    resources/views/causes/index.blade.php
    Causes listing page, available in English, Dari and Pashto.
--}}
<!DOCTYPE html>
<html lang="{{ $htmlLang }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Our Causes') }} | Orphan Prosperity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.i18n-head')
</head>
<body class="bg-white text-gray-800 antialiased">

    {{-- ============ NAVBAR ============ --}}
    @include('partials.navbar')

    {{-- ============ HERO ============ --}}
    {{-- Background: save causes-hero.svg as public/images/causes-hero.svg --}}
    <section class="relative bg-gray-900 min-h-[380px] flex items-end overflow-hidden">
        <img src="{{ asset('images/causes-hero.svg') }}"
             alt=""
             class="absolute inset-0 w-full h-full object-cover object-right rtl:object-left">
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/20 to-transparent rtl:bg-gradient-to-l"></div>

        <div class="relative max-w-7xl mx-auto px-6 pb-16 pt-24 w-full">
            <div class="flex gap-4 mb-6">
                <span class="px-6 py-2.5 rounded-full bg-white/90 text-gray-900 text-sm font-semibold animate-[fadeInUp_0.6s_ease-out_both]">
                    {{ __(':count Active Causes', ['count' => count($causes)]) }}
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight max-w-2xl animate-[fadeInUp_0.7s_ease-out_0.15s_both]
                       bg-gradient-to-r from-white via-sky-200 to-blue-400 bg-clip-text text-transparent rtl:bg-gradient-to-l">
                {{ __('Pick a Cause,') }}<br>{{ __('See It Through') }}
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
            <button type="button" class="filter-btn active px-5 py-2.5 rounded-full border border-gray-200 text-sm font-medium text-gray-700 transition" data-category="all">{{ __('All') }}</button>
            @foreach ($causes->pluck('category')->unique() as $category)
                <button type="button" class="filter-btn px-5 py-2.5 rounded-full border border-gray-200 text-sm font-medium text-gray-700 transition" data-category="{{ $category }}">{{ __($category) }}</button>
            @endforeach
        </div>

        <div class="grid md:grid-cols-3 gap-8" id="causeGrid">
            @foreach ($causes as $cause)
                <div class="cause-card rounded-lg overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col h-full bg-white" data-category="{{ $cause->category }}">
                    
                    {{-- کادر تصویر با پشتیبانی از تصاویر عمودی و افقی --}}
                    <div class="relative w-full h-56 bg-slate-100 flex items-center justify-center overflow-hidden p-2">
                        {{-- تصویر تار در پس‌زمینه برای پر کردن فضاهای خالی --}}
                        <img src="{{ $cause->imageUrl() }}" 
                             alt="" 
                             class="absolute inset-0 w-full h-full object-cover blur-md opacity-30 scale-110 pointer-events-none">
                        
                        {{-- تصویر اصلی (بدون برش) --}}
                        <img src="{{ $cause->imageUrl() }}"
                             alt="{{ $cause->title }}" 
                             class="relative max-h-full max-w-full object-contain rounded drop-shadow-sm z-10 transition-transform duration-300 hover:scale-105">
                    </div>

                    <div class="p-5 flex flex-col flex-grow justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">{{ __($cause->category) }}</span>
                                <span class="text-xs text-gray-400">{{ $cause->location }}</span>
                            </div>
                            <h3 class="font-semibold text-lg text-gray-900 mb-2 line-clamp-1">{{ $cause->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $cause->summary }}</p>
                        </div>

                        <div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $cause->percentFunded() }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mb-4">
                                <span>{{ __('Goal') }}: <span dir="ltr">${{ number_format($cause->goal) }}</span></span>
                                <span>{{ $cause->donors }} {{ __('donors') }}</span>
                            </div>
                            <a href="{{ url('/causes/' . $cause->slug) }}"
                               class="block text-center bg-gray-900 hover:bg-black text-white text-sm font-medium py-2.5 rounded-md transition">
                                {{ __('View Details') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="text-center text-gray-400 text-sm mt-10 hidden" id="emptyState">{{ __('No causes in this category right now.') }}</p>
    </section>

    {{-- ============ FOOTER ============ --}}
    @include('partials.footer', ['gallery' => true])

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