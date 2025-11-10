@extends('backend.layouts.master')

@section('title', __('messages.site_photo'))
@section('page-title', __('messages.site_photo'))

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
                <p class="text-sm text-gray-500 mt-1">{{ __('messages.photo_date') }}: {{ $photo->photo_date?->format('Y-m-d') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('client.photos.index', $project) }}" class="btn btn-light">
                    <i class="uil uil-arrow-left me-2"></i>
                    {{ __('messages.back') }}
                </a>
                <a href="{{ asset('storage/' . $photo->photo_path) }}" download class="btn btn-primary">
                    <i class="uil uil-import me-2"></i>
                    {{ __('messages.download') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Photo -->
    <div class="card">
        <div class="p-6">
            <div class="rounded-lg overflow-hidden shadow">
                <img src="{{ asset('storage/' . $photo->photo_path) }}"
                     alt="{{ $photo->description }}"
                     class="w-full max-h-[600px] object-contain bg-black">
            </div>
            <div class="mt-6 grid md:grid-cols-2 gap-6">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700 mb-2">{{ __('messages.description') }} (English)</h5>
                    <p class="text-gray-700 whitespace-pre-line">{{ $photo->description ?? __('messages.no_data') }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700 mb-2">{{ __('messages.description') }} (العربية)</h5>
                    <p class="text-gray-700 whitespace-pre-line" dir="rtl">{{ $photo->description_ar ?? __('messages.no_data') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
