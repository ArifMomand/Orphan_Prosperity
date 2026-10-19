@extends('admin.layout')

@section('title', 'Gallery')

@section('content')
<div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="flex items-end gap-4">
        @csrf
        <div class="flex-1">
            <label class="block text-sm text-gray-600 mb-1">Add an image</label>
            <input type="file" name="image" accept="image/*" required
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-600 file:font-semibold hover:file:bg-blue-100">
        </div>
        <div class="flex-1">
            <label class="block text-sm text-gray-600 mb-1">Caption (optional)</label>
            <input type="text" name="caption"
                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-full transition">
            Upload
        </button>
    </form>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @forelse ($images as $image)
        <div class="relative group rounded-lg overflow-hidden border border-gray-200">
            <img src="{{ $image->imageUrl() }}" alt="{{ $image->caption }}" class="w-full h-40 object-cover">
            <form method="POST" action="{{ route('admin.gallery.destroy', $image) }}"
                  class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors flex items-center justify-center"
                  onsubmit="return confirm('Remove this image?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="opacity-0 group-hover:opacity-100 transition bg-red-600 text-white text-xs font-semibold px-4 py-2 rounded-full">
                    Delete
                </button>
            </form>
        </div>
    @empty
        <p class="text-gray-400 col-span-4 text-center py-10">No images uploaded yet.</p>
    @endforelse
</div>
@endsection
