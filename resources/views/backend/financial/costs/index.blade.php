@extends('backend.layouts.master')

@section('title', __('messages.project_costs') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.project_costs'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $project->contract_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('backend.costs.create', $project) }}" class="btn btn-primary">
                <i class="uil uil-plus me-2"></i>
                {{ __('messages.new') }} {{ __('messages.cost') }}
            </a>
            <a href="{{ route('backend.projects.show', $project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid lg:grid-cols-5 gap-6">
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.labor') }}</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ number_format($stats['labor'], 2) }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-user text-primary text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.material') }}</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ number_format($stats['material'], 2) }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-box text-success text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.equipment') }}</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ number_format($stats['equipment'], 2) }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-wrench text-warning text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.other') }}</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ number_format($stats['other'], 2) }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-ellipsis-h text-info text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total') }}</p>
                        <h3 class="text-2xl font-bold text-danger">{{ number_format($stats['total'], 2) }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-danger/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-chart-line text-danger text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget vs Actual -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.budget_vs_actual') }}</h4>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">{{ __('messages.budget') }}: {{ number_format($budget, 2) }}</span>
                    <span class="text-sm font-medium text-gray-900">{{ number_format($budgetPercentage, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-6">
                    <div class="h-6 rounded-full flex items-center justify-end px-2 {{ $budgetPercentage > 100 ? 'bg-danger' : ($budgetPercentage > 80 ? 'bg-warning' : 'bg-success') }}"
                         style="width: {{ min($budgetPercentage, 100) }}%">
                        <span class="text-xs font-semibold text-white">{{ number_format($budgetUsed, 2) }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-sm text-gray-600">{{ __('messages.used') }}: {{ number_format($budgetUsed, 2) }}</span>
                    <span class="text-sm {{ $budgetRemaining >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ __('messages.remaining') }}: {{ number_format($budgetRemaining, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cost Type Filters -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.filter_by_type') }}</h4>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('backend.costs.index', $project) }}" class="flex items-end gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <button type="submit" name="cost_type" value="" class="btn {{ !request('cost_type') ? 'btn-primary' : 'btn-light' }}">
                        {{ __('messages.all') }}
                    </button>
                    <button type="submit" name="cost_type" value="labor" class="btn {{ request('cost_type') === 'labor' ? 'btn-primary' : 'btn-light' }}">
                        {{ __('messages.labor') }}
                    </button>
                    <button type="submit" name="cost_type" value="material" class="btn {{ request('cost_type') === 'material' ? 'btn-success' : 'btn-light' }}">
                        {{ __('messages.material') }}
                    </button>
                    <button type="submit" name="cost_type" value="equipment" class="btn {{ request('cost_type') === 'equipment' ? 'btn-warning' : 'btn-light' }}">
                        {{ __('messages.equipment') }}
                    </button>
                    <button type="submit" name="cost_type" value="other" class="btn {{ request('cost_type') === 'other' ? 'btn-info' : 'btn-light' }}">
                        {{ __('messages.other') }}
                    </button>
                </div>

                <div class="flex-1 flex items-end gap-4">
                    <div class="flex-1">
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.date_from') }}
                        </label>
                        <input type="date"
                               id="date_from"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               class="form-input w-full">
                    </div>
                    <div class="flex-1">
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.date_to') }}
                        </label>
                        <input type="date"
                               id="date_to"
                               name="date_to"
                               value="{{ request('date_to') }}"
                               class="form-input w-full">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-filter me-2"></i>
                        {{ __('messages.filter') }}
                    </button>
                    @if(request()->hasAny(['cost_type', 'date_from', 'date_to']))
                    <a href="{{ route('backend.costs.index', $project) }}" class="btn btn-light">
                        <i class="uil uil-times me-2"></i>
                        {{ __('messages.clear') }}
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Costs Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.project_costs') }}</h4>
        </div>
        <div class="overflow-x-auto">
            @if($costs->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.cost_date') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.cost_type') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.amount') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.description') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($costs as $cost)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($cost->cost_date)->format('Y-m-d') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($cost->cost_type === 'labor')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-primary/10 text-primary">
                                    {{ __('messages.labor') }}
                                </span>
                            @elseif($cost->cost_type === 'material')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-success/10 text-success">
                                    {{ __('messages.material') }}
                                </span>
                            @elseif($cost->cost_type === 'equipment')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-warning/10 text-warning">
                                    {{ __('messages.equipment') }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-info/10 text-info">
                                    {{ __('messages.other') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($cost->amount, 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">
                                {{ Str::limit(app()->getLocale() === 'ar' && $cost->description_ar ? $cost->description_ar : $cost->description, 50) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('backend.costs.edit', $cost) }}"
                                   class="text-warning hover:text-warning/80"
                                   title="{{ __('messages.edit') }}">
                                    <i class="uil uil-edit text-lg"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('backend.costs.destroy', $cost) }}"
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
            @else
            <div class="p-12 text-center">
                <i class="uil uil-chart-line text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_data') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
