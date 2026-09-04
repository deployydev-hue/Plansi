@props([
    'mobile' => false,
])

@php
    $isUpcoming = request()->routeIs('tasks.index')
        && request('due_date') === 'upcoming'
        && request('status', 'pending') === 'pending';
    $isCompleted = request()->routeIs('tasks.index')
        && request('status') === 'completed';
    $isMyTasks = request()->routeIs('tasks.create', 'tasks.edit')
        || (request()->routeIs('tasks.index') && ! $isUpcoming && ! $isCompleted);
@endphp

<div class="flex min-h-0 flex-1 flex-col">
    @unless (request()->routeIs('tasks.index', 'tasks.create'))
        <a href="{{ route('tasks.create') }}" class="btn btn-primary w-full">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            Add task
        </a>
    @endunless

    <nav class="{{ request()->routeIs('tasks.index', 'tasks.create') ? '' : 'mt-7' }}" aria-label="{{ $mobile ? 'Mobile primary navigation' : 'Primary navigation' }}">
        <p class="px-3 text-[0.6875rem] font-semibold uppercase tracking-[0.14em] text-muted">
            Today
        </p>

        <div class="mt-2 space-y-1">
            <x-nav-item :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none">
                        <path d="M3.5 10.2 10 4l6.5 6.2v5.3a1 1 0 0 1-1 1H4.5a1 1 0 0 1-1-1v-5.3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                        <path d="M7.5 16.5v-5h5v5" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </x-slot:icon>
                Today
            </x-nav-item>

            <x-nav-item :href="route('tasks.index')" :active="$isMyTasks">
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none">
                        <rect x="3.5" y="3.5" width="13" height="13" rx="2.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="m6.5 10 2 2 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-slot:icon>
                My Tasks
            </x-nav-item>

            <x-nav-item
                :href="route('tasks.index', ['due_date' => 'upcoming', 'status' => 'pending'])"
                :active="$isUpcoming"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none">
                        <rect x="3.5" y="5" width="13" height="11.5" rx="2.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M6.5 3.5v3M13.5 3.5v3M3.5 8.5h13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </x-slot:icon>
                Upcoming
            </x-nav-item>

            <x-nav-item
                :href="route('tasks.index', ['status' => 'completed'])"
                :active="$isCompleted"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none">
                        <circle cx="10" cy="10" r="6.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="m6.8 10.2 2.1 2.1 4.4-4.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-slot:icon>
                Completed
            </x-nav-item>
        </div>
    </nav>

    <nav class="mt-8" aria-label="{{ $mobile ? 'Mobile organization navigation' : 'Organization navigation' }}">
        <p class="px-3 text-[0.6875rem] font-semibold uppercase tracking-[0.14em] text-muted">
            Organize
        </p>

        <div class="mt-2">
            <x-nav-item :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none">
                        <path d="M4.5 6.5h4l1.3 1.7h5.7a1 1 0 0 1 1 1v5.3a1.5 1.5 0 0 1-1.5 1.5H5a1.5 1.5 0 0 1-1.5-1.5V7.5a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    </svg>
                </x-slot:icon>
                Categories
            </x-nav-item>
        </div>
    </nav>

    <div class="mt-auto border-t border-border pt-4">
        <div class="mb-3 flex items-center gap-3 px-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-mint-soft text-sm font-semibold text-primary" aria-hidden="true">
                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
            </span>
            <span class="min-w-0">
                <span class="block truncate text-sm font-semibold text-text-primary">{{ auth()->user()->name }}</span>
                <span class="block truncate text-xs text-muted">{{ auth()->user()->email }}</span>
            </span>
        </div>

        <div class="space-y-1">
            <x-nav-item :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none">
                        <circle cx="10" cy="7" r="3" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M4.5 16c.7-3 2.5-4.5 5.5-4.5s4.8 1.5 5.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </x-slot:icon>
                Account
            </x-nav-item>

            <x-nav-item :href="route('password.edit')" :active="request()->routeIs('password.edit')">
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none">
                        <circle cx="10" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M10 3.5v2M10 14.5v2M16.5 10h-2M5.5 10h-2M14.6 5.4l-1.4 1.4M6.8 13.2l-1.4 1.4M14.6 14.6l-1.4-1.4M6.8 6.8 5.4 5.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </x-slot:icon>
                Settings
            </x-nav-item>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="flex min-h-11 w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-text-secondary transition hover:bg-danger-soft hover:text-danger"
                >
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M8 4H5.5A1.5 1.5 0 0 0 4 5.5v9A1.5 1.5 0 0 0 5.5 16H8M12.5 6.5 16 10l-3.5 3.5M16 10H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Sign out
                </button>
            </form>
        </div>
    </div>
</div>
