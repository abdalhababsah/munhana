@php
    $profileUser = Auth::user();
    $roleLabel = $profileUser?->role ? __('messages.' . $profileUser->role) : __('messages.user');
@endphp
<div class="profile-menu">
    <div class="flex flex-col items-center h-full gap-4 py-10 px-3">
        <!-- Profile Link -->
        <a href="{{ route('profile.edit') }}" type="button" class="flex flex-col items-center gap-1">
            <img src="{{ asset('dash/assets/images/users/avatar-6.jpg') }}" alt="user-image" class="rounded-full h-8 w-8">
            <span class="font-medium text-base">{{ $profileUser->name ?? __('messages.user') }}</span>
            <span class="text-sm">{{ $roleLabel }}</span>
        </a>

        <!-- Search Modal Button -->
        <button type="button" data-hs-overlay="#search-modal" class="bg-white rounded-full shadow-md p-2">
            <span class="sr-only">Search</span>
            <span class="flex items-center justify-center h-6 w-6">
                <i class="uil uil-search text-2xl"></i>
            </span>
        </button>

        <!-- Fullscreen Toggle Button -->
        <div class="flex">
            <button data-toggle="fullscreen" type="button" class="bg-white rounded-full shadow-md p-2">
                <span class="sr-only">Fullscreen Mode</span>
                <span class="flex items-center justify-center h-6 w-6">
                    <i class="uil uil-focus text-2xl"></i>
                </span>
            </button>
        </div>

        <!-- Logout Button -->
        <div class="flex">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-white rounded-full shadow-md p-2">
                    <span class="sr-only">Logout</span>
                    <span class="flex items-center justify-center h-6 w-6">
                        <i class="uil uil-sign-out-alt text-2xl"></i>
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>
