@extends('admin.layout')

@section('title', 'Messages')

@section('content')
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-3">From</th>
                <th class="text-left px-5 py-3">Subject</th>
                <th class="text-left px-5 py-3">Received</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-right px-5 py-3">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($messages as $message)
                <tr class="{{ $message->status === 'unread' ? 'bg-blue-50/40' : '' }}">
                    <td class="px-5 py-3">
                        <div class="font-medium text-gray-900">{{ $message->name }}</div>
                        <div class="text-gray-400 text-xs">{{ $message->email }}</div>
                    </td>
                    <td class="px-5 py-3 text-gray-500 capitalize">{{ $message->subject }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $message->created_at->format('M j, Y g:ia') }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $message->status === 'unread' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($message->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.messages.show', $message) }}" class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">No messages yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
