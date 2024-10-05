<x-front-layout>
    <section id="carouselExampleFade" class="carousel slide carousel-fade slider" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('storage/' . $actualite->image) }}" class="d-block" alt="...">
                <div class="carousel-caption">
                    <h2>Actualité > </h2>
                    <p>
                        Actualités
                        <i class="fas fa-angle-right"></i>
                        <span>{{ $actualite->titre }}</span>
                    </p>
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
