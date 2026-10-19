@extends('layouts.app')
@section('title', __('Aman ID — Orphan Prosperity'))

@section('content')

{{-- ================= HERO ================= --}}
<section class="bg-gradient-to-br from-slate-50 to-blue-50 py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight">
                {{ __('The Most Powerful Way to Transform Lives') }}
            </h1>
            <p class="mt-5 text-lg text-gray-600 max-w-xl">
                {{ __('Register a family or individual for an Aman ID and deliver aid directly to them — with complete transparency and verified photo or video confirmation.') }}
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('aman-id.create') }}"
                   class="inline-flex bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700
                          text-white font-semibold px-8 py-4 rounded-2xl shadow-lg shadow-blue-600/25 transition hover:scale-105">
                    {{ __('Register Aman ID') }}
                </a>
                <a href="#find"
                   class="inline-flex border border-gray-300 hover:border-gray-900 font-semibold px-8 py-4 rounded-2xl transition">
                    {{ __('Find an Aman ID') }}
                </a>
            </div>
        </div>

        <div class="max-w-md w-full mx-auto lg:ms-auto">
            @include('partials.aman-card', ['name' => 'Ahmad la***', 'id' => 'A-00371'])
        </div>
    </div>
</section>

{{-- ================= SEARCH ================= --}}
<section id="find" class="max-w-3xl mx-auto px-6 py-16">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">{{ __('Find an Aman ID to support') }}</h2>
        <p class="text-gray-500 mt-2">
            {!! __('Have an Aman ID card or QR code? Search by the Aman ID printed on the card (for example :example) or by name, and give directly.', [
                'example' => '<span class="font-mono" dir="ltr">A-0000003</span>',
            ]) !!}
        </p>
    </div>

    <form method="GET" action="{{ route('aman-id.search') }}#results" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="w-5 h-5 text-gray-400 absolute start-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" name="q" value="{{ $q }}" required maxlength="80"
                   placeholder="{{ __('Aman ID or name') }}"
                   class="w-full rounded-2xl border border-gray-200 bg-white ps-12 pe-4 py-4 text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
        </div>
        <button type="submit"
                class="rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-4 transition">
            {{ __('Search') }}
        </button>
    </form>

    <p class="mt-3 text-xs text-gray-400 text-center">
        {{ __('Private profiles can only be found with their Aman ID. Phone numbers are never shown.') }}
    </p>

    {{-- ================= RESULTS ================= --}}
    @if ($searched)
        <div id="results" class="mt-10 scroll-mt-28">

            @if ($error)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800 text-center">
                    {{ $error }}
                </div>

            @elseif ($results->isEmpty())
                <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center">
                    <div class="text-lg font-semibold text-gray-900">{{ __('No verified Aman ID found') }}</div>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ __('Check the ID on the card and try again. Families who chose a private profile can only be found by their Aman ID, not by name.') }}
                    </p>
                </div>

            @else
                <p class="text-sm text-gray-500 mb-4">
                    {{ __(':count result(s) for', ['count' => $results->count()]) }}
                    <span class="font-semibold text-gray-900">"{{ $q }}"</span>
                </p>

                <div class="space-y-8">
                    @foreach ($results as $r)
                        @php
                            $isPub = $r->visibility === 'public';
                            $name  = $isPub ? $r->full_name : $r->masked_name;
                        @endphp
                        <div class="bg-white rounded-3xl border border-gray-200 p-5 sm:p-6">
                            <div class="max-w-md mx-auto">
                                @include('partials.aman-card', ['name' => $name, 'id' => $r->aman_id, 'qr' => $r->qr_data_uri])
                            </div>

                            <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $r->province }}, {{ $r->country }}</span>
                                <span class="inline-flex items-center gap-1 text-green-700 font-medium">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0z"/></svg>
                                    {{ __('Verified') }}
                                </span>
                            </div>

                            <div class="mt-4 grid sm:grid-cols-2 gap-3">
                                <a href="{{ route('aman-id.show', $r) }}"
                                   class="text-center rounded-2xl border border-gray-300 hover:border-gray-900 font-semibold py-3 transition">
                                    {{ __('View profile') }}
                                </a>
                                <a href="{{ url('/donate') . '?' . http_build_query(['aman_id' => $r->aman_id]) }}"
                                   class="text-center rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700
                                          text-white font-semibold py-3 shadow-lg shadow-blue-600/25 transition">
                                    {{ __('Donate') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</section>

{{-- ================= HOW IT WORKS ================= --}}
<section class="max-w-7xl mx-auto px-6 pb-16">
    <h2 class="text-3xl font-bold text-gray-900 text-center mb-10">{{ __('How it works') }}</h2>
    <div class="grid md:grid-cols-3 gap-8">
        @foreach ([
            ['1', 'Register', 'Add the person or family you want to help, their situation and their needs.'],
            ['2', 'Get verified', 'Our team contacts the family and verifies the information and consent.'],
            ['3', 'Deliver aid', 'Donors give directly to the Aman ID and receive photo or video confirmation.'],
        ] as [$n, $title, $text])
            <div class="rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center mb-4">{{ $n }}</div>
                <h3 class="font-semibold text-lg text-gray-900 mb-1">{{ __($title) }}</h3>
                <p class="text-sm text-gray-500">{{ __($text) }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection