<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
            <div class="py-4 container-fluid">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>
                            Devoir : {{ $devoir->titre_devoir }};
                            Classe : {{ $devoir->classe->libClasse }};
                            Matière : {{ $devoir->matiere->libelleMatiere }}
                        </h3>
                        <span class="badge bg-{{ $devoir->statut === "terminé" ? 'success' : 'warning' }}">
                            {{ $devoir->statut === "terminé" ? 'terminé' : 'en cours' }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3 mt-1">
                                <div class="card text-white bg-primary">
                                    <div class="card-body">
                                        <h5 class="card-title text-white">Totals élèves</h5>
                                        <p class="card-text display-4">{{ $totalStudents }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mt-1">
                                <div class="card text-white bg-secondary">
                                    <div class="card-body">
                                        <h5 class="card-title text-white">Participants</h5>
                                        <p class="card-text display-4">{{ $devoir->results()->count() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mt-1">
                                <div class="card text-white bg-success">
                                    <div class="card-body">
                                        <h5 class="card-title text-white">Devoirs complétés</h5>
                                        <p class="card-text display-4">{{ $completedCount }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mt-1">
                                <div class="card text-white bg-info">
                                    <div class="card-body">
                                        <h5 class="card-title text-white">Taux de complétion</h5>
                                        <p class="card-text display-4">{{ number_format($completionRate, 1) }}%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="align-middle text-center bg-transparent border-bottom">
                                            Élève
                                        </th>
                                        <th class="align-middle text-center bg-transparent border-bottom">
                                            Statut
                                        </th>
                                        <th class="align-middle text-center bg-transparent border-bottom">
                                            Score
                                        </th>
                                        <th class="align-middle text-center bg-transparent border-bottom">
                                            Pourcentage
                                        </th>
                                        <th class="align-middle text-center bg-transparent border-bottom">
                                            Commencé le
                                        </th>
                                        <th class="align-middle text-center bg-transparent border-bottom">
                                            Terminé le
                                        </th>
                                        <th class="align-middle text-center bg-transparent border-bottom">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- @ f oreach($devoir->results()->with('user')->get() as $result) -->
                                    @foreach($students as $student)
                                    @php
                                        $result = $student->devoirResults->first();
                                    @endphp
                                    <tr>
                                        <td class="align-middle text-center bg-transparent border-bottom">
                                            {{ $student->name }}
                                        </td>
                                        <td class="align-middle text-center bg-transparent border-bottom">
                                            @if($result && $result->completed_at)
                                                <span class="badge bg-success">Terminé</span>
                                            @elseif($result)
                                                <span class="badge bg-warning">En cours ({{ $result->answers()->count() }}/{{ $devoir->questions()->count() }})</span>
                                            @else
                                                <span class="badge bg-secondary">Non traité</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center bg-transparent border-bottom">
                                            {{ $result->score ?? 0 }} / {{ $result->total_questions ?? 0 }}
                                        </td>
                                        <td class="align-middle text-center bg-transparent border-bottom">
                                            @if($result && $result->completed_at)
                                                {{ $result->score ?? 0 }} / {{ $result->total_questions ?? 0 }} ({{ $result->percentage ? number_format($result->percentage, 1) : 0.0}}%)
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="align-middle text-center bg-transparent border-bottom">
                                            {{ $result && $result->started_at ? formatDate($result->started_at, 'd/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="align-middle text-center bg-transparent border-bottom">
                                            {{ $result && $result->completed_at ? formatDate($result->completed_at, 'd/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="{{ $result === null ? "disabled" : "" }} align-middle text-center bg-transparent border-bottom">
                                            <a href="{{ $result ? route('devoirs.teacher.results', [$devoir, $result->user->id]) : "#" }}"
                                               class="">
                                               <i class="fa-solid fa-circle-info"></i>
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