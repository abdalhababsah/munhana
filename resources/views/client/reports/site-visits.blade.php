@extends('backend.layouts.master')

@section('title', __('messages.site_visits'))
@section('page-title', __('messages.site_visits'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="card">
        <div class="p-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">{{ __('messages.project') }}</p>
                <h4 class="text-2xl font-semibold text-gray-900">
                    {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                </h4>
                <p class="text-sm text-gray-500 mt-1">{{ $project->contract_number }}</p>
            </div>
            <a href="{{ route('client.projects.show', $project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <!-- Total -->
    <div class="card">
        <div class="p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">{{ __('messages.total_visits') }}</p>
                <h3 class="text-3xl font-bold text-purple-600">{{ $siteVisits->count() }}</h3>
            </div>
            <i class="uil uil-clipboard-notes text-4xl text-purple-400"></i>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.site_visits') }}</h4>
        </div>

        @if($siteVisits->isEmpty())
            <div class="p-12 text-center">
                <i class="uil uil-clipboard-notes text-6xl text-gray-300 mb-4"></i>
                <h5 class="text-lg font-semibold text-gray-900">{{ __('messages.no_site_visits_yet') }}</h5>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                {{ __('messages.visit_date') }}
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                {{ __('messages.visitor_name') }}
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                {{ __('messages.purpose') }}
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                {{ __('messages.findings') }}
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                {{ __('messages.created_by') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($siteVisits as $visit)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $visit->visit_date?->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $visit->visitor_name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ app()->getLocale() === 'ar' && $visit->purpose_ar ? $visit->purpose_ar : $visit->purpose }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ app()->getLocale() === 'ar' && $visit->findings_ar ? $visit->findings_ar : $visit->findings }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $visit->creator?->name ?? '—' }}
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
