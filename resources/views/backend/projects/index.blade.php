@extends('backend.layouts.master')

@section('title', __('messages.projects'))
@section('page-title', __('messages.projects'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header with Create Button -->
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">{{ __('messages.all_projects') }}</h4>
        <a href="{{ route('backend.projects.create') }}" class="btn btn-primary">
            <i class="uil uil-plus me-2"></i>
            {{ __('messages.new_project') }}
        </a>
    </div>

    <!-- Search and Filters Card -->
    <div class="card">
        <div class="p-6">
            <form method="GET" action="{{ route('backend.projects.index') }}" class="space-y-4">
                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.search') }}
                        </label>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="{{ __('messages.project_name') }}, {{ __('messages.contract_number') }}"
                               class="form-input w-full">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.status') }}
                        </label>
                        <select id="status" name="status" class="form-select w-full">
                            <option value="">{{ __('messages.status') }}</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                {{ __('messages.active') }}
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                {{ __('messages.completed') }}
                            </option>
                            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>
                                {{ __('messages.archived') }}
                            </option>
                        </select>
                    </div>

                    <!-- Client Filter -->
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.client') }}
                        </label>
                        <select id="client_id" name="client_id" class="form-select w-full">
                            <option value="">{{ __('messages.client') }}</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-1">
                            <i class="uil uil-filter me-2"></i>
                            {{ __('messages.search') }}
                        </button>
                        <a href="{{ route('backend.projects.index') }}" class="btn btn-light">
                            <i class="uil uil-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Projects Table Card -->
    <div class="card">
        <div class="p-6">
            @if(session('success'))
            <div class="mb-4 p-4 bg-success/10 text-success rounded-md">
                {{ session('success') }}
            </div>
            @endif

            @if($projects->count() > 0)
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
                                {{ __('messages.contract_number') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.start_date') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.end_date') }}
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
                        @foreach($projects as $project)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ app()->getLocale() === 'ar' ? $project->location_ar : $project->location }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $project->client->name }}</div>
                                <div class="text-sm text-gray-500">{{ $project->client->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $project->contract_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $project->start_date->format('Y-m-d') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $project->end_date->format('Y-m-d') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($project->status === 'active')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-success/10 text-success">
                                        {{ __('messages.active') }}
                                    </span>
                                @elseif($project->status === 'completed')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-info/10 text-info">
                                        {{ __('messages.completed') }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ __('messages.archived') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('backend.projects.show', $project) }}"
                                       class="text-info hover:text-info-dark"
                                       title="{{ __('messages.view') }}">
                                        <i class="uil uil-eye text-lg"></i>
                                    </a>
                                    <a href="{{ route('backend.projects.edit', $project) }}"
                                       class="text-warning hover:text-warning-dark"
                                       title="{{ __('messages.edit') }}">
                                        <i class="uil uil-edit text-lg"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('backend.projects.destroy', $project) }}"
                                          onsubmit="return confirm('{{ __('messages.confirm_delete') }}');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-danger hover:text-danger-dark"
                                                title="{{ __('messages.delete') }}">
                                            <i class="uil uil-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $projects->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <i class="uil uil-briefcase text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">{{ __('messages.no_data') }}</p>
                <a href="{{ route('backend.projects.create') }}" class="btn btn-primary mt-4">
                    <i class="uil uil-plus me-2"></i>
                    {{ __('messages.new_project') }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
