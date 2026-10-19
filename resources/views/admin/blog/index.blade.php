@extends('admin.layout')

@section('title', 'Blog Posts')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-500 text-sm">{{ $posts->count() }} posts</p>
    <a href="{{ route('admin.blog.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">
        + Add Post
    </a>
</div>

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-3">Title</th>
                <th class="text-left px-5 py-3">Published</th>
                <th class="text-right px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($posts as $post)
                <tr>
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $post->title }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $post->published_at?->format('M j, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.blog.edit', $post) }}" class="text-blue-600 hover:text-blue-800 font-medium mr-4">Edit</a>
                        <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" class="inline" onsubmit="return confirm('Delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400">No posts yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
