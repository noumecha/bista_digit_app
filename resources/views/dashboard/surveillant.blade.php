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
                                    <h5 class="card-title text-white">Incidents Totaux</h5>
                                    <p class="card-text display-4">{{ $stats['total'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Avertissements</h5>
                                    <p class="card-text display-4">{{ $stats['warnings'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-danger mb-3">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Exclusions</h5>
                                    <p class="card-text display-4">{{ $stats['exclusions'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Quick Stats and Alerts -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0 text-white">État Disciplinaire</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-primary">
                                        <i class="fas fa-users me-2"></i>
                                        <strong>{{ $stats['studentsWithoutDiscipline'] }} élèves</strong> sans incident ce mois
                                    </div>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-clock me-2"></i>
                                        <strong>{{ $stats['retards'] }} heures</strong> de retards cumulés
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0 text-white">Répartition par Type</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="disciplineTypeChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Charts Section -->
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0 text-white">Tendance Mensuelle</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="monthlyTrendsChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0 text-white">Incidents par Classe</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="byClasseChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Incidents récents -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0 text-white">Incidents Récents</h5>
                                </div>
                                <div class="card-body">
                                    @if($recentDisciplines->isEmpty())
                                        <div class="alert alert-success">Aucun incident récent - bonne conduite!</div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Élève</th>
                                                        <th>Classe</th>
                                                        <th>Type</th>
                                                        <th>Détails</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentDisciplines as $discipline)
                                                    <tr class="{{ $discipline->decision === 'exclusion' ? 'table-danger' :
                                                        ($discipline->avertissement ? 'table-warning' : '') }}">
                                                        <td>{{ $discipline->created_at->format('d/m/Y') }}</td>
                                                        <td>{{ $discipline->eleve->name }}</td>
                                                        <td>{{ $discipline->classe->libClasse }}</td>
                                                        <td>
                                                            @if($discipline->decision === 'exclusion')
                                                                <span class="badge bg-danger">Exclusion</span>
                                                            @elseif($discipline->avertissement)
                                                                <span class="badge bg-warning">Avertissement</span>
                                                            @else
                                                                <span class="badge bg-secondary">Incident</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ !empty($discipline->decision) ? Str::limit($discipline->decision, 50) : "-" }}</td>
                                                        <td>
                                                            <a href="#"
                                                               class="btn btn-sm btn-primary">
                                                                Voir
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <a href="{{ route('education.discipline') }}" class="btn btn-primary mt-2">
                                            Voir tous les incidents
                                        </a>
                                    @endif
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
        // Discipline Type Chart (Doughnut)
        const typeCtx = document.getElementById('disciplineTypeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($chartData['byType'])) !!},
                datasets: [{
                    data: {!! json_encode(array_values($chartData['byType'])) !!},
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(23, 162, 184, 0.8)'
                    ],
                    borderColor: [
                        'rgba(220, 53, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(23, 162, 184, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Monthly Trends Chart
        const trendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['monthlyTrends']['labels']) !!},
                datasets: [{
                    label: 'Incidents disciplinaires',
                    data: {!! json_encode(array_values($chartData['monthlyTrends']['data'])) !!},
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // By Classe Chart
        const classeCtx = document.getElementById('byClasseChart').getContext('2d');
        new Chart(classeCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['byClasse']->pluck('classe')) !!},
                datasets: [{
                    label: 'Nombre d\'incidents',
                    data: {!! json_encode($chartData['byClasse']->pluck('count')) !!},
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>
    @endsection
</x-app-layout>