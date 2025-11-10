@extends('backend.layouts.master')

@section('title', __('messages.maintenance_schedules'))
@section('page-title', __('messages.maintenance_schedules'))

@section('content')
<div class="card">
    <div class="card-header flex items-center justify-between">
        <h4 class="card-title">{{ __('messages.select_project') }}</h4>
        <a href="{{ route('backend.projects.index', ['status' => 'completed']) }}" class="btn btn-light">
            {{ __('messages.view_completed_projects') }}
        </a>
    </div>
    <div class="p-6">
        @if($projects->isEmpty())
            <div class="text-center py-10">
                <i class="uil uil-calender text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">{{ __('messages.no_completed_projects') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.project') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.contract_number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($projects as $project)
                        <tr>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $project->contract_number }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-success/10 text-success">
                                    {{ __('messages.completed') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('backend.maintenance-schedules.index', $project) }}" class="btn btn-sm btn-primary">
                                    {{ __('messages.manage_maintenance_schedules') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
