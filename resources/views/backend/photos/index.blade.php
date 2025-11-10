@extends('backend.layouts.master')

@section('title', __('messages.site_photos') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.site_photos'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $project->contract_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('backend.photos.create', $project) }}" class="btn btn-primary">
                <i class="uil uil-plus me-2"></i>
                {{ __('messages.upload') }} {{ __('messages.photos') }}
            </a>
            <a href="{{ route('backend.projects.show', $project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_photos') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_photos'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-image text-primary text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.photo_dates') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_dates'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-calendar-alt text-success text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.filter_photos') }}</h4>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('backend.photos.index', $project) }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.date_from') }}
                    </label>
                    <input type="date"
                           id="date_from"
                           name="date_from"
                           value="{{ request('date_from') }}"
                           class="form-input w-full">
                </div>
                <div class="flex-1">
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.date_to') }}
                    </label>
                    <input type="date"
                           id="date_to"
                           name="date_to"
                           value="{{ request('date_to') }}"
                           class="form-input w-full">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="uil uil-filter me-2"></i>
                    {{ __('messages.filter') }}
                </button>
                @if(request()->hasAny(['date_from', 'date_to']))
                <a href="{{ route('backend.photos.index', $project) }}" class="btn btn-light">
                    <i class="uil uil-times me-2"></i>
                    {{ __('messages.clear') }}
                </a>
                @endif
            </form>
        </div>
    </div>

    @if($groupedPhotos->count() > 0)
        @foreach($groupedPhotos as $date => $photos)
        <div class="card" x-data="{
            lightboxOpen: false,
            currentIndex: 0,
            photos: {{ $photos->map(function($p) { return ['url' => asset('storage/' . $p->photo_path), 'description' => app()->getLocale() === 'ar' && $p->description_ar ? $p->description_ar : $p->description, 'uploader' => $p->uploader->name, 'date' => $p->photo_date]; })->toJson() }}
        }">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h4 class="card-title">
                        <i class="uil uil-calendar-alt me-2"></i>
                        {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}
                    </h4>
                    <span class="text-sm text-gray-600">{{ $photos->count() }} {{ __('messages.photos') }}</span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($photos as $index => $photo)
                    <div class="group relative aspect-square rounded-lg overflow-hidden cursor-pointer bg-gray-100"
                         @click="lightboxOpen = true; currentIndex = {{ $index }}">
                        <img src="{{ asset('storage/' . $photo->photo_path) }}"
                             alt="{{ __('messages.site_photo') }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300 flex items-center justify-center">
                            <i class="uil uil-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/70 to-transparent">
                            <p class="text-white text-xs truncate">
                                {{ app()->getLocale() === 'ar' && $photo->description_ar ? $photo->description_ar : ($photo->description ?? __('messages.site_photo')) }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Lightbox Modal -->
                <div x-show="lightboxOpen"
                     x-cloak
                     @keydown.escape.window="lightboxOpen = false"
                     @keydown.arrow-left.window="currentIndex = currentIndex > 0 ? currentIndex - 1 : photos.length - 1"
                     @keydown.arrow-right.window="currentIndex = currentIndex < photos.length - 1 ? currentIndex + 1 : 0"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
                     @click.self="lightboxOpen = false">

                    <!-- Close Button -->
                    <button @click="lightboxOpen = false"
                            class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                        <i class="uil uil-times text-3xl"></i>
                    </button>

                    <!-- Previous Button -->
                    <button @click="currentIndex = currentIndex > 0 ? currentIndex - 1 : photos.length - 1"
                            class="absolute left-4 text-white hover:text-gray-300 z-10">
                        <i class="uil uil-angle-left text-4xl"></i>
                    </button>

                    <!-- Next Button -->
                    <button @click="currentIndex = currentIndex < photos.length - 1 ? currentIndex + 1 : 0"
                            class="absolute right-4 text-white hover:text-gray-300 z-10">
                        <i class="uil uil-angle-right text-4xl"></i>
                    </button>

                    <!-- Image Container -->
                    <div class="max-w-6xl w-full flex flex-col items-center">
                        <img :src="photos[currentIndex].url"
                             :alt="photos[currentIndex].description || '{{ __('messages.site_photo') }}'"
                             class="max-h-[80vh] w-auto rounded-lg shadow-2xl">

                        <!-- Image Info -->
                        <div class="mt-4 bg-white/10 backdrop-blur-sm rounded-lg p-4 max-w-2xl w-full">
                            <p class="text-white text-center mb-2" x-text="photos[currentIndex].description || '{{ __('messages.site_photo') }}'"></p>
                            <div class="flex items-center justify-between text-sm text-gray-300">
                                <span x-text="'{{ __('messages.uploaded_by') }}: ' + photos[currentIndex].uploader"></span>
                                <span x-text="(currentIndex + 1) + ' / ' + photos.length"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
    <div class="card">
        <div class="p-12 text-center">
            <i class="uil uil-image text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">{{ __('messages.no_photos') }}</p>
            <a href="{{ route('backend.photos.create', $project) }}" class="btn btn-primary mt-4">
                <i class="uil uil-plus me-2"></i>
                {{ __('messages.upload') }} {{ __('messages.photos') }}
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
