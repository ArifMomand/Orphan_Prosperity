@extends('admin.layout')
@section('title', 'Aman IDs')

@section('content')
@php
    $badge = [
        'pending'  => 'bg-amber-100 text-amber-700',
        'verified' => 'bg-green-100 text-green-700',
        'rejected' => 'bg-red-100 text-red-700',
    ];
    $tabs = ['pending' => 'Pending', 'verified' => 'Verified', 'rejected' => 'Rejected', 'all' => 'All'];
@endphp

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex gap-2">
        @foreach ($tabs as $key => $label)
            <a href="{{ route('admin.aman-ids.index', ['status' => $key, 'q' => $q]) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium border transition
                      {{ $status === $key ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400' }}">
                {{ $label }} <span class="opacity-70">({{ $counts[$key] }})</span>
            </a>
        @endforeach
    </div>

    <form method="GET" class="flex gap-2">
        <input type="hidden" name="status" value="{{ $status }}">
        <input type="text" name="q" value="{{ $q }}" placeholder="Search name, ID, phone, province"
               class="w-72 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        <button class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium">Search</button>
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-5 py-3 font-medium">Aman ID</th>
                <th class="px-5 py-3 font-medium">Name</th>
                <th class="px-5 py-3 font-medium">Province</th>
                <th class="px-5 py-3 font-medium">Phone</th>
                <th class="px-5 py-3 font-medium">Visibility</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium">Registered</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-gray-900">{{ $item->aman_id }}</td>
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $item->full_name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $item->province }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $item->primary_phone }}</td>
                    <td class="px-5 py-3 text-gray-600 capitalize">{{ $item->visibility }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge[$item->status] }}">{{ ucfirst($item->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $item->created_at->format('M j, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.aman-ids.show', $item) }}" class="text-blue-600 font-medium hover:underline">Review</a>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
    @if ($item->status === 'verified')
        <a href="{{ route('admin.aman-ids.card', $item) }}" target="_blank" class="ml-3 text-gray-600 font-medium hover:underline">Card</a>
    @endif
</td>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400">No Aman IDs found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $items->links() }}</div>
@endsection