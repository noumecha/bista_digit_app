<header>
    <div class="my-nav">
        <div class="container">
            <div class="row">
                <div class="nav-items">
                    <div class="menu-toggle d-flex aligns-center"
                        style="padding:10px 15px;
                        margin: 7px;
                        "
                    >
                        <i class="text-white fa-solid fa-bars"></i>
                    </div>
                    <div class="logo text-white">
                        @if (isset(appConfiguration()->school_logo))
                            <a href="{{ route('home.index') }}">
                                <img src="{{ asset('storage/'.appConfiguration()->school_logo) }}">
                            </a>
                        @else
                            <a class="h4 text-white" href={{ route('home.index') }}>POWEREDUCATION</a>
                        @endif
                    </div>
                    <div class="menu-items">
                        <div class="menu">
                            <ul>
                                <li>
                                    <a href="{{ route ('home.index') }}"
                                        class="{{ request()->routeIs('home.index') ? 'active-menu' : '' }}"
                                    >
                                        Acceuil
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route ('home.about') }}"
                                        class="{{ request()->routeIs('home.about') ? 'active-menu' : '' }}"
                                    >
                                        A propos
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route ('home.actus') }}"
                                        class="{{ request()->routeIs('home.actus') ? 'active-menu' : '' }}"
                                        >
                                        Actualités
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route ('home.programmes') }}"
                                        class="{{ request()->routeIs('home.programmes') ? 'active-menu' : '' }}"
                                        >
                                        Programmes
                                    </a>
                                    <ul class="submenu">
                                        <li><a href="{{ route('home.boosterPage') }}">Booster</a></li>
                                        <li><a href="{{ route('home.leaderPage') }}">I Am A Leader</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ route ('home.clubs') }}"
                                        class="{{ request()->routeIs('home.clubs') ? 'active-menu' : '' }}"
                                        >
                                        Clubs
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('home.epreuves') }}"
                                        class="{{ request()->routeIs('home.epreuves') ? 'active-menu' : '' }}"
                                        >
                                        Epreuves
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('home.contact') }}"
                                        class="{{ request()->routeIs('home.contact') ? 'active-menu' : '' }}"
                                        >
                                        Contact
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="login-menu">
                        @if (Auth::check())
                            <a class="text-white" href="{{ route('dashboard') }}">
                                <i class="fa-solid fa-gear"></i>
                            </a>
                        @else
                            <a class="text-white" href="{{ route('sign-in') }}">
                                <i class="fa-solid fa-right-to-bracket"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
