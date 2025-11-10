@extends('backend.layouts.master')

@section('title', __('messages.new') . ' ' . __('messages.cost') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.new') . ' ' . __('messages.cost'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $project->contract_number }}</p>
        </div>
        <a href="{{ route('backend.costs.index', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.cost_details') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.costs.store') }}">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Cost Date -->
                    <div>
                        <label for="cost_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.cost_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               id="cost_date"
                               name="cost_date"
                               value="{{ old('cost_date', date('Y-m-d')) }}"
                               class="form-input w-full @error('cost_date') border-danger @enderror"
                               required>
                        @error('cost_date')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cost Type -->
                    <div>
                        <label for="cost_type" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.cost_type') }} <span class="text-danger">*</span>
                        </label>
                        <select id="cost_type"
                                name="cost_type"
                                class="form-input w-full @error('cost_type') border-danger @enderror"
                                required>
                            <option value="">{{ __('messages.select_type') }}</option>
                            <option value="labor" {{ old('cost_type') === 'labor' ? 'selected' : '' }}>{{ __('messages.labor') }}</option>
                            <option value="material" {{ old('cost_type') === 'material' ? 'selected' : '' }}>{{ __('messages.material') }}</option>
                            <option value="equipment" {{ old('cost_type') === 'equipment' ? 'selected' : '' }}>{{ __('messages.equipment') }}</option>
                            <option value="other" {{ old('cost_type') === 'other' ? 'selected' : '' }}>{{ __('messages.other') }}</option>
                        </select>
                        @error('cost_type')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.amount') }} <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               id="amount"
                               name="amount"
                               value="{{ old('amount') }}"
                               step="0.01"
                               min="0"
                               class="form-input w-full @error('amount') border-danger @enderror"
                               placeholder="0.00"
                               required>
                        @error('amount')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description (English) -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.description') }} ({{ __('messages.english') }}) <span class="text-danger">*</span>
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              class="form-input w-full @error('description') border-danger @enderror"
                              placeholder="{{ __('messages.cost_description_placeholder') }}"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description (Arabic) -->
                <div class="mb-6">
                    <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.description') }} ({{ __('messages.arabic') }})
                    </label>
                    <textarea id="description_ar"
                              name="description_ar"
                              rows="4"
                              dir="rtl"
                              class="form-input w-full @error('description_ar') border-danger @enderror"
                              placeholder="{{ __('messages.cost_description_placeholder_ar') }}">{{ old('description_ar') }}</textarea>
                    @error('description_ar')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('backend.costs.index', $project) }}" class="btn btn-light">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-check me-2"></i>
                        {{ __('messages.create') }} {{ __('messages.cost') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
