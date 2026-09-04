@props([
    'name',
    'label',
    'checked' => false,
    'value' => '1',
    'hint' => null,
])

@php
    $fieldId = $attributes->get('id', $name);
    $hintId = $hint ? $fieldId.'-hint' : null;
@endphp

<div class="flex items-start gap-3">
    <input
        id="{{ $fieldId }}"
        name="{{ $name }}"
        type="checkbox"
        value="{{ $value }}"
        @checked(old($name, $checked))
        @if ($hintId) aria-describedby="{{ $hintId }}" @endif
        {{ $attributes->except('id')->class('mt-0.5 h-5 w-5 rounded border-control-border text-primary accent-primary') }}
    >

    <div>
        <label for="{{ $fieldId }}" class="block text-sm font-medium text-text-primary">
            {{ $label }}
        </label>
        @if ($hint)
            <p id="{{ $hintId }}" class="form-hint">{{ $hint }}</p>
        @endif
    </div>
</div>
