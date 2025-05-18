<x-front-layout>
    <!-- ==== image === -->
    <section id="carouselExampleFade" class="carousel slide carousel-fade slider">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('storage/' . $specialite->specialite_image) }}" class="d-block" alt="...">
                <div class="carousel-caption">
                    <h2> {{ $specialite->specialite_title }} </h2>
                </div>
            </div>
        </div>
    </section>
    <!-- ==== contenu === -->
    <section class="bg-04">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="heading" style="text-align: justify;">
                        <p class="" id="contenu">
                            {!! $specialite->contenu !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==== slider === -->
    <div id="carouselExampleIndicators" class="carousel slide slider" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach ($specialite->sliders as $slider)
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $loop->index }}"
                    class="{{ $loop->first ? 'active' : "" }}" aria-current="true" aria-label="Slide {{ $loop->index + 1 }}">
                </button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @if (empty($specialite->sliders))
                <div class="carousel-item active">
                    <img src="{{ asset('storage/'.$specialite->specialite_image) }}" class="d-block" alt="...">
                    <div class="carousel-caption">
                        <h2>
                            {{ $specialite->specialite_title }}
                        </h2>
                    </div>
                </div>
            @else
                @foreach ($specialite->sliders as $slider)
                    <div class="carousel-item {{ $loop->first ? 'active' : "" }}" data-bs-interval="5000">
                        <img src="{{ asset('storage/' . $slider["image"]) }}" class="d-block" alt="...">
                        <div class="carousel-caption">
                            <h1>
                                {{ $slider["title"] }}
                            </h1>
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
    <!-- ====================== Featured started====================== -->
    <section class="bg-02">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="heading">
                        <h2>BIEN PLUS ENCORE</h2>
                    </div>
                </div>
                @if ($specialites->isEmpty())
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
                    @foreach ($specialites as $specialite)
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="featured-box">
                                <div class="feature-card">
                                    <a href="{{ route('home.specialitePage', $specialite->id) }}">
                                        <i class="fa-solid fa-link"></i>
                                    </a>
                                    <img style="height: 300px;
                                        width: 100%;
                                        object-fit: cover;"
                                        src="{{ asset('storage/'.$specialite->specialite_image) }}"
                                    >
                                </div>
                                <div class="content">
                                    <h3 class="text-uppercase">
                                        <a href="{{ route('home.specialitePage', $specialite->id) }}">
                                            {{ $specialite->specialite_title }}
                                        </a>
                                    </h3>
                                    <p class="" style="text-align: justify;">
                                        {!! Str::limit(strip_tags($specialite->contenu), $limit=200, $end="...") !!}
                                        <a href="{{ route('home.specialitePage', $specialite->id) }}">
                                            Lire la suite
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
    <!-- ====================== Blog Section started====================== -->
    <section class="bg-04">
        <div class="container">
            <div class="row">
               <div class="col-12">
                    <div class="heading">
                        <h2>Dernières actualités</h2>
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