@extends('backend.layouts.master')

@section('title', app()->getLocale() === 'ar' ? $project->name_ar : $project->name)
@section('page-title', __('messages.project_details'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">{{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}</h4>
            <p class="text-sm text-gray-500 mt-1">{{ $project->contract_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('worker.projects.index') }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <!-- Project Info Card -->
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.project_information') }}</h4>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">{{ __('messages.status') }}</p>
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
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">{{ __('messages.client') }}</p>
                        <p class="text-sm font-medium text-gray-900">{{ $project->client->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">{{ __('messages.my_role') }}</p>
                        <p class="text-sm font-medium text-primary">
                            {{ app()->getLocale() === 'ar' && $workerRole?->role_description_ar ? $workerRole->role_description_ar : ($workerRole?->role_description ?? '-') }}
                        </p>
                    </div>

                    @if($project->start_date)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">{{ __('messages.start_date') }}</p>
                        <p class="text-sm font-medium text-gray-900">{{ $project->start_date->format('Y-m-d') }}</p>
                    </div>
                    @endif

                    @if($project->end_date)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">{{ __('messages.end_date') }}</p>
                        <p class="text-sm font-medium text-gray-900">{{ $project->end_date->format('Y-m-d') }}</p>
                    </div>
                    @endif

                    @if($workerRole?->assigned_at)
                    <div class="pt-4 border-t">
                        <p class="text-sm text-gray-500 mb-1">{{ __('messages.assigned_since') }}</p>
                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($workerRole->assigned_at)->format('Y-m-d') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics & Quick Actions -->
        <div class="lg:col-span-2">
            <!-- Worker Statistics -->
            <div class="card mb-6">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.my_contributions') }}</h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-primary/5 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.reports_created') }}</p>
                            <p class="text-2xl font-bold text-primary">{{ $workerStats['total_reports'] }}</p>
                        </div>
                        <div class="bg-warning/5 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.materials_received') }}</p>
                            <p class="text-2xl font-bold text-warning">{{ $workerStats['total_materials'] }}</p>
                        </div>
                        <div class="bg-success/5 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.photos_uploaded') }}</p>
                            <p class="text-2xl font-bold text-success">{{ $workerStats['total_photos'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.quick_actions') }}</h4>
                </div>
                <div class="p-6">
                    <div class="grid md:grid-cols-3 gap-4">
                        <a href="{{ route('worker.reports.create', $project) }}" class="card hover:shadow-lg transition">
                            <div class="p-5 flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                    <i class="uil uil-clipboard-notes text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ __('messages.add_report') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('messages.daily_report') }}</p>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('worker.materials.create', $project) }}" class="card hover:shadow-lg transition">
                            <div class="p-5 flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-warning/10 text-warning flex items-center justify-center">
                                    <i class="uil uil-truck text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ __('messages.add_material') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('messages.material_delivery') }}</p>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('worker.photos.create', $project) }}" class="card hover:shadow-lg transition">
                            <div class="p-5 flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-success/10 text-success flex items-center justify-center">
                                    <i class="uil uil-camera text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ __('messages.upload_photos') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('messages.site_photos') }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    @if($project->dailyReports->count() > 0)
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.my_recent_reports') }}</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.date') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.work_done') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('messages.completion') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($project->dailyReports as $report)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $report->report_date?->format('Y-m-d') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900 line-clamp-2">{{ Str::limit($report->work_done, 100) }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-medium text-primary">{{ number_format($report->completion_percentage, 0) }}%</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('worker.reports.show', [$project, $report]) }}" class="text-primary hover:text-primary/80">
                                <i class="uil uil-eye text-lg"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Photos -->
    @if($project->sitePhotos->count() > 0)
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.my_recent_photos') }}</h4>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach($project->sitePhotos as $photo)
                <div class="group relative aspect-square rounded-lg overflow-hidden bg-gray-100">
                    <img src="{{ Storage::url($photo->photo_path) }}" alt="{{ $photo->description }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                        <a href="{{ Storage::url($photo->photo_path) }}" target="_blank" class="text-white">
                            <i class="uil uil-eye text-2xl"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
