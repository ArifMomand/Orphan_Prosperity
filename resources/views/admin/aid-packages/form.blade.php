@extends('admin.layout')

@section('title', $package->exists ? 'Edit Package' : 'Add Package')

@section('content')
@php
    $input = 'w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition';
@endphp
<div class="max-w-2xl bg-white rounded-lg border border-gray-200 p-8">
    <form method="POST"
          action="{{ $package->exists ? route('admin.aid-packages.update', $package) : route('admin.aid-packages.store') }}"
          enctype="multipart/form-data" class="space-y-5">
        @csrf
        @if ($package->exists) @method('PUT') @endif

        @if ($errors->any())
            <div class="px-4 py-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label class="block text-sm text-gray-600 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $package->title) }}" required class="{{ $input }}">
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Category</label>
                <select name="category" required class="{{ $input }}">
                    @foreach (\App\Models\AidPackage::CATEGORIES as $cat)
                        <option value="{{ $cat }}" @selected(old('category', $package->category) === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Price</label>
                <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $package->price) }}" required class="{{ $input }}">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Currency</label>
                <select name="currency" required class="{{ $input }}">
                    @foreach (\App\Models\AidPackage::CURRENCIES as $code => $symbol)
                        <option value="{{ $code }}" @selected(old('currency', $package->currency) === $code)>{{ $code }} ({{ $symbol }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Description <span class="text-gray-400">(shown on the package's own page)</span></label>
            <textarea name="description" rows="4" class="{{ $input }} resize-none">{{ old('description', $package->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">What's included <span class="text-gray-400">(one item per line)</span></label>
            <textarea name="includes" rows="5" placeholder="10 kg flour&#10;5 L cooking oil&#10;5 kg rice" class="{{ $input }} resize-none">{{ old('includes', $package->includes) }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Image</label>
            @if ($package->image_path)
                <img src="{{ asset($package->image_path) }}" alt="Current image" class="w-32 h-32 object-cover rounded-lg mb-2 border border-gray-200">
            @endif
            <input type="file" name="image" accept="image/*"
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-600 file:font-semibold hover:file:bg-blue-100">
            <p class="text-xs text-gray-400 mt-1">{{ $package->image_path ? 'Leave blank to keep the current image.' : 'Square image works best.' }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 items-end">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Sort order</label>
                <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $package->sort_order ?? 0) }}" class="{{ $input }}">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700 pb-2.5">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->is_active)) class="rounded border-gray-300 text-blue-600"> Show on site
            </label>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                {{ $package->exists ? 'Save Changes' : 'Add Package' }}
            </button>
            <a href="{{ route('admin.aid-packages.index') }}" class="border border-gray-300 hover:border-gray-900 text-sm font-medium px-6 py-2.5 rounded-full transition flex items-center">Cancel</a>
        </div>
    </form>
</div>
@endsection
