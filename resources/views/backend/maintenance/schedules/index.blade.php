@extends('backend.layouts.master')

@section('title', __('messages.maintenance_schedules'))
@section('page-title', __('messages.maintenance_schedules'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Project Header -->
    <div class="card">
        <div class="p-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">{{ __('messages.project') }}</p>
                <h4 class="text-2xl font-semibold text-gray-900">
                    {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                </h4>
                <p class="text-sm text-gray-500">{{ $project->contract_number }}</p>
            </div>
            <a href="{{ route('backend.maintenance-schedules.create', $project) }}" class="btn btn-primary">
                <i class="uil uil-plus me-2"></i>{{ __('messages.add_maintenance_schedule') }}
            </a>
        </div>
    </div>

    <!-- Upcoming Maintenance -->
    <div class="grid md:grid-cols-2 gap-4">
        <div class="card border-l-4 border-primary">
            <div class="p-5">
                <p class="text-sm text-gray-500 mb-2">{{ __('messages.upcoming_maintenance') }}</p>
                @if($upcoming)
                    <h3 class="text-xl font-semibold text-gray-900">{{ $upcoming->maintenance_date?->format('Y-m-d') }}</h3>
                    <p class="text-gray-600">
                        {{ app()->getLocale() === 'ar' ? $upcoming->maintenance_type_ar : $upcoming->maintenance_type }}
                    </p>
                @else
                    <p class="text-gray-500">{{ __('messages.no_upcoming_maintenance') }}</p>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="p-5">
                <p class="text-sm text-gray-500 mb-2">{{ __('messages.filter_by_status') }}</p>
                <div class="flex flex-wrap gap-2">
                    @php
                        $statuses = [
                            null => __('messages.all'),
                            'scheduled' => __('messages.scheduled'),
                            'completed' => __('messages.completed'),
                            'cancelled' => __('messages.cancelled'),
                        ];
                    @endphp
                    @foreach($statuses as $key => $label)
                        <a href="{{ route('backend.maintenance-schedules.index', ['project' => $project->id, 'status' => $key]) }}"
                           class="px-3 py-1 rounded-full text-xs font-semibold border {{ $status === $key ? 'bg-primary text-white border-primary' : 'bg-white text-gray-600 border-gray-200' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.maintenance_schedules') }}</h4>
        </div>
        @if($schedules->isEmpty())
            <div class="p-10 text-center">
                <i class="uil uil-calender text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">{{ __('messages.no_maintenance_schedules_yet') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.maintenance_type') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.notes') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($schedules as $schedule)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $schedule->maintenance_date?->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ app()->getLocale() === 'ar' ? $schedule->maintenance_type_ar : $schedule->maintenance_type }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badgeClasses = match($schedule->status) {
                                        'scheduled' => 'bg-info/10 text-info',
                                        'completed' => 'bg-success/10 text-success',
                                        'cancelled' => 'bg-danger/10 text-danger',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClasses }}">
                                    {{ __('messages.' . $schedule->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ app()->getLocale() === 'ar' ? $schedule->notes_ar : $schedule->notes }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button type="button"
                                        data-hs-overlay="#edit-schedule-{{ $schedule->id }}"
                                        class="btn btn-sm btn-light me-2">
                                    {{ __('messages.edit') }}
                                </button>
                                <form method="POST"
                                      action="{{ route('backend.maintenance-schedules.destroy', $schedule) }}"
                                      class="inline"
                                      onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.delete') }}</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div id="edit-schedule-{{ $schedule->id }}" class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-50 overflow-y-auto">
                            <div class="hs-overlay-open:mt-8 hs-overlay-open:opacity-100 mt-0 opacity-0 transition-all max-w-2xl mx-auto">
                                <div class="bg-white rounded-lg shadow-lg">
                                    <div class="flex items-center justify-between p-4 border-b">
                                        <h4 class="text-lg font-semibold">{{ __('messages.edit_maintenance_schedule') }}</h4>
                                        <button type="button" class="text-gray-400 hover:text-gray-600" data-hs-overlay="#edit-schedule-{{ $schedule->id }}">✕</button>
                                    </div>
                                    <form method="POST" action="{{ route('backend.maintenance-schedules.update', $schedule) }}" class="p-6 space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="form-label">{{ __('messages.maintenance_date') }}</label>
                                                <input type="date" name="maintenance_date" value="{{ $schedule->maintenance_date?->format('Y-m-d') }}" class="form-input w-full" required>
                                            </div>
                                            <div>
                                                <label class="form-label">{{ __('messages.status') }}</label>
                                                <select name="status" class="form-select w-full" required>
                                                    <option value="scheduled" {{ $schedule->status === 'scheduled' ? 'selected' : '' }}>{{ __('messages.scheduled') }}</option>
                                                    <option value="completed" {{ $schedule->status === 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                                                    <option value="cancelled" {{ $schedule->status === 'cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">{{ __('messages.maintenance_type') }} (EN)</label>
                                                <input type="text" name="maintenance_type" value="{{ $schedule->maintenance_type }}" class="form-input w-full" required>
                                            </div>
                                            <div>
                                                <label class="form-label">{{ __('messages.maintenance_type') }} (AR)</label>
                                                <input type="text" name="maintenance_type_ar" value="{{ $schedule->maintenance_type_ar }}" dir="rtl" class="form-input w-full" required>
                                            </div>
                                        </div>
                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="form-label">{{ __('messages.notes') }} (EN)</label>
                                                <textarea name="notes" rows="3" class="form-textarea w-full">{{ $schedule->notes }}</textarea>
                                            </div>
                                            <div>
                                                <label class="form-label">{{ __('messages.notes') }} (AR)</label>
                                                <textarea name="notes_ar" rows="3" dir="rtl" class="form-textarea w-full">{{ $schedule->notes_ar }}</textarea>
                                            </div>
                                        </div>
                                        <div class="flex justify-end gap-3">
                                            <button type="button" class="btn btn-light" data-hs-overlay="#edit-schedule-{{ $schedule->id }}">{{ __('messages.cancel') }}</button>
                                            <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
