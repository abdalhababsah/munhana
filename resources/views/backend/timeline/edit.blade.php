@extends('backend.layouts.master')

@section('title', __('messages.edit') . ' ' . __('messages.activity'))
@section('page-title', __('messages.edit') . ' ' . __('messages.activity'))

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

    <!-- Delay Warning -->
    @if(isset($timeline->is_delayed) && $timeline->is_delayed)
    <div class="p-4 bg-danger/10 text-danger rounded-md flex items-center">
        <i class="uil uil-exclamation-triangle text-2xl me-3"></i>
        <div>
            <p class="font-semibold">{{ __('messages.activity_delayed') }}</p>
            <p class="text-sm">{{ __('messages.actual_end_date_exceeds_planned') }}</p>
        </div>
    </div>
    @endif

    <!-- Edit Timeline Activity Form -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.edit') }} {{ __('messages.activity') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.timeline.update', $timeline) }}" x-data="{
                plannedStartDate: '{{ old('planned_start_date', $timeline->planned_start_date ? \Carbon\Carbon::parse($timeline->planned_start_date)->format('Y-m-d') : '') }}',
                plannedEndDate: '{{ old('planned_end_date', $timeline->planned_end_date ? \Carbon\Carbon::parse($timeline->planned_end_date)->format('Y-m-d') : '') }}',
                actualStartDate: '{{ old('actual_start_date', $timeline->actual_start_date ? \Carbon\Carbon::parse($timeline->actual_start_date)->format('Y-m-d') : '') }}',
                actualEndDate: '{{ old('actual_end_date', $timeline->actual_end_date ? \Carbon\Carbon::parse($timeline->actual_end_date)->format('Y-m-d') : '') }}',
                get plannedDuration() {
                    if (!this.plannedStartDate || !this.plannedEndDate) return 0;
                    const start = new Date(this.plannedStartDate);
                    const end = new Date(this.plannedEndDate);
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    return diffDays;
                },
                get actualDuration() {
                    if (!this.actualStartDate || !this.actualEndDate) return 0;
                    const start = new Date(this.actualStartDate);
                    const end = new Date(this.actualEndDate);
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    return diffDays;
                }
            }">
                @csrf
                @method('PUT')

                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- Activity Name (English) -->
                    <div>
                        <label for="activity_name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.activity_name') }} (English) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="activity_name"
                               name="activity_name"
                               value="{{ old('activity_name', $timeline->activity_name) }}"
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
                               value="{{ old('activity_name_ar', $timeline->activity_name_ar) }}"
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
                                <option value="{{ $item->id }}" {{ old('boq_item_id', $timeline->boq_item_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_code }} - {{ app()->getLocale() === 'ar' ? $item->item_name_ar : $item->item_name }}
                                </option>
                            @endforeach
                        </select>
                        @if($timeline->boqItem)
                        <div class="mt-2 p-3 bg-info/10 rounded-md">
                            <p class="text-sm text-info">
                                <i class="uil uil-link me-1"></i>
                                {{ __('messages.linked_to') }}: {{ $timeline->boqItem->item_code }} -
                                {{ app()->getLocale() === 'ar' ? $timeline->boqItem->item_name_ar : $timeline->boqItem->item_name }}
                            </p>
                        </div>
                        @endif
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
                               value="{{ old('planned_start_date', $timeline->planned_start_date ? \Carbon\Carbon::parse($timeline->planned_start_date)->format('Y-m-d') : '') }}"
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
                               value="{{ old('planned_end_date', $timeline->planned_end_date ? \Carbon\Carbon::parse($timeline->planned_end_date)->format('Y-m-d') : '') }}"
                               class="form-input w-full @error('planned_end_date') border-danger @enderror"
                               required>
                        @error('planned_end_date')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Planned Duration (Auto-calculated) -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.planned_duration') }}
                        </label>
                        <div class="p-4 bg-gray-50 rounded-md border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ __('messages.calculated_duration') }}:</span>
                                <span class="text-2xl font-bold text-primary">
                                    <span x-text="plannedDuration"></span> {{ __('messages.days') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actual Start Date -->
                    <div>
                        <label for="actual_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.actual_start_date') }}
                        </label>
                        <input type="date"
                               id="actual_start_date"
                               name="actual_start_date"
                               x-model="actualStartDate"
                               value="{{ old('actual_start_date', $timeline->actual_start_date ? \Carbon\Carbon::parse($timeline->actual_start_date)->format('Y-m-d') : '') }}"
                               class="form-input w-full @error('actual_start_date') border-danger @enderror">
                        @error('actual_start_date')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actual End Date -->
                    <div>
                        <label for="actual_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.actual_end_date') }}
                        </label>
                        <input type="date"
                               id="actual_end_date"
                               name="actual_end_date"
                               x-model="actualEndDate"
                               value="{{ old('actual_end_date', $timeline->actual_end_date ? \Carbon\Carbon::parse($timeline->actual_end_date)->format('Y-m-d') : '') }}"
                               class="form-input w-full @error('actual_end_date') border-danger @enderror">
                        @error('actual_end_date')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actual Duration (Auto-calculated) -->
                    <div class="lg:col-span-2" x-show="actualStartDate && actualEndDate">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.actual_duration') }}
                        </label>
                        <div class="p-4 bg-info/10 rounded-md border border-info">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-info">{{ __('messages.actual_duration_calculated') }}:</span>
                                <span class="text-2xl font-bold text-info">
                                    <span x-text="actualDuration"></span> {{ __('messages.days') }}
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
                            <option value="pending" {{ old('status', $timeline->status) === 'pending' ? 'selected' : '' }}>
                                {{ __('messages.pending') }}
                            </option>
                            <option value="in_progress" {{ old('status', $timeline->status) === 'in_progress' ? 'selected' : '' }}>
                                {{ __('messages.in_progress') }}
                            </option>
                            <option value="completed" {{ old('status', $timeline->status) === 'completed' ? 'selected' : '' }}>
                                {{ __('messages.completed') }}
                            </option>
                            <option value="delayed" {{ old('status', $timeline->status) === 'delayed' ? 'selected' : '' }}>
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
                        {{ __('messages.update') }}
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
