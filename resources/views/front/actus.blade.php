<x-front-layout>
    <section class="bg-02-a">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="_head_01">
                        <h2>Actualités</h2>
                        <p>Acceuil<i class="fas fa-angle-right"></i><span>Articles & Publication</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-04">
        <div class="container">
            <div class="row">
               <div class="col-12">
                    <div class="heading">
                        <h2>Les dernières actualités du collège</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($actualites as $actualite)
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <article class="_lk_bg_sd_we">
                        <div class="_bv_xs_we" style="background:url({{ asset('storage/' . $actualite->image) }})"></div>
                        <div class="_xs_we_er">
                            <div class="_he_w">
                                <h5>
                                    <a class="title h5 text-uppercase" href="{{ route('actualites.show', $actualite->id) }}">
                                        {{ $actualite->titre }}
                                    </a>
                                </h5>
                                <ol>
                                    <li><span>Par</span>admin<span class="_mn_cd_xs"><i>le {{ date('d M Y', strtotime($actualite->created_at)) }}</i></span></li>
                                </ol>
                                <p>
                                    {!! Str::limit($actualite->contenu , $limit=300, $end="...") !!}
                                </p>
                    </div>
                        </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-front-layout>
