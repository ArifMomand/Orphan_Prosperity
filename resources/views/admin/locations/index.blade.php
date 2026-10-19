@extends('admin.layout')

@section('title', 'Project Locations')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-500 text-sm">These show up as dots on the homepage's "Where We Work" map.</p>
    <a href="{{ route('admin.locations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">
        + Add Location
    </a>
</div>

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-3">Name</th>
                <th class="text-left px-5 py-3">Description</th>
                <th class="text-left px-5 py-3">Position (x%, y%)</th>
                <th class="text-right px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($locations as $location)
                <tr>
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $location->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ \Illuminate\Support\Str::limit($location->description, 60) }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $location->x_percent }}%, {{ $location->y_percent }}%</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.locations.edit', $location) }}" class="text-blue-600 hover:text-blue-800 font-medium mr-4">Edit</a>
                        <form method="POST" action="{{ route('admin.locations.destroy', $location) }}" class="inline" onsubmit="return confirm('Remove this location?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">No locations yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
