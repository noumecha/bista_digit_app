<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0 text-white">Notes booster de : {{ $user->name }} {{ $user->surname }}</h4>
                                <span class="badge bg-white text-primary">
                                    Programme Booster - {{ $user->getCurrentYearClasseName(getCurrentYear()->id)->libClasse }}
                                </span>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="card-body border-bottom">
                            <form method="GET" action="{{ route('student.boosternotes') }}" class="row g-3">
                                <div class="col-md-4">
                                    <label for="booster_matiere_id" class="form-label">Matière Booster</label>
                                    <select class="form-select" id="booster_matiere_id" name="booster_matiere_id">
                                        <option value="">Toutes les matières</option>
                                        @foreach($matieres as $matiere)
                                            <option value="{{ $matiere->id }}" {{ $selectedMatiere == $matiere->id ? 'selected' : '' }}>
                                                {{ $matiere->matiere->libelleMatiere }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="evaluation_id" class="form-label">Évaluation Booster</label>
                                    <select class="form-select" id="evaluation_id" name="evaluation_id">
                                        <option value="">Toutes les évaluations</option>
                                        @foreach($evaluations as $evaluation)
                                            <option value="{{ $evaluation->id }}" {{ $selectedEvaluation == $evaluation->id ? 'selected' : '' }}>
                                                {{ $evaluation->libelleEvaluation }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="trimester" class="form-label">Trimestre</label>
                                    <select class="form-select" id="trimester" name="trimester">
                                        <option value="">Tous les trimestres</option>
                                        @foreach($trimestres as $trimestre)
                                            <option value="{{ $trimestre->id }}" {{ $selectedTrimester == $trimestre->id ? 'selected' : '' }}>
                                                {{ $trimestre->libelleTrimestre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">Filtrer</button>
                                    @if($selectedMatiere || $selectedEvaluation || $selectedTrimester)
                                        <a href="{{ route('student.boosternotes') }}" class="btn btn-outline-secondary ms-2">
                                            Réinitialiser
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>

                        <!-- Statistics -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light-primary h-100">
                                        <div class="card-body text-center">
                                            <h6 class="card-subtitle mb-1">Moyenne Générale</h6>
                                            <h3 class="card-title mb-0">
                                                {{ number_format($notes->avg('note'), 2) }}/20
                                            </h3>
                                            <small class="text-muted">sur {{ $notes->count() }} notes</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light-success h-100">
                                        <div class="card-body text-center">
                                            <h6 class="card-subtitle mb-1">Meilleure Note</h6>
                                            <h3 class="card-title mb-0">
                                                {{ $notes->max('note') ?? '-' }}/20
                                            </h3>
                                            @if($notes->isNotEmpty())
                                                <small class="text-muted">
                                                    en {{ $notes->sortByDesc('note')->first()->matiere->matiere->libelleMatiere }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light-danger h-100">
                                        <div class="card-body text-center">
                                            <h6 class="card-subtitle mb-1">Note la plus basse</h6>
                                            <h3 class="card-title mb-0">
                                                {{ $notes->min('note') ?? '-' }}/20
                                            </h3>
                                            @if($notes->isNotEmpty())
                                                <small class="text-muted">
                                                    en {{ $notes->sortBy('note')->first()->matiere->matiere->libelleMatiere }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart -->
                        @if($notesByTrimester->isNotEmpty())
                        <div class="card-body border-top">
                            <h5 class="mb-3">Évolution par trimestre</h5>
                            <div style="height: 300px;">
                                <canvas id="boosterNotesChart"></canvas>
                            </div>
                        </div>
                        @endif

                        <!-- Notes Table -->
                        <div class="card-body">
                            <h5 class="mb-3">Détail des notes Booster</h5>

                            @if($notes->isEmpty())
                                <div class="alert alert-info">Aucune note Booster trouvée pour les filtres sélectionnés.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Matière Booster</th>
                                                <th>Évaluation</th>
                                                <th>Trimestre</th>
                                                <th>Note</th>
                                                <th>Appréciation</th>
                                                <th>Classe Moy.</th>
                                                <th>Min/Max</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($notes as $note)
                                            <tr>
                                                <td>{{ $note->evaluation->dateDeDebut }}</td>
                                                <td>{{ $note->matiere->matiere->libelleMatiere }}</td>
                                                <td>{{ $note->evaluation->libelleEvaluation }}</td>
                                                <td>{{ $note->evaluation->trimestre->libelleTrimestre ?? '-' }}</td>
                                                <td class="fw-bold {{ $note->note >= 10 ? 'text-success' : 'text-danger' }}">
                                                    {{ $note->note }}/20
                                                </td>
                                                <td>{{ $note->appreciation ?? '-' }}</td>
                                                <td>{{ round($note->gcma, 2) ?? '-' }}/20</td>
                                                <td>
                                                    {{ $note->min_value ?? '-' }}-{{ $note->max_value ?? '-' }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
    @if($notesByTrimester->isNotEmpty())
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('boosterNotesChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($notesByTrimester->keys()) !!},
                    datasets: [{
                        label: 'Moyenne par trimestre (Booster)',
                        data: {!! json_encode($notesByTrimester->values()) !!},
                        backgroundColor: 'rgba(255, 193, 7, 0.2)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            max: 20,
                            title: {
                                display: true,
                                text: 'Note /20'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toFixed(2) + '/20';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endif
    @endsection
</x-app-layout>