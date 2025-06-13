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
            <div class="row mt-3">
                <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">
                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Élèves</h5>
                                    <p class="card-text display-4">{{ $stats['students'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Enseignants</h5>
                                    <p class="card-text display-4">{{ $stats['teachers'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Classes</h5>
                                    <p class="card-text display-4">{{ $stats['classes'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($alerts['exclusions'] > 0 || $alerts['warnings'] > 0 || $alerts['lateDevoirs'] > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0">Alertes Critiques</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($alerts['exclusions'] > 0)
                                        <div class="col-md-4">
                                            <div class="alert alert-danger">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ $alerts['exclusions'] }} exclusion(s) cette année
                                            </div>
                                        </div>
                                        @endif
                                        @if($alerts['warnings'] > 0)
                                        <div class="col-md-4">
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $alerts['warnings'] }} avertissement(s) cette année
                                            </div>
                                        </div>
                                        @endif
                                        @if($alerts['lateDevoirs'] > 0)
                                        <div class="col-md-4">
                                            <div class="alert alert-info">
                                                <i class="fas fa-clock"></i>
                                                {{ $alerts['lateDevoirs'] }} devoir(s) en retard
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Raccourcis -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="mb-3">Raccourcis Rapides</h4>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('utilisateur.personnels') }}" class="btn btn-primary">
                                    <i class="fas fa-users"></i> Gestion Utilisateurs
                                </a>
                                <a href="{{ route('education.classes') }}" class="btn btn-success">
                                    <i class="fas fa-chalkboard"></i> Gestion Classes
                                </a>
                                <a href="{{ route('education.matiere') }}" class="btn btn-info">
                                    <i class="fas fa-book"></i> Gestion Matières
                                </a>
                                <a href="{{ route('evaluation.evaluations') }}" class="btn btn-warning">
                                    <i class="fas fa-clipboard-check"></i> Gestion Évaluations
                                </a>
                                <a href="{{ route('education.discipline') }}" class="btn btn-danger">
                                    <i class="fas fa-gavel"></i> Discipline
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Charts Section -->
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0 text-white">Répartition des Élèves par Classe</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="studentDistributionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0 text-white">Tendances Disciplinaires</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="disciplineTrendsChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Evaluation Stats -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0 text-white">Statistiques des Évaluations par Trimestre</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Trimestre</th>
                                                    <th>Nombre d'Évaluations</th>
                                                    <th>Moyenne Générale</th>
                                                    <th>Taux de Remplissage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($chartData['evaluationStats'] as $trimestre => $stats)
                                                <tr>
                                                    <td>{{ $trimestre }}</td>
                                                    <td>{{ $stats['count'] }}</td>
                                                    <td class="fw-bold {{ $stats['average'] >= 10 ? 'text-success' : 'text-danger' }}">
                                                        {{ number_format($stats['average'] ?? 0, 2) }}/20
                                                    </td>
                                                    <td>
                                                        <div class="progress" style="width:100% !important; height: 9px !important;">
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                style="width: {{ rand(70, 100) }}%"
                                                                aria-valuenow="{{ rand(70, 100) }}"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                                {{ rand(70, 100) }}%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <x-app.footer />
        </div>
    </main>
    @section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Student Distribution Chart
        const studentCtx = document.getElementById('studentDistributionChart').getContext('2d');
        new Chart(studentCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['studentDistribution']->pluck('classe')) !!},
                datasets: [{
                    label: 'Nombre d\'élèves',
                    data: {!! json_encode($chartData['studentDistribution']->pluck('count')) !!},
                    backgroundColor: 'rgba(78, 115, 223, 0.8)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre d\'élèves'
                        }
                    }
                }
            }
        });

        // Discipline Trends Chart
        const disciplineCtx = document.getElementById('disciplineTrendsChart').getContext('2d');
        new Chart(disciplineCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['disciplineTrends']['labels']) !!},
                datasets: [
                    {
                        label: 'Heures d\'absence',
                        data: {!! json_encode(array_values($chartData['disciplineTrends']['absences'])) !!},
                        borderColor: 'rgba(231, 74, 59, 1)',
                        backgroundColor: 'rgba(231, 74, 59, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Avertissements',
                        data: {!! json_encode(array_values($chartData['disciplineTrends']['warnings'])) !!},
                        borderColor: 'rgba(246, 194, 62, 1)',
                        backgroundColor: 'rgba(246, 194, 62, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre'
                        }
                    }
                }
            }
        });
    });
    </script>
    @endsection
</x-app-layout>