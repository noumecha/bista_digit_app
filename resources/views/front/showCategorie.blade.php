<!-- resources/views/categories/show.blade.php -->

<x-front-layout>
    <section class="bg-02-a">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="_head_01">
                        <h2>{{ $category->libelleCategorie }}</h2>
                        <p>Acceuil<i class="fas fa-angle-right"></i><span>{{ $category->libelleCategorie }}</span></p>
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
                        <h2>Articles dans la catégorie "{{ $category->libelleCategorie }}"</h2>
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
                                        <li><span>Par</span>{{ $actualite->user->name }}<span class="_mn_cd_xs"><i>le {{ date('d M Y', strtotime($actualite->created_at)) }}</i></span></li>
                                    </ol>
                                    <p>
                                        {!! Str::limit($actualite->contenu, 70, '...') !!}
                                    </p>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $actualites->links() }}
            </div>
        </div>
    </section>
</x-front-layout>
