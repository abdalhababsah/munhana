@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
    <div class="card overflow-hidden sm:rounded-md rounded-none">
        <div class="px-6 py-8">
            <a href="{{ route('backend.dashboard') }}" class="flex justify-center mb-8">
                <img class="h-6" src="{{ asset('dash/assets/images/logo-dark.png') }}" alt="Logo">
            </a>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label class="mb-2" for="name">Name</label>
                    <input id="name" class="form-input @error('name') border-danger @enderror" type="text" name="name" value="{{ old('name') }}" placeholder="Enter your name" required autofocus autocomplete="name">
                    @error('name')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2" for="email">Email Address</label>
                    <input id="email" class="form-input @error('email') border-danger @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="username">
                    @error('email')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2" for="password">Password</label>
                    <input id="password" class="form-input @error('password') border-danger @enderror" type="password" name="password" placeholder="Enter your password" required autocomplete="new-password">
                    @error('password')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2" for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" class="form-input @error('password_confirmation') border-danger @enderror" type="password" name="password_confirmation" placeholder="Confirm your password" required autocomplete="new-password">
                    @error('password_confirmation')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center mb-4">
                    <input type="checkbox" class="form-checkbox rounded" id="terms" required>
                    <label class="ms-2" for="terms">I accept <a href="#" class="text-primary">Terms and Conditions</a></label>
                </div>

                <div class="flex justify-center mb-3">
                    <button type="submit" class="btn w-full text-white bg-primary"> Sign Up Free </button>
                </div>
            </form>
        </div>
    </div>

    <p class="text-white text-center mt-8">Already have an account ?<a href="{{ route('login') }}" class="font-medium ms-1">Sign In</a></p>
</div>
@endsection
