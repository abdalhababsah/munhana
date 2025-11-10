@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')
<div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
    <div class="card overflow-hidden sm:rounded-md rounded-none">
        <div class="px-6 py-8">
            <a href="{{ route('backend.dashboard') }}" class="flex justify-center mb-8">
                <img class="h-6" src="{{ asset('dash/assets/images/logo-dark.png') }}" alt="Logo">
            </a>

            <p class="text-center mb-6">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 p-4 bg-success/10 text-success rounded-md text-sm">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            @endif

            <div class="flex flex-col gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn w-full"
                    
                    style="background: #e6dac0; color: #2f2f2f;"
                    >Resend Verification Email</button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn w-full bg-gray-200 text-gray-700 hover:bg-gray-300">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
