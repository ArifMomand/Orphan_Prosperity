@extends('layouts.site')

@section('title', $package->title . ' | Orphan Prosperity')

@section('content')
<section class="max-w-7xl mx-auto px-6 pt-8">
    <nav class="text-sm text-gray-500 flex flex-wrap items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a><span>/</span>
        <a href="{{ route('aid.index') }}" class="hover:text-blue-600">Direct Aid</a><span>/</span>
        <span class="text-gray-900">{{ $package->title }}</span>
    </nav>
</section>

<section class="max-w-7xl mx-auto px-6 py-10 grid lg:grid-cols-2 gap-10 items-start">
    <div class="rounded-2xl overflow-hidden bg-gray-100 aspect-square shadow-lg lg:sticky lg:top-24">
        <img src="{{ $package->imageUrl() }}" alt="{{ $package->title }}" class="w-full h-full object-cover">
    </div>

    <div>
        <span class="inline-block text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">{{ $package->category }}</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-3">{{ $package->title }}</h1>
        <div class="text-3xl font-extrabold text-blue-600 mt-3">{{ $package->priceLabel() }}</div>

        @if ($package->description)
            <p class="text-gray-600 leading-relaxed mt-6">{{ $package->description }}</p>
        @endif

        @if (count($package->includesList()))
            <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">What's included</h2>
            <ul class="space-y-2">
                @foreach ($package->includesList() as $line)
                    <li class="flex items-start gap-2 text-gray-700">
                        <span class="mt-0.5 w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs shrink-0">✓</span>
                        {{ $line }}
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="flex flex-wrap gap-3 mt-10">
            <a href="{{ url('/donate?package=' . $package->id) }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-7 py-3 rounded-full shadow-md transition hover:scale-105">
                Send this aid
            </a>
            <a href="{{ route('aid.index') }}" class="border border-gray-300 hover:border-gray-900 font-medium px-7 py-3 rounded-full transition">Back to all packages</a>
        </div>

        @if ($people->isNotEmpty())
            <h2 class="text-xl font-bold text-gray-900 mt-12 mb-3">People who need this</h2>
            <div class="flex flex-wrap gap-4">
                @foreach ($people as $person)
                    <a href="{{ route('people.show', $person) }}" class="flex items-center gap-3 rounded-xl border border-gray-100 pr-4 hover:shadow-md hover:border-blue-200 transition">
                        <img src="{{ $person->imageUrl() }}" alt="{{ $person->name }}" class="w-14 h-14 rounded-l-xl object-cover">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">{{ $person->name }}</div>
                            <div class="text-xs text-gray-500">{{ $person->locationLabel() }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

@if ($related->isNotEmpty())
<section class="bg-gray-50 py-14">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">More {{ strtolower($package->category) }} packages</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ($related as $other)
                <a href="{{ route('aid.show', $other) }}" class="group block">
                    <div class="rounded-xl overflow-hidden bg-gray-100 aspect-square">
                        <img src="{{ $other->imageUrl() }}" alt="{{ $other->title }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <div class="mt-2 text-sm font-semibold text-gray-900 truncate">{{ $other->title }}</div>
                    <div class="text-sm text-gray-600">{{ $other->priceLabel() }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
