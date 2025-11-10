@extends('backend.layouts.master')

@section('title', __('messages.user_management'))
@section('page-title', __('messages.user_management'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">{{ __('messages.user_management') }}</h4>
        <a href="{{ route('backend.users.create') }}" class="btn btn-primary">
            <i class="uil uil-plus me-2"></i>
            {{ __('messages.new') }} {{ __('messages.user') }}
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid lg:grid-cols-4 gap-6">
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_users') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-users-alt text-primary text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.admins') }}</p>
                        <h3 class="text-2xl font-bold text-danger">{{ $stats['admins'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-danger/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-shield-check text-danger text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.clients') }}</p>
                        <h3 class="text-2xl font-bold text-success">{{ $stats['clients'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-user-circle text-success text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.workers') }}</p>
                        <h3 class="text-2xl font-bold text-warning">{{ $stats['workers'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-hard-hat text-warning text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.search_and_filter') }}</h4>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('backend.users.index') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.search') }}
                    </label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-input w-full"
                           placeholder="{{ __('messages.search_by_name_or_email') }}">
                </div>
                <div class="flex-1">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.filter_by_role') }}
                    </label>
                    <select id="role" name="role" class="form-input w-full">
                        <option value="">{{ __('messages.all_roles') }}</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
                        <option value="client" {{ request('role') === 'client' ? 'selected' : '' }}>{{ __('messages.client') }}</option>
                        <option value="worker" {{ request('role') === 'worker' ? 'selected' : '' }}>{{ __('messages.worker') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="uil uil-search me-2"></i>
                    {{ __('messages.search') }}
                </button>
                @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('backend.users.index') }}" class="btn btn-light">
                    <i class="uil uil-times me-2"></i>
                    {{ __('messages.clear') }}
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.users') }}</h4>
        </div>
        <div class="overflow-x-auto">
            @if($users->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.name') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.email') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.role') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.phone') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.language') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.created_at') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center me-3">
                                    <span class="text-sm font-semibold text-primary">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $user->email }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role === 'admin')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-danger/10 text-danger">
                                    {{ __('messages.admin') }}
                                </span>
                            @elseif($user->role === 'client')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-success/10 text-success">
                                    {{ __('messages.client') }}
                                </span>
                            @elseif($user->role === 'worker')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-warning/10 text-warning">
                                    {{ __('messages.worker') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $user->phone ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $user->language === 'ar' ? __('messages.arabic') : __('messages.english') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $user->created_at->format('Y-m-d') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('backend.users.show', $user) }}"
                                   class="text-info hover:text-info/80"
                                   title="{{ __('messages.view') }}">
                                    <i class="uil uil-eye text-lg"></i>
                                </a>
                                <a href="{{ route('backend.users.edit', $user) }}"
                                   class="text-warning hover:text-warning/80"
                                   title="{{ __('messages.edit') }}">
                                    <i class="uil uil-edit text-lg"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST"
                                      action="{{ route('backend.users.destroy', $user) }}"
                                      onsubmit="return confirm('{{ __('messages.confirm_delete') }}');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-danger hover:text-danger/80"
                                            title="{{ __('messages.delete') }}">
                                        <i class="uil uil-trash text-lg"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t">
                {{ $users->links() }}
            </div>
            @else
            <div class="p-12 text-center">
                <i class="uil uil-users-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_data') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
