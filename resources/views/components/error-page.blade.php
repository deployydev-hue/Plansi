@props(['code', 'title', 'message'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>{{ $code }} | PLANSI</title>
    <link rel="icon" href="{{ asset('brand/favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-text-primary">
    <main class="flex min-h-screen items-center justify-center px-5 py-12">
        <section class="w-full max-w-xl rounded-3xl border border-border bg-surface px-6 py-12 text-center shadow-sm sm:px-10">
            <a href="{{ url('/') }}" class="mx-auto flex w-fit items-center gap-3 text-primary">
                <img src="{{ asset('brand/mark.svg') }}" alt="" class="h-9 w-9">
                <span class="font-semibold tracking-[0.14em]">PLANSI</span>
            </a>
            <p class="mt-10 text-sm font-semibold text-primary">Error {{ $code }}</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ $title }}</h1>
            <p class="mx-auto mt-4 max-w-md text-sm leading-6 text-text-secondary">{{ $message }}</p>
            <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="mt-8 inline-flex items-center justify-center rounded-2xl bg-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-primary-hover">
                {{ auth()->check() ? 'Back to Dashboard' : 'Back to Home' }}
            </a>
        </section>
    </main>
</body>
</html>
