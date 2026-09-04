@props(['task'])

@php
    $isCompleted = $task->status === 'completed';

    $priorityClasses = match ($task->priority) {
        'high' => 'border-danger/20 bg-danger-soft text-danger',
        'medium' => 'border-warning/20 bg-warning-soft text-warning',
        default => 'border-success/20 bg-success-soft text-success',
    };

    if (! $task->due_at) {
        $dueLabel = 'No due date';
        $dueClasses = 'text-muted';
    } elseif ($task->status === 'pending' && $task->due_at->isBefore(today())) {
        $overdueDays = max(1, (int) $task->due_at->copy()->startOfDay()->diffInDays(today()));
        $dueLabel = 'Overdue by '.$overdueDays.' '.($overdueDays === 1 ? 'day' : 'days');
        $dueClasses = 'text-danger';
    } elseif ($task->due_at->isToday()) {
        $dueLabel = 'Due today · '.$task->due_at->format('g:i A');
        $dueClasses = 'text-warning';
    } elseif ($task->due_at->isTomorrow()) {
        $dueLabel = 'Tomorrow';
        $dueClasses = 'text-text-secondary';
    } else {
        $dueLabel = $task->due_at->format('M j');
        $dueClasses = 'text-text-secondary';
    }
@endphp

<li
    class="group relative flex items-start gap-2.5 px-3 py-3.5 sm:gap-3 sm:px-5 sm:py-4"
    x-data="taskMenu"
>
    <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="shrink-0">
        @csrf
        @method('PATCH')

        <button
            type="submit"
            role="checkbox"
            aria-checked="{{ $isCompleted ? 'true' : 'false' }}"
            aria-label="{{ $isCompleted ? 'Mark '.$task->title.' as pending' : 'Mark '.$task->title.' as completed' }}"
            class="flex h-11 w-11 items-center justify-center rounded-xl text-primary transition hover:bg-mint-soft"
        >
            <span
                class="flex h-6 w-6 items-center justify-center rounded-full border-2 {{ $isCompleted ? 'border-primary bg-primary text-white' : 'border-control-border bg-surface text-transparent' }}"
                aria-hidden="true"
            >
                <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none">
                    <path d="m3.5 8.2 2.8 2.8 6.2-6.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </button>
    </form>

    <div class="min-w-0 flex-1 pt-1.5">
        <a
            href="{{ route('tasks.edit', $task) }}"
            class="block w-fit max-w-full break-words text-[0.9375rem] font-semibold leading-5 {{ $isCompleted ? 'text-muted line-through decoration-border' : 'text-text-primary hover:text-primary' }}"
        >
            {{ $task->title }}
        </a>

        <div class="mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs">
            <span class="font-semibold {{ $isCompleted ? 'text-muted' : $dueClasses }}">
                {{ $dueLabel }}
            </span>

            <span class="text-border" aria-hidden="true">·</span>

            <span class="rounded-md border px-2 py-0.5 font-semibold {{ $isCompleted ? 'border-border bg-background text-muted' : $priorityClasses }}">
                {{ ucfirst($task->priority) }} priority
            </span>

            @if ($task->category)
                <span class="text-border" aria-hidden="true">·</span>
                <span class="max-w-full break-words text-text-secondary">{{ $task->category->name }}</span>
            @endif
        </div>
    </div>

    <div class="relative shrink-0">
        <button
            x-ref="trigger"
            type="button"
            class="btn btn-text btn-icon"
            aria-haspopup="menu"
            :aria-expanded="open.toString()"
            @click="toggleMenu"
            @keydown.arrow-down.prevent="openMenu(true)"
            aria-label="Actions for {{ $task->title }}"
        >
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                <circle cx="4" cy="10" r="1.5" fill="currentColor"/>
                <circle cx="10" cy="10" r="1.5" fill="currentColor"/>
                <circle cx="16" cy="10" r="1.5" fill="currentColor"/>
            </svg>
        </button>

        <div
            x-cloak
            x-show="open"
            x-transition.opacity.duration.140ms
            @click.outside="closeMenu"
            @keydown.escape.stop="closeMenu(true)"
            role="menu"
            aria-label="Task actions"
            class="absolute right-0 z-30 mt-1 w-48 rounded-xl border border-border bg-surface p-1.5 shadow-md"
        >
            <a
                x-ref="firstAction"
                href="{{ route('tasks.edit', $task) }}"
                role="menuitem"
                class="flex min-h-11 items-center rounded-lg px-3 text-sm font-medium text-text-primary hover:bg-mint-soft hover:text-primary"
            >
                Edit
            </a>

            <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                @csrf
                @method('PATCH')
                <button
                    type="submit"
                    role="menuitem"
                    class="flex min-h-11 w-full items-center rounded-lg px-3 text-left text-sm font-medium text-text-primary hover:bg-mint-soft hover:text-primary"
                >
                    {{ $isCompleted ? 'Mark pending' : 'Complete' }}
                </button>
            </form>

            <div class="my-1 border-t border-border"></div>

            <x-dialog
                title="Delete this task?"
                :action="route('tasks.destroy', $task)"
                method="DELETE"
                triggerLabel="Delete"
                confirmLabel="Delete task"
                destructive
                triggerClass="flex min-h-11 w-full items-center rounded-lg px-3 text-left text-sm font-semibold text-danger hover:bg-danger-soft"
                triggerRole="menuitem"
            >
                <p class="mt-4 break-words rounded-xl bg-background px-4 py-3 text-sm font-semibold text-text-primary">
                    “{{ $task->title }}”
                </p>
                <p class="mt-3 text-sm text-text-secondary">This action cannot be undone.</p>
            </x-dialog>
        </div>
    </div>
</li>
