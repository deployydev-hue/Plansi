@props([
    'href',
    'active' => false,
])

<a
    href="{{ $href }}"
    @if ($active) aria-current="page" @endif
    {{ $attributes->class([
        'group relative flex min-h-11 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
        'bg-mint-soft text-primary' => $active,
        'text-text-secondary hover:bg-surface hover:text-text-primary' => ! $active,
    ]) }}
>
    <span
        @class([
            'absolute inset-y-2 left-0 w-0.5 rounded-full bg-primary transition-opacity',
            'opacity-100' => $active,
            'opacity-0' => ! $active,
        ])
        aria-hidden="true"
    ></span>

    @isset($icon)
        <span class="flex h-5 w-5 shrink-0 items-center justify-center" aria-hidden="true">
            {{ $icon }}
        </span>
    @endisset

    <span class="min-w-0 truncate">{{ $slot }}</span>
</a>
