@extends('layouts.front')

@section('title', __('messages.app_name') . ' - ' . __('messages.app_tagline'))

@section('content')
    {{-- HERO --}}
    <section class="relative overflow-hidden bg-[#2f2f2f] text-white py-24">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-32 {{ app()->getLocale() === 'ar' ? '-left-32' : '-right-32' }} w-96 h-96 bg-[#d3af38]/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 {{ app()->getLocale() === 'ar' ? '-right-16' : '-left-16' }} w-72 h-72 bg-[#e6dac0]/20 rounded-full blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 grid lg:grid-cols-2 gap-12 items-center relative z-10">
            <div>
                <p class="text-xs uppercase tracking-[0.45em] text-white/60">
                    {{ __('messages.hero_badge_construction') }}
                </p>
                <h1 class="text-4xl md:text-5xl font-semibold leading-tight mt-6">
                    {{ __('messages.hero_title_construction') }}
                </h1>
                <p class="text-lg text-white/80 mt-6">
                    {{ __('messages.hero_description_construction') }}
                </p>

                <div class="flex flex-wrap gap-4 mt-10">
                    <a href="{{ route('contact.show') }}"
                       class="px-8 py-3 rounded-xl font-semibold shadow-lg"
                       style="background-color:#d3af38; color:#2f2f2f; box-shadow:0 15px 35px rgba(0,0,0,0.25);">
                        {{ __('messages.start_project') }}
                    </a>
                    <a href="{{ route('login') }}"
                       class="px-8 py-3 rounded-xl font-semibold border hover:bg-white/5 transition"
                       style="border-color:rgba(230,218,192,0.6); color:#ffffff;">
                        {{ __('messages.client_portal') }}
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-6 mt-12 text-center">
                    <div>
                        <p class="text-3xl font-bold">12+</p>
                        <p class="text-sm text-white/70">{{ __('messages.years_experience') }}</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">85+</p>
                        <p class="text-sm text-white/70">{{ __('messages.projects_delivered') }}</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">18</p>
                        <p class="text-sm text-white/70">{{ __('messages.cities_served') }}</p>
                    </div>
                </div>
            </div>

            {{-- Hero Visual: Moodboard + BOQ --}}
            <div class="relative">
                <div class="rounded-[32px] border border-white/10 bg-white/5 backdrop-blur shadow-2xl p-6 md:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex gap-2">
                            <span class="w-3 h-3 rounded-full" style="background-color:#d3af38;"></span>
                            <span class="w-3 h-3 rounded-full bg-white/40"></span>
                            <span class="w-3 h-3 rounded-full bg-white/20"></span>
                        </div>
                        <span class="text-xs uppercase tracking-[0.2em] text-white/70">
                            {{ __('messages.hero_mock_palette') }}
                        </span>
                    </div>

                    <div class="grid gap-6 md:grid-cols-5">
                        {{-- Moodboard --}}
                        <div class="md:col-span-3 space-y-4">
                            <h3 class="text-sm font-semibold flex items-center gap-2 text-white">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold"
                                      style="background-color:rgba(211,175,56,0.1); color:#d3af38;">
                                    3D
                                </span>
                                {{ __('messages.hero_mock_moodboard_title') }}
                            </h3>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="aspect-[4/3] rounded-xl border border-white/10 flex items-center justify-center text-xs text-white/80"
                                     style="background:linear-gradient(135deg,#1d1d1d,#2f2f2f);">
                                    {{ __('messages.hero_mock_floorplan') }}
                                </div>
                                <div class="aspect-[4/3] rounded-xl border border-white/10 flex items-center justify-center text-xs text-[#2f2f2f]"
                                     style="background:linear-gradient(135deg,#e6dac0,#f3e7cd);">
                                    {{ __('messages.hero_mock_materials') }}
                                </div>
                                <div class="aspect-[4/3] rounded-xl border border-white/10 flex items-center justify-center text-xs text-[#2f2f2f]"
                                     style="background:radial-gradient(circle at top,#d3af38,#e6dac0);">
                                    {{ __('messages.hero_mock_lighting') }}
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-xs text-white/80">
                                <div class="flex gap-3">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full" style="background-color:#d3af38;"></span>
                                        {{ __('messages.hero_mock_status') }}
                                    </span>
                                    <span>•</span>
                                    <span>{{ __('messages.hero_mock_deadline') }}</span>
                                </div>
                                <span class="font-medium" style="color:#d3af38;">
                                    {{ __('messages.hero_mock_saved_cost') }}
                                </span>
                            </div>
                        </div>

                        {{-- BOQ Snapshot --}}
                        <div class="md:col-span-2 space-y-3 text-xs">
                            <h3 class="font-semibold text-white">
                                {{ __('messages.hero_mock_boq_title') }}
                            </h3>
                            <div class="space-y-2 rounded-xl border border-white/10 p-3"
                                 style="background-color:rgba(0,0,0,0.3);">
                                <div class="flex justify-between">
                                    <span class="text-white/80">{{ __('messages.hero_mock_item1') }}</span>
                                    <span class="font-semibold" style="color:#d3af38;">+4.5%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/80">{{ __('messages.hero_mock_item2') }}</span>
                                    <span class="font-semibold text-emerald-300">-8.0%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/80">{{ __('messages.hero_mock_item3') }}</span>
                                    <span class="font-semibold text-sky-200">+1.9%</span>
                                </div>
                            </div>

                            <div class="space-y-2 rounded-xl border border-white/10 p-3"
                                 style="background-color:rgba(0,0,0,0.3);">
                                <div class="flex justify-between text-white/80">
                                    <span>{{ __('messages.hero_mock_budget') }}</span>
                                    <span class="font-semibold text-white">$184,900</span>
                                </div>
                                <div class="w-full rounded-full h-2 overflow-hidden bg-white/10">
                                    <div class="h-2 rounded-full"
                                         style="width:80%; background:linear-gradient(to right,#d3af38,#e6dac0);"></div>
                                </div>
                                <div class="flex justify-between text-[11px] text-white/70">
                                    <span>{{ __('messages.hero_mock_progress') }}</span>
                                    <span>80%</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-[11px] text-white/70">
                                <span>{{ __('messages.hero_mock_sync') }}</span>
                                <span class="inline-flex items-center gap-1" style="color:#d3af38;">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color:#d3af38;"></span>
                                    {{ __('messages.hero_mock_live') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Floating badge --}}
                <div class="absolute -bottom-8 {{ app()->getLocale() === 'ar' ? 'left-6' : 'right-6' }} bg-white text-[#2f2f2f] rounded-2xl px-4 py-3 shadow-xl flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                         style="background-color:#e6dac0;">
                        <svg class="w-5 h-5" fill="none" stroke="#2f2f2f" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="text-xs">
                        <div class="font-semibold">{{ __('messages.hero_badge_label') }}</div>
                        <div class="text-slate-500">{{ __('messages.hero_badge_sub') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICES --}}
    <section id="services" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <p class="font-semibold uppercase tracking-wide"
                   style="color:#d3af38;">
                    {{ __('messages.services') }}
                </p>
                <h2 class="text-3xl font-semibold mt-3 text-[#2f2f2f]">
                    {{ __('messages.services_title') }}
                </h2>
                <p class="text-slate-600 mt-3">
                    {{ __('messages.services_subtitle') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $services = [
                        [
                            'title' => __('messages.service_arch_title'),
                            'text'  => __('messages.service_arch_desc'),
                            'points'=> [
                                __('messages.service_arch_point1'),
                                __('messages.service_arch_point2'),
                                __('messages.service_arch_point3'),
                            ],
                            'icon'  => 'uil-scenery',
                            'image' => 'dash/assets/images/HRES-84.webp',
                        ],
                        [
                            'title' => __('messages.service_interior_title'),
                            'text'  => __('messages.service_interior_desc'),
                            'points'=> [
                                __('messages.service_interior_point1'),
                                __('messages.service_interior_point2'),
                                __('messages.service_interior_point3'),
                            ],
                            'icon'  => 'uil-ruler-combined',
                            'image' => 'dash/assets/images/modern-sofa.jpg',
                        ],
                        [
                            'title' => __('messages.service_fitout_title'),
                            'text'  => __('messages.service_fitout_desc'),
                            'points'=> [
                                __('messages.service_fitout_point1'),
                                __('messages.service_fitout_point2'),
                                __('messages.service_fitout_point3'),
                            ],
                            'icon'  => 'uil-constructor',
                            'image' => 'dash/assets/images/modern-contemporary-interior-design.jpg',
                        ],
                        [
                            'title' => __('messages.service_turnkey_title'),
                            'text'  => __('messages.service_turnkey_desc'),
                            'points'=> [
                                __('messages.service_turnkey_point1'),
                                __('messages.service_turnkey_point2'),
                                __('messages.service_turnkey_point3'),
                            ],
                            'icon'  => 'uil-key-skeleton',
                            'image' => 'dash/assets/images/a44f725d1984987604fbad6f904e8e9e.webp',
                        ],
                    ];
                @endphp

                @foreach($services as $service)
                    <div class="card border border-slate-100 hover:border-[#d3af38]/60 hover:shadow-xl transition bg-white rounded-2xl overflow-hidden">
                        @if($service['image'])
                            <div class="h-48 overflow-hidden">
                                <img src="{{ asset($service['image']) }}"
                                     alt="{{ $service['title'] }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        @endif
                        <div class="p-6">
                            @if(!$service['image'])
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 text-2xl"
                                     style="background-color:rgba(227,208,165,0.35); color:#2f2f2f;">
                                    <i class="uil {{ $service['icon'] }}"></i>
                                </div>
                            @endif
                            <h3 class="text-xl font-semibold mb-3 text-[#2f2f2f]">{{ $service['title'] }}</h3>
                            <p class="text-slate-600 text-sm mb-4">{{ $service['text'] }}</p>
                            <ul class="text-xs text-slate-500 space-y-1">
                                @foreach($service['points'] as $point)
                                    <li>• {{ $point }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PROJECTS --}}
    <section id="projects" class="py-20" style="background-color:#f7f4ec;">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-10">
                <div>
                    <p class="font-semibold uppercase tracking-wide"
                       style="color:#d3af38;">
                        {{ __('messages.featured_projects') }}
                    </p>
                    <h2 class="text-3xl font-semibold mt-2 text-[#2f2f2f]">
                        {{ __('messages.projects_title') }}
                    </h2>
                    <p class="text-slate-600 mt-2 max-w-xl">
                        {{ __('messages.projects_subtitle') }}
                    </p>
                </div>
                <a href="{{ route('login') }}"
                   class="px-6 py-2 rounded-full border text-sm font-semibold hover:bg-[#2f2f2f] hover:text-white transition"
                   style="border-color:#d3af38; color:#2f2f2f;">
                    {{ __('messages.projects_button') }}
                </a>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Card 1 --}}
                <article class="bg-white rounded-3xl shadow border border-slate-100 overflow-hidden">
                    <div class="h-48 relative"
                         style="background:linear-gradient(135deg,#2f2f2f,#d3af38);">
                        <div class="absolute inset-5 border border-white/35 rounded-2xl"></div>
                        <span class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} text-xs px-3 py-1 rounded-full bg-black/40 text-white">
                            {{ __('messages.project1_tag') }}
                        </span>
                    </div>
                    <div class="p-6 space-y-2">
                        <span class="text-xs uppercase tracking-wide" style="color:#d3af38;">
                            {{ __('messages.project1_scope') }}
                        </span>
                        <h3 class="text-xl font-semibold text-[#2f2f2f]">
                            {{ __('messages.project1_title') }}
                        </h3>
                        <p class="text-slate-600 text-sm">
                            {{ __('messages.project1_location') }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ __('messages.project1_stat') }}
                        </p>
                    </div>
                </article>

                {{-- Card 2 --}}
                <article class="bg-white rounded-3xl shadow border border-slate-100 overflow-hidden">
                    <div class="h-48 relative"
                         style="background:linear-gradient(135deg,#2f2f2f,#e6dac0);">
                        <div class="absolute inset-5 border border-white/35 rounded-2xl"></div>
                        <span class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} text-xs px-3 py-1 rounded-full bg-black/40 text-white">
                            {{ __('messages.project2_tag') }}
                        </span>
                    </div>
                    <div class="p-6 space-y-2">
                        <span class="text-xs uppercase tracking-wide" style="color:#d3af38;">
                            {{ __('messages.project2_scope') }}
                        </span>
                        <h3 class="text-xl font-semibold text-[#2f2f2f]">
                            {{ __('messages.project2_title') }}
                        </h3>
                        <p class="text-slate-600 text-sm">
                            {{ __('messages.project2_location') }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ __('messages.project2_stat') }}
                        </p>
                    </div>
                </article>

                {{-- Card 3 --}}
                <article class="bg-white rounded-3xl shadow border border-slate-100 overflow-hidden">
                    <div class="h-48 relative"
                         style="background:linear-gradient(135deg,#2f2f2f,#b5932a);">
                        <div class="absolute inset-5 border border-white/35 rounded-2xl"></div>
                        <span class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} text-xs px-3 py-1 rounded-full bg-black/40 text-white">
                            {{ __('messages.project3_tag') }}
                        </span>
                    </div>
                    <div class="p-6 space-y-2">
                        <span class="text-xs uppercase tracking-wide" style="color:#d3af38;">
                            {{ __('messages.project3_scope') }}
                        </span>
                        <h3 class="text-xl font-semibold text-[#2f2f2f]">
                            {{ __('messages.project3_title') }}
                        </h3>
                        <p class="text-slate-600 text-sm">
                            {{ __('messages.project3_location') }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ __('messages.project3_stat') }}
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- PLATFORM / PROCESS --}}
    <section id="platform" class="py-20 bg-white">
        <div class="container mx-auto px-4 grid lg:grid-cols-2 gap-10 items-start">
            <div>
                <p class="font-semibold uppercase tracking-wide"
                   style="color:#d3af38;">
                    {{ __('messages.platform_title') }}
                </p>
                <h2 class="text-3xl font-semibold mt-4 text-[#2f2f2f]">
                    {{ __('messages.process_title') }}
                </h2>
                <p class="mt-3 text-slate-600">
                    {{ __('messages.process_subtitle') }}
                </p>

                <ul class="mt-6 space-y-4 text-slate-700 text-sm">
                    <li class="flex gap-3">
                        <i class="uil uil-check-circle text-2xl" style="color:#d3af38;"></i>
                        <div>
                            <h3 class="font-semibold text-[#2f2f2f] mb-1">
                                {{ __('messages.process_step1_title') }}
                            </h3>
                            <p class="text-slate-600">{{ __('messages.process_step1_desc') }}</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <i class="uil uil-check-circle text-2xl" style="color:#d3af38;"></i>
                        <div>
                            <h3 class="font-semibold text-[#2f2f2f] mb-1">
                                {{ __('messages.process_step2_title') }}
                            </h3>
                            <p class="text-slate-600">{{ __('messages.process_step2_desc') }}</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <i class="uil uil-check-circle text-2xl" style="color:#d3af38;"></i>
                        <div>
                            <h3 class="font-semibold text-[#2f2f2f] mb-1">
                                {{ __('messages.process_step3_title') }}
                            </h3>
                            <p class="text-slate-600">{{ __('messages.process_step3_desc') }}</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <i class="uil uil-check-circle text-2xl" style="color:#d3af38;"></i>
                        <div>
                            <h3 class="font-semibold text-[#2f2f2f] mb-1">
                                {{ __('messages.process_step4_title') }}
                            </h3>
                            <p class="text-slate-600">{{ __('messages.process_step4_desc') }}</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="rounded-3xl shadow-xl border border-slate-100 p-8"
                 style="background-color:#2f2f2f; color:white;">
                <h3 class="text-2xl font-semibold">
                    {{ __('messages.platform_headline') }}
                </h3>
                <p class="text-white/80 mt-4 text-sm">
                    {{ __('messages.process_panel_note') }}
                </p>

                <div class="mt-8 space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-white/80">{{ __('messages.process_panel_line1') }}</span>
                        <span class="font-semibold" style="color:#d3af38;">12</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/80">{{ __('messages.process_panel_line2') }}</span>
                        <span class="font-semibold text-[#e6dac0]">7</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/80">{{ __('messages.process_panel_line3') }}</span>
                        <span class="font-semibold text-emerald-300">+3.2%</span>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('contact.show') }}"
                       class="px-6 py-2 rounded-full text-sm font-semibold"
                       style="background-color:#d3af38; color:#2f2f2f;">
                        {{ __('messages.book_demo') }}
                    </a>
                    <a href="{{ route('login') }}"
                       class="px-6 py-2 rounded-full text-sm font-semibold border hover:bg-white/10 transition"
                       style="border-color:#e6dac0; color:#ffffff;">
                        {{ __('messages.login') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section id="cta" class="py-20 text-white"
             style="background:linear-gradient(135deg,#2f2f2f,#1a1a1a);">
        <div class="container mx-auto px-4 max-w-4xl text-center">
            <h2 class="text-3xl md:text-4xl font-semibold mb-4">
                {{ __('messages.cta_title') }}
            </h2>
            <p class="text-lg md:text-xl text-white/80 mb-8">
                {{ __('messages.cta_description') }}
            </p>
            <div class="flex flex-wrap gap-4 justify-center items-center">
                <a href="{{ route('contact.show') }}"
                   class="px-10 py-3.5 rounded-full font-semibold text-base shadow-xl"
                   style="background-color:#d3af38; color:#2f2f2f;">
                    {{ __('messages.cta_button') }}
                </a>
                <a href="#services"
                   class="px-8 py-3.5 rounded-full font-semibold text-sm border hover:bg-white/5 transition"
                   style="border-color:#e6dac0; color:#ffffff;">
                    {{ __('messages.cta_secondary') }}
                </a>
            </div>
        </div>
    </section>
@endsection
