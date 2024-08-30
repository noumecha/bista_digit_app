<header>
    <div class="my-nav">
        <div class="container">
            <div class="row">
                <div class="nav-items">
                    <div class="menu-toggle"></div>
                    <div class="logo text-white">
                        <h2>
                            BISTA
                        </h2>
                        <!--img src="../assets/front/images/logo-01.png"-->
                    </div>
                    <div class="menu-items">
                        <div class="menu">
                            <ul>
                                <li><a href="{{ route ('home.index') }}">Acceuil</a></li>
                                <li><a href="{{ route ('home.about') }}">A propos</a></li>
                                <li><a href="#">Actualités</a></li>
                                <li><a href="#">Programmes</a></li>
                                <li><a href="#">Activités</a></li>
                                <li><a href="{{ route('sign-in') }}">Connexion</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
