<!-- Sidebar Menu Start -->
<div class="app-menu">

    <!-- Brand Logo -->
    <a href="{{ route('backend.dashboard') }}" class="logo-box">
        <img src="{{ asset('dash/assets/images/logo-light.png') }}" class="logo-light h-6" alt="Light logo">
        <img src="{{ asset('dash/assets/images/logo-dark.png') }}" class="logo-dark h-6" alt="Dark logo">
    </a>

    <!--- Menu -->
    <div data-simplebar>
        <ul class="menu">
            <li class="menu-title">Menu</li>

            <li class="menu-item">
                <a href="{{ route('backend.dashboard') }}" class="menu-link {{ request()->routeIs('backend.dashboard') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="uil uil-estate"></i></span>
                    <span class="menu-text"> Dashboard </span>
                    <span class="badge bg-primary rounded ms-auto">01</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <span class="menu-icon"><i class="uil uil-hipchat"></i></span>
                    <span class="menu-text"> AI Chat </span>
                </a>
            </li>

            <li class="menu-title">Custom</li>

            <li class="menu-item">
                <a href="javascript:void(0)" data-hs-collapse="#sidenavExtraPages" class="menu-link">
                    <span class="menu-icon"><i class="uil uil-file-plus"></i></span>
                    <span class="menu-text"> Extra Pages </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul id="sidenavExtraPages" class="sub-menu hidden">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Starter</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Invoice</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Maintenance</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0)" data-hs-collapse="#sidenavAuthPages" class="menu-link">
                    <span class="menu-icon"><i class="uil uil-sign-in-alt"></i></span>
                    <span class="menu-text"> Auth Pages </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul id="sidenavAuthPages" class="sub-menu hidden">
                    <li class="menu-item">
                        <a href="{{ route('login') }}" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Log In</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('register') }}" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Register</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('password.request') }}" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Recover Password</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Lock Screen</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0)" data-hs-collapse="#sidenavErrorPages" class="menu-link">
                    <span class="menu-icon"><i class="uil uil-sync-exclamation"></i></span>
                    <span class="menu-text"> Error Pages </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul id="sidenavErrorPages" class="sub-menu hidden">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Error 404</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Error 500</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-title">Elements</li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <span class="menu-icon"><i class="uil uil-apps"></i></span>
                    <span class="menu-text"> Components </span>
                </a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0)" data-hs-collapse="#sidenavForms" class="menu-link">
                    <span class="menu-icon"><i class="uil uil-file-alt"></i></span>
                    <span class="menu-text"> Forms </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul id="sidenavForms" class="sub-menu hidden">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Form Elements</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Masks</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Editor</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Validation</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Form Layout</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0)" data-hs-collapse="#sidenavMaps" class="menu-link">
                    <span class="menu-icon"><i class="uil uil-map-marker"></i></span>
                    <span class="menu-text"> Maps </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul id="sidenavMaps" class="sub-menu hidden">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Vector Maps</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Google Maps</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <span class="menu-icon"><i class="uil uil-th"></i></span>
                    <span class="menu-text"> Tables </span>
                </a>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <span class="menu-icon"><i class="uil uil-chart"></i></i></span>
                    <span class="menu-text"> Chart </span>
                </a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0)" data-hs-collapse="#sidenavLevel" class="menu-link">
                    <span class="menu-icon">
                        <i class="uil uil-share-alt"></i>
                    </span>
                    <span class="menu-text"> Level </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul id="sidenavLevel" class="sub-menu hidden">
                    <li class="menu-item">
                        <a href="javascript: void(0)" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Item 1</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="javascript: void(0)" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Item 2</span>
                            <span class="badge bg-info rounded ms-auto">New</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-title">Account</li>

            <li class="menu-item">
                <a href="{{ route('profile.edit') }}" class="menu-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="uil uil-user"></i></span>
                    <span class="menu-text"> Profile </span>
                </a>
            </li>

            <li class="menu-item">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <a href="#" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="menu-icon"><i class="uil uil-sign-out-alt"></i></span>
                        <span class="menu-text"> Logout </span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</div>
<!-- Sidebar Menu End  -->
