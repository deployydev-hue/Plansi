@php
    $flashes = collect([
        ['type' => 'success', 'message' => session('success')],
        ['type' => 'success', 'message' => session('status')],
        ['type' => 'success', 'message' => session('message')],
        ['type' => 'warning', 'message' => session('warning')],
        ['type' => 'error', 'message' => session('error')],
        ['type' => 'info', 'message' => session('info')],
    ])->filter(fn ($flash) => filled($flash['message']));
@endphp

@if ($flashes->isNotEmpty())
    <div class="mb-6 space-y-3" aria-label="Notifications">
        @foreach ($flashes as $flash)
            <div
                @class([
                    'flex items-start gap-3 rounded-xl border px-4 py-3 text-sm',
                    'border-success/25 bg-success-soft text-success' => $flash['type'] === 'success',
                    'border-warning/25 bg-warning-soft text-warning' => $flash['type'] === 'warning',
                    'border-danger/25 bg-danger-soft text-danger' => $flash['type'] === 'error',
                    'border-info/25 bg-info-soft text-info' => $flash['type'] === 'info',
                ])
                role="{{ $flash['type'] === 'error' ? 'alert' : 'status' }}"
            >
                <span class="mt-0.5 font-bold" aria-hidden="true">
                    {{ $flash['type'] === 'error' ? '!' : '✓' }}
                </span>
                <p class="min-w-0 flex-1 font-medium">{{ $flash['message'] }}</p>
            </div>
        @endforeach
    </div>
@endif
