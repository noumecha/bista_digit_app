
<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
            <div class="container">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ $devoir->titre_devoir }}</h3>
                        <span class="badge bg-{{ $devoir->is_published ? 'success' : 'warning' }}">
                            {{ $devoir->is_published ? 'Publié' : 'Brouillon' }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card text-white bg-primary">
                                    <div class="card-body">
                                        <h5 class="card-title">Participants</h5>
                                        <p class="card-text display-4">{{ $devoir->results()->count() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-info">
                                    <div class="card-body">
                                        <h5 class="card-title">Moyenne</h5>
                                        <p class="card-text display-4">{{ number_format($devoir->results()->avg('percentage'), 1) }}%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-{{ $devoir->dateDeFin > now() ? 'success' : 'secondary' }}">
                                    <div class="card-body">
                                        <h5 class="card-title">Statut</h5>
                                        <p class="card-text">
                                            {{ $devoir->dateDeFin > now() ? 'En cours' : 'Terminé' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Élève</th>
                                        <th>Statut</th>
                                        <th>Score</th>
                                        <th>Pourcentage</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($devoir->results()->with('user')->get() as $result)
                                    <tr>
                                        <td>{{ $result->user->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $result->completed_at ? 'success' : 'warning' }}">
                                                {{ $result->completed_at ? 'Terminé' : 'En cours' }}
                                            </span>
                                        </td>
                                        <td>{{ $result->score }} / {{ $result->total_questions }}</td>
                                        <td>{{ number_format($result->percentage, 1) }}%</td>
                                        <td>
                                            <a href="{{ route('devoirs.teacher.results', [$devoir, $result]) }}"
                                               class="btn btn-sm btn-info">
                                                Détails
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <x-app.footer />
    </main>
    @section('scripts')
    @endsection
</x-app-layout>