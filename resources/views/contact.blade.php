@extends('layouts.front')

@section('title', __('messages.contact_us') . ' | ' . __('messages.app_name'))

@section('content')
    {{-- HERO / HEADER --}}
    <section class="py-20 text-white relative overflow-hidden"
             style="background:linear-gradient(135deg,#2f2f2f,#1b1b1b);">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 {{ app()->getLocale() === 'ar' ? '-left-24' : '-right-24' }} w-80 h-80 rounded-full blur-3xl"
                 style="background-color:rgba(211,175,56,0.25);"></div>
            <div class="absolute bottom-0 {{ app()->getLocale() === 'ar' ? '-right-16' : '-left-16' }} w-64 h-64 rounded-full blur-3xl"
                 style="background-color:rgba(230,218,192,0.2);"></div>
        </div>

        <div class="container mx-auto px-4 max-w-5xl relative z-10">
            <p class="text-sm uppercase tracking-[0.45em] text-white/70">
                {{ __('messages.get_in_touch') }}
            </p>
            <h1 class="text-4xl md:text-5xl font-semibold mt-4">
                {{ __('messages.contact_headline') }}
            </h1>
            <p class="text-lg text-white/80 mt-6 max-w-3xl">
                {{ __('messages.contact_intro') }}
            </p>
        </div>
    </section>

    {{-- CONTENT + FORM --}}
    <section class="py-16"
             style="background-color:#f7f4ec;">
        <div class="container mx-auto px-4 grid lg:grid-cols-3 gap-8">
            {{-- LEFT COLUMN: INFO + MAP --}}
            <div class="space-y-6">
                {{-- Office Card --}}
                <div class="bg-white rounded-2xl shadow border border-slate-100 p-8">
                    <h3 class="text-xl font-semibold"
                        style="color:#2f2f2f;">
                        {{ __('messages.office_address') }}
                    </h3>
                    <p class="mt-4 text-slate-600">
                        {{ __('messages.office_address_text') }}
                    </p>
                    <div class="mt-6 space-y-4 text-slate-700">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                {{ __('messages.phone') }}
                            </p>
                            <p class="text-lg font-semibold"
                               style="color:#2f2f2f;">
                                +966 11 123 4567
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                {{ __('messages.email') }}
                            </p>
                            <p class="text-lg font-semibold"
                               style="color:#2f2f2f;">
                                hello@munhana.sa
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Consultation Card --}}
                <div class="rounded-2xl shadow-lg p-8 bg-white text-black"
                     >
                    <h3 class="text-xl font-semibold">
                        {{ __('messages.project_consultation') }}
                    </h3>
                    <p class="text-white/75 mt-4 text-sm">
                        {{ __('messages.project_consultation_text') }}
                    </p>
                    <div class="mt-6 flex items-center gap-3">
                        <div class="inline-flex items-center justify-center h-10 w-10 rounded-full"
                             style="background-color:#e6dac0;">
                            <img src="{{ asset('dash/assets/images/logo-sm.png') }}"
                                 class="h-7"
                                 alt="logo">
                        </div>
                        <img src="{{ asset('dash/assets/images/logo-light.png') }}"
                             class="h-6"
                             alt="Munhana">
                    </div>
                </div>

                {{-- Map Card --}}
                <div style="background-color:#2f2f2f;" class=" rounded-2xl shadow border border-slate-100 overflow-hidden">
                    <div class="px-6 pt-6 pb-2">
                        <h3 class="text-sm text-white font-semibold"
                           >
                            {{ __('messages.office_location') ?? __('messages.office_address') }}
                        </h3>
                        <p class="text-xs text-white mt-1">
                            {{ __('messages.office_address_text') }}
                        </p>
                    </div>
                    <div class="w-full aspect-[4/3]">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d4394.7512333294435!2d35.8974420756326!3d32.044009973978596!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2s!5e1!3m2!1sen!2sjo!4v1762710954245!5m2!1sen!2sjo"
                            style="border:0;"
                            class="w-full h-full"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: FORM --}}
            <div class="lg:col-span-2">
                @if(session('success'))
                    <div class="mb-6 rounded-2xl px-4 py-3 flex items-center gap-2"
                         style="border:1px solid rgba(34,197,94,0.25); background-color:rgba(34,197,94,0.08); color:#15803d;">
                        <i class="uil uil-check-circle text-xl"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow border border-slate-100 p-8">
                    <form method="POST"
                          action="{{ route('contact.submit') }}"
                          class="grid gap-6">
                        @csrf

                        {{-- Name + Email --}}
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label text-sm font-semibold"
                                       style="color:#2f2f2f;">
                                    {{ __('messages.name') }}
                                </label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="form-input w-full @error('name') border-danger @enderror"
                                       style="border-radius:0.75rem;"
                                       required>
                                @error('name')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label text-sm font-semibold"
                                       style="color:#2f2f2f;">
                                    {{ __('messages.email') }}
                                </label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-input w-full @error('email') border-danger @enderror"
                                       style="border-radius:0.75rem;"
                                       required>
                                @error('email')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Phone + Company --}}
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label text-sm font-semibold"
                                       style="color:#2f2f2f;">
                                    {{ __('messages.phone') }}
                                </label>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       class="form-input w-full @error('phone') border-danger @enderror"
                                       style="border-radius:0.75rem;">
                                @error('phone')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label text-sm font-semibold"
                                       style="color:#2f2f2f;">
                                    {{ __('messages.company') }}
                                </label>
                                <input type="text"
                                       name="company"
                                       value="{{ old('company') }}"
                                       class="form-input w-full @error('company') border-danger @enderror"
                                       style="border-radius:0.75rem;">
                                @error('company')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Project type + Subject --}}
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label text-sm font-semibold"
                                       style="color:#2f2f2f;">
                                    {{ __('messages.project_type') }}
                                </label>
                                <input type="text"
                                       name="project_type"
                                       value="{{ old('project_type') }}"
                                       class="form-input w-full @error('project_type') border-danger @enderror"
                                       style="border-radius:0.75rem;">
                                @error('project_type')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label text-sm font-semibold"
                                       style="color:#2f2f2f;">
                                    {{ __('messages.subject') }}
                                </label>
                                <input type="text"
                                       name="subject"
                                       value="{{ old('subject') }}"
                                       class="form-input w-full @error('subject') border-danger @enderror"
                                       style="border-radius:0.75rem;"
                                       required>
                                @error('subject')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Message --}}
                        <div>
                            <label class="form-label text-sm font-semibold"
                                   style="color:#2f2f2f;">
                                {{ __('messages.message') }}
                            </label>
                            <textarea name="message"
                                      rows="5"
                                      class="form-textarea w-full @error('message') border-danger @enderror"
                                      style="border-radius:0.75rem;"
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                                class="btn w-full py-3 text-lg font-semibold"
                                style="background-color:#d3af38; color:#2f2f2f; border-radius:9999px; border:1px solid #d3af38;">
                            {{ __('messages.send_message') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
