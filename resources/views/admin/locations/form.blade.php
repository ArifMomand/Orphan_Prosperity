@extends('admin.layout')

@section('title', $location->exists ? 'Edit Location' : 'Add Location')

@section('content')
<div class="grid lg:grid-cols-[1fr_320px] gap-8 max-w-4xl">
    <div class="bg-white rounded-lg border border-gray-200 p-8">
        <form method="POST"
              action="{{ $location->exists ? route('admin.locations.update', $location) : route('admin.locations.store') }}"
              class="space-y-5">
            @csrf
            @if ($location->exists) @method('PUT') @endif

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
                <label class="block text-sm text-gray-600 mb-1">City / region name</label>
                <input type="text" name="name" value="{{ old('name', $location->name) }}" required placeholder="e.g. Kandahar"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Description <span class="text-gray-400">(shown when a visitor clicks the dot)</span></label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm resize-none focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">{{ old('description', $location->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Horizontal position (0–100)</label>
                    <input type="number" step="0.1" min="0" max="100" name="x_percent" id="xInput"
                           value="{{ old('x_percent', $location->x_percent) }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Vertical position (0–100)</label>
                    <input type="number" step="0.1" min="0" max="100" name="y_percent" id="yInput"
                           value="{{ old('y_percent', $location->y_percent) }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                </div>
            </div>
            <p class="text-xs text-gray-400">0 is top/left, 100 is bottom/right. Use the preview on the right to fine-tune — Kabul is roughly 58, 42.</p>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                    {{ $location->exists ? 'Save Changes' : 'Add Location' }}
                </button>
                <a href="{{ route('admin.locations.index') }}" class="border border-gray-300 hover:border-gray-900 text-sm font-medium px-6 py-2.5 rounded-full transition flex items-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Live preview --}}
    <div class="bg-gray-900 rounded-lg p-4">
        <p class="text-xs text-gray-400 mb-3">Live preview</p>
        <div class="relative w-full aspect-[10/8]" id="mapPreview">
            <svg viewBox="0 0 100 100" class="w-full h-full">
                <path d="M10,64 L8,50 L14,37 L26,25 L40,15 L50,10 L56,6 L64,4 L76,3 L86,2 L92,6 L88,11 L80,13 L74,17 L78,21 L85,24 L90,30 L92,38 L88,47 L80,52 L84,60 L76,68 L64,73 L50,72 L38,68 L28,63 L18,58 L12,60 Z"
                      fill="#1e3a5f" stroke="#3b82f6" stroke-width="0.8" stroke-linejoin="round"/>
            </svg>
            <div id="previewDot" class="absolute w-3 h-3 -ml-1.5 -mt-1.5 rounded-full bg-blue-400 ring-4 ring-blue-400/30" style="left:50%; top:50%;"></div>
        </div>
    </div>
</div>

<script>
    const xInput = document.getElementById('xInput');
    const yInput = document.getElementById('yInput');
    const dot = document.getElementById('previewDot');

    function updateDot() {
        const x = parseFloat(xInput.value) || 0;
        const y = parseFloat(yInput.value) || 0;
        dot.style.left = x + '%';
        dot.style.top = y + '%';
    }

    xInput.addEventListener('input', updateDot);
    yInput.addEventListener('input', updateDot);
    updateDot();
</script>
@endsection