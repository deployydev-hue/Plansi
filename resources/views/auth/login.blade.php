@extends('layouts.guest')

@section('title', 'Login | PLANSI')

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
            Welcome back
        </p>


        <h1
            class="
                text-3xl
                font-semibold
                tracking-tight
                text-text-primary
            "
        >
            Sign in to your account
        </h1>


        <p
            class="
                mt-3
                text-sm
                leading-6
                text-text-secondary
            "
        >
            Continue where you left off
            and stay focused on your tasks.
        </p>

    </div>


    {{-- Errors --}}
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

            <p
                class="
                    text-sm
                    font-medium
                    text-danger
                "
            >
                The provided credentials could not be verified.
            </p>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('login') }}"
        class="space-y-5"
    >

        @csrf


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
                value="{{ old('email') }}"
                placeholder="name@example.com"
                autocomplete="email"
                required
                autofocus
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
                    placeholder:text-text-secondary/60
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
                Password
            </label>


            <div class="relative">

                <input
                    :type="showPassword ? 'text' : 'password'"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
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


        {{-- Submit --}}
        <button
            type="submit"
            class="
                mt-2
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
            Sign In
        </button>

    </form>


    {{-- Register Link --}}
    <p
        class="
            mt-7
            text-center
            text-sm
            text-text-secondary
        "
    >
        Don't have an account?

        <a
            href="{{ route('register') }}"
            class="
                font-semibold
                text-primary
                transition
                hover:text-primary-hover
            "
        >
            Create one
        </a>
    </p>

@endsection
