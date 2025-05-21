<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="_kl_de_w">
                    @if (isset(appConfiguration()->school_logo))
                        <a href="{{ route('home.index') }}">
                            <img src="{{ asset('storage/'.appConfiguration()->school_logo) }}">
                        </a>
                    @else
                        <h3>
                            <a class="h4 text-white" href={{ route('home.index') }}>POWEREDUCATION</a>
                        </h3>
                    @endif
                    @if (appConfiguration() !== null)
                        @if(isset(appConfiguration()->description))
                            <p style="text-align: justify;">
                                {!! Str::limit(strip_tags(appConfiguration()->description) , $limit=200, $end="...") !!}
                                <a href="{{ route('home.about') }}">lire la suite </a>
                            </p>
                        @endif
                    @else
                        <p>
                            Aucune description pour le moment ...
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="_kl_de_w">
                    <h3>Liens rapides</h3>
                    <ol>
                        <li>
                            <i class="fas fa-angle-right"></i>
                            <a class="text-white" href="{{ route ('home.index') }}">Acceuil</a>
                        </li>
                        <li>
                            <i class="fas fa-angle-right"></i>
                            <a class="text-white" href="{{ route ('home.about') }}">Apropos</a>
                        </li>
                        <li>
                            <i class="fas fa-angle-right"></i>
                            <a class="text-white" href="{{ route ('home.actus') }}">Actualités</a>
                        </li>
                        <li>
                            <i class="fas fa-angle-right"></i>
                            <a class="text-white" href="{{ route ('home.about') }}">A propos</a>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="_kl_de_w">
                    <h3>Programmes</h3>
                    <ol>
                        <li>
                            <i class="fas fa-angle-right"></i>
                            <a class="text-white" href="{{ route ('home.programmes') }}">BOOSTER</a>
                        </li>
                        <li>
                            <i class="fas fa-angle-right"></i>
                            <a class="text-white" href="{{ route ('home.programmes') }}">I'AM A LEADER</a>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="col-12">
                <div class="copy-right">
                    <p>© {{ Date('Y') }} All Rights Reserved <a href="#">POWEREDUCATION</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>
