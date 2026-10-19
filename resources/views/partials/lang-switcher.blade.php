@php
    $current = $locales[$locale] ?? $locales['en'];

    $globe = '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c2.5 2.6 3.8 5.6 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.6-3.8-9S9.5 5.6 12 3z"/>';
@endphp

@if (! empty($mobile))

    {{-- ============ MOBILE: simple row (the mobile menu clips dropdowns) ============ --}}
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $globe !!}</svg>

        <div class="flex flex-1 rounded-full border border-gray-300 overflow-hidden text-xs font-semibold">
            @foreach ($locales as $code => $meta)
                <a href="{{ route('lang.switch', $code) }}"
                   lang="{{ $meta['html'] }}" hreflang="{{ $meta['html'] }}"
                   class="flex-1 text-center px-3 py-2 transition {{ $locale === $code ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    {{ $meta['native'] }}
                </a>
            @endforeach
        </div>
    </div>

@else

    {{-- ============ DESKTOP: globe button + dropdown ============ --}}
    <div class="relative" data-lang-switcher>
        <button type="button" data-lang-toggle
                aria-haspopup="menu" aria-expanded="false" aria-label="{{ __('Language') }}"
                class="group inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white/80 hover:bg-white hover:border-blue-400 px-3.5 py-2 text-sm font-medium text-gray-700 shadow-sm transition">
            <svg class="w-[18px] h-[18px] text-blue-600 transition-transform duration-500 group-hover:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $globe !!}</svg>
            <span lang="{{ $current['html'] }}">{{ $current['native'] }}</span>
            <svg data-lang-chevron class="w-4 h-4 text-gray-400 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </button>

        <div data-lang-menu role="menu"
             class="lang-menu absolute end-0 top-full mt-2 w-56 rounded-2xl border border-gray-100 bg-white p-1.5 shadow-xl shadow-slate-900/10 ring-1 ring-black/5 z-[60]">
            @foreach ($locales as $code => $meta)
                @php $isActive = $locale === $code; @endphp
                <a href="{{ route('lang.switch', $code) }}" role="menuitem"
                   lang="{{ $meta['html'] }}" hreflang="{{ $meta['html'] }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 transition {{ $isActive ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                    <span class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold {{ $isActive ? 'text-blue-700' : 'text-gray-900' }}" dir="auto">{{ $meta['native'] }}</span>
                        <span class="block text-xs text-gray-400" dir="ltr">{{ $meta['name'] }}</span>
                    </span>
                    @if ($isActive)
                        <svg class="w-5 h-5 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    @once
    <style>
        .lang-menu {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-6px) scale(.97);
            transition: opacity .2s ease, transform .2s ease, visibility .2s ease;
        }
        .lang-menu.open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-lang-switcher]').forEach(function (box) {
                const btn  = box.querySelector('[data-lang-toggle]');
                const menu = box.querySelector('[data-lang-menu]');
                const chev = box.querySelector('[data-lang-chevron]');

                function setOpen(open) {
                    menu.classList.toggle('open', open);
                    btn.setAttribute('aria-expanded', String(open));
                    chev.classList.toggle('rotate-180', open);
                }

                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    setOpen(! menu.classList.contains('open'));
                });

                document.addEventListener('click', function (e) {
                    if (! box.contains(e.target)) setOpen(false);
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') { setOpen(false); btn.focus(); }
                });
            });
        });
    </script>
    @endonce

@endif