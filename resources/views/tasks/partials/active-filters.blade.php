@if ($hasActiveCriteria)
    <div class="flex flex-wrap items-center gap-2 border-b border-border px-4 py-3 sm:px-5" aria-label="Active filters">
        <span class="mr-1 text-xs font-semibold uppercase tracking-wide text-muted">Active</span>

        @if ($hasSearch)
            <a href="{{ $queryWithout('search') }}" class="inline-flex min-h-9 items-center gap-1.5 rounded-lg bg-background px-3 text-xs font-semibold text-text-secondary hover:text-primary">
                Search: “{{ request('search') }}” <span aria-hidden="true">×</span>
                <span class="sr-only">Remove search filter</span>
            </a>
        @endif
        @if (request()->filled('status'))
            <a href="{{ $queryWithout('status') }}" class="inline-flex min-h-9 items-center gap-1.5 rounded-lg bg-background px-3 text-xs font-semibold text-text-secondary hover:text-primary">
                {{ ucfirst(request('status')) }} <span aria-hidden="true">×</span>
                <span class="sr-only">Remove status filter</span>
            </a>
        @endif
        @if (request()->filled('priority'))
            <a href="{{ $queryWithout('priority') }}" class="inline-flex min-h-9 items-center gap-1.5 rounded-lg bg-background px-3 text-xs font-semibold text-text-secondary hover:text-primary">
                {{ ucfirst(request('priority')) }} priority <span aria-hidden="true">×</span>
                <span class="sr-only">Remove priority filter</span>
            </a>
        @endif
        @if ($categoryName)
            <a href="{{ $queryWithout('category_id') }}" class="inline-flex min-h-9 max-w-full items-center gap-1.5 rounded-lg bg-background px-3 text-xs font-semibold text-text-secondary hover:text-primary">
                <span class="truncate">{{ $categoryName }}</span> <span aria-hidden="true">×</span>
                <span class="sr-only">Remove category filter</span>
            </a>
        @endif
        @if (request()->filled('due_date'))
            <a href="{{ $queryWithout('due_date') }}" class="inline-flex min-h-9 items-center gap-1.5 rounded-lg bg-background px-3 text-xs font-semibold text-text-secondary hover:text-primary">
                {{ str(request('due_date'))->replace('_', ' ')->title() }} <span aria-hidden="true">×</span>
                <span class="sr-only">Remove due date filter</span>
            </a>
        @endif
        @if ($hasCustomSort)
            <a href="{{ $queryWithout('sort') }}" class="inline-flex min-h-9 items-center gap-1.5 rounded-lg bg-background px-3 text-xs font-semibold text-text-secondary hover:text-primary">
                {{ $sortLabels[request('sort')] ?? 'Custom sort' }} <span aria-hidden="true">×</span>
                <span class="sr-only">Reset sorting</span>
            </a>
        @endif

        <a href="{{ route('tasks.index') }}" class="ml-auto text-sm font-semibold text-primary hover:text-primary-hover">Clear all</a>
    </div>
@endif
