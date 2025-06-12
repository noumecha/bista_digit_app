<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0 text-white">
                                    Etat disciplinaire de :  {{ $user->name }} {{  $user->surname }}
                                </h4>
                                <span class="badge bg-white text-primary">
                                    Classe : {{ $user->getCurrentYearClasseName(getCurrentYear()->id)->libClasse }}
                                </span>
                            </div>
                        </div>
                        <!-- Filters -->
                        <div class="card-body border-bottom">
                            <form method="GET" action="{{ route('student.discipline') }}"
                                class="row g-3">
                                <div class="col-md-6">
                                    <label for="year_id" class="form-label">Annee Scolaire</label>
                                    <select class="form-select" id="year_id" name="year_id">
                                        @foreach($schoolYears as $year)
                                            <option value="{{ $year->id }}" {{ $selectedYear == $year->id ? 'selected' : '' }}>
                                                {{ $year->libelleAnneeScolaire }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="month" class="form-label">Mois</label>
                                    <select class="form-select" id="month" name="month">
                                        <option value="">tout les mois</option>
                                        @foreach (getAllSchoolMonths() as $month)
                                            <option value="{{ $month->format("m") }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                                                {{ monthNameToFrench($month->format("m")) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="submit" class="btn btn-lg btn-primary">Filtrer</button>
                                    @if($selectedMonth || $selectedYear != $currentYear->id)
                                        <a href="{{ route('student.discipline') }}" class="btn btn-lg btn-outline-secondary ms-2">
                                            Effacer
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="card-body">
                            <h5 class="mb-3">Resumé des statistiques</h5>
                            <div class="row">
                                <div class="col-md-4 col-xs-6 mb-3">
                                    <div class="card bg-light-danger text-center">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-1">Total Absences</h6>
                                            <h4 class="card-title mb-0">{{ $totalAbsences }}h</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-6 mb-3">
                                    <div class="card bg-light-success text-center">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-1">Absences justifiées</h6>
                                            <h4 class="card-title mb-0">{{ $totalJustified }}h</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-6 mb-3">
                                    <div class="card bg-light-warning text-center">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-1">Retards</h6>
                                            <h4 class="card-title mb-0">{{ $totalRetards }}h</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-6 mb-3">
                                    <div class="card bg-light-info text-center">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-1">Consignes</h6>
                                            <h4 class="card-title mb-0">{{ $totalConsignes }}h</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-6 mb-3">
                                    <div class="card bg-light-danger text-center">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-1">Exclusions</h6>
                                            <h4 class="card-title mb-0">{{ $totalExclusions }} jour(s)</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-6 mb-3">
                                    <div class="card bg-light-primary text-center">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-1">Conseils de disciplines</h6>
                                            <h4 class="card-title mb-0">{{ $conseils->count() }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Discipline Records -->
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Rapports mensuels</h5>
                                @if($selectedMonth)
                                    <span class="badge bg-primary">
                                        Rapport de : {{ monthNameToFrench($selectedMonth) }}
                                    </span>
                                @endif
                            </div>

                            @if($disciplines->isEmpty())
                                <div class="alert alert-info">Aucun rapports de disciplines trouvés pour les filtres selectionnés</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mois</th>
                                                <th>Absence</th>
                                                <th>Justifié</th>
                                                <th>Retard</th>
                                                <th>Consigne</th>
                                                <th>Exclusion</th>
                                                <th>Avertissement</th>
                                                <th>Blame</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($disciplines as $record)
                                            <tr>
                                                <td>{{ monthNameToFrench($record->mois) }}</td>
                                                <td>{{ $record->heures_absence }}h</td>
                                                <td>{{ $record->heures_justifiees }}h</td>
                                                <td>{{ $record->heures_retards }}h</td>
                                                <td>{{ $record->heures_consignes }}h</td>
                                                <td>{{ $record->jours_exclusions }} jours</td>
                                                <td>
                                                    @if($record->avertissement)
                                                        <span class="badge bg-warning text-dark">Oui</span>
                                                    @else
                                                        <span class="badge bg-secondary">Non</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($record->blame)
                                                        <span class="badge bg-danger">Oui</span>
                                                    @else
                                                        <span class="badge bg-secondary">Non</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Disciplinary Councils -->
                        <div class="card-body border-top">
                            <h5 class="mb-3">Conseils de disciplines</h5>
                            @if($conseils->isEmpty())
                                <div class="alert alert-info">Aucun conseil de discipline trouvé.</div>
                            @else
                                <div class="accordion" id="conseilsAccordion">
                                    @foreach($conseils as $conseil)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $loop->index }}"
                                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                    aria-controls="collapse{{ $loop->index }}">
                                                {{ $conseil->date_conseil->format('d/m/Y') }} - {{ $conseil->motif }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $loop->index }}"
                                             class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                             aria-labelledby="heading{{ $loop->index }}"
                                             data-bs-parent="#conseilsAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Date :</strong> {{ $conseil->date_conseil->format('l, F j, Y') }}</p>
                                                        <p><strong>Motif :</strong> {{ $conseil->motif }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Decision :</strong> {{ $conseil->decision }}</p>
                                                        <p><strong>Annee Scolaire :</strong> {{ $conseil->anneeScolaire->libelleAnneeScolaire }}</p>
                                                    </div>
                                                </div>
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
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
    @endsection
</x-app-layout>