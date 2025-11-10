@extends('backend.layouts.master')

@section('title', __('messages.edit') . ' ' . __('messages.financial_claim') . ' - ' . $claim->claim_number)
@section('page-title', __('messages.edit') . ' ' . __('messages.financial_claim'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ $claim->claim_number }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">
                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </p>
        </div>
        <a href="{{ route('backend.claims.index', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.edit') }} {{ __('messages.financial_claim') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.claims.update', $claim) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Claim Number (Read-only) -->
                    <div>
                        <label for="claim_number" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.claim_number') }}
                        </label>
                        <input type="text"
                               id="claim_number"
                               value="{{ $claim->claim_number }}"
                               class="form-input w-full bg-gray-50"
                               readonly>
                    </div>

                    <!-- Claim Date -->
                    <div>
                        <label for="claim_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.claim_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               id="claim_date"
                               name="claim_date"
                               value="{{ old('claim_date', $claim->claim_date) }}"
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
                               value="{{ old('amount', $claim->amount) }}"
                               step="0.01"
                               min="0"
                               class="form-input w-full @error('amount') border-danger @enderror"
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
                            <option value="pending" {{ old('status', $claim->status) === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                            <option value="approved" {{ old('status', $claim->status) === 'approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                            <option value="paid" {{ old('status', $claim->status) === 'paid' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
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
                              required>{{ old('description', $claim->description) }}</textarea>
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
                              class="form-input w-full @error('description_ar') border-danger @enderror">{{ old('description_ar', $claim->description_ar) }}</textarea>
                    @error('description_ar')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Attachment -->
                @if($claim->attachment_path)
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">{{ __('messages.current_attachment') }}</p>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <i class="uil uil-file text-xl text-primary"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ basename($claim->attachment_path) }}</p>
                        </div>
                        <a href="{{ asset('storage/' . $claim->attachment_path) }}"
                           download
                           class="text-primary hover:text-primary/80">
                            <i class="uil uil-download-alt text-lg"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- New Attachment -->
                <div class="mb-6">
                    <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $claim->attachment_path ? __('messages.replace_attachment') : __('messages.attachment') }}
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
                    <button type="submit" class="btn btn-warning">
                        <i class="uil uil-check me-2"></i>
                        {{ __('messages.update') }} {{ __('messages.financial_claim') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
