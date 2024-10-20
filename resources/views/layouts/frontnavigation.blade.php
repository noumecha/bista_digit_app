<header>
    <div class="my-nav">
        <div class="container">
            <div class="row">
                <div class="nav-items">
                    <div class="menu-toggle"></div>
                    <div class="logo text-white">
                        <h2>
                            <a class="text-white" href={{ route('home.index') }}>BISTA</a>
                        </h2>
                        <!--img src="../assets/front/images/logo-01.png"-->
                    </div>
                    <div class="menu-items">
                        <div class="menu">
                            <ul>
                                <li><a href="{{ route ('home.index') }}">Acceuil</a></li>
                                <li><a href="{{ route ('home.about') }}">A propos</a></li>
                                <li>
                                    <a href="{{ route ('home.actus') }}">Actualités</a>
                                    <ul class="submenu">
                                        @php
                                            $categories = \App\Models\CategorieActualite::all();
                                        @endphp
                                        @foreach ($categories as $cat)
                                            <li>
                                                <a href="{{ route('home.showCategorie', $cat->id) }}">
                                                    {{ $cat->libelleCategorie }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ route ('home.programmes') }}">Programmes</a>
                                    <ul class="submenu">
                                        <li><a href="#">Booster</a></li>
                                        <li><a href="#">I Am A Leader</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ route ('home.clubs') }}">Clubs</a>
                                    <ul class="submenu">
                                        <li><a href="#">Club Santé</a></li>
                                        <li><a href="#">Club Journal</a></li>
                                        <li><a href="#">Club Musique</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('home.epreuves') }}">Epreuves</a></li>
                                <li><a href="{{ route('sign-in') }}">Connexion</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
