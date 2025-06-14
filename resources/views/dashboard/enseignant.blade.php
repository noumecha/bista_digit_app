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
                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-3">
                                        <a href="{{ route('education.devoirs') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i> Ajouter un devoir
                                        </a>
                                        <a href="{{ route('evaluation.notes') }}" class="btn btn-success">
                                            <i class="fas fa-edit me-2"></i> Remplir une note
                                        </a>
                                        <a href="{{ route('education.epreuves') }}" class="btn btn-info">
                                            <i class="fas fa-upload me-2"></i> Uploader une épreuve
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-gradient-primary text-white shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-sm mb-0">Matières enseignées</p>
                                            <h3 class="mb-0 text-white">{{ count($matieres) }}</h3>
                                        </div>
                                        <i class="fas fa-book fa-2x opacity-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-gradient-success text-white shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-sm mb-0">Classes</p>
                                            <h3 class="mb-0 text-white">{{ count($classes) }}</h3>
                                        </div>
                                        <i class="fas fa-chalkboard fa-2x opacity-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-gradient-warning text-white shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-sm mb-0">Devoirs à controler</p>
                                            <h3 class="mb-0 text-white">{{ count($devoirs) }}</h3>
                                        </div>
                                        <i class="fas fa-tasks fa-2x opacity-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-gradient-info text-white shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-sm mb-0">Notes attribuées</p>
                                            <h3 class="mb-0 text-white">{{ count($recentNotes) }}</h3>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x opacity-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0 text-white">Performance par Matière</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="matiereChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0 text-white">Statut des Devoirs</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="devoirChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Classes et Matières -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0 text-white">Mes Classes</h5>
                                </div>
                                <div class="card-body">
                                    @if($classes->isEmpty())
                                        <div class="alert alert-info">Aucune classe assignée</div>
                                    @else
                                        <div class="list-group">
                                            @foreach($classes as $classe)
                                            <a href="{{ route('classe.show', $classe->id) }}"
                                               class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">{{ $classe->libClasse }}</h6>
                                                    <small>{{ $classe->getStudents()->count() }} élève(s)</small>
                                                </div>
                                                <p class="mb-1">
                                                    @foreach($user->teacherMatieres(getCurrentYear()->id) as $matiere)
                                                        <span class="badge bg-info">
                                                            {{ $matiere->libelleMatiere }}
                                                        </span>
                                                    @endforeach
                                                </p>
                                            </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0 text-white">Mes Matières</h5>
                                </div>
                                <div class="card-body">
                                    @if($matieres->isEmpty())
                                        <div class="alert alert-info">Aucune matière assignée</div>
                                    @else
                                        <div class="row">
                                            @foreach($matieres as $matiere)
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <h6 class="card-title">{{ $matiere->libelleMatiere }}</h6>
                                                        <p class="card-text">
                                                            <small>Classes:
                                                                @foreach($user->teacherClasses(getCurrentYear()->id) as $classe)
                                                                    <span class="badge bg-secondary">{{ $classe->libClasse }}</span>
                                                                @endforeach
                                                            </small>
                                                        </p>
                                                        <a href="#"
                                                           class="btn btn-sm btn-outline-primary">
                                                            Voir détails
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Devoirs à corriger -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0 text-white">Devoirs à Contrôler</h5>
                                </div>
                                <div class="card-body">
                                    @if($devoirs->isEmpty())
                                        <div class="alert alert-success">Aucun devoir à corriger - tout est à jour!</div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Titre</th>
                                                        <th>Matière</th>
                                                        <th>Classe</th>
                                                        <th>Date limite</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($devoirs as $devoir)
                                                    <tr>
                                                        <td>{{ $devoir->titre_devoir }}</td>
                                                        <td>{{ $devoir->matiere->libelleMatiere }}</td>
                                                        <td>{{ $devoir->classe->libClasse }}</td>
                                                        <td class="{{ $devoir->date_fin >= now() ? 'text-danger' : '' }}">
                                                            {{ $devoir->date_fin }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('devoirs.teacher.show', $devoir->id) }}"
                                                               class="btn btn-sm btn-primary">
                                                                Contrôler
                                                            </a>
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
                    <!-- Notes récentes attribuées -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0 text-white">Notes Récentes Attribuées</h5>
                                </div>
                                <div class="card-body">
                                    @if($recentNotes->isEmpty())
                                        <div class="alert alert-info">Aucune note attribuée récemment</div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Élève</th>
                                                        <th>Matière</th>
                                                        <th>Note</th>
                                                        <th>Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentNotes as $note)
                                                    <tr>
                                                        <td>{{ $note->eleve->name }}</td>
                                                        <td>{{ $note->matiere->libelleMatiere }}</td>
                                                        <td class="{{ $note->note >= 10 ? 'text-success' : 'text-danger' }}">
                                                            <strong>{{ $note->note }}/20</strong>
                                                        </td>
                                                        <td>{{ $note->created_at->format('d/m/Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('evaluation.notes') }}"
                                                               class="btn btn-sm btn-outline-secondary">
                                                                Modifier
                                                            </a>
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
                </main>
            </div>
            <x-app.footer />
        </div>
    </main>
    @section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Matiere Performance Chart
        const matiereCtx = document.getElementById('matiereChart').getContext('2d');
        new Chart(matiereCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['matiereStats']->pluck('matiere')) !!},
                datasets: [{
                    label: 'Moyenne',
                    data: {!! json_encode($chartData['matiereStats']->pluck('average')) !!},
                    backgroundColor: 'rgba(78, 115, 223, 0.8)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }, {
                    label: 'Taux de réussite',
                    data: {!! json_encode($chartData['matiereStats']->map(function($item) {
                        return $item['count'] > 0 ? ($item['passed'] / $item['count']) * 100 : 0;
                    })) !!},
                    backgroundColor: 'rgba(28, 200, 138, 0.8)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 1,
                    type: 'line',
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 20,
                        title: {
                            display: true,
                            text: 'Moyenne /20'
                        }
                    },
                    y1: {
                        position: 'right',
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Taux de réussite %'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
        // Devoir Status Chart
        const devoirCtx = document.getElementById('devoirChart').getContext('2d');
        new Chart(devoirCtx, {
            type: 'doughnut',
            data: {
                labels: ['Terminés', 'En cours', 'Total'],
                datasets: [{
                    data: [
                        {{ $chartData['devoirStats']['completed'] }},
                        {{ $chartData['devoirStats']['pending'] }},
                        {{ $chartData['devoirStats']['total'] }}
                    ],
                    backgroundColor: [
                        'rgba(28, 200, 138, 0.8)',
                        'rgba(246, 194, 62, 0.8)',
                        'rgba(78, 115, 223, 0.8)'
                    ],
                    borderColor: [
                        'rgba(28, 200, 138, 1)',
                        'rgba(246, 194, 62, 1)',
                        'rgba(78, 115, 223, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw + ' devoirs';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
    </script>
    @endsection
</x-app-layout>