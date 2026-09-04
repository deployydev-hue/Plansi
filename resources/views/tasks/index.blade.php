@extends('layouts.app')

@section('title', 'My Tasks | PLANSI')

@section('content')
    @php
        $hasSearch = request()->filled('search');
        $hasFilters = collect(['status', 'priority', 'category_id', 'due_date'])
            ->contains(fn ($key) => request()->filled($key));
        $hasCustomSort = request('sort', 'newest') !== 'newest';
        $hasActiveCriteria = $hasSearch || $hasFilters || $hasCustomSort;
        $cleanQuery = fn (array $query) => collect($query)
            ->reject(fn ($value) => $value === null || $value === '')
            ->all();
        $queryWithout = fn (string $key) => route(
            'tasks.index',
            $cleanQuery(request()->except(['page', $key]))
        );
        $quickFilterQuery = request()->except(['page', 'status', 'due_date']);
        $categoryName = $categories->firstWhere('id', (int) request('category_id'))?->name;
        $sortLabels = [
            'oldest' => 'Oldest first',
            'due_soon' => 'Due date',
            'priority_high' => 'High priority first',
        ];
    @endphp

    <div
        x-data="{ quickAddOpen: {{ old('quick_add') === '1' && $errors->any() ? 'true' : 'false' }} }"
        class="space-y-6"
    >
        <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-primary">Workspace</p>
                <h1 class="type-h1 mt-2">My Tasks</h1>
                <p class="mt-2 max-w-2xl text-base leading-7 text-text-secondary">
                    Organize your work, focus on what matters, and keep track of your progress.
                </p>
            </div>

            @if ($workspaceTaskCount > 0)
                <button
                    type="button"
                    class="btn btn-primary shrink-0"
                    @click="quickAddOpen = !quickAddOpen"
                    :aria-expanded="quickAddOpen.toString()"
                    aria-controls="quick-add-panel"
                >
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    Add task
                </button>
            @endif
        </header>

        <section
            id="quick-add-panel"
            x-cloak
            x-show="quickAddOpen"
            x-transition.opacity.duration.180ms
            aria-labelledby="quick-add-title"
            class="rounded-2xl border border-primary/20 bg-mint-soft/50 p-4 sm:p-5"
        >
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 id="quick-add-title" class="text-lg font-semibold text-text-primary">Capture a task</h2>
                    <p class="mt-1 text-sm text-text-secondary">Add a title now. Details are optional.</p>
                </div>
                <button
                    type="button"
                    class="btn btn-text btn-icon -mr-2 -mt-2 shrink-0"
                    @click="quickAddOpen = false"
                    aria-label="Close quick add"
                >
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="m5 5 10 10M15 5 5 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <form
                method="POST"
                action="{{ route('tasks.store') }}"
                class="mt-4"
                x-data="{ submitting: false }"
                @submit="if (submitting) { $event.preventDefault() } else { submitting = true }"
            >
                @csrf
                <input type="hidden" name="quick_add" value="1">
                <input type="hidden" name="status" value="pending">

                <label for="quick-title" class="sr-only">Task title</label>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <div class="min-w-0 flex-1">
                        <input
                            x-ref="quickTitle"
                            x-effect="if (quickAddOpen) setTimeout(() => $el.focus(), 200)"
                            id="quick-title"
                            name="title"
                            type="text"
                            maxlength="150"
                            required
                            value="{{ old('quick_add') === '1' ? old('title') : '' }}"
                            placeholder="What needs to be done?"
                            @if (old('quick_add') === '1' && $errors->has('title'))
                                aria-invalid="true"
                                aria-describedby="quick-title-error"
                            @endif
                            class="form-control"
                        >
                        @if (old('quick_add') === '1' && $errors->has('title'))
                            <p id="quick-title-error" class="form-error">{{ $errors->first('title') }}</p>
                        @endif
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary shrink-0 sm:min-w-28"
                        :disabled="submitting"
                        :aria-busy="submitting.toString()"
                    >
                        <span x-show="!submitting">Add task</span>
                        <span x-cloak x-show="submitting">Adding…</span>
                    </button>
                </div>

                <details class="mt-3 rounded-xl border border-border bg-surface/80">
                    <summary class="flex min-h-11 cursor-pointer items-center justify-between gap-3 px-4 py-2.5 text-sm font-semibold text-primary">
                        Details
                        <span class="text-xs font-medium text-muted">Optional</span>
                    </summary>

                    <div class="grid gap-4 border-t border-border p-4 md:grid-cols-3">
                        <x-select
                            id="quick-priority"
                            name="priority"
                            label="Priority"
                            :options="['low' => 'Low', 'medium' => 'Medium', 'high' => 'High']"
                            :selected="old('quick_add') === '1' ? old('priority', 'medium') : 'medium'"
                            required
                        />
                        <x-select
                            id="quick-category"
                            name="category_id"
                            label="Category"
                            :options="$categories->pluck('name', 'id')->toArray()"
                            :selected="old('quick_add') === '1' ? old('category_id', '') : ''"
                            placeholder="No category"
                        />
                        <x-input
                            id="quick-due-at"
                            name="due_at"
                            type="datetime-local"
                            label="Due date"
                            :value="old('quick_add') === '1' ? old('due_at') : null"
                        />
                        <div class="md:col-span-3">
                            <a href="{{ route('tasks.create') }}" class="text-sm font-semibold text-primary hover:text-primary-hover">
                                Open full task form
                            </a>
                        </div>
                    </div>
                </details>
            </form>
        </section>

        <section aria-labelledby="find-tasks-title" class="rounded-2xl border border-border bg-surface">
            <h2 id="find-tasks-title" class="sr-only">Find and filter tasks</h2>

            <div class="border-b border-border p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <form method="GET" action="{{ route('tasks.index') }}" class="min-w-0 flex-1">
                        @foreach (request()->except(['search', 'page']) as $name => $value)
                            @if ($value !== null && $value !== '')
                                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        <label for="task-search" class="sr-only">Search tasks</label>
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-muted" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <circle cx="8.5" cy="8.5" r="5.25" stroke="currentColor" stroke-width="1.6"/>
                                <path d="m12.5 12.5 4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                            </svg>
                            <input
                                id="task-search"
                                name="search"
                                type="search"
                                maxlength="150"
                                value="{{ request('search') }}"
                                placeholder="Search tasks…"
                                class="form-control pl-11 pr-24"
                            >
                            <button type="submit" class="btn btn-text absolute right-1 top-1/2 min-h-10 -translate-y-1/2">
                                Search
                            </button>
                        </div>
                    </form>

                    @include('tasks.partials.mobile-filters', [
                        'categories' => $categories,
                        'hasSearch' => $hasSearch,
                        'hasFilters' => $hasFilters,
                        'hasCustomSort' => $hasCustomSort,
                    ])
                </div>

                <nav aria-label="Quick task filters" class="scrollbar-none mt-4 flex gap-2 overflow-x-auto pb-1">
                    <a href="{{ route('tasks.index', $cleanQuery($quickFilterQuery)) }}" class="btn shrink-0 {{ ! request()->filled('status') && ! request()->filled('due_date') ? 'btn-primary' : 'btn-secondary' }}" @if (! request()->filled('status') && ! request()->filled('due_date')) aria-current="page" @endif>All</a>
                    <a href="{{ route('tasks.index', $cleanQuery([...$quickFilterQuery, 'status' => 'pending', 'due_date' => 'today'])) }}" class="btn shrink-0 {{ request('status') === 'pending' && request('due_date') === 'today' ? 'btn-primary' : 'btn-secondary' }}" @if (request('status') === 'pending' && request('due_date') === 'today') aria-current="page" @endif>Today</a>
                    <a href="{{ route('tasks.index', $cleanQuery([...$quickFilterQuery, 'status' => 'pending', 'due_date' => 'upcoming'])) }}" class="btn shrink-0 {{ request('status') === 'pending' && request('due_date') === 'upcoming' ? 'btn-primary' : 'btn-secondary' }}" @if (request('status') === 'pending' && request('due_date') === 'upcoming') aria-current="page" @endif>Upcoming</a>
                    <a href="{{ route('tasks.index', $cleanQuery([...$quickFilterQuery, 'status' => 'pending', 'due_date' => 'overdue'])) }}" class="btn shrink-0 {{ request('status') === 'pending' && request('due_date') === 'overdue' ? 'btn-primary' : 'btn-secondary' }}" @if (request('status') === 'pending' && request('due_date') === 'overdue') aria-current="page" @endif>Overdue</a>
                    <a href="{{ route('tasks.index', $cleanQuery([...$quickFilterQuery, 'status' => 'completed'])) }}" class="btn shrink-0 {{ request('status') === 'completed' && ! request()->filled('due_date') ? 'btn-primary' : 'btn-secondary' }}" @if (request('status') === 'completed' && ! request()->filled('due_date')) aria-current="page" @endif>Completed</a>
                </nav>
            </div>

            <details class="hidden border-b border-border lg:block" @if ($hasFilters || $hasCustomSort) open @endif>
                <summary class="flex min-h-12 cursor-pointer items-center justify-between gap-4 px-5 py-3 text-sm font-semibold text-primary">
                    More filters
                    <span class="text-xs font-medium text-muted">Status, priority, category, due date, sorting</span>
                </summary>
                <form method="GET" action="{{ route('tasks.index') }}" class="border-t border-border p-5">
                    @if ($hasSearch)
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                        @include('tasks.partials.filter-fields', ['idPrefix' => 'desktop-', 'categories' => $categories])
                    </div>
                    <div class="mt-5 flex justify-end gap-3">
                        <a href="{{ route('tasks.index', $hasSearch ? ['search' => request('search')] : []) }}" class="btn btn-secondary">Clear filters</a>
                        <button type="submit" class="btn btn-primary">Apply filters</button>
                    </div>
                </form>
            </details>

            @include('tasks.partials.active-filters', [
                'hasActiveCriteria' => $hasActiveCriteria,
                'hasSearch' => $hasSearch,
                'hasCustomSort' => $hasCustomSort,
                'categoryName' => $categoryName,
                'queryWithout' => $queryWithout,
                'sortLabels' => $sortLabels,
            ])

            <div class="flex flex-col gap-2 px-4 py-4 sm:flex-row sm:items-end sm:justify-between sm:px-5">
                <div>
                    <h2 id="task-results-title" class="text-lg font-semibold text-text-primary">
                        {{ $hasActiveCriteria ? 'Task results' : 'Your task list' }}
                    </h2>
                    <p class="mt-1 text-sm text-text-secondary" aria-live="polite">
                        {{ $tasks->total() }} {{ $tasks->total() === 1 ? 'task' : 'tasks' }}
                        @if (! $hasActiveCriteria && $dueTodayCount > 0)
                            · {{ $dueTodayCount }} due today
                        @endif
                    </p>
                </div>

                @if ($tasks->hasPages())
                    <p class="text-sm text-muted">Showing {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }} of {{ $tasks->total() }}</p>
                @endif
            </div>

            @if ($tasks->isEmpty())
                @php
                    if ($workspaceTaskCount === 0) {
                        $emptyTitle = 'Start with what matters.';
                        $emptyMessage = 'Add your first task to begin planning.';
                    } elseif (request('due_date') === 'overdue' && ! $hasSearch) {
                        $emptyTitle = 'Nothing overdue.';
                        $emptyMessage = 'You’re currently on track.';
                    } elseif (request('due_date') === 'today' && ! $hasSearch) {
                        $emptyTitle = 'Nothing due today.';
                        $emptyMessage = 'Your schedule is clear for today.';
                    } elseif (request('status') === 'completed' && ! $hasSearch) {
                        $emptyTitle = 'No completed tasks yet.';
                        $emptyMessage = 'Completed work will stay available here.';
                    } else {
                        $emptyTitle = 'Nothing matches these filters.';
                        $emptyMessage = 'Try removing a filter or clearing your search.';
                    }
                @endphp

                <div class="border-t border-border px-5 py-12 text-center">
                    <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-mint-soft text-primary" aria-hidden="true">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none">
                            <path d="M5 10h10M10 5v10" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <h3 class="mt-4 text-lg font-semibold text-text-primary">{{ $emptyTitle }}</h3>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-text-secondary">{{ $emptyMessage }}</p>
                    <div class="mt-5 flex flex-wrap justify-center gap-3">
                        @if ($hasActiveCriteria)
                            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Clear filters</a>
                        @endif
                        @if ($workspaceTaskCount === 0)
                            <button type="button" class="btn btn-primary" @click="quickAddOpen = true">Add task</button>
                        @endif
                    </div>
                </div>
            @else
                <ul class="divide-y divide-border border-t border-border" aria-labelledby="task-results-title">
                    @foreach ($tasks as $task)
                        <x-task-row :task="$task" />
                    @endforeach
                </ul>
                <x-pagination :paginator="$tasks" />
            @endif
        </section>
    </div>
@endsection
