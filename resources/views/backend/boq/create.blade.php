@extends('backend.layouts.master')

@section('title', __('messages.new') . ' ' . __('messages.boq_item'))
@section('page-title', __('messages.new') . ' ' . __('messages.boq_item'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">
            {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
        </h4>
        <a href="{{ route('backend.boq.index', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <!-- Create BOQ Item Form -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.new') }} {{ __('messages.boq_item') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.boq.store') }}" x-data="{
                totalQuantity: {{ old('total_quantity', 0) }},
                unitPrice: {{ old('unit_price', 0) }},
                get totalPrice() {
                    return (this.totalQuantity * this.unitPrice).toFixed(2);
                }
            }">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- Item Code -->
                    <div>
                        <label for="item_code" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.item_code') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="item_code"
                               name="item_code"
                               value="{{ old('item_code') }}"
                               class="form-input w-full @error('item_code') border-danger @enderror"
                               placeholder="BOQ-001"
                               required>
                        @error('item_code')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Approved Supplier -->
                    <div>
                        <label for="approved_supplier" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.approved_supplier') }}
                        </label>
                        <input type="text"
                               id="approved_supplier"
                               name="approved_supplier"
                               value="{{ old('approved_supplier') }}"
                               class="form-input w-full @error('approved_supplier') border-danger @enderror">
                        @error('approved_supplier')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Item Name (English) -->
                    <div>
                        <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.item_name') }} (English) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="item_name"
                               name="item_name"
                               value="{{ old('item_name') }}"
                               class="form-input w-full @error('item_name') border-danger @enderror"
                               required>
                        @error('item_name')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Item Name (Arabic) -->
                    <div>
                        <label for="item_name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.item_name') }} (العربية) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="item_name_ar"
                               name="item_name_ar"
                               value="{{ old('item_name_ar') }}"
                               class="form-input w-full @error('item_name_ar') border-danger @enderror"
                               dir="rtl"
                               required>
                        @error('item_name_ar')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit (English) -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.unit') }} (English) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="unit"
                               name="unit"
                               value="{{ old('unit') }}"
                               class="form-input w-full @error('unit') border-danger @enderror"
                               placeholder="m², m³, kg, ton"
                               required>
                        @error('unit')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit (Arabic) -->
                    <div>
                        <label for="unit_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.unit') }} (العربية) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="unit_ar"
                               name="unit_ar"
                               value="{{ old('unit_ar') }}"
                               class="form-input w-full @error('unit_ar') border-danger @enderror"
                               placeholder="متر مربع، متر مكعب، كيلوجرام، طن"
                               dir="rtl"
                               required>
                        @error('unit_ar')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Quantity -->
                    <div>
                        <label for="total_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.total_quantity') }} <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               id="total_quantity"
                               name="total_quantity"
                               x-model.number="totalQuantity"
                               value="{{ old('total_quantity') }}"
                               step="0.01"
                               min="0"
                               class="form-input w-full @error('total_quantity') border-danger @enderror"
                               required>
                        @error('total_quantity')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Price -->
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.unit_price') }} <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               id="unit_price"
                               name="unit_price"
                               x-model.number="unitPrice"
                               value="{{ old('unit_price') }}"
                               step="0.01"
                               min="0"
                               class="form-input w-full @error('unit_price') border-danger @enderror"
                               required>
                        @error('unit_price')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Price (Auto-calculated) -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.total_price') }}
                        </label>
                        <div class="p-4 bg-gray-50 rounded-md border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ __('messages.calculated_total') }}:</span>
                                <span class="text-2xl font-bold text-primary" x-text="totalPrice"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Specifications (English) -->
                    <div class="lg:col-span-2">
                        <label for="specifications" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.technical_specifications') }} (English)
                        </label>
                        <textarea id="specifications"
                                  name="specifications"
                                  rows="4"
                                  class="form-textarea w-full @error('specifications') border-danger @enderror">{{ old('specifications') }}</textarea>
                        @error('specifications')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Technical Specifications (Arabic) -->
                    <div class="lg:col-span-2">
                        <label for="specifications_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.technical_specifications') }} (العربية)
                        </label>
                        <textarea id="specifications_ar"
                                  name="specifications_ar"
                                  rows="4"
                                  dir="rtl"
                                  class="form-textarea w-full @error('specifications_ar') border-danger @enderror">{{ old('specifications_ar') }}</textarea>
                        @error('specifications_ar')
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
                    <a href="{{ route('backend.boq.index', $project) }}" class="btn btn-light">
                        {{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
