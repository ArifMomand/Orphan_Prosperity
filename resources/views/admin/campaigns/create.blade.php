@extends('admin.layout')

@section('title', 'Add Campaigns')

@section('content')
<div class="max-w-3xl bg-white rounded-lg border border-gray-200 p-8">
    <p class="text-sm text-gray-500 mb-6">
        Choose a picture and paste its YouTube link. Visitors tap the picture and the video plays on your website.
        Add as many as you need (up to 10 at a time).
    </p>

    <form method="POST" action="{{ route('admin.campaigns.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        @if ($errors->any())
            <div class="px-4 py-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="rows" class="space-y-4"></div>

        <button type="button" id="addRow"
                class="border border-dashed border-blue-400 text-blue-600 hover:bg-blue-50 text-sm font-semibold px-5 py-2.5 rounded-full transition">
            + Add another picture
        </button>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                Publish Campaigns
            </button>
            <a href="{{ route('admin.campaigns.index') }}" class="border border-gray-300 hover:border-gray-900 text-sm font-medium px-6 py-2.5 rounded-full transition flex items-center">
                Cancel
            </a>
        </div>
    </form>
</div>

{{-- One campaign row (copied by the script below) --}}
<template id="rowTemplate">
    <div class="campaign-row border border-gray-200 rounded-xl p-4 bg-gray-50">
        <div class="flex items-center justify-between mb-3">
            <span class="row-label text-sm font-semibold text-gray-700">Campaign</span>
            <button type="button" class="remove-row text-sm text-red-600 hover:text-red-800 font-medium">Remove</button>
        </div>

        <div class="flex gap-4">
            <label class="shrink-0 cursor-pointer">
                <div class="w-28 h-36 rounded-lg border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden text-gray-400 text-xs text-center hover:border-blue-400 transition">
                    <span class="preview-text px-2">Click to choose picture</span>
                    <img class="preview-img hidden w-full h-full object-cover" alt="">
                </div>
                <input type="file" name="items[__I__][image]" accept="image/*" required class="image-input sr-only">
            </label>

            <div class="flex-1 space-y-3">
                <input type="text" name="items[__I__][video_url]" required
                       placeholder="YouTube link (youtu.be/... or youtube.com/watch?v=...)"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                <input type="text" name="items[__I__][title]" maxlength="120"
                       placeholder="Title (optional)"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
            </div>
        </div>
    </div>
</template>

<script>
    (function () {
        const rows   = document.getElementById('rows');
        const tpl    = document.getElementById('rowTemplate').innerHTML;
        const addBtn = document.getElementById('addRow');
        const MAX    = 10;
        let counter  = 0;

        function refresh() {
            const all = rows.querySelectorAll('.campaign-row');
            all.forEach(function (row, i) {
                row.querySelector('.row-label').textContent = 'Campaign ' + (i + 1);
                row.querySelector('.remove-row').classList.toggle('hidden', all.length === 1);
            });
            addBtn.disabled = all.length >= MAX;
            addBtn.classList.toggle('opacity-50', all.length >= MAX);
        }

        function addRow() {
            if (rows.querySelectorAll('.campaign-row').length >= MAX) return;

            const wrap = document.createElement('div');
            wrap.innerHTML = tpl.split('__I__').join(counter++).trim();
            const row = wrap.firstElementChild;

            row.querySelector('.remove-row').addEventListener('click', function () {
                row.remove();
                refresh();
            });

            const input = row.querySelector('.image-input');
            input.addEventListener('change', function () {
                const img  = row.querySelector('.preview-img');
                const text = row.querySelector('.preview-text');
                const file = input.files[0];

                if (!file) {
                    img.classList.add('hidden');
                    text.classList.remove('hidden');
                    return;
                }
                img.src = URL.createObjectURL(file);
                img.classList.remove('hidden');
                text.classList.add('hidden');
            });

            rows.appendChild(row);
            refresh();
        }

        addBtn.addEventListener('click', addRow);

        // Start with one row. Click "+ Add another picture" for more.
addRow();
    })();
</script>
@endsection