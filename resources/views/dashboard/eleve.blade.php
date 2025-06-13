<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-3 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">
                                {{ Date('H') >= 00 && Date('H') <= 15 ? 'Bonjour' : 'Bonsoir'  }},
                                {{ Auth::user()->name }}
                            </h3>
                            <p class="mb-0">Ravie de vous revoir</p>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-0">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- En-tête avec infos étudiant -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <p class="mb-1">
                                        <strong>Classe : </strong> {{ $classe->libClasse ?? 'Non assigné' }}
                                    </p>
                                    <p class="mb-0">
                                        <strong>Année scolaire :</strong> {{ $currentYear->libelleAnneeScolaire }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="alert alert-info mb-0">
                                        <strong>Moyenne Générale des notes :</strong>
                                        {{ number_format($user->notes()->avg('note'), 2) }}/20
                                    </div>
                                </div>
                            </div>
                            <!-- Statistiques rapides -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-light-success">
                                        <div class="card-body text-center">
                                            <h6 class="card-subtitle mb-1">Meilleure Note</h6>
                                            <h3 class="card-title mb-0">
                                                {{ $user->notes()->max('note') ?? '-' }}/20
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light-danger">
                                        <div class="card-body text-center">
                                            <h6 class="card-subtitle mb-1">Note la plus basse</h6>
                                            <h3 class="card-title mb-0">
                                                {{ $user->notes()->min('note') ?? '-' }}/20
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light-warning">
                                        <div class="card-body text-center">
                                            <h6 class="card-subtitle mb-1">Absences</h6>
                                            <h3 class="card-title mb-0">
                                                {{ $disciplineStats['absences'] }}h
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- bulletin statistics -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <div class="justify-content-between align-items-center">
                                        <h5 class="mb-0 text-white">Statistiques des Bulletins</h5>
                                        <div>
                                            <form id="bulletinFilterForm" class="row mt-2 g-2">
                                                <div class="col-auto">
                                                    <select name="trimestre_id" class="form-select form-select-sm">
                                                        <option value="">Tous les trimestres</option>
                                                        @foreach($trimestres as $trimestre)
                                                            <option value="{{ $trimestre->id }}" {{ request('trimestre_id') == $trimestre->id ? 'selected' : '' }}>
                                                                {{ $trimestre->libelleTrimestre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-auto">
                                                    <select name="evaluation_id" class="form-select form-select-sm">
                                                        <option value="">Toutes les évaluations</option>
                                                        @foreach($evaluations as $evaluation)
                                                            <option value="{{ $evaluation->id }}" {{ request('evaluation_id') == $evaluation->id ? 'selected' : '' }}>
                                                                {{ $evaluation->libelleEvaluation }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-sm btn-light">Filtrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($bulletins->isEmpty())
                                        <div class="alert alert-info">Aucun bulletin disponible</div>
                                    @else
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="chart-container" style="height: 300px;">
                                                    <canvas id="bulletinEvolutionChart"></canvas>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="chart-container" style="height: 300px;">
                                                    <canvas id="bulletinComparisonChart"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Trimestre</th>
                                                                <th>Évaluation</th>
                                                                <th>Ma Moyenne</th>
                                                                <th>Moy. Classe</th>
                                                                <th>Min/Max</th>
                                                                <th>Rang</th>
                                                                <th>Taux Réussite</th>
                                                                <th>Appréciation</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($bulletins as $bulletin)
                                                            <tr>
                                                                <td>{{ $bulletin->trimestre->libelleTrimestre ?? "-" }}</td>
                                                                <td>{{ $bulletin->evaluation->libelleEvaluation ?? "-" }}</td>
                                                                <td class="fw-bold {{ $bulletin->average >= 10 ? 'text-success' : 'text-danger' }}">
                                                                    {{ number_format($bulletin->average, 2) }}/20
                                                                </td>
                                                                <td>{{ number_format($bulletin->general_average, 2) }}/20</td>
                                                                <td>
                                                                    {{ number_format($bulletin->min_average, 2) }} -
                                                                    {{ number_format($bulletin->max_average, 2) }}
                                                                </td>
                                                                <td>{{ $bulletin->range }}/{{ $bulletin->classe->students()->count() }}</td>
                                                                <td>{{ number_format($bulletin->getWinPercent(), 1) }}%</td>
                                                                <td>{{ $bulletin->appreciation }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- Dernières notes et prochains devoirs -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0 text-white">Dernières Notes</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($latestNotes->isEmpty())
                                                <div class="alert alert-info">Aucune note récente</div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Matière</th>
                                                                <th>Note</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($latestNotes as $note)
                                                            <tr>
                                                                <td>{{ $note->matiere->libelleMatiere }}</td>
                                                                <td class="{{ $note->note >= 10 ? 'text-success' : 'text-danger' }}">
                                                                    <strong>{{ $note->note }}/20</strong>
                                                                </td>
                                                                <td>{{ $note->created_at->format('d/m/Y') }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <a href="{{ route('student.notes') }}" class="btn btn-sm btn-info mt-2">
                                                    Voir toutes mes notes
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-warning text-dark">
                                            <h5 class="mb-0 text-white">Prochains Devoirs</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($upcomingDevoirs->isEmpty())
                                                <div class="alert alert-info">Aucun devoir à venir</div>
                                            @else
                                                <div class="list-group">
                                                    @foreach($upcomingDevoirs as $devoir)
                                                    <a href="{{ route('education.devoirs') }}"
                                                       class="list-group-item list-group-item-action">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">{{ $devoir->matiere->libelleMatiere }}</h6>
                                                            <small>{{ $devoir->date_fin }}</small>
                                                        </div>
                                                        <p class="mb-1">{{ $devoir->titre }}</p>
                                                        <small>À terminé avant le {{ $devoir->date_fin }}</small>
                                                    </a>
                                                    @endforeach
                                                </div>
                                                <a href="{{ route('education.devoirs') }}" class="btn btn-sm btn-warning mt-2">
                                                    Voir tous les devoirs
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Matières -->
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0 text-white">Mes Matières</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($matieres as $matiere)
                                        <div class="col-md-4 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $matiere->libelleMatiere }}</h6>
                                                    <p class="card-text mb-1">
                                                        <small>Enseignant: {{ $matiere->getTeacher($classe->id) }}</small>
                                                    </p>
                                                    <p class="card-text mb-1">
                                                        <small>Coefficient: {{ $matiere->getCoef($classe->id) }}</small>
                                                    </p>
                                                    <p class="card-text">
                                                        <small>Moyenne:
                                                            {{ number_format($user->notes()->where('matiere_id', $matiere->id)->avg('note'), 2) }}/20
                                                        </small>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>
    @section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Evolution Chart
        const evolutionCtx = document.getElementById('bulletinEvolutionChart').getContext('2d');
        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($bulletinStats['labels']) !!},
                datasets: [
                    {
                        label: 'Ma Moyenne',
                        data: {!! json_encode($bulletinStats['averages']) !!},
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Moyenne de Classe',
                        data: {!! json_encode($bulletinStats['classAverages']) !!},
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.05)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false
                    }
                ]
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
                }
            }
        });
        // Comparison Chart
        const comparisonCtx = document.getElementById('bulletinComparisonChart').getContext('2d');
        new Chart(comparisonCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($bulletinStats['labels']) !!},
                datasets: [
                    {
                        label: 'Ma Moyenne',
                        data: {!! json_encode($bulletinStats['averages']) !!},
                        backgroundColor: '#4e73df'
                    },
                    {
                        label: 'Minimum Classe',
                        data: {!! json_encode($bulletinStats['minAverages']) !!},
                        backgroundColor: '#e74a3b'
                    },
                    {
                        label: 'Maximum Classe',
                        data: {!! json_encode($bulletinStats['maxAverages']) !!},
                        backgroundColor: '#1cc88a'
                    }
                ]
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
                }
            }
        });
        // Filter form submission
        document.getElementById('bulletinFilterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            // You can implement AJAX filtering here or reload the page
            window.location.href = window.location.pathname + '?' + params;
        });
    });
    </script>
    @endsection
</x-app-layout>