@extends('admin.layout')

@section('title', 'Homepage Video')

@section('content')
<div class="max-w-xl bg-white rounded-lg border border-gray-200 p-8">
    <p class="text-gray-600 text-sm mb-6">
        Upload a new video to replace the one currently playing in the homepage hero section.
        It will immediately replace <code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">videos/intro.mp4</code> —
        no code changes needed.
    </p>

    <div class="mb-6 rounded-lg overflow-hidden border border-gray-200 bg-black">
        <video src="{{ asset('videos/intro.mp4') }}" controls class="w-full aspect-video"></video>
        <p class="text-xs text-gray-400 px-3 py-2 bg-gray-50">Current video</p>
    </div>

    <form method="POST" action="{{ route('admin.video.upload') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        @if ($errors->any())
            <div class="px-4 py-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <div>
            <label class="block text-sm text-gray-600 mb-1">New video file (.mp4, up to 100MB)</label>
            <input type="file" name="video" accept="video/mp4" required
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-600 file:font-semibold hover:file:bg-blue-100">
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
            Upload &amp; Replace Video
        </button>
    </form>
</div>
@endsection
