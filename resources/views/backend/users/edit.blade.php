@extends('backend.layouts.master')

@section('title', __('messages.edit') . ' ' . __('messages.user') . ' - ' . $user->name)
@section('page-title', __('messages.edit') . ' ' . __('messages.user'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">{{ __('messages.edit') }} {{ __('messages.user') }}</h4>
        <a href="{{ route('backend.users.index') }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.user_information') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.users.update', $user) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.name') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           class="form-input w-full @error('name') border-danger @enderror"
                           required
                           autofocus>
                    @error('name')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.email') }} <span class="text-danger">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           class="form-input w-full @error('email') border-danger @enderror"
                           required>
                    @error('email')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.password') }}
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-input w-full @error('password') border-danger @enderror">
                        @error('password')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ __('messages.leave_blank_to_keep_current') }}</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.confirm_password') }}
                        </label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-input w-full">
                    </div>
                </div>

                <!-- Password Note -->
                <div class="bg-info/10 border border-info/20 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="uil uil-info-circle text-info text-xl mt-0.5"></i>
                        <p class="text-sm text-gray-700">{{ __('messages.current_password_will_remain_unchanged') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.role') }} <span class="text-danger">*</span>
                        </label>
                        <select id="role"
                                name="role"
                                class="form-input w-full @error('role') border-danger @enderror"
                                required>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
                            <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>{{ __('messages.client') }}</option>
                            <option value="worker" {{ old('role', $user->role) === 'worker' ? 'selected' : '' }}>{{ __('messages.worker') }}</option>
                        </select>
                        @error('role')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.phone') }}
                        </label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               class="form-input w-full @error('phone') border-danger @enderror">
                        @error('phone')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Language -->
                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.language') }} <span class="text-danger">*</span>
                        </label>
                        <select id="language"
                                name="language"
                                class="form-input w-full @error('language') border-danger @enderror"
                                required>
                            <option value="ar" {{ old('language', $user->language) === 'ar' ? 'selected' : '' }}>{{ __('messages.arabic') }}</option>
                            <option value="en" {{ old('language', $user->language) === 'en' ? 'selected' : '' }}>{{ __('messages.english') }}</option>
                        </select>
                        @error('language')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('backend.users.index') }}" class="btn btn-light">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="uil uil-check me-2"></i>
                        {{ __('messages.update') }} {{ __('messages.user') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
