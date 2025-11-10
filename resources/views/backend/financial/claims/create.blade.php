@extends('backend.layouts.master')

@section('title', __('messages.new') . ' ' . __('messages.financial_claim') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.new') . ' ' . __('messages.financial_claim'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $project->contract_number }}</p>
        </div>
        <a href="{{ route('backend.claims.index', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.claim_details') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.claims.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Claim Number -->
                    <div>
                        <label for="claim_number" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.claim_number') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="claim_number"
                               name="claim_number"
                               value="{{ old('claim_number', $claimNumber) }}"
                               class="form-input w-full bg-gray-50 @error('claim_number') border-danger @enderror"
                               readonly
                               required>
                        @error('claim_number')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ __('messages.auto_generated') }}</p>
                    </div>

                    <!-- Claim Date -->
                    <div>
                        <label for="claim_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.claim_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               id="claim_date"
                               name="claim_date"
                               value="{{ old('claim_date', date('Y-m-d')) }}"
                               class="form-input w-full @error('claim_date') border-danger @enderror"
                               required>
                        @error('claim_date')
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

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.status') }} <span class="text-danger">*</span>
                        </label>
                        <select id="status"
                                name="status"
                                class="form-input w-full @error('status') border-danger @enderror"
                                required>
                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                            <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                            <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
                        </select>
                        @error('status')
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
                              placeholder="{{ __('messages.claim_description_placeholder') }}"
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
                              placeholder="{{ __('messages.claim_description_placeholder_ar') }}">{{ old('description_ar') }}</textarea>
                    @error('description_ar')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Attachment -->
                <div class="mb-6">
                    <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.attachment') }}
                    </label>
                    <input type="file"
                           id="attachment"
                           name="attachment"
                           accept=".pdf,.xlsx,.xls,.doc,.docx"
                           class="form-input w-full @error('attachment') border-danger @enderror">
                    @error('attachment')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">{{ __('messages.claim_attachment_help') }}</p>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('backend.claims.index', $project) }}" class="btn btn-light">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-check me-2"></i>
                        {{ __('messages.create') }} {{ __('messages.financial_claim') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
