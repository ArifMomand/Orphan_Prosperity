@extends('admin.layout')

@section('title', 'Aid Packages')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-500 text-sm">{{ $packages->count() }} packages</p>
    <a href="{{ route('admin.aid-packages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">
        + Add Package
    </a>
</div>

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-3">Package</th>
                <th class="text-left px-5 py-3">Category</th>
                <th class="text-left px-5 py-3">Price</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-right px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($packages as $package)
                <tr>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $package->imageUrl() }}" alt="" class="w-10 h-10 rounded object-cover border border-gray-200">
                            <span class="font-medium text-gray-900">{{ $package->title }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $package->category }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $package->priceLabel() }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $package->is_active ? 'Visible' : 'Hidden' }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.aid-packages.edit', $package) }}" class="text-blue-600 hover:text-blue-800 font-medium mr-4">Edit</a>
                        <form method="POST" action="{{ route('admin.aid-packages.destroy', $package) }}" class="inline" onsubmit="return confirm('Remove this package? It will also leave every wishlist.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">No packages yet. Add your first one.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
