@extends('backend.layouts.master')

@section('title', __('messages.edit') . ' ' . __('messages.boq_item'))
@section('page-title', __('messages.edit') . ' ' . __('messages.boq_item'))

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

    <!-- Edit BOQ Item Form -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.edit') }} {{ __('messages.boq_item') }}: {{ $boq->item_code }}</h4>
        </div>
        <div class="p-6">
            <!-- Completion Status Alert -->
            <div class="mb-6 p-4 rounded-md {{ $boq->completion_percentage < 50 ? 'bg-danger/10 text-danger' : ($boq->completion_percentage < 80 ? 'bg-warning/10 text-warning' : 'bg-success/10 text-success') }}">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <i class="uil uil-chart-pie text-2xl me-3"></i>
                        <div>
                            <p class="font-semibold">{{ __('messages.completion_status') }}</p>
                            <p class="text-sm">{{ __('messages.executed') }}: {{ number_format($boq->executed_quantity, 2) }} / {{ number_format($boq->total_quantity, 2) }}</p>
                        </div>
                    </div>
                    <span class="text-3xl font-bold">{{ number_format($boq->completion_percentage, 1) }}%</span>
                </div>
                <div class="w-full bg-white/30 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $boq->completion_percentage < 50 ? 'bg-danger' : ($boq->completion_percentage < 80 ? 'bg-warning' : 'bg-success') }}"
                         style="width: {{ min($boq->completion_percentage, 100) }}%"></div>
                </div>
            </div>

            <form method="POST" action="{{ route('backend.boq.update', $boq) }}" x-data="{
                totalQuantity: {{ old('total_quantity', $boq->total_quantity) }},
                unitPrice: {{ old('unit_price', $boq->unit_price) }},
                get totalPrice() {
                    return (this.totalQuantity * this.unitPrice).toFixed(2);
                }
            }">
                @csrf
                @method('PUT')

                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- Item Code -->
                    <div>
                        <label for="item_code" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.item_code') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="item_code"
                               name="item_code"
                               value="{{ old('item_code', $boq->item_code) }}"
                               class="form-input w-full @error('item_code') border-danger @enderror"
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
                               value="{{ old('approved_supplier', $boq->approved_supplier) }}"
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
                               value="{{ old('item_name', $boq->item_name) }}"
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
                               value="{{ old('item_name_ar', $boq->item_name_ar) }}"
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
                               value="{{ old('unit', $boq->unit) }}"
                               class="form-input w-full @error('unit') border-danger @enderror"
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
                               value="{{ old('unit_ar', $boq->unit_ar) }}"
                               class="form-input w-full @error('unit_ar') border-danger @enderror"
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
                               value="{{ old('total_quantity', $boq->total_quantity) }}"
                               step="0.01"
                               min="0"
                               class="form-input w-full @error('total_quantity') border-danger @enderror"
                               required>
                        @error('total_quantity')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Executed Quantity (Read-only) -->
                    <div>
                        <label for="executed_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.executed_quantity') }}
                        </label>
                        <input type="number"
                               id="executed_quantity"
                               value="{{ $boq->executed_quantity }}"
                               step="0.01"
                               class="form-input w-full bg-gray-100"
                               readonly
                               disabled>
                        <p class="mt-1 text-xs text-gray-500">{{ __('messages.updated_from_daily_reports') }}</p>
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
                               value="{{ old('unit_price', $boq->unit_price) }}"
                               step="0.01"
                               min="0"
                               class="form-input w-full @error('unit_price') border-danger @enderror"
                               required>
                        @error('unit_price')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Price (Auto-calculated) -->
                    <div>
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
                                  class="form-textarea w-full @error('specifications') border-danger @enderror">{{ old('specifications', $boq->specifications) }}</textarea>
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
                                  class="form-textarea w-full @error('specifications_ar') border-danger @enderror">{{ old('specifications_ar', $boq->specifications_ar) }}</textarea>
                        @error('specifications_ar')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4 mt-6 pt-6 border-t">
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-check me-2"></i>
                        {{ __('messages.update') }}
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
