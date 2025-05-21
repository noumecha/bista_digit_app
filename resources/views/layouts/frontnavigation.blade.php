<header>
    <div class="my-nav">
        <div class="container">
            <div class="row">
                <div class="nav-items">
                    <div class="menu-toggle"></div>
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
                                <li><a href="{{ route ('home.index') }}">Acceuil</a></li>
                                <li><a href="{{ route ('home.about') }}">A propos</a></li>
                                <li>
                                    <a href="{{ route ('home.actus') }}">Actualités</a>
                                </li>
                                <li>
                                    <a href="{{ route ('home.programmes') }}">Programmes</a>
                                    <ul class="submenu">
                                        <li><a href="{{ route('home.boosterPage') }}">Booster</a></li>
                                        <li><a href="{{ route('home.leaderPage') }}">I Am A Leader</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ route ('home.clubs') }}">Clubs</a>
                                </li>
                                <li>
                                    <a href="{{ route('home.epreuves') }}">Epreuves</a>
                                </li>
                                <li>
                                    <a href="{{ route('home.contact') }}">Contact</a>
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
