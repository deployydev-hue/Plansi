@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => '',
    'placeholder' => 'Select an option',
    'required' => false,
])

@php
    $selectedValue = (string) $selected;
@endphp

<div
    x-data="{
        open: false,
        value: @js($selectedValue),
        options: @js($options),

        get selectedLabel() {
            if (
                this.value === '' ||
                this.value === null ||
                typeof this.options[this.value] === 'undefined'
            ) {
                return @js($placeholder);
            }

            return this.options[this.value];
        },

        selectOption(value) {
            this.value = String(value);
            this.open = false;
        }
    }"
    class="relative"
    @keydown.escape.window="open = false"
>

    @if ($label)
        <label
            for="{{ $name }}"
            class="
                mb-2
                block
                text-sm
                font-medium
                text-text-primary
            "
        >
            {{ $label }}

            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif


    {{-- Actual value sent to Laravel --}}
    <input
        type="hidden"
        id="{{ $name }}"
        name="{{ $name }}"
        x-model="value"
        @if ($required) required @endif
    >


    {{-- Select Button --}}
    <button
        type="button"
        @click="open = !open"
        :aria-expanded="open"
        class="
            flex
            w-full
            items-center
            justify-between
            gap-3
            rounded-2xl
            border
            border-border
            bg-background
            px-4
            py-3
            text-left
            text-sm
            text-text-primary
            outline-none
            transition
            hover:border-primary/50
            focus:border-primary
            focus:ring-4
            focus:ring-mint-soft
        "
    >

        <span
            x-text="selectedLabel"
            :class="value === '' ? 'text-text-secondary' : 'text-text-primary'"
            class="truncate"
        ></span>


        {{-- Custom Arrow --}}
        <svg
            class="
                h-4
                w-4
                shrink-0
                text-text-secondary
                transition-transform
                duration-200
            "
            :class="open ? 'rotate-180' : ''"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M5 7.5L10 12.5L15 7.5"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </svg>

    </button>


    {{-- Dropdown Menu --}}
    <div
        x-cloak
        x-show="open"
        x-transition.origin.top
        @click.outside="open = false"
        class="
            absolute
            left-0
            right-0
            z-50
            mt-2
            max-h-64
            overflow-y-auto
            rounded-2xl
            border
            border-border
            bg-white
            p-2
            shadow-xl
        "
    >

        {{-- Placeholder / Empty --}}
        <button
            type="button"
            @click="selectOption('')"
            class="
                flex
                w-full
                items-center
                justify-between
                rounded-xl
                px-3
                py-2.5
                text-left
                text-sm
                transition
                hover:bg-mint-soft
            "
            :class="value === ''
                ? 'bg-mint-soft text-primary font-semibold'
                : 'text-text-secondary'"
        >

            <span>
                {{ $placeholder }}
            </span>

            <span
                x-show="value === ''"
                class="font-bold text-primary"
            >
                ✓
            </span>

        </button>


        {{-- Options --}}
        <template
            x-for="[optionValue, optionLabel] in Object.entries(options)"
            :key="optionValue"
        >

            <button
                type="button"
                @click="selectOption(optionValue)"
                class="
                    mt-1
                    flex
                    w-full
                    items-center
                    justify-between
                    rounded-xl
                    px-3
                    py-2.5
                    text-left
                    text-sm
                    transition
                    hover:bg-mint-soft
                    hover:text-primary
                "
                :class="value === String(optionValue)
                    ? 'bg-mint-soft text-primary font-semibold'
                    : 'text-text-primary'"
            >

                <span x-text="optionLabel"></span>

                <span
                    x-show="value === String(optionValue)"
                    class="font-bold text-primary"
                >
                    ✓
                </span>

            </button>

        </template>

    </div>


    @error($name)

        <p class="mt-2 text-sm text-danger">
            {{ $message }}
        </p>

    @enderror

</div>