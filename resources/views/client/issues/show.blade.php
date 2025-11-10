@extends('backend.layouts.master')

@section('title', __('messages.issue'))
@section('page-title', __('messages.issue'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('client.issues.index') }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>{{ __('messages.back') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ app()->getLocale() === 'ar' ? $issue->issue_title_ar : $issue->issue_title }}</h4>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">
                    {{ __('messages.issue_date') }}: {{ $issue->issue_date?->format('Y-m-d') }}
                </span>
                @php
                    $badge = match($issue->status){
                        'open' => 'bg-danger/10 text-danger',
                        'in_progress' => 'bg-warning/10 text-warning',
                        'resolved' => 'bg-success/10 text-success',
                        default => 'bg-gray-100 text-gray-600'
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">{{ __('messages.' . $issue->status) }}</span>
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
                    <img src="{{ asset('storage/' . $issue->photo_path) }}" alt="issue photo" class="rounded-lg max-h-[32rem] object-contain bg-gray-50">
                </div>
            @endif

            @if($issue->status === 'resolved' && $issue->resolution_notes)
                <div class="border rounded-lg p-4 bg-success/5">
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.resolution_notes') }}</p>
                    <p class="text-gray-700">{{ $issue->resolution_notes }}</p>
                </div>
            @endif
        </div>
    </div>

    @if($project)
    <div class="grid md:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-header"><h4 class="card-title">{{ __('messages.project') }}</h4></div>
            <div class="p-6 space-y-2">
                <p class="font-semibold text-gray-900">{{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}</p>
                <p class="text-sm text-gray-500">{{ $project->contract_number }}</p>
                <p class="text-sm text-gray-500">
                    {{ __('messages.status') }}:
                    <span class="font-semibold">{{ __('messages.' . $project->status) }}</span>
                </p>
            </div>
        </div>
    </div>
    @endif

    @include('backend.partials.comments', ['commentable' => $issue, 'comments' => $comments])
</div>
@endsection
