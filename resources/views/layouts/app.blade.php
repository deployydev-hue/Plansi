<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PLANSI')</title>

    <link rel="icon" href="{{ asset('brand/favicon.svg') }}" type="image/svg+xml">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body x-data="appShell" class="bg-background text-text-primary">
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <div class="min-h-screen lg:flex">
        {{-- Persistent desktop sidebar --}}
        <aside
            class="sticky top-0 hidden h-screen w-64 shrink-0 border-r border-border bg-[#f2f3ee] lg:flex lg:flex-col"
            aria-label="Application sidebar"
        >
            <div class="flex h-full min-h-0 flex-col px-4 py-5">
                <a
                    href="{{ route('dashboard') }}"
                    class="mb-7 flex items-center gap-3 px-2 text-primary"
                    aria-label="PLANSI Today"
                >
                    <img src="{{ asset('brand/mark.svg') }}" alt="" class="h-10 w-10">
                    <span>
                        <span class="block text-base font-bold tracking-[0.18em]">PLANSI</span>
                        <span class="block text-[0.6875rem] font-medium text-muted">Plan what matters.</span>
                    </span>
                </a>

                <x-app-navigation />
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            {{-- Compact responsive-web header --}}
            <header class="sticky top-0 z-40 border-b border-border bg-background/95 backdrop-blur lg:hidden">
                <div class="flex min-h-[4.25rem] items-center justify-between gap-4 px-4 sm:px-6">
                    <a
                        href="{{ route('dashboard') }}"
                        class="flex min-w-0 items-center gap-2.5 text-primary"
                        aria-label="PLANSI Today"
                    >
                        <img src="{{ asset('brand/mark.svg') }}" alt="" class="h-9 w-9 shrink-0">
                        <span class="truncate text-base font-bold tracking-[0.16em]">PLANSI</span>
                    </a>

                    <div class="flex items-center gap-2">
                        @unless (request()->routeIs('tasks.index', 'tasks.create'))
                            <a
                                href="{{ route('tasks.create') }}"
                                class="btn btn-primary min-h-10 px-3 sm:px-4"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                                <span class="hidden sm:inline">Add task</span>
                                <span class="sr-only sm:hidden">Add task</span>
                            </a>
                        @endunless

                        <button
                            type="button"
                            class="btn btn-secondary btn-icon min-h-10"
                            @click="openDrawer"
                            :aria-expanded="drawerOpen.toString()"
                            aria-controls="mobile-navigation-drawer"
                            :aria-label="drawerOpen ? 'Close navigation menu' : 'Open navigation menu'"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M3.5 5.5h13M3.5 10h13M3.5 14.5h13" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            {{-- Existing page contents render unchanged inside this workspace. --}}
            <main id="main-content" tabindex="-1">
                <div class="mx-auto w-full max-w-[1440px] px-4 py-6 sm:px-6 sm:py-8 xl:px-10 xl:py-10">
                    <x-flash-stack />
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Mobile and tablet navigation drawer --}}
    <div
        x-cloak
        x-show="drawerOpen"
        class="fixed inset-0 z-[70] lg:hidden"
        @keydown.escape.window="closeDrawer"
    >
        <button
            type="button"
            class="absolute inset-0 bg-text-primary/45"
            aria-label="Close navigation menu"
            tabindex="-1"
            @click="closeDrawer"
            x-transition.opacity.duration.240ms
        ></button>

        <aside
            id="mobile-navigation-drawer"
            x-ref="drawer"
            x-show="drawerOpen"
            x-transition:enter="transition duration-[240ms] ease-out"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition duration-180 ease-in"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            @keydown.tab="trapDrawerFocus($event)"
            role="dialog"
            aria-modal="true"
            aria-labelledby="mobile-navigation-title"
            class="relative flex h-full w-[min(21rem,88vw)] flex-col border-r border-border bg-[#f2f3ee] shadow-lg"
        >
            <div class="flex min-h-[4.25rem] items-center justify-between border-b border-border px-4">
                <a
                    href="{{ route('dashboard') }}"
                    class="flex min-w-0 items-center gap-2.5 text-primary"
                    @click="closeDrawer(false)"
                >
                    <img src="{{ asset('brand/mark.svg') }}" alt="" class="h-9 w-9">
                    <span id="mobile-navigation-title" class="text-base font-bold tracking-[0.16em]">PLANSI</span>
                </a>

                <button
                    x-ref="drawerClose"
                    type="button"
                    class="btn btn-secondary btn-icon"
                    aria-label="Close navigation menu"
                    @click="closeDrawer"
                >
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="m5 5 10 10M15 5 5 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <div class="flex min-h-0 flex-1 flex-col overflow-y-auto px-4 py-5">
                <x-app-navigation mobile />
            </div>
        </aside>
    </div>
</body>
</html>
