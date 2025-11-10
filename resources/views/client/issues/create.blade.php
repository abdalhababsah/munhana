@extends('backend.layouts.master')

@section('title', __('messages.report_issue'))
@section('page-title', __('messages.report_issue'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="card">
        <div class="p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ __('messages.project') }}</p>
                <h4 class="text-2xl font-semibold text-gray-900">
                    {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                </h4>
                <p class="text-sm text-gray-500">{{ $project->contract_number }}</p>
            </div>
            <a href="{{ route('client.issues.index') }}" class="btn btn-light">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h4 class="card-title">{{ __('messages.report_issue') }}</h4></div>
        <div class="p-6">
            <form method="POST" action="{{ route('client.issues.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">{{ __('messages.issue_title') }} (EN) <span class="text-danger">*</span></label>
                        <input type="text" name="issue_title" value="{{ old('issue_title') }}" class="form-input w-full @error('issue_title') border-danger @enderror" required>
                        @error('issue_title')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.issue_title') }} (AR) <span class="text-danger">*</span></label>
                        <input type="text" name="issue_title_ar" dir="rtl" value="{{ old('issue_title_ar') }}" class="form-input w-full @error('issue_title_ar') border-danger @enderror" required>
                        @error('issue_title_ar')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">{{ __('messages.description') }} (EN)</label>
                        <textarea name="description" rows="4" class="form-textarea w-full @error('description') border-danger @enderror">{{ old('description') }}</textarea>
                        @error('description')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.description') }} (AR)</label>
                        <textarea name="description_ar" rows="4" dir="rtl" class="form-textarea w-full @error('description_ar') border-danger @enderror">{{ old('description_ar') }}</textarea>
                        @error('description_ar')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">{{ __('messages.photo') }} <span class="text-danger">*</span></label>
                    <input type="file" name="photo" id="issue-photo" accept="image/*" class="form-input w-full @error('photo') border-danger @enderror" required>
                    @error('photo')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    <div class="mt-4" id="photo-preview" hidden>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.photo_preview') }}</p>
                        <img src="#" alt="preview" class="max-h-64 rounded-lg border">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-upload me-2"></i>{{ __('messages.submit') }}
                    </button>
                    <a href="{{ route('client.issues.index') }}" class="btn btn-light">{{ __('messages.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('issue-photo');
        const previewWrapper = document.getElementById('photo-preview');
        const previewImage = previewWrapper?.querySelector('img');

        if (!input || !previewWrapper || !previewImage) {
            return;
        }

        input.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (!file) {
                previewWrapper.hidden = true;
                previewImage.src = '#';
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                previewImage.src = e.target.result;
                previewWrapper.hidden = false;
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
