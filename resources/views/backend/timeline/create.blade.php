@extends('backend.layouts.master')

@section('title', __('messages.new') . ' ' . __('messages.activity'))
@section('page-title', __('messages.new') . ' ' . __('messages.activity'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">
            {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
        </h4>
        <a href="{{ route('backend.timeline.index', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <!-- Create Timeline Activity Form -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.new') }} {{ __('messages.activity') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.timeline.store') }}" x-data="{
                plannedStartDate: '{{ old('planned_start_date') }}',
                plannedEndDate: '{{ old('planned_end_date') }}',
                get duration() {
                    if (!this.plannedStartDate || !this.plannedEndDate) return 0;
                    const start = new Date(this.plannedStartDate);
                    const end = new Date(this.plannedEndDate);
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    return diffDays;
                }
            }">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- Activity Name (English) -->
                    <div>
                        <label for="activity_name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.activity_name') }} (English) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="activity_name"
                               name="activity_name"
                               value="{{ old('activity_name') }}"
                               class="form-input w-full @error('activity_name') border-danger @enderror"
                               required>
                        @error('activity_name')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Activity Name (Arabic) -->
                    <div>
                        <label for="activity_name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.activity_name') }} (العربية) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="activity_name_ar"
                               name="activity_name_ar"
                               value="{{ old('activity_name_ar') }}"
                               class="form-input w-full @error('activity_name_ar') border-danger @enderror"
                               dir="rtl"
                               required>
                        @error('activity_name_ar')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link to BOQ Item -->
                    <div class="lg:col-span-2">
                        <label for="boq_item_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.link_to_boq_item') }}
                        </label>
                        <select id="boq_item_id"
                                name="boq_item_id"
                                class="form-select w-full @error('boq_item_id') border-danger @enderror">
                            <option value="">{{ __('messages.no_link') }}</option>
                            @foreach($boqItems as $item)
                                <option value="{{ $item->id }}" {{ old('boq_item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_code }} - {{ app()->getLocale() === 'ar' ? $item->item_name_ar : $item->item_name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">{{ __('messages.optional_link_activity_to_boq') }}</p>
                        @error('boq_item_id')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Planned Start Date -->
                    <div>
                        <label for="planned_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.planned_start_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               id="planned_start_date"
                               name="planned_start_date"
                               x-model="plannedStartDate"
                               value="{{ old('planned_start_date') }}"
                               class="form-input w-full @error('planned_start_date') border-danger @enderror"
                               required>
                        @error('planned_start_date')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Planned End Date -->
                    <div>
                        <label for="planned_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.planned_end_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               id="planned_end_date"
                               name="planned_end_date"
                               x-model="plannedEndDate"
                               value="{{ old('planned_end_date') }}"
                               class="form-input w-full @error('planned_end_date') border-danger @enderror"
                               required>
                        @error('planned_end_date')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration (Auto-calculated) -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.duration') }}
                        </label>
                        <div class="p-4 bg-gray-50 rounded-md border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ __('messages.calculated_duration') }}:</span>
                                <span class="text-2xl font-bold text-primary">
                                    <span x-text="duration"></span> {{ __('messages.days') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.status') }} <span class="text-danger">*</span>
                        </label>
                        <select id="status"
                                name="status"
                                class="form-select w-full @error('status') border-danger @enderror"
                                required>
                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>
                                {{ __('messages.pending') }}
                            </option>
                            <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>
                                {{ __('messages.in_progress') }}
                            </option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>
                                {{ __('messages.completed') }}
                            </option>
                            <option value="delayed" {{ old('status') === 'delayed' ? 'selected' : '' }}>
                                {{ __('messages.delayed') }}
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4 mt-6 pt-6 border-t">
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-check me-2"></i>
                        {{ __('messages.create') }}
                    </button>
                    <a href="{{ route('backend.timeline.index', $project) }}" class="btn btn-light">
                        {{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
