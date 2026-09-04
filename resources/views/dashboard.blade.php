@extends('layouts.app')

@section('title', 'Today | PLANSI')

@section('content')
    <header class="mb-7 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-primary">Today</p>

            <h1 class="type-h1 mt-2 break-words">
                {{ $greeting }}{{ $greetingName ? ', '.$greetingName : '' }}.
            </h1>

            <p class="mt-2 text-base text-text-secondary">Here’s what matters today.</p>
        </div>

        <time
            datetime="{{ now()->toDateString() }}"
            class="shrink-0 text-sm font-medium text-muted"
        >
            {{ now()->format('l, F j') }}
        </time>
    </header>

    @if ($totalTasks === 0)
        <section
            aria-labelledby="new-workspace-title"
            class="flex min-h-[24rem] flex-col items-center justify-center rounded-2xl border border-dashed border-control-border bg-surface px-6 py-12 text-center"
        >
            <span
                class="flex h-12 w-12 items-center justify-center rounded-xl bg-mint-soft text-primary"
                aria-hidden="true"
            >
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </span>

            <h2 id="new-workspace-title" class="type-h2 mt-5">Start with what matters.</h2>
            <p class="mt-3 max-w-md text-base leading-7 text-text-secondary">
                Add your first task and PLANSI will help keep your day clear.
            </p>

            <a href="{{ route('tasks.create') }}" class="btn btn-primary mt-6">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                Add your first task
            </a>
        </section>
    @else
        <div class="grid grid-cols-1 gap-6 min-[1360px]:grid-cols-12 min-[1360px]:items-start">
            {{-- Focus --}}
            <section
                aria-labelledby="focus-title"
                class="overflow-hidden rounded-2xl border border-primary/20 bg-mint-soft/60 min-[1360px]:col-span-8 min-[1360px]:row-start-1"
            >
                <div class="p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-primary">Focus</p>
                        <span class="text-xs font-medium text-muted">Next priority</span>
                    </div>

                    @if ($focusTask)
                        @php
                            $focusPriorityClasses = match ($focusTask->priority) {
                                'high' => 'border-danger/20 bg-danger-soft text-danger',
                                'medium' => 'border-warning/20 bg-warning-soft text-warning',
                                default => 'border-success/20 bg-success-soft text-success',
                            };

                            if ($focusTask->due_at->isBefore(today())) {
                                $focusOverdueDays = max(1, (int) $focusTask->due_at->copy()->startOfDay()->diffInDays(today()));
                                $focusDueLabel = 'Overdue by '.$focusOverdueDays.' '.($focusOverdueDays === 1 ? 'day' : 'days');
                                $focusDueClasses = 'text-danger';
                            } elseif ($focusTask->due_at->isToday()) {
                                $focusDueLabel = 'Due today · '.$focusTask->due_at->format('g:i A');
                                $focusDueClasses = 'text-warning';
                            } elseif ($focusTask->due_at->isTomorrow()) {
                                $focusDueLabel = 'Due tomorrow · '.$focusTask->due_at->format('g:i A');
                                $focusDueClasses = 'text-text-secondary';
                            } else {
                                $focusDueLabel = 'Due '.$focusTask->due_at->format('M j · g:i A');
                                $focusDueClasses = 'text-text-secondary';
                            }
                        @endphp

                        <h2 id="focus-title" class="mt-4 max-w-3xl break-words text-2xl font-semibold leading-8 tracking-tight text-text-primary sm:text-[1.75rem] sm:leading-9">
                            {{ $focusTask->title }}
                        </h2>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            @if ($focusTask->category)
                                <span class="rounded-lg border border-border bg-surface/80 px-2.5 py-1.5 text-xs font-medium text-text-secondary">
                                    {{ $focusTask->category->name }}
                                </span>
                            @endif

                            <span class="rounded-lg border px-2.5 py-1.5 text-xs font-semibold {{ $focusPriorityClasses }}">
                                {{ ucfirst($focusTask->priority) }} priority
                            </span>

                            <span class="px-1 text-xs font-semibold {{ $focusDueClasses }}">
                                {{ $focusDueLabel }}
                            </span>
                        </div>

                        <a href="{{ route('tasks.edit', $focusTask) }}" class="btn btn-secondary mt-6">
                            Edit task
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="m8 5 5 5-5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    @else
                        <h2 id="focus-title" class="type-h3 mt-4">You’re clear for now.</h2>
                        <p class="mt-2 text-sm leading-6 text-text-secondary">
                            Nothing urgent needs your attention.
                        </p>
                        <a
                            href="{{ route('tasks.index', ['due_date' => 'upcoming', 'status' => 'pending']) }}"
                            class="btn btn-secondary mt-5"
                        >
                            View upcoming tasks
                        </a>
                    @endif
                </div>
            </section>

            {{-- Today --}}
            <section
                aria-labelledby="today-title"
                class="overflow-hidden rounded-2xl border border-border bg-surface min-[1360px]:col-span-8 min-[1360px]:row-start-2"
            >
                <div class="flex items-end justify-between gap-4 border-b border-border px-4 py-4 sm:px-5">
                    <div>
                        <h2 id="today-title" class="type-h3">Today</h2>
                        <p class="mt-1 text-sm text-muted">
                            {{ $todayTotalCount }}
                            {{ $todayTotalCount === 1 ? 'task' : 'tasks' }} planned
                        </p>
                    </div>

                    @if ($dueTodayTasks > 5)
                        <a
                            href="{{ route('tasks.index', ['due_date' => 'today', 'status' => 'pending']) }}"
                            class="btn btn-text shrink-0 px-2"
                        >
                            View all today
                        </a>
                    @endif
                </div>

                @if ($todayTasks->isNotEmpty())
                    <div class="divide-y divide-border">
                        @foreach ($todayTasks as $task)
                            <x-dashboard-task-row :task="$task" />
                        @endforeach
                    </div>
                @elseif ($todayTotalCount > 0 && $todayCompletedCount === $todayTotalCount)
                    <div class="px-5 py-8">
                        <h3 class="text-base font-semibold text-success">Today is complete.</h3>
                        <p class="mt-2 text-sm leading-6 text-text-secondary">
                            You finished everything planned for today.
                        </p>
                    </div>
                @else
                    <div class="px-5 py-8">
                        <h3 class="text-base font-semibold text-text-primary">Nothing due today.</h3>
                        <p class="mt-2 text-sm leading-6 text-text-secondary">
                            Your day is clear. Check upcoming work or add something new.
                        </p>
                    </div>
                @endif
            </section>

            {{-- Daily progress --}}
            <section
                aria-labelledby="progress-title"
                class="rounded-2xl border border-border bg-surface p-5 min-[1360px]:col-span-4 min-[1360px]:col-start-9 min-[1360px]:row-start-1"
            >
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-muted">Today’s progress</p>
                <h2 id="progress-title" class="sr-only">Today’s progress</h2>

                @if ($todayTotalCount > 0)
                    <div class="mt-4 flex items-end justify-between gap-4">
                        <p class="text-3xl font-semibold tracking-tight text-text-primary">
                            {{ $todayProgressPercentage }}%
                        </p>
                        <p class="pb-1 text-sm font-medium text-text-secondary">
                            {{ $todayCompletedCount }} of {{ $todayTotalCount }} completed
                        </p>
                    </div>

                    <div
                        class="mt-4 h-2 overflow-hidden rounded-full bg-border"
                        role="progressbar"
                        aria-label="Tasks completed today"
                        aria-valuemin="0"
                        aria-valuemax="{{ $todayTotalCount }}"
                        aria-valuenow="{{ $todayCompletedCount }}"
                        aria-valuetext="{{ $todayCompletedCount }} of {{ $todayTotalCount }} completed"
                    >
                        <div
                            class="h-full rounded-full bg-primary transition-[width] duration-300"
                            style="width: {{ $todayProgressPercentage }}%"
                        ></div>
                    </div>

                    <p class="mt-4 text-sm leading-6 text-text-secondary">
                        @if ($todayCompletedCount === $todayTotalCount)
                            Everything planned for today is complete.
                        @elseif ($todayCompletedCount === 0)
                            Start with one task at a time.
                        @else
                            Keep going—your day is taking shape.
                        @endif
                    </p>
                @else
                    <p class="mt-4 text-lg font-semibold text-text-primary">Nothing scheduled.</p>
                    <p class="mt-2 text-sm leading-6 text-text-secondary">
                        Progress will appear when tasks are due today.
                    </p>
                @endif
            </section>

            {{-- Overdue only occupies space when needed. --}}
            @if ($overdueTasks > 0)
                <section
                    aria-labelledby="overdue-title"
                    class="overflow-hidden rounded-2xl border border-danger/20 bg-surface min-[1360px]:col-span-4 min-[1360px]:col-start-9 min-[1360px]:row-start-2"
                >
                    <div class="border-b border-danger/15 bg-danger-soft/60 px-4 py-4 sm:px-5">
                        <h2 id="overdue-title" class="text-lg font-semibold text-text-primary">Overdue</h2>
                        <p class="mt-1 text-sm text-danger">
                            {{ $overdueTasks }} {{ $overdueTasks === 1 ? 'task needs' : 'tasks need' }} attention
                        </p>
                    </div>

                    <div class="divide-y divide-border">
                        @foreach ($overdueTaskList as $task)
                            <x-dashboard-task-row :task="$task" context="overdue" />
                        @endforeach
                    </div>

                    @if ($overdueTasks > $overdueTaskList->count())
                        <div class="border-t border-border px-4 py-3 sm:px-5">
                            <a
                                href="{{ route('tasks.index', ['due_date' => 'overdue', 'status' => 'pending']) }}"
                                class="text-sm font-semibold text-primary hover:text-primary-hover"
                            >
                                View all overdue
                            </a>
                        </div>
                    @endif
                </section>
            @endif

            {{-- Upcoming --}}
            <section
                aria-labelledby="upcoming-title"
                class="overflow-hidden rounded-2xl border border-border bg-surface min-[1360px]:col-span-8 min-[1360px]:row-start-3"
            >
                <div class="flex items-end justify-between gap-4 border-b border-border px-4 py-4 sm:px-5">
                    <div>
                        <h2 id="upcoming-title" class="type-h3">Upcoming</h2>
                        <p class="mt-1 text-sm text-muted">Your next pending deadlines</p>
                    </div>

                    <a
                        href="{{ route('tasks.index', ['due_date' => 'upcoming', 'status' => 'pending']) }}"
                        class="btn btn-text shrink-0 px-2"
                    >
                        View all
                    </a>
                </div>

                @if ($upcomingTasks->isNotEmpty())
                    <div class="divide-y divide-border">
                        @foreach ($upcomingTasks as $task)
                            <x-dashboard-task-row :task="$task" context="upcoming" />
                        @endforeach
                    </div>
                @else
                    <div class="px-5 py-8">
                        <h3 class="text-base font-semibold text-text-primary">Nothing upcoming.</h3>
                        <p class="mt-2 text-sm leading-6 text-text-secondary">
                            Future deadlines will appear here.
                        </p>
                    </div>
                @endif
            </section>

            {{-- Secondary workspace statistics --}}
            <section
                aria-labelledby="workspace-overview-title"
                class="rounded-2xl border border-border bg-surface p-5 min-[1360px]:col-span-4 min-[1360px]:col-start-9 min-[1360px]:row-start-3"
            >
                <h2 id="workspace-overview-title" class="text-base font-semibold text-text-primary">
                    Workspace overview
                </h2>
                <p class="mt-1 text-sm text-muted">Secondary account totals</p>

                <dl class="mt-5 grid grid-cols-2 gap-x-5 gap-y-5">
                    <div>
                        <dt class="text-xs font-medium text-muted">Total</dt>
                        <dd class="mt-1 text-xl font-semibold text-text-primary">{{ $totalTasks }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted">Pending</dt>
                        <dd class="mt-1 text-xl font-semibold text-text-primary">{{ $pendingTasks }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted">Completed</dt>
                        <dd class="mt-1 text-xl font-semibold text-success">{{ $completedTasks }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted">Overdue</dt>
                        <dd class="mt-1 text-xl font-semibold {{ $overdueTasks > 0 ? 'text-danger' : 'text-text-primary' }}">
                            {{ $overdueTasks }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted">Categories</dt>
                        <dd class="mt-1 text-xl font-semibold text-text-primary">{{ $categoriesCount }}</dd>
                    </div>
                </dl>
            </section>
        </div>
    @endif
@endsection
