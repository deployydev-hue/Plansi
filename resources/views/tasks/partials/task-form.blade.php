@php
    $isCreate = $mode === 'create';
    $formAction = $isCreate ? route('tasks.store') : route('tasks.update', $task);
    $statusLabel = null;
    $statusClass = null;

    if (! $isCreate) {
        if ($task->status === 'completed') {
            $statusLabel = 'Completed';
            $statusClass = 'bg-success-soft text-success';
        } elseif ($task->due_at?->lt(today())) {
            $statusLabel = 'Overdue';
            $statusClass = 'bg-danger-soft text-danger';
        } else {
            $statusLabel = 'Pending';
            $statusClass = 'bg-mint-soft text-primary';
        }
    }

    $fieldLabels = [
        'title' => 'Task title',
        'description' => 'Description',
        'due_at' => 'Due date and time',
        'priority' => 'Priority',
        'category_id' => 'Category',
        'status' => 'Status',
    ];
@endphp

<div class="mx-auto max-w-3xl">
    <a href="{{ route('tasks.index') }}" class="btn btn-text -ml-3 mb-5">
        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
            <path d="m11.5 5-5 5 5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Back to My Tasks
    </a>

    <header class="mb-7 sm:mb-8">
        <div class="flex flex-wrap items-center gap-3">
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-primary">Tasks</p>
            @if ($statusLabel)
                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
            @endif
        </div>
        <h1 class="type-h1 mt-2">{{ $isCreate ? 'Create a task' : 'Edit task' }}</h1>
        <p class="mt-2 max-w-2xl text-base leading-7 text-text-secondary">
            {{ $isCreate
                ? 'Add the details that help you know what matters and when.'
                : 'Update the details that keep this task clear and actionable.' }}
        </p>
    </header>

    @if ($errors->any())
        <div
            role="alert"
            class="mb-6 rounded-2xl border border-danger/30 bg-danger-soft px-5 py-4"
            x-data
            x-init="$nextTick(() => document.querySelector('[aria-invalid=true]')?.focus())"
        >
            <p class="font-semibold text-danger">Please review {{ $errors->count() === 1 ? 'this field' : 'these fields' }}.</p>
            <ul class="mt-2 space-y-1 text-sm text-danger">
                @foreach ($errors->keys() as $field)
                    <li>
                        <a href="#{{ $field }}" class="underline decoration-danger/40 underline-offset-2 hover:decoration-danger">
                            {{ $fieldLabels[$field] ?? str($field)->headline() }}: {{ $errors->first($field) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ $formAction }}"
        class="overflow-hidden rounded-2xl border border-border bg-surface shadow-sm"
        x-data="{ submitting: false }"
        @submit="submitting = true"
    >
        @csrf
        @unless ($isCreate)
            @method('PUT')
        @endunless

        <section class="p-5 sm:p-7" aria-labelledby="task-section-title">
            <div class="mb-6">
                <h2 id="task-section-title" class="text-lg font-semibold text-text-primary">Task</h2>
                <p class="mt-1 text-sm text-text-secondary">Start with a clear outcome. Add context only when it will help later.</p>
            </div>

            <div class="space-y-6">
                <x-input
                    name="title"
                    label="Task title"
                    requirement="Required"
                    :value="$task?->title"
                    placeholder="What needs to get done?"
                    maxlength="150"
                    required
                    :autofocus="$isCreate && ! $errors->any()"
                    class="text-base font-medium"
                />

                <x-textarea
                    name="description"
                    label="Description"
                    requirement="Optional"
                    :value="$task?->description"
                    hint="Add notes, context, or anything you’ll need later. Up to 5,000 characters."
                    placeholder="Add helpful context for this task…"
                    maxlength="5000"
                    rows="6"
                />
            </div>
        </section>

        <section class="border-t border-border p-5 sm:p-7" aria-labelledby="planning-section-title">
            <div class="mb-6">
                <h2 id="planning-section-title" class="text-lg font-semibold text-text-primary">Planning</h2>
                <p class="mt-1 text-sm text-text-secondary">Set timing, importance, and organization for the task.</p>
            </div>

            <div class="space-y-6">
                <x-input
                    name="due_at"
                    label="Due date and time"
                    requirement="Optional"
                    type="datetime-local"
                    :value="$task?->due_at?->format('Y-m-d\TH:i')"
                    hint="Leave this empty when the task has no deadline. Times use your current application timezone."
                />

                @php
                    $selectedPriority = old('priority', $task?->priority ?? 'medium');
                    $priorityError = $errors->first('priority');
                @endphp
                <fieldset id="priority" @if ($priorityError) aria-describedby="priority-error" @endif>
                    <legend class="w-full">
                        <span class="flex items-baseline justify-between gap-3">
                            <span class="form-label mb-0">Priority <span class="sr-only">(required)</span></span>
                            <span class="text-xs font-medium text-primary">Required</span>
                        </span>
                    </legend>
                    <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-3">
                        @foreach (['high' => 'High', 'medium' => 'Medium', 'low' => 'Low'] as $value => $label)
                            <label class="relative flex min-h-12 cursor-pointer items-center justify-center rounded-xl border border-control-border bg-surface px-4 py-2.5 text-sm font-semibold text-text-secondary transition hover:border-primary hover:text-primary has-[:checked]:border-primary has-[:checked]:bg-mint-soft has-[:checked]:text-primary has-[:focus-visible]:ring-3 has-[:focus-visible]:ring-primary/20">
                                <input
                                    type="radio"
                                    name="priority"
                                    value="{{ $value }}"
                                    class="sr-only"
                                    required
                                    @checked($selectedPriority === $value)
                                    @if ($priorityError) aria-invalid="true" aria-describedby="priority-error" @endif
                                >
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    @if ($priorityError)
                        <p id="priority-error" class="form-error">{{ $priorityError }}</p>
                    @endif
                </fieldset>

                <x-select
                    name="category_id"
                    label="Category"
                    requirement="Optional"
                    :options="$categories->pluck('name', 'id')->toArray()"
                    :selected="$task?->category_id ?? ''"
                    placeholder="No category"
                    hint="Choose an existing category, or leave this task ungrouped."
                />
            </div>
        </section>

        <section class="border-t border-border p-5 sm:p-7" aria-labelledby="status-section-title">
            <div class="mb-6">
                <h2 id="status-section-title" class="text-lg font-semibold text-text-primary">Status</h2>
                <p class="mt-1 text-sm text-text-secondary">
                    {{ $isCreate
                        ? 'New tasks normally begin as pending. Choose completed only when the work is already done.'
                        : 'Changing to completed records completion time. Returning to pending clears it.' }}
                </p>
            </div>

            <x-select
                name="status"
                label="Task status"
                requirement="Required"
                :options="['pending' => 'Pending', 'completed' => 'Completed']"
                :selected="$task?->status ?? 'pending'"
                placeholder="Choose status"
                required
            />
        </section>

        <footer class="flex flex-col-reverse gap-3 border-t border-border bg-background/60 p-5 sm:flex-row sm:items-center sm:justify-end sm:px-7 sm:py-5">
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
            <button
                type="submit"
                class="btn btn-primary min-w-32"
                :disabled="submitting"
                :aria-busy="submitting.toString()"
                :data-loading="submitting.toString()"
            >
                <span x-show="!submitting">{{ $isCreate ? 'Create task' : 'Save changes' }}</span>
                <span x-cloak x-show="submitting" aria-live="polite">{{ $isCreate ? 'Creating…' : 'Saving…' }}</span>
            </button>
        </footer>
    </form>

    @unless ($isCreate)
        <section class="mt-7 border-t border-danger/30 pt-7" aria-labelledby="danger-zone-title">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 id="danger-zone-title" class="font-semibold text-text-primary">Delete task</h2>
                    <p class="mt-1 text-sm leading-6 text-text-secondary">Permanently remove this task and its history.</p>
                </div>

                <x-dialog
                    title="Delete this task?"
                    :action="route('tasks.destroy', $task)"
                    method="DELETE"
                    triggerLabel="Delete task"
                    confirmLabel="Delete task"
                    destructive
                >
                    <p class="mt-4 break-words rounded-xl bg-background px-4 py-3 text-sm font-semibold text-text-primary">“{{ $task->title }}”</p>
                    <p class="mt-3 text-sm text-text-secondary">This action cannot be undone.</p>
                </x-dialog>
            </div>
        </section>
    @endunless
</div>
