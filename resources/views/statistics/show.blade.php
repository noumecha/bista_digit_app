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
                    <form class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="annee_scolaire_id" class="form-select" onchange="this.form.submit()">
                                    @foreach($obcStats as $stat)
                                        <option value="{{ $stat->annee_scolaire_id }}"
                                            {{ $selectedOBC && $selectedOBC->annee_scolaire_id == $stat->annee_scolaire_id ? 'selected' : '' }}>
                                            Année {{ $stat->annee_scolaire_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    @if($selectedOBC)
                        <div class="obc-ranking text-center py-4">
                            <div class="display-2 text-success fw-bold mb-3">
                                {{ $selectedOBC->obc_rank }}<sup>ème</sup>
                            </div>
                            <p class="lead">sur {{ $selectedOBC->data['total_schools'] ?? '1200' }} établissements au Cameroun</p>
                            <div class="progress mt-4" style="height: 20px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ ($selectedOBC->data['total_schools'] - $selectedOBC->obc_rank) / $selectedOBC->data['total_schools'] * 100 }}%"
                                    aria-valuenow="{{ $selectedOBC->obc_rank }}"
                                    aria-valuemin="1"
                                    aria-valuemax="{{ $selectedOBC->data['total_schools'] }}">
                                </div>
                            </div>
                        </div>
                    @endif
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
                    <form class="mb-4">
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
                    @if($publishedStats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Trimestre</th>
                                        <th>Classe</th>
                                        <th>Date Publication</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($publishedStats as $stat)
                                    <tr>
                                        <td>{{ $stat->trimestre->libelleTrimestre }}</td>
                                        <td>{{ $stat->classe->libClasse }}</td>
                                        <td>{{ $stat->published_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('front.statistics.details', $stat->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                                Voir les Résultats
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $publishedStats->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            Aucune statistique publiée pour ces critères
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-front-layout>