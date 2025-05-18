<header>
    <div class="my-nav">
        <div class="container">
            <div class="row">
                <div class="nav-items">
                    <div class="menu-toggle"></div>
                    <div class="logo text-white">
                        <a class="h4 text-white" href={{ route('home.index') }}>POWEREDUCATION</a>
                        <!--img src="../assets/front/images/logo-01.png"-->
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
                                <li><a href="{{ route('home.epreuves') }}">Epreuves</a></li>
                                <li>
                                    @if (Auth::check())
                                        <a href="{{ route('dashboard') }}">Dashboard</a>
                                    @else
                                        <a href="{{ route('sign-in') }}">Connexion</a>
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
