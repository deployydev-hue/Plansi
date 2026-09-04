@props([
    'task',
    'context' => 'today',
])

@php
    $priorityClasses = match ($task->priority) {
        'high' => 'border-danger/20 bg-danger-soft text-danger',
        'medium' => 'border-warning/20 bg-warning-soft text-warning',
        default => 'border-success/20 bg-success-soft text-success',
    };

    if ($task->due_at?->isBefore(today())) {
        $overdueDays = max(1, (int) $task->due_at->copy()->startOfDay()->diffInDays(today()));
        $dueLabel = 'Overdue by '.$overdueDays.' '.($overdueDays === 1 ? 'day' : 'days');
        $dueClasses = 'text-danger';
    } elseif ($task->due_at?->isToday()) {
        $dueLabel = 'Due '.$task->due_at->format('g:i A');
        $dueClasses = 'text-warning';
    } elseif ($task->due_at?->isTomorrow()) {
        $dueLabel = 'Tomorrow · '.$task->due_at->format('g:i A');
        $dueClasses = 'text-text-secondary';
    } else {
        $dueLabel = $task->due_at?->format('M j · g:i A') ?? 'No due date';
        $dueClasses = 'text-text-secondary';
    }
@endphp

<article class="group flex items-start gap-3 px-4 py-4 sm:items-center sm:px-5">
    <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="shrink-0">
        @csrf
        @method('PATCH')

        <button
            type="submit"
            role="checkbox"
            aria-checked="false"
            aria-label="Mark {{ $task->title }} as completed"
            class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full border-2 border-control-border bg-surface text-transparent transition hover:border-primary hover:bg-mint-soft focus-visible:border-primary sm:mt-0"
        >
            <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="m3.5 8.2 2.8 2.8 6.2-6.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </form>

    <div class="min-w-0 flex-1">
        <div class="flex min-w-0 flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-3">
            <h3 class="min-w-0 break-words text-[0.9375rem] font-semibold leading-5 text-text-primary">
                {{ $task->title }}
            </h3>

            <span class="hidden text-border sm:block" aria-hidden="true">·</span>

            <p class="shrink-0 text-xs font-medium {{ $dueClasses }}">
                {{ $dueLabel }}
            </p>
        </div>

        <div class="mt-2 flex flex-wrap items-center gap-2">
            @if ($task->category)
                <span class="rounded-lg bg-background px-2 py-1 text-xs font-medium text-text-secondary">
                    {{ $task->category->name }}
                </span>
            @endif

            <span class="rounded-lg border px-2 py-1 text-[0.6875rem] font-semibold uppercase tracking-wide {{ $priorityClasses }}">
                {{ ucfirst($task->priority) }} priority
            </span>
        </div>
    </div>

    <a
        href="{{ route('tasks.edit', $task) }}"
        class="btn btn-text min-h-10 shrink-0 px-2"
        aria-label="Edit {{ $task->title }}"
    >
        Edit
    </a>
</article>
