@extends('backend.layouts.master')

@section('title', __('messages.boq_items'))
@section('page-title', __('messages.boq_items'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">
            {{ __('messages.boq_items') }}
        </h4>
    </div>

    <!-- Summary Cards -->
    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
        <!-- Total Items -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_items') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $stats['total_items'] }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-list-ul text-primary text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Value -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_value') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ number_format($stats['total_value'], 2) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-moneybag text-success text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Quantity -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_quantity') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ number_format($stats['total_quantity'], 2) }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ __('messages.executed') }}: {{ number_format($stats['total_executed_quantity'], 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-package text-info text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Completion -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.overall_completion') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ number_format($stats['overall_completion'], 1) }}%
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-chart-pie text-warning text-2xl"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $stats['overall_completion'] < 50 ? 'bg-danger' : ($stats['overall_completion'] < 80 ? 'bg-warning' : 'bg-success') }}"
                             style="width: {{ min($stats['overall_completion'], 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOQ Items Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.boq_items') }}</h4>
        </div>
        <div class="overflow-x-auto">
            @if($boqItems->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.project') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.item_code') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.item_name') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.quantity') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.unit_price') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.total_price') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.completion') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($boqItems as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('backend.projects.show', $item->project) }}" class="text-sm text-primary hover:underline">
                                {{ app()->getLocale() === 'ar' ? $item->project->name_ar : $item->project->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $item->item_code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ app()->getLocale() === 'ar' ? $item->item_name_ar : $item->item_name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ number_format($item->total_quantity, 2) }}
                                {{ app()->getLocale() === 'ar' ? $item->unit_ar : $item->unit }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ __('messages.executed') }}: {{ number_format($item->executed_quantity, 2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ number_format($item->unit_price, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($item->total_price, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $completion = $item->total_quantity > 0 ? ($item->executed_quantity / $item->total_quantity) * 100 : 0;
                            @endphp
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <div class="text-sm font-semibold {{ $completion < 50 ? 'text-danger' : ($completion < 80 ? 'text-warning' : 'text-success') }}">
                                        {{ number_format($completion, 1) }}%
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                        <div class="h-1.5 rounded-full {{ $completion < 50 ? 'bg-danger' : ($completion < 80 ? 'bg-warning' : 'bg-success') }}"
                                             style="width: {{ min($completion, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('backend.boq.edit', $item) }}"
                                   class="text-warning hover:text-warning/80"
                                   title="{{ __('messages.edit') }}">
                                    <i class="uil uil-edit text-lg"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="p-6">
                {{ $boqItems->links() }}
            </div>
            @else
            <div class="p-12 text-center">
                <i class="uil uil-list-ul text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_data') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
