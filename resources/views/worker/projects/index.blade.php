@extends('backend.layouts.master')

@section('title', __('messages.my_projects'))
@section('page-title', __('messages.my_projects'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">{{ __('messages.my_projects') }}</h4>
        <span class="text-sm text-gray-500">{{ $projects->count() }} {{ __('messages.projects') }}</span>
    </div>

    @if($projects->count() > 0)
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($projects as $project)
        <div class="card hover:shadow-lg transition">
            <div class="p-6">
                <!-- Project Status Badge -->
                <div class="flex items-center justify-between mb-4">
                    @if($project->status === 'active' || $project->status === 'in_progress')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-success/10 text-success">
                            {{ __('messages.' . $project->status) }}
                        </span>
                    @elseif($project->status === 'completed')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-info/10 text-info">
                            {{ __('messages.completed') }}
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                            {{ __('messages.' . $project->status) }}
                        </span>
                    @endif
                </div>

                <!-- Project Name -->
                <h5 class="text-lg font-semibold text-gray-900 mb-3">
                    {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                </h5>

                <!-- Contract Number -->
                <p class="text-sm text-gray-500 mb-3">
                    <i class="uil uil-file-contract-dollar me-1"></i>
                    {{ $project->contract_number }}
                </p>

                <!-- Client -->
                <p class="text-sm text-gray-600 mb-3">
                    <i class="uil uil-user me-1"></i>
                    {{ $project->client->name }}
                </p>

                <!-- Worker Role -->
                <div class="pb-4 mb-4 border-b">
                    <p class="text-xs text-gray-500 mb-1">{{ __('messages.my_role') }}</p>
                    <p class="text-sm font-medium text-primary">
                        {{ app()->getLocale() === 'ar' && $project->pivot->role_description_ar ? $project->pivot->role_description_ar : ($project->pivot->role_description ?? '-') }}
                    </p>
                </div>

                <!-- Actions -->
                <a href="{{ route('worker.projects.show', $project) }}" class="btn btn-primary w-full">
                    <i class="uil uil-eye me-2"></i>
                    {{ __('messages.view_details') }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card">
        <div class="p-12 text-center">
            <i class="uil uil-briefcase text-6xl text-gray-300 mb-4"></i>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.no_projects_assigned') }}</h4>
            <p class="text-gray-500">{{ __('messages.no_projects_assigned_message') }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
