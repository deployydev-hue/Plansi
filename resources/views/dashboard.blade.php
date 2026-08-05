@extends('layouts.app')

@section('title', 'Dashboard | PLANSI')

@section('content')


    {{-- Welcome Header --}}
    <section
        class="
            mb-8
            flex
            flex-col
            gap-5
            lg:flex-row
            lg:items-end
            lg:justify-between
        "
    >

        <div>

            <p
                class="
                    mb-1
                    text-sm
                    font-medium
                    text-primary
                "
            >
                Dashboard
            </p>


            <h1
                class="
                    text-3xl
                    font-semibold
                    tracking-tight
                    text-text-primary
                    sm:text-4xl
                "
            >
                Welcome back,
                {{ auth()->user()->name }}
            </h1>


            <p
                class="
                    mt-2
                    max-w-2xl
                    text-sm
                    leading-6
                    text-text-secondary
                "
            >
                Here is a quick look at your tasks
                and what needs your attention today.
            </p>

        </div>


        {{-- Primary Action --}}
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



    {{-- Main Overview --}}
    <section class="mb-8">

        <div class="mb-5">

            <h2
                class="
                    text-lg
                    font-semibold
                    text-text-primary
                "
            >
                Overview
            </h2>

            <p
                class="
                    mt-1
                    text-sm
                    text-text-secondary
                "
            >
                Your workspace at a glance.
            </p>

        </div>



        {{-- Statistics Grid --}}
        <div
            class="
                grid
                grid-cols-1
                gap-4
                sm:grid-cols-2
                xl:grid-cols-4
            "
        >


            {{-- Total Tasks --}}
            <article
                class="
                    rounded-3xl
                    border
                    border-border
                    bg-surface
                    p-5
                    shadow-sm
                "
            >

                <div
                    class="
                        mb-5
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-2xl
                        bg-mint-soft
                        text-lg
                        font-semibold
                        text-primary
                    "
                >
                    ✓
                </div>


                <p
                    class="
                        text-sm
                        font-medium
                        text-text-secondary
                    "
                >
                    Total Tasks
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-semibold
                        tracking-tight
                        text-text-primary
                    "
                >
                    {{ $totalTasks }}
                </p>

            </article>



            {{-- Pending --}}
            <article
                class="
                    rounded-3xl
                    border
                    border-border
                    bg-surface
                    p-5
                    shadow-sm
                "
            >

                <div
                    class="
                        mb-5
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-2xl
                        bg-yellow-soft
                        text-lg
                    "
                >
                    ◷
                </div>


                <p
                    class="
                        text-sm
                        font-medium
                        text-text-secondary
                    "
                >
                    Pending
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-semibold
                        tracking-tight
                        text-text-primary
                    "
                >
                    {{ $pendingTasks }}
                </p>

            </article>



            {{-- Completed --}}
            <article
                class="
                    rounded-3xl
                    border
                    border-border
                    bg-surface
                    p-5
                    shadow-sm
                "
            >

                <div
                    class="
                        mb-5
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-2xl
                        bg-mint-soft
                        text-lg
                        font-semibold
                        text-success
                    "
                >
                    ✓
                </div>


                <p
                    class="
                        text-sm
                        font-medium
                        text-text-secondary
                    "
                >
                    Completed
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-semibold
                        tracking-tight
                        text-success
                    "
                >
                    {{ $completedTasks }}
                </p>

            </article>



            {{-- High Priority --}}
            <article
                class="
                    rounded-3xl
                    border
                    border-border
                    bg-surface
                    p-5
                    shadow-sm
                "
            >

                <div
                    class="
                        mb-5
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-2xl
                        bg-danger/10
                        text-lg
                        font-semibold
                        text-danger
                    "
                >
                    !
                </div>


                <p
                    class="
                        text-sm
                        font-medium
                        text-text-secondary
                    "
                >
                    High Priority
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-semibold
                        tracking-tight
                        text-danger
                    "
                >
                    {{ $highPriorityTasks }}
                </p>

            </article>



            {{-- Due Today --}}
            <article
                class="
                    rounded-3xl
                    border
                    border-border
                    bg-surface
                    p-5
                    shadow-sm
                "
            >

                <div
                    class="
                        mb-5
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-2xl
                        bg-yellow-soft
                        text-lg
                    "
                >
                    ◉
                </div>


                <p
                    class="
                        text-sm
                        font-medium
                        text-text-secondary
                    "
                >
                    Due Today
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-semibold
                        tracking-tight
                        text-text-primary
                    "
                >
                    {{ $dueTodayTasks }}
                </p>

            </article>



            {{-- Overdue --}}
            <article
                class="
                    rounded-3xl
                    border
                    border-danger/15
                    bg-danger/5
                    p-5
                    shadow-sm
                "
            >

                <div
                    class="
                        mb-5
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-2xl
                        bg-danger/10
                        text-lg
                        font-semibold
                        text-danger
                    "
                >
                    !
                </div>


                <p
                    class="
                        text-sm
                        font-medium
                        text-text-secondary
                    "
                >
                    Overdue
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-semibold
                        tracking-tight
                        text-danger
                    "
                >
                    {{ $overdueTasks }}
                </p>

            </article>



            {{-- Categories --}}
            <article
                class="
                    rounded-3xl
                    border
                    border-border
                    bg-surface
                    p-5
                    shadow-sm
                "
            >

                <div
                    class="
                        mb-5
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-2xl
                        bg-mint-soft
                        text-lg
                        font-semibold
                        text-primary
                    "
                >
                    #
                </div>


                <p
                    class="
                        text-sm
                        font-medium
                        text-text-secondary
                    "
                >
                    Categories
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-semibold
                        tracking-tight
                        text-text-primary
                    "
                >
                    {{ $categoriesCount }}
                </p>

            </article>



            {{-- Progress --}}
            <article
                class="
                    rounded-3xl
                    border
                    border-primary/10
                    bg-primary
                    p-5
                    text-white
                    shadow-sm
                "
            >

                <p
                    class="
                        text-sm
                        font-medium
                        text-white/70
                    "
                >
                    Completion
                </p>


                <div
                    class="
                        mt-2
                        flex
                        items-end
                        justify-between
                        gap-3
                    "
                >

                    <p
                        class="
                            text-3xl
                            font-semibold
                            tracking-tight
                        "
                    >
                        {{ $completionPercentage }}%
                    </p>


                    <span
                        class="
                            text-xs
                            font-medium
                            text-white/70
                        "
                    >
                        {{ $completedTasks }}
                        / {{ $totalTasks }}
                    </span>

                </div>


                {{-- Progress Bar --}}
                <div
                    class="
                        mt-5
                        h-2
                        overflow-hidden
                        rounded-full
                        bg-white/20
                    "
                >

                    <div
                        class="
                            h-full
                            rounded-full
                            bg-mint
                            transition-all
                            duration-500
                        "
                        style="width: {{ $completionPercentage }}%"
                    ></div>

                </div>

            </article>

        </div>

    </section>



    {{-- Lower Dashboard --}}
    <section
        class="
            grid
            grid-cols-1
            gap-6
            xl:grid-cols-[1fr_340px]
        "
    >


        {{-- Recent Tasks --}}
        <div>

            <div
                class="
                    mb-5
                    flex
                    items-end
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
                        Recent Tasks
                    </h2>


                    <p
                        class="
                            mt-1
                            text-sm
                            text-text-secondary
                        "
                    >
                        Your latest activity.
                    </p>

                </div>


                <a
                    href="{{ route('tasks.index') }}"
                    class="
                        text-sm
                        font-semibold
                        text-primary
                        transition
                        hover:text-primary-hover
                    "
                >
                    View all →
                </a>

            </div>



            @if ($recentTasks->isEmpty())

                {{-- Empty --}}
                <div
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
                            text-lg
                            font-semibold
                            text-text-primary
                        "
                    >
                        Your workspace is empty
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
                        Create your first task and start
                        organizing your work.
                    </p>


                    <a
                        href="{{ route('tasks.create') }}"
                        class="
                            mt-6
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
                        Create First Task
                    </a>

                </div>


            @else

                <div
                    class="
                        overflow-hidden
                        rounded-3xl
                        border
                        border-border
                        bg-surface
                        shadow-sm
                    "
                >

                    @foreach ($recentTasks as $task)

                        <div
                            class="
                                flex
                                flex-col
                                gap-4
                                border-b
                                border-border
                                p-5
                                last:border-b-0
                                sm:flex-row
                                sm:items-center
                                sm:justify-between
                            "
                        >

                            {{-- Task --}}
                            <div
                                class="
                                    flex
                                    min-w-0
                                    items-start
                                    gap-4
                                "
                            >

                                {{-- Status Indicator --}}
                                <div
                                    class="
                                        mt-1
                                        flex
                                        h-9
                                        w-9
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl

                                        {{ $task->status === 'completed'
                                            ? 'bg-mint-soft text-success'
                                            : 'bg-yellow-soft text-text-primary'
                                        }}
                                    "
                                >

                                    {{ $task->status === 'completed'
                                        ? '✓'
                                        : '•'
                                    }}

                                </div>


                                <div class="min-w-0">

                                    <h3
                                        class="
                                            truncate
                                            font-semibold
                                            text-text-primary

                                            {{ $task->status === 'completed'
                                                ? 'line-through opacity-60'
                                                : ''
                                            }}
                                        "
                                    >
                                        {{ $task->title }}
                                    </h3>


                                    <div
                                        class="
                                            mt-2
                                            flex
                                            flex-wrap
                                            items-center
                                            gap-2
                                            text-xs
                                            text-text-secondary
                                        "
                                    >

                                        {{-- Category --}}
                                        <span
                                            class="
                                                rounded-full
                                                bg-background
                                                px-2.5
                                                py-1
                                            "
                                        >
                                            {{ $task->category?->name ?? 'No Category' }}
                                        </span>


                                        {{-- Priority --}}
                                        @if ($task->priority === 'high')

                                            <span
                                                class="
                                                    rounded-full
                                                    bg-danger/10
                                                    px-2.5
                                                    py-1
                                                    font-medium
                                                    text-danger
                                                "
                                            >
                                                High
                                            </span>

                                        @elseif ($task->priority === 'medium')

                                            <span
                                                class="
                                                    rounded-full
                                                    bg-yellow-soft
                                                    px-2.5
                                                    py-1
                                                    font-medium
                                                    text-text-primary
                                                "
                                            >
                                                Medium
                                            </span>

                                        @else

                                            <span
                                                class="
                                                    rounded-full
                                                    bg-mint-soft
                                                    px-2.5
                                                    py-1
                                                    font-medium
                                                    text-success
                                                "
                                            >
                                                Low
                                            </span>

                                        @endif


                                        {{-- Due --}}
                                        @if ($task->due_at)

                                            <span>
                                                {{ $task->due_at->format('M d · H:i') }}
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>


                            {{-- Edit --}}
                            <a
                                href="{{ route('tasks.edit', $task) }}"
                                class="
                                    inline-flex
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-xl
                                    border
                                    border-border
                                    bg-white
                                    px-4
                                    py-2
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

                        </div>

                    @endforeach

                </div>

            @endif

        </div>



        {{-- Quick Actions --}}
        <aside>

            <h2
                class="
                    mb-5
                    text-lg
                    font-semibold
                    text-text-primary
                "
            >
                Quick Actions
            </h2>


            <div
                class="
                    rounded-3xl
                    border
                    border-border
                    bg-surface
                    p-5
                    shadow-sm
                "
            >


                {{-- Create Task --}}
                <a
                    href="{{ route('tasks.create') }}"
                    class="
                        flex
                        items-center
                        gap-4
                        rounded-2xl
                        bg-primary
                        p-4
                        text-white
                        transition
                        hover:bg-primary-hover
                    "
                >

                    <div
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-white/15
                            text-xl
                        "
                    >
                        +
                    </div>


                    <div>

                        <p class="text-sm font-semibold">
                            Create Task
                        </p>

                        <p class="mt-1 text-xs text-white/70">
                            Add something new.
                        </p>

                    </div>

                </a>



                {{-- Tasks --}}
                <a
                    href="{{ route('tasks.index') }}"
                    class="
                        mt-3
                        flex
                        items-center
                        gap-4
                        rounded-2xl
                        border
                        border-border
                        p-4
                        transition
                        hover:border-primary/30
                        hover:bg-mint-soft
                    "
                >

                    <div
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-mint-soft
                            font-semibold
                            text-primary
                        "
                    >
                        ✓
                    </div>


                    <div>

                        <p
                            class="
                                text-sm
                                font-semibold
                                text-text-primary
                            "
                        >
                            My Tasks
                        </p>

                        <p
                            class="
                                mt-1
                                text-xs
                                text-text-secondary
                            "
                        >
                            View and organize tasks.
                        </p>

                    </div>

                </a>



                {{-- Categories --}}
                <a
                    href="{{ route('categories.index') }}"
                    class="
                        mt-3
                        flex
                        items-center
                        gap-4
                        rounded-2xl
                        border
                        border-border
                        p-4
                        transition
                        hover:border-primary/30
                        hover:bg-mint-soft
                    "
                >

                    <div
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-mint-soft
                            font-semibold
                            text-primary
                        "
                    >
                        #
                    </div>


                    <div>

                        <p
                            class="
                                text-sm
                                font-semibold
                                text-text-primary
                            "
                        >
                            Categories
                        </p>

                        <p
                            class="
                                mt-1
                                text-xs
                                text-text-secondary
                            "
                        >
                            Manage your workspace.
                        </p>

                    </div>

                </a>

            </div>


            {{-- Small Productivity Card --}}
            <div
                class="
                    mt-5
                    rounded-3xl
                    bg-yellow-soft
                    p-5
                "
            >

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-wide
                        text-text-secondary
                    "
                >
                    Today
                </p>


                <p
                    class="
                        mt-2
                        text-lg
                        font-semibold
                        text-text-primary
                    "
                >
                    {{ $dueTodayTasks }}
                    {{ $dueTodayTasks === 1 ? 'task' : 'tasks' }}
                    due today
                </p>


                <p
                    class="
                        mt-2
                        text-sm
                        leading-6
                        text-text-secondary
                    "
                >
                    Focus on what matters most
                    and keep the list manageable.
                </p>

            </div>

        </aside>

    </section>

@endsection
