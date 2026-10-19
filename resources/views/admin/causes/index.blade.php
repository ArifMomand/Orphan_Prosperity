@extends('admin.layout')

@section('title', 'Causes')

@section('content')
@if (session('success'))
    <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-200">{{ session('success') }}</div>
@endif

<div class="flex justify-between items-center mb-6">
    <p class="text-gray-500 text-sm">
        {{ $causes->count() }} causes
        @php $pendingCount = $causes->where('status', 'pending')->count(); @endphp
        @if ($pendingCount)
            &middot; <span class="text-yellow-600 font-medium">{{ $pendingCount }} waiting for approval</span>
        @endif
    </p>
    <a href="{{ route('admin.causes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">
        + Add Cause
    </a>
</div>

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-3">Title</th>
                <th class="text-left px-5 py-3">Category</th>
                <th class="text-left px-5 py-3">Submitted by</th>
                <th class="text-left px-5 py-3">Progress</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-right px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @php
                $statusColors = [
                    'pending'  => 'bg-yellow-100 text-yellow-700',
                    'approved' => 'bg-green-100 text-green-700',
                    'rejected' => 'bg-red-100 text-red-700',
                ];
            @endphp
            @forelse ($causes as $cause)
                <tr>
                    <td class="px-5 py-3 font-medium text-gray-900">
                        {{ $cause->title }}
                        @if ($cause->orphan_name)
                            <div class="text-xs text-gray-400 font-normal">{{ $cause->orphan_name }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $cause->category }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $cause->pioneer?->name ?? 'Admin' }}</td>
                    <td class="px-5 py-3 text-gray-500">${{ number_format($cause->raised) }} / ${{ number_format($cause->goal) }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColors[$cause->status] ?? 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($cause->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        @if ($cause->status === 'approved')
                            <a href="{{ route('admin.causes.donations', $cause) }}" class="text-indigo-600 hover:text-indigo-800 font-medium mr-3">Donations</a>
                        @endif
                        @if ($cause->status !== 'approved')
                            <form method="POST" action="{{ route('admin.causes.approve', $cause) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 font-medium mr-3">Approve</button>
                            </form>
                        @endif
                        @if ($cause->status !== 'rejected')
                            <form method="POST" action="{{ route('admin.causes.reject', $cause) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-orange-600 hover:text-orange-800 font-medium mr-3">Reject</button>
                            </form>
                        @endif
                        <a href="{{ route('admin.causes.edit', $cause) }}" class="text-blue-600 hover:text-blue-800 font-medium mr-3">Edit</a>
                        <form method="POST" action="{{ route('admin.causes.destroy', $cause) }}" class="inline" onsubmit="return confirm('Delete this cause? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">No causes yet. Add your first one.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection