@php
    $showGallery = $gallery ?? false;   // Amin page passes ['gallery' => true]
    $showGive    = $give ?? false;      // Donation page passes ['give' => true]

    $socials = [
        ['WhatsApp', 'https://wa.me/000000000000', 'hover:bg-green-500',
            '<path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.29-1.39a9.9 9.9 0 0 0 4.75 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.96 6.45 17.5 2 12.04 2zm5.8 14.19c-.24.68-1.41 1.3-1.95 1.35-.5.05-1.02.24-3.42-.71-2.9-1.15-4.77-4.09-4.92-4.28-.14-.19-1.18-1.57-1.18-3 0-1.42.75-2.12 1.02-2.41.27-.29.58-.36.78-.36.19 0 .39 0 .56.01.18.01.42-.07.65.5.24.58.82 2 .89 2.14.07.14.12.31.02.5-.09.19-.14.31-.28.48-.14.16-.29.36-.42.49-.14.14-.28.29-.12.57.16.29.72 1.2 1.55 1.94 1.07.95 1.96 1.25 2.25 1.39.29.14.46.12.63-.07.17-.19.72-.84.92-1.13.19-.29.38-.24.63-.14.26.09 1.66.79 1.94.93.29.14.48.21.55.33.07.13.07.71-.17 1.39z"/>'],
        ['Instagram', 'https://instagram.com', 'hover:bg-pink-500',
            '<path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41 1.27-.06 1.65-.07 4.85-.07M12 0C8.74 0 8.33.01 7.05.07 5.78.13 4.9.33 4.14.63c-.79.31-1.46.72-2.13 1.38A5.9 5.9 0 0 0 .63 4.14C.33 4.9.13 5.78.07 7.05.01 8.33 0 8.74 0 12s.01 3.67.07 4.95c.06 1.27.26 2.15.56 2.91.31.79.72 1.46 1.38 2.13.66.66 1.34 1.07 2.13 1.38.76.3 1.64.5 2.91.56C8.33 23.99 8.74 24 12 24s3.67-.01 4.95-.07c1.27-.06 2.15-.26 2.91-.56.79-.31 1.46-.72 2.13-1.38.66-.66 1.07-1.34 1.38-2.13.3-.76.5-1.64.56-2.91.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95c-.06-1.27-.26-2.15-.56-2.91a5.9 5.9 0 0 0-1.38-2.13A5.9 5.9 0 0 0 19.86.63C19.1.33 18.22.13 16.95.07 15.67.01 15.26 0 12 0z"/><path d="M12 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84zm0 10.16A4 4 0 1 1 16 12a4 4 0 0 1-4 4zM18.41 4.15a1.44 1.44 0 1 0 1.44 1.44 1.44 1.44 0 0 0-1.44-1.44z"/>'],
        ['YouTube', 'https://youtube.com', 'hover:bg-red-600',
            '<path d="M23.5 6.19a3.02 3.02 0 0 0-2.12-2.14C19.51 3.5 12 3.5 12 3.5s-7.51 0-9.38.55A3.02 3.02 0 0 0 .5 6.19 31.6 31.6 0 0 0 0 12a31.6 31.6 0 0 0 .5 5.81 3.02 3.02 0 0 0 2.12 2.14c1.87.55 9.38.55 9.38.55s7.51 0 9.38-.55a3.02 3.02 0 0 0 2.12-2.14A31.6 31.6 0 0 0 24 12a31.6 31.6 0 0 0-.5-5.81zM9.6 15.6V8.4L15.8 12z"/>'],
        ['Facebook', 'https://facebook.com', 'hover:bg-blue-600',
            '<path d="M22 12.06C22 6.51 17.52 2 12 2S2 6.51 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.51 1.49-3.9 3.77-3.9 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.89h2.78l-.44 2.91h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94z"/>'],
    ];
@endphp

<footer class="bg-gray-900 text-gray-300 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-10">
        <div>
            <div class="text-white text-xl font-bold mb-3" dir="ltr">Orphan<span class="text-blue-500">Prosperity</span></div>
            <p class="text-sm text-gray-400 mb-4">
                {{ __('Providing education, love, and opportunities for a brighter future in Afghanistan.') }}
            </p>
            <p class="text-sm text-gray-400">📍 {{ __('Chelsoton 7th district, Kabul, Afghanistan') }}</p>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-3">{{ __('About Us') }}</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ url('/about') }}" class="hover:text-white">{{ __('Our Story') }}</a></li>
                <li><a href="{{ url('/causes') }}" class="hover:text-white">{{ __('Causes') }}</a></li>
                <li><a href="{{ url('/aman-id') }}" class="hover:text-white">{{ __('Aman ID') }}</a></li>
                <li><a href="{{ url('/pioneer') }}" class="hover:text-white">{{ __('Volunteers') }}</a></li>
                <li><a href="{{ url('/contact') }}" class="hover:text-white">{{ __('Contact Us') }}</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-3">{{ __('Useful Links') }}</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#" class="hover:text-white">{{ __('FAQ') }}</a></li>
                <li><a href="#" class="hover:text-white">{{ __('Forums') }}</a></li>
                <li><a href="#" class="hover:text-white">{{ __('Reports') }}</a></li>
                <li><a href="#" class="hover:text-white">{{ __('Terms of Use') }}</a></li>
            </ul>
        </div>

       
    </div>

    <div class="max-w-7xl mx-auto px-6 mt-12 pt-6 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-400">
        <div class="flex gap-3">
            @foreach ($socials as [$name, $link, $hover, $icon])
                <a href="{{ $link }}" target="_blank" rel="noopener" aria-label="{{ $name }}"
                   class="group w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center
                          transition-all duration-300 {{ $hover }} hover:scale-110 hover:-translate-y-1">
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                </a>
            @endforeach
        </div>
        <div>&copy; {{ date('Y') }} Orphan Prosperity. {{ __('All rights reserved.') }}</div>
    </div>
</footer>