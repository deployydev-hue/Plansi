@extends('layouts.app')

@section('title', 'Account Settings | PLANSI')

@section('content')

    {{-- Page Header --}}
    <section class="mb-8">

        <p
            class="
                mb-1
                text-sm
                font-medium
                text-primary
            "
        >
            Account
        </p>

        <h1
            class="
                text-3xl
                font-semibold
                tracking-tight
                text-text-primary
                sm:text-4xl
            "
        >
            Account Settings
        </h1>

        <p
            class="
                mt-2
                max-w-2xl
                text-sm
                leading-6
                text-text-secondary
            "
        >
            Manage your profile information and account security.
        </p>

    </section>


    <section
        class="
            grid
            grid-cols-1
            gap-6
            lg:grid-cols-[280px_1fr]
        "
    >

        {{-- Settings Navigation --}}
        <aside>

            <div
                class="
                    rounded-3xl
                    border
                    border-border
                    bg-surface
                    p-3
                    shadow-sm
                "
            >

                {{-- Profile --}}
                <a
                    href="{{ route('profile.edit') }}"
                    class="
                        block
                        rounded-2xl
                        bg-mint-soft
                        px-4
                        py-3
                    "
                >

                    <p
                        class="
                            text-sm
                            font-semibold
                            text-primary
                        "
                    >
                        Profile
                    </p>

                    <p
                        class="
                            mt-1
                            text-xs
                            text-text-secondary
                        "
                    >
                        Update your personal information.
                    </p>

                </a>


                {{-- Password --}}
                <a
                    href="{{ route('password.edit') }}"
                    class="
                        mt-2
                        block
                        rounded-2xl
                        px-4
                        py-3
                        transition
                        hover:bg-mint-soft
                    "
                >

                    <p
                        class="
                            text-sm
                            font-semibold
                            text-text-primary
                        "
                    >
                        Password & Security
                    </p>

                    <p
                        class="
                            mt-1
                            text-xs
                            text-text-secondary
                        "
                    >
                        Change your account password.
                    </p>

                </a>

            </div>


            {{-- Account Summary --}}
            <div
                class="
                    mt-4
                    rounded-3xl
                    border
                    border-border
                    bg-surface
                    p-5
                    shadow-sm
                "
            >

                {{-- Initial --}}
                <div
                    class="
                        flex
                        h-12
                        w-12
                        items-center
                        justify-center
                        rounded-2xl
                        bg-mint-soft
                        text-lg
                        font-semibold
                        uppercase
                        text-primary
                    "
                >
                    {{ mb_substr($user->name, 0, 1) }}
                </div>


                <p
                    class="
                        mt-4
                        truncate
                        text-sm
                        font-semibold
                        text-text-primary
                    "
                >
                    {{ $user->name }}
                </p>


                <p
                    class="
                        mt-1
                        break-all
                        text-xs
                        text-text-secondary
                    "
                >
                    {{ $user->email }}
                </p>


                {{-- Verified Status --}}
                @if ($user->hasVerifiedEmail())

                    <div
                        class="
                            mt-4
                            inline-flex
                            items-center
                            gap-2
                            rounded-full
                            bg-mint-soft
                            px-3
                            py-1.5
                            text-xs
                            font-semibold
                            text-success
                        "
                    >
                        ✓ Verified email
                    </div>

                @endif

            </div>

        </aside>


        {{-- Profile Form --}}
        <div
            class="
                rounded-3xl
                border
                border-border
                bg-surface
                p-6
                shadow-sm
                sm:p-8
            "
        >

            <div class="mb-7">

                <h2
                    class="
                        text-xl
                        font-semibold
                        text-text-primary
                    "
                >
                    Profile Information
                </h2>

                <p
                    class="
                        mt-2
                        text-sm
                        leading-6
                        text-text-secondary
                    "
                >
                    Update the name and email address associated with your account.
                </p>

            </div>


            {{-- Success --}}
            @if (session('status'))

                <div
                    class="
                        mb-6
                        rounded-2xl
                        border
                        border-success/20
                        bg-mint-soft
                        px-5
                        py-4
                    "
                >

                    <p
                        class="
                            text-sm
                            font-semibold
                            text-success
                        "
                    >
                        {{ session('status') }}
                    </p>

                </div>

            @endif


            {{-- General Errors --}}
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
                            font-semibold
                            text-danger
                        "
                    >
                        Please check the information below.
                    </p>

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('profile.update') }}"
                class="
                    max-w-xl
                    space-y-5
                "
            >

                @csrf
                @method('PUT')


                {{-- Name --}}
                <div>

                    <label
                        for="name"
                        class="
                            mb-2
                            block
                            text-sm
                            font-semibold
                            text-text-primary
                        "
                    >
                        Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        autocomplete="name"
                        required
                        class="
                            w-full
                            rounded-2xl
                            border
                            border-border
                            bg-background
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

                    @error('name')

                        <p class="mt-2 text-sm text-danger">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


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
                        value="{{ old('email', $user->email) }}"
                        autocomplete="email"
                        required
                        class="
                            w-full
                            rounded-2xl
                            border
                            border-border
                            bg-background
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


                    <p
                        class="
                            mt-2
                            text-xs
                            leading-5
                            text-text-secondary
                        "
                    >
                        Changing your email address will require email verification again.
                    </p>

                </div>


                {{-- Current Password --}}
                <div
                    x-data="{
                        showPassword: false
                    }"
                >

                    <label
                        for="current_password"
                        class="
                            mb-2
                            block
                            text-sm
                            font-semibold
                            text-text-primary
                        "
                    >
                        Current Password
                    </label>


                    <div class="relative">

                        <input
                            :type="showPassword ? 'text' : 'password'"
                            id="current_password"
                            name="current_password"
                            placeholder="Required only when changing email"
                            autocomplete="current-password"
                            class="
                                w-full
                                rounded-2xl
                                border
                                border-border
                                bg-background
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
                            @click="showPassword = ! showPassword"
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


                    @error('current_password')

                        <p class="mt-2 text-sm text-danger">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Actions --}}
                <div
                    class="
                        flex
                        flex-col
                        gap-3
                        border-t
                        border-border
                        pt-6
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                    "
                >

                    <p
                        class="
                            text-xs
                            leading-5
                            text-text-secondary
                        "
                    >
                        Your tasks and categories will not be affected.
                    </p>


                    <button
                        type="submit"
                        class="
                            inline-flex
                            shrink-0
                            items-center
                            justify-center
                            rounded-2xl
                            bg-primary
                            px-5
                            py-3
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
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </section>

@endsection