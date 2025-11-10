@php
    $flashTypes = ['success', 'error', 'warning', 'info', 'status'];
    $hasAlerts = $errors->any();

    foreach ($flashTypes as $type) {
        if (session()->has($type)) {
            $hasAlerts = true;
            break;
        }
    }
@endphp

@if ($hasAlerts)
    <div class="space-y-3 mb-6">
        @foreach ($flashTypes as $type)
            @if (session()->has($type))
                @php
                    $message = session($type);
                    $variant = match ($type) {
                        'error' => 'danger',
                        'status' => 'primary',
                        default => $type,
                    };
                @endphp
                <div class="alert alert-{{ $variant }} alert-dismissible fade show" role="alert">
                    <span>{{ $message }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.close') }}"></button>
                </div>
            @endif
        @endforeach

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p class="mb-2 font-semibold">{{ __('messages.error') }}</p>
                <ul class="mb-0 list-disc ps-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.close') }}"></button>
            </div>
        @endif
    </div>
@endif

