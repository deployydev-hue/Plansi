@extends('layouts.app')

@section('title', 'Account Settings | PLANSI')

@section('content')

    {{-- Page Header --}}
    <section
        class="
            mb-8
            flex
            flex-col
            gap-3
        "
    >

        <p
            class="
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
                max-w-2xl
                text-sm
                leading-6
                text-text-secondary
            "
        >
            Manage your account security and keep your PLANSI access protected.
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

                <div
                    class="
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
                        Password & Security
                    </p>

                    <p
                        class="
                            mt-1
                            text-xs
                            text-text-secondary
                        "
                    >
                        Update your account password.
                    </p>

                </div>

            </div>


            {{-- Account Info --}}
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

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-wide
                        text-text-secondary
                    "
                >
                    Signed in as
                </p>

                <p
                    class="
                        mt-3
                        truncate
                        text-sm
                        font-semibold
                        text-text-primary
                    "
                >
                    {{ auth()->user()->name }}
                </p>

                <p
                    class="
                        mt-1
                        break-all
                        text-xs
                        text-text-secondary
                    "
                >
                    {{ auth()->user()->email }}
                </p>

            </div>

        </aside>


        {{-- Password Form --}}
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
                    Change Password
                </h2>

                <p
                    class="
                        mt-2
                        text-sm
                        leading-6
                        text-text-secondary
                    "
                >
                    Confirm your current password before choosing a new one.
                </p>

            </div>


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
                action="{{ route('password.change') }}"
                class="
                    max-w-xl
                    space-y-5
                "
            >

                @csrf
                @method('PUT')


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
                            placeholder="Enter your current password"
                            autocomplete="current-password"
                            required
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


                    @error('current_password')

                        <p class="mt-2 text-sm text-danger">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- New Password --}}
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
                            placeholder="Create a new password"
                            autocomplete="new-password"
                            required
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


                {{-- Confirm New Password --}}
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


                {{-- Submit --}}
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
                        Your new password must be different from your current password.
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
                        Update Password
                    </button>

                </div>

            </form>

        </div>

    </section>

@endsection
