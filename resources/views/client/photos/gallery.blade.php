@extends('backend.layouts.master')

@section('title', __('messages.site_photos'))
@section('page-title', __('messages.site_photos'))

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

    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.filter_photos') }}</h4>
        </div>
        <div class="p-6">
            <form method="GET" class="grid lg:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.start_date') }}
                    </label>
                    <input type="date"
                           id="start_date"
                           name="start_date"
                           value="{{ $filters['start_date'] ?? '' }}"
                           class="form-input w-full">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.end_date') }}
                    </label>
                    <input type="date"
                           id="end_date"
                           name="end_date"
                           value="{{ $filters['end_date'] ?? '' }}"
                           class="form-input w-full">
                </div>
                <div class="flex items-end gap-3 lg:col-span-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-filter me-2"></i>
                        {{ __('messages.filter') }}
                    </button>
                    @if(!empty($filters['start_date']) || !empty($filters['end_date']))
                    <a href="{{ route('client.photos.index', $project) }}" class="btn btn-light">
                        <i class="uil uil-times me-2"></i>
                        {{ __('messages.clear') }}
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Gallery -->
    @if($photosByDate->isEmpty())
        <div class="card">
            <div class="p-12 text-center">
                <i class="uil uil-image text-6xl text-gray-300 mb-4"></i>
                <h5 class="text-lg font-semibold text-gray-900">{{ __('messages.no_photos_yet') }}</h5>
            </div>
        </div>
    @else
        <div class="space-y-6">
            @foreach($photosByDate as $date => $photos)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title flex items-center gap-2">
                            <i class="uil uil-calendar-alt text-primary"></i>
                            {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}
                        </h4>
                    </div>
                    <div class="p-6">
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($photos as $photo)
                                <a href="{{ route('client.photos.show', $photo) }}"
                                   class="group block border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                             alt="{{ $photo->description }}"
                                             class="w-full h-56 object-cover group-hover:scale-[1.01] transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                    </div>
                                    <div class="p-4 space-y-1">
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ app()->getLocale() === 'ar' && $photo->description_ar ? $photo->description_ar : $photo->description ?? __('messages.site_photo') }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ __('messages.photo_date') }}: {{ $photo->photo_date?->format('Y-m-d') }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
