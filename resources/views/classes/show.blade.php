<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6>Détail de la classe de : {{ $classe->libClasse }}</h6>
                            <span class="badge bg-gradient-{{ $classe->section->color ?? 'primary' }}">
                                Section : {{ $classe->section->libelleSection ?? 'No Section' }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group">
                                        <li class="list-group-item border-0 ps-0 pt-0 text-sm">
                                            <strong class="text-dark">Cycle :</strong> &nbsp; {{ $classe->cycleClasse }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0 text-sm">
                                            <strong class="text-dark">Année scolaire :</strong> &nbsp; {{ getCurrentYear()->libelleAnneeScolaire }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0 text-sm">
                                            <strong class="text-dark">Effectif :</strong> &nbsp;
                                            {{ $classe->effectif->getEffectif() ?? '0' }} élève(s)
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-gradient-{{ $classe->section->color ?? 'primary' }} text-white">
                                        <div class="card-body p-3">
                                            <div class="text-center">
                                                <h4 class="mb-0 text-white"> Statistiques rapipdes </h4>
                                                <hr class="bg-white">
                                                <div class="row">
                                                    <div class="col-6 border-end">
                                                        <p class="mb-0 text-white"> Matières </p>
                                                        <h3 class="mb-0 text-white">{{ $classe->getMatieres()->count() }}</h3>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0 text-white"> Enseignant </p>
                                                        <h3 class="mb-0 text-white">{{ $classe->getTeachers()->count() }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Matières de la classe</h6>
                        </div>
                        <div class="card-body p-3">
                            @if($classe->getMatieres()->count() > 0)
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Matiere </th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Coefficient </th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Enseignant </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($classe->getMatieres() as $matiere)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $matiere->libelleMatiere }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-gradient-info">{{ $matiere->getCoef($classe->id) }}</span>
                                                    </td>
                                                    <td>
                                                        {{ $matiere->getTeacher($classe->id) ? $matiere->getTeacher($classe->id) : "non assigné" }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Aucune matiere configurer pour cette classe
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6>Elèves de la classe : ({{ $classe->getStudents()->count() }})</h6>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            @if($classe->getStudents()->count() > 0)
                                <div class="list-group">
                                    @foreach($classe->getStudents()->take(5) as $student)
                                        <div class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                            <div class="avatar me-3">
                                                <img src="{{ $student->profile ? asset('storage/'.$student->profile) : asset('assets/img/default-avatar.png') }}"
                                                     alt="{{ $student->name }}" class="border-radius-lg shadow" width="48">
                                            </div>
                                            <div class="d-flex align-items-start flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $student->name }}</h6>
                                                <p class="mb-0 text-xs">{{ $student->matricule ?? 'No matricule' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($classe->getStudents()->count() > 5)
                                    <div class="text-center mt-2">
                                        <small class="text-muted">+{{ $classe->getStudents()->count() - 5 }} Plus d'élèves</small>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info text-white">
                                    Aucun élève dans cette classe
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Activités récentes</h6>
                            <div class="d-flex">
                                <a href="{{ route('education.devoirs') }}" class="btn btn-sm btn-outline-primary me-2">
                                    Devoirs ({{ $classe->devoirs->count() }})
                                </a>
                                <a href="{{ route('education.epreuves') }}" class="btn btn-sm btn-outline-secondary">
                                    Epreuves ({{ $classe->epreuves->count() }})
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
</x-app-layout>