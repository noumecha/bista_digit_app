<x-front-layout>
    <section class="bg-02-a">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="_head_01">
                        <h2 class="title h2 text-uppercase">
                            Acceuil
                        </h2>
                        <p>
                            Epreuves
                            <i class="fas fa-angle-right"></i>
                            <span>{{ $epreuve->matiere->libelleMatiere }} : {{ $epreuve->libelleEpreuve }}</span>
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
                        <p class="text-justify">
                            {#!! $epreuve->contenu !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-front-layout>
