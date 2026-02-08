@php
    $flashTypes = ['success', 'error', 'warning', 'info', 'status'];
    $hasAlerts = false;

    foreach ($flashTypes as $type) {
        if (session()->has($type)) {
            $hasAlerts = true;
            break;
        }
    }
    if ($errors->any()) {
        $hasAlerts = true;
    }
@endphp

@if ($hasAlerts)
    <div class="space-y-3 mb-6">
        @foreach ($flashTypes as $type)
            @if (session()->has($type))
                @php
                    $message = session($type);
                    $colors = match ($type) {
                        'success' => 'bg-green-50 text-green-800 border-green-200',
                        'error', 'danger' => 'bg-red-50 text-red-800 border-red-200',
                        'warning' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
                        'info', 'status', 'primary' => 'bg-blue-50 text-blue-800 border-blue-200',
                        default => 'bg-gray-50 text-gray-800 border-gray-200',
                    };
                    $icon = match ($type) {
                        'success' => 'uil-check-circle',
                        'error', 'danger' => 'uil-exclamation-circle',
                        'warning' => 'uil-exclamation-triangle',
                        'info', 'status', 'primary' => 'uil-info-circle',
                        default => 'uil-info-circle',
                    };
                @endphp
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms
                    class="flex items-center p-4 mb-4 text-sm rounded-lg border {{ $colors }}" role="alert">
                    <i class="uil {{ $icon }} text-xl me-3"></i>
                    <span class="sr-only">{{ ucfirst($type) }}</span>
                    <div class="flex-1">
                        {{ $message }}
                    </div>
                    <button type="button" @click="show = false"
                        class="ms-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 hover:bg-black/5 focus:ring-2 focus:outline-none focus:ring-black/10 transition-colors">
                        <span class="sr-only">{{ __('messages.close') }}</span>
                        <i class="uil uil-times text-lg"></i>
                    </button>
                </div>
            @endif
        @endforeach

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms
                class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                <i class="uil uil-exclamation-circle text-xl me-3 mt-0.5"></i>
                <div class="flex-1">
                    <span class="font-medium">{{ __('messages.error_occurred') }}</span>
                    <p class="mt-1 text-sm">{{ __('messages.please_check_form_errors') }}</p>
                </div>
                <button type="button" @click="show = false"
                    class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 hover:bg-red-200 focus:ring-2 focus:outline-none focus:ring-red-400">
                    <span class="sr-only">{{ __('messages.close') }}</span>
                    <i class="uil uil-times text-lg"></i>
                </button>
            </div>
        @endif
    </div>
@endif