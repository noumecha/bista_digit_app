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
                                    <h5 class="">Données disciplinaires par élève par classe</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer la discipline(Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-discipline-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterDisciplineForm">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="searchDiscipline" value="" id="searchDiscipline" class="form-control" placeholder="Rechercher par élèves, par total d'abscence"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="classeFilter" id="classeFilter" class="form-select">
                                            <option value="">Toutes les classes</option>
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
                                        <select id="monthFilter" name="monthFilter" class="form-select">
                                            <option value="">Tout les mois</option>
                                            <option value="1">Septembre</option>
                                            <option value="2">Octobre</option>
                                            <option value="3">Novembre</option>
                                            <option value="4">Décembre</option>
                                            <option value="5">Janvier</option>
                                            <option value="6">Février</option>
                                            <option value="7">Mars</option>
                                            <option value="8">Avril</option>
                                            <option value="9">Mai</option>
                                            <option value="10">Juin</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="disciplinesTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a discipline datas -->
        <div class="modal fade" id="create-discipline-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="disciplineForm" class="form row">
                    @csrf
                    <input type="hidden" name="disciplineId" id="disciplineId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-discipline-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-discipline-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="classe_id" id="classe_id" class="form-select">
                                            <option value="">Toutes les classes</option>
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
                                            <option value="">Sélectionner Un élève</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select name="mois" id="mois" class="form-select">
                                            <option value="">Selectionner le mois</option>
                                            <option value="1">Septembre</option>
                                            <option value="2">Octobre</option>
                                            <option value="3">Novembre</option>
                                            <option value="4">Décembre</option>
                                            <option value="5">Janvier</option>
                                            <option value="6">Février</option>
                                            <option value="7">Mars</option>
                                            <option value="8">Avril</option>
                                            <option value="9">Mai</option>
                                            <option value="10">Juin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="heures_absence" class="form-control-label">
                                            Total heures d'abscence :
                                        </label>
                                        <input type="number" id="heures_absence" name="heures_absence" class="form-control" value="{{ old("heures_absence") }}" aria-label="Name"
                                            aria-describedby="name-addon">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="heures_justifiees" class="form-control-label">
                                            Heures d'abscence justifiées :
                                        </label>
                                        <input type="number" id="heures_justifiees" name="heures_justifiees" class="form-control" value="{{ old("heures_justifiees") }}" aria-label="Name"
                                            aria-describedby="name-addon">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" id="decision_container">
                                        <label for="decision" class="form-control-label">
                                            Entrez la décision :
                                        </label>
                                        <textarea name="decision" class="form-control" id="decision" rows="3">
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
                            <button type="button" id="submit-discipline-form-button" class="spinner-submit-discipline-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-discipline-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/discipline.js') }}"></script>
    @endsection
</x-app-layout>
