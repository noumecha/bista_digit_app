<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center success-message" id="">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Conseils de discipline par élève par évaluation</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les conseils de discipline(Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-conseildiscipline-modal"
                                    >
                                        <i class="fa-solid fa-clock me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterConseilDisciplineForm">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="searchText" value=""
                                            id="searchText" class="form-control"
                                            placeholder="Rechercher par élèves (nom, prenom)"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="evaluationFilter" id="evaluationFilter" class="form-select">
                                            <option value="">Toutes les évalutations</option>
                                            @foreach($evaluations as $evaluation)
                                                <option value="{{ $evaluation->id }}">
                                                    {{ $evaluation->libelleEvaluation }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select id="monthFilter" name="monthFilter" class="form-select">
                                            <option value="">Tout les mois</option>
                                            @foreach (getAllSchoolMonths() as $month)
                                                <option value="{{ $month->format("m") }}">
                                                    {{ monthNameToFrench($month->format("m")) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="conseildisciplinesTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a discipline datas -->
        <div class="modal fade" id="create-conseildiscipline-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="conseilDisciplineForm" class="form row">
                    @csrf
                    <input type="hidden" name="conseilDisciplineId" id="conseilDisciplineId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-conseil-discipline-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-conseil-discipline-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select name="evaluation_id" id="evaluation_id" class="form-select">
                                            <option value="">Selectionnez une évaluation</option>
                                            @foreach($evaluations as $evaluation)
                                                <option value="{{ $evaluation->id }}">
                                                    {{ $evaluation->libelleEvaluation }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="classe_id" id="classe_id" class="form-select">
                                            <option value="">Selectionnez une classe</option>
                                            @foreach($classes as $classe)
                                                <option value="{{ $classe->id }}">
                                                    {{ $classe->libClasse }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select id="user_id" name="user_id" class="form-select">
                                            <option value="">Sélectionnez Un élève</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mois" class="form-control-label">
                                            Mois de discipline :
                                        </label>
                                        <select name="mois" id="mois" class="form-select">
                                            <option value="">Selectionnez un mois</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_conseil" class="form-control-label">
                                            Date du conseil de discipline :
                                        </label>
                                        <input type="date" id="date_conseil"
                                            name="date_conseil" class="form-control"
                                            value="{{old("date_conseil")}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="date_conseil" class="form-control-label">
                                            Motif du conseil de discipline :
                                        </label>
                                        <input type="text" name="motif" value=""
                                            id="motif" class="form-control"
                                            placeholder="Entrez le motif du conseil de discipline"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" id="decision_container">
                                        <label for="decision" class="form-control-label">
                                            Entrez la décision :
                                        </label>
                                        <textarea placeholder="Entrez le rapport détaillée du conseil de discipline"
                                            name="decision" class="form-control" id="decision" rows="3">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="annee_scolaire_id" class="form-control-label d-none">
                                            Anneé :
                                        </label>
                                        <input type="hidden" class="form-control" id="annee_scolaire_id" name="annee_scolaire_id" value="{{ $activeYear->id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-conseil-discipline-form-button" class="spinner-submit-conseil-discipline-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-conseil-discipline-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/conseil-disciplines.js') }}"></script>
    @endsection
</x-app-layout>
