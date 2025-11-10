@extends('backend.layouts.master')

@section('title', __('messages.boq') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.boq'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Project Header -->
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $project->contract_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('backend.boq.create', $project) }}" class="btn btn-primary">
                <i class="uil uil-plus me-2"></i>
                {{ __('messages.new') }} {{ __('messages.boq_item') }}
            </a>
            <a href="{{ route('backend.projects.show', $project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
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
        <div class="p-6">
            @if(session('success'))
            <div class="mb-4 p-4 bg-success/10 text-success rounded-md">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 p-4 bg-danger/10 text-danger rounded-md">
                {{ session('error') }}
            </div>
            @endif

            @if($boqItems->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.item_code') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.item_name') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.unit') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.total_quantity') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.executed_quantity') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.completion') }} %
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.unit_price') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.total_price') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.supplier') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($boqItems as $item)
                        @php
                            $completionPercentage = $item->total_quantity > 0
                                ? ($item->executed_quantity / $item->total_quantity) * 100
                                : 0;
                            $completionClass = $completionPercentage < 50 ? 'text-danger' : ($completionPercentage < 80 ? 'text-warning' : 'text-success');
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->item_code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ app()->getLocale() === 'ar' ? $item->item_name_ar : $item->item_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ app()->getLocale() === 'ar' ? $item->unit_ar : $item->unit }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($item->total_quantity, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($item->executed_quantity, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold {{ $completionClass }}">
                                    {{ number_format($completionPercentage, 1) }}%
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="h-1.5 rounded-full {{ $completionPercentage < 50 ? 'bg-danger' : ($completionPercentage < 80 ? 'bg-warning' : 'bg-success') }}"
                                         style="width: {{ min($completionPercentage, 100) }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($item->unit_price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($item->total_price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $item->approved_supplier ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('backend.boq.edit', $item) }}"
                                       class="text-warning hover:text-warning-dark"
                                       title="{{ __('messages.edit') }}">
                                        <i class="uil uil-edit text-lg"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('backend.boq.destroy', $item) }}"
                                          onsubmit="return confirm('{{ __('messages.confirm_delete') }}');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-danger hover:text-danger-dark"
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
            </div>
            @else
            <div class="text-center py-12">
                <i class="uil uil-file-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">{{ __('messages.no_data') }}</p>
                <a href="{{ route('backend.boq.create', $project) }}" class="btn btn-primary mt-4">
                    <i class="uil uil-plus me-2"></i>
                    {{ __('messages.new') }} {{ __('messages.boq_item') }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
