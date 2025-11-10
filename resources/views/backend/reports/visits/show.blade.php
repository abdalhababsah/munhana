@extends('backend.layouts.master')

@section('title', __('messages.site_visit') . ' - ' . \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d'))
@section('page-title', __('messages.site_visit'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ __('messages.site_visit') }} - {{ \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d') }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">
                {{ app()->getLocale() === 'ar' ? $visit->project->name_ar : $visit->project->name }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('backend.visits.edit', $visit) }}" class="btn btn-warning">
                <i class="uil uil-edit me-2"></i>
                {{ __('messages.edit') }}
            </a>
            <form method="POST" action="{{ route('backend.visits.destroy', $visit) }}"
                  onsubmit="return confirm('{{ __('messages.confirm_delete') }}');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="uil uil-trash me-2"></i>
                    {{ __('messages.delete') }}
                </button>
            </form>
            <a href="{{ route('backend.visits.index', $visit->project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.visit_details') }}</h4>
        </div>
        <div class="p-6">
            <div class="grid lg:grid-cols-2 gap-6">
                <div>
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.visit_date') }}</span>
                        <span class="text-sm text-gray-900 font-semibold">
                            {{ \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d') }}
                        </span>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.visitor_name') }}</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $visit->visitor_name }}</span>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.created_by') }}</span>
                        <span class="text-sm text-gray-900">{{ $visit->creator->name }}</span>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.created_at') }}</span>
                        <span class="text-sm text-gray-900">{{ $visit->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t">
                <h5 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.purpose') }}</h5>
                <p class="text-gray-600 whitespace-pre-line" {!! app()->getLocale() === 'ar' && $visit->purpose_ar ? 'dir="rtl"' : '' !!}>
                    {{ app()->getLocale() === 'ar' && $visit->purpose_ar ? $visit->purpose_ar : $visit->purpose }}
                </p>
            </div>

            <div class="mt-6 pt-6 border-t">
                <h5 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.findings') }}</h5>
                <p class="text-gray-600 whitespace-pre-line" {!! app()->getLocale() === 'ar' && $visit->findings_ar ? 'dir="rtl"' : '' !!}>
                    {{ app()->getLocale() === 'ar' && $visit->findings_ar ? $visit->findings_ar : $visit->findings }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
