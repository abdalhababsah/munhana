@extends('backend.layouts.master')

@section('title', __('messages.add_material_delivery'))
@section('page-title', __('messages.add_material_delivery'))

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h4 class="card-title">{{ __('messages.project') }}:
                    {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}</h4>
                <a href="{{ url()->previous() }}" class="btn btn-light">{{ __('messages.back') }}</a>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('worker.materials.store') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div>
                        <label class="form-label">{{ __('messages.select_boq_item') }}</label>
                        <select name="boq_item_id" class="form-select w-full @error('boq_item_id') border-danger @enderror"
                            required>
                            <option value="">{{ __('messages.select_boq_item') }}</option>
                            @foreach($project->boqItems as $item)
                                <option value="{{ $item->id }}" @selected(old('boq_item_id') == $item->id)>
                                    {{ app()->getLocale() === 'ar' ? $item->item_name_ar : $item->item_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('boq_item_id')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                        @if($project->boqItems->isEmpty())
                            <p class="text-sm text-gray-500 mt-1">{{ __('messages.no_boq_items') }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="form-label">{{ __('messages.delivery_date') }}</label>
                        <input type="date" name="delivery_date" value="{{ old('delivery_date', now()->format('Y-m-d')) }}"
                            class="form-input w-full @error('delivery_date') border-danger @enderror" required>
                        @error('delivery_date')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">{{ __('messages.quantity') }}</label>
                        <input type="number" step="0.01" name="quantity" value="{{ old('quantity') }}"
                            class="form-input w-full @error('quantity') border-danger @enderror" min="0" required>
                        @error('quantity')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">{{ __('messages.supplier_name') }}</label>
                        <input type="text" name="supplier_name" value="{{ old('supplier_name') }}"
                            class="form-input w-full @error('supplier_name') border-danger @enderror">
                        @error('supplier_name')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">{{ __('messages.received_by') }}</label>
                        <input type="text" name="received_by" value="{{ old('received_by', auth()->user()->name) }}"
                            class="form-input w-full @error('received_by') border-danger @enderror">
                        @error('received_by')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">{{ __('messages.notes') }} (EN)</label>
                        <textarea name="notes" rows="4"
                            class="form-textarea w-full @error('notes') border-danger @enderror">{{ old('notes') }}</textarea>
                        @error('notes')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">{{ __('messages.notes') }} (AR)</label>
                        <textarea name="notes_ar" rows="4" dir="rtl"
                            class="form-textarea w-full @error('notes_ar') border-danger @enderror">{{ old('notes_ar') }}</textarea>
                        @error('notes_ar')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-full py-2">
                        <i class="uil uil-check me-2"></i>{{ __('messages.submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection