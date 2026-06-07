@if($paginator->hasPages())
    <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 sm:px-6">
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    Showing <span class="font-medium">{{ $paginator->firstItem() }}</span> to
                    <span class="font-medium">{{ $paginator->lastItem() }}</span> of
                    <span class="font-medium">{{ $paginator->total() }}</span> results
                </p>
            </div>
            <div>
                {{ $paginator->links('components.pagination-links') }}
            </div>
        </div>
        <div class="flex flex-1 justify-between sm:hidden">
            @if($paginator->onFirstPage())
                <span class="btn-secondary opacity-50 cursor-not-allowed">Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn-secondary">Previous</a>
            @endif
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn-secondary">Next</a>
            @else
                <span class="btn-secondary opacity-50 cursor-not-allowed">Next</span>
            @endif
        </div>
    </div>
@endif
