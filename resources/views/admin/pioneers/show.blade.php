@extends('admin.layout')

@section('title', 'Application: ' . $application->full_name)

@section('content')
<a href="{{ route('admin.pioneers.index') }}" class="text-sm text-blue-600 hover:text-blue-800 mb-6 inline-block">&larr; Back to applications</a>

<div class="max-w-2xl bg-white rounded-lg border border-gray-200 p-8">
    <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-100">
        <div>
            <div class="text-xs text-gray-400 mb-1">Full name</div>
            <div class="font-medium text-gray-900">{{ $application->full_name }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-400 mb-1">Email</div>
            <a href="mailto:{{ $application->email }}" class="font-medium text-blue-600">{{ $application->email }}</a>
        </div>
        <div>
            <div class="text-xs text-gray-400 mb-1">Phone</div>
            <div class="font-medium text-gray-900">{{ $application->phone ?: '—' }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-400 mb-1">City</div>
            <div class="font-medium text-gray-900">{{ $application->city }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-400 mb-1">District</div>
            <div class="font-medium text-gray-900">{{ $application->district ?: '—' }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-400 mb-1">Experience</div>
            <div class="font-medium text-gray-900 capitalize">{{ $application->experience }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-400 mb-1">Heard from</div>
            <div class="font-medium text-gray-900 capitalize">{{ $application->heard_from ?: '—' }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-400 mb-1">Availability</div>
            <div class="font-medium text-gray-900">{{ collect($application->availability)->implode(', ') ?: '—' }}</div>
        </div>
        <div class="col-span-2">
            <div class="text-xs text-gray-400 mb-1">Skills</div>
            <div class="font-medium text-gray-900">{{ collect($application->skills)->implode(', ') ?: '—' }}</div>
        </div>
    </div>

    <div class="mb-6">
        <div class="text-xs text-gray-400 mb-1">Why they want to volunteer</div>
        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $application->motivation }}</p>
    </div>

    <form method="POST" action="{{ route('admin.pioneers.status', $application) }}" class="flex items-center gap-3 pt-6 border-t border-gray-100">
        @csrf
        <label class="text-sm text-gray-600">Status:</label>
        <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-600">
            <option value="pending" @selected($application->status === 'pending')>Pending</option>
            <option value="verified" @selected($application->status === 'verified')>Verified</option>
            <option value="rejected" @selected($application->status === 'rejected')>Rejected</option>
        </select>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-full transition">
            Update
        </button>
    </form>
</div>
@endsection