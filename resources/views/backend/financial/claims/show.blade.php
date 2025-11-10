@extends('backend.layouts.master')

@section('title', __('messages.financial_claim') . ' - ' . $claim->claim_number)
@section('page-title', __('messages.financial_claim'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ $claim->claim_number }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">
                {{ app()->getLocale() === 'ar' ? $claim->project->name_ar : $claim->project->name }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('backend.claims.edit', $claim) }}" class="btn btn-warning">
                <i class="uil uil-edit me-2"></i>
                {{ __('messages.edit') }}
            </a>
            <a href="{{ route('backend.claims.index', $claim->project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Claim Details -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">{{ __('messages.claim_details') }}</h4>
                        @if($claim->status === 'pending')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-warning/10 text-warning">
                                {{ __('messages.pending') }}
                            </span>
                        @elseif($claim->status === 'approved')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-success/10 text-success">
                                {{ __('messages.approved') }}
                            </span>
                        @elseif($claim->status === 'paid')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-info/10 text-info">
                                {{ __('messages.paid') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('messages.claim_date') }}</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($claim->claim_date)->format('l, F d, Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('messages.amount') }}</p>
                            <p class="text-2xl font-bold text-primary">
                                {{ number_format($claim->amount, 2) }}
                            </p>
                        </div>
                    </div>

                    <!-- Description (English) -->
                    <div class="mb-6">
                        <h5 class="text-sm font-semibold text-gray-700 mb-3">
                            {{ __('messages.description') }} ({{ __('messages.english') }})
                        </h5>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="prose max-w-none">
                                <div class="whitespace-pre-line text-gray-700">{{ $claim->description }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Description (Arabic) -->
                    @if($claim->description_ar)
                    <div class="mb-6">
                        <h5 class="text-sm font-semibold text-gray-700 mb-3">
                            {{ __('messages.description') }} ({{ __('messages.arabic') }})
                        </h5>
                        <div class="bg-gray-50 rounded-lg p-4" dir="rtl">
                            <div class="prose max-w-none">
                                <div class="whitespace-pre-line text-gray-700">{{ $claim->description_ar }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Attachment -->
                    @if($claim->attachment_path)
                    <div class="mb-6">
                        <h5 class="text-sm font-semibold text-gray-700 mb-3">{{ __('messages.attachment') }}</h5>
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                            <i class="uil uil-file text-2xl text-primary"></i>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ basename($claim->attachment_path) }}</p>
                                @if(file_exists(storage_path('app/public/' . $claim->attachment_path)))
                                <p class="text-xs text-gray-500">
                                    {{ number_format(filesize(storage_path('app/public/' . $claim->attachment_path)) / 1024, 2) }} KB
                                </p>
                                @endif
                            </div>
                            <a href="{{ asset('storage/' . $claim->attachment_path) }}"
                               download
                               class="btn btn-sm btn-primary">
                                <i class="uil uil-download-alt me-1"></i>
                                {{ __('messages.download') }}
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Metadata -->
                    <div class="pt-6 border-t">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">{{ __('messages.submitted_by') }}:</span>
                                {{ $claim->submitter->name }}
                            </div>
                            <div>
                                <span class="font-medium">{{ __('messages.created_at') }}:</span>
                                {{ $claim->created_at->format('Y-m-d H:i') }}
                            </div>
                            @if($claim->updated_at != $claim->created_at)
                            <div>
                                <span class="font-medium">{{ __('messages.updated_at') }}:</span>
                                {{ $claim->updated_at->format('Y-m-d H:i') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="card mb-6">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.summary') }}</h4>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">{{ __('messages.project') }}</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ app()->getLocale() === 'ar' ? $claim->project->name_ar : $claim->project->name }}
                        </p>
                    </div>
                    <div class="pt-4 border-t">
                        <p class="text-sm text-gray-500 mb-1">{{ __('messages.running_total') }}</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($runningTotal, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
