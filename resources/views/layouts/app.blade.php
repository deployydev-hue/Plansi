<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PLANSI')</title>
    <link rel="icon" href="{{ asset('brand/favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-text-primary">
    <header
        x-data="{ mobileMenuOpen: false }"
        @keydown.escape.window="mobileMenuOpen = false"
        class="sticky top-0 z-50 border-b border-border bg-background/95 backdrop-blur"
    >
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:py-4">
            <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-2.5 font-semibold text-primary" aria-label="PLANSI dashboard">
                <img src="{{ asset('brand/mark.svg') }}" alt="" class="h-10 w-10 shrink-0">
                <span class="truncate text-base font-bold tracking-[0.16em] sm:text-lg">PLANSI</span>
            </a>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="Primary navigation">
                @foreach ([
                    'dashboard' => ['Dashboard', route('dashboard')],
                    'tasks.*' => ['Tasks', route('tasks.index')],
                    'categories.*' => ['Categories', route('categories.index')],
                ] as $pattern => [$label, $url])
                    <a
                        href="{{ $url }}"
                        @class([
                            'rounded-xl px-4 py-2 text-sm font-medium transition hover:bg-mint-soft hover:text-primary',
                            'bg-mint-soft text-primary' => request()->routeIs($pattern),
                            'text-text-secondary' => ! request()->routeIs($pattern),
                        ])
                        @if (request()->routeIs($pattern)) aria-current="page" @endif
                    >{{ $label }}</a>
                @endforeach
            </nav>

            <div class="hidden min-w-0 items-center gap-4 lg:flex">
                <div class="min-w-0 max-w-52 text-right">
                    <p class="truncate text-sm font-medium text-text-primary">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-text-secondary">My workspace</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl border border-border bg-white px-4 py-2 text-sm font-medium text-text-secondary transition hover:border-danger hover:text-danger">
                        Logout
                    </button>
                </form>
            </div>

            <button
                type="button"
                class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-border bg-white text-primary transition hover:bg-mint-soft focus:outline-none focus:ring-4 focus:ring-mint lg:hidden"
                @click="mobileMenuOpen = ! mobileMenuOpen"
                :aria-expanded="mobileMenuOpen.toString()"
                aria-controls="mobile-navigation"
                aria-label="Toggle navigation menu"
            >
                <svg x-show="! mobileMenuOpen" aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-cloak x-show="mobileMenuOpen" aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>

        <div
            id="mobile-navigation"
            x-cloak
            x-show="mobileMenuOpen"
            x-transition.opacity.duration.150ms
            @click.outside="mobileMenuOpen = false"
            class="border-t border-border bg-surface px-4 py-4 shadow-lg sm:px-6 lg:hidden"
        >
            <nav class="mx-auto max-w-7xl space-y-1" aria-label="Mobile navigation">
                @foreach ([
                    'dashboard' => ['Dashboard', route('dashboard')],
                    'tasks.*' => ['Tasks', route('tasks.index')],
                    'categories.*' => ['Categories', route('categories.index')],
                ] as $pattern => [$label, $url])
                    <a
                        href="{{ $url }}"
                        @click="mobileMenuOpen = false"
                        @class([
                            'block rounded-xl px-4 py-3 text-sm font-medium transition hover:bg-mint-soft hover:text-primary',
                            'bg-mint-soft text-primary' => request()->routeIs($pattern),
                            'text-text-secondary' => ! request()->routeIs($pattern),
                        ])
                        @if (request()->routeIs($pattern)) aria-current="page" @endif
                    >{{ $label }}</a>
                @endforeach
            </nav>

            <div class="mx-auto mt-4 flex max-w-7xl items-center justify-between gap-4 border-t border-border pt-4">
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-text-primary">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-text-secondary">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="rounded-xl border border-danger/20 bg-danger/5 px-4 py-2.5 text-sm font-medium text-danger transition hover:bg-danger/10">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto min-h-[calc(100vh-69px)] max-w-7xl px-4 py-6 sm:px-6 sm:py-8">
        @yield('content')
    </main>
</body>
</html>
