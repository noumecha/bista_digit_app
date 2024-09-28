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
                            Actualités
                            <i class="fas fa-angle-right"></i>
                            <span>{{ $actualite->titre }}</span>
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
                    <div class="text-justify heading">
                        <p class="">
                            {!! $actualite->contenu !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-front-layout>
