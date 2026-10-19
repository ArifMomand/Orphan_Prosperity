@extends('admin.layout')

@section('title', 'People in Need')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-500 text-sm">{{ $people->count() }} people</p>
    <a href="{{ route('admin.people.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">
        + Add Person
    </a>
</div>

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-3">Person</th>
                <th class="text-left px-5 py-3">Category</th>
                <th class="text-left px-5 py-3">Location</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-right px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($people as $person)
                <tr>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $person->imageUrl() }}" alt="" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                            <span class="font-medium text-gray-900">{{ $person->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $person->category }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $person->locationLabel() }}</td>
                    <td class="px-5 py-3 text-gray-500">
                        {{ $person->is_active ? 'Visible' : 'Hidden' }}{{ $person->is_verified ? ' · Verified' : '' }}
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.people.edit', $person) }}" class="text-blue-600 hover:text-blue-800 font-medium mr-4">Edit</a>
                        <form method="POST" action="{{ route('admin.people.destroy', $person) }}" class="inline" onsubmit="return confirm('Remove this person? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">No people yet. Add your first one.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
