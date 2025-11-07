@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')
<div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
    <div class="card overflow-hidden sm:rounded-md rounded-none">
        <div class="px-6 py-8">
            <a href="{{ route('backend.dashboard') }}" class="flex justify-center mb-8">
                <img class="h-6" src="{{ asset('dash/assets/images/logo-dark.png') }}" alt="Logo">
            </a>

            <p class="text-center lg:w-3/4 mx-auto mb-6">This is a secure area of the application. Please confirm your password before continuing.</p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="mb-4">
                    <label class="mb-2" for="password">Password</label>
                    <input id="password" class="form-input @error('password') border-danger @enderror" type="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                    @error('password')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center mb-3">
                    <button type="submit" class="btn w-full text-white bg-primary"> Confirm </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
