<x-front-layout>
    <section class="bg-02-a">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="_head_01">
                        <h2>A Propos du collège</h2>
                        <p>Acceuil<i class="fas fa-angle-right"></i><span>A propos de nous</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="se-001">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    @if (isset($appconfiguration) && isset($appconfiguration->school_name))
                        <h1>{{ $appconfiguration->school_name }}</h1>
                    @endif
                    <div class="text-justify mt-3">
                        @if (isset($appconfiguration) && isset($appconfiguration->description))
                            {!! $appconfiguration->description !!}
                        @else
                            <p>Aucune description disponible ....</p>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="_Ol_er_qw yu">
                        <img
                            @if (isset($appconfiguration) && isset($appconfiguration->school_logo))
                                src="{{ asset('storage/' . $appconfiguration->school_logo) }}"
                            @else
                                src="{{ asset('front/images/logo.png') }}"
                            @endif
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====================== section started====================== -->
    <section class="bg-01">
         <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="se-box">
                        <div class="icon">
                            <i class="fa-solid fa-chalkboard-teacher"></i>
                        </div>
                        <div class="content">
                            <h3>Enseignants Professionnels</h3>
                            <p>
                                Une équipe d’enseignants qualifiés, fiable, disponibles pour l’encadrement des élèves.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="se-box">
                        <div class="icon">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div class="content">
                            <h3>Laboratoires de pointes</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="se-box">
                        <div class="icon">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div class="content">
                            <h3>Bourses d'études & programmes de soutien</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====================== Featured started====================== -->
    <section class="bg-02">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="heading">
                        <h2>PARTICULARITE DU COLLEGE : {{ $appconfiguration->school_name }}</h2>
                        <p class="">
                            {!! Str::limit(strip_tags($appconfiguration->description), $limit=150, $end="...") !!}
                            <a href="{{ route('home.about') }}">lire la suite</a>
                        </p>
                    </div>
                </div>
                @if ($atouts->isEmpty())
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="content">
                            <h3 class="text-uppercase">
                                Nos atouts
                            </h3>
                            <p class="" style="text-align: center;">
                                Présentation des atouts de notre établissement
                            </p>
                        </div>
                    </div>
                @else
                    @foreach ($atouts as $atout)
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="featured-box">
                                <div class="feature-card">
                                    <a href="{{ route('home.specialitePage', $atout->id) }}">
                                        <i class="fa-solid fa-link"></i>
                                    </a>
                                    <img style="height: 300px;
                                        width: 100%;
                                        object-fit: cover;"
                                        src="{{ asset('storage/'.$atout->specialite_image) }}"
                                    >
                                </div>
                                <div class="content">
                                    <h3 class="text-uppercase">
                                        <a href="{{ route('home.specialitePage', $atout->id) }}">
                                            {{ $atout->specialite_title }}
                                        </a>
                                    </h3>
                                    <p class="" style="text-align: justify;">
                                        {!! Str::limit(strip_tags($atout->contenu), $limit=200, $end="...") !!}
                                        <a href="{{ route('home.specialitePage', $atout->id) }}">
                                            lire la suite
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <!-- ============ Counter section ============ -->
    <section class="bg-03">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div class="_lk_bg_cd">
                        <i class="fa-solid fa-history"></i>
                      <div class="counting" data-count="0">0</div>
                      <h5>ANNEES D'EXPERIENCE</h5>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div class="_lk_bg_cd">
                        <i class="fa-solid fa-users"></i>
                        <div class="counting" data-count="{{ $students }}">0</div>
                        <h5>
                            @if($students < 2)
                                ELEVE
                            @else
                                ELEVES
                            @endif
                        </h5>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div class="_lk_bg_cd">
                        <i class="fa-solid fa-chalkboard-teacher"></i>
                        <div class="counting" data-count="{{ $teachers }}">0</div>
                        <h5>
                            @if($teachers < 2)
                                ENSEIGNANT QUALIFIE
                            @else
                                ENSEIGNANTS QUALIFIES
                            @endif
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====================== Blog Section started====================== -->
    <section class="bg-04">
        <div class="container">
            <div class="row">
               <div class="col-12">
                    <div class="heading">
                        <h2>Nos dernières actualités</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @if ($actualites->isEmpty())
                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
                        <article class="_lk_bg_sd_we">
                            <div class="_xs_we_er">
                                <div class="_he_w">
                                    <h3>
                                        Aucune actualité pour le moment
                                    </h3>
                                </div>
                            </div>
                        </article>
                    </div>
                @else
                    @foreach ($actualites as $actualite)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <article class="_lk_bg_sd_we">
                                <div class="_bv_xs_we" style="
                                    height: 300px;
                                    width: 100%;
                                    object-fit: cover;
                                    background:url({{ asset('storage/'. $actualite->image) }});">
                                </div>
                                <div class="_xs_we_er">
                                    <div class="_he_w">
                                        <h3>
                                            <a href="{{ route('actualites.show', $actualite->id) }}">
                                                {{ $actualite->titre }}
                                            </a>
                                        </h3>
                                        <ol>
                                            <li>
                                                <span>Par</span>{{ $actualite->user->name }}<span class="_mn_cd_xs"><i>le {{ date('d M Y', strtotime($actualite->created_at)) }}</i></span>
                                            </li>
                                        </ol>
                                        <p class="" style="text-align: justify;">
                                            {!! Str::limit(strip_tags($actualite->contenu) , $limit=200, $end="...") !!}
                                            <a href="{{ route('actualites.show', $actualite->id) }}">
                                                lire la suite
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
</x-front-layout>
