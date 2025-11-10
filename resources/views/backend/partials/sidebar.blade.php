<!-- Sidebar Menu Start -->
@php
    $authUser = auth()->user();
    $role = $authUser?->role ?? 'guest';
    $dashboardRoute = match ($role) {
        'client' => 'client.dashboard',
        'worker' => 'worker.dashboard',
        default => 'backend.dashboard',
    };
@endphp
<div class="app-menu">

    <!-- Brand Logo -->
    <a href="{{ route($dashboardRoute) }}" class="logo-box">
        <img src="{{ asset('dash/assets/images/logo-light.png') }}" class="logo-light h-10" alt="Light logo">
        <img src="{{ asset('dash/assets/images/logo-dark.png') }}" class="logo-dark h-10" alt="Dark logo">
    </a>

    <!--- Menu -->
    <div data-simplebar>
        <ul class="menu">
            @if($role === 'client')
                <li class="menu-title">{{ __('messages.dashboard') }}</li>
                <li class="menu-item">
                    <a href="{{ route('client.dashboard') }}" class="menu-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-estate"></i></span>
                        <span class="menu-text">{{ __('messages.dashboard') }}</span>
                    </a>
                </li>

                <li class="menu-title">{{ __('messages.my_projects') }}</li>
                <li class="menu-item">
                    <a href="{{ route('client.projects.index') }}" class="menu-link {{ request()->routeIs('client.projects.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-briefcase"></i></span>
                        <span class="menu-text">{{ __('messages.my_projects') }}</span>
                    </a>
                </li>

                <li class="menu-title">{{ __('messages.support') }}</li>
                <li class="menu-item">
                    <a href="{{ route('client.issues.projects') }}" class="menu-link {{ request()->routeIs('client.issues.projects') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-comment-question"></i></span>
                        <span class="menu-text">{{ __('messages.report_issue') }}</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('client.issues.index') }}" class="menu-link {{ request()->routeIs('client.issues.*') && !request()->routeIs('client.issues.projects') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-notebooks"></i></span>
                        <span class="menu-text">{{ __('messages.my_issues') }}</span>
                    </a>
                </li>

                <li class="menu-title">{{ __('messages.profile') }}</li>
                <li class="menu-item">
                    <a href="{{ route('profile.edit') }}" class="menu-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-user"></i></span>
                        <span class="menu-text">{{ __('messages.profile') }}</span>
                    </a>
                </li>

                <li class="menu-item">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form-client">
                        @csrf
                        <a href="#" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form-client').submit();">
                            <span class="menu-icon"><i class="uil uil-sign-out-alt"></i></span>
                            <span class="menu-text">{{ __('messages.logout') }}</span>
                        </a>
                    </form>
                </li>
            @elseif($role === 'worker')
                <li class="menu-title">{{ __('messages.dashboard') }}</li>
                <li class="menu-item">
                    <a href="{{ route('worker.dashboard') }}" class="menu-link {{ request()->routeIs('worker.dashboard') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-estate"></i></span>
                        <span class="menu-text">{{ __('messages.dashboard') }}</span>
                    </a>
                </li>

                <li class="menu-title">{{ __('messages.projects') }}</li>
                <li class="menu-item">
                    <a href="{{ route('worker.projects.index') }}" class="menu-link {{ request()->routeIs('worker.projects.index') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-briefcase-alt"></i></span>
                        <span class="menu-text">{{ __('messages.worker_projects') }}</span>
                    </a>
                </li>

                <li class="menu-title">{{ __('messages.quick_action') }}</li>
                <li class="menu-item">
                    <a href="{{ route('worker.projects.index', ['action' => 'report']) }}" class="menu-link {{ request()->routeIs('worker.reports.*') || (request()->routeIs('worker.projects.index') && request('action') === 'report') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-clipboard-alt"></i></span>
                        <span class="menu-text">{{ __('messages.add_daily_report') }}</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('worker.projects.index', ['action' => 'material']) }}" class="menu-link {{ request()->routeIs('worker.materials.*') || (request()->routeIs('worker.projects.index') && request('action') === 'material') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-truck"></i></span>
                        <span class="menu-text">{{ __('messages.add_material_delivery') }}</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('worker.projects.index', ['action' => 'photo']) }}" class="menu-link {{ request()->routeIs('worker.photos.*') || (request()->routeIs('worker.projects.index') && request('action') === 'photo') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-camera"></i></span>
                        <span class="menu-text">{{ __('messages.upload_site_photos') }}</span>
                    </a>
                </li>

                <li class="menu-title">{{ __('messages.profile') }}</li>
                <li class="menu-item">
                    <a href="{{ route('profile.edit') }}" class="menu-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-user"></i></span>
                        <span class="menu-text">{{ __('messages.profile') }}</span>
                    </a>
                </li>

                <li class="menu-item">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form-worker">
                        @csrf
                        <a href="#" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form-worker').submit();">
                            <span class="menu-icon"><i class="uil uil-sign-out-alt"></i></span>
                            <span class="menu-text">{{ __('messages.logout') }}</span>
                        </a>
                    </form>
                </li>
            @elseif($role === 'admin')
                <li class="menu-title">{{ __('messages.dashboard') }}</li>

                <li class="menu-item">
                    <a href="{{ route('backend.dashboard') }}" class="menu-link {{ request()->routeIs('backend.dashboard') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-estate"></i></span>
                        <span class="menu-text"> {{ __('messages.dashboard') }} </span>
                    </a>
                </li>

                <li class="menu-title">{{ __('messages.projects') }}</li>

                <li class="menu-item">
                    <a href="javascript:void(0)" data-hs-collapse="#sidenavProjects" class="menu-link {{ request()->routeIs('backend.projects.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-briefcase"></i></span>
                        <span class="menu-text"> {{ __('messages.projects') }} </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul id="sidenavProjects" class="sub-menu {{ request()->routeIs('backend.projects.*') ? '' : 'hidden' }}">
                        <li class="menu-item">
                            <a href="{{ route('backend.projects.index') }}" class="menu-link {{ request()->routeIs('backend.projects.index') ? 'active' : '' }}">
                                <span class="menu-dot"></span>
                                <span class="menu-text">{{ __('messages.all_projects') }}</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('backend.projects.create') }}" class="menu-link {{ request()->routeIs('backend.projects.create') ? 'active' : '' }}">
                                <span class="menu-dot"></span>
                                <span class="menu-text">{{ __('messages.new_project') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-title">{{ __('messages.boq') }}</li>

                <li class="menu-item">
                    <a href="{{ route('backend.boq.all') }}" class="menu-link {{ request()->routeIs('backend.boq.all') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-list-ul"></i></span>
                        <span class="menu-text"> {{ __('messages.boq_items') }} </span>
                    </a>
                </li>

                <li class="menu-title">{{ __('messages.timeline') }}</li>

                <li class="menu-item">
                    <a href="{{ route('backend.timeline.all') }}" class="menu-link {{ request()->routeIs('backend.timeline.all') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-calender"></i></span>
                        <span class="menu-text"> {{ __('messages.timelines') }} </span>
                    </a>
                </li>

                <li class="menu-title">{{ __('messages.users') }}</li>

                <li class="menu-item">
                    <a href="javascript:void(0)" data-hs-collapse="#sidenavUsers" class="menu-link {{ request()->routeIs('backend.users.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-users-alt"></i></span>
                        <span class="menu-text"> {{ __('messages.users') }} </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul id="sidenavUsers" class="sub-menu {{ request()->routeIs('backend.users.*') ? '' : 'hidden' }}">
                        <li class="menu-item">
                            <a href="{{ route('backend.users.index') }}" class="menu-link {{ request()->routeIs('backend.users.index') ? 'active' : '' }}">
                                <span class="menu-dot"></span>
                                <span class="menu-text">{{ __('messages.users') }}</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('backend.users.create') }}" class="menu-link {{ request()->routeIs('backend.users.create') ? 'active' : '' }}">
                                <span class="menu-dot"></span>
                                <span class="menu-text">{{ __('messages.create') }} {{ __('messages.user') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-title">{{ __('messages.reports') }}</li>

                <li class="menu-item">
                    <a href="{{ route('backend.reports.daily.all') }}" class="menu-link {{ request()->routeIs('backend.reports.daily.all') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-clipboard-notes"></i></span>
                        <span class="menu-text"> {{ __('messages.daily_reports') }} </span>
                    </a>
                </li>

                <li class="menu-title">{{ __('messages.profile') }}</li>

                <li class="menu-item">
                    <a href="{{ route('profile.edit') }}" class="menu-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-user"></i></span>
                        <span class="menu-text"> {{ __('messages.profile') }} </span>
                    </a>
                </li>

                <li class="menu-item">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form-admin">
                        @csrf
                        <a href="#" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                            <span class="menu-icon"><i class="uil uil-sign-out-alt"></i></span>
                            <span class="menu-text"> {{ __('messages.logout') }} </span>
                        </a>
                    </form>
                </li>

                <li class="menu-title">{{ __('messages.post_completion') }}</li>
                <li class="menu-item">
                    <a href="{{ route('backend.warranty-issues.projects') }}" class="menu-link {{ request()->routeIs('backend.warranty-issues.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-shield-check"></i></span>
                        <span class="menu-text">{{ __('messages.warranty_issues') }}</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('backend.maintenance-schedules.projects') }}" class="menu-link {{ request()->routeIs('backend.maintenance-schedules.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-wrench"></i></span>
                        <span class="menu-text">{{ __('messages.maintenance_schedules') }}</span>
                    </a>
                </li>
                <li class="menu-title">{{ __('messages.leads') }}</li>
                <li class="menu-item">
                    <a href="{{ route('backend.contacts.index') }}" class="menu-link {{ request()->routeIs('backend.contacts.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-envelope"></i></span>
                        <span class="menu-text">{{ __('messages.contact_messages') }}</span>
                    </a>
                </li>
            @else
                <li class="menu-title">{{ __('messages.dashboard') }}</li>
                <li class="menu-item">
                    <a href="{{ route('dashboard') }}" class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-estate"></i></span>
                        <span class="menu-text">{{ __('messages.dashboard') }}</span>
                    </a>
                </li>
                <li class="menu-title">{{ __('messages.profile') }}</li>
                <li class="menu-item">
                    <a href="{{ route('profile.edit') }}" class="menu-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="uil uil-user"></i></span>
                        <span class="menu-text">{{ __('messages.profile') }}</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
<!-- Sidebar Menu End  -->
