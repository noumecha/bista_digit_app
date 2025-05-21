@if (empty($actualites->items()))
    <div class="col-12 mt-5">
        <h3 class="text-center">
            Aucune actulité trouvée ...
        </h3>
    </div>
@else
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
                                {!! Str::limit(strip_tags($actualite->contenu) , $limit=70, $end="...") !!}
                                <a class="" href="{{ route('actualites.show', $actualite->id) }}">
                                    lire plus
                                </a>
                            </p>
                    </div>
                </div>
            </article>
        </div>
    @endforeach
@endif
<div class="d-flex justify-content-center">
    {{ $actualites->appends(request()->query())->links() }}
</div>