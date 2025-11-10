@extends('backend.layouts.master')

@section('title', __('messages.new_project'))
@section('page-title', __('messages.new_project'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('backend.projects.index') }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <!-- Create Project Form -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.new_project') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.projects.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- Project Name (English) -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.project_name') }} (English) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-input w-full @error('name') border-danger @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Name (Arabic) -->
                    <div>
                        <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.project_name') }} (العربية) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="name_ar"
                               name="name_ar"
                               value="{{ old('name_ar') }}"
                               class="form-input w-full @error('name_ar') border-danger @enderror"
                               dir="rtl"
                               required>
                        @error('name_ar')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Client -->
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.client') }} <span class="text-danger">*</span>
                        </label>
                        <select id="client_id"
                                name="client_id"
                                class="form-select w-full @error('client_id') border-danger @enderror"
                                required>
                            <option value="">{{ __('messages.client') }}</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} - {{ $client->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract Number -->
                    <div>
                        <label for="contract_number" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.contract_number') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="contract_number"
                               name="contract_number"
                               value="{{ old('contract_number') }}"
                               class="form-input w-full @error('contract_number') border-danger @enderror"
                               required>
                        @error('contract_number')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.start_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               id="start_date"
                               name="start_date"
                               value="{{ old('start_date') }}"
                               class="form-input w-full @error('start_date') border-danger @enderror"
                               required>
                        @error('start_date')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.end_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               id="end_date"
                               name="end_date"
                               value="{{ old('end_date') }}"
                               class="form-input w-full @error('end_date') border-danger @enderror"
                               required>
                        @error('end_date')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Budget -->
                    <div>
                        <label for="total_budget" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.total_budget') }} <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               id="total_budget"
                               name="total_budget"
                               value="{{ old('total_budget') }}"
                               step="0.01"
                               min="0"
                               class="form-input w-full @error('total_budget') border-danger @enderror"
                               required>
                        @error('total_budget')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.status') }} <span class="text-danger">*</span>
                        </label>
                        <select id="status"
                                name="status"
                                class="form-select w-full @error('status') border-danger @enderror"
                                required>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                {{ __('messages.active') }}
                            </option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>
                                {{ __('messages.completed') }}
                            </option>
                            <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>
                                {{ __('messages.archived') }}
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location (English) -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.location') }} (English) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="location"
                               name="location"
                               value="{{ old('location') }}"
                               class="form-input w-full @error('location') border-danger @enderror"
                               required>
                        @error('location')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location (Arabic) -->
                    <div>
                        <label for="location_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.location') }} (العربية) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="location_ar"
                               name="location_ar"
                               value="{{ old('location_ar') }}"
                               class="form-input w-full @error('location_ar') border-danger @enderror"
                               dir="rtl"
                               required>
                        @error('location_ar')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract File -->
                    <div class="lg:col-span-2">
                        <label for="contract_file" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.contract_file') }} (PDF)
                        </label>
                        <input type="file"
                               id="contract_file"
                               name="contract_file"
                               accept=".pdf"
                               class="form-input w-full @error('contract_file') border-danger @enderror">
                        <p class="mt-1 text-sm text-gray-500">{{ __('Maximum file size: 10MB') }}</p>
                        @error('contract_file')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description (English) -->
                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.description') }} (English)
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  class="form-textarea w-full @error('description') border-danger @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description (Arabic) -->
                    <div class="lg:col-span-2">
                        <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.description') }} (العربية)
                        </label>
                        <textarea id="description_ar"
                                  name="description_ar"
                                  rows="4"
                                  dir="rtl"
                                  class="form-textarea w-full @error('description_ar') border-danger @enderror">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4 mt-6 pt-6 border-t">
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-check me-2"></i>
                        {{ __('messages.create') }}
                    </button>
                    <a href="{{ route('backend.projects.index') }}" class="btn btn-light">
                        {{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
