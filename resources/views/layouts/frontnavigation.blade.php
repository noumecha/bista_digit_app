<header>
    <div class="my-nav">
        <div class="container">
            <div class="row">
                <div class="nav-items">
                    <div class="menu-toggle"></div>
                    <div class="logo text-white">
                        <h2>
                            <a class="decoration-none" href={{ route('home.index') }}>BISTA</a>
                        </h2>
                        <!--img src="../assets/front/images/logo-01.png"-->
                    </div>
                    <div class="menu-items">
                        <div class="menu">
                            <ul>
                                <li><a href="{{ route ('home.index') }}">Acceuil</a></li>
                                <li><a href="{{ route ('home.about') }}">A propos</a></li>
                                <li>
                                    <a href="#">Actualités</a>
                                    <ul class="submenu">
                                        <li><a href="#">Activités pédagogiques</a></li>
                                        <li><a href="#">Activités Sportives</a></li>
                                        <li><a href="#">Activités extra-scolaire</a></li>
                                        <li><a href="#">Fondation ISHAN</a></li>
                                        <li><a href="#">Programme Iam A Leader</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">Programmes</a>
                                    <ul class="submenu">
                                        <li><a href="#">Booster</a></li>
                                        <li><a href="#">I Am A Leader</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('sign-in') }}">Connexion</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
