<x-front-layout>
    <section id="carouselExampleFade" class="carousel slide carousel-fade slider">
        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 75vh">
                <img @if(appConfiguration() !== null && appConfiguration()->school_image !== null) {
                        src="{{ asset('storage/' . appConfiguration()->school_image) }}"
                    }
                    @else {
                        src="{{ asset('front/images/slider/1.jpg') }}"
                    }
                    @endif
                    class="d-block" alt="...">
                <div class="carousel-caption">
                    <h2 class="text-uppercase">
                        Résultats du {{ $publishedStat->trimestre->libelleTrimestre }} de la classe de {{ $publishedStat->classe->libClasse }}
                    </h2>
                    <p>
                        Publié le {{ $publishedStat->created_at->format('d/m/Y à H:i') }} par {{ $publishedStat->publisher->name }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Rang</th>
                                    <th>Matricule</th>
                                    <th>Nom & Prénom</th>
                                    <th>Moyenne</th>
                                    <th>Appréciation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bulletins as $bulletin)
                                <tr>
                                    <td>
                                        {{ $bulletin->range }}<sup>{{ $bulletin->range === 1 ? "er" : "ème" }}</sup>
                                    </td>
                                    <td>
                                        {{ $bulletin->student->matricule }}
                                    </td>
                                    <td>
                                        {{ $bulletin->student->name }} {{ $bulletin->student->surname }}
                                    </td>
                                    <td class="fw-bold {{ $bulletin->average >= 10 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($bulletin->average, 2) }}/20
                                    </td>
                                    <td>
                                        {{ $bulletin->appreciation }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('home.stats') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Retour aux statistiques
                        </a>
                        <a href="#" class="btn d-none btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i> Imprimer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-front-layout>