@if (empty($epreuves->items()))
    <div class="col-12 mt-5">
        <h3 class="text-center">
            Aucune épreuve trouvée ...
        </h3>
    </div>
@else
    @foreach ($epreuves as $epreuve)
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <article class="_lk_bg_sd_we">
                <div class="_bv_xs_we" style="background:url({{ $epreuve->isImage ? asset('storage/' . $epreuve->fichier) : '' }})">
                    @if (!$epreuve->isImage)
                        <iframe class="h-100 w-100" src="{{ asset('storage/'.$epreuve->fichier) }}#page=1" frameborder="0"></iframe>
                    @endif
                </div>
                <div class="_xs_we_er">
                    <div class="_he_w">
                        <h5>
                            <a class="title h5 text-uppercase" href="{{ route('home.showepreuve', $epreuve->id) }}">
                                {{ $epreuve->matiere->libelleMatiere }} : {{ $epreuve->libelleEpreuve }}-{{ $epreuve->classe->libClasse }}
                            </a>
                        </h5>
                        <ol class="mt-3">
                            <li><span>Par</span>{{ $epreuve->user->name }}<span class="_mn_cd_xs"><i>le {{ date('d M Y', strtotime($epreuve->created_at)) }}</i></span></li>
                        </ol>
                        <a class="mt-3 btn btn-primary text-white"  download="{{ $epreuve->fichier }}" href="{{ asset('storage/' . $epreuve->fichier) }}">
                            Télécharger
                        </a>
                    </div>
                </div>
            </article>
        </div>
    @endforeach
@endif