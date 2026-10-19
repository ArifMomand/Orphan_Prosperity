@extends('admin.layout')

@section('title', 'Pioneer Applications')

@section('content')
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-3">Name</th>
                <th class="text-left px-5 py-3">City / District</th>
                <th class="text-left px-5 py-3">Applied</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-right px-5 py-3">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($applications as $application)
                <tr>
                    <td class="px-5 py-3">
                        <div class="font-medium text-gray-900">{{ $application->full_name }}</div>
                        <div class="text-gray-400 text-xs">{{ $application->email }}</div>
                    </td>
                    <td class="px-5 py-3 text-gray-500">
                        <div>{{ $application->city }}</div>
                        <div class="text-gray-400 text-xs">{{ $application->district ?: '—' }}</div>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $application->created_at->format('M j, Y') }}</td>
                    <td class="px-5 py-3">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'verified' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.pioneers.show', $application) }}" class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">No applications yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection