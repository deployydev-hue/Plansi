@extends('layouts.guest')

@section('title', 'Verify Email | PLANSI')

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
            One last step
        </p>


        <h1
            class="
                text-3xl
                font-semibold
                tracking-tight
                text-text-primary
            "
        >
            Verify your email
        </h1>


        <p
            class="
                mt-3
                text-sm
                leading-6
                text-text-secondary
            "
        >
            We sent a verification link to
            <span class="font-semibold text-text-primary">
                {{ auth()->user()->email }}
            </span>.
            Please check your inbox before continuing to PLANSI.
        </p>

    </div>


    {{-- Success Message --}}
    @if (session('message'))

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

            <p
                class="
                    text-sm
                    font-semibold
                    text-primary
                "
            >
                {{ session('message') }}
            </p>

        </div>

    @endif


    {{-- Information Card --}}
    <div
        class="
            mb-6
            rounded-2xl
            border
            border-border
            bg-surface
            px-5
            py-5
        "
    >

        <p
            class="
                text-sm
                leading-6
                text-text-secondary
            "
        >
            Didn't receive the email?
            Check your spam folder first, or request a new verification link below.
        </p>

    </div>


    {{-- Resend Verification --}}
    <form
        method="POST"
        action="{{ route('verification.send') }}"
    >

        @csrf

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
            Resend Verification Email
        </button>

    </form>


    {{-- Logout --}}
    <form
        method="POST"
        action="{{ route('logout') }}"
        class="mt-4"
    >

        @csrf

        <button
            type="submit"
            class="
                inline-flex
                w-full
                items-center
                justify-center
                rounded-2xl
                border
                border-border
                bg-surface
                px-5
                py-3.5
                text-sm
                font-semibold
                text-text-secondary
                transition
                hover:border-primary/40
                hover:text-primary
                focus:outline-none
                focus:ring-4
                focus:ring-mint-soft
            "
        >
            Sign Out
        </button>

    </form>

@endsection