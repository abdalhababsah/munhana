@extends('backend.layouts.master')

@section('title', __('messages.worker_assignments') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.worker_assignments'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">{{ __('messages.worker_assignments') }}</h4>
            <p class="text-sm text-gray-500 mt-1">
                {{ __('messages.project') }}: {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </p>
        </div>
        <a href="{{ route('backend.projects.show', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <!-- Assign New Worker -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.assign_worker') }}</h4>
        </div>
        <div class="p-6">
            @if($availableWorkers->count() > 0)
            <form method="POST" action="{{ route('backend.workers.assignments.store', $project) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="worker_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.select_worker') }} <span class="text-danger">*</span>
                        </label>
                        <select id="worker_id" name="worker_id" class="form-input w-full" required>
                            <option value="">{{ __('messages.choose_worker') }}</option>
                            @foreach($availableWorkers as $worker)
                            <option value="{{ $worker->id }}">{{ $worker->name }} ({{ $worker->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="role_description" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.role_description') }} ({{ __('messages.english') }})
                        </label>
                        <input type="text" id="role_description" name="role_description" class="form-input w-full" placeholder="{{ __('messages.eg_site_supervisor') }}">
                    </div>
                    <div>
                        <label for="role_description_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.role_description') }} ({{ __('messages.arabic') }})
                        </label>
                        <input type="text" id="role_description_ar" name="role_description_ar" class="form-input w-full" placeholder="{{ __('messages.eg_site_supervisor_ar') }}" dir="rtl">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="uil uil-plus me-2"></i>
                    {{ __('messages.assign_worker') }}
                </button>
            </form>
            @else
            <p class="text-gray-500 text-center py-4">{{ __('messages.all_workers_assigned') }}</p>
            @endif
        </div>
    </div>

    <!-- Assigned Workers -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                {{ __('messages.assigned_workers') }}
                <span class="text-sm font-normal text-gray-500">({{ $project->assignedUsers->count() }})</span>
            </h4>
        </div>
        <div class="overflow-x-auto">
            @if($project->assignedUsers->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.worker') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.role_description') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.assigned_by') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.assigned_date') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($project->assignedUsers as $worker)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-warning/10 flex items-center justify-center me-3">
                                    <span class="text-sm font-semibold text-warning">{{ strtoupper(substr($worker->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $worker->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $worker->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($worker->pivot->role_description || $worker->pivot->role_description_ar)
                                <p class="text-sm text-gray-900">
                                    {{ app()->getLocale() === 'ar' && $worker->pivot->role_description_ar ? $worker->pivot->role_description_ar : $worker->pivot->role_description }}
                                </p>
                            @else
                                <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $assigner = \App\Models\User::find($worker->pivot->assigned_by);
                            @endphp
                            <span class="text-sm text-gray-600">{{ $assigner->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($worker->pivot->assigned_at)->format('Y-m-d') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button"
                                        class="text-warning hover:text-warning/80"
                                        data-hs-overlay="#edit-modal-{{ $worker->id }}"
                                        title="{{ __('messages.edit') }}">
                                    <i class="uil uil-edit text-lg"></i>
                                </button>
                                <form method="POST"
                                      action="{{ route('backend.workers.assignments.destroy', [$project, $worker]) }}"
                                      onsubmit="return confirm('{{ __('messages.confirm_remove_worker') }}');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-danger hover:text-danger/80"
                                            title="{{ __('messages.remove') }}">
                                        <i class="uil uil-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div id="edit-modal-{{ $worker->id }}" class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[60] overflow-x-hidden overflow-y-auto">
                        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                            <div class="flex flex-col bg-white border shadow-sm rounded-xl">
                                <div class="flex justify-between items-center py-3 px-4 border-b">
                                    <h3 class="font-bold text-gray-800">
                                        {{ __('messages.edit_assignment') }}
                                    </h3>
                                    <button type="button" class="hs-dropdown-toggle inline-flex flex-shrink-0 justify-center items-center h-8 w-8 rounded-md text-gray-500 hover:text-gray-400" data-hs-overlay="#edit-modal-{{ $worker->id }}">
                                        <i class="uil uil-times text-xl"></i>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('backend.workers.assignments.update', [$project, $worker]) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ __('messages.role_description') }} ({{ __('messages.english') }})
                                            </label>
                                            <input type="text" name="role_description" value="{{ $worker->pivot->role_description }}" class="form-input w-full">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ __('messages.role_description') }} ({{ __('messages.arabic') }})
                                            </label>
                                            <input type="text" name="role_description_ar" value="{{ $worker->pivot->role_description_ar }}" class="form-input w-full" dir="rtl">
                                        </div>
                                    </div>
                                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t">
                                        <button type="button" class="btn btn-light" data-hs-overlay="#edit-modal-{{ $worker->id }}">
                                            {{ __('messages.cancel') }}
                                        </button>
                                        <button type="submit" class="btn btn-warning">
                                            {{ __('messages.update') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-12 text-center">
                <i class="uil uil-users-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_workers_assigned') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
