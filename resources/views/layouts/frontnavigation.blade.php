<header>
    <div class="my-nav">
        <div class="container">
            <div class="row">
                <div class="nav-items d-flex flex-row align-items-center justify-content-between"
                    style="
                        display: flex !important;
                        flex-direction: row;
                        align-items: center !important;
                        justify-content: space-between;
                    "    
                >
                    <div class="logo d-flex flex-row justify-content-between align-items-center text-white mt-0">
                        @if(isset(appConfiguration()->school_logo))
                            <a class="" href="{ { route('home.index') }}">
                                <img src="{{ asset('storage/'.appConfiguration()->school_logo) }}"
                                    style="
                                        height: 55px;
                                        width: 55px;
                                    "
                                >
                            </a>
                        @else
                            <a class="h4 text-white" href={{ route('home.index') }}>POWEREDUCATION</a>
                        @endif
                        <!-- mobile menu toggle -->
                        <div class="menu-toggle d-flex aligns-center"
                            style="
                                position: relative !important;
                            "
                        >
                            <i class="text-white fa-solid fa-bars"></i>
                        </div>
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
                                        Evaluations
                                    </a>
                                    <ul class="submenu">
                                        <li>
                                            <a href="{{ route('home.epreuves') }}">
                                                Epreuves
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('home.stats') }}">
                                                Statistiques
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ route('home.contact') }}"
                                        class="{{ request()->routeIs('home.contact') ? 'active-menu' : '' }}"
                                        >
                                        Contact
                                    </a>
                                </li>
                                <li>
                                    @if (Auth::check())
                                        <a class="text-white" title="Administration" href="{{ route('dashboard') }}">
                                            <i class="fa-solid fa-lg fa-circle-user"></i>
                                        </a>
                                    @else
                                        <a class="text-white" title="connexion" href="{{ route('sign-in') }}">
                                            <i class="fa-solid fa-lg fa-right-to-bracket"></i>
                                        </a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
