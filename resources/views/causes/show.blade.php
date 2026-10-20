{{--
    resources/views/causes/show.blade.php
    Single cause detail page, available in English, Dari and Pashto.
    Expects: $cause (Cause model), $related (Collection of Cause)
--}}
<!DOCTYPE html>
<html lang="{{ $htmlLang }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cause->title }} | Orphan Prosperity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.i18n-head')
</head>
<body class="bg-white text-gray-800 antialiased">

    {{-- ============ NAVBAR ============ --}}
    @include('partials.navbar')

    {{-- ============ BREADCRUMB / HEADER ============ --}}
    <section class="relative bg-gray-900 min-h-[320px] flex items-end overflow-hidden">
        {{-- تصویر پس‌زمینه هدر با محوکننده --}}
        <img src="{{ $cause->imageUrl() }}"
             alt=""
             class="absolute inset-0 w-full h-full object-cover blur-sm opacity-30 scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

        <div class="relative max-w-7xl mx-auto px-6 pb-12 pt-24 w-full">
            <div class="text-sm text-gray-300 mb-3">
                <a href="{{ url('/causes') }}" class="hover:text-white">{{ __('Causes') }}</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $cause->title }}</span>
            </div>
            <span class="inline-block text-xs font-semibold text-white bg-blue-600 px-3 py-1 rounded-full mb-4">{{ __($cause->category) }}</span>
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
                {{-- کادر تصویر اصلی با پشتیبانی کاملا پاسخگو از Landscape و Portrait --}}
                <div class="relative w-full min-h-[350px] max-h-[500px] bg-slate-100 rounded-lg overflow-hidden mb-8 flex items-center justify-center p-3 border border-gray-100 shadow-sm">
                    {{-- پس‌زمینه محو برای پر کردن حاشیه‌ها --}}
                    <img src="{{ $cause->imageUrl() }}" 
                         alt="" 
                         class="absolute inset-0 w-full h-full object-cover blur-xl opacity-25 scale-110 pointer-events-none">
                    
                    {{-- تصویر اصلی کامل بدون برش --}}
                    <img src="{{ $cause->imageUrl() }}"
                         alt="{{ $cause->title }}" 
                         class="relative max-h-[480px] w-auto max-w-full object-contain rounded drop-shadow-md z-10">
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('About This Cause') }}</h2>
                @foreach (explode("\n\n", $cause->story) as $paragraph)
                    <p class="text-gray-600 leading-relaxed mb-4">{{ $paragraph }}</p>
                @endforeach

                <div class="grid grid-cols-2 gap-6 mt-8 pt-8 border-t border-gray-100">
                    <div>
                        <div class="text-sm text-gray-400 mb-1">{{ __('Location') }}</div>
                        <div class="font-semibold text-gray-900">{{ $cause->location }}</div>
                    </div>
                    @if ($cause->orphan_name)
                        <div>
                            <div class="text-sm text-gray-400 mb-1">{{ __('Orphan / Beneficiary') }}</div>
                            <div class="font-semibold text-gray-900">{{ $cause->orphan_name }}</div>
                        </div>
                    @endif
                    <div>
                        <div class="text-sm text-gray-400 mb-1">{{ __('Category') }}</div>
                        <div class="font-semibold text-gray-900">{{ __($cause->category) }}</div>
                    </div>
                </div>
            </div>

            {{-- Right: donate panel --}}
            <div class="sticky top-24 bg-white border border-gray-100 shadow-sm rounded-lg p-6">
                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-3">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $cause->percentFunded() }}%"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-500 mb-6">
                    <span>{!! __(':amount raised', ['amount' => '<strong class="text-gray-900" dir="ltr">$' . number_format($cause->raised) . '</strong>']) !!}</span>
                    <span>{!! __('of :amount', ['amount' => '<span dir="ltr">$' . number_format($cause->goal) . '</span>']) !!}</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6 text-center">
                    <div class="bg-gray-50 rounded-lg py-3">
                        <div class="text-xl font-extrabold text-gray-900" dir="ltr">{{ $cause->percentFunded() }}%</div>
                        <div class="text-xs text-gray-500">{{ __('Funded') }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg py-3">
                        <div class="text-xl font-extrabold text-gray-900">{{ $cause->donors }}</div>
                        <div class="text-xs text-gray-500">{{ __('Donors') }}</div>
                    </div>
                </div>

                <a href="{{ url('/donate') }}"
                   class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-full transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                    {{ __('Donate to This Cause') }}
                </a>

                <div class="flex items-center justify-center gap-3 mt-5 pt-5 border-t border-gray-100 text-sm text-gray-500">
                    <span>{{ __('Share:') }}</span>
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
                <h2 class="text-3xl font-bold text-gray-900 mb-10">{{ __('Other Causes You Might Support') }}</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach ($related as $item)
                        <div class="rounded-lg overflow-hidden border border-gray-100 bg-white shadow-sm hover:shadow-md transition flex flex-col h-full">
                            {{-- تصویر کادر پیشنهادها --}}
                            <div class="relative w-full h-52 bg-slate-100 flex items-center justify-center overflow-hidden p-2">
                                <img src="{{ $item->imageUrl() }}" 
                                     alt="" 
                                     class="absolute inset-0 w-full h-full object-cover blur-md opacity-30 scale-110 pointer-events-none">
                                
                                <img src="{{ $item->imageUrl() }}"
                                     alt="{{ $item->title }}" 
                                     class="relative max-h-full max-w-full object-contain rounded drop-shadow-sm z-10 transition-transform duration-300 hover:scale-105">
                            </div>

                            <div class="p-5 flex flex-col flex-grow justify-between">
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-900 mb-2 line-clamp-1">{{ $item->title }}</h3>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $item->percentFunded() }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 mb-4">
                                        <span>{{ __('Goal') }}: <span dir="ltr">${{ number_format($item->goal) }}</span></span>
                                        <span>{{ $item->donors }} {{ __('donors') }}</span>
                                    </div>
                                </div>
                                <a href="{{ url('/causes/' . $item->slug) }}"
                                   class="block text-center bg-gray-900 hover:bg-black text-white text-sm font-medium py-2.5 rounded-md transition">
                                    {{ __('View Details') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ FOOTER ============ --}}
    @include('partials.footer', ['gallery' => true])

</body>
</html>