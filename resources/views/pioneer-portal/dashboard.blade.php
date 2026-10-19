@extends('pioneer-portal.layout')
@section('title', 'Pioneer Dashboard')

@section('content')
@php $status = $application?->status; @endphp

<div class="max-w-3xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Welcome, {{ $pioneer->name }}</h1>
    <p class="text-gray-500 text-sm mb-8">Manage the causes you share with the world.</p>

    {{-- Verification status --}}
    @if (! $application)
        <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-5 mb-8 text-sm">
            We couldn't find a volunteer application for <strong>{{ $pioneer->email }}</strong>.
            <a href="{{ url('/pioneer') }}" class="underline font-semibold">Apply as a Pioneer</a> using this same email so the admin can verify you.
        </div>
    @elseif ($status === 'pending')
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl p-5 mb-8 text-sm">
            <strong>Verification pending.</strong> The admin is reviewing your application. You'll be able to add causes as soon as it's approved.
        </div>
    @elseif ($status === 'rejected')
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-5 mb-8 text-sm">
            <strong>Application not approved.</strong> Please contact us if you think this is a mistake.
        </div>
    @else
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-5 mb-8 text-sm">
            <strong>You're verified.</strong> You can add causes below.
        </div>
    @endif

    {{-- Add cause form (verified only) --}}
    @if ($status === 'verified')
        <form method="POST" action="{{ route('pioneer.causes.store') }}" enctype="multipart/form-data"
              class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8 mb-10">
            @csrf
            <h2 class="font-bold text-xl text-gray-900 mb-6">Add a new cause</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4 mb-5">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                <input type="text" name="orphan_name" value="{{ old('orphan_name') }}" placeholder="Orphan's name (or group name)" required
                       class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Cause title" required
                       class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">

                <div class="grid grid-cols-2 gap-4">
                    <select name="category" required
                            class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-600 focus:outline-none focus:border-blue-600">
                        <option value="" disabled selected>Category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="location" value="{{ old('location') }}" placeholder="Location" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>

                <input type="text" name="summary" value="{{ old('summary') }}" maxlength="300" placeholder="Short summary (shown on the card)" required
                       class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">

                <textarea name="story" rows="7" placeholder="Full story (separate paragraphs with a blank line)" required
                          class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm resize-none focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">{{ old('story') }}</textarea>

                <input type="number" name="goal" value="{{ old('goal') }}" min="1" placeholder="Funding goal (USD)" required
                       class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">

                <div>
                    <label class="text-sm text-gray-600 block mb-2">Photo (JPG, PNG or WebP, max 4 MB)</label>
                    <input type="file" name="image" accept="image/*" required
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-600 file:font-semibold hover:file:bg-blue-100">
                </div>
            </div>

            <button type="submit"
                    class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-full transition">
                Publish Cause
            </button>
        </form>
    @endif

    {{-- My causes --}}
    <h2 class="font-bold text-xl text-gray-900 mb-4">My causes</h2>
    <div class="space-y-3">
        @forelse ($causes as $cause)
    @php
        $badge = [
            'pending'  => 'bg-yellow-100 text-yellow-700',
            'approved' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-red-100 text-red-700',
        ][$cause->status] ?? 'bg-gray-100 text-gray-500';
    @endphp
    <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-4">
        <img src="{{ $cause->imageUrl() }}" alt="" class="w-16 h-16 rounded-lg object-cover shrink-0">
        <div class="flex-1 min-w-0">
            <div class="font-semibold text-gray-900 truncate">{{ $cause->title }}</div>
            <div class="text-xs text-gray-400">{{ $cause->orphan_name }} &middot; {{ $cause->category }}</div>
        </div>
        <span class="text-xs font-semibold px-2.5 py-1 rounded-full shrink-0 {{ $badge }}">{{ ucfirst($cause->status) }}</span>
        @if ($cause->status === 'approved')
            <a href="{{ url('/causes/' . $cause->slug) }}" class="text-sm text-blue-600 font-medium shrink-0">View</a>
        @endif
    </div>
@empty
    <p class="text-sm text-gray-400">You haven't added any causes yet.</p>
@endforelse
    </div>
</div>
@endsection