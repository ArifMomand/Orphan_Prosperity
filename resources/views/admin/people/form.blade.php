@extends('admin.layout')

@section('title', $person->exists ? 'Edit Person' : 'Add Person')

@section('content')
@php
    $input = 'w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition';
    $checked = old('aid_package_ids', $selectedPackages);
@endphp
<div class="max-w-2xl bg-white rounded-lg border border-gray-200 p-8">
    <form method="POST"
          action="{{ $person->exists ? route('admin.people.update', $person) : route('admin.people.store') }}"
          enctype="multipart/form-data" class="space-y-5">
        @csrf
        @if ($person->exists) @method('PUT') @endif

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
            <label class="block text-sm text-gray-600 mb-1">Full name</label>
            <input type="text" name="name" value="{{ old('name', $person->name) }}" required class="{{ $input }}">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Category</label>
                <select name="category" required class="{{ $input }}">
                    @foreach (\App\Models\NeedyPerson::CATEGORIES as $cat)
                        <option value="{{ $cat }}" @selected(old('category', $person->category) === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">City</label>
                <input type="text" name="city" value="{{ old('city', $person->city) }}" placeholder="e.g. Kunduz" class="{{ $input }}">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Country</label>
                <input type="text" name="country" value="{{ old('country', $person->country) }}" required class="{{ $input }}">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Code <span class="text-gray-400">(flag)</span></label>
                <input type="text" name="country_code" maxlength="2" value="{{ old('country_code', $person->country_code) }}" required class="{{ $input }} uppercase">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Age <span class="text-gray-400">(optional)</span></label>
                <input type="number" name="age" min="0" max="120" value="{{ old('age', $person->age) }}" class="{{ $input }}">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Family members <span class="text-gray-400">(optional)</span></label>
                <input type="number" name="family_size" min="1" max="50" value="{{ old('family_size', $person->family_size) }}" class="{{ $input }}">
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Story <span class="text-gray-400">(shown on the person's own page. Separate paragraphs with a blank line)</span></label>
            <textarea name="story" rows="6" class="{{ $input }} resize-none">{{ old('story', $person->story) }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Photo</label>
            @if ($person->image_path)
                <img src="{{ asset($person->image_path) }}" alt="Current photo" class="w-28 h-36 object-cover rounded-lg mb-2 border border-gray-200">
            @endif
            <input type="file" name="image" accept="image/*"
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-600 file:font-semibold hover:file:bg-blue-100">
            <p class="text-xs text-gray-400 mt-1">{{ $person->image_path ? 'Leave blank to keep the current photo.' : 'Portrait photo works best (4:5).' }}</p>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-2">Wishlist <span class="text-gray-400">(aid packages this person needs)</span></label>
            <div class="grid grid-cols-2 gap-2 max-h-52 overflow-y-auto border border-gray-200 rounded-lg p-3">
                @forelse ($packages as $pkg)
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="aid_package_ids[]" value="{{ $pkg->id }}" @checked(in_array($pkg->id, $checked)) class="rounded border-gray-300 text-blue-600">
                        <span class="truncate">{{ $pkg->title }}</span>
                    </label>
                @empty
                    <p class="text-sm text-gray-400 col-span-2">Add aid packages first.</p>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Sort order</label>
                <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $person->sort_order ?? 0) }}" class="{{ $input }}">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700 pb-2.5">
                <input type="checkbox" name="is_verified" value="1" @checked(old('is_verified', $person->is_verified)) class="rounded border-gray-300 text-blue-600"> Verified
            </label>
            <label class="flex items-center gap-2 text-sm text-gray-700 pb-2.5">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $person->is_active)) class="rounded border-gray-300 text-blue-600"> Show on site
            </label>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                {{ $person->exists ? 'Save Changes' : 'Add Person' }}
            </button>
            <a href="{{ route('admin.people.index') }}" class="border border-gray-300 hover:border-gray-900 text-sm font-medium px-6 py-2.5 rounded-full transition flex items-center">Cancel</a>
        </div>
    </form>
</div>
@endsection
