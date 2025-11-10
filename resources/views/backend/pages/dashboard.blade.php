@extends('backend.layouts.master')

@section('title', __('messages.dashboard'))
@section('page-title', __('messages.dashboard'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Statistics Cards -->
    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
        <!-- Total Projects Card -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.projects') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $totalProjects }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-briefcase text-primary text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-gray-500 text-sm">{{ __('messages.all_projects') }}</span>
                </div>
            </div>
        </div>

        <!-- Active Projects Card -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.active_projects') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $activeProjects }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-check-circle text-success text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-success text-sm">{{ __('messages.active') }}</span>
                </div>
            </div>
        </div>

        <!-- Completed Projects Card -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.completed_projects') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $completedProjects }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-clipboard-alt text-info text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-info text-sm">{{ __('messages.completed') }}</span>
                </div>
            </div>
        </div>

        <!-- Total Clients Card -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.client') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $totalClients }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-users-alt text-warning text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-gray-500 text-sm">{{ __('messages.users') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Projects Table -->
    <div class="card">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="card-title">{{ __('messages.projects') }}</h4>
                <a href="{{ route('backend.projects.index') }}" class="btn btn-sm btn-primary">
                    {{ __('messages.view') }} {{ __('messages.all_projects') }}
                </a>
            </div>

            @if($recentProjects->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.project_name') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.client') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.start_date') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.status') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentProjects as $project)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $project->client->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $project->start_date->format('Y-m-d') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($project->status === 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success/10 text-success">
                                        {{ __('messages.active') }}
                                    </span>
                                @elseif($project->status === 'completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-info/10 text-info">
                                        {{ __('messages.completed') }}
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ __('messages.archived') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('backend.projects.show', $project) }}" class="text-primary hover:text-primary-dark">
                                    {{ __('messages.view') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-gray-500">{{ __('messages.no_data') }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="p-6">
                <h4 class="card-title mb-4">{{ __('messages.project_status') }}</h4>
                <div class="space-y-4">
                    @foreach(['active', 'completed', 'archived'] as $status)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full {{ $status === 'active' ? 'bg-success' : ($status === 'completed' ? 'bg-info' : 'bg-gray-400') }}"></div>
                            <span class="text-sm font-medium text-gray-700">{{ __('messages.' . $status) }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $projectsByStatus[$status] ?? 0 }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="card-title">{{ __('messages.contact_messages') }}</h4>
                    <a href="{{ route('backend.contacts.index') }}" class="btn btn-sm btn-light">{{ __('messages.view_all') }}</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentContacts as $contact)
                    <div class="flex items-center justify-between border-b last:border-b-0 pb-4 last:pb-0">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $contact->name }}</p>
                            <p class="text-xs text-gray-500">{{ $contact->subject }}</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $contact->status === 'new' ? 'bg-primary/10 text-primary' : ($contact->status === 'in_progress' ? 'bg-warning/10 text-warning' : 'bg-success/10 text-success') }}">
                            {{ __('messages.' . $contact->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">{{ __('messages.no_data') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
