@extends('admin.layout')

@section('title', 'Campaigns')

@section('content')
@if (session('success'))
    <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-200">{{ session('success') }}</div>
@endif

<div class="flex justify-between items-center mb-6">
    <p class="text-gray-500 text-sm">
        {{ $campaigns->total() }} campaigns &middot; {{ $activeCount }} showing on the home page
    </p>
    <a href="{{ route('admin.campaigns.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">
        + Add Campaigns
    </a>
</div>

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-3">Picture</th>
                <th class="text-left px-5 py-3">Title / Video</th>
                <th class="text-left px-5 py-3">Priority</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-right px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($campaigns as $campaign)
                @php $videoId = $campaign->youtubeId(); @endphp
                <tr>
                    <td class="px-5 py-3">
                        <img src="{{ $campaign->imageUrl() }}" alt="" class="w-14 h-[4.5rem] object-cover rounded-lg border border-gray-200">
                    </td>
                    <td class="px-5 py-3">
                        <div class="font-medium text-gray-900">{{ $campaign->title ?: 'Untitled' }}</div>
                        @if ($videoId)
                            <a href="https://www.youtube.com/watch?v={{ $videoId }}" target="_blank" rel="noopener"
                               class="text-xs text-blue-600 hover:text-blue-800">&#9654; Check video on YouTube</a>
                        @else
                            <span class="text-xs text-red-600">Invalid YouTube link</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $campaign->sort_order }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $campaign->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $campaign->is_active ? 'Showing' : 'Hidden' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <form method="POST" action="{{ route('admin.campaigns.toggle', $campaign) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-orange-600 hover:text-orange-800 font-medium mr-3">
                                {{ $campaign->is_active ? 'Hide' : 'Show' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="text-blue-600 hover:text-blue-800 font-medium mr-3">Edit</a>
                        <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}" class="inline" onsubmit="return confirm('Delete this campaign? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">No campaigns yet. Add your first ones.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $campaigns->links() }}</div>
@endsection