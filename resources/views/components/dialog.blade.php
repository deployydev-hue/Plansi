@props([
    'title',
    'description' => null,
    'action' => null,
    'method' => 'POST',
    'triggerLabel' => 'Open dialog',
    'confirmLabel' => 'Confirm',
    'cancelLabel' => 'Cancel',
    'destructive' => false,
    'triggerClass' => null,
    'triggerRole' => null,
])

@php
    $dialogId = 'dialog-'.Illuminate\Support\Str::uuid();
    $defaultTriggerClass = $destructive ? 'btn btn-danger' : 'btn btn-secondary';
@endphp

<div x-data="accessibleDialog">
    <button
        type="button"
        class="{{ $triggerClass ?? $defaultTriggerClass }}"
        @if ($triggerRole) role="{{ $triggerRole }}" @endif
        @click="openDialog"
        aria-haspopup="dialog"
        aria-controls="{{ $dialogId }}"
    >
        {{ $triggerLabel }}
    </button>

    <div
        x-cloak
        x-show="open"
        class="fixed inset-0 z-[80] flex items-end justify-center p-4 sm:items-center"
        @keydown.escape.window="closeDialog"
    >
        <button
            type="button"
            class="absolute inset-0 bg-text-primary/45"
            aria-label="Close dialog"
            tabindex="-1"
            @click="closeDialog"
            x-transition.opacity.duration.240ms
        ></button>

        <section
            x-ref="dialog"
            x-show="open"
            x-transition:enter="transition duration-[240ms] ease-out"
            x-transition:enter-start="translate-y-3 opacity-0 sm:translate-y-0 sm:scale-[0.98]"
            x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
            x-transition:leave="transition duration-180 ease-in"
            x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100"
            x-transition:leave-end="translate-y-3 opacity-0 sm:translate-y-0 sm:scale-[0.98]"
            id="{{ $dialogId }}"
            role="dialog"
            aria-modal="true"
            aria-labelledby="{{ $dialogId }}-title"
            @keydown.tab="trapFocus($event)"
            class="relative z-10 w-full max-w-md rounded-2xl border border-border bg-surface p-6 shadow-lg"
        >
            <h2 id="{{ $dialogId }}-title" class="type-h3">{{ $title }}</h2>

            @if ($description)
                <p class="mt-3 text-sm leading-6 text-text-secondary">{{ $description }}</p>
            @endif

            {{ $slot }}

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" class="btn btn-secondary" @click="closeDialog">
                    {{ $cancelLabel }}
                </button>

                @if ($action)
                    <form method="POST" action="{{ $action }}">
                        @csrf
                        @if (! in_array(strtoupper($method), ['GET', 'POST']))
                            @method($method)
                        @endif

                        <button
                            type="submit"
                            class="btn {{ $destructive ? 'btn-danger' : 'btn-primary' }} w-full"
                        >
                            {{ $confirmLabel }}
                        </button>
                    </form>
                @endif
            </div>
        </section>
    </div>
</div>
