@props(['paginator'])

@if ($paginator->hasPages())
    <nav
        aria-label="Task list pagination"
        class="flex flex-col gap-3 border-t border-border px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5"
    >
        <p class="text-center text-sm text-muted sm:text-left">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
            <span class="hidden sm:inline">· {{ $paginator->total() }} results</span>
        </p>

        <div class="grid grid-cols-2 gap-2 sm:flex">
            @if ($paginator->onFirstPage())
                <span class="btn btn-secondary opacity-50" aria-disabled="true">Previous</span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="btn btn-secondary"
                    rel="prev"
                    aria-label="Go to previous page"
                >
                    Previous
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="btn btn-secondary"
                    rel="next"
                    aria-label="Go to next page"
                >
                    Next
                </a>
            @else
                <span class="btn btn-secondary opacity-50" aria-disabled="true">Next</span>
            @endif
        </div>
    </nav>
@endif
