@extends('layouts.app')

@section('title', 'Create Task | PLANSI')

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
            New Task
        </p>

        <h1
            class="
                text-3xl font-semibold tracking-tight
                text-text-primary
            "
        >
            Create a task
        </h1>

        <p class="mt-2 text-sm leading-6 text-text-secondary">
            Add the details, choose a priority,
            and set a due date when needed.
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


    {{-- Form --}}
    <form
        method="POST"
        action="{{ route('tasks.store') }}"
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
                value="{{ old('title') }}"
                placeholder="What needs to be done?"
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
                    placeholder:text-text-secondary/60
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
                placeholder="Add any useful details..."
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
                    placeholder:text-text-secondary/60
                    hover:border-primary/50
                    focus:border-primary
                    focus:ring-4
                    focus:ring-mint-soft
                "
            >{{ old('description') }}</textarea>

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
                :selected="old('priority', 'medium')"
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
                :selected="old('status', 'pending')"
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
                :selected="old('category_id', '')"
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
                    value="{{ old('due_at') }}"
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


        {{-- Actions --}}
        <div
            class="
                flex flex-col-reverse
                gap-3
                border-t border-border
                pt-6
                sm:flex-row
                sm:items-center
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
                    inline-flex items-center justify-center gap-2
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
                <span>+</span>

                Create Task
            </button>

        </div>

    </form>

</div>

@endsection
