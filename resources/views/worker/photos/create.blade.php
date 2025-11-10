@extends('backend.layouts.master')

@section('title', __('messages.upload_site_photos'))
@section('page-title', __('messages.upload_site_photos'))

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h4 class="card-title">{{ __('messages.project') }}: {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}</h4>
            <a href="{{ url()->previous() }}" class="btn btn-light">{{ __('messages.back') }}</a>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('worker.photos.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div>
                    <label class="form-label">{{ __('messages.photo_date') }}</label>
                    <input type="date" name="photo_date" value="{{ old('photo_date', now()->format('Y-m-d')) }}" class="form-input w-full text-lg @error('photo_date') border-danger @enderror" required>
                    @error('photo_date')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">{{ __('messages.description') }} (EN)</label>
                    <textarea name="description" rows="3" class="form-textarea w-full text-lg @error('description') border-danger @enderror">{{ old('description') }}</textarea>
                    @error('description')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">{{ __('messages.description') }} (AR)</label>
                    <textarea name="description_ar" rows="3" dir="rtl" class="form-textarea w-full text-lg @error('description_ar') border-danger @enderror">{{ old('description_ar') }}</textarea>
                    @error('description_ar')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">{{ __('messages.photos') }}</label>
                    <input type="file" name="photos[]" id="worker-photos" accept="image/*" multiple required class="form-input w-full text-lg @error('photos') border-danger @enderror">
                    @error('photos')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    @error('photos.*')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    <div id="photos-preview" class="grid grid-cols-3 gap-3 mt-4 hidden"></div>
                </div>

                <button type="submit" class="btn btn-primary w-full text-lg py-3">
                    <i class="uil uil-cloud-upload me-2"></i>{{ __('messages.upload') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('worker-photos');
        const preview = document.getElementById('photos-preview');

        if (!input || !preview) return;

        input.addEventListener('change', (event) => {
            const files = Array.from(event.target.files || []);
            preview.innerHTML = '';
            if (!files.length) {
                preview.classList.add('hidden');
                return;
            }

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-32 object-cover rounded-lg border';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });

            preview.classList.remove('hidden');
        });
    });
</script>
@endpush
