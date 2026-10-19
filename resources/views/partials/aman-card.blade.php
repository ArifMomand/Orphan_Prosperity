@props([
    'name' => 'Name',
    'id' => 'A-0000000',
    'qr' => null,
    'nameId' => null,
    'idId' => null,
])

<div class="relative rounded-3xl bg-gradient-to-br from-blue-600 to-indigo-800 text-white shadow-2xl
            shadow-blue-900/30 p-5 sm:p-8 aspect-[1.6/1] flex flex-col justify-between overflow-hidden">
    <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-white/10"></div>
    <div class="absolute -left-12 -bottom-16 w-56 h-56 rounded-full bg-white/5"></div>

    <div class="relative flex items-center justify-between">
        <div class="flex items-center gap-2 font-bold text-sm sm:text-base">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3zm-1 14-4-4 1.4-1.4L11 13.2l4.6-4.6L17 10l-6 6z"/></svg>
            Orphan Prosperity
        </div>
        <span class="font-semibold text-sm sm:text-base">Aman ID</span>
    </div>

    <div class="relative flex items-end justify-between gap-3">
        <div class="min-w-0">
            <div class="text-[10px] sm:text-xs text-blue-100">Name</div>
            <div @if($nameId) id="{{ $nameId }}" @endif class="text-base sm:text-xl font-semibold truncate">{{ $name }}</div>

            <div class="text-[10px] sm:text-xs text-blue-100 mt-2 sm:mt-3">Aman ID</div>
            <div @if($idId) id="{{ $idId }}" @endif class="text-base sm:text-xl font-semibold tracking-wide font-mono">{{ $id }}</div>
        </div>

        @if ($qr)
            <div class="shrink-0 text-center">
                <img src="{{ $qr }}" alt="QR code for {{ $id }}"
                     class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-white p-1.5">
                <div class="mt-1 text-[9px] sm:text-[10px] text-blue-100 tracking-wide">Scan to give</div>
            </div>
        @endif
    </div>
</div>