@extends('backend.layouts.master')

@section('title', (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.project_details'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Project Header -->
    <div class="card">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-1">
                    <h4 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                    </h4>
                    <p class="text-gray-600">{{ $project->contract_number }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($project->status === 'active' || $project->status === 'in_progress')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-success/10 text-success">
                            {{ __('messages.' . $project->status) }}
                        </span>
                    @elseif($project->status === 'completed')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-info/10 text-info">
                            {{ __('messages.completed') }}
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-600">
                            {{ __('messages.' . $project->status) }}
                        </span>
                    @endif
                    <a href="{{ route('client.projects.index') }}" class="btn btn-light">
                        <i class="uil uil-arrow-left me-2"></i>
                        {{ __('messages.back') }}
                    </a>
                    @if(in_array($project->status, ['completed', 'archived']))
                        <a href="{{ route('client.issues.create', $project) }}" class="btn btn-outline-danger">
                            <i class="uil uil-comment-question me-2"></i>
                            {{ __('messages.report_issue') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Progress Bar -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">{{ __('messages.project_completion') }}</span>
                    <span class="text-sm font-bold {{ $project->completion_percentage < 50 ? 'text-danger' : ($project->completion_percentage < 80 ? 'text-warning' : 'text-success') }}">
                        {{ number_format($project->completion_percentage, 1) }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full {{ $project->completion_percentage < 50 ? 'bg-danger' : ($project->completion_percentage < 80 ? 'bg-warning' : 'bg-success') }}"
                         style="width: {{ min($project->completion_percentage, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Section -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title flex items-center gap-2">
                <i class="uil uil-info-circle text-primary"></i>
                {{ __('messages.overview') }}
            </h4>
        </div>
        <div class="p-6">
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h5 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.basic_information') }}</h5>

                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.contract_number') }}</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $project->contract_number }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.start_date') }}</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.end_date') }}</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') }}</span>
                    </div>

                    @if($project->location)
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.location') }}</span>
                        <span class="text-sm text-gray-900">{{ app()->getLocale() === 'ar' && $project->location_ar ? $project->location_ar : $project->location }}</span>
                    </div>
                    @endif

                    @if($project->budget)
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.budget') }}</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ number_format($project->budget, 2) }} {{ __('messages.currency') }}</span>
                    </div>
                    @endif
                </div>

                <!-- Statistics -->
                <div>
                    <h5 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.project_statistics') }}</h5>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.boq_items') }}</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $stats['total_boq_items'] }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.total_cost') }}</p>
                            <p class="text-xl font-bold text-green-600">{{ number_format($stats['total_boq_cost'], 0) }}</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.activities') }}</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $stats['total_activities'] }}</p>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.daily_reports') }}</p>
                            <p class="text-2xl font-bold text-orange-600">{{ $stats['total_reports'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($project->description || $project->description_ar)
            <div class="mt-6 pt-6 border-t">
                <h5 class="text-lg font-semibold text-gray-900 mb-3">{{ __('messages.description') }}</h5>
                <p class="text-gray-600 whitespace-pre-line">
                    {{ app()->getLocale() === 'ar' && $project->description_ar ? $project->description_ar : $project->description }}
                </p>
            </div>
            @endif
        </div>
    </div>

    <!-- Timeline Section -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title flex items-center gap-2">
                <i class="uil uil-schedule text-primary"></i>
                {{ __('messages.timeline') }}
            </h4>
        </div>
        <div class="p-6">
            @if($project->timelines->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.activity') }}</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.dates') }}</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.status') }}</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.progress') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($project->timelines as $activity)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ app()->getLocale() === 'ar' ? $activity->activity_name_ar : $activity->activity_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($activity->start_date)->format('Y-m-d') }} - {{ \Carbon\Carbon::parse($activity->end_date)->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $activity->status === 'completed' ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning' }}">
                                    {{ __('messages.' . $activity->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-primary" style="width: {{ $activity->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-sm">{{ $activity->progress_percentage }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="uil uil-schedule text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_timeline_activities') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- BOQ Section -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title flex items-center gap-2">
                <i class="uil uil-bill text-primary"></i>
                {{ __('messages.boq') }}
            </h4>
        </div>
        <div class="p-6">
            @if($project->boqItems->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.item') }}</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.unit') }}</th>
                            <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">{{ __('messages.quantity') }}</th>
                            <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">{{ __('messages.unit_price') }}</th>
                            <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">{{ __('messages.total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($project->boqItems as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ app()->getLocale() === 'ar' ? $item->item_name_ar : $item->item_name }}</p>
                                @if($item->description || $item->description_ar)
                                <p class="text-xs text-gray-500">{{ app()->getLocale() === 'ar' && $item->description_ar ? $item->description_ar : $item->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->unit }}</td>
                            <td class="px-6 py-4 text-end text-sm text-gray-900">{{ number_format($item->quantity, 2) }}</td>
                            <td class="px-6 py-4 text-end text-sm text-gray-900">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-6 py-4 text-end text-sm font-semibold text-gray-900">{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-50 font-semibold">
                            <td colspan="4" class="px-6 py-4 text-end">{{ __('messages.total') }}</td>
                            <td class="px-6 py-4 text-end text-lg">{{ number_format($stats['total_boq_cost'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="uil uil-bill text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_boq_items') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Reports Section -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title flex items-center gap-2">
                <i class="uil uil-file-alt text-primary"></i>
                {{ __('messages.reports') }}
            </h4>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="border rounded-lg p-5 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('messages.daily_reports') }}</p>
                            <h4 class="text-2xl font-bold text-gray-900">{{ $project->dailyReports->count() }}</h4>
                        </div>
                        <i class="uil uil-file-alt text-3xl text-primary/60"></i>
                    </div>
                    <p class="text-sm text-gray-600">{{ __('messages.daily_work_summary') }}</p>
                    <a href="{{ route('client.reports.daily', $project) }}" class="btn btn-sm btn-primary">
                        {{ __('messages.view_details') }}
                    </a>
                </div>

                <div class="border rounded-lg p-5 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('messages.material_deliveries') }}</p>
                            <h4 class="text-2xl font-bold text-gray-900">{{ $project->materialDeliveries->count() }}</h4>
                        </div>
                        <i class="uil uil-truck text-3xl text-emerald-500/70"></i>
                    </div>
                    <p class="text-sm text-gray-600">{{ __('messages.delivery_history') }}</p>
                    <a href="{{ route('client.reports.material-deliveries', $project) }}" class="btn btn-sm btn-primary">
                        {{ __('messages.view_details') }}
                    </a>
                </div>

                <div class="border rounded-lg p-5 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('messages.site_visits') }}</p>
                            <h4 class="text-2xl font-bold text-gray-900">{{ $project->siteVisits->count() }}</h4>
                        </div>
                        <i class="uil uil-clipboard-notes text-3xl text-purple-500/70"></i>
                    </div>
                    <p class="text-sm text-gray-600">{{ __('messages.visit_details') }}</p>
                    <a href="{{ route('client.reports.site-visits', $project) }}" class="btn btn-sm btn-primary">
                        {{ __('messages.view_details') }}
                    </a>
                </div>

                <div class="border rounded-lg p-5 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('messages.weekly_plans') }}</p>
                            <h4 class="text-2xl font-bold text-gray-900">{{ $project->weeklyPlans->count() }}</h4>
                        </div>
                        <i class="uil uil-schedule text-3xl text-orange-500/70"></i>
                    </div>
                    <p class="text-sm text-gray-600">{{ __('messages.weekly_plan_details') }}</p>
                    <a href="{{ route('client.reports.weekly-plans', $project) }}" class="btn btn-sm btn-primary">
                        {{ __('messages.view_details') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Site Photos Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-image text-primary"></i>
                    {{ __('messages.photos') }}
                </h4>
                @if($project->sitePhotos->count() > 6)
                <a href="{{ route('client.photos.index', $project) }}" class="btn btn-sm btn-primary">
                    <i class="uil uil-image me-2"></i>
                    {{ __('messages.view_all') }}
                </a>
                @endif
            </div>
        </div>
        <div class="p-6">
            @php
                $previewPhotos = $project->sitePhotos->take(6);
            @endphp
            @if($previewPhotos->isEmpty())
                <div class="text-center py-12">
                    <i class="uil uil-image text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">{{ __('messages.no_photos_yet') }}</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($previewPhotos as $photo)
                    <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="{{ $photo->description }}" class="w-full h-48 object-cover">
                        <div class="p-3">
                            <p class="text-sm font-medium text-gray-900 mb-1">
                                {{ app()->getLocale() === 'ar' && $photo->description_ar ? $photo->description_ar : ($photo->description ?? __('messages.site_photo')) }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $photo->photo_date?->format('Y-m-d') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
