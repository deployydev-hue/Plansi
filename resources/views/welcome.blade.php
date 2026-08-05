<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>PLANSI | Plan what matters.</title>

    <meta
        name="description"
        content="PLANSI is a calm productivity workspace for organizing tasks, priorities, categories and deadlines."
    >

    <link rel="icon" href="{{ asset('brand/favicon.svg') }}" type="image/svg+xml">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>


<body class="bg-background text-text-primary">

    <div
        x-data="{ mobileMenu: false }"
        class="min-h-screen"
    >

        {{-- ========================================= --}}
        {{-- NAVBAR --}}
        {{-- ========================================= --}}

        <header
            class="
                sticky
                top-0
                z-50
                border-b
                border-border
                bg-background/90
                backdrop-blur-xl
            "
        >

            <div
                class="
                    mx-auto
                    flex
                    max-w-7xl
                    items-center
                    justify-between
                    px-5
                    py-4
                    sm:px-6
                    lg:px-8
                "
            >

                {{-- Brand --}}
                <a
                    href="{{ url('/') }}"
                    class="
                        flex
                        items-center
                        gap-3
                    "
                >

                    <img src="{{ asset('brand/logo.svg') }}" alt="PLANSI — Plan what matters." class="h-11 w-auto max-w-[12.5rem] sm:max-w-[14rem]">

                </a>


                {{-- Desktop Navigation --}}
                <nav
                    class="
                        hidden
                        items-center
                        gap-1
                        md:flex
                    "
                >

                    <a
                        href="#features"
                        class="
                            rounded-xl
                            px-4
                            py-2
                            text-sm
                            font-medium
                            text-text-secondary
                            transition
                            hover:bg-mint-soft
                            hover:text-primary
                        "
                    >
                        Features
                    </a>


                    <a
                        href="#how-it-works"
                        class="
                            rounded-xl
                            px-4
                            py-2
                            text-sm
                            font-medium
                            text-text-secondary
                            transition
                            hover:bg-mint-soft
                            hover:text-primary
                        "
                    >
                        How It Works
                    </a>


                    <a
                        href="#web-mobile"
                        class="
                            rounded-xl
                            px-4
                            py-2
                            text-sm
                            font-medium
                            text-text-secondary
                            transition
                            hover:bg-mint-soft
                            hover:text-primary
                        "
                    >
                        Web & Mobile
                    </a>

                </nav>


                {{-- Desktop Auth Actions --}}
                <div
                    class="
                        hidden
                        items-center
                        gap-3
                        md:flex
                    "
                >

                    @auth

                        <a
                            href="{{ route('dashboard') }}"
                            class="
                                inline-flex
                                items-center
                                justify-center
                                rounded-2xl
                                bg-primary
                                px-5
                                py-2.5
                                text-sm
                                font-semibold
                                text-white
                                transition
                                hover:bg-primary-hover
                            "
                        >
                            Open Dashboard
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="
                                rounded-2xl
                                px-4
                                py-2.5
                                text-sm
                                font-semibold
                                text-text-secondary
                                transition
                                hover:text-primary
                            "
                        >
                            Sign In
                        </a>


                        <a
                            href="{{ route('register') }}"
                            class="
                                inline-flex
                                items-center
                                justify-center
                                rounded-2xl
                                bg-primary
                                px-5
                                py-2.5
                                text-sm
                                font-semibold
                                text-white
                                transition
                                hover:bg-primary-hover
                            "
                        >
                            Get Started
                        </a>

                    @endauth

                </div>


                {{-- Mobile Menu Button --}}
                <button
                    type="button"
                    @click="mobileMenu = !mobileMenu"
                    class="
                        flex
                        h-10
                        w-10
                        items-center
                        justify-center
                        rounded-xl
                        border
                        border-border
                        bg-white
                        text-primary
                        md:hidden
                    "
                    aria-label="Open navigation"
                >

                    <svg
                        x-show="!mobileMenu"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M4 7H20M4 12H20M4 17H20"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                        />
                    </svg>


                    <svg
                        x-cloak
                        x-show="mobileMenu"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M6 6L18 18M18 6L6 18"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                        />
                    </svg>

                </button>

            </div>


            {{-- Mobile Navigation --}}
            <div
                x-cloak
                x-show="mobileMenu"
                x-transition
                class="
                    border-t
                    border-border
                    bg-background
                    px-5
                    pb-5
                    pt-3
                    md:hidden
                "
            >

                <div class="space-y-1">

                    <a
                        href="#features"
                        @click="mobileMenu = false"
                        class="
                            block
                            rounded-xl
                            px-4
                            py-3
                            text-sm
                            font-medium
                            text-text-secondary
                            hover:bg-mint-soft
                            hover:text-primary
                        "
                    >
                        Features
                    </a>


                    <a
                        href="#how-it-works"
                        @click="mobileMenu = false"
                        class="
                            block
                            rounded-xl
                            px-4
                            py-3
                            text-sm
                            font-medium
                            text-text-secondary
                            hover:bg-mint-soft
                            hover:text-primary
                        "
                    >
                        How It Works
                    </a>


                    <a
                        href="#web-mobile"
                        @click="mobileMenu = false"
                        class="
                            block
                            rounded-xl
                            px-4
                            py-3
                            text-sm
                            font-medium
                            text-text-secondary
                            hover:bg-mint-soft
                            hover:text-primary
                        "
                    >
                        Web & Mobile
                    </a>

                </div>


                <div
                    class="
                        mt-4
                        border-t
                        border-border
                        pt-4
                    "
                >

                    @auth

                        <a
                            href="{{ route('dashboard') }}"
                            class="
                                flex
                                w-full
                                items-center
                                justify-center
                                rounded-2xl
                                bg-primary
                                px-5
                                py-3
                                text-sm
                                font-semibold
                                text-white
                            "
                        >
                            Open Dashboard
                        </a>

                    @else

                        <div
                            class="
                                grid
                                grid-cols-2
                                gap-3
                            "
                        >

                            <a
                                href="{{ route('login') }}"
                                class="
                                    flex
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    border
                                    border-border
                                    bg-white
                                    px-4
                                    py-3
                                    text-sm
                                    font-semibold
                                    text-text-secondary
                                "
                            >
                                Sign In
                            </a>


                            <a
                                href="{{ route('register') }}"
                                class="
                                    flex
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-primary
                                    px-4
                                    py-3
                                    text-sm
                                    font-semibold
                                    text-white
                                "
                            >
                                Get Started
                            </a>

                        </div>

                    @endauth

                </div>

            </div>

        </header>



        {{-- ========================================= --}}
        {{-- HERO --}}
        {{-- ========================================= --}}

        <main>

            <section
                class="
                    relative
                    overflow-hidden
                    px-5
                    pb-20
                    pt-16
                    sm:px-6
                    sm:pb-24
                    sm:pt-20
                    lg:px-8
                    lg:pb-32
                    lg:pt-28
                "
            >

                {{-- Background decoration --}}
                <div
                    class="
                        absolute
                        -right-40
                        -top-32
                        h-96
                        w-96
                        rounded-full
                        bg-mint-soft
                        blur-3xl
                    "
                ></div>


                <div
                    class="
                        relative
                        mx-auto
                        grid
                        max-w-7xl
                        items-center
                        gap-14
                        lg:grid-cols-[0.92fr_1.08fr]
                    "
                >

                    {{-- Hero Copy --}}
                    <div>

                        <div
                            class="
                                mb-6
                                inline-flex
                                items-center
                                gap-2
                                rounded-full
                                border
                                border-primary/10
                                bg-mint-soft
                                px-4
                                py-2
                                text-sm
                                font-semibold
                                text-primary
                            "
                        >

                            <span
                                class="
                                    h-2
                                    w-2
                                    rounded-full
                                    bg-success
                                "
                            ></span>

                            Simple planning. Clear progress.

                        </div>


                        <h1
                            class="
                                max-w-3xl
                                text-4xl
                                font-semibold
                                leading-[1.08]
                                tracking-tight
                                text-text-primary
                                sm:text-5xl
                                lg:text-6xl
                            "
                        >
                            Stay focused.
                            <span class="text-primary">
                                Get things done.
                            </span>
                        </h1>


                        <p
                            class="
                                mt-6
                                max-w-xl
                                text-base
                                leading-8
                                text-text-secondary
                                sm:text-lg
                            "
                        >
                            A calm workspace for organizing tasks,
                            priorities, categories and deadlines
                            without unnecessary complexity.
                        </p>


                        {{-- Hero Actions --}}
                        <div
                            class="
                                mt-8
                                flex
                                flex-col
                                gap-3
                                sm:flex-row
                            "
                        >

                            @auth

                                <a
                                    href="{{ route('dashboard') }}"
                                    class="
                                        inline-flex
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-2xl
                                        bg-primary
                                        px-6
                                        py-3.5
                                        text-sm
                                        font-semibold
                                        text-white
                                        shadow-sm
                                        transition
                                        hover:bg-primary-hover
                                    "
                                >
                                    Open Dashboard

                                    <span>
                                        →
                                    </span>
                                </a>

                            @else

                                <a
                                    href="{{ route('register') }}"
                                    class="
                                        inline-flex
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-2xl
                                        bg-primary
                                        px-6
                                        py-3.5
                                        text-sm
                                        font-semibold
                                        text-white
                                        shadow-sm
                                        transition
                                        hover:bg-primary-hover
                                    "
                                >
                                    Get Started

                                    <span>
                                        →
                                    </span>
                                </a>


                                <a
                                    href="{{ route('login') }}"
                                    class="
                                        inline-flex
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        border
                                        border-border
                                        bg-white
                                        px-6
                                        py-3.5
                                        text-sm
                                        font-semibold
                                        text-text-secondary
                                        transition
                                        hover:border-primary
                                        hover:text-primary
                                    "
                                >
                                    Sign In
                                </a>

                            @endauth

                        </div>


                        {{-- Small proof row --}}
                        <div
                            class="
                                mt-10
                                flex
                                flex-wrap
                                gap-x-6
                                gap-y-3
                                text-sm
                                text-text-secondary
                            "
                        >

                            <span class="flex items-center gap-2">
                                <span class="text-success">✓</span>
                                Priorities
                            </span>

                            <span class="flex items-center gap-2">
                                <span class="text-success">✓</span>
                                Categories
                            </span>

                            <span class="flex items-center gap-2">
                                <span class="text-success">✓</span>
                                Deadlines
                            </span>

                            <span class="flex items-center gap-2">
                                <span class="text-success">✓</span>
                                Progress
                            </span>

                        </div>

                    </div>



                    {{-- Hero Product Mockup --}}
                    <div class="relative">

                        {{-- Yellow Accent --}}
                        <div
                            class="
                                absolute
                                -left-5
                                -top-5
                                h-24
                                w-24
                                rounded-3xl
                                bg-yellow-soft
                            "
                        ></div>


                        <div
                            class="
                                relative
                                overflow-hidden
                                rounded-[2rem]
                                border
                                border-border
                                bg-white
                                p-4
                                shadow-xl
                                sm:p-5
                            "
                        >

                            {{-- Mock App Header --}}
                            <div
                                class="
                                    mb-5
                                    flex
                                    items-center
                                    justify-between
                                    gap-4
                                "
                            >

                                <div class="flex items-center gap-3">

                                    <div
                                        class="
                                            flex
                                            h-10
                                            w-10
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-primary
                                            font-bold
                                            text-white
                                        "
                                    >
                                        S
                                    </div>

                                    <div>

                                        <p
                                            class="
                                                text-sm
                                                font-semibold
                                                text-text-primary
                                            "
                                        >
                                            My Workspace
                                        </p>

                                        <p
                                            class="
                                                mt-0.5
                                                text-xs
                                                text-text-secondary
                                            "
                                        >
                                            Monday, August 3
                                        </p>

                                    </div>

                                </div>


                                <div
                                    class="
                                        flex
                                        h-9
                                        w-9
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-mint-soft
                                        text-primary
                                    "
                                >
                                    +
                                </div>

                            </div>


                            {{-- Stats --}}
                            <div
                                class="
                                    grid
                                    grid-cols-2
                                    gap-3
                                    sm:grid-cols-4
                                "
                            >

                                <div
                                    class="
                                        rounded-2xl
                                        bg-background
                                        p-4
                                    "
                                >
                                    <p class="text-xs text-text-secondary">
                                        Total
                                    </p>

                                    <p
                                        class="
                                            mt-2
                                            text-2xl
                                            font-semibold
                                            text-text-primary
                                        "
                                    >
                                        12
                                    </p>
                                </div>


                                <div
                                    class="
                                        rounded-2xl
                                        bg-yellow-soft
                                        p-4
                                    "
                                >
                                    <p class="text-xs text-text-secondary">
                                        Pending
                                    </p>

                                    <p
                                        class="
                                            mt-2
                                            text-2xl
                                            font-semibold
                                            text-text-primary
                                        "
                                    >
                                        5
                                    </p>
                                </div>


                                <div
                                    class="
                                        rounded-2xl
                                        bg-mint-soft
                                        p-4
                                    "
                                >
                                    <p class="text-xs text-text-secondary">
                                        Completed
                                    </p>

                                    <p
                                        class="
                                            mt-2
                                            text-2xl
                                            font-semibold
                                            text-success
                                        "
                                    >
                                        7
                                    </p>
                                </div>


                                <div
                                    class="
                                        rounded-2xl
                                        bg-danger/5
                                        p-4
                                    "
                                >
                                    <p class="text-xs text-text-secondary">
                                        Overdue
                                    </p>

                                    <p
                                        class="
                                            mt-2
                                            text-2xl
                                            font-semibold
                                            text-danger
                                        "
                                    >
                                        1
                                    </p>
                                </div>

                            </div>


                            {{-- Progress --}}
                            <div
                                class="
                                    mt-4
                                    rounded-3xl
                                    bg-primary
                                    p-5
                                    text-white
                                "
                            >

                                <div
                                    class="
                                        flex
                                        items-end
                                        justify-between
                                        gap-4
                                    "
                                >

                                    <div>

                                        <p class="text-xs text-white/60">
                                            Today
                                        </p>

                                        <p
                                            class="
                                                mt-1
                                                text-lg
                                                font-semibold
                                            "
                                        >
                                            Make progress, not noise.
                                        </p>

                                    </div>


                                    <span
                                        class="
                                            rounded-full
                                            bg-mint
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            text-primary
                                        "
                                    >
                                        68%
                                    </span>

                                </div>


                                <div
                                    class="
                                        mt-5
                                        h-2
                                        overflow-hidden
                                        rounded-full
                                        bg-white/15
                                    "
                                >
                                    <div
                                        class="
                                            h-full
                                            w-2/3
                                            rounded-full
                                            bg-mint
                                        "
                                    ></div>
                                </div>

                            </div>


                            {{-- Tasks Preview --}}
                            <div class="mt-4 space-y-3">

                                <div
                                    class="
                                        flex
                                        items-center
                                        justify-between
                                        gap-4
                                        rounded-2xl
                                        border
                                        border-border
                                        p-4
                                    "
                                >

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="
                                                h-4
                                                w-4
                                                rounded-full
                                                border-2
                                                border-primary
                                            "
                                        ></div>

                                        <div>

                                            <p
                                                class="
                                                    text-sm
                                                    font-semibold
                                                    text-text-primary
                                                "
                                            >
                                                Finish dashboard UI
                                            </p>

                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-text-secondary
                                                "
                                            >
                                                Web Development
                                            </p>

                                        </div>

                                    </div>


                                    <span
                                        class="
                                            rounded-full
                                            bg-danger/10
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            text-danger
                                        "
                                    >
                                        High
                                    </span>

                                </div>


                                <div
                                    class="
                                        flex
                                        items-center
                                        justify-between
                                        gap-4
                                        rounded-2xl
                                        border
                                        border-border
                                        p-4
                                    "
                                >

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="
                                                flex
                                                h-4
                                                w-4
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-success
                                                text-[9px]
                                                text-white
                                            "
                                        >
                                            ✓
                                        </div>

                                        <div>

                                            <p
                                                class="
                                                    text-sm
                                                    font-semibold
                                                    text-text-secondary
                                                    line-through
                                                "
                                            >
                                                Plan mobile screens
                                            </p>

                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-text-secondary
                                                "
                                            >
                                                Product
                                            </p>

                                        </div>

                                    </div>


                                    <span
                                        class="
                                            rounded-full
                                            bg-mint-soft
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            text-success
                                        "
                                    >
                                        Done
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>



            {{-- ========================================= --}}
            {{-- FEATURES --}}
            {{-- ========================================= --}}

            <section
                id="features"
                class="
                    border-y
                    border-border
                    bg-white
                    px-5
                    py-20
                    sm:px-6
                    lg:px-8
                    lg:py-28
                "
            >

                <div class="mx-auto max-w-7xl">

                    <div
                        class="
                            mx-auto
                            max-w-2xl
                            text-center
                        "
                    >

                        <p
                            class="
                                text-sm
                                font-semibold
                                text-primary
                            "
                        >
                            Everything you need
                        </p>


                        <h2
                            class="
                                mt-3
                                text-3xl
                                font-semibold
                                tracking-tight
                                text-text-primary
                                sm:text-4xl
                            "
                        >
                            Simple tools for a clearer day.
                        </h2>


                        <p
                            class="
                                mt-4
                                text-base
                                leading-7
                                text-text-secondary
                            "
                        >
                            PLANSI keeps the important things visible
                            without overwhelming you with unnecessary features.
                        </p>

                    </div>


                    <div
                        class="
                            mt-12
                            grid
                            grid-cols-1
                            gap-5
                            md:grid-cols-2
                            xl:grid-cols-4
                        "
                    >

                        {{-- Feature 1 --}}
                        <article
                            class="
                                rounded-3xl
                                border
                                border-border
                                bg-background
                                p-6
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-12
                                    w-12
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-mint-soft
                                    text-xl
                                    text-primary
                                "
                            >
                                ✓
                            </div>


                            <h3
                                class="
                                    mt-5
                                    text-lg
                                    font-semibold
                                    text-text-primary
                                "
                            >
                                Organize Tasks
                            </h3>


                            <p
                                class="
                                    mt-2
                                    text-sm
                                    leading-6
                                    text-text-secondary
                                "
                            >
                                Create, edit, complete and organize
                                everything from one clear workspace.
                            </p>

                        </article>


                        {{-- Feature 2 --}}
                        <article
                            class="
                                rounded-3xl
                                border
                                border-border
                                bg-background
                                p-6
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-12
                                    w-12
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-yellow-soft
                                    text-xl
                                "
                            >
                                !
                            </div>


                            <h3
                                class="
                                    mt-5
                                    text-lg
                                    font-semibold
                                    text-text-primary
                                "
                            >
                                Set Priorities
                            </h3>


                            <p
                                class="
                                    mt-2
                                    text-sm
                                    leading-6
                                    text-text-secondary
                                "
                            >
                                Separate low, medium and high-priority
                                work so your next action stays obvious.
                            </p>

                        </article>


                        {{-- Feature 3 --}}
                        <article
                            class="
                                rounded-3xl
                                border
                                border-border
                                bg-background
                                p-6
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-12
                                    w-12
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-mint-soft
                                    text-xl
                                    text-primary
                                "
                            >
                                #
                            </div>


                            <h3
                                class="
                                    mt-5
                                    text-lg
                                    font-semibold
                                    text-text-primary
                                "
                            >
                                Use Categories
                            </h3>


                            <p
                                class="
                                    mt-2
                                    text-sm
                                    leading-6
                                    text-text-secondary
                                "
                            >
                                Keep study, work, personal and project
                                tasks separated without creating clutter.
                            </p>

                        </article>


                        {{-- Feature 4 --}}
                        <article
                            class="
                                rounded-3xl
                                border
                                border-border
                                bg-background
                                p-6
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-12
                                    w-12
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-danger/5
                                    text-xl
                                    text-danger
                                "
                            >
                                ◷
                            </div>


                            <h3
                                class="
                                    mt-5
                                    text-lg
                                    font-semibold
                                    text-text-primary
                                "
                            >
                                Track Deadlines
                            </h3>


                            <p
                                class="
                                    mt-2
                                    text-sm
                                    leading-6
                                    text-text-secondary
                                "
                            >
                                See what is due today, upcoming
                                or overdue before deadlines become a problem.
                            </p>

                        </article>

                    </div>

                </div>

            </section>



            {{-- ========================================= --}}
            {{-- HOW IT WORKS --}}
            {{-- ========================================= --}}

            <section
                id="how-it-works"
                class="
                    px-5
                    py-20
                    sm:px-6
                    lg:px-8
                    lg:py-28
                "
            >

                <div class="mx-auto max-w-7xl">

                    <div
                        class="
                            grid
                            gap-12
                            lg:grid-cols-[0.8fr_1.2fr]
                            lg:items-center
                        "
                    >

                        <div>

                            <p
                                class="
                                    text-sm
                                    font-semibold
                                    text-primary
                                "
                            >
                                How it works
                            </p>


                            <h2
                                class="
                                    mt-3
                                    text-3xl
                                    font-semibold
                                    tracking-tight
                                    text-text-primary
                                    sm:text-4xl
                                "
                            >
                                From idea to done in three simple steps.
                            </h2>


                            <p
                                class="
                                    mt-5
                                    max-w-lg
                                    text-base
                                    leading-7
                                    text-text-secondary
                                "
                            >
                                PLANSI is designed to remove friction.
                                Add what matters, organize it, then focus on execution.
                            </p>

                        </div>


                        <div class="space-y-4">

                            {{-- Step 1 --}}
                            <article
                                class="
                                    flex
                                    gap-5
                                    rounded-3xl
                                    border
                                    border-border
                                    bg-white
                                    p-6
                                    shadow-sm
                                "
                            >

                                <span
                                    class="
                                        flex
                                        h-11
                                        w-11
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-primary
                                        text-sm
                                        font-semibold
                                        text-white
                                    "
                                >
                                    01
                                </span>


                                <div>

                                    <h3
                                        class="
                                            font-semibold
                                            text-text-primary
                                        "
                                    >
                                        Create your account
                                    </h3>


                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            leading-6
                                            text-text-secondary
                                        "
                                    >
                                        Set up your personal workspace
                                        and keep your tasks private to your account.
                                    </p>

                                </div>

                            </article>


                            {{-- Step 2 --}}
                            <article
                                class="
                                    flex
                                    gap-5
                                    rounded-3xl
                                    border
                                    border-border
                                    bg-white
                                    p-6
                                    shadow-sm
                                "
                            >

                                <span
                                    class="
                                        flex
                                        h-11
                                        w-11
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-mint-soft
                                        text-sm
                                        font-semibold
                                        text-primary
                                    "
                                >
                                    02
                                </span>


                                <div>

                                    <h3
                                        class="
                                            font-semibold
                                            text-text-primary
                                        "
                                    >
                                        Add and organize tasks
                                    </h3>


                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            leading-6
                                            text-text-secondary
                                        "
                                    >
                                        Add priority, category and deadline,
                                        then search, filter and sort when needed.
                                    </p>

                                </div>

                            </article>


                            {{-- Step 3 --}}
                            <article
                                class="
                                    flex
                                    gap-5
                                    rounded-3xl
                                    border
                                    border-border
                                    bg-white
                                    p-6
                                    shadow-sm
                                "
                            >

                                <span
                                    class="
                                        flex
                                        h-11
                                        w-11
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-yellow-soft
                                        text-sm
                                        font-semibold
                                        text-text-primary
                                    "
                                >
                                    03
                                </span>


                                <div>

                                    <h3
                                        class="
                                            font-semibold
                                            text-text-primary
                                        "
                                    >
                                        Focus and complete
                                    </h3>


                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            leading-6
                                            text-text-secondary
                                        "
                                    >
                                        Use the dashboard to understand your progress
                                        and complete what matters most.
                                    </p>

                                </div>

                            </article>

                        </div>

                    </div>

                </div>

            </section>



            {{-- ========================================= --}}
            {{-- WEB + MOBILE --}}
            {{-- ========================================= --}}

            <section
                id="web-mobile"
                class="
                    bg-primary
                    px-5
                    py-20
                    text-white
                    sm:px-6
                    lg:px-8
                    lg:py-28
                "
            >

                <div
                    class="
                        mx-auto
                        grid
                        max-w-7xl
                        items-center
                        gap-14
                        lg:grid-cols-2
                    "
                >

                    <div>

                        <p
                            class="
                                text-sm
                                font-semibold
                                text-mint
                            "
                        >
                            Web + Mobile
                        </p>


                        <h2
                            class="
                                mt-3
                                max-w-xl
                                text-3xl
                                font-semibold
                                tracking-tight
                                sm:text-4xl
                            "
                        >
                            One workspace.
                            Wherever you work.
                        </h2>


                        <p
                            class="
                                mt-5
                                max-w-xl
                                text-base
                                leading-7
                                text-white/70
                            "
                        >
                            PLANSI is designed as one consistent
                            product experience across web and mobile,
                            powered by the same task data and logic.
                        </p>


                        <div
                            class="
                                mt-8
                                grid
                                grid-cols-1
                                gap-3
                                sm:grid-cols-2
                            "
                        >

                            <div
                                class="
                                    rounded-2xl
                                    border
                                    border-white/10
                                    bg-white/5
                                    p-4
                                "
                            >
                                <p class="font-semibold">
                                    Web
                                </p>

                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        text-white/60
                                    "
                                >
                                    Spacious productivity dashboard.
                                </p>
                            </div>


                            <div
                                class="
                                    rounded-2xl
                                    border
                                    border-white/10
                                    bg-white/5
                                    p-4
                                "
                            >
                                <p class="font-semibold">
                                    Mobile
                                </p>

                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        text-white/60
                                    "
                                >
                                    Fast task access on the go.
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- Device Mockups --}}
                    <div
                        class="
                            flex
                            items-end
                            justify-center
                            gap-4
                        "
                    >

                        {{-- Web Mockup --}}
                        <div
                            class="
                                hidden
                                w-full
                                max-w-md
                                rounded-3xl
                                border
                                border-white/10
                                bg-white
                                p-4
                                shadow-2xl
                                sm:block
                            "
                        >

                            <div class="flex gap-1.5">

                                <span class="h-2 w-2 rounded-full bg-danger"></span>

                                <span class="h-2 w-2 rounded-full bg-yellow-accent"></span>

                                <span class="h-2 w-2 rounded-full bg-success"></span>

                            </div>


                            <div
                                class="
                                    mt-4
                                    grid
                                    grid-cols-3
                                    gap-2
                                "
                            >

                                <div
                                    class="
                                        rounded-xl
                                        bg-mint-soft
                                        p-3
                                    "
                                >
                                    <p class="text-xs text-text-secondary">
                                        Total
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            text-lg
                                            font-semibold
                                            text-primary
                                        "
                                    >
                                        12
                                    </p>
                                </div>


                                <div
                                    class="
                                        rounded-xl
                                        bg-yellow-soft
                                        p-3
                                    "
                                >
                                    <p class="text-xs text-text-secondary">
                                        Today
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            text-lg
                                            font-semibold
                                            text-text-primary
                                        "
                                    >
                                        3
                                    </p>
                                </div>


                                <div
                                    class="
                                        rounded-xl
                                        bg-background
                                        p-3
                                    "
                                >
                                    <p class="text-xs text-text-secondary">
                                        Done
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            text-lg
                                            font-semibold
                                            text-success
                                        "
                                    >
                                        7
                                    </p>
                                </div>

                            </div>


                            <div class="mt-3 space-y-2">

                                @foreach (['Finish UI', 'Review API', 'Test mobile flow'] as $item)

                                    <div
                                        class="
                                            rounded-xl
                                            border
                                            border-border
                                            px-3
                                            py-2.5
                                            text-xs
                                            text-text-primary
                                        "
                                    >
                                        {{ $item }}
                                    </div>

                                @endforeach

                            </div>

                        </div>


                        {{-- Mobile Mockup --}}
                        <div
                            class="
                                w-40
                                shrink-0
                                rounded-[2rem]
                                border-[5px]
                                border-white/10
                                bg-background
                                p-3
                                shadow-2xl
                                sm:w-44
                            "
                        >

                            <div
                                class="
                                    mx-auto
                                    h-1.5
                                    w-12
                                    rounded-full
                                    bg-text-primary/10
                                "
                            ></div>


                            <div class="mt-5">

                                <p
                                    class="
                                        text-xs
                                        font-semibold
                                        text-primary
                                    "
                                >
                                    Good morning
                                </p>

                                <p
                                    class="
                                        mt-1
                                        text-lg
                                        font-semibold
                                        text-text-primary
                                    "
                                >
                                    My Tasks
                                </p>


                                <div
                                    class="
                                        mt-4
                                        rounded-2xl
                                        bg-primary
                                        p-3
                                        text-white
                                    "
                                >
                                    <p class="text-[10px] text-white/60">
                                        Progress
                                    </p>

                                    <p class="mt-1 text-lg font-semibold">
                                        68%
                                    </p>
                                </div>


                                <div class="mt-3 space-y-2">

                                    <div
                                        class="
                                            rounded-xl
                                            bg-white
                                            p-3
                                        "
                                    >
                                        <p
                                            class="
                                                text-xs
                                                font-semibold
                                                text-text-primary
                                            "
                                        >
                                            Finish UI
                                        </p>

                                        <span
                                            class="
                                                mt-2
                                                inline-flex
                                                rounded-full
                                                bg-danger/10
                                                px-2
                                                py-1
                                                text-[9px]
                                                font-semibold
                                                text-danger
                                            "
                                        >
                                            High
                                        </span>
                                    </div>


                                    <div
                                        class="
                                            rounded-xl
                                            bg-white
                                            p-3
                                        "
                                    >
                                        <p
                                            class="
                                                text-xs
                                                font-semibold
                                                text-text-primary
                                            "
                                        >
                                            Study Laravel
                                        </p>

                                        <span
                                            class="
                                                mt-2
                                                inline-flex
                                                rounded-full
                                                bg-mint-soft
                                                px-2
                                                py-1
                                                text-[9px]
                                                font-semibold
                                                text-success
                                            "
                                        >
                                            Low
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>



            {{-- ========================================= --}}
            {{-- CTA --}}
            {{-- ========================================= --}}

            <section
                class="
                    px-5
                    py-20
                    sm:px-6
                    lg:px-8
                    lg:py-28
                "
            >

                <div
                    class="
                        mx-auto
                        max-w-5xl
                        overflow-hidden
                        rounded-[2rem]
                        bg-mint-soft
                        px-6
                        py-14
                        text-center
                        sm:px-10
                        lg:px-16
                    "
                >

                    <p
                        class="
                            text-sm
                            font-semibold
                            text-primary
                        "
                    >
                        Start simple
                    </p>


                    <h2
                        class="
                            mx-auto
                            mt-3
                            max-w-2xl
                            text-3xl
                            font-semibold
                            tracking-tight
                            text-text-primary
                            sm:text-4xl
                        "
                    >
                        Ready to organize your day?
                    </h2>


                    <p
                        class="
                            mx-auto
                            mt-4
                            max-w-xl
                            text-base
                            leading-7
                            text-text-secondary
                        "
                    >
                        Create your workspace, add what matters,
                        and focus on making progress one task at a time.
                    </p>


                    <div
                        class="
                            mt-8
                            flex
                            flex-col
                            justify-center
                            gap-3
                            sm:flex-row
                        "
                    >

                        @auth

                            <a
                                href="{{ route('dashboard') }}"
                                class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-primary
                                    px-6
                                    py-3.5
                                    text-sm
                                    font-semibold
                                    text-white
                                    transition
                                    hover:bg-primary-hover
                                "
                            >
                                Open Dashboard
                            </a>

                        @else

                            <a
                                href="{{ route('register') }}"
                                class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-primary
                                    px-6
                                    py-3.5
                                    text-sm
                                    font-semibold
                                    text-white
                                    transition
                                    hover:bg-primary-hover
                                "
                            >
                                Create Free Account
                            </a>


                            <a
                                href="{{ route('login') }}"
                                class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    border
                                    border-primary/15
                                    bg-white
                                    px-6
                                    py-3.5
                                    text-sm
                                    font-semibold
                                    text-primary
                                "
                            >
                                Sign In
                            </a>

                        @endauth

                    </div>

                </div>

            </section>

        </main>



        {{-- ========================================= --}}
        {{-- FOOTER --}}
        {{-- ========================================= --}}

        <footer
            class="
                border-t
                border-border
                bg-white
            "
        >

            <div
                class="
                    mx-auto
                    flex
                    max-w-7xl
                    flex-col
                    gap-5
                    px-5
                    py-8
                    sm:px-6
                    md:flex-row
                    md:items-center
                    md:justify-between
                    lg:px-8
                "
            >

                <div class="flex items-center gap-3">

                    <div
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-xl
                            bg-primary
                            text-sm
                            font-bold
                            text-white
                        "
                    >
                        <img src="{{ asset('brand/mark-light.svg') }}" alt="" class="h-7 w-7">
                    </div>

                    <div>

                        <p
                            class="
                                text-sm
                                font-semibold
                                text-text-primary
                            "
                        >
                            PLANSI
                        </p>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-text-secondary
                            "
                        >
                            Plan what matters.
                        </p>

                    </div>

                </div>


                <p
                    class="
                        text-xs
                        text-text-secondary
                    "
                >
                    © {{ date('Y') }} PLANSI.
                </p>

            </div>

        </footer>

    </div>

</body>

</html>
