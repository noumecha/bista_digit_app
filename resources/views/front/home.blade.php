<x-front-layout>
    <div id="carouselExampleIndicators" class="carousel slide slider" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach ($sliders as $slider)
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $loop->index }}"
                    class="{{ $loop->first ? 'active' : "" }}" aria-current="true" aria-label="Slide {{ $loop->index + 1 }}">
                </button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @if ($sliders->isEmpty())
                <div class="carousel-item active">
                    <img src="{{ asset('front/images/slider/1.jpg') }}" class="d-block" alt="...">
                    <div class="carousel-caption">
                        <h2>
                            Pour une meilleure éducation
                        </h2>
                        <p>
                            Implication de la technologie dans l'enseignement
                        </p>
                    </div>
                </div>
            @else
                @foreach ($sliders as $slider)
                    <div class="carousel-item {{ $loop->first ? 'active' : "" }}" data-bs-interval="5000">
                        <img src="{{ asset('storage/' . $slider->slider_image) }}" class="d-block" alt="...">
                        <div class="carousel-caption">
                            <h1>
                                {{ $slider->slider_title }}
                            </h1>
                            <p>
                                {{ $slider->slider_text }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
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
    <!-- ============ Programme et clubs ============ -->
    <section class="bg-02">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="heading">
                        <h2 class="text-uppercase">programmes & clubs</h2>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    @if (isset($club))
                        <div class="featured-box">
                            <div class="feature-card">
                                <a href="{{ route('home.clubPage', $club->id) }}">
                                    <i class="fa-solid fa-link"></i>
                                </a>
                                <img style="height: 300px;
                                    width: 100%;
                                    object-fit: cover;" src="{{ asset('storage/'.$club->club_image) }}">
                            </div>
                            <div class="content">
                                <h3 class="text-uppercase">
                                    <a href="{{ route('home.clubPage', $club->id) }}">
                                        {{ $club->club_name }}
                                    </a>
                                </h3>
                                <p class="" style="text-align: justify;">
                                    {!! Str::limit(strip_tags($club->contenu), $limit=200, $end="...") !!}
                                    <a href="{{ route('home.clubPage', $club->id) }}">
                                        Lire la suite
                                    </a>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    @if (isset($leader))
                        <div class="featured-box">
                            <div class="feature-card">
                                <a href="#"><i class="fa-solid fa-link"></i></a>
                                <img style="height: 300px;
                                    width: 100%;
                                    object-fit: cover;" src="{{ asset('storage/'.$leader->specialite_image) }}">
                            </div>
                            <div class="content">
                                <h3 class="text-uppercase">
                                    {{ $leader->specialite_title }}
                                </h3>
                                <p class="" style="text-align: justify;">
                                    {!! Str::limit(strip_tags($leader->contenu), $limit=200, $end="...") !!}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    @if (isset($booster))
                        <div class="featured-box">
                            <div class="feature-card">
                                <a href="#"><i class="fa fa-link"></i></a>
                                <img style="height: 300px;
                                    width: 100%;
                                    object-fit: cover;" src="{{ asset('storage/'.$booster->specialite_image) }}">
                            </div>
                            <div class="content">
                                <h3 class="text-uppercase">
                                    {{ $booster->specialite_title }}
                                </h3>
                                <p class="" style="text-align: justify;">
                                    {!! Str::limit(strip_tags($booster->contenu), $limit=200, $end="...") !!}
                                </p>
                            </div>
                        </div>
                    @endif
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
