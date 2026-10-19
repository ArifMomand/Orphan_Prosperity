<!DOCTYPE html>
<html lang="{{ $htmlLang }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Volunteer') }} | Orphan Prosperity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.i18n-head')
</head>
<body class="bg-white text-gray-800 antialiased">

@php
    // ---- Real numbers from the database ----
    $verifiedAmins       = \App\Models\PioneerApplication::where('status', 'verified')->count();
    $totalApplications   = \App\Models\PioneerApplication::count();
    $pendingApplications = \App\Models\PioneerApplication::where('status', 'pending')->count();
    $citiesRepresented   = \App\Models\PioneerApplication::where('status', 'verified')
                              ->whereNotNull('city')->where('city', '!=', '')
                              ->distinct()->count('city');

    $numbers = [
        [$verifiedAmins, 'Verified Amins'],
        [$totalApplications, 'Applications received'],
        [$pendingApplications, 'Awaiting verification'],
        [$citiesRepresented, 'Cities represented'],
    ];

    $steps = [
        ['Create your profile', "A short form — name, background, what you're good at, and a username and password for logging in. About three minutes."],
        ['Clear a quick verification', 'We run a standard background check and confirm your details, usually within two days.'],
        ['Pick a mission', 'Browse open opportunities near you and choose ones that fit your schedule.'],
        ['Watch it add up', "Every completed mission shows up in your dashboard, alongside the kids you've helped."],
    ];

    $missions = [
        ['📚', 'Teaching & Tutoring', 'Homework help, reading circles, and basic-skills classes at partner homes.'],
        ['🩺', 'Medical & Wellness', 'Basic checkups, first-aid support, and wellness visits for licensed volunteers.'],
        ['📦', 'Logistics & Delivery', 'Sorting donations, running supply drops, and keeping partner sites stocked.'],
        ['🤝', 'Community Outreach', 'Events, fundraising drives, and connecting local families to our programs.'],
    ];

    $experiences = ['none' => 'No prior experience', 'some' => 'Some experience', 'experienced' => 'Experienced volunteer'];
    $heardFrom   = ['social' => 'Social media', 'friend' => 'Friend or family', 'event' => 'An event', 'search' => 'Search / web', 'other' => 'Other'];
    $availability = ['weekdays' => 'Weekdays', 'weekends' => 'Weekends', 'evenings' => 'Evenings', 'flexible' => 'Flexible'];
    $skills = [
        'logistics' => 'Logistics', 'medical' => 'Medical', 'teaching' => 'Teaching', 'translation' => 'Translation',
        'photography' => 'Photography', 'driving' => 'Driving', 'it_tech' => 'IT / Tech', 'counseling' => 'Counseling',
    ];

    $field    = 'w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition';
    $select   = $field . ' text-gray-500';
    $textarea = $field . ' resize-none';
    $pill     = 'skill-pill px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-700 transition hover:border-blue-300';
@endphp

@if (session('success'))
    <div id="toast" class="fixed top-5 end-5 z-[100] max-w-sm bg-white border border-green-200 rounded-xl shadow-lg p-4 flex items-start gap-3 toast-in">
        <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0 text-sm font-bold">✓</span>
        <div class="flex-1">
            <div class="font-semibold text-gray-900 text-sm">{{ __('Application received') }}</div>
            <p class="text-sm text-gray-500 mt-0.5">{{ session('success') }}</p>
        </div>
        <button onclick="document.getElementById('toast').remove()" class="text-gray-400 hover:text-gray-600 shrink-0">✕</button>
        <div class="absolute bottom-0 start-0 h-0.5 bg-green-500 rounded-full toast-bar"></div>
    </div>
@endif

    {{-- ============ NAVBAR ============ --}}
    @include('partials.navbar')

    {{-- ============ PAGE BANNER ============ --}}
    <section class="relative bg-gray-900 py-24 overflow-hidden">
        <img src="{{ asset('images/we.jpeg') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/50 to-black/30"></div>
        <div class="relative max-w-4xl mx-auto px-6 text-center">
            <div class="text-blue-400 font-semibold text-sm tracking-widest uppercase mb-3 animate-[fadeInUp_0.5s_ease-out_both]">
                {{ __('Become an Amin') }}
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold text-white leading-tight" id="planHeadline">
                {{ __("Show up for a child who's waiting") }}
            </h1>
            <p class="mt-4 text-lg text-gray-200 max-w-xl mx-auto animate-[fadeInUp_0.6s_ease-out_0.2s_both]">
                {{ __('Sign up, get verified, and start volunteering on missions that fit your time and skills — then watch the difference add up, one child at a time.') }}
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3 animate-[fadeInUp_0.6s_ease-out_0.35s_both]">
                @if (auth('pioneer')->check())
                    <a href="{{ route('pioneer.dashboard') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-full transition">
                        {{ __('My Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('pioneer.login') }}"
                       class="border border-white/70 hover:bg-white hover:text-gray-900 text-white font-semibold px-8 py-3 rounded-full transition">
                        {{ __('Login') }}
                    </a>
                    <a href="#apply"
                       class="open-apply bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-full transition">
                        {{ __('Register') }}
                    </a>
                @endif
            </div>
        </div>
    </section>

    @once
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes wordIn { from { opacity: 0; transform: translateY(10px); filter: blur(2px); } to { opacity: 1; transform: translateY(0); filter: blur(0); } }
        @keyframes toastIn { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes toastBar { from { width: 100%; } to { width: 0%; } }
        .toast-in { animation: toastIn .4s ease-out both; }
        .toast-bar { animation: toastBar 6s linear forwards; }
        .word-in { display: inline-block; opacity: 0; animation: wordIn 0.55s ease forwards; }
        .reveal { opacity: 0; transform: translateY(14px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.in { opacity: 1; transform: translateY(0); }
        .step-line { transition: height .5s cubic-bezier(.4,0,.2,1); }
        .step-item .step-num { transition: background-color .4s ease, transform .4s ease; }
        .step-item.in .step-num { background-color: #1d4ed8; transform: scale(1.08); }
        .skill-pill.active { background-color: #2563eb; color: #fff; border-color: #2563eb; }
        #apply { scroll-margin-top: 5rem; }
    </style>
    @endonce

    {{-- ============ HOW IT WORKS ============ --}}
    <section class="bg-gray-50 py-20" id="how-it-works">
        <div class="max-w-2xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-gray-900 mb-3 reveal">{{ __('How it works') }}</h2>
            <p class="text-gray-500 mb-10 max-w-md reveal">{{ __('Four steps between signing up and standing in front of the kids who need you.') }}</p>

            <div class="relative ps-4">
                <div class="absolute start-[19px] top-2 bottom-2 w-0.5 bg-gray-200"></div>
                <div class="absolute start-[19px] top-2 w-0.5 bg-blue-600 step-line" id="stepLine" style="height:0%"></div>

                @foreach ($steps as $i => [$title, $text])
                    <div class="step-item relative flex gap-5 {{ $loop->last ? '' : 'mb-9' }}" data-step="{{ $i + 1 }}">
                        <span class="step-num shrink-0 w-10 h-10 rounded-full bg-gray-300 text-white font-bold flex items-center justify-center relative z-10">{{ $i + 1 }}</span>
                        <div class="pt-1">
                            <h3 class="font-semibold text-gray-900">{{ __($title) }}</h3>
                            <p class="text-gray-500 text-sm mt-1">{{ __($text) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex items-center gap-3 text-sm text-gray-500 reveal">
                <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">✓</span>
                {{ __('Every Amin passes a background check before their first mission — no exceptions.') }}
            </div>

            @guest('pioneer')
                <div class="mt-10 flex flex-wrap items-center gap-3">
                    <a href="#apply"
                       class="open-apply bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-full transition">
                        {{ __('Register as an Amin') }}
                    </a>
                    <a href="{{ route('pioneer.login') }}"
                       class="border border-gray-300 hover:border-blue-400 hover:text-blue-600 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-full transition">
                        {{ __('Login') }}
                    </a>
                </div>
            @endguest
        </div>
    </section>

    {{-- ============ APPLICATION FORM (hidden until Register is clicked) ============ --}}
    <section id="apply" class="hidden bg-white py-20 border-t border-gray-100">
        <div class="max-w-2xl mx-auto px-6">
            <form method="POST" action="{{ url('/pioneer/apply') }}"
                  class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8" id="applyForm">
                @csrf
                <h2 class="font-bold text-2xl text-gray-900 mb-1">{{ __('Apply as an Amin') }}</h2>
                <p class="text-sm text-gray-500 mb-6">{{ __("Fill in your details and choose a username and password. You'll use them to log in later.") }}</p>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4 mb-5">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-4">
                    <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="{{ __('Full name') }}" required class="{{ $field }}">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required class="{{ $field }}">
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="{{ __('Phone (optional)') }}" class="{{ $field }}">
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="{{ __('City / location') }}" required class="{{ $field }}">
                    <input type="text" name="district" value="{{ old('district') }}" placeholder="{{ __('District') }}" required class="{{ $field }}">

                    <div class="grid grid-cols-2 gap-4">
                        <select name="experience" required class="{{ $select }}">
                            <option value="" disabled selected>{{ __('Experience level') }}</option>
                            @foreach ($experiences as $value => $label)
                                <option value="{{ $value }}" @selected(old('experience') === $value)>{{ __($label) }}</option>
                            @endforeach
                        </select>
                        <select name="heard_from" class="{{ $select }}">
                            <option value="" disabled selected>{{ __('How did you hear about us?') }}</option>
                            @foreach ($heardFrom as $value => $label)
                                <option value="{{ $value }}">{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <div class="text-sm text-gray-600 mb-2">{{ __('Your availability') }}</div>
                        <div class="flex flex-wrap gap-2" id="availabilityGroup">
                            @foreach ($availability as $value => $label)
                                <button type="button" class="{{ $pill }}" data-value="{{ $value }}">{{ __($label) }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="availability" id="availabilityInput">
                    </div>

                    <div>
                        <div class="text-sm text-gray-600 mb-2">{{ __('Your skills') }}</div>
                        <div class="flex flex-wrap gap-2" id="skillsGroup">
                            @foreach ($skills as $value => $label)
                                <button type="button" class="{{ $pill }}" data-value="{{ $value }}">{{ __($label) }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="skills" id="skillsInput">
                    </div>

                    <textarea name="motivation" rows="4" placeholder="{{ __('What made you want to volunteer with us?') }}" required class="{{ $textarea }}">{{ old('motivation') }}</textarea>

                    <div class="pt-4 border-t border-gray-100">
                        <div class="text-sm font-semibold text-gray-900 mb-3">{{ __('Your login details') }}</div>
                        <div class="space-y-4">
                            <input type="text" name="username" value="{{ old('username') }}" placeholder="{{ __('Username (letters, numbers, - and _)') }}" required autocomplete="username" class="{{ $field }}">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="password" name="password" placeholder="{{ __('Password (min 8 characters)') }}" required autocomplete="new-password" class="{{ $field }}">
                                <input type="password" name="password_confirmation" placeholder="{{ __('Confirm password') }}" required autocomplete="new-password" class="{{ $field }}">
                            </div>
                        </div>
                    </div>

                    <label class="flex items-start gap-2 text-xs text-gray-500">
                        <input type="checkbox" name="agree_background_check" required class="mt-0.5 accent-blue-600">
                        {{ __('I agree to a background check and to being contacted about volunteer opportunities.') }}
                    </label>
                </div>

                <button type="submit"
                        class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-full transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                    {{ __('Submit Application') }}
                </button>
                <p class="text-sm text-gray-500 text-center mt-4">
                    {{ __('Already registered?') }} <a href="{{ route('pioneer.login') }}" class="text-blue-600 font-medium">{{ __('Log in') }}</a>
                </p>
            </form>
        </div>
    </section>

    {{-- ============ REAL AMIN NUMBERS (from the database) ============ --}}
    <section class="max-w-7xl mx-auto px-6 py-14 border-b border-gray-100">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach ($numbers as [$count, $label])
                <div>
                    <div class="text-3xl font-extrabold text-gray-900" data-count="{{ $count }}">0</div>
                    <div class="text-sm text-gray-500 mt-1">{{ __($label) }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ MISSION TYPES ============ --}}
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="max-w-xl mb-10 reveal">
            <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ __('Where Amins are needed most') }}</h2>
            <p class="text-gray-500">{{ __("Every skill has a place here — pick the kind of work you'd actually enjoy showing up for.") }}</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($missions as [$icon, $title, $text])
                <div class="reveal p-6 rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition">
                    <div class="w-11 h-11 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-4 text-lg">{{ $icon }}</div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ __($title) }}</h3>
                    <p class="text-sm text-gray-500">{{ __($text) }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    @include('partials.footer', ['gallery' => true])

    <script>
        // Animate headline word-by-word
        (function () {
            const el = document.getElementById('planHeadline');
            const words = el.textContent.trim().split(/\s+/);
            el.innerHTML = words.map((w, i) =>
                `<span class="word-in" style="animation-delay:${i * 0.09}s">${w}&nbsp;</span>`
            ).join('');
        })();

        // Toggle pill groups (skills / availability)
        function wirePillGroup(groupId, inputId) {
            const group = document.getElementById(groupId);
            const input = document.getElementById(inputId);
            const selected = new Set();
            group.querySelectorAll('.skill-pill').forEach(pill => {
                pill.addEventListener('click', () => {
                    const val = pill.dataset.value;
                    if (selected.has(val)) {
                        selected.delete(val);
                        pill.classList.remove('active');
                    } else {
                        selected.add(val);
                        pill.classList.add('active');
                    }
                    input.value = Array.from(selected).join(',');
                });
            });
        }
        wirePillGroup('skillsGroup', 'skillsInput');
        wirePillGroup('availabilityGroup', 'availabilityInput');

        // Reveal the application form when Register is clicked (or after a validation error)
        const applySection = document.getElementById('apply');
        function openApply() {
            applySection.classList.remove('hidden');
            applySection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        document.querySelectorAll('.open-apply').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                openApply();
            });
        });
        if (location.hash === '#apply' || @json($errors->any())) {
            openApply();
        }

        // Animate step plan
        const stepItems = Array.from(document.querySelectorAll('.step-item'));
        const stepLine = document.getElementById('stepLine');
        const stepObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('in');
                const idx = stepItems.indexOf(entry.target);
                stepLine.style.height = ((idx + 1) / stepItems.length * 100) + '%';
                stepObserver.unobserve(entry.target);
            });
        }, { threshold: 0.6 });
        stepItems.forEach(item => stepObserver.observe(item));

        // Reveal + count-up on scroll
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
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('in');
                if (entry.target.dataset.count) {
                    animateNumber(entry.target, 0, parseInt(entry.target.dataset.count, 10));
                }
                revealObserver.unobserve(entry.target);
            });
        }, { threshold: 0.3 });
        document.querySelectorAll('.reveal, [data-count]').forEach(el => revealObserver.observe(el));
    </script>
</body>
</html>