@extends('backend.layouts.master')

@section('title', __('messages.financial_claims') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.financial_claims'))

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
            <a href="{{ route('backend.claims.create', $project) }}" class="btn btn-primary">
                <i class="uil uil-plus me-2"></i>
                {{ __('messages.new') }} {{ __('messages.financial_claim') }}
            </a>
            <a href="{{ route('backend.projects.show', $project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="grid lg:grid-cols-4 gap-6">
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_claims') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_claims'], 2) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-money-bill text-primary text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_approved') }}</p>
                        <h3 class="text-2xl font-bold text-success">{{ number_format($stats['total_approved'], 2) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-check-circle text-success text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_paid') }}</p>
                        <h3 class="text-2xl font-bold text-info">{{ number_format($stats['total_paid'], 2) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-dollar-sign text-info text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.pending_amount') }}</p>
                        <h3 class="text-2xl font-bold text-warning">{{ number_format($stats['pending_amount'], 2) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-clock text-warning text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">{{ __('messages.filter_by_status') }}</h4>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('backend.claims.index', $project) }}" class="flex items-center gap-2">
                <button type="submit" name="status" value="" class="btn {{ !request('status') ? 'btn-primary' : 'btn-light' }}">
                    {{ __('messages.all') }}
                </button>
                <button type="submit" name="status" value="pending" class="btn {{ request('status') === 'pending' ? 'btn-warning' : 'btn-light' }}">
                    {{ __('messages.pending') }}
                </button>
                <button type="submit" name="status" value="approved" class="btn {{ request('status') === 'approved' ? 'btn-success' : 'btn-light' }}">
                    {{ __('messages.approved') }}
                </button>
                <button type="submit" name="status" value="paid" class="btn {{ request('status') === 'paid' ? 'btn-info' : 'btn-light' }}">
                    {{ __('messages.paid') }}
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.financial_claims') }}</h4>
        </div>
        <div class="overflow-x-auto">
            @if($claims->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.claim_number') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.claim_date') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.amount') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.status') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.description') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.submitted_by') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($claims as $claim)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $claim->claim_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($claim->claim_date)->format('Y-m-d') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($claim->amount, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($claim->status === 'pending')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-warning/10 text-warning">
                                    {{ __('messages.pending') }}
                                </span>
                            @elseif($claim->status === 'approved')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-success/10 text-success">
                                    {{ __('messages.approved') }}
                                </span>
                            @elseif($claim->status === 'paid')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-info/10 text-info">
                                    {{ __('messages.paid') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">
                                {{ Str::limit(app()->getLocale() === 'ar' && $claim->description_ar ? $claim->description_ar : $claim->description, 50) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $claim->submitter->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('backend.claims.show', $claim) }}"
                                   class="text-info hover:text-info/80"
                                   title="{{ __('messages.view') }}">
                                    <i class="uil uil-eye text-lg"></i>
                                </a>
                                <a href="{{ route('backend.claims.edit', $claim) }}"
                                   class="text-warning hover:text-warning/80"
                                   title="{{ __('messages.edit') }}">
                                    <i class="uil uil-edit text-lg"></i>
                                </a>
                                @if($claim->status !== 'paid')
                                <form method="POST"
                                      action="{{ route('backend.claims.destroy', $claim) }}"
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
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-12 text-center">
                <i class="uil uil-money-bill text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_data') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
