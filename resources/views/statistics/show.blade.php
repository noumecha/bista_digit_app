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
                        Statistiques
                    </h2>
                    <p class="lead">
                        Consultez les résultats trimestriels et le classement OBC de notre établissement
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Main Content -->
    <section class="py-5 bg-light">
        <div class="container">
            <!-- OBC Ranking Section -->
            <div class="card shadow-lg mb-5">
                <div class="card-header bg-success text-white">
                    <h2 class="h5 mb-0">Classement OBC</h2>
                </div>
                <div class="card-body">
                    <form class="mb-4" id="obcFilterForm">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="annee_scolaire_id" id="annee_scolaire_id" class="form-select">
                                    @foreach($obcStats as $stat)
                                        <option value="{{ $stat->anneescolaire->id }}"
                                            {{ $selectedOBC && $selectedOBC->anneescolaire->id == $stat->anneescolaire->id ? 'selected' : '' }}>
                                            Année scolaire {{ $stat->anneescolaire->libelleAnneeScolaire }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="obc" value="1" class="d-none">
                            </div>
                        </div>
                    </form>
                    <div class="row d-none" id="obcLoader" style="text-align: center;">
                        <div class="col-12 mt-2">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="obcRanksTable">
                    </div>
                </div>
            </div>
            <!-- Trimestrial Stats Section -->
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">
                        Résultats Trimestriels
                    </h2>
                </div>
                <div class="card-body">
                    <form class="mb-4" id="statsDataSearch">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <select name="trimestre_id" class="form-select">
                                    <option value="">Tous les trimestres</option>
                                    @foreach($trimestres as $trimestre)
                                        <option value="{{ $trimestre->id }}" {{ request('trimestre_id') == $trimestre->id ? 'selected' : '' }}>
                                            {{ $trimestre->libelleTrimestre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <select name="classe_id" class="form-select">
                                    <option value="">Toutes les classes</option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                            {{ $classe->libClasse }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </div>
                    </form>
                    <div class="row d-none" id="loader" style="text-align: center;">
                        <div class="col-12 mt-2">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="statsDatas">
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-front-layout>