@props([
    'variant' => 'primary',
    'type' => 'button',
    'loading' => false,
])

@php
    $variantClass = match ($variant) {
        'secondary' => 'btn-secondary',
        'text' => 'btn-text',
        'danger' => 'btn-danger',
        'icon' => 'btn-secondary btn-icon',
        default => 'btn-primary',
    };
@endphp

<button
    type="{{ $type }}"
    @if ($loading) aria-busy="true" data-loading="true" @endif
    {{ $attributes->class(['btn', $variantClass]) }}
>
    {{ $slot }}
</button>
