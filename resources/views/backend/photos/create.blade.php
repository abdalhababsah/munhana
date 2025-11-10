@extends('backend.layouts.master')

@section('title', __('messages.upload') . ' ' . __('messages.photos') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.upload') . ' ' . __('messages.photos'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $project->contract_number }}</p>
        </div>
        <a href="{{ route('backend.photos.index', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <div class="card" x-data="{
        photos: [],
        previewUrls: [],
        handleFiles(event) {
            const files = Array.from(event.target.files);
            this.photos = files;
            this.previewUrls = [];

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewUrls.push({
                        url: e.target.result,
                        name: file.name,
                        size: (file.size / 1024 / 1024).toFixed(2)
                    });
                };
                reader.readAsDataURL(file);
            });
        },
        removePhoto(index) {
            this.photos.splice(index, 1);
            this.previewUrls.splice(index, 1);

            // Update file input
            const dt = new DataTransfer();
            this.photos.forEach(file => dt.items.add(file));
            document.getElementById('photos').files = dt.files;
        }
    }">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.upload_site_photos') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.photos.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <!-- Photo Date -->
                <div class="mb-6">
                    <label for="photo_date" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.photo_date') }} <span class="text-danger">*</span>
                    </label>
                    <input type="date"
                           id="photo_date"
                           name="photo_date"
                           value="{{ old('photo_date', date('Y-m-d')) }}"
                           class="form-input w-full @error('photo_date') border-danger @enderror"
                           required>
                    @error('photo_date')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">{{ __('messages.photo_date_help') }}</p>
                </div>

                <!-- File Upload Area -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.select_photos') }} <span class="text-danger">*</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition-colors">
                        <input type="file"
                               id="photos"
                               name="photos[]"
                               multiple
                               accept="image/jpeg,image/png,image/jpg,image/gif"
                               @change="handleFiles($event)"
                               class="hidden"
                               required>
                        <label for="photos" class="cursor-pointer">
                            <i class="uil uil-cloud-upload text-5xl text-gray-400 mb-4"></i>
                            <p class="text-base text-gray-700 font-medium mb-2">{{ __('messages.click_to_upload') }}</p>
                            <p class="text-sm text-gray-500">{{ __('messages.upload_photos_help') }}</p>
                        </label>
                    </div>
                    @error('photos')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('photos.*')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo Previews -->
                <div x-show="previewUrls.length > 0" x-cloak class="mb-6">
                    <h5 class="text-sm font-semibold text-gray-700 mb-3">
                        {{ __('messages.selected_photos') }} (<span x-text="previewUrls.length"></span>)
                    </h5>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <template x-for="(preview, index) in previewUrls" :key="index">
                            <div class="relative group">
                                <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                                    <img :src="preview.url"
                                         :alt="preview.name"
                                         class="w-full h-full object-cover">
                                </div>
                                <button type="button"
                                        @click="removePhoto(index)"
                                        class="absolute top-2 right-2 bg-danger text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="uil uil-times"></i>
                                </button>
                                <div class="mt-1">
                                    <p class="text-xs text-gray-600 truncate" x-text="preview.name"></p>
                                    <p class="text-xs text-gray-500" x-text="preview.size + ' MB'"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Description (English) -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.description') }} ({{ __('messages.english') }})
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="3"
                              class="form-input w-full @error('description') border-danger @enderror"
                              placeholder="{{ __('messages.photo_description_placeholder') }}">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">{{ __('messages.photo_description_help') }}</p>
                </div>

                <!-- Description (Arabic) -->
                <div class="mb-6">
                    <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.description') }} ({{ __('messages.arabic') }})
                    </label>
                    <textarea id="description_ar"
                              name="description_ar"
                              rows="3"
                              dir="rtl"
                              class="form-input w-full @error('description_ar') border-danger @enderror"
                              placeholder="{{ __('messages.photo_description_placeholder_ar') }}">{{ old('description_ar') }}</textarea>
                    @error('description_ar')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Info -->
                <div class="bg-info/10 border border-info/20 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="uil uil-info-circle text-info text-xl mt-0.5"></i>
                        <div class="flex-1">
                            <p class="text-sm text-gray-700 font-medium mb-2">{{ __('messages.upload_requirements') }}:</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• {{ __('messages.max_file_size') }}: 5MB {{ __('messages.per_photo') }}</li>
                                <li>• {{ __('messages.supported_formats') }}: JPEG, PNG, JPG, GIF</li>
                                <li>• {{ __('messages.can_upload_multiple') }}</li>
                                <li>• {{ __('messages.description_shared') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('backend.photos.index', $project) }}" class="btn btn-light">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary" :disabled="previewUrls.length === 0">
                        <i class="uil uil-upload me-2"></i>
                        {{ __('messages.upload') }} {{ __('messages.photos') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
