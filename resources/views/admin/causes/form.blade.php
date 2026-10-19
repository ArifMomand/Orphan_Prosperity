@extends('admin.layout')

@section('title', $cause->exists ? 'Edit Cause' : 'Add Cause')

@section('content')
<div class="max-w-2xl bg-white rounded-lg border border-gray-200 p-8">
    <form method="POST"
          action="{{ $cause->exists ? route('admin.causes.update', $cause) : route('admin.causes.store') }}"
          enctype="multipart/form-data" class="space-y-5">
        @csrf
        @if ($cause->exists) @method('PUT') @endif

        @if ($errors->any())
            <div class="px-4 py-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label class="block text-sm text-gray-600 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $cause->title) }}" required
                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Category</label>
                <input type="text" name="category" value="{{ old('category', $cause->category) }}" required placeholder="e.g. Water, Education"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Location</label>
                <input type="text" name="location" value="{{ old('location', $cause->location) }}"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Goal ($)</label>
            <input type="number" name="goal" min="1" value="{{ old('goal', $cause->goal) }}" required
                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
        </div>

        {{-- Real totals now come from recorded donations --}}
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
            <div class="text-sm font-semibold text-gray-900 mb-1">Raised so far</div>
            <p class="text-xs text-gray-500 mb-3">
                Calculated automatically from the donations you record on the cause's
                <b>Donations</b> page. Only fill in a starting value if money was raised before you began recording donations.
            </p>

            @if ($cause->exists)
                <div class="flex gap-6 mb-4 text-sm">
                    <div><span class="text-gray-500">Current total:</span> <b>${{ number_format($cause->raised) }}</b></div>
                    <div><span class="text-gray-500">Donors:</span> <b>{{ $cause->donors }}</b></div>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Starting amount ($)</label>
                    <input type="number" name="opening_raised" min="0" value="{{ old('opening_raised', $cause->opening_raised ?? 0) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Starting donor count</label>
                    <input type="number" name="opening_donors" min="0" value="{{ old('opening_donors', $cause->opening_donors ?? 0) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Short summary <span class="text-gray-400">(shown on the causes grid)</span></label>
            <textarea name="summary" rows="2" required
                      class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm resize-none focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">{{ old('summary', $cause->summary) }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Full story <span class="text-gray-400">(shown on the cause's own page — separate paragraphs with a blank line)</span></label>
            <textarea name="story" rows="6" required
                      class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm resize-none focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">{{ old('story', $cause->story) }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Image</label>
            @if ($cause->image_path)
                <img src="{{ asset($cause->image_path) }}" alt="Current image" class="w-40 h-28 object-cover rounded-lg mb-2 border border-gray-200">
            @endif
            <input type="file" name="image" accept="image/*"
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-600 file:font-semibold hover:file:bg-blue-100">
            <p class="text-xs text-gray-400 mt-1">{{ $cause->image_path ? 'Leave blank to keep the current image.' : 'JPG or PNG recommended.' }}</p>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                {{ $cause->exists ? 'Save Changes' : 'Create Cause' }}
            </button>
            <a href="{{ route('admin.causes.index') }}" class="border border-gray-300 hover:border-gray-900 text-sm font-medium px-6 py-2.5 rounded-full transition flex items-center">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection