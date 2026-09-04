@extends('layouts.app')

@section('title', 'Categories | PLANSI')

@section('content')

    {{-- Page Header --}}
    <section
        class="
            mb-8
            flex flex-col
            gap-4
            sm:flex-row
            sm:items-end
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
                Categories
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
                Create simple categories to organize
                your tasks and keep your workspace clear.
            </p>

        </div>


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
            View My Tasks
        </a>

    </section>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div
            class="
                mb-6
                rounded-2xl
                border
                border-danger/20
                bg-danger/5
                px-5
                py-4
            "
        >

            <p class="mb-2 text-sm font-semibold text-danger">
                Please check the following:
            </p>

            <ul
                class="
                    list-disc
                    space-y-1
                    pl-5
                    text-sm
                    text-danger
                "
            >

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Main Layout --}}
    <div
        class="
            grid
            grid-cols-1
            gap-6
            lg:grid-cols-[360px_1fr]
        "
    >

        {{-- Create Category --}}
        <aside>

            <div
                class="
                    rounded-3xl
                    border
                    border-border
                    bg-surface
                    p-6
                    shadow-sm
                    lg:sticky
                    lg:top-28
                "
            >

                <div
                    class="
                        mb-6
                        flex
                        h-12
                        w-12
                        items-center
                        justify-center
                        rounded-2xl
                        bg-mint-soft
                        text-xl
                        font-semibold
                        text-primary
                    "
                >
                    +
                </div>


                <h2
                    class="
                        text-xl
                        font-semibold
                        text-text-primary
                    "
                >
                    Create category
                </h2>

                <p
                    class="
                        mt-2
                        text-sm
                        leading-6
                        text-text-secondary
                    "
                >
                    Group related tasks under one
                    simple category.
                </p>


                <form
                    method="POST"
                    action="{{ route('categories.store') }}"
                    class="mt-6"
                >

                    @csrf


                    <div>

                        <label
                            for="name"
                            class="
                                mb-2
                                block
                                text-sm
                                font-semibold
                                text-text-primary
                            "
                        >
                            Category Name

                            <span class="text-danger">
                                *
                            </span>
                        </label>


                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. Work, Study, Personal"
                            required
                            maxlength="100"
                            class="
                                w-full
                                rounded-2xl
                                border
                                border-border
                                bg-background
                                px-4
                                py-3.5
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

                        @error('name')

                            <p class="mt-2 text-sm text-danger">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    <button
                        type="submit"
                        class="
                            mt-5
                            inline-flex
                            w-full
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
                        <span>
                            +
                        </span>

                        Create Category
                    </button>

                </form>

            </div>

        </aside>


        {{-- Categories List --}}
        <section>

            {{-- List Header --}}
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
                        My Categories
                    </h2>

                    <p
                        class="
                            mt-1
                            text-sm
                            text-text-secondary
                        "
                    >
                        {{ $categories->count() }}

                        {{ $categories->count() === 1
                            ? 'category'
                            : 'categories'
                        }}
                    </p>

                </div>

            </div>


            {{-- Empty State --}}
            @if ($categories->isEmpty())

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
                        #
                    </div>


                    <h3
                        class="
                            text-xl
                            font-semibold
                            text-text-primary
                        "
                    >
                        No categories yet
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
                        Create your first category
                        to start organizing related tasks.
                    </p>

                </div>


            @else

                {{-- Category Cards --}}
                <div
                    class="
                        grid
                        grid-cols-1
                        gap-4
                        xl:grid-cols-2
                    "
                >

                    @foreach ($categories as $category)

                        <article
                            class="
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

                            {{-- Card Header --}}
                            <div
                                class="
                                    mb-5
                                    flex
                                    items-start
                                    justify-between
                                    gap-4
                                "
                            >

                                <div
                                    class="
                                        flex
                                        min-w-0
                                        items-center
                                        gap-3
                                    "
                                >

                                    <div
                                        class="
                                            flex
                                            h-11
                                            w-11
                                            shrink-0
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


                                    <div class="min-w-0">

                                        <h3
                                            class="
                                                truncate
                                                font-semibold
                                                text-text-primary
                                            "
                                        >
                                            {{ $category->name }}
                                        </h3>

                                        <p
                                            class="
                                                mt-1
                                                text-sm
                                                text-text-secondary
                                            "
                                        >
                                            {{ $category->tasks_count }}

                                            {{ $category->tasks_count === 1
                                                ? 'task'
                                                : 'tasks'
                                            }}
                                        </p>

                                    </div>

                                </div>


                                {{-- Task Count Badge --}}
                                <span
                                    class="
                                        shrink-0
                                        rounded-full
                                        bg-background
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-text-secondary
                                    "
                                >
                                    {{ $category->tasks_count }}
                                </span>

                            </div>


                            {{-- Update Form --}}
                            <form
                                method="POST"
                                action="{{ route('categories.update', $category) }}"
                            >

                                @csrf
                                @method('PUT')


                                <label
                                    for="category-{{ $category->id }}"
                                    class="
                                        mb-2
                                        block
                                        text-sm
                                        font-medium
                                        text-text-primary
                                    "
                                >
                                    Category Name
                                </label>


                                <input
                                    type="text"
                                    id="category-{{ $category->id }}"
                                    name="name"
                                    value="{{ $category->name }}"
                                    required
                                    maxlength="100"
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
                                        hover:border-primary/50
                                        focus:border-primary
                                        focus:ring-4
                                        focus:ring-mint-soft
                                    "
                                >


                                <button
                                    type="submit"
                                    class="
                                        mt-3
                                        inline-flex
                                        w-full
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-mint-soft
                                        px-4
                                        py-3
                                        text-sm
                                        font-semibold
                                        text-primary
                                        transition
                                        hover:bg-mint
                                    "
                                >
                                    Save Changes
                                </button>

                            </form>


                            {{-- Divider --}}
                            <div
                                class="
                                    my-5
                                    border-t
                                    border-border
                                "
                            ></div>


                            {{-- Delete Form --}}
                            <form
                                method="POST"
                                action="{{ route('categories.destroy', $category) }}"
                                onsubmit="
                                    return confirm(
                                        'Are you sure you want to delete this category?'
                                    )
                                "
                            >

                                @csrf
                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="
                                        inline-flex
                                        w-full
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        border
                                        border-danger/20
                                        bg-danger/5
                                        px-4
                                        py-3
                                        text-sm
                                        font-medium
                                        text-danger
                                        transition
                                        hover:bg-danger/10
                                    "
                                >
                                    Delete Category
                                </button>

                            </form>

                        </article>

                    @endforeach

                </div>

            @endif

        </section>

    </div>

@endsection
