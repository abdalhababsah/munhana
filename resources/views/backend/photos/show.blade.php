@extends('backend.layouts.master')

@section('title', __('messages.site_photo') . ' - ' . (app()->getLocale() === 'ar' ? $photo->project->name_ar : $photo->project->name))
@section('page-title', __('messages.site_photo'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $photo->project->name_ar : $photo->project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $photo->project->contract_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('backend.photos.destroy', $photo) }}"
                  onsubmit="return confirm('{{ __('messages.confirm_delete') }}');"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="uil uil-trash me-2"></i>
                    {{ __('messages.delete') }}
                </button>
            </form>
            <a href="{{ route('backend.photos.index', $photo->project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Photo Display -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="p-6">
                    <div class="rounded-lg overflow-hidden bg-gray-100">
                        <img src="{{ asset('storage/' . $photo->photo_path) }}"
                             alt="{{ __('messages.site_photo') }}"
                             class="w-full h-auto">
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <a href="{{ asset('storage/' . $photo->photo_path) }}"
                           download
                           class="btn btn-sm btn-primary">
                            <i class="uil uil-download-alt me-2"></i>
                            {{ __('messages.download') }}
                        </a>
                        <a href="{{ asset('storage/' . $photo->photo_path) }}"
                           target="_blank"
                           class="btn btn-sm btn-light">
                            <i class="uil uil-external-link-alt me-2"></i>
                            {{ __('messages.open_full_size') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Details -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.photo_details') }}</h4>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Photo Date -->
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('messages.photo_date') }}</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($photo->photo_date)->format('l, F d, Y') }}
                            </p>
                        </div>

                        <!-- Description (English) -->
                        @if($photo->description)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('messages.description') }} ({{ __('messages.english') }})</p>
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $photo->description }}</p>
                        </div>
                        @endif

                        <!-- Description (Arabic) -->
                        @if($photo->description_ar)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('messages.description') }} ({{ __('messages.arabic') }})</p>
                            <p class="text-sm text-gray-700 whitespace-pre-line" dir="rtl">{{ $photo->description_ar }}</p>
                        </div>
                        @endif

                        <!-- Uploaded By -->
                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-1">{{ __('messages.uploaded_by') }}</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $photo->uploader->name }}</p>
                        </div>

                        <!-- Upload Date -->
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('messages.uploaded_at') }}</p>
                            <p class="text-sm text-gray-900">{{ $photo->created_at->format('Y-m-d H:i') }}</p>
                        </div>

                        <!-- File Info -->
                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">{{ __('messages.file_info') }}</p>
                            <div class="bg-gray-50 rounded-lg p-3 space-y-1">
                                <p class="text-xs text-gray-600">
                                    <span class="font-medium">{{ __('messages.path') }}:</span>
                                    {{ $photo->photo_path }}
                                </p>
                                @if(file_exists(storage_path('app/public/' . $photo->photo_path)))
                                <p class="text-xs text-gray-600">
                                    <span class="font-medium">{{ __('messages.size') }}:</span>
                                    {{ number_format(filesize(storage_path('app/public/' . $photo->photo_path)) / 1024 / 1024, 2) }} MB
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
