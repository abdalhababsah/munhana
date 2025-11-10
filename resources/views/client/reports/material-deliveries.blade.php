@extends('backend.layouts.master')

@section('title', __('messages.material_deliveries'))
@section('page-title', __('messages.material_deliveries'))

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
                <p class="text-sm text-gray-500 mt-1">{{ $project->contract_number }}</p>
            </div>
            <a href="{{ route('client.projects.show', $project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <!-- Summary -->
    <div class="grid md:grid-cols-3 gap-6">
        <div class="card">
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_deliveries') }}</p>
                <h3 class="text-3xl font-bold text-blue-600">{{ $summary['total_deliveries'] }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_quantity') }}</p>
                <h3 class="text-3xl font-bold text-emerald-600">{{ number_format($summary['total_quantity'], 2) }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-2">{{ __('messages.unique_suppliers') }}</p>
                <h3 class="text-3xl font-bold text-purple-600">{{ $summary['unique_suppliers'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Deliveries -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.delivery_history') }}</h4>
        </div>

        @if($deliveries->isEmpty())
            <div class="p-12 text-center">
                <i class="uil uil-truck text-6xl text-gray-300 mb-4"></i>
                <h5 class="text-lg font-semibold text-gray-900">{{ __('messages.no_deliveries_yet') }}</h5>
            </div>
        @else
            <div class="p-6 space-y-6">
                @foreach($groupedDeliveries as $boqItemId => $items)
                    @php
                        $boqItem = $items->first()->boqItem;
                        $boqName = $boqItem
                            ? (app()->getLocale() === 'ar' && $boqItem->item_name_ar ? $boqItem->item_name_ar : $boqItem->item_name)
                            : __('messages.boq_item');
                        $totalDelivered = $items->sum('quantity');
                    @endphp
                    <div class="border rounded-lg">
                        <div class="px-6 py-4 flex flex-wrap items-center justify-between gap-3 bg-gray-50 rounded-t-lg">
                            <div>
                                <p class="text-sm text-gray-500">{{ __('messages.boq_item') }}</p>
                                <h5 class="text-lg font-semibold text-gray-900">{{ $boqName }}</h5>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ __('messages.quantity_delivered') }}</p>
                                <p class="text-lg font-semibold text-emerald-600">{{ number_format($totalDelivered, 2) }}</p>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                            {{ __('messages.delivery_date') }}
                                        </th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                            {{ __('messages.quantity') }}
                                        </th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                            {{ __('messages.supplier_name') }}
                                        </th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                            {{ __('messages.received_by') }}
                                        </th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                            {{ __('messages.notes') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($items as $delivery)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $delivery->delivery_date?->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ number_format($delivery->quantity, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $delivery->supplier_name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $delivery->received_by ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            @php
                                                $note = app()->getLocale() === 'ar' && $delivery->notes_ar
                                                    ? $delivery->notes_ar
                                                    : $delivery->notes;
                                            @endphp
                                            {{ $note ?? '—' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
