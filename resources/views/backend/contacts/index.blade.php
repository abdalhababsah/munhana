@extends('backend.layouts.master')

@section('title', __('messages.contact_messages'))
@section('page-title', __('messages.contact_messages'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="grid md:grid-cols-4 gap-4">
        <div class="card"><div class="p-6"><p class="text-sm text-slate-500">{{ __('messages.total_messages') }}</p><h3 class="text-3xl font-bold text-slate-900">{{ $stats['total'] }}</h3></div></div>
        <div class="card border-l-4 border-primary"><div class="p-6"><p class="text-sm text-slate-500">{{ __('messages.new') }}</p><h3 class="text-3xl font-bold text-primary">{{ $stats['new'] }}</h3></div></div>
        <div class="card border-l-4 border-warning"><div class="p-6"><p class="text-sm text-slate-500">{{ __('messages.in_progress') }}</p><h3 class="text-3xl font-bold text-warning">{{ $stats['in_progress'] }}</h3></div></div>
        <div class="card border-l-4 border-success"><div class="p-6"><p class="text-sm text-slate-500">{{ __('messages.resolved') }}</p><h3 class="text-3xl font-bold text-success">{{ $stats['resolved'] }}</h3></div></div>
    </div>

    <div class="card">
        <div class="p-4 flex flex-wrap gap-3">
            @php
                $filters = [
                    null => __('messages.all'),
                    'new' => __('messages.new'),
                    'in_progress' => __('messages.in_progress'),
                    'resolved' => __('messages.resolved'),
                ];
            @endphp
            @foreach($filters as $key => $label)
                <a href="{{ route('backend.contacts.index', ['status' => $key]) }}"
                   class="px-4 py-2 rounded-full text-sm font-semibold border {{ $status === $key ? 'bg-primary text-white border-primary' : 'bg-white text-slate-600 border-slate-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h4 class="card-title">{{ __('messages.recent_messages') }}</h4></div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">{{ __('messages.name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">{{ __('messages.email') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">{{ __('messages.subject') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">{{ __('messages.status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($contacts as $contact)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-slate-900">{{ $contact->name }}</p>
                                <p class="text-xs text-slate-500">{{ $contact->created_at->format('Y-m-d') }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $contact->email }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $contact->subject }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $badge = match($contact->status) {
                                        'new' => 'bg-primary/10 text-primary',
                                        'in_progress' => 'bg-warning/10 text-warning',
                                        'resolved' => 'bg-success/10 text-success',
                                        default => 'bg-slate-100 text-slate-600'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">{{ __('messages.' . $contact->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('backend.contacts.show', $contact) }}" class="btn btn-sm btn-light">{{ __('messages.view_details') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">{{ __('messages.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $contacts->links() }}</div>
    </div>
</div>
@endsection
