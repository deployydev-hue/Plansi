@extends('layouts.app')

@section('title', 'My Tasks | PLANSI')

@section('content')

    {{-- Page Header --}}
    <section
        class="
            mb-8
            flex flex-col
            gap-5
            sm:flex-row
            sm:items-center
            sm:justify-between
        "
    >

        <div>
            <p class="mb-1 text-sm font-medium text-primary">
                Workspace
            </p>

            <h1
                class="
                    text-3xl
                    font-semibold
                    tracking-tight
                    text-text-primary
                "
            >
                My Tasks
            </h1>

            <p
                class="
                    mt-2
                    max-w-xl
                    text-sm
                    leading-6
                    text-text-secondary
                "
            >
                Organize your work, focus on what matters,
                and keep track of your progress.
            </p>
        </div>


        <a
            href="{{ route('tasks.create') }}"
            class="
                inline-flex
                items-center
                justify-center
                gap-2
                rounded-2xl
                bg-primary
                px-5
                py-3
                text-sm
                font-semibold
                text-white
                shadow-sm
                transition
                hover:bg-primary-hover
            "
        >
            <span class="text-lg leading-none">
                +
            </span>

            Create Task
        </a>

    </section>


    {{-- Success Message --}}
    @if (session('success'))

        <div
            class="
                mb-6
                rounded-2xl
                border
                border-success/20
                bg-mint-soft
                px-5
                py-4
                text-sm
                font-medium
                text-success
            "
        >
            {{ session('success') }}
        </div>

    @endif


    {{-- Search, Filters & Sort --}}
    <section
        class="
            mb-8
            rounded-3xl
            border
            border-border
            bg-surface
            p-5
            shadow-sm
        "
    >

        {{-- Filter Header --}}
        <div class="mb-5">

            <h2 class="font-semibold text-text-primary">
                Find your tasks
            </h2>

            <p class="mt-1 text-sm text-text-secondary">
                Search, filter and sort your task list.
            </p>

        </div>


        <form
            method="GET"
            action="{{ route('tasks.index') }}"
        >

            <div
                class="
                    grid
                    grid-cols-1
                    gap-4
                    md:grid-cols-2
                    xl:grid-cols-6
                "
            >

                {{-- Search --}}
                <div class="md:col-span-2 xl:col-span-2">

                    <label
                        for="search"
                        class="
                            mb-2
                            block
                            text-sm
                            font-medium
                            text-text-primary
                        "
                    >
                        Search
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        placeholder="Search title or description..."
                        value="{{ request('search') }}"
                        maxlength="150"
                        class="
                            w-full
                            rounded-2xl
                            border
                            border-border
                            bg-background
                            px-4
                            py-3
                            text-sm
                            text-text-primary
                            outline-none
                            transition
                            placeholder:text-text-secondary/60
                            hover:border-primary/50
                            focus:border-primary
                            focus:ring-4
                            focus:ring-mint-soft
                        "
                    >

                </div>


                {{-- Status --}}
                <div>

                    <x-select
                        name="status"
                        label="Status"
                        :options="[
                            'pending' => 'Pending',
                            'completed' => 'Completed',
                        ]"
                        :selected="request('status', '')"
                        placeholder="All Statuses"
                    />

                </div>


                {{-- Priority --}}
                <div>

                    <x-select
                        name="priority"
                        label="Priority"
                        :options="[
                            'low' => 'Low',
                            'medium' => 'Medium',
                            'high' => 'High',
                        ]"
                        :selected="request('priority', '')"
                        placeholder="All Priorities"
                    />

                </div>


                {{-- Category --}}
                <div>

                    <x-select
                        name="category_id"
                        label="Category"
                        :options="$categories->pluck('name', 'id')->toArray()"
                        :selected="request('category_id', '')"
                        placeholder="All Categories"
                    />

                </div>


                {{-- Due Date --}}
                <div>

                    <x-select
                        name="due_date"
                        label="Due Date"
                        :options="[
                            'today' => 'Due Today',
                            'overdue' => 'Overdue',
                            'upcoming' => 'Upcoming',
                            'no_due' => 'No Due Date',
                        ]"
                        :selected="request('due_date', '')"
                        placeholder="All Dates"
                    />

                </div>

            </div>


            {{-- Sort & Actions --}}
            <div
                class="
                    mt-5
                    flex flex-col
                    gap-4
                    border-t
                    border-border
                    pt-5
                    md:flex-row
                    md:items-end
                    md:justify-between
                "
            >

                {{-- Sort --}}
                <div class="w-full md:max-w-xs">

                    <x-select
                        name="sort"
                        label="Sort By"
                        :options="[
                            'newest' => 'Newest First',
                            'oldest' => 'Oldest First',
                            'due_soon' => 'Due Soon',
                            'priority_high' => 'High Priority First',
                        ]"
                        :selected="request('sort', 'newest')"
                        placeholder="Sort Tasks"
                    />

                </div>


                {{-- Buttons --}}
                <div
                    class="
                        flex
                        flex-col
                        gap-3
                        sm:flex-row
                    "
                >

                    <a
                        href="{{ route('tasks.index') }}"
                        class="
                            inline-flex
                            items-center
                            justify-center
                            rounded-2xl
                            border
                            border-border
                            bg-white
                            px-5
                            py-3
                            text-sm
                            font-medium
                            text-text-secondary
                            transition
                            hover:border-primary
                            hover:text-primary
                        "
                    >
                        Clear Filters
                    </a>


                    <button
                        type="submit"
                        class="
                            inline-flex
                            items-center
                            justify-center
                            rounded-2xl
                            bg-primary
                            px-6
                            py-3
                            text-sm
                            font-semibold
                            text-white
                            shadow-sm
                            transition
                            hover:bg-primary-hover
                        "
                    >
                        Apply Filters
                    </button>

                </div>

            </div>

        </form>

    </section>


    {{-- Results Header --}}
    <div
        class="
            mb-5
            flex
            items-center
            justify-between
            gap-4
        "
    >

        <div>

            <h2
                class="
                    text-lg
                    font-semibold
                    text-text-primary
                "
            >
                Tasks
            </h2>

            <p class="mt-1 text-sm text-text-secondary">

                {{ $tasks->count() }}

                {{ $tasks->count() === 1 ? 'task' : 'tasks' }}

                found

            </p>

        </div>

    </div>


    {{-- Empty State --}}
    @if ($tasks->isEmpty())

        <section
            class="
                flex
                min-h-80
                flex-col
                items-center
                justify-center
                rounded-3xl
                border
                border-dashed
                border-border
                bg-surface
                px-6
                py-12
                text-center
            "
        >

            <div
                class="
                    mb-5
                    flex
                    h-16
                    w-16
                    items-center
                    justify-center
                    rounded-2xl
                    bg-mint-soft
                    text-2xl
                    text-primary
                "
            >
                ✓
            </div>


            <h3
                class="
                    text-xl
                    font-semibold
                    text-text-primary
                "
            >
                {{ request()->query() ? 'No matching tasks' : 'Your task list is empty' }}
            </h3>


            <p
                class="
                    mt-2
                    max-w-sm
                    text-sm
                    leading-6
                    text-text-secondary
                "
            >
                @if (request()->query())
                    Try broadening your search or clearing the active filters.
                @else
                    Create your first task to start organizing your day.
                @endif
            </p>


            <div
                class="
                    mt-6
                    flex
                    flex-col
                    gap-3
                    sm:flex-row
                "
            >

                @if (request()->query())
                    <a
                        href="{{ route('tasks.index') }}"
                    class="
                        rounded-2xl
                        border
                        border-border
                        bg-white
                        px-5
                        py-3
                        text-sm
                        font-medium
                        text-text-secondary
                        transition
                        hover:border-primary
                        hover:text-primary
                    "
                >
                        Clear Filters
                    </a>
                @endif


                <a
                    href="{{ route('tasks.create') }}"
                    class="
                        rounded-2xl
                        bg-primary
                        px-5
                        py-3
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-primary-hover
                    "
                >
                    Create Task
                </a>

            </div>

        </section>


    @else

        {{-- Tasks Grid --}}
        <section
            class="
                grid
                grid-cols-1
                gap-5
                md:grid-cols-2
                xl:grid-cols-3
            "
        >

            @foreach ($tasks as $task)

                <article
                    class="
                        group
                        flex
                        flex-col
                        rounded-3xl
                        border
                        border-border
                        bg-surface
                        p-5
                        shadow-sm
                        transition
                        duration-200
                        hover:-translate-y-0.5
                        hover:shadow-md
                    "
                >

                    {{-- Priority + Status --}}
                    <div
                        class="
                            mb-5
                            flex
                            items-start
                            justify-between
                            gap-4
                        "
                    >

                        {{-- Priority --}}
                        @if ($task->priority === 'high')

                            <span
                                class="
                                    inline-flex
                                    rounded-full
                                    bg-danger/10
                                    px-3
                                    py-1
                                    text-xs
                                    font-semibold
                                    text-danger
                                "
                            >
                                High Priority
                            </span>


                        @elseif ($task->priority === 'medium')

                            <span
                                class="
                                    inline-flex
                                    rounded-full
                                    bg-yellow-soft
                                    px-3
                                    py-1
                                    text-xs
                                    font-semibold
                                    text-text-primary
                                "
                            >
                                Medium Priority
                            </span>


                        @else

                            <span
                                class="
                                    inline-flex
                                    rounded-full
                                    bg-mint-soft
                                    px-3
                                    py-1
                                    text-xs
                                    font-semibold
                                    text-success
                                "
                            >
                                Low Priority
                            </span>

                        @endif


                        {{-- Status --}}
                        @if ($task->status === 'completed')

                            <span
                                class="
                                    text-xs
                                    font-semibold
                                    text-success
                                "
                            >
                                ✓ Completed
                            </span>

                        @else

                            <span
                                class="
                                    text-xs
                                    font-medium
                                    text-text-secondary
                                "
                            >
                                Pending
                            </span>

                        @endif

                    </div>


                    {{-- Task Content --}}
                    <div class="flex-1">

                        <h3
                            class="
                                text-lg
                                font-semibold
                                leading-6
                                text-text-primary

                                {{ $task->status === 'completed'
                                    ? 'line-through opacity-60'
                                    : ''
                                }}
                            "
                        >
                            {{ $task->title }}
                        </h3>


                        {{-- Description --}}
                        @if ($task->description)

                            <p
                                class="
                                    mt-2
                                    line-clamp-3
                                    text-sm
                                    leading-6
                                    text-text-secondary
                                "
                            >
                                {{ $task->description }}
                            </p>

                        @endif


                        {{-- Metadata --}}
                        <div
                            class="
                                mt-5
                                space-y-3
                                text-sm
                            "
                        >

                            {{-- Category --}}
                            <div
                                class="
                                    flex
                                    items-center
                                    justify-between
                                    gap-4
                                "
                            >

                                <span class="text-text-secondary">
                                    Category
                                </span>

                                <span
                                    class="
                                        rounded-full
                                        bg-background
                                        px-3
                                        py-1
                                        font-medium
                                        text-text-primary
                                    "
                                >
                                    {{ $task->category?->name ?? 'No Category' }}
                                </span>

                            </div>


                            {{-- Due Date --}}
                            <div
                                class="
                                    flex
                                    items-center
                                    justify-between
                                    gap-4
                                "
                            >

                                <span class="text-text-secondary">
                                    Due Date
                                </span>

                                <span
                                    class="
                                        text-right
                                        font-medium
                                        text-text-primary
                                    "
                                >
                                    {{ $task->due_at
                                        ? $task->due_at->format('M d, Y · H:i')
                                        : 'No due date'
                                    }}
                                </span>

                            </div>

                        </div>


                        {{-- Due Status --}}
                        @if (
                            $task->due_at &&
                            $task->status === 'pending'
                        )

                            <div class="mt-4">

                                @if ($task->due_at->isBefore(today()))

                                    <span
                                        class="
                                            inline-flex
                                            rounded-xl
                                            bg-danger/10
                                            px-3
                                            py-2
                                            text-xs
                                            font-semibold
                                            text-danger
                                        "
                                    >
                                        Overdue
                                    </span>


                                @elseif ($task->due_at->isToday())

                                    <span
                                        class="
                                            inline-flex
                                            rounded-xl
                                            bg-yellow-soft
                                            px-3
                                            py-2
                                            text-xs
                                            font-semibold
                                            text-text-primary
                                        "
                                    >
                                        Due Today
                                    </span>


                                @else

                                    <span
                                        class="
                                            inline-flex
                                            rounded-xl
                                            bg-mint-soft
                                            px-3
                                            py-2
                                            text-xs
                                            font-semibold
                                            text-primary
                                        "
                                    >
                                        Upcoming
                                    </span>

                                @endif

                            </div>

                        @endif


                        {{-- Completed Date --}}
                        @if ($task->completed_at)

                            <p
                                class="
                                    mt-4
                                    text-xs
                                    text-text-secondary
                                "
                            >
                                Completed
                                {{ $task->completed_at->format('M d, Y · H:i') }}
                            </p>

                        @endif

                    </div>


                    {{-- Actions --}}
                    <div
                        class="
                            mt-6
                            border-t
                            border-border
                            pt-4
                        "
                    >

                        {{-- Toggle --}}
                        <form
                            method="POST"
                            action="{{ route('tasks.toggle', $task) }}"
                            class="mb-3"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="
                                    w-full
                                    rounded-2xl
                                    px-4
                                    py-3
                                    text-sm
                                    font-semibold
                                    transition

                                    {{ $task->status === 'completed'
                                        ? 'border border-border bg-background text-text-secondary hover:border-primary hover:text-primary'
                                        : 'bg-mint-soft text-primary hover:bg-mint'
                                    }}
                                "
                            >
                                {{ $task->status === 'completed'
                                    ? 'Mark as Pending'
                                    : '✓ Mark as Completed'
                                }}
                            </button>

                        </form>


                        {{-- Edit / Delete --}}
                        <div
                            class="
                                grid
                                grid-cols-2
                                gap-3
                            "
                        >

                            <a
                                href="{{ route('tasks.edit', $task) }}"
                                class="
                                    flex
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    border
                                    border-border
                                    bg-white
                                    px-4
                                    py-2.5
                                    text-sm
                                    font-medium
                                    text-text-secondary
                                    transition
                                    hover:border-primary
                                    hover:text-primary
                                "
                            >
                                Edit
                            </a>


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
                                        border
                                        border-danger/20
                                        bg-danger/5
                                        px-4
                                        py-2.5
                                        text-sm
                                        font-medium
                                        text-danger
                                        transition
                                        hover:bg-danger/10
                                    "
                                >
                                    Delete
                                </button>

                            </form>

                        </div>

                    </div>

                </article>

            @endforeach

        </section>

    @endif

@endsection
