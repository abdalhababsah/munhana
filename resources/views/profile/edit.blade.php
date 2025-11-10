@extends(
    auth()->user()->role === 'admin' ? 'backend.layouts.master' :
    (auth()->user()->role === 'client' ? 'backend.layouts.master' : 'backend.layouts.master')
)

@section('title', __('messages.edit_profile'))
@section('page-title', __('messages.edit_profile'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Success Messages -->
    @if(session('success'))
        <div class="alert alert-success bg-success/10 border border-success/20 text-success px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Information Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold">{{ __('messages.profile_information') }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ __('messages.profile_information_description') }}</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.name') }}</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                           class="form-input w-full @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.email') }}</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                           class="form-input w-full @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone Field -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.phone') }}</label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                           class="form-input w-full @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Language Field -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.language') }}</label>
                    <select name="language" class="form-input w-full @error('language') border-red-500 @enderror" required>
                        <option value="ar" {{ auth()->user()->language === 'ar' ? 'selected' : '' }}>
                            {{ __('messages.arabic') }}
                        </option>
                        <option value="en" {{ auth()->user()->language === 'en' ? 'selected' : '' }}>
                            {{ __('messages.english') }}
                        </option>
                    </select>
                    @error('language')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role Display (Read-only) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.role') }}</label>
                    <div>
                        @if(auth()->user()->role === 'admin')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-primary/10 text-primary">
                                {{ __('messages.admin') }}
                            </span>
                        @elseif(auth()->user()->role === 'client')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-info/10 text-info">
                                {{ __('messages.client') }}
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-warning/10 text-warning">
                                {{ __('messages.worker') }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Password Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold">{{ __('messages.update_password') }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ __('messages.update_password_description') }}</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.current_password') }}</label>
                    <input type="password" name="current_password"
                           class="form-input w-full @error('current_password') border-red-500 @enderror" required>
                    @error('current_password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.new_password') }}</label>
                    <input type="password" name="password"
                           class="form-input w-full @error('password') border-red-500 @enderror" required>
                    <p class="text-xs text-gray-500 mt-1">{{ __('messages.password_requirements') }}</p>
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.confirm_password') }}</label>
                    <input type="password" name="password_confirmation"
                           class="form-input w-full" required>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        {{ __('messages.update_password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
