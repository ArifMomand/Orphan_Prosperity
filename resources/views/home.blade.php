<!DOCTYPE html>
<html lang="{{ $htmlLang }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Orphan Prosperity — Give Hope, Save Lives') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.i18n-head')
</head>
<body class="bg-white text-gray-800 antialiased">

    {{-- ============ NAVBAR ============ --}}
    @include('partials.navbar')


{{-- ================================================================
     HERO
================================================================ --}}
<section class="relative bg-gray-900 overflow-hidden hero-section">

    <img src="{{ asset('images/bge.png') }}"
         onerror="this.onerror=null;this.src='{{ asset('images/home-hero.svg') }}';"
         alt="Hero background"
         class="absolute inset-0 w-full h-full object-cover object-center">

    <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/45 to-black/25 rtl:bg-gradient-to-l"></div>

    <div class="relative max-w-[1600px] mx-auto px-6 md:px-14 hero-content">

        {{-- TEXT --}}
        <div class="hero-text">

            <div class="flex gap-4 mb-6">
                <a href="{{ url('/donate') }}"
                   class="px-6 py-2.5 rounded-full bg-white/90 text-gray-900 text-sm font-semibold
                          transition-all duration-300 hover:bg-white hover:scale-105 hover:shadow-lg
                          animate-[fadeInUp_0.6s_ease-out_both]">
                    {{ __('Give Hope') }}
                </a>

                <a href="{{ url('/causes') }}"
                   class="px-6 py-2.5 rounded-full bg-blue-600 text-white text-sm font-semibold
                          transition-all duration-300 hover:bg-blue-700 hover:scale-105 hover:shadow-lg
                          animate-[fadeInUp_0.6s_ease-out_0.1s_both]">
                    {{ __('Build Futures') }}
                </a>
            </div>

            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight min-h-[5.5rem] md:min-h-[7.5rem]">
                <span id="typewriter-text"
                      class="bg-gradient-to-r from-white via-sky-200 to-blue-400 bg-clip-text text-transparent rtl:bg-gradient-to-l"></span>
                <span class="typewriter-cursor"></span>
            </h1>

            <p class="mt-5 text-lg text-gray-200 max-w-lg hero-subtitle">
                {{ __('Providing education, love, and opportunities for a brighter future in Afghanistan.') }}
            </p>
        </div>

        {{-- VIDEO CARD --}}
        <div class="hero-video-column">
            <div class="hero-video-glow"></div>

            <button id="openVideoBtn" type="button" class="hero-video-card" aria-label="{{ __('Watch our story') }}">
                <div class="hero-video-frame">

                    <video id="introVideoPreview" class="hero-preview-video"
                           poster="{{ asset('images/hero.jpeg') }}" muted playsinline preload="metadata">
                        <source src="{{ asset('videos/intro.mp4') }}" type="video/mp4">
                    </video>

                    <div class="hero-video-overlay"></div>

                    <div class="hero-video-top">
                        <span class="hero-video-live-dot"></span>
                        <span>{{ __('Our Story') }}</span>
                    </div>

                    <div class="hero-play-container">
                        <span class="hero-play-button"><span class="hero-play-icon">▶</span></span>
                    </div>

                    <div class="hero-video-bottom">
                        <div>
                            <p class="hero-video-title">{{ __('Watch our story') }}</p>
                            <p class="hero-video-subtitle">{{ __('See the difference we make') }}</p>
                        </div>
                        <span class="hero-video-arrow">↗</span>
                    </div>
                </div>
            </button>
        </div>
    </div>

    {{-- VIDEO MODAL --}}
    <div id="videoModal" class="video-modal" aria-hidden="true">
        <div id="videoModalBackdrop" class="video-modal-backdrop"></div>

        <div class="video-modal-content">
            <button id="closeVideoBtn" type="button" class="video-close-button" aria-label="{{ __('Close video') }}">
                <span></span><span></span>
            </button>

            <div class="video-modal-frame">
                <video id="introVideo" class="video-modal-player" controls playsinline preload="metadata"
                       poster="{{ asset('images/hero.jpeg') }}">
                    <source src="{{ asset('videos/intro.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</section>

@once
<style>
    /* ---------- HERO ---------- */
    .hero-section { min-height: calc(100svh - 80px); display: flex; align-items: center; }
    .hero-content { width: 100%; min-height: calc(100svh - 80px); display: grid; grid-template-columns: minmax(0, 1fr) 280px; gap: 50px; align-items: center; padding-top: 35px; padding-bottom: 35px; }
    .hero-text { min-width: 0; }
    .hero-video-column { position: relative; width: 250px; height: 445px; display: flex; justify-content: center; align-items: center; justify-self: end; }
    .hero-video-glow { position: absolute; width: 75%; height: 75%; left: 50%; top: 50%; transform: translate(-50%, -50%); background: radial-gradient(circle, rgba(37,99,235,.35), rgba(14,165,233,.12) 45%, transparent 75%); filter: blur(35px); pointer-events: none; }
    .hero-video-card { position: relative; display: block; width: 250px; height: 445px; padding: 0; border: 0; background: transparent; cursor: pointer; border-radius: 22px; z-index: 2; text-align: start; animation: fadeInUp .8s ease-out .3s both; }
    .hero-video-frame { position: relative; width: 100%; height: 100%; overflow: hidden; border-radius: 22px; background: #020617; border: 1px solid rgba(255,255,255,.25); box-shadow: 0 20px 50px rgba(0,0,0,.45), 0 0 0 1px rgba(255,255,255,.04); transition: transform .4s ease, box-shadow .4s ease; }
    .hero-video-card:hover .hero-video-frame { transform: translateY(-5px) scale(1.015); box-shadow: 0 30px 65px rgba(0,0,0,.55), 0 0 30px rgba(37,99,235,.22); }
    .hero-preview-video { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; }
    .hero-video-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,.42), transparent 35%, transparent 55%, rgba(0,0,0,.78)); pointer-events: none; }
    .hero-video-top { position: absolute; top: 13px; inset-inline-start: 13px; display: flex; align-items: center; gap: 7px; padding: 6px 10px; border-radius: 999px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.18); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: #fff; font-size: 9px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .hero-video-live-dot { width: 7px; height: 7px; border-radius: 50%; background: #38bdf8; box-shadow: 0 0 9px rgba(56,189,248,.9); }
    .hero-play-container { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); }
    .hero-play-button { width: 62px; height: 62px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(37,99,235,.94); border: 2px solid rgba(255,255,255,.85); box-shadow: 0 10px 30px rgba(0,0,0,.35), 0 0 0 9px rgba(37,99,235,.12); transition: transform .3s ease, box-shadow .3s ease; }
    .hero-video-card:hover .hero-play-button { transform: scale(1.12); box-shadow: 0 12px 35px rgba(0,0,0,.4), 0 0 0 14px rgba(37,99,235,.13); }
    .hero-play-icon { color: #fff; font-size: 21px; margin-left: 3px; }
    .hero-video-bottom { position: absolute; left: 14px; right: 14px; bottom: 14px; display: flex; align-items: flex-end; justify-content: space-between; color: #fff; }
    .hero-video-title { margin: 0; font-size: 12px; font-weight: 700; }
    .hero-video-subtitle { margin: 2px 0 0; color: rgba(255,255,255,.72); font-size: 8px; }
    .hero-video-arrow { width: 29px; height: 29px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2); font-size: 13px; transition: transform .3s ease; }
    .hero-video-card:hover .hero-video-arrow { transform: rotate(8deg) scale(1.08); }

    /* ---------- HERO VIDEO MODAL ---------- */
    .video-modal { position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 15px; visibility: hidden; opacity: 0; pointer-events: none; transition: opacity .3s ease, visibility .3s ease; }
    .video-modal.active { visibility: visible; opacity: 1; pointer-events: auto; }
    .video-modal-backdrop { position: absolute; inset: 0; background: rgba(2,6,23,.88); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
    .video-modal-content { position: relative; z-index: 2; width: min(90vw, 430px); max-height: 94svh; transform: scale(.88) translateY(20px); opacity: 0; transition: transform .4s cubic-bezier(.22,1,.36,1), opacity .3s ease; }
    .video-modal.active .video-modal-content { transform: scale(1) translateY(0); opacity: 1; }
    .video-modal-frame { width: 100%; max-height: 92svh; overflow: hidden; border-radius: 18px; background: #000; border: 1px solid rgba(255,255,255,.2); box-shadow: 0 30px 100px rgba(0,0,0,.7); }
    .video-modal-player { display: block; width: 100%; height: auto; max-height: 90svh; object-fit: contain; background: #000; }
    .video-close-button { position: absolute; z-index: 10; top: -12px; inset-inline-end: -12px; width: 40px; height: 40px; border-radius: 50%; border: 1px solid rgba(255,255,255,.25); background: rgba(15,23,42,.96); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: transform .25s ease, background .25s ease; }
    .video-close-button:hover { transform: rotate(90deg); background: #2563eb; }
    .video-close-button span { position: absolute; width: 16px; height: 2px; background: #fff; border-radius: 2px; }
    .video-close-button span:first-child { transform: rotate(45deg); }
    .video-close-button span:last-child { transform: rotate(-45deg); }

    /* ---------- ANIMATIONS / TYPEWRITER ---------- */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    .typewriter-cursor { display: inline-block; width: 3px; height: 1em; margin-inline-start: 3px; background: #bae6fd; vertical-align: -0.1em; animation: blinkCursor .8s step-end infinite; }
    @keyframes blinkCursor { 50% { opacity: 0; } }
    .hero-subtitle { opacity: 0; animation: fadeInUp .6s ease-out .4s forwards; }

    /* ---------- TABLET ---------- */
    @media (max-width: 1024px) and (min-width: 768px) {
        .hero-content { grid-template-columns: minmax(0, 1fr) 220px; gap: 30px; }
        .hero-video-column, .hero-video-card { width: 210px; height: 374px; }
    }

    /* ---------- MOBILE ---------- */
    @media (max-width: 767px) {
        .hero-section { min-height: 100svh; display: flex; align-items: center; }
        .hero-content { min-height: auto; width: 100%; display: flex; flex-direction: column; align-items: center; gap: clamp(12px, 3svh, 22px); padding-top: clamp(16px, 4svh, 35px); padding-bottom: clamp(16px, 4svh, 28px); }
        .hero-text { width: 100%; text-align: center; }
        .hero-text .flex { justify-content: center; }
        .hero-text h1 { text-align: start; font-size: clamp(1.5rem, 5.5svh, 3rem); }
        .hero-text .hero-subtitle { text-align: start; margin-left: auto; margin-right: auto; font-size: clamp(0.85rem, 2.2svh, 1.125rem); }
        .hero-video-column { width: clamp(120px, 34svh, 180px); height: clamp(213px, 60svh, 320px); flex-shrink: 0; margin-top: 0; justify-self: center; }
        .hero-video-card { width: 100%; height: 100%; border-radius: 18px; }
        .hero-video-frame { border-radius: 18px; }
        .hero-play-button { width: clamp(42px, 11svh, 56px); height: clamp(42px, 11svh, 56px); }
        .hero-play-icon { font-size: clamp(14px, 3.2svh, 19px); }
        .hero-video-top { top: 10px; inset-inline-start: 10px; padding: 5px 8px; font-size: 7px; }
        .hero-video-bottom { left: 10px; right: 10px; bottom: 10px; }
        .hero-video-title { font-size: 10px; }
        .hero-video-subtitle { font-size: 7px; }
        .hero-video-arrow { width: 25px; height: 25px; font-size: 11px; }
        .video-modal { padding: 10px; }
        .video-modal-content { width: min(88vw, 360px); max-height: 94svh; }
        .video-modal-frame { max-height: 92svh; border-radius: 15px; }
        .video-modal-player { max-height: 90svh; }
        .video-close-button { width: 36px; height: 36px; top: -8px; inset-inline-end: -5px; }
    }

    /* ---------- VERY SMALL PHONES ---------- */
    @media (max-width: 380px) {
        .hero-content { gap: 18px; padding-top: 25px; }
        .hero-video-column, .hero-video-card { width: 145px; height: 258px; }
        .hero-play-button { width: 46px; height: 46px; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ---------- hero video ---------- */
        const openVideoBtn = document.getElementById('openVideoBtn');
        const closeVideoBtn = document.getElementById('closeVideoBtn');
        const videoModal = document.getElementById('videoModal');
        const videoModalBackdrop = document.getElementById('videoModalBackdrop');
        const introVideo = document.getElementById('introVideo');
        const previewVideo = document.getElementById('introVideoPreview');

        function openVideo() {
            videoModal.classList.add('active');
            videoModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            setTimeout(function () {
                introVideo.currentTime = 0;
                introVideo.play().catch(function () {});
            }, 300);
        }

        function closeVideo() {
            introVideo.pause();
            introVideo.currentTime = 0;
            videoModal.classList.remove('active');
            videoModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        if (openVideoBtn) openVideoBtn.addEventListener('click', openVideo);
        if (closeVideoBtn) closeVideoBtn.addEventListener('click', closeVideo);
        if (videoModalBackdrop) videoModalBackdrop.addEventListener('click', closeVideo);

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && videoModal.classList.contains('active')) closeVideo();
        });

        if (previewVideo) previewVideo.play().catch(function () {});

        /* ---------- typewriter ---------- */
        const el = document.getElementById('typewriter-text');
        if (!el) return;

        const lines = @json([__('Empowering Orphans'), __('Transforming Lives')]);

        const typeSpeed = 95;
        const deleteSpeed = 55;
        const holdAfterType = 2400;
        const holdAfterDelete = 700;

        let lineIndex = 0;
        let charIndex = 0;
        let deleting = false;

        function render() {
            el.innerHTML =
                lines.slice(0, lineIndex).map(function (line) { return line + '<br>'; }).join('') +
                lines[lineIndex].slice(0, charIndex);
        }

        function tick() {
            if (!deleting) {
                charIndex++;
                render();

                if (charIndex === lines[lineIndex].length) {
                    if (lineIndex === lines.length - 1) {
                        deleting = true;
                        setTimeout(tick, holdAfterType);
                        return;
                    }
                    lineIndex++;
                    charIndex = 0;
                    setTimeout(tick, typeSpeed);
                    return;
                }
                setTimeout(tick, typeSpeed);
            } else {
                if (charIndex > 0) {
                    charIndex--;
                    render();
                    setTimeout(tick, deleteSpeed);
                    return;
                }
                if (lineIndex > 0) {
                    lineIndex--;
                    charIndex = lines[lineIndex].length;
                    render();
                    setTimeout(tick, deleteSpeed);
                    return;
                }
                deleting = false;
                lineIndex = 0;
                charIndex = 0;
                setTimeout(tick, holdAfterDelete);
            }
        }

        tick();
    });
</script>
@endonce


    {{-- ============ ICON FEATURES ROW ============ --}}
    @php
        // Replace these short sentences with your own whenever you like.
        $features = [
            [
                'title' => 'Education',
                'text' => 'School fees, books and supplies so every child can stay in class.',
                'svg' => '<path d="M12 3 1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4.09L12 21l7-3.73v-4.09L12 17l-7-3.82z"/>',
            ],
            [
                'title' => 'Clean Water',
                'text' => 'Clean water access for vulnerable communities.',
                'svg' => '<path d="M12 2c-4 5.2-6 8.6-6 11.2C6 17.4 8.8 20 12 20s6-2.6 6-6.8C18 10.6 16 7.2 12 2zm0 15.5a4.3 4.3 0 0 1-4.3-4.3c0-.4.34-.74.75-.74s.75.34.75.74A2.8 2.8 0 0 0 12 16a.75.75 0 0 1 0 1.5z"/>',
            ],
            [
                'title' => 'Health Care',
                'text' => 'Basic healthcare and checkups for vulnerable families.',
                'svg' => '<path d="M12 21s-6.7-4.35-9.3-8.1C.8 10.1 1.4 6.6 4.3 5.1c2.2-1.15 4.6-.4 5.9 1.4l1.8 2.5 1.8-2.5c1.3-1.8 3.7-2.55 5.9-1.4 2.9 1.5 3.5 5 1.6 7.8C18.7 16.65 12 21 12 21z"/>',
            ],
            [
                'title' => 'Local Communities',
                'text' => 'Working with local families and leaders to build lasting change.',
                'svg' => '<path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>',
            ],
        ];
    @endphp

    <section class="bg-gray-50 py-14">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8">
            @foreach ($features as $i => $item)
                <div class="flex items-start gap-3 group">
                    <div class="w-12 h-12 shrink-0 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center
                                transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white group-hover:rotate-6 group-hover:scale-110"
                         style="animation-delay: {{ $i * 0.12 }}s;">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">{!! $item['svg'] !!}</svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ __($item['title']) }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ __($item['text']) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>


    {{-- ================================================================
         CAMPAIGNS (admin: image + YouTube link, plays inside the site)
    ================================================================ --}}
    @php
        $playableCampaigns = isset($campaigns)
            ? $campaigns->filter(fn ($c) => $c->youtubeId())->values()
            : collect();
    @endphp

    @if ($playableCampaigns->isNotEmpty())
    <section id="campaigns" class="campaign-section relative overflow-hidden py-16 sm:py-20">

        <div class="campaign-glow campaign-glow-1"></div>
        <div class="campaign-glow campaign-glow-2"></div>

        <div class="relative max-w-7xl mx-auto px-6">

            <div class="flex items-end justify-between gap-6 mb-8 sm:mb-10">
                <div>
                    <span class="campaign-pill">
                        <span class="campaign-pill-dot"></span>
                        {{ __('Campaigns') }}
                    </span>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-white">{{ __('See Our Campaigns in Action') }}</h2>
                    <p class="mt-2 text-sm sm:text-base text-slate-300 max-w-xl">
                        {{ __('Tap any picture to watch the story behind it, right here on the page.') }}
                    </p>
                </div>

                <div class="hidden sm:flex items-center gap-2 shrink-0">
                    <button type="button" id="campaignPrev" class="campaign-arrow" aria-label="{{ __('Previous campaigns') }}">
                        <svg class="w-5 h-5 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <button type="button" id="campaignNext" class="campaign-arrow" aria-label="{{ __('Next campaigns') }}">
                        <svg class="w-5 h-5 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>
            </div>

            <div id="campaignTrack" class="campaign-track">
                @foreach ($playableCampaigns as $i => $campaign)
                    <button type="button"
                            class="campaign-card"
                            style="--i: {{ min($i, 8) }};"
                            data-video="{{ $campaign->youtubeId() }}"
                            data-short="{{ $campaign->isShort() ? '1' : '0' }}"
                            data-title="{{ $campaign->title }}"
                            aria-label="{{ __('Watch video:') }} {{ $campaign->title ?: __('Campaign video') }}">

                        <img src="{{ $campaign->imageUrl() }}"
                             alt="{{ $campaign->title ?: __('Campaign') }}"
                             loading="lazy" decoding="async" class="campaign-img">

                        <span class="campaign-shade"></span>

                        <span class="campaign-badge">
                            <span class="campaign-badge-dot"></span>
                            {{ $campaign->isShort() ? __('Short') : __('Video') }}
                        </span>

                        <span class="campaign-play" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 0 0 1.52.85l11-6.86a1 1 0 0 0 0-1.7l-11-6.86A1 1 0 0 0 8 5.14z"/></svg>
                        </span>

                        <span class="campaign-info">
                            @if ($campaign->title)
                                <span class="campaign-title">{{ $campaign->title }}</span>
                            @endif
                            <span class="campaign-cta">
                                {{ __('Watch now') }}
                                <svg class="w-3.5 h-3.5 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </span>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- VIDEO POP-UP --}}
        <div id="campaignModal" class="cm-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="{{ __('Campaign video') }}">
            <div id="campaignBackdrop" class="cm-backdrop"></div>

            <div class="cm-content">
                <button id="campaignClose" type="button" class="cm-close" aria-label="{{ __('Close video') }}">
                    <span></span><span></span>
                </button>
                <div id="campaignFrame" class="cm-frame"></div>
                <p id="campaignCaption" class="cm-caption"></p>
            </div>
        </div>
    </section>

    @once
    <style>
        .campaign-section { background: linear-gradient(180deg, #0f172a 0%, #0f172a 55%, #172554 100%); }
        .campaign-glow { position: absolute; border-radius: 9999px; filter: blur(80px); pointer-events: none; opacity: .35; animation: campaignFloat 14s ease-in-out infinite; }
        .campaign-glow-1 { top: -5rem; left: -6rem; width: 22rem; height: 22rem; background: #2563eb; }
        .campaign-glow-2 { bottom: -6rem; right: -5rem; width: 24rem; height: 24rem; background: #0ea5e9; animation-delay: -7s; }
        @keyframes campaignFloat { 0%, 100% { transform: translate(0, 0) scale(1); } 50% { transform: translate(26px, -30px) scale(1.12); } }

        .campaign-pill { display: inline-flex; align-items: center; gap: .5rem; padding: .35rem .85rem; border-radius: 9999px; font-size: .72rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #bae6fd; background: rgba(56,189,248,.12); border: 1px solid rgba(56,189,248,.3); }
        .campaign-pill-dot { width: .45rem; height: .45rem; border-radius: 9999px; background: #38bdf8; box-shadow: 0 0 10px rgba(56,189,248,.9); animation: campaignBlink 1.6s ease-in-out infinite; }
        @keyframes campaignBlink { 50% { opacity: .35; } }

        .campaign-arrow { width: 2.75rem; height: 2.75rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; color: #fff; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.18); transition: background .25s ease, transform .25s ease, opacity .25s ease; }
        .campaign-arrow:hover:not(:disabled) { background: #2563eb; transform: scale(1.08); }
        .campaign-arrow:disabled { opacity: .3; cursor: default; }

        .campaign-track { display: flex; gap: 1.25rem; overflow-x: auto; overscroll-behavior-x: contain; scroll-snap-type: x mandatory; scroll-behavior: smooth; padding: .75rem .25rem 1.5rem; scrollbar-width: none; }
        .campaign-track::-webkit-scrollbar { display: none; }

        .campaign-card { position: relative; flex: 0 0 auto; width: clamp(215px, 68vw, 275px); aspect-ratio: 4 / 5; padding: 0; text-align: start; cursor: pointer; overflow: hidden; border-radius: 1.15rem; background: #0b1220; border: 1px solid rgba(255,255,255,.12); scroll-snap-align: start; box-shadow: 0 16px 34px -16px rgba(0,0,0,.7); transition: transform .45s cubic-bezier(.22,1,.36,1), box-shadow .45s ease, border-color .3s ease; }
        .campaign-card:hover, .campaign-card:focus-visible { transform: translateY(-8px); border-color: rgba(125,211,252,.6); box-shadow: 0 28px 50px -16px rgba(37,99,235,.55); outline: none; }

        .campaign-section:not(.in) .campaign-card { opacity: 0; }
        .campaign-section.in .campaign-card { animation: campaignIn .75s cubic-bezier(.22,1,.36,1) calc(var(--i, 0) * 90ms) backwards; }
        @keyframes campaignIn { from { opacity: 0; transform: translateY(34px) scale(.96); } to { opacity: 1; transform: none; } }

        .campaign-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; transition: transform .7s cubic-bezier(.22,1,.36,1), filter .5s ease; }
        .campaign-card:hover .campaign-img { transform: scale(1.1); filter: brightness(1.08) saturate(1.1); }
        .campaign-shade { position: absolute; inset: 0; pointer-events: none; background: linear-gradient(to top, rgba(2,6,23,.92) 0%, rgba(2,6,23,.4) 40%, transparent 65%); }

        .campaign-badge { position: absolute; top: .8rem; inset-inline-start: .8rem; display: inline-flex; align-items: center; gap: .4rem; padding: .3rem .65rem; border-radius: 9999px; font-size: .62rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #fff; background: rgba(0,0,0,.45); border: 1px solid rgba(255,255,255,.22); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); }
        .campaign-badge-dot { width: .4rem; height: .4rem; border-radius: 9999px; background: #ef4444; box-shadow: 0 0 8px rgba(239,68,68,.9); }

        .campaign-play { position: absolute; left: 50%; top: 46%; width: 3.6rem; height: 3.6rem; margin: -1.8rem 0 0 -1.8rem; display: flex; align-items: center; justify-content: center; border-radius: 9999px; color: #fff; background: rgba(37,99,235,.92); border: 2px solid rgba(255,255,255,.85); box-shadow: 0 10px 28px rgba(0,0,0,.4); transition: transform .35s cubic-bezier(.22,1,.36,1), background .3s ease; }
        .campaign-play svg { width: 1.35rem; height: 1.35rem; margin-left: .15rem; }
        .campaign-play::after { content: ''; position: absolute; inset: -.55rem; border-radius: 9999px; border: 2px solid rgba(125,211,252,.55); animation: campaignRing 2.2s ease-out infinite; }
        @keyframes campaignRing { 0% { transform: scale(.85); opacity: .9; } 100% { transform: scale(1.5); opacity: 0; } }
        .campaign-card:hover .campaign-play { transform: scale(1.14); background: #1d4ed8; }

        .campaign-info { position: absolute; left: 0; right: 0; bottom: 0; padding: 1rem; display: flex; flex-direction: column; gap: .4rem; }
        .campaign-title { color: #fff; font-weight: 600; font-size: .95rem; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .campaign-cta { display: inline-flex; align-items: center; gap: .35rem; font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #7dd3fc; transition: gap .3s ease, color .3s ease; }
        .campaign-card:hover .campaign-cta { gap: .7rem; color: #e0f2fe; }

        .cm-modal { position: fixed; inset: 0; z-index: 10000; display: flex; align-items: center; justify-content: center; padding: 1rem; visibility: hidden; opacity: 0; pointer-events: none; transition: opacity .3s ease, visibility .3s ease; }
        .cm-modal.active { visibility: visible; opacity: 1; pointer-events: auto; }
        .cm-backdrop { position: absolute; inset: 0; background: rgba(2,6,23,.9); -webkit-backdrop-filter: blur(12px); backdrop-filter: blur(12px); }
        .cm-content { position: relative; z-index: 2; max-width: 100%; transform: scale(.9) translateY(20px); opacity: 0; transition: transform .4s cubic-bezier(.22,1,.36,1), opacity .3s ease; }
        .cm-modal.active .cm-content { transform: none; opacity: 1; }
        .cm-frame { position: relative; overflow: hidden; background: #000; width: min(92vw, 960px, calc(84svh * 16 / 9)); aspect-ratio: 16 / 9; border-radius: 1rem; border: 1px solid rgba(255,255,255,.2); box-shadow: 0 30px 100px rgba(0,0,0,.7); }
        .cm-frame.is-short { width: auto; height: min(82svh, 680px); aspect-ratio: 9 / 16; max-width: 92vw; }
        .cm-frame iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
        .cm-caption { margin: .85rem 0 0; text-align: center; color: #e2e8f0; font-size: .95rem; font-weight: 600; }
        .cm-caption:empty { display: none; }
        .cm-close { position: absolute; top: -.9rem; inset-inline-end: -.9rem; z-index: 10; width: 2.6rem; height: 2.6rem; border-radius: 9999px; cursor: pointer; display: flex; align-items: center; justify-content: center; background: rgba(15,23,42,.96); border: 1px solid rgba(255,255,255,.25); transition: transform .25s ease, background .25s ease; }
        .cm-close:hover { transform: rotate(90deg); background: #2563eb; }
        .cm-close span { position: absolute; width: 1rem; height: 2px; background: #fff; border-radius: 2px; }
        .cm-close span:first-child { transform: rotate(45deg); }
        .cm-close span:last-child { transform: rotate(-45deg); }

        @media (max-width: 640px) {
            .campaign-track { gap: 1rem; }
            .cm-close { top: -.6rem; inset-inline-end: -.2rem; width: 2.3rem; height: 2.3rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            .campaign-glow, .campaign-pill-dot, .campaign-play::after { animation: none; }
            .campaign-section:not(.in) .campaign-card { opacity: 1; }
            .campaign-section.in .campaign-card { animation: none; }
            .campaign-card:hover { transform: none; }
            .campaign-track { scroll-behavior: auto; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const section = document.getElementById('campaigns');
            if (!section) return;

            const track = document.getElementById('campaignTrack');
            const prevBtn = document.getElementById('campaignPrev');
            const nextBtn = document.getElementById('campaignNext');
            const modal = document.getElementById('campaignModal');
            const frame = document.getElementById('campaignFrame');
            const caption = document.getElementById('campaignCaption');
            const closeBtn = document.getElementById('campaignClose');
            const backdrop = document.getElementById('campaignBackdrop');

            /* cards slide in when the section is visible */
            if ('IntersectionObserver' in window) {
                const io = new IntersectionObserver(function (entries) {
                    if (entries[0].isIntersecting) {
                        section.classList.add('in');
                        io.disconnect();
                    }
                }, { threshold: 0.12 });
                io.observe(section);
            } else {
                section.classList.add('in');
            }

            /* arrows (work in both left-to-right and right-to-left) */
            const sign = document.documentElement.dir === 'rtl' ? -1 : 1;

            function cardStep() {
                const card = track.querySelector('.campaign-card');
                return card ? card.getBoundingClientRect().width + 20 : 280;
            }
            function updateArrows() {
                if (!prevBtn || !nextBtn) return;
                const pos = Math.abs(track.scrollLeft);
                prevBtn.disabled = pos <= 4;
                nextBtn.disabled = pos + track.clientWidth >= track.scrollWidth - 4;
            }
            if (prevBtn && nextBtn) {
                prevBtn.addEventListener('click', function () { track.scrollBy({ left: -cardStep() * 2 * sign, behavior: 'smooth' }); });
                nextBtn.addEventListener('click', function () { track.scrollBy({ left: cardStep() * 2 * sign, behavior: 'smooth' }); });
                track.addEventListener('scroll', updateArrows, { passive: true });
                window.addEventListener('resize', updateArrows);
                updateArrows();
            }

            /* video pop-up: YouTube is only loaded when a card is clicked */
            let lastTrigger = null;

            function openVideo(card) {
                const id = card.dataset.video || '';
                if (!/^[\w-]{11}$/.test(id)) return;

                lastTrigger = card;

                const iframe = document.createElement('iframe');
                iframe.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0&modestbranding=1&playsinline=1';
                iframe.title = card.dataset.title || 'Campaign video';
                iframe.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
                iframe.referrerPolicy = 'strict-origin-when-cross-origin';
                iframe.allowFullscreen = true;

                frame.innerHTML = '';
                frame.appendChild(iframe);
                frame.classList.toggle('is-short', card.dataset.short === '1');
                caption.textContent = card.dataset.title || '';

                modal.classList.add('active');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                closeBtn.focus();
            }

            function closeVideo() {
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                frame.innerHTML = '';
                if (lastTrigger) lastTrigger.focus();
            }

            track.addEventListener('click', function (e) {
                const card = e.target.closest('.campaign-card');
                if (card) openVideo(card);
            });
            closeBtn.addEventListener('click', closeVideo);
            backdrop.addEventListener('click', closeVideo);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('active')) closeVideo();
            });
        });
    </script>
    @endonce
    @endif


    {{-- ============ LATEST CAUSES ============ --}}
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-3xl font-bold text-gray-900">{{ __('Latest Causes') }}</h2>
            <a href="{{ url('/causes') }}"
               class="border border-gray-300 hover:border-gray-900 text-sm font-medium px-5 py-2.5 rounded-full transition">
                {{ __('More Causes') }}
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @forelse ($latestCauses as $cause)
                <div class="rounded-lg overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition">
                    <img src="{{ $cause->imageUrl() }}" alt="{{ $cause->title }}" class="w-full h-48 object-cover">
                    <div class="p-5">
                        <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $cause->title }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ $cause->summary }}</p>
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
            @empty
                <p class="text-gray-400 col-span-3 text-center py-10">{{ __('No causes added yet.') }}</p>
            @endforelse
        </div>
    </section>

    {{-- ============ SUPPORT PEOPLE IN NEED + DIRECT AID (send this partial next) ============ --}}
    @include('partials.home-aid')


{{-- ================================================================
    WHERE WE WORK
================================================================ --}}
@php
    $provinces = [
        'AFBDS' => [
            'name' => __('Badakhshan'),
            'need' => __('Winter shelter'),
            'description' => __('Winter shelter and essential supplies for vulnerable families in Badakhshan.'),
            'x' => 695, 'y' => 163, 'color' => '#8fd0ed',
        ],
        'AFBAL' => [
            'name' => __('Balkh (Mazar)'),
            'need' => __('Bread for widows'),
            'description' => __('Bread and food support for widows and vulnerable households around Mazar-i-Sharif.'),
            'x' => 458, 'y' => 169, 'color' => '#aeb8d5',
        ],
        'AFHER' => [
            'name' => __('Herat'),
            'need' => __('Food relief'),
            'description' => __('Food relief and essential household support for vulnerable communities in Herat.'),
            'x' => 137, 'y' => 359, 'color' => '#8fd0ed',
        ],
        'AFBAM' => [
            'name' => __('Bamyan'),
            'need' => __('Mountain blankets'),
            'description' => __('Warm blankets and winter supplies for families living in mountainous areas.'),
            'x' => 478, 'y' => 305, 'color' => '#8fd0ed',
        ],
        'AFKAB' => [
            'name' => __('Kabul'),
            'need' => __('Education'),
            'description' => __('Education support, youth development, emergency assistance, and community programs in Kabul.'),
            'x' => 592, 'y' => 340, 'color' => '#aeb8d5',
        ],
        'AFNAN' => [
            'name' => __('Nangarhar'),
            'need' => __('Health clinic'),
            'description' => __('Primary healthcare and community health support for vulnerable families in Nangarhar.'),
            'x' => 675, 'y' => 367, 'color' => '#9ca9bd',
        ],
        'AFKAN' => [
            'name' => __('Kandahar'),
            'need' => __('Food distribution'),
            'description' => __('Food distribution and emergency assistance for vulnerable families in Kandahar.'),
            'x' => 365, 'y' => 602, 'color' => '#aeb8d5',
        ],
        'AFHEL' => [
            'name' => __('Helmand'),
            'need' => __('Water support'),
            'description' => __('Clean water access and essential support for communities across Helmand.'),
            'x' => 269, 'y' => 601, 'color' => '#8fd0ed',
        ],
    ];
@endphp

<section id="where-we-work" class="bg-gray-50 py-12 sm:py-16 lg:py-20">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center mb-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">{{ __('Where We Work') }}</h2>
        <p class="text-sm sm:text-base text-gray-500 max-w-2xl mx-auto">
            {{ __('We currently operate across Afghanistan. Select a province to see its active need.') }}
        </p>
    </div>

    <div class="max-w-7xl mx-auto px-3 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_380px] gap-4 sm:gap-5">

            {{-- MAP (always left-to-right so the markers stay on the right provinces) --}}
            <div id="afghanMap" dir="ltr"
                 class="relative bg-white rounded-xl sm:rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                <div id="mapViewport" class="relative w-full" style="aspect-ratio:1000/762;">

                    <img id="afghanistanMap" src="{{ asset('images/af.svg') }}" alt="Afghanistan map" draggable="false">

                    <div id="mapMarkerLayer" class="absolute inset-0">

                        @foreach ($provinces as $id => $province)
                            <div class="map-marker"
                                 data-province="{{ $id }}"
                                 style="left: {{ ($province['x'] / 1000) * 100 }}%; top: {{ ($province['y'] / 762) * 100 }}%;">

                                <span class="marker-pulse"></span>
                                <span class="marker-ring"></span>

                                <button type="button" class="marker-dot" data-province="{{ $id }}" aria-label="{{ $province['name'] }}"></button>

                                <button type="button" class="marker-label" data-province="{{ $id }}">{{ $province['name'] }}</button>
                            </div>
                        @endforeach

                        <div id="liveLocation" style="left:52%; top:50%;">
                            <span class="live-pulse"></span>
                            <span class="live-ring"></span>
                            <span class="live-dot"></span>
                        </div>
                    </div>

                    <div class="live-badge">
                        <span class="live-badge-dot"></span>
                        <span>{{ __('Live') }}</span>
                    </div>
                </div>
            </div>

            {{-- PROVINCES SIDEBAR --}}
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200">
                    <h3 class="text-xs sm:text-sm font-bold tracking-[0.2em] text-blue-900 uppercase">{{ __('Provinces') }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ __('Select a province to view its active need.') }}</p>
                </div>

                <div class="province-list">
                    @foreach ($provinces as $id => $province)
                        <button type="button" class="province-item" data-province="{{ $id }}">
                            <span class="province-name">
                                <span class="province-color" style="background:{{ $province['color'] }}"></span>
                                <span>{{ $province['name'] }}</span>
                            </span>
                            <span class="province-need">{{ $province['need'] }}</span>
                        </button>
                    @endforeach
                </div>

                <div id="provinceInfo" class="province-info">
                    <div id="provinceEmpty">
                        <p>{{ __('No province selected yet.') }}</p>
                    </div>

                    <div id="provinceSelected" class="hidden">
                        <div class="selected-title">
                            <span id="selectedColor" class="selected-color"></span>
                            <h3 id="selectedName"></h3>
                        </div>
                        <div id="selectedNeed" class="selected-need"></div>
                        <p id="selectedDescription" class="selected-description"></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@once
<style>
    #afghanMap { width: 100%; background: #ffffff; }
    #mapViewport { width: 100%; overflow: hidden; position: relative; }
    /* The SVG is 1000 x 762: never use object-cover here */
    #afghanistanMap { position: absolute; inset: 0; width: 100%; height: 100%; display: block; object-fit: contain; object-position: center; user-select: none; pointer-events: none; }
    #mapMarkerLayer { position: absolute; inset: 0; pointer-events: none; }
    .map-marker { position: absolute; width: 1px; height: 1px; transform: translate(-50%, -50%); z-index: 20; }

    .marker-dot { position: absolute; left: 0; top: 0; width: 15px; height: 15px; padding: 0; transform: translate(-50%, -50%); border-radius: 9999px; background: #0ea5e9; border: 3px solid #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,.18); cursor: pointer; pointer-events: auto; transition: transform .2s ease, background .2s ease; }
    .marker-dot:hover { transform: translate(-50%, -50%) scale(1.3); }
    .map-marker.selected .marker-dot { background: #0284c7; transform: translate(-50%, -50%) scale(1.3); }
    .marker-ring { position: absolute; left: 0; top: 0; width: 29px; height: 29px; transform: translate(-50%, -50%); border-radius: 9999px; background: rgba(14,165,233,.22); pointer-events: none; }
    .marker-pulse { position: absolute; left: 0; top: 0; width: 44px; height: 44px; transform: translate(-50%, -50%); border-radius: 9999px; background: rgba(14,165,233,.12); pointer-events: none; animation: provincePulse 2s ease-in-out infinite; }
    @keyframes provincePulse {
        0% { transform: translate(-50%, -50%) scale(.7); opacity: .2; }
        50% { transform: translate(-50%, -50%) scale(1.15); opacity: .65; }
        100% { transform: translate(-50%, -50%) scale(.7); opacity: .2; }
    }

    .marker-label { position: absolute; left: 15px; top: 0; transform: translateY(-50%); white-space: nowrap; padding: 6px 9px; background: #ffffff; color: #1f2937; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 10px; line-height: 1; font-weight: 700; box-shadow: 0 3px 10px rgba(0,0,0,.10); cursor: pointer; pointer-events: auto; transition: background .2s ease, color .2s ease; }
    .marker-label:hover, .map-marker.selected .marker-label { background: #eff6ff; color: #075985; border-color: #bae6fd; }

    #liveLocation { position: absolute; width: 1px; height: 1px; transform: translate(-50%, -50%); z-index: 40; pointer-events: none; }
    .live-dot { position: absolute; left: 0; top: 0; width: 12px; height: 12px; transform: translate(-50%, -50%); border-radius: 9999px; background: #ef4444; border: 3px solid #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,.20); }
    .live-ring { position: absolute; left: 0; top: 0; width: 27px; height: 27px; transform: translate(-50%, -50%); border-radius: 9999px; background: rgba(239,68,68,.18); }
    .live-pulse { position: absolute; left: 0; top: 0; width: 46px; height: 46px; transform: translate(-50%, -50%); border-radius: 9999px; background: rgba(239,68,68,.10); animation: livePulse 1.7s ease-in-out infinite; }
    @keyframes livePulse {
        0% { transform: translate(-50%, -50%) scale(.65); opacity: .2; }
        50% { transform: translate(-50%, -50%) scale(1.2); opacity: .65; }
        100% { transform: translate(-50%, -50%) scale(.65); opacity: .2; }
    }

    .live-badge { position: absolute; top: 14px; right: 14px; z-index: 50; display: flex; align-items: center; gap: 6px; padding: 6px 10px; background: rgba(255,255,255,.95); border: 1px solid #e5e7eb; border-radius: 9999px; box-shadow: 0 2px 8px rgba(0,0,0,.08); color: #4b5563; font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .live-badge-dot { width: 8px; height: 8px; border-radius: 9999px; background: #ef4444; animation: badgePulse 1.5s infinite; }
    @keyframes badgePulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }

    .province-list { max-height: 420px; overflow-y: auto; }
    .province-item { width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 15px 20px; text-align: start; background: #ffffff; border: 0; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background .2s ease; }
    .province-item:hover { background: #f8fafc; }
    .province-item.selected { background: #eff6ff; }
    .province-name { display: flex; align-items: center; gap: 10px; min-width: 0; font-size: 14px; font-weight: 600; color: #1f2937; }
    .province-color { width: 10px; height: 10px; min-width: 10px; border-radius: 9999px; }
    .province-need { flex-shrink: 0; color: #6b7280; font-size: 12px; text-align: end; }

    .province-info { padding: 20px; background: #f8fafc; border-top: 1px solid #e5e7eb; }
    .province-info p { margin: 0; color: #6b7280; font-size: 14px; line-height: 1.6; }
    .selected-title { display: flex; align-items: center; gap: 8px; }
    .selected-title h3 { margin: 0; font-size: 16px; font-weight: 700; color: #111827; }
    .selected-color { width: 10px; height: 10px; border-radius: 9999px; }
    .selected-need { margin-top: 6px; font-size: 11px; font-weight: 700; color: #0369a1; text-transform: uppercase; letter-spacing: .08em; }
    .selected-description { margin-top: 8px !important; }

    #provinceInfo.update { animation: infoUpdate .3s ease-out; }
    @keyframes infoUpdate { 0% { opacity: .5; transform: scale(.98); } 100% { opacity: 1; transform: scale(1); } }

    @media (max-width: 1023px) { .province-list { max-height: 300px; } }

    @media (max-width: 640px) {
        #mapViewport { aspect-ratio: 1000 / 762 !important; }
        .marker-dot { width: 11px; height: 11px; border-width: 2px; }
        .marker-ring { width: 22px; height: 22px; }
        .marker-pulse { width: 32px; height: 32px; }
        .marker-label { left: 11px; padding: 4px 5px; font-size: 7px; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,.10); }
        .live-dot { width: 9px; height: 9px; border-width: 2px; }
        .live-ring { width: 20px; height: 20px; }
        .live-pulse { width: 32px; height: 32px; }
        .live-badge { top: 8px; right: 8px; padding: 5px 7px; font-size: 8px; gap: 4px; }
        .live-badge-dot { width: 6px; height: 6px; }
        .province-item { padding: 12px 14px; }
        .province-name { font-size: 12px; }
        .province-need { font-size: 10px; }
        .province-info { padding: 14px; }
        .province-info p { font-size: 12px; }
    }

    @media (max-width: 380px) {
        .marker-label { font-size: 6px; padding: 3px 4px; }
        .marker-dot { width: 9px; height: 9px; }
        .marker-ring { width: 18px; height: 18px; }
        .marker-pulse { width: 27px; height: 27px; }
        .province-name { font-size: 11px; }
        .province-need { font-size: 9px; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const provinces = @json($provinces);

        const provinceItems = document.querySelectorAll('.province-item');
        const mapMarkers = document.querySelectorAll('.map-marker');
        const markerButtons = document.querySelectorAll('.marker-dot');
        const markerLabels = document.querySelectorAll('.marker-label');

        const provinceEmpty = document.getElementById('provinceEmpty');
        const provinceSelected = document.getElementById('provinceSelected');
        const selectedName = document.getElementById('selectedName');
        const selectedNeed = document.getElementById('selectedNeed');
        const selectedDescription = document.getElementById('selectedDescription');
        const selectedColor = document.getElementById('selectedColor');
        const provinceInfo = document.getElementById('provinceInfo');

        function selectProvince(provinceId) {
            const province = provinces[provinceId];
            if (!province) return;

            provinceItems.forEach(function (item) {
                item.classList.toggle('selected', item.dataset.province === provinceId);
            });

            mapMarkers.forEach(function (marker) {
                marker.classList.toggle('selected', marker.dataset.province === provinceId);
            });

            provinceEmpty.classList.add('hidden');
            provinceSelected.classList.remove('hidden');

            selectedName.textContent = province.name;
            selectedNeed.textContent = province.need;
            selectedDescription.textContent = province.description;
            selectedColor.style.backgroundColor = province.color;

            provinceInfo.classList.remove('update');
            void provinceInfo.offsetWidth;
            provinceInfo.classList.add('update');
        }

        provinceItems.forEach(function (item) {
            item.addEventListener('click', function () { selectProvince(item.dataset.province); });
        });

        markerButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.stopPropagation();
                selectProvince(button.dataset.province);
            });
        });

        markerLabels.forEach(function (label) {
            label.addEventListener('click', function (event) {
                event.stopPropagation();
                selectProvince(label.dataset.province);
            });
        });
    });
</script>
@endonce


    {{-- ============ STATS ============ --}}
    <section class="max-w-7xl mx-auto px-6 pb-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach ([
                ['1.2k+', 'Projects Completed'],
                ['1000', 'Monthly Donations'],
                ['480', 'Partners Worldwide'],
                ['1.4m', 'Donations Raised'],
            ] as [$num, $label])
                <div>
                    <div class="text-3xl font-extrabold text-gray-900" dir="ltr">{{ $num }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ __($label) }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ LATEST NEWS ============ --}}
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-3xl font-bold text-gray-900">{{ __('Latest News and Blog') }}</h2>
            <a href="{{ url('/blog') }}"
               class="border border-gray-300 hover:border-gray-900 text-sm font-medium px-5 py-2.5 rounded-full transition">
                {{ __('More News') }}
            </a>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @forelse ($latestPosts as $post)
                <article>
                    <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" class="w-full h-48 object-cover rounded-lg mb-4">
                    <div class="text-xs text-gray-400 mb-2">{{ $post->published_at?->format($isRtl ? 'Y/m/d' : 'F j, Y') }}</div>
                    <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $post->title }}</h3>
                    <p class="text-sm text-gray-500 mb-3">{{ $post->excerpt }}</p>
                    <a href="#" class="text-blue-600 text-sm font-medium">{{ __('Read more') }} {!! $isRtl ? '&larr;' : '&rarr;' !!}</a>
                </article>
            @empty
                <p class="text-gray-400 col-span-3 text-center py-10">{{ __('No posts yet.') }}</p>
            @endforelse
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    @include('partials.footer')

</body>
</html>