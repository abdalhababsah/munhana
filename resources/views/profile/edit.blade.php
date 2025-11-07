@extends('backend.layouts.master')

@section('title', 'Profile')
@section('page-title', 'Profile Settings')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Profile Information Card -->
    <div class="card">
        <div class="p-6">
            <h4 class="card-title mb-4">Profile Information</h4>
            <p class="text-gray-600 mb-6">Update your account's profile information and email address.</p>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label class="mb-2" for="name">Name</label>
                    <input id="name" class="form-input @error('name') border-danger @enderror" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    @error('name')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2" for="email">Email</label>
                    <input id="email" class="form-input @error('email') border-danger @enderror" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    @error('email')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">
                                Your email address is unverified.
                                <a href="{{ route('verification.send') }}" class="text-primary underline">Click here to re-send the verification email.</a>
                            </p>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn bg-primary text-white">Save Changes</button>
                    @if (session('status') === 'profile-updated')
                        <p class="text-success text-sm">Saved successfully.</p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Update Password Card -->
    <div class="card">
        <div class="p-6">
            <h4 class="card-title mb-4">Update Password</h4>
            <p class="text-gray-600 mb-6">Ensure your account is using a long, random password to stay secure.</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="mb-2" for="current_password">Current Password</label>
                    <input id="current_password" class="form-input @error('current_password', 'updatePassword') border-danger @enderror" type="password" name="current_password" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2" for="password">New Password</label>
                    <input id="password" class="form-input @error('password', 'updatePassword') border-danger @enderror" type="password" name="password" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2" for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" class="form-input @error('password_confirmation', 'updatePassword') border-danger @enderror" type="password" name="password_confirmation" autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn bg-primary text-white">Update Password</button>
                    @if (session('status') === 'password-updated')
                        <p class="text-success text-sm">Password updated successfully.</p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account Card -->
    <div class="card">
        <div class="p-6">
            <h4 class="card-title mb-4 text-danger">Delete Account</h4>
            <p class="text-gray-600 mb-6">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>

            <button type="button" data-hs-overlay="#delete-account-modal" class="btn bg-danger text-white">Delete Account</button>
        </div>
    </div>
</div>

<!-- Delete Account Confirmation Modal -->
<div id="delete-account-modal" class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[60] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-white shadow-sm rounded-xl">
            <div class="flex justify-between items-center py-3 px-4 border-b">
                <h3 class="font-bold text-gray-800">Delete Account</h3>
                <button type="button" class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100" data-hs-overlay="#delete-account-modal">
                    <span class="sr-only">Close</span>
                    <i class="uil uil-times text-xl"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <p class="text-gray-800 mb-4">
                    Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label class="mb-2" for="password_delete">Password</label>
                        <input id="password_delete" class="form-input @error('password', 'userDeletion') border-danger @enderror" type="password" name="password" placeholder="Enter your password">
                        @error('password', 'userDeletion')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end items-center gap-x-2 py-3">
                        <button type="button" class="btn bg-gray-200 text-gray-800" data-hs-overlay="#delete-account-modal">Cancel</button>
                        <button type="submit" class="btn bg-danger text-white">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
