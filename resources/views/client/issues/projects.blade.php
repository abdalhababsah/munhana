@extends('backend.layouts.master')

@section('title', __('messages.report_issue'))
@section('page-title', __('messages.report_issue'))

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('messages.select_project_to_report_issue') }}</h4>
    </div>
    <div class="p-6">
        @if($projects->isEmpty())
            <div class="text-center py-12">
                <i class="uil uil-clipboard text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_completed_projects') }}</p>
            </div>
        @else
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($projects as $project)
                    <div class="border rounded-lg p-5 flex flex-col gap-3">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('messages.project') }}</p>
                            <h5 class="text-lg font-semibold text-gray-900">
                                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                            </h5>
                            <p class="text-xs text-gray-500">{{ $project->contract_number }}</p>
                        </div>
                        <a href="{{ route('client.issues.create', $project) }}" class="btn btn-primary">
                            {{ __('messages.report_issue') }}
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
