@extends('backend.layouts.master')

@section('title', __('messages.add_warranty_issue'))
@section('page-title', __('messages.add_warranty_issue'))

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('messages.add_warranty_issue') }}</h4>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ route('backend.warranty-issues.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">{{ __('messages.issue_title') }} (EN) <span class="text-danger">*</span></label>
                    <input type="text" name="issue_title" class="form-input w-full @error('issue_title') border-danger @enderror" value="{{ old('issue_title') }}" required>
                    @error('issue_title')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.issue_title') }} (AR) <span class="text-danger">*</span></label>
                    <input type="text" name="issue_title_ar" dir="rtl" class="form-input w-full @error('issue_title_ar') border-danger @enderror" value="{{ old('issue_title_ar') }}" required>
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
                <label class="form-label">{{ __('messages.photo') }} ({{ __('messages.optional') }})</label>
                <input type="file" name="photo_path" accept="image/*" class="form-input w-full @error('photo_path') border-danger @enderror">
                @error('photo_path')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="btn btn-primary">
                    <i class="uil uil-check me-2"></i>{{ __('messages.save') }}
                </button>
                <a href="{{ route('backend.warranty-issues.index', $project) }}" class="btn btn-light">
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
