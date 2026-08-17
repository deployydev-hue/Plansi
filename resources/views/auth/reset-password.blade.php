@extends('layouts.guest')

@section('title', 'Reset Password | PLANSI')

@section('content')

    {{-- Header --}}
    <div class="mb-8">

        <p
            class="
                mb-2
                text-sm
                font-semibold
                text-primary
            "
        >
            Secure your account
        </p>

        <h1
            class="
                text-3xl
                font-semibold
                tracking-tight
                text-text-primary
            "
        >
            Reset your password
        </h1>

        <p
            class="
                mt-3
                text-sm
                leading-6
                text-text-secondary
            "
        >
            Choose a new password for your PLANSI account.
        </p>

    </div>


    @if ($errors->any())

        <div
            class="
                mb-6
                rounded-2xl
                border
                border-danger/20
                bg-danger/5
                px-5
                py-4
            "
        >
            <p class="text-sm font-semibold text-danger">
                Please check the information below.
            </p>
        </div>

    @endif


    <form
        method="POST"
        action="{{ route('password.update') }}"
        class="space-y-5"
    >

        @csrf

        <input
            type="hidden"
            name="token"
            value="{{ $token }}"
        >


        {{-- Email --}}
        <div>

            <label
                for="email"
                class="
                    mb-2
                    block
                    text-sm
                    font-semibold
                    text-text-primary
                "
            >
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $email) }}"
                autocomplete="email"
                required
                class="
                    w-full
                    rounded-2xl
                    border
                    border-border
                    bg-surface
                    px-4
                    py-3.5
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

            @error('email')
                <p class="mt-2 text-sm text-danger">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- Password --}}
        <div
            x-data="{
                showPassword: false
            }"
        >

            <label
                for="password"
                class="
                    mb-2
                    block
                    text-sm
                    font-semibold
                    text-text-primary
                "
            >
                New Password
            </label>

            <div class="relative">

                <input
                    :type="showPassword ? 'text' : 'password'"
                    id="password"
                    name="password"
                    placeholder="Enter a new password"
                    autocomplete="new-password"
                    required
                    class="
                        w-full
                        rounded-2xl
                        border
                        border-border
                        bg-surface
                        px-4
                        py-3.5
                        pr-16
                        text-sm
                        text-text-primary
                        outline-none
                        transition
                        placeholder:text-text-secondary/60
                        hover:border-primary/50
                        focus:border-primary
                        focus:ring-4
                        focus:ring-mint-soft
                    "
                >

                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="
                        absolute
                        right-4
                        top-1/2
                        -translate-y-1/2
                        text-xs
                        font-semibold
                        text-text-secondary
                        transition
                        hover:text-primary
                    "
                    x-text="showPassword ? 'Hide' : 'Show'"
                ></button>

            </div>

            @error('password')
                <p class="mt-2 text-sm text-danger">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- Confirm Password --}}
        <div
            x-data="{
                showPassword: false
            }"
        >

            <label
                for="password_confirmation"
                class="
                    mb-2
                    block
                    text-sm
                    font-semibold
                    text-text-primary
                "
            >
                Confirm New Password
            </label>

            <div class="relative">

                <input
                    :type="showPassword ? 'text' : 'password'"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Repeat your new password"
                    autocomplete="new-password"
                    required
                    class="
                        w-full
                        rounded-2xl
                        border
                        border-border
                        bg-surface
                        px-4
                        py-3.5
                        pr-16
                        text-sm
                        text-text-primary
                        outline-none
                        transition
                        placeholder:text-text-secondary/60
                        hover:border-primary/50
                        focus:border-primary
                        focus:ring-4
                        focus:ring-mint-soft
                    "
                >

                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="
                        absolute
                        right-4
                        top-1/2
                        -translate-y-1/2
                        text-xs
                        font-semibold
                        text-text-secondary
                        transition
                        hover:text-primary
                    "
                    x-text="showPassword ? 'Hide' : 'Show'"
                ></button>

            </div>

        </div>


        <button
            type="submit"
            class="
                inline-flex
                w-full
                items-center
                justify-center
                rounded-2xl
                bg-primary
                px-5
                py-3.5
                text-sm
                font-semibold
                text-white
                shadow-sm
                transition
                hover:bg-primary-hover
                focus:outline-none
                focus:ring-4
                focus:ring-mint
            "
        >
            Reset Password
        </button>

    </form>

@endsection