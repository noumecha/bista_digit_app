<x-front-layout>
    <section id="carouselExampleFade" class="carousel slide carousel-fade slider" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('storage/' . $actualite->image) }}" class="d-block" alt="...">
                <div class="carousel-caption">
                    <h2>Actualité</h2>
                    <p>
                        {{ $actualite->categorieActualite->libelleCategorie }}
                        <i class="fas fa-angle-right"></i>
                        <span>{{ $actualite->titre }}</span>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="se-001">
        <div class="container">
            <div class="row">
               <div class="col-12">
                    <div class="text-justify heading">
                        <p class="">
                            {!! $actualite->contenu !!}
                        </p>
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
