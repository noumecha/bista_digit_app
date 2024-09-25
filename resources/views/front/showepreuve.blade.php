<x-front-layout>
    <section class="bg-02-a">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="_head_01">
                        <h2 class="title h2 text-uppercase">
                            Epreuves
                        </h2>
                        <p>
                            Epreuve
                            <i class="fas fa-angle-right"></i>
                            <span>{{ $epreuve->matiere->libelleMatiere }} : {{ $epreuve->libelleEpreuve }}-{{ $epreuve->classe->libClasse }}</span>
                        </p>
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
                        @if (!$epreuve->isImage)
                            <iframe style="height: 800px; width: 100%" src="{{ asset('storage/'.$epreuve->fichier) }}" frameborder="0"></iframe>
                        @else
                            <div class="row justify-content-center">
                                <img style="h-800 w-100" src="{{ asset('storage/'.$epreuve->fichier) }}" alt="epreuve-image"/>
                            </div>
                            <div class="row mt-3 justify-content-center">
                                <a href="{{ asset('storage/'.$epreuve->fichier) }}" download="{{ asset('storage/'.$epreuve->fichier) }}" class="btn btn-primary">
                                    Télécharger
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-front-layout>
