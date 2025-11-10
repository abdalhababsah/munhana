@extends('backend.layouts.master')

@section('title', __('messages.contact_messages'))
@section('page-title', __('messages.contact_messages'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('backend.contacts.index') }}" class="btn btn-light"><i class="uil uil-arrow-left me-2"></i>{{ __('messages.back') }}</a>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-header"><h4 class="card-title">{{ __('messages.contact_details') }}</h4></div>
            <div class="p-6 space-y-4 text-sm text-slate-600">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('messages.name') }}</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $contact->name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('messages.email') }}</p>
                    <p class="text-slate-900">{{ $contact->email }}</p>
                </div>
                @if($contact->phone)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('messages.phone') }}</p>
                        <p class="text-slate-900">{{ $contact->phone }}</p>
                    </div>
                @endif
                @if($contact->company)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('messages.company') }}</p>
                        <p class="text-slate-900">{{ $contact->company }}</p>
                    </div>
                @endif
                @if($contact->project_type)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('messages.project_type') }}</p>
                        <p class="text-slate-900">{{ $contact->project_type }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h4 class="card-title">{{ __('messages.status') }}</h4></div>
            <div class="p-6">
                <form method="POST" action="{{ route('backend.contacts.update', $contact) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <select name="status" class="form-select w-full" required>
                        <option value="new" @selected($contact->status === 'new')>{{ __('messages.new') }}</option>
                        <option value="in_progress" @selected($contact->status === 'in_progress')>{{ __('messages.in_progress') }}</option>
                        <option value="resolved" @selected($contact->status === 'resolved')>{{ __('messages.resolved') }}</option>
                    </select>
                    <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h4 class="card-title">{{ __('messages.message') }}</h4></div>
        <div class="p-6">
            <p class="text-slate-700 whitespace-pre-line">{{ $contact->message }}</p>
        </div>
    </div>
</div>
@endsection
