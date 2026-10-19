@extends('admin.layout')

@section('title', 'Edit Campaign')

@section('content')
<div class="max-w-2xl bg-white rounded-lg border border-gray-200 p-8">
    <form method="POST" action="{{ route('admin.campaigns.update', $campaign) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

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
            <label class="block text-sm text-gray-600 mb-1">Title <span class="text-gray-400">(optional)</span></label>
            <input type="text" name="title" value="{{ old('title', $campaign->title) }}" maxlength="120"
                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">YouTube link</label>
            <input type="text" name="video_url" value="{{ old('video_url', $campaign->video_url) }}" required
                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Picture</label>
            <img src="{{ $campaign->imageUrl() }}" alt="Current picture" class="w-32 h-40 object-cover rounded-lg mb-2 border border-gray-200">
            <input type="file" name="image" accept="image/*"
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-600 file:font-semibold hover:file:bg-blue-100">
            <p class="text-xs text-gray-400 mt-1">Leave blank to keep the current picture.</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Priority <span class="text-gray-400">(higher shows first)</span></label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $campaign->sort_order) }}"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
            </div>
            <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="is_active" value="1" class="accent-blue-600"
                           @checked(old('is_active', $campaign->is_active))>
                    Show on the home page
                </label>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                Save Changes
            </button>
            <a href="{{ route('admin.campaigns.index') }}" class="border border-gray-300 hover:border-gray-900 text-sm font-medium px-6 py-2.5 rounded-full transition flex items-center">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection