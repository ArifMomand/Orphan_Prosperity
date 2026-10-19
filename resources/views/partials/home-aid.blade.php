{{-- ============ SUPPORT PEOPLE IN NEED ============ --}}
<section class="max-w-7xl mx-auto px-6 pt-16 pb-6"
         data-aid-section data-default="{{ $peopleCategories[0] ?? 'all' }}">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('Support People In Need') }}</h2>
            <a href="{{ route('people.index') }}"
               class="border border-gray-300 hover:border-gray-900 text-xs font-medium px-3 py-1 rounded-full transition">{{ __('View all') }} {!! $isRtl ? '&lsaquo;' : '&rsaquo;' !!}</a>
        </div>
        <div class="flex gap-2">
            <button type="button" data-prev aria-label="{{ __('Previous') }}"
                    class="w-8 h-8 rounded-full border border-gray-300 text-gray-700 flex items-center justify-center transition hover:border-gray-900 disabled:opacity-40 disabled:cursor-not-allowed">{!! $isRtl ? '&rarr;' : '&larr;' !!}</button>
            <button type="button" data-next aria-label="{{ __('Next') }}"
                    class="w-8 h-8 rounded-full border border-gray-300 text-gray-700 flex items-center justify-center transition hover:border-gray-900 disabled:opacity-40 disabled:cursor-not-allowed">{!! $isRtl ? '&larr;' : '&rarr;' !!}</button>
        </div>
    </div>

    <div class="flex items-center gap-2 mb-5 overflow-x-auto pb-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        <select data-location aria-label="{{ __('Filter by location') }}"
                class="shrink-0 border border-gray-200 rounded-md text-xs text-gray-600 px-3 py-2 bg-white focus:outline-none focus:border-blue-600">
            <option value="">{{ __('All locations') }}</option>
            @foreach ($peopleCities as $city)
                <option value="{{ $city }}">{{ $city }}</option>
            @endforeach
        </select>
        @foreach ($peopleCategories as $category)
            <button type="button" data-filter="{{ $category }}"
                    class="shrink-0 border border-gray-200 bg-white text-gray-700 rounded-full text-xs font-medium px-4 py-2 transition hover:border-blue-600">
                {{ __($category) }}
            </button>
        @endforeach
    </div>

    <div data-scroller class="flex gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        @foreach ($needyPeople as $person)
            <article data-card data-category="{{ $person->category }}" data-city="{{ $person->city }}"
                     class="snap-start shrink-0 w-44 sm:w-52">
                <div class="relative rounded-xl overflow-hidden bg-gray-100 aspect-[4/5]">
                    <a href="{{ route('people.show', $person) }}" class="block w-full h-full">
                        <img src="{{ $person->imageUrl() }}" alt="{{ $person->name }}" loading="lazy" class="w-full h-full object-cover">
                    </a>
                    <button type="button" data-heart aria-label="{{ __('Save :name', ['name' => $person->name]) }}"
                            class="absolute top-2 end-2 w-7 h-7 rounded-full bg-white/90 text-gray-500 flex items-center justify-center transition hover:scale-110">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-4.4-9.4-8.6C.9 9.3 2 5.8 5 5c2-.5 4 .4 5 2.2L12 9l2-1.8c1-1.8 3-2.7 5-2.2 3 .8 4.1 4.3 2.4 7.4C19 16.6 12 21 12 21z"/></svg>
                    </button>
                </div>

                <div class="mt-2 rounded-xl bg-gray-50 p-3">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-sm font-semibold text-gray-900 truncate">
                            <a href="{{ route('people.show', $person) }}" class="hover:text-blue-600 transition-colors">{{ $person->name }}</a>
                        </h3>
                        @if ($person->is_verified)
                            <svg class="w-4 h-4 shrink-0 text-blue-600" viewBox="0 0 24 24" fill="currentColor" aria-label="{{ __('Verified') }}"><path d="M12 2l2.4 2 3.1-.2 1 3 2.7 1.6-.9 3 .9 3-2.7 1.6-1 3-3.1-.2L12 22l-2.4-2-3.1.2-1-3L2.8 15.6l.9-3-.9-3L5.5 8l1-3 3.1.2L12 2zm-1.2 13.4l5-5-1.1-1.1-3.9 3.9-1.8-1.8-1.1 1.1 2.9 2.9z"/></svg>
                        @endif
                    </div>
                    <p class="text-[11px] text-gray-500 mt-0.5">{{ __('From :place', ['place' => $person->locationLabel() . ' ' . $person->flag()]) }}</p>

                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center gap-1 min-w-0">
                            <span class="text-[11px] text-gray-500">{{ __('Wishlist:') }}</span>
                            @foreach ($person->aidPackages->take(3) as $item)
                                <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}" title="{{ $item->title }}"
                                     class="w-5 h-5 rounded object-cover border border-gray-200">
                            @endforeach
                        </div>
                        <a href="{{ route('people.show', $person) }}"
                           class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded-md transition">
                            {{ __('Support') }} {!! $isRtl ? '&lsaquo;' : '&rsaquo;' !!}
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
        <p data-empty class="{{ $needyPeople->isEmpty() ? '' : 'hidden' }} w-full text-center text-gray-400 py-10">{{ __('No people to show here yet.') }}</p>
    </div>
</section>

{{-- ============ SEND DIRECT AID ============ --}}
<section class="max-w-7xl mx-auto px-6 pt-10 pb-16" data-aid-section data-default="all">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('Send Direct Aid') }}</h2>
            <a href="{{ route('aid.index') }}"
               class="border border-gray-300 hover:border-gray-900 text-xs font-medium px-3 py-1 rounded-full transition">{{ __('View all') }} {!! $isRtl ? '&lsaquo;' : '&rsaquo;' !!}</a>
        </div>
        <div class="flex gap-2">
            <button type="button" data-prev aria-label="{{ __('Previous') }}"
                    class="w-8 h-8 rounded-full border border-gray-300 text-gray-700 flex items-center justify-center transition hover:border-gray-900 disabled:opacity-40 disabled:cursor-not-allowed">{!! $isRtl ? '&rarr;' : '&larr;' !!}</button>
            <button type="button" data-next aria-label="{{ __('Next') }}"
                    class="w-8 h-8 rounded-full border border-gray-300 text-gray-700 flex items-center justify-center transition hover:border-gray-900 disabled:opacity-40 disabled:cursor-not-allowed">{!! $isRtl ? '&larr;' : '&rarr;' !!}</button>
        </div>
    </div>

    <div class="flex items-center gap-2 mb-5 overflow-x-auto pb-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        <button type="button" data-filter="all"
                class="shrink-0 border border-gray-200 bg-white text-gray-700 rounded-full text-xs font-medium px-4 py-2 transition hover:border-blue-600">{{ __('All') }}</button>
        @foreach ($aidCategories as $category)
            <button type="button" data-filter="{{ $category }}"
                    class="shrink-0 border border-gray-200 bg-white text-gray-700 rounded-full text-xs font-medium px-4 py-2 transition hover:border-blue-600">
                {{ __($category) }}
            </button>
        @endforeach
    </div>

    <div data-scroller class="flex gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        @foreach ($aidPackages as $package)
            <a href="{{ route('aid.show', $package) }}" data-card data-category="{{ $package->category }}"
               class="snap-start shrink-0 w-44 sm:w-52 group">
                <div class="relative rounded-xl overflow-hidden bg-gray-100 aspect-square">
                    <img src="{{ $package->imageUrl() }}" alt="{{ $package->title }}" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <button type="button" data-heart aria-label="{{ __('Save :name', ['name' => $package->title]) }}"
                            class="absolute top-2 end-2 w-7 h-7 rounded-full bg-white/90 text-gray-500 flex items-center justify-center transition hover:scale-110">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-4.4-9.4-8.6C.9 9.3 2 5.8 5 5c2-.5 4 .4 5 2.2L12 9l2-1.8c1-1.8 3-2.7 5-2.2 3 .8 4.1 4.3 2.4 7.4C19 16.6 12 21 12 21z"/></svg>
                    </button>
                </div>
                <div class="mt-2 rounded-xl bg-gray-50 p-3">
                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $package->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1" dir="ltr">{{ $package->priceLabel() }}</p>
                </div>
            </a>
        @endforeach
        <p data-empty class="{{ $aidPackages->isEmpty() ? '' : 'hidden' }} w-full text-center text-gray-400 py-10">{{ __('No aid packages yet.') }}</p>
    </div>
</section>

@once
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const on = ['bg-blue-600', 'text-white', 'border-blue-600'];
        const off = ['bg-white', 'text-gray-700', 'border-gray-200'];
        const sign = document.documentElement.dir === 'rtl' ? -1 : 1;   // scrolling direction flips in right-to-left

        document.querySelectorAll('[data-aid-section]').forEach(function (section) {
            const scroller = section.querySelector('[data-scroller]');
            const cards = Array.from(scroller.querySelectorAll('[data-card]'));
            const tabs = Array.from(section.querySelectorAll('[data-filter]'));
            const select = section.querySelector('[data-location]');
            const prev = section.querySelector('[data-prev]');
            const next = section.querySelector('[data-next]');
            const empty = section.querySelector('[data-empty]');
            let active = section.dataset.default || 'all';

            function updateArrows() {
                const pos = Math.abs(scroller.scrollLeft);
                prev.disabled = pos <= 2;
                next.disabled = pos + scroller.clientWidth >= scroller.scrollWidth - 2;
            }

            function apply() {
                let shown = 0;
                cards.forEach(function (card) {
                    const okCat = active === 'all' || card.dataset.category === active;
                    const okCity = !select || !select.value || card.dataset.city === select.value;
                    card.classList.toggle('hidden', !(okCat && okCity));
                    if (okCat && okCity) shown++;
                });
                if (empty) empty.classList.toggle('hidden', shown > 0);

                tabs.forEach(function (tab) {
                    const isActive = tab.dataset.filter === active;
                    on.forEach(function (c) { tab.classList.toggle(c, isActive); });
                    off.forEach(function (c) { tab.classList.toggle(c, !isActive); });
                });

                scroller.scrollTo({ left: 0 });
                updateArrows();
            }

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () { active = tab.dataset.filter; apply(); });
            });
            if (select) select.addEventListener('change', apply);

            prev.addEventListener('click', function () { scroller.scrollBy({ left: -scroller.clientWidth * 0.8 * sign }); });
            next.addEventListener('click', function () { scroller.scrollBy({ left: scroller.clientWidth * 0.8 * sign }); });
            scroller.addEventListener('scroll', updateArrows, { passive: true });
            window.addEventListener('resize', updateArrows);

            apply();
        });

        // Heart toggle (visual only; wire up to a wishlist later if needed)
        document.querySelectorAll('[data-heart]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                btn.classList.toggle('text-red-500');
                btn.classList.toggle('text-gray-500');
                btn.querySelector('svg').setAttribute('fill', btn.classList.contains('text-red-500') ? 'currentColor' : 'none');
            });
        });
    });
</script>
@endonce