@extends('layouts.app')

@section('title', 'Edit Task | PLANSI')

@section('content')

<div class="mx-auto max-w-3xl">

    {{-- Back --}}
    <a
        href="{{ route('tasks.index') }}"
        class="
            mb-6 inline-flex items-center gap-2
            text-sm font-medium text-text-secondary
            transition hover:text-primary
        "
    >
        ← Back to Tasks
    </a>


    {{-- Header --}}
    <div class="mb-8">

        <p class="mb-1 text-sm font-medium text-primary">
            Update Task
        </p>

        <h1
            class="
                text-3xl font-semibold tracking-tight
                text-text-primary
            "
        >
            Edit task
        </h1>

        <p class="mt-2 text-sm leading-6 text-text-secondary">
            Update the task details, priority,
            category, status or due date.
        </p>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div
            class="
                mb-6 rounded-2xl
                border border-danger/20
                bg-danger/5
                px-5 py-4
            "
        >

            <p class="mb-2 text-sm font-semibold text-danger">
                Please check the following:
            </p>

            <ul class="list-disc space-y-1 pl-5 text-sm text-danger">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- UPDATE FORM --}}
    <form
        method="POST"
        action="{{ route('tasks.update', $task) }}"
        class="
            rounded-3xl
            border border-border
            bg-surface
            p-6
            shadow-sm
            sm:p-8
        "
    >

        @csrf
        @method('PUT')


        {{-- Title --}}
        <div class="mb-6">

            <label
                for="title"
                class="
                    mb-2 block
                    text-sm font-semibold
                    text-text-primary
                "
            >
                Task Title
                <span class="text-danger">*</span>
            </label>

            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $task->title) }}"
                required
                maxlength="150"
                autofocus
                class="
                    w-full
                    rounded-2xl
                    border border-border
                    bg-background
                    px-4 py-3.5
                    text-sm text-text-primary
                    outline-none
                    transition
                    hover:border-primary/50
                    focus:border-primary
                    focus:ring-4
                    focus:ring-mint-soft
                "
            >

            @error('title')
                <p class="mt-2 text-sm text-danger">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- Description --}}
        <div class="mb-6">

            <div class="mb-2 flex items-center justify-between">

                <label
                    for="description"
                    class="text-sm font-semibold text-text-primary"
                >
                    Description
                </label>

                <span class="text-xs text-text-secondary">
                    Optional
                </span>

            </div>

            <textarea
                id="description"
                name="description"
                rows="5"
                maxlength="5000"
                class="
                    w-full
                    resize-y
                    rounded-2xl
                    border border-border
                    bg-background
                    px-4 py-3.5
                    text-sm leading-6
                    text-text-primary
                    outline-none
                    transition
                    hover:border-primary/50
                    focus:border-primary
                    focus:ring-4
                    focus:ring-mint-soft
                "
            >{{ old('description', $task->description) }}</textarea>

            @error('description')
                <p class="mt-2 text-sm text-danger">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- Priority + Status --}}
        <div
            class="
                mb-6
                grid grid-cols-1
                gap-5
                sm:grid-cols-2
            "
        >

            {{-- Priority --}}
            <x-select
                name="priority"
                label="Priority"
                :options="[
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                ]"
                :selected="old('priority', $task->priority)"
                placeholder="Choose Priority"
                required
            />


            {{-- Status --}}
            <x-select
                name="status"
                label="Status"
                :options="[
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                ]"
                :selected="old('status', $task->status)"
                placeholder="Choose Status"
                required
            />

        </div>


        {{-- Category + Due Date --}}
        <div
            class="
                mb-8
                grid grid-cols-1
                gap-5
                sm:grid-cols-2
            "
        >

            {{-- Category --}}
            <x-select
                name="category_id"
                label="Category"
                :options="$categories->pluck('name', 'id')->toArray()"
                :selected="old('category_id', $task->category_id ?? '')"
                placeholder="No Category"
            />


            {{-- Due Date --}}
            <div>

                <label
                    for="due_at"
                    class="
                        mb-2 block
                        text-sm font-semibold
                        text-text-primary
                    "
                >
                    Due Date
                </label>

                <input
                    type="datetime-local"
                    id="due_at"
                    name="due_at"
                    value="{{ old(
                        'due_at',
                        $task->due_at?->format('Y-m-d\TH:i')
                    ) }}"
                    class="
                        w-full
                        rounded-2xl
                        border border-border
                        bg-background
                        px-4 py-3
                        text-sm text-text-primary
                        outline-none
                        transition
                        hover:border-primary/50
                        focus:border-primary
                        focus:ring-4
                        focus:ring-mint-soft
                    "
                >

                <p class="mt-2 text-xs leading-5 text-text-secondary">
                    Leave empty when the task has no deadline.
                </p>

                @error('due_at')
                    <p class="mt-2 text-sm text-danger">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>


        {{-- Task Info --}}
        <div
            class="
                mb-8
                rounded-2xl
                bg-mint-soft
                px-5 py-4
            "
        >

            <p class="text-sm font-semibold text-primary">
                Task information
            </p>

            <div
                class="
                    mt-3
                    grid grid-cols-1
                    gap-2
                    text-xs
                    text-text-secondary
                    sm:grid-cols-2
                "
            >

                <p>
                    Created:

                    <span class="font-medium text-text-primary">
                        {{ $task->created_at->format('M d, Y · H:i') }}
                    </span>
                </p>


                @if ($task->completed_at)

                    <p>
                        Completed:

                        <span class="font-medium text-success">
                            {{ $task->completed_at->format('M d, Y · H:i') }}
                        </span>
                    </p>

                @endif

            </div>

        </div>


        {{-- Update Actions --}}
        <div
            class="
                flex flex-col-reverse
                gap-3
                border-t border-border
                pt-6
                sm:flex-row
                sm:justify-end
            "
        >

            <a
                href="{{ route('tasks.index') }}"
                class="
                    inline-flex items-center justify-center
                    rounded-2xl
                    border border-border
                    bg-white
                    px-5 py-3
                    text-sm font-medium
                    text-text-secondary
                    transition
                    hover:border-primary
                    hover:text-primary
                "
            >
                Cancel
            </a>


            <button
                type="submit"
                class="
                    inline-flex items-center justify-center
                    rounded-2xl
                    bg-primary
                    px-6 py-3
                    text-sm font-semibold
                    text-white
                    shadow-sm
                    transition
                    hover:bg-primary-hover
                "
            >
                Save Changes
            </button>

        </div>

    </form>


    {{-- DELETE SECTION --}}
    <section
        class="
            mt-6
            rounded-3xl
            border border-danger/20
            bg-danger/5
            p-6
        "
    >

        <div
            class="
                flex flex-col
                gap-4
                sm:flex-row
                sm:items-center
                sm:justify-between
            "
        >

            <div>

                <h2 class="font-semibold text-text-primary">
                    Delete task
                </h2>

                <p class="mt-1 text-sm text-text-secondary">
                    Permanently remove this task.
                    This action cannot be undone.
                </p>

            </div>


            {{-- Separate Delete Form --}}
            <form
                method="POST"
                action="{{ route('tasks.destroy', $task) }}"
                onsubmit="return confirm('Are you sure you want to delete this task?')"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="
                        w-full
                        rounded-2xl
                        border border-danger/20
                        bg-white
                        px-5 py-3
                        text-sm font-semibold
                        text-danger
                        transition
                        hover:bg-danger/10
                        sm:w-auto
                    "
                >
                    Delete Task
                </button>

            </form>

        </div>

    </section>

</div>

@endsection
