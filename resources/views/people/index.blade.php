@extends('layouts.site')

@section('title', 'People in Need | Orphan Prosperity')

@section('content')
<section class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-600 text-white">
    <div class="max-w-7xl mx-auto px-6 py-14">
        <p class="text-sm text-blue-100 mb-2">
            <a href="{{ url('/') }}" class="hover:text-white">Home</a> / People in Need
        </p>
        <h1 class="text-3xl md:text-5xl font-extrabold">People in Need</h1>
        <p class="mt-3 text-blue-100 max-w-xl">Every person here has been checked by our team. Open a profile to read their story and choose how to help.</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-6 py-12">
    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-2 mb-8">
        <a href="{{ route('people.index', array_filter(['city' => $activeCity])) }}"
           class="px-4 py-2 rounded-full border text-sm font-medium transition {{ $activeCategory ? 'border-gray-200 text-gray-700 hover:border-blue-600' : 'bg-blue-600 border-blue-600 text-white' }}">All</a>
        @foreach ($categories as $cat)
            <a href="{{ route('people.index', array_filter(['category' => $cat, 'city' => $activeCity])) }}"
               class="px-4 py-2 rounded-full border text-sm font-medium transition {{ $activeCategory === $cat ? 'bg-blue-600 border-blue-600 text-white' : 'border-gray-200 text-gray-700 hover:border-blue-600' }}">{{ $cat }}</a>
        @endforeach

        <form method="GET" action="{{ route('people.index') }}" class="ml-auto">
            @if ($activeCategory)<input type="hidden" name="category" value="{{ $activeCategory }}">@endif
            <select name="city" onchange="this.form.submit()" aria-label="Filter by location"
                    class="border border-gray-200 rounded-md text-sm text-gray-600 px-3 py-2 bg-white focus:outline-none focus:border-blue-600">
                <option value="">All locations</option>
                @foreach ($cities as $c)
                    <option value="{{ $c }}" @selected($activeCity === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($people as $person)
            <article class="group">
                <a href="{{ route('people.show', $person) }}" class="block rounded-xl overflow-hidden bg-gray-100 aspect-[4/5]">
                    <img src="{{ $person->imageUrl() }}" alt="{{ $person->name }}" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                </a>
                <div class="mt-2 rounded-xl bg-gray-50 p-3">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-sm font-semibold text-gray-900 truncate">
                            <a href="{{ route('people.show', $person) }}" class="hover:text-blue-600 transition-colors">{{ $person->name }}</a>
                        </h3>
                        @if ($person->is_verified)
                            <svg class="w-4 h-4 shrink-0 text-blue-600" viewBox="0 0 24 24" fill="currentColor" aria-label="Verified"><path d="M12 2l2.4 2 3.1-.2 1 3 2.7 1.6-.9 3 .9 3-2.7 1.6-1 3-3.1-.2L12 22l-2.4-2-3.1.2-1-3L2.8 15.6l.9-3-.9-3L5.5 8l1-3 3.1.2L12 2zm-1.2 13.4l5-5-1.1-1.1-3.9 3.9-1.8-1.8-1.1 1.1 2.9 2.9z"/></svg>
                        @endif
                    </div>
                    <p class="text-[11px] text-gray-500 mt-0.5">{{ $person->category }} · From {{ $person->locationLabel() }} {{ $person->flag() }}</p>
                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center gap-1 min-w-0">
                            <span class="text-[11px] text-gray-500">Wishlist:</span>
                            @foreach ($person->aidPackages->take(3) as $item)
                                <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="w-5 h-5 rounded object-cover border border-gray-200">
                            @endforeach
                        </div>
                        <a href="{{ route('people.show', $person) }}" class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded-md transition">Details &rsaquo;</a>
                    </div>
                </div>
            </article>
        @empty
            <p class="col-span-full text-center text-gray-400 py-16">No people found for this filter.</p>
        @endforelse
    </div>

    @include('partials.simple-pager', ['paginator' => $people])
</section>
@endsection
