<div x-data="accessibleDialog" class="lg:hidden">
    <button
        type="button"
        class="btn btn-secondary shrink-0"
        @click="openDialog"
        aria-haspopup="dialog"
    >
        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
            <path d="M3 5h14M5.5 10h9M8 15h4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
        </svg>
        Filters
        @if ($hasFilters || $hasCustomSort)
            <span class="rounded-full bg-primary px-2 py-0.5 text-xs text-white">
                {{ collect(['status', 'priority', 'category_id', 'due_date'])->filter(fn ($key) => request()->filled($key))->count() + ($hasCustomSort ? 1 : 0) }}
            </span>
        @endif
    </button>

    <div
        x-cloak
        x-show="open"
        class="fixed inset-0 z-[80] flex items-end justify-center"
        @keydown.escape.window="closeDialog"
    >
        <button
            type="button"
            class="absolute inset-0 bg-text-primary/45"
            aria-label="Close filters"
            tabindex="-1"
            @click="closeDialog"
        ></button>

        <section
            x-ref="dialog"
            x-show="open"
            x-transition:enter="transition duration-[240ms] ease-out"
            x-transition:enter-start="translate-y-5 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition duration-180 ease-in"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-5 opacity-0"
            role="dialog"
            aria-modal="true"
            aria-labelledby="mobile-filters-title"
            @keydown.tab="trapFocus($event)"
            class="relative z-10 max-h-[88vh] w-full overflow-y-auto rounded-t-2xl border border-border bg-surface p-5 shadow-lg"
        >
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 id="mobile-filters-title" class="type-h3">Filter tasks</h3>
                    <p class="mt-1 text-sm text-muted">Narrow the workspace.</p>
                </div>
                <button type="button" class="btn btn-secondary btn-icon" @click="closeDialog" aria-label="Close filters">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="m5 5 10 10M15 5 5 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <form method="GET" action="{{ route('tasks.index') }}" class="mt-5 space-y-4">
                @if ($hasSearch)
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @include('tasks.partials.filter-fields', ['idPrefix' => 'mobile-', 'categories' => $categories])

                <div class="sticky bottom-0 -mx-5 mt-6 flex gap-3 border-t border-border bg-surface px-5 pb-1 pt-4">
                    <a href="{{ route('tasks.index', $hasSearch ? ['search' => request('search')] : []) }}" class="btn btn-secondary flex-1">Clear</a>
                    <button type="submit" class="btn btn-primary flex-1">Apply filters</button>
                </div>
            </form>
        </section>
    </div>
</div>
