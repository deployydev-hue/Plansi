@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'hint' => null,
    'requirement' => null,
])

@php
    $fieldId = $attributes->get('id', $name);
    $safeId = preg_replace('/[^a-zA-Z0-9_-]/', '-', $fieldId);
    $error = $errors->first($name);
    $hintId = $hint ? $safeId.'-hint' : null;
    $errorId = $error ? $safeId.'-error' : null;
    $describedBy = collect([$hintId, $errorId])->filter()->implode(' ');
@endphp

<div>
    @if ($label)
        <div class="mb-2 flex items-baseline justify-between gap-3">
            <label for="{{ $fieldId }}" class="form-label mb-0">
                {{ $label }}
                @if ($required)<span class="sr-only">(required)</span>@endif
            </label>
            @if ($requirement)
                <span class="shrink-0 text-xs font-medium {{ $required ? 'text-primary' : 'text-muted' }}">{{ $requirement }}</span>
            @endif
        </div>
    @endif

    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        @if ($required) required @endif
        @if ($disabled) disabled @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if ($error) aria-invalid="true" @endif
        {{ $attributes->except('id')->class([
            'form-control min-h-32 resize-y',
            'is-error' => $error,
        ]) }}
    >{{ old($name, $value) }}</textarea>

    @if ($hint)
        <p id="{{ $hintId }}" class="form-hint">{{ $hint }}</p>
    @endif

    @if ($error)
        <p id="{{ $errorId }}" class="form-error">{{ $error }}</p>
    @endif
</div>
