@extends('backend.layouts.master')

@section('title', __('messages.edit') . ' ' . __('messages.site_visit'))
@section('page-title', __('messages.edit') . ' ' . __('messages.site_visit'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">
            {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
        </h4>
        <a href="{{ route('backend.visits.index', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.edit') }} {{ __('messages.site_visit') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.visits.update', $visit) }}">
                @csrf
                @method('PUT')

                <div class="grid lg:grid-cols-2 gap-6">
                    <div>
                        <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.visit_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date" id="visit_date" name="visit_date"
                               value="{{ old('visit_date', \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d')) }}"
                               class="form-input w-full @error('visit_date') border-danger @enderror" required>
                        @error('visit_date')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="visitor_name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.visitor_name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="visitor_name" name="visitor_name"
                               value="{{ old('visitor_name', $visit->visitor_name) }}"
                               class="form-input w-full @error('visitor_name') border-danger @enderror" required>
                        @error('visitor_name')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.purpose') }} (English) <span class="text-danger">*</span>
                        </label>
                        <textarea id="purpose" name="purpose" rows="4"
                                  class="form-textarea w-full @error('purpose') border-danger @enderror"
                                  placeholder="{{ __('messages.visit_purpose_placeholder') }}"
                                  required>{{ old('purpose', $visit->purpose) }}</textarea>
                        @error('purpose')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="purpose_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.purpose') }} (العربية)
                        </label>
                        <textarea id="purpose_ar" name="purpose_ar" rows="4" dir="rtl"
                                  class="form-textarea w-full @error('purpose_ar') border-danger @enderror"
                                  placeholder="{{ __('messages.visit_purpose_placeholder') }}">{{ old('purpose_ar', $visit->purpose_ar) }}</textarea>
                        @error('purpose_ar')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="findings" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.findings') }} (English) <span class="text-danger">*</span>
                        </label>
                        <textarea id="findings" name="findings" rows="4"
                                  class="form-textarea w-full @error('findings') border-danger @enderror"
                                  placeholder="{{ __('messages.visit_findings_placeholder') }}"
                                  required>{{ old('findings', $visit->findings) }}</textarea>
                        @error('findings')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="findings_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.findings') }} (العربية)
                        </label>
                        <textarea id="findings_ar" name="findings_ar" rows="4" dir="rtl"
                                  class="form-textarea w-full @error('findings_ar') border-danger @enderror"
                                  placeholder="{{ __('messages.visit_findings_placeholder') }}">{{ old('findings_ar', $visit->findings_ar) }}</textarea>
                        @error('findings_ar')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-6 pt-6 border-t">
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-check me-2"></i>
                        {{ __('messages.update') }}
                    </button>
                    <a href="{{ route('backend.visits.index', $project) }}" class="btn btn-light">
                        {{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
