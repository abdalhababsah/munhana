@extends('backend.layouts.master')

@section('title', __('messages.warranty_issue_details'))
@section('page-title', __('messages.warranty_issue_details'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('backend.warranty-issues.index', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>{{ __('messages.back') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.issue_information') }}</h4>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.issue_title') }} (EN)</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $issue->issue_title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.issue_title') }} (AR)</p>
                    <p class="text-lg font-semibold text-gray-900" dir="rtl">{{ $issue->issue_title_ar }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.issue_date') }}</p>
                <p class="text-gray-900">{{ $issue->issue_date?->format('Y-m-d') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.status') }}</p>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $issue->status === 'open' ? 'bg-danger/10 text-danger' : ($issue->status === 'in_progress' ? 'bg-warning/10 text-warning' : 'bg-success/10 text-success') }}">
                        {{ __('messages.' . $issue->status) }}
                    </span>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                @if($issue->description)
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.description') }} (EN)</p>
                    <p class="text-gray-700 whitespace-pre-line">{{ $issue->description }}</p>
                </div>
                @endif
                @if($issue->description_ar)
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.description') }} (AR)</p>
                    <p class="text-gray-700 whitespace-pre-line" dir="rtl">{{ $issue->description_ar }}</p>
                </div>
                @endif
            </div>

            @if($issue->photo_path)
            <div>
                <p class="text-sm text-gray-500 mb-2">{{ __('messages.photo') }}</p>
                <img src="{{ asset('storage/' . $issue->photo_path) }}" alt="Issue photo" class="rounded-lg max-h-96 object-contain bg-gray-50">
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.update_status') }}</h4>
        </div>
        <div class="p-6 space-y-4">
            <form method="POST" action="{{ route('backend.warranty-issues.update', $issue) }}" class="grid md:grid-cols-2 gap-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="form-label">{{ __('messages.status') }}</label>
                    <select name="status" class="form-select w-full @error('status') border-danger @enderror" required>
                        <option value="open" {{ $issue->status === 'open' ? 'selected' : '' }}>{{ __('messages.open') }}</option>
                        <option value="in_progress" {{ $issue->status === 'in_progress' ? 'selected' : '' }}>{{ __('messages.in_progress') }}</option>
                        <option value="resolved" {{ $issue->status === 'resolved' ? 'selected' : '' }}>{{ __('messages.resolved') }}</option>
                    </select>
                    @error('status')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.resolution_notes') }}</label>
                    <textarea name="resolution_notes" rows="3" class="form-textarea w-full @error('resolution_notes') border-danger @enderror">{{ old('resolution_notes', $issue->resolution_notes) }}</textarea>
                    @error('resolution_notes')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2 flex items-center gap-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-sync me-2"></i>{{ __('messages.update') }}
                    </button>
                </div>
            </form>
            <form method="POST" action="{{ route('backend.warranty-issues.destroy', $issue) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">{{ __('messages.delete') }}</button>
            </form>
        </div>
    </div>

    @include('backend.partials.comments', ['commentable' => $issue, 'comments' => $comments])
</div>
@endsection
