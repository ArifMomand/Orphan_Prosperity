<!DOCTYPE html>
<html lang="{{ $htmlLang }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Donate') }} | Orphan Prosperity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.i18n-head')
</head>
<body class="bg-white text-gray-800 antialiased">

@php
    $amanId = $amanId ?? null;
    $cause  = $cause ?? null;
    $need   = $need ?? null;

    // English label (kept for the Donorbox comment your team reads) and the translated label for visitors
    $needLabelEn = null;
    if ($amanId && $need) {
        $needLabelEn = $need === 'cash'
            ? 'direct cash support'
            : (\App\Models\AmanId::NEEDS[$need][0] ?? null);
    }
    $needLabel = $needLabelEn ? ($need === 'cash' ? __('direct cash support') : $needLabelEn) : null;

    // Text added to the Donorbox comment field so the gift can be matched when you record it
    $donorboxComment = null;
    if ($amanId) {
        $donorboxComment = 'Aman ID ' . $amanId->aman_id . ($needLabelEn ? ' - ' . $needLabelEn : '');
    } elseif ($cause) {
        $donorboxComment = 'Cause: ' . $cause->title;
    }

    // Sentences shown above the form when the gift is for a specific Aman ID or cause
    $givingTo = null;
    $commentHint = null;
    if ($amanId) {
        $repl = [
            'id'   => '<span class="font-mono font-semibold" dir="ltr">' . e($amanId->aman_id) . '</span>',
            'name' => '<span class="text-blue-700">' . e($amanId->masked_name) . '</span>',
            'need' => '<strong>' . e($needLabel ?? '') . '</strong>',
        ];
        $givingTo = $needLabel
            ? __('You are giving to Aman ID :id (:name) for :need', $repl)
            : __('You are giving to Aman ID :id (:name)', $repl);
        $commentHint = __('If the comment box in the form is empty, please type :value in it.', [
            'value' => '<span class="font-mono font-semibold" dir="ltr">' . e($amanId->aman_id) . '</span>',
        ]);
    } elseif ($cause) {
        $givingTo = __('You are giving to the cause :title', ['title' => '<strong>' . e($cause->title) . '</strong>']);
        $commentHint = __('If the comment box in the form is empty, please type the cause name in it.');
    }

    // Base link (without amount/frequency; JavaScript adds those when a donor picks a chip)
    $donorboxBase = 'https://donorbox.org/embed/orphan-prosperity?language=en'
        . ($donorboxComment ? '&comment=' . urlencode($donorboxComment) : '');

    // First load: $50 monthly
    $donorboxSrc = $donorboxBase . '&amount=50&default_interval=m';

    // Real numbers from the database
    $stats = [
        ['value' => \App\Models\Cause::where('status', 'approved')->count(),                         'label' => 'Active causes'],
        ['value' => \App\Models\AmanId::where('status', 'verified')->count(),                        'label' => 'Verified Aman IDs'],
        ['value' => \App\Models\CauseDonation::count() + \App\Models\AmanDonation::count(),          'label' => 'Gifts recorded'],
        ['value' => \App\Models\PioneerApplication::where('status', 'verified')->count(),            'label' => 'Verified Amins'],
    ];

    // Texts used by the page script (so the live impact line can be translated)
    $jsText = [
        'monthly' => __('monthly'),
        'oneTime' => __('one-time'),
        'choose'  => __('Choose an amount above.'),
        'custom'  => __('A gift of $:amount, sized by you and put to use where it is needed.'),
        'impacts' => [
            8  => __('School Fee.'),
            15  => __('uniform .'),
            25 => __('Fees, Courses, lunches.'),
            30 => __('Books And Stationery.'),
            70 => __('School Fee, Books, Stationery, Uniforms.'),
            100 => __('School Fee, Books, Stationery, Uniforms, Lunches.'),
        ],
    ];
@endphp

    {{-- ============ NAVBAR ============ --}}
    @include('partials.navbar')

    @once
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes floaty { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        .reveal { opacity: 0; transform: translateY(14px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.in { opacity: 1; transform: translateY(0); }
        .amt-chip.active { background: #2563eb; color: #fff; border-color: #2563eb; box-shadow: 0 8px 20px -8px rgba(37,99,235,.6); }
        .freq-btn.active { background: #fff; color: #1d4ed8; box-shadow: 0 1px 4px rgba(0,0,0,.12); }
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
        details[open] .faq-plus { transform: rotate(45deg); }
    </style>
    @endonce

    {{-- =====================================================
         HERO: headline + DONORBOX FORM (above the fold)
    ====================================================== --}}
    <section class="relative bg-gray-900 overflow-hidden">
        <img src="{{ asset('images/we.jpeg') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-25">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-900/90 to-indigo-950/90"></div>
        <div class="absolute -left-24 top-10 w-72 h-72 rounded-full bg-blue-600/20 blur-3xl" style="animation: floaty 9s ease-in-out infinite;"></div>
        <div class="absolute right-0 bottom-0 w-96 h-96 rounded-full bg-indigo-500/20 blur-3xl" style="animation: floaty 11s ease-in-out infinite;"></div>

        <div class="relative max-w-7xl mx-auto px-5 sm:px-6 py-10 lg:py-16
                    grid lg:grid-cols-2 gap-x-14 gap-y-8 items-start">

            {{-- A) Headline --}}
            <div class="lg:col-start-1 lg:row-start-1 animate-[fadeInUp_0.6s_ease-out_both]">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/15 text-blue-100 text-xs font-semibold tracking-wide px-4 py-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    {{ __('Secure giving · Every gift is tracked') }}
                </span>
                <h1 class="mt-5 text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight">
                    {{ __('Give a child room to grow,') }}
                    <span class="bg-gradient-to-r from-sky-300 to-blue-400 bg-clip-text text-transparent">{{ __('not just get by.') }}</span>
                </h1>
                <p class="mt-4 text-base sm:text-lg text-gray-300 max-w-xl">
                    {{ __('Your donation covers school fees, daily meals and steady care for orphans in Afghanistan. Pick an amount and give in under a minute.') }}
                </p>
                <p class="mt-4 text-base sm:text-lg text-blue-300 max-w-xl">{{ __('7% of your donation goes towards administrative costs') }}</p>
            </div>

            {{-- B) DONATION CARD (Donorbox) --}}
            <div class="lg:col-start-2 lg:row-start-1 lg:row-span-2 animate-[fadeInUp_0.7s_ease-out_0.1s_both]" id="checkout">
                <div class="bg-white rounded-3xl shadow-2xl shadow-black/40 p-4 sm:p-6">

                    {{-- Who the gift is for --}}
                    @if ($amanId)
                        <div class="mb-4 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                            <div class="flex items-start justify-between gap-3">
                                <div>{!! $givingTo !!}</div>
                                <a href="{{ route('aman-id.show', $amanId) }}" class="shrink-0 text-xs font-semibold text-blue-700 hover:underline">{{ __('View') }} {!! $isRtl ? '&larr;' : '&rarr;' !!}</a>
                            </div>
                            <p class="mt-1.5 text-xs text-blue-700/80">{!! $commentHint !!}</p>
                        </div>
                    @elseif ($cause)
                        <div class="mb-4 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                            <div class="flex items-start justify-between gap-3">
                                <div>{!! $givingTo !!}</div>
                                <a href="{{ url('/causes/' . $cause->slug) }}" class="shrink-0 text-xs font-semibold text-blue-700 hover:underline">{{ __('View') }} {!! $isRtl ? '&larr;' : '&rarr;' !!}</a>
                            </div>
                            <p class="mt-1.5 text-xs text-blue-700/80">{{ $commentHint }}</p>
                        </div>
                    @endif

                    <!-- {{-- Frequency --}}
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <h2 class="font-bold text-gray-900">{{ __('Choose your gift') }}</h2>
                        <div class="inline-flex bg-gray-100 rounded-full p-1 text-sm font-semibold">
                            <button type="button" id="freqMonthly" class="freq-btn active px-4 py-1.5 rounded-full text-gray-500 transition">{{ __('Monthly') }}</button>
                            <button type="button" id="freqOnce" class="freq-btn px-4 py-1.5 rounded-full text-gray-500 transition">{{ __('One-time') }}</button>
                        </div>
                    </div> -->

                    <!-- {{-- Amount chips --}}
                    <div class="grid grid-cols-4 gap-2">
                        @foreach ([8, 15, 25, 30, 70, 100] as $amt)
                            <button type="button"
                                    class="amt-chip {{ $amt === 50 ? 'active' : '' }} rounded-xl border border-gray-200 py-3 text-base font-bold text-gray-800 hover:border-blue-400 transition"
                                    data-amount="{{ $amt }}"><span dir="ltr">${{ $amt }}</span></button>
                        @endforeach
                    </div> -->

                    <!-- <div class="mt-2 flex items-center gap-2 rounded-xl border border-dashed border-gray-300 px-4 py-2.5 focus-within:border-blue-500 transition">
                        <span class="text-sm text-gray-500 shrink-0">{{ __('Other amount $') }}</span>
                        <input type="number" min="1" id="customAmount" placeholder="75"
                               class="w-full bg-transparent border-0 focus:outline-none font-bold text-gray-900">
                    </div>

                    {{-- Impact --}}
                    <div class="mt-3 flex items-start gap-3 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-3">
                        <span class="mt-0.5 w-8 h-8 shrink-0 rounded-full bg-blue-600 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.4-9.4-8.6C.9 9.3 2 5.8 5 5c2-.5 4 .4 5 2.2L12 9l2-1.8c1-1.8 3-2.7 5-2.2 3 .8 4.1 4.3 2.4 7.4C19 16.6 12 21 12 21z"/></svg>
                        </span>
                        <div class="text-sm">
                            <div class="font-semibold text-gray-900">
                                <span id="impactAmount" dir="ltr">$50</span> <span id="impactFreq" class="text-gray-500 font-medium">{{ __('monthly') }}</span>
                            </div>
                            <p class="text-gray-600" id="impactLine">{{ __('Provides a full month of meals and basic school supplies for one child.') }}</p>
                        </div>
                    </div> -->

                    {{-- Donorbox --}}
                    <div class="mt-4 rounded-2xl border border-gray-200 overflow-hidden">
<script type="module" src="https://donorbox.org/widgets.js" async></script><dbox-widget campaign="orphans_prosperity" type="donation_form" enable-auto-scroll="true"></dbox-widget>                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            {{ __('Secure payment by Donorbox') }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('We never see your card details') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- C) Why give --}}
            <div class="lg:col-start-1 lg:row-start-2 grid sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3 gap-3 animate-[fadeInUp_0.8s_ease-out_0.2s_both]">
                @foreach ([
                    ['Every gift is recorded', 'Donations are logged against the cause or Aman ID you choose.'],
                    ['Verified beneficiaries', 'Aman IDs are checked by our team before they go live.'],
                    ['Direct delivery', 'Aid is delivered to the family, with confirmation.'],
                ] as [$title, $text])
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-4 backdrop-blur">
                        <div class="text-sm font-semibold text-white">{{ __($title) }}</div>
                        <p class="text-xs text-gray-400 mt-1">{{ __($text) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ REAL NUMBERS ============ --}}
    <section class="max-w-7xl mx-auto px-6 py-14 border-b border-gray-100">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach ($stats as $s)
                <div>
                    <div class="text-3xl sm:text-4xl font-extrabold text-gray-900" data-count="{{ $s['value'] }}">0</div>
                    <div class="text-sm text-gray-500 mt-1">{{ __($s['label']) }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ WHERE YOUR GIFT GOES ============ --}}
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="max-w-xl mb-10 reveal">
            <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ __('Where your gift goes') }}</h2>
            <p class="text-gray-500">{{ __('Your donation funds real, specific care, not an abstract cause.') }}</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach ([
                ['Education', 'School fees, books and supplies so every child can stay in class.', 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25', 'from-blue-500 to-indigo-600'],
                ['Food & daily care', 'Regular meals, warm clothes and the basics families struggle to afford.', 'M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z', 'from-emerald-500 to-teal-600'],
                ['Health & shelter', 'Health checkups, winter supplies and safe shelter for vulnerable children.', 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z', 'from-rose-500 to-pink-600'],
            ] as [$title, $text, $icon, $grad])
                <div class="reveal rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition p-7">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $grad }} text-white flex items-center justify-center mb-5 shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg text-gray-900 mb-1">{{ __($title) }}</h3>
                    <p class="text-sm text-gray-500">{{ __($text) }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ GIVE DIRECTLY ============ --}}
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="reveal">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ __('Want to help a specific child or family?') }}</h2>
                <p class="text-gray-500 mb-6">
                    {{ __('Search for an Aman ID by name or number, or scan the QR code on an Aman ID card, then give directly to that person. Your gift is tagged to them, so it reaches the right family.') }}
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ url('/aman-id') }}#find" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-full transition hover:-translate-y-0.5 hover:shadow-lg">{{ __('Find an Aman ID') }}</a>
                    <a href="{{ url('/causes') }}" class="border border-gray-300 hover:border-gray-900 font-semibold px-6 py-3 rounded-full transition">{{ __('Browse causes') }}</a>
                </div>
            </div>

            <ol class="space-y-4 reveal">
                @foreach ([
                    ['Choose', 'Pick a cause or an Aman ID, or give to where it is needed most.'],
                    ['Give securely', 'Pay by card in the form at the top of this page.'],
                    ['We deliver', 'Our team delivers the aid and records your gift against that cause or person.'],
                ] as $i => [$title, $text])
                    <li class="flex gap-4 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <span class="w-10 h-10 shrink-0 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                        <div>
                            <div class="font-semibold text-gray-900">{{ __($title) }}</div>
                            <p class="text-sm text-gray-500 mt-0.5">{{ __($text) }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ============ FAQ ============ --}}
    <section class="max-w-3xl mx-auto px-6 py-20">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center reveal">{{ __('Questions about giving') }}</h2>

        <div class="space-y-3 reveal">
            @foreach ([
                ['Is my payment secure?', 'Payments are processed by Donorbox over a secure connection. Orphan Prosperity never sees or stores your card details.'],
                ['Can I give every month?', 'Yes. Choose "Monthly" at the top of the form. Choose "One-time" if you only want to give once.'],
                ['Can I choose where my gift goes?', 'Yes. Open a cause or an Aman ID and press Donate, and your gift is tagged to it. If the comment box in the form is empty, type the name or Aman ID shown on the page.'],
                ['How do I know my gift was used?', 'Every donation is recorded by our team against the cause or Aman ID you chose, and the totals are shown on the cause pages.'],
            ] as [$q, $a])
                <details class="group bg-white rounded-2xl border border-gray-200 px-6 py-4">
                    <summary class="flex items-center justify-between gap-4 cursor-pointer font-semibold text-gray-900">
                        {{ __($q) }}
                        <span class="faq-plus shrink-0 text-2xl text-blue-600 leading-none transition-transform">+</span>
                    </summary>
                    <p class="mt-3 text-sm text-gray-500">{{ __($a) }}</p>
                </details>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="#checkout" class="inline-flex bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-8 py-3.5 rounded-full shadow-lg shadow-blue-600/25 transition hover:-translate-y-0.5">
                {{ __('Give now') }} &uarr;
            </a>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    @include('partials.footer', ['give' => true])

    <script>
        // ---------- Donorbox: amount + frequency ----------
        const baseSrc = @json($donorboxBase);
        const t = @json($jsText);
        const frame = document.getElementById('donorboxFrame');
        const chips = Array.from(document.querySelectorAll('.amt-chip'));
        const custom = document.getElementById('customAmount');
        const freqMonthly = document.getElementById('freqMonthly');
        const freqOnce = document.getElementById('freqOnce');
        const impactAmount = document.getElementById('impactAmount');
        const impactFreq = document.getElementById('impactFreq');
        const impactLine = document.getElementById('impactLine');

        let amount = 50;
        let interval = 'm'; // m = monthly, o = one-time
        let timer = null;

        function reloadForm() {
            frame.src = baseSrc + (amount ? '&amount=' + amount : '') + '&default_interval=' + interval;
        }

        function updateImpact() {
            impactAmount.textContent = amount ? '$' + amount : '';
            impactFreq.textContent = interval === 'm' ? t.monthly : t.oneTime;
            impactLine.textContent = t.impacts[amount] || (amount
                ? t.custom.replace(':amount', amount)
                : t.choose);
        }

        chips.forEach(chip => chip.addEventListener('click', () => {
            amount = parseInt(chip.dataset.amount, 10);
            custom.value = '';
            chips.forEach(c => c.classList.toggle('active', c === chip));
            updateImpact();
            reloadForm();
        }));

        custom.addEventListener('input', () => {
            const val = parseInt(custom.value, 10);
            chips.forEach(c => c.classList.remove('active'));
            amount = val > 0 ? val : 0;
            updateImpact();
            clearTimeout(timer);
            timer = setTimeout(reloadForm, 700);   // wait until the donor stops typing
        });

        function setFreq(next) {
            interval = next;
            freqMonthly.classList.toggle('active', next === 'm');
            freqOnce.classList.toggle('active', next === 'o');
            updateImpact();
            reloadForm();
        }
        freqMonthly.addEventListener('click', () => setFreq('m'));
        freqOnce.addEventListener('click', () => setFreq('o'));

        updateImpact();

        // ---------- Count-up + reveal on scroll ----------
        function animateNumber(el, from, to, duration = 900) {
            const start = performance.now();
            function tick(now) {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(from + (to - from) * eased);
                if (progress < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        }
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('in');
                if (entry.target.dataset.count) {
                    animateNumber(entry.target, 0, parseInt(entry.target.dataset.count, 10));
                }
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.3 });
        document.querySelectorAll('.reveal, [data-count]').forEach(el => observer.observe(el));
    </script>

</body>
</html>