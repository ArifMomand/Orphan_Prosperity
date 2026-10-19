@extends('layouts.site')

@section('title', 'Send Direct Aid | Orphan Prosperity')

@section('content')
<section class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-600 text-white">
    <div class="max-w-7xl mx-auto px-6 py-14">
        <p class="text-sm text-blue-100 mb-2">
            <a href="{{ url('/') }}" class="hover:text-white">Home</a> / Direct Aid
        </p>
        <h1 class="text-3xl md:text-5xl font-extrabold">Send Direct Aid</h1>
        <p class="mt-3 text-blue-100 max-w-xl">Choose a package and we deliver it to a family who needs it. Open a package to see exactly what is inside.</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-6 py-12">
    <div class="flex flex-wrap items-center gap-2 mb-8">
        <a href="{{ route('aid.index') }}"
           class="px-4 py-2 rounded-full border text-sm font-medium transition {{ $activeCategory ? 'border-gray-200 text-gray-700 hover:border-blue-600' : 'bg-blue-600 border-blue-600 text-white' }}">All</a>
        @foreach ($categories as $cat)
            <a href="{{ route('aid.index', ['category' => $cat]) }}"
               class="px-4 py-2 rounded-full border text-sm font-medium transition {{ $activeCategory === $cat ? 'bg-blue-600 border-blue-600 text-white' : 'border-gray-200 text-gray-700 hover:border-blue-600' }}">{{ $cat }}</a>
        @endforeach
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($packages as $package)
            <a href="{{ route('aid.show', $package) }}" class="group block">
                <div class="rounded-xl overflow-hidden bg-gray-100 aspect-square">
                    <img src="{{ $package->imageUrl() }}" alt="{{ $package->title }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                </div>
                <div class="mt-2 rounded-xl bg-gray-50 p-3">
                    <div class="text-[11px] text-blue-600 font-semibold">{{ $package->category }}</div>
                    <h3 class="text-sm font-semibold text-gray-900 truncate mt-0.5">{{ $package->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $package->priceLabel() }}</p>
                </div>
            </a>
        @empty
            <p class="col-span-full text-center text-gray-400 py-16">No packages found for this filter.</p>
        @endforelse
    </div>

    @include('partials.simple-pager', ['paginator' => $packages])
</section>
@endsection
