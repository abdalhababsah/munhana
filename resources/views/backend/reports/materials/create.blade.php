@extends('backend.layouts.master')

@section('title', __('messages.new') . ' ' . __('messages.material_delivery'))
@section('page-title', __('messages.new') . ' ' . __('messages.material_delivery'))

@section('content')
<div class="flex flex-col gap-6" x-data="{ selectedBoqItem: null, boqItems: {{ $boqItems->toJson() }} }">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">
            {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
        </h4>
        <a href="{{ route('backend.materials.index', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <!-- Create Material Delivery Form -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.new') }} {{ __('messages.material_delivery') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.materials.store') }}">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- BOQ Item -->
                    <div class="lg:col-span-2">
                        <label for="boq_item_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.boq_item') }} <span class="text-danger">*</span>
                        </label>
                        <select id="boq_item_id"
                                name="boq_item_id"
                                class="form-select w-full @error('boq_item_id') border-danger @enderror"
                                x-model="selectedBoqItem"
                                @change="selectedBoqItem = boqItems.find(item => item.id == $event.target.value)"
                                required>
                            <option value="">{{ __('messages.select_boq_item') }}</option>
                            @foreach($boqItems as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->item_code }} - {{ app()->getLocale() === 'ar' ? $item->item_name_ar : $item->item_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('boq_item_id')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- BOQ Item Details Card -->
                    <div class="lg:col-span-2" x-show="selectedBoqItem" x-cloak>
                        <div class="p-4 bg-info/10 rounded-md">
                            <h5 class="text-sm font-semibold text-gray-900 mb-3">{{ __('messages.boq_item_details') }}</h5>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('messages.total_quantity') }}</p>
                                    <p class="text-sm font-semibold text-gray-900" x-text="selectedBoqItem ? selectedBoqItem.total_quantity.toFixed(2) + ' ' + (selectedBoqItem['unit{{ app()->getLocale() === 'ar' ? '_ar' : '' }}']) : '-'"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('messages.already_delivered') }}</p>
                                    <p class="text-sm font-semibold text-success" x-text="selectedBoqItem ? selectedBoqItem.executed_quantity.toFixed(2) + ' ' + (selectedBoqItem['unit{{ app()->getLocale() === 'ar' ? '_ar' : '' }}']) : '-'"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('messages.remaining_quantity') }}</p>
                                    <p class="text-sm font-semibold"
                                       :class="selectedBoqItem && (selectedBoqItem.total_quantity - selectedBoqItem.executed_quantity) < 0 ? 'text-danger' : 'text-gray-900'"
                                       x-text="selectedBoqItem ? (selectedBoqItem.total_quantity - selectedBoqItem.executed_quantity).toFixed(2) + ' ' + (selectedBoqItem['unit{{ app()->getLocale() === 'ar' ? '_ar' : '' }}']) : '-'"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Date -->
                    <div>
                        <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.delivery_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               id="delivery_date"
                               name="delivery_date"
                               value="{{ old('delivery_date', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                               class="form-input w-full @error('delivery_date') border-danger @enderror"
                               required>
                        @error('delivery_date')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity Delivered -->
                    <div>
                        <label for="quantity_delivered" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.quantity_delivered') }} <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               id="quantity_delivered"
                               name="quantity_delivered"
                               value="{{ old('quantity_delivered') }}"
                               min="0"
                               step="0.01"
                               class="form-input w-full @error('quantity_delivered') border-danger @enderror"
                               required>
                        @error('quantity_delivered')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Supplier Name -->
                    <div>
                        <label for="supplier_name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.supplier_name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="supplier_name"
                               name="supplier_name"
                               value="{{ old('supplier_name') }}"
                               class="form-input w-full @error('supplier_name') border-danger @enderror"
                               required>
                        @error('supplier_name')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Received By -->
                    <div>
                        <label for="received_by" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.received_by') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="received_by"
                               name="received_by"
                               value="{{ old('received_by') }}"
                               class="form-input w-full @error('received_by') border-danger @enderror"
                               required>
                        @error('received_by')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes (English) -->
                    <div class="lg:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.notes') }} (English)
                        </label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="3"
                                  class="form-textarea w-full @error('notes') border-danger @enderror"
                                  placeholder="{{ __('messages.delivery_notes_placeholder') }}">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes (Arabic) -->
                    <div class="lg:col-span-2">
                        <label for="notes_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.notes') }} (العربية)
                        </label>
                        <textarea id="notes_ar"
                                  name="notes_ar"
                                  rows="3"
                                  dir="rtl"
                                  class="form-textarea w-full @error('notes_ar') border-danger @enderror"
                                  placeholder="{{ __('messages.delivery_notes_placeholder') }}">{{ old('notes_ar') }}</textarea>
                        @error('notes_ar')
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
                    <a href="{{ route('backend.materials.index', $project) }}" class="btn btn-light">
                        {{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
