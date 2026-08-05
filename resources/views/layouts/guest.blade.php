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

    <title>
        @yield('title', 'PLANSI')
    </title>

    <link rel="icon" href="{{ asset('brand/favicon.svg') }}" type="image/svg+xml">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-background text-text-primary">

    <main
        class="
            min-h-screen
            lg:grid
            lg:grid-cols-2
        "
    >

        {{-- Brand / Visual Side --}}
        <section
            class="
                relative
                hidden
                overflow-hidden
                bg-primary
                p-12
                text-white
                lg:flex
                lg:flex-col
                lg:justify-between
            "
        >

            {{-- Decorative Shapes --}}
            <div
                class="
                    absolute
                    -right-24
                    -top-24
                    h-72
                    w-72
                    rounded-full
                    bg-mint/10
                "
            ></div>

            <div
                class="
                    absolute
                    -bottom-32
                    -left-20
                    h-96
                    w-96
                    rounded-full
                    bg-white/5
                "
            ></div>


            {{-- Brand --}}
            <div
                class="
                    relative
                    z-10
                    flex
                    items-center
                    gap-3
                "
            >

                <img src="{{ asset('brand/logo-light.svg') }}" alt="PLANSI — Plan what matters." class="h-14 w-auto max-w-[17rem]">

            </div>


            {{-- Main Message --}}
            <div
                class="
                    relative
                    z-10
                    max-w-lg
                "
            >

                <div
                    class="
                        mb-6
                        inline-flex
                        rounded-full
                        bg-white/10
                        px-4
                        py-2
                        text-sm
                        font-medium
                        text-mint
                    "
                >
                    Your tasks. Your focus.
                </div>


                <h1
                    class="
                        text-4xl
                        font-semibold
                        leading-tight
                        tracking-tight
                        xl:text-5xl
                    "
                >
                    A calmer way to organize what matters.
                </h1>


                <p
                    class="
                        mt-6
                        max-w-md
                        text-base
                        leading-7
                        text-white/70
                    "
                >
                    Keep tasks clear, prioritize your work,
                    and stay focused without unnecessary complexity.
                </p>


                {{-- Mini Preview --}}
                <div
                    class="
                        mt-10
                        max-w-md
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/10
                        p-5
                        backdrop-blur
                    "
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-semibold">
                                Today's progress
                            </p>

                            <p class="mt-1 text-xs text-white/60">
                                Stay focused on one task at a time.
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

            </div>


            {{-- Footer --}}
            <p
                class="
                    relative
                    z-10
                    text-sm
                    text-white/50
                "
            >
                Simple planning. Clear progress.
            </p>

        </section>


        {{-- Form Side --}}
        <section
            class="
                flex
                min-h-screen
                items-center
                justify-center
                px-5
                py-10
                sm:px-8
                lg:px-12
            "
        >

            <div class="w-full max-w-md">

                {{-- Mobile Brand --}}
                <a
                    href="/"
                    class="
                        mb-10
                        flex
                        items-center
                        gap-3
                        lg:hidden
                    "
                >

                    <img src="{{ asset('brand/logo.svg') }}" alt="PLANSI — Plan what matters." class="h-12 w-auto max-w-[15rem]">

                </a>


                @yield('content')

            </div>

        </section>

    </main>

</body>

</html>
