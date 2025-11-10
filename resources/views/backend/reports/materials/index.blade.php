@extends('backend.layouts.master')

@section('title', __('messages.material_deliveries') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.material_deliveries'))

@section('content')
<div class="flex flex-col gap-6" x-data="{ expandedItems: {} }">
    <!-- Project Header -->
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $project->contract_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('backend.materials.create', $project) }}" class="btn btn-primary">
                <i class="uil uil-plus me-2"></i>
                {{ __('messages.new') }} {{ __('messages.material_delivery') }}
            </a>
            <a href="{{ route('backend.projects.show', $project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-6">
        <!-- Total Deliveries -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_deliveries') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_deliveries'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-truck text-primary text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Items -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_items') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_items'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-package text-info text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Value Delivered -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_value_delivered') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_value_delivered'], 2) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-moneybag text-success text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Material Deliveries by BOQ Item -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.material_deliveries') }} {{ __('messages.by_boq_item') }}</h4>
        </div>
        <div class="overflow-x-auto">
            @if($groupedDeliveries->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase w-12"></th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.boq_item') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.total_quantity') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.delivered_quantity') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.remaining_quantity') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.delivery_progress') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($groupedDeliveries as $boqItemId => $data)
                    @php
                        $boqItem = $data['boq_item'];
                        $totalDelivered = $data['total_delivered'];
                        $remaining = $boqItem->total_quantity - $totalDelivered;
                        $percentage = $boqItem->total_quantity > 0 ? ($totalDelivered / $boqItem->total_quantity) * 100 : 0;
                    @endphp
                    <!-- Main Row -->
                    <tr class="hover:bg-gray-50 cursor-pointer" @click="expandedItems[{{ $boqItemId }}] = !expandedItems[{{ $boqItemId }}]">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <i class="uil text-lg transition-transform"
                               :class="expandedItems[{{ $boqItemId }}] ? 'uil-angle-down' : 'uil-angle-right'"></i>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $boqItem->item_code }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ app()->getLocale() === 'ar' ? $boqItem->item_name_ar : $boqItem->item_name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">
                                {{ number_format($boqItem->total_quantity, 2) }}
                                {{ app()->getLocale() === 'ar' ? $boqItem->unit_ar : $boqItem->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-success">
                                {{ number_format($totalDelivered, 2) }}
                                {{ app()->getLocale() === 'ar' ? $boqItem->unit_ar : $boqItem->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm {{ $remaining < 0 ? 'text-danger font-semibold' : 'text-gray-600' }}">
                                {{ number_format($remaining, 2) }}
                                {{ app()->getLocale() === 'ar' ? $boqItem->unit_ar : $boqItem->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-[120px]">
                                    <div class="text-sm font-semibold {{ $percentage > 100 ? 'text-danger' : ($percentage === 100 ? 'text-success' : 'text-warning') }}">
                                        {{ number_format($percentage, 1) }}%
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="h-2 rounded-full {{ $percentage > 100 ? 'bg-danger' : ($percentage === 100 ? 'bg-success' : 'bg-warning') }}"
                                             style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Expanded Details Row -->
                    <tr x-show="expandedItems[{{ $boqItemId }}]" x-cloak>
                        <td colspan="6" class="px-6 py-4 bg-gray-50">
                            <div class="text-sm font-semibold text-gray-700 mb-3">
                                {{ __('messages.delivery_history') }} ({{ $data['deliveries']->count() }} {{ __('messages.deliveries') }})
                            </div>
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-start text-xs font-medium text-gray-600">{{ __('messages.delivery_date') }}</th>
                                        <th class="px-4 py-2 text-start text-xs font-medium text-gray-600">{{ __('messages.quantity') }}</th>
                                        <th class="px-4 py-2 text-start text-xs font-medium text-gray-600">{{ __('messages.supplier') }}</th>
                                        <th class="px-4 py-2 text-start text-xs font-medium text-gray-600">{{ __('messages.received_by') }}</th>
                                        <th class="px-4 py-2 text-start text-xs font-medium text-gray-600">{{ __('messages.notes') }}</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-600">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($data['deliveries'] as $delivery)
                                    <tr class="hover:bg-gray-100">
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ \Carbon\Carbon::parse($delivery->delivery_date)->format('Y-m-d') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm font-semibold">
                                            {{ number_format($delivery->quantity_delivered, 2) }}
                                            {{ app()->getLocale() === 'ar' ? $boqItem->unit_ar : $boqItem->unit }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">{{ $delivery->supplier_name }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $delivery->received_by }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            @if(app()->getLocale() === 'ar' && $delivery->notes_ar)
                                                {{ Str::limit($delivery->notes_ar, 50) }}
                                            @else
                                                {{ Str::limit($delivery->notes, 50) }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('backend.materials.edit', $delivery) }}"
                                                   class="text-warning hover:text-warning/80"
                                                   title="{{ __('messages.edit') }}">
                                                    <i class="uil uil-edit text-lg"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('backend.materials.destroy', $delivery) }}"
                                                      onsubmit="return confirm('{{ __('messages.confirm_delete') }}');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-danger hover:text-danger/80"
                                                            title="{{ __('messages.delete') }}">
                                                        <i class="uil uil-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-12 text-center">
                <i class="uil uil-truck text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_data') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
