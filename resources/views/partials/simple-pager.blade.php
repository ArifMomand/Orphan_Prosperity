@if ($paginator->hasPages())
    <div class="flex items-center justify-center gap-4 mt-12 text-sm">
        @if ($paginator->onFirstPage())
            <span class="px-5 py-2.5 rounded-full border border-gray-200 text-gray-300">&larr; Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-5 py-2.5 rounded-full border border-gray-300 hover:border-gray-900 font-medium transition">&larr; Previous</a>
        @endif

        <span class="text-gray-500">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-5 py-2.5 rounded-full border border-gray-300 hover:border-gray-900 font-medium transition">Next &rarr;</a>
        @else
            <span class="px-5 py-2.5 rounded-full border border-gray-200 text-gray-300">Next &rarr;</span>
        @endif
    </div>
@endif
