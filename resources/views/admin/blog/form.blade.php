@extends('admin.layout')

@section('title', $post->exists ? 'Edit Post' : 'Add Post')

@section('content')
<div class="max-w-2xl bg-white rounded-lg border border-gray-200 p-8">
    <form method="POST"
          action="{{ $post->exists ? route('admin.blog.update', $post) : route('admin.blog.store') }}"
          enctype="multipart/form-data" class="space-y-5">
        @csrf
        @if ($post->exists) @method('PUT') @endif

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
            <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Published date</label>
            <input type="date" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d')) }}"
                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Short excerpt <span class="text-gray-400">(shown on the homepage card)</span></label>
            <textarea name="excerpt" rows="2" required
                      class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm resize-none focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Full post body <span class="text-gray-400">(optional, for a future full post page)</span></label>
            <textarea name="body" rows="6"
                      class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm resize-none focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">{{ old('body', $post->body) }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Image</label>
            @if ($post->image_path)
                <img src="{{ asset($post->image_path) }}" alt="Current image" class="w-40 h-28 object-cover rounded-lg mb-2 border border-gray-200">
            @endif
            <input type="file" name="image" accept="image/*"
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-600 file:font-semibold hover:file:bg-blue-100">
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                {{ $post->exists ? 'Save Changes' : 'Create Post' }}
            </button>
            <a href="{{ route('admin.blog.index') }}" class="border border-gray-300 hover:border-gray-900 text-sm font-medium px-6 py-2.5 rounded-full transition flex items-center">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
