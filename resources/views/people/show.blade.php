@extends('layouts.site')

@section('title', $person->name . ' | Orphan Prosperity')

@section('content')
@php
    use App\Models\AidPackage;
    $first = \Illuminate\Support\Str::before($person->name, ' ');
    $wishlist = $person->aidPackages;
    $currencies = $wishlist->pluck('currency')->unique();
    $total = $currencies->count() === 1
        ? (new AidPackage(['currency' => $currencies->first(), 'price' => $wishlist->sum('price')]))->priceLabel()
        : null;
@endphp

<section class="max-w-7xl mx-auto px-6 pt-8">
    <nav class="text-sm text-gray-500 flex flex-wrap items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a><span>/</span>
        <a href="{{ route('people.index') }}" class="hover:text-blue-600">People in Need</a><span>/</span>
        <span class="text-gray-900">{{ $person->name }}</span>
    </nav>
</section>

<section class="max-w-7xl mx-auto px-6 py-10 grid lg:grid-cols-[minmax(0,420px)_1fr] gap-10 items-start">
    {{-- Photo --}}
    <div class="lg:sticky lg:top-24">
        <div class="rounded-2xl overflow-hidden bg-gray-100 aspect-[4/5] shadow-lg">
            <img src="{{ $person->imageUrl() }}" alt="{{ $person->name }}" class="w-full h-full object-cover">
        </div>
    </div>

    {{-- Details --}}
    <div>
        <span class="inline-block text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">{{ $person->category }}</span>
        <div class="flex items-center gap-2 mt-3">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900">{{ $person->name }}</h1>
            @if ($person->is_verified)
                <svg class="w-7 h-7 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-label="Verified"><path d="M12 2l2.4 2 3.1-.2 1 3 2.7 1.6-.9 3 .9 3-2.7 1.6-1 3-3.1-.2L12 22l-2.4-2-3.1.2-1-3L2.8 15.6l.9-3-.9-3L5.5 8l1-3 3.1.2L12 2zm-1.2 13.4l5-5-1.1-1.1-3.9 3.9-1.8-1.8-1.1 1.1 2.9 2.9z"/></svg>
            @endif
        </div>
        <p class="text-gray-500 mt-1">From {{ $person->city ? $person->city . ', ' : '' }}{{ $person->country }} {{ $person->flag() }}</p>

        {{-- Quick facts --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
            <div class="rounded-xl bg-gray-50 p-4"><div class="text-xs text-gray-500">Category</div><div class="font-semibold text-gray-900 mt-1">{{ $person->category }}</div></div>
            <div class="rounded-xl bg-gray-50 p-4"><div class="text-xs text-gray-500">Location</div><div class="font-semibold text-gray-900 mt-1">{{ $person->city ?: $person->country }}</div></div>
            @if ($person->age)
                <div class="rounded-xl bg-gray-50 p-4"><div class="text-xs text-gray-500">Age</div><div class="font-semibold text-gray-900 mt-1">{{ $person->age }}</div></div>
            @endif
            @if ($person->family_size)
                <div class="rounded-xl bg-gray-50 p-4"><div class="text-xs text-gray-500">Family members</div><div class="font-semibold text-gray-900 mt-1">{{ $person->family_size }}</div></div>
            @endif
        </div>

        {{-- Story --}}
        <h2 class="text-xl font-bold text-gray-900 mt-10 mb-3">{{ $first }}'s story</h2>
        @forelse ($person->storyParagraphs() as $paragraph)
            <p class="text-gray-600 leading-relaxed mb-4">{{ $paragraph }}</p>
        @empty
            <p class="text-gray-400">The full story will be added soon.</p>
        @endforelse

        {{-- Wishlist --}}
        <h2 class="text-xl font-bold text-gray-900 mt-10 mb-3">{{ $first }}'s wishlist</h2>
        @if ($wishlist->isEmpty())
            <p class="text-gray-400">No wishlist items yet.</p>
        @else
            <div class="grid sm:grid-cols-2 gap-4">
                @foreach ($wishlist as $item)
                    <a href="{{ route('aid.show', $item) }}" class="flex items-center gap-3 rounded-xl border border-gray-100 p-3 hover:shadow-md hover:border-blue-200 transition">
                        <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}" class="w-16 h-16 rounded-lg object-cover shrink-0">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-gray-900 truncate">{{ $item->title }}</div>
                            <div class="text-xs text-gray-500">{{ $item->category }}</div>
                            <div class="text-sm font-semibold text-blue-600 mt-0.5">{{ $item->priceLabel() }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
            @if ($total)
                <p class="text-sm text-gray-500 mt-4">Covering the whole wishlist: <span class="font-semibold text-gray-900">{{ $total }}</span></p>
            @endif
        @endif

        {{-- Actions --}}
        <div class="flex flex-wrap gap-3 mt-10">
            <a href="{{ url('/donate?person=' . $person->id) }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-7 py-3 rounded-full shadow-md transition hover:scale-105">
                Support {{ $first }}
            </a>
            <a href="{{ route('people.index') }}" class="border border-gray-300 hover:border-gray-900 font-medium px-7 py-3 rounded-full transition">Back to all people</a>
        </div>
    </div>
</section>

@if ($related->isNotEmpty())
<section class="bg-gray-50 py-14">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">More {{ strtolower($person->category) }} who need help</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ($related as $other)
                <a href="{{ route('people.show', $other) }}" class="group block">
                    <div class="rounded-xl overflow-hidden bg-gray-100 aspect-[4/5]">
                        <img src="{{ $other->imageUrl() }}" alt="{{ $other->name }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <div class="mt-2 text-sm font-semibold text-gray-900 truncate">{{ $other->name }}</div>
                    <div class="text-xs text-gray-500">From {{ $other->locationLabel() }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
