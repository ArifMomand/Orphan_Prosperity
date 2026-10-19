{{--
    resources/views/causes/show.blade.php
    Single cause detail page — matches home.blade.php's Tailwind styling exactly.
    Expects: $cause (Cause model), $related (Collection of Cause)
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cause->title }} | Orphan Prosperity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-800 antialiased">

    {{-- ============ NAVBAR ============ --}}
       @include('partials.navbar')


    @once
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
        });
    </script>
    @endonce

    {{-- ============ BREADCRUMB / HEADER ============ --}}
    <section class="relative bg-gray-900 min-h-[320px] flex items-end overflow-hidden">
        <img src="{{ $cause->imageUrl() }}"
             alt="{{ $cause->title }}"
             class="absolute inset-0 w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

        <div class="relative max-w-7xl mx-auto px-6 pb-12 pt-24 w-full">
            <div class="text-sm text-gray-300 mb-3">
                <a href="{{ url('/causes') }}" class="hover:text-white">Causes</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $cause->title }}</span>
            </div>
            <span class="inline-block text-xs font-semibold text-white bg-blue-600 px-3 py-1 rounded-full mb-4">{{ $cause->category }}</span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-white leading-tight max-w-2xl">
                {{ $cause->title }}
            </h1>
        </div>
    </section>

    {{-- ============ DETAIL LAYOUT ============ --}}
    <section class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid lg:grid-cols-[1.4fr_1fr] gap-12 items-start">

            {{-- Left: story --}}
            <div>
                <img src="{{ $cause->imageUrl() }}"
                     alt="{{ $cause->title }}" class="w-full h-80 object-cover rounded-lg mb-8">

                <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Cause</h2>
                @foreach (explode("\n\n", $cause->story) as $paragraph)
                    <p class="text-gray-600 leading-relaxed mb-4">{{ $paragraph }}</p>
                @endforeach

                <div class="grid grid-cols-2 gap-6 mt-8 pt-8 border-t border-gray-100">
                    <div>
                        <div class="text-sm text-gray-400 mb-1">Location</div>
                        <div class="font-semibold text-gray-900">{{ $cause->location }}</div>
                    </div>
                    @if ($cause->orphan_name)
    <div>
        <div class="text-sm text-gray-400 mb-1">Orphan / Beneficiary</div>
        <div class="font-semibold text-gray-900">{{ $cause->orphan_name }}</div>
    </div>
@endif
                    <div>
                        <div class="text-sm text-gray-400 mb-1">Category</div>
                        <div class="font-semibold text-gray-900">{{ $cause->category }}</div>
                    </div>
                </div>
            </div>

            {{-- Right: donate panel --}}
            <div class="sticky top-24 bg-white border border-gray-100 shadow-sm rounded-lg p-6">
                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-3">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $cause->percentFunded() }}%"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-500 mb-6">
                    <span><strong class="text-gray-900">${{ number_format($cause->raised) }}</strong> raised</span>
                    <span>of ${{ number_format($cause->goal) }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6 text-center">
                    <div class="bg-gray-50 rounded-lg py-3">
                        <div class="text-xl font-extrabold text-gray-900">{{ $cause->percentFunded() }}%</div>
                        <div class="text-xs text-gray-500">Funded</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg py-3">
                        <div class="text-xl font-extrabold text-gray-900">{{ $cause->donors }}</div>
                        <div class="text-xs text-gray-500">Donors</div>
                    </div>
                </div>

                <a href="{{ url('/donate') }}"
                   class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-full transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                    Donate to This Cause
                </a>

                <div class="flex items-center justify-center gap-3 mt-5 pt-5 border-t border-gray-100 text-sm text-gray-500">
                    <span>Share:</span>
                    <a href="https://wa.me/?text={{ urlencode($cause->title) }}" target="_blank" rel="noopener" class="hover:text-blue-600">WhatsApp</a>
                    <span>&middot;</span>
                    <a href="https://facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="hover:text-blue-600">Facebook</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ RELATED CAUSES ============ --}}
    @if ($related->count())
        <section class="bg-gray-50 py-20">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-3xl font-bold text-gray-900 mb-10">Other Causes You Might Support</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach ($related as $item)
                        <div class="rounded-lg overflow-hidden border border-gray-100 bg-white shadow-sm hover:shadow-md transition">
                            <img src="{{ $item->imageUrl() }}"
                                 alt="{{ $item->title }}" class="w-full h-44 object-cover">
                            <div class="p-5">
                                <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $item->title }}</h3>
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $item->percentFunded() }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mb-4">
                                    <span>Goal: ${{ number_format($item->goal) }}</span>
                                    <span>{{ $item->donors }} donors</span>
                                </div>
                                <a href="{{ url('/causes/' . $item->slug) }}"
                                   class="block text-center bg-gray-900 hover:bg-black text-white text-sm font-medium py-2.5 rounded-md transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-gray-900 text-gray-300 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-10">
            <div>
                <div class="text-white text-xl font-bold mb-3">Orphan<span class="text-blue-500">Prosperity</span></div>
                <p class="text-sm text-gray-400 mb-4">
                    Incident luctus porta amet lectus at ultrices nec sed amet. Sed id egestas.
                </p>
                <p class="text-sm text-gray-400">📍 Shar-e-Naw, Kabul, Afghanistan</p>
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
            <div>&copy; {{ date('Y') }} Orphan Prosperity. All rights reserved.</div>
        </div>
    </footer>

</body>
</html>
