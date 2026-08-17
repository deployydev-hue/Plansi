@extends('layouts.guest')

@section('title', 'Forgot Password | PLANSI')

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
            Password recovery
        </p>

        <h1
            class="
                text-3xl
                font-semibold
                tracking-tight
                text-text-primary
            "
        >
            Forgot your password?
        </h1>

        <p
            class="
                mt-3
                text-sm
                leading-6
                text-text-secondary
            "
        >
            Enter your email address and we'll send you
            a secure link to reset your password.
        </p>

    </div>


    {{-- Success --}}
    @if (session('status'))

        <div
            class="
                mb-6
                rounded-2xl
                border
                border-primary/20
                bg-mint-soft
                px-5
                py-4
            "
        >
            <p class="text-sm font-semibold text-primary">
                {{ session('status') }}
            </p>
        </div>

    @endif


    <form
        method="POST"
        action="{{ route('password.email') }}"
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
            Send Reset Link
        </button>

    </form>


    <p
        class="
            mt-7
            text-center
            text-sm
            text-text-secondary
        "
    >
        Remember your password?

        <a
            href="{{ route('login') }}"
            class="
                font-semibold
                text-primary
                transition
                hover:text-primary-hover
            "
        >
            Sign in
        </a>
    </p>

@endsection