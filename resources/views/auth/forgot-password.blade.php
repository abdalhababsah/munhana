@extends('layouts.guest')

@section('title', 'Recover Password')

@section('content')
<div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
    <div class="card overflow-hidden sm:rounded-md rounded-none">
        <div class="px-6 py-8">
            <a href="{{ route('backend.dashboard') }}" class="flex justify-center mb-8">
                <img class="h-6" src="{{ asset('dash/assets/images/logo-dark.png') }}" alt="Logo">
            </a>

            <p class="text-center lg:w-3/4 mx-auto mb-6">Enter your email address and we'll send you an email with instructions to reset your password.</p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-success/10 text-success rounded-md text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label class="mb-2" for="email">Email Address</label>
                    <input id="email" class="form-input @error('email') border-danger @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                    @error('email')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center mb-3">
                    <button type="submit" class="btn w-full"
                    style="background: #e6dac0; color: #2f2f2f;"
                    > Reset Password </button>
                </div>
            </form>
        </div>
    </div>

    <p class="text-white text-center mt-8">Already have an account ?<a href="{{ route('login') }}" class="font-medium ms-1">Sign In</a></p>
</div>
@endsection
