@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
    <div class="card overflow-hidden sm:rounded-md rounded-none">
        <div class="px-6 py-8">
            <a href="{{ route('backend.dashboard') }}" class="flex justify-center mb-8">
                <img class="h-6" src="{{ asset('dash/assets/images/logo-dark.png') }}" alt="Logo">
            </a>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-success/10 text-success rounded-md text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="mb-2" for="email">Email Address</label>
                    <input id="email" class="form-input @error('email') border-danger @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus autocomplete="username">
                    @error('email')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label for="password">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-[#2f2f2f]">Forget Password ?</a>
                        @endif
                    </div>
                    <input id="password" class="form-input @error('password') border-danger @enderror" type="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                    @error('password')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center mb-4">
                    <input type="checkbox" class="form-checkbox rounded" id="remember_me" name="remember">
                    <label class="ms-2" for="remember_me">Remember me</label>
                </div>

                <div class="flex justify-center mb-3">
                    <button 
                    style="background: #e6dac0; color: #2f2f2f;"
                    type="submit" class="btn w-full "> Sign In </button>
                </div>
            </form>
        </div>
    </div>

    <p class="text-white text-center mt-8">Create an Account ?<a href="{{ route('register') }}" class="font-medium ms-1">Register</a></p>
</div>
@endsection
