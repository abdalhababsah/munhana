@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
    <div class="card overflow-hidden sm:rounded-md rounded-none">
        <div class="px-6 py-8">
            <a href="{{ route('backend.dashboard') }}" class="flex justify-center mb-8">
                <img class="h-6" src="{{ asset('dash/assets/images/logo-dark.png') }}" alt="Logo">
            </a>

            <p class="text-center lg:w-3/4 mx-auto mb-6">Enter your email and new password to reset your password.</p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-4">
                    <label class="mb-2" for="email">Email Address</label>
                    <input id="email" class="form-input @error('email') border-danger @enderror" type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="Enter your email" required autofocus autocomplete="username">
                    @error('email')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2" for="password">New Password</label>
                    <input id="password" class="form-input @error('password') border-danger @enderror" type="password" name="password" placeholder="Enter new password" required autocomplete="new-password">
                    @error('password')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2" for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" class="form-input @error('password_confirmation') border-danger @enderror" type="password" name="password_confirmation" placeholder="Confirm new password" required autocomplete="new-password">
                    @error('password_confirmation')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center mb-3">
                    <button type="submit" class="btn w-full text-white bg-primary"> Reset Password </button>
                </div>
            </form>
        </div>
    </div>

    <p class="text-white text-center mt-8">Remember your password ?<a href="{{ route('login') }}" class="font-medium ms-1">Sign In</a></p>
</div>
@endsection
