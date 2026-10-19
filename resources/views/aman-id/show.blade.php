@extends('layouts.app')
@section('title', 'Aman ID ' . $amanId->aman_id)

@push('head')
    {{-- Keep beneficiary pages out of Google --}}
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
@php
    $justRegistered = $justRegistered ?? false;
    $isPublic = $isPublic ?? ($amanId->visibility === 'public');

    $displayName = $isPublic ? $amanId->full_name : $amanId->masked_name;

    $donateUrl = function (array $extra = []) use ($amanId) {
        return url('/donate') . '?' . http_build_query(array_merge(['aman_id' => $amanId->aman_id], $extra));
    };

    $statusStyle = [
        'pending'  => 'bg-amber-100 text-amber-700',
        'verified' => 'bg-green-100 text-green-700',
        'rejected' => 'bg-red-100 text-red-700',
    ];
@endphp

<section class="bg-gray-50 py-12 sm:py-16">
    <div class="max-w-3xl mx-auto px-6">

    {{-- =====================================================
         A) JUST REGISTERED (shown once, to the person who registered)
    ====================================================== --}}
    @if ($justRegistered)
        <div class="text-center">
            <div class="w-14 h-14 mx-auto rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Aman ID created</h1>
            <p class="text-gray-500 mt-2">
                Your registration was received. Our team will contact the family to verify the details.
            </p>

            <div class="mt-8 text-left max-w-md mx-auto">
                @include('partials.aman-card', ['name' => $amanId->masked_name, 'id' => $amanId->aman_id])
            </div>

            <div class="mt-6 inline-flex rounded-full px-4 py-1.5 text-sm font-medium {{ $statusStyle[$amanId->status] }}">
                Status: {{ ucfirst($amanId->status) }}
            </div>

            <p class="mt-4 text-sm text-gray-500">
                Save this page link. Once the Aman ID is verified, anyone who scans its QR code can see it.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('aman-id.create') }}" class="rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 transition">Register another</a>
                <a href="{{ url('/') }}" class="rounded-2xl border border-gray-300 hover:border-gray-900 font-semibold px-6 py-3 transition">Back to home</a>
            </div>
        </div>

    {{-- =====================================================
         B) NOT VERIFIED (public sees nothing about the person)
    ====================================================== --}}
    @elseif ($amanId->status !== 'verified')
        <div class="text-center bg-white rounded-3xl border border-gray-200 p-10">
            <div class="w-14 h-14 mx-auto rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">This Aman ID is not verified</h1>
            <p class="text-gray-500 mt-2">
                This registration has not been approved by Orphan Prosperity, so its details and donations are not available.
            </p>
            <a href="{{ url('/') }}" class="inline-flex mt-6 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 transition">Back to home</a>
        </div>

    {{-- =====================================================
         C) VERIFIED PUBLIC PROFILE (QR scan lands here)
    ====================================================== --}}
    @else
        <div class="text-center mb-8">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 text-green-700 text-sm font-semibold px-4 py-1.5">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0z"/></svg>
                Verified by Orphan Prosperity
            </span>
        </div>

        <div class="max-w-md mx-auto">
@include('partials.aman-card', ['name' => $displayName, 'id' => $amanId->aman_id, 'qr' => $amanId->qr_data_uri])        </div>

        {{-- Details --}}
        <div class="mt-8 bg-white rounded-3xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-900 mb-2">About</h2>
            <dl class="text-sm divide-y divide-gray-100">
                <div class="flex justify-between py-3"><dt class="text-gray-500">Aman ID</dt><dd class="font-mono font-medium">{{ $amanId->aman_id }}</dd></div>
                <div class="flex justify-between py-3"><dt class="text-gray-500">Location</dt><dd>{{ $amanId->province }}, {{ $amanId->country }}</dd></div>
                <div class="flex justify-between py-3"><dt class="text-gray-500">Verified on</dt><dd>{{ optional($amanId->reviewed_at)->format('M j, Y') }}</dd></div>
            </dl>

            @if ($isPublic && !empty($amanId->categories))
                <h3 class="font-semibold text-gray-900 mt-5 mb-2">Situation</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($amanId->categories as $cat)
                        <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-sm">{{ \App\Models\AmanId::CATEGORIES[$cat] ?? $cat }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Needs + donate --}}
        <div class="mt-6 bg-white rounded-3xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-900">How you can help</h2>
            <p class="text-sm text-gray-500 mb-4">Choose a need below to donate directly to this Aman ID.</p>

            <div class="space-y-3">
                @foreach ($amanId->needs ?? [] as $slug)
                    @php [$label, $group] = \App\Models\AmanId::NEEDS[$slug] ?? [$slug, '']; @endphp
                    <div class="flex items-center justify-between gap-3 rounded-2xl border border-gray-200 px-4 py-3">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $label }}</div>
                            <div class="text-xs text-gray-400">{{ $group }}</div>
                        </div>
                        <a href="{{ $donateUrl(['need' => $slug]) }}"
                           class="shrink-0 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 transition">
                            Donate
                        </a>
                    </div>
                @endforeach

                @if ($amanId->cash_need)
                    <div class="flex items-center justify-between gap-3 rounded-2xl border border-gray-200 px-4 py-3">
                        <div>
                            <div class="text-sm font-medium text-gray-900">Direct cash support</div>
                            <div class="text-xs text-gray-400">Requested: ${{ number_format($amanId->cash_amount, 0) }}</div>
                        </div>
                        <a href="{{ $donateUrl(['need' => 'cash']) }}"
                           class="shrink-0 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 transition">
                            Donate
                        </a>
                    </div>
                @endif

                @if (empty($amanId->needs) && !$amanId->cash_need)
                    <p class="text-sm text-gray-400">No specific needs have been listed.</p>
                @endif
            </div>

            <a href="{{ $donateUrl() }}"
               class="mt-5 block text-center rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700
                      text-white font-semibold py-3.5 shadow-lg shadow-blue-600/25 transition">
                Donate to this Aman ID
            </a>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400">
            Phone numbers and private details are never shown. Donations are delivered by Orphan Prosperity with confirmation.
        </p>
    @endif

    </div>
</section>
@endsection