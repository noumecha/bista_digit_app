<x-front-layout>
    <section id="carouselExampleFade" class="carousel slide carousel-fade slider">
        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 75vh">
                <img @if(isset($appconfiguration) && isset($appconfiguration->school_name)) {
                        src="{{ asset('storage/' . $appconfiguration->school_image) }}"
                    }
                    @else {
                        src="{{ asset('front/images/slider/1.jpg') }}"
                    }
                    @endif
                    class="d-block" alt="...">
                <div class="carousel-caption">
                    <h2 class="text-uppercase">
                        contactez-nous
                    </h2>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-02">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="heading">
                        <h2>
                            Nos contacts
                        </h2>
                        @if (appConfiguration() !== null)
                            @if (isset(appConfiguration()->school_phone_1))
                                <p class="text-lowercase">
                                    <i class="fa-solid fa-phone"></i>
                                    {{ appConfiguration()->school_phone_1 }}
                                </p>
                            @endif
                            @if (isset(appConfiguration()->school_phone_2))
                                <p class="text-lowercase">
                                    <i class="fa-solid fa-phone"></i>
                                    {{ appConfiguration()->school_phone_2 }}
                                </p>
                            @endif
                            @if (isset(appConfiguration()->school_email))
                                <p class="text-lowercase">
                                    <i class="fa-solid fa-envelope"></i>
                                    {{ appConfiguration()->school_email }}
                                </p>
                            @endif
                        @else
                            <p>
                                Aucun contact ou adresse e-mail ajouté pour l'instant
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="heading">
                        <h2>Localisation</h2>
                        @if (appConfiguration() !== null)
                            <p>
                                @if (isset(appConfiguration()->school_town))
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ appConfiguration()->school_town }}
                                @endif
                                @if (isset(appConfiguration()->school_location))
                                    - {{ appConfiguration()->school_location }}
                                @endif
                                @if (isset(appConfiguration()->school_postal_box))
                                    - PB. {{ appConfiguration()->school_postal_box }}
                                @endif
                            </p>
                        @else
                            <p>
                                Aucune information de localisation ajoutée
                            </p>
                        @endif
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
