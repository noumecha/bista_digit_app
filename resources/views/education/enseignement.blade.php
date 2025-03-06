<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center success-message">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des Enseignants et leur classes</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer l'attribution des classes aux enseignant
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-enseignement-modal"
                                    >
                                        <i class="fa-solid fa-book-open-reader me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterEnseignementForm">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="searchTeacher" id="searchTeacher" class="form-control" placeholder="Rechercher par nom, prenom de l'enseignant"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select name="classeFilter" class="form-select" id="classeFilter">
                                            <option value="">Toutes les classes</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}">
                                                    {{ $classe->libClasse }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select name="matiereFilter" class="form-select" id="matiereFilter">
                                            <option value="">Toutes les matieres</option>
                                            @foreach ($matieres as $matiere)
                                                <option value="{{ $matiere->id }}">
                                                    {{ $matiere->libelleMatiere }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="enseignementsTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for create or update enseignement -->
        <div class="modal fade" id="create-enseignement-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form  enctype="multipart/form-data" role="form" id="enseignementForm" class="form row">
                    @csrf
                    <input type="hidden" name="enseignementId" id="enseignementId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-enseignement-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-enseignement-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="enseignant_matiere_id" class="form-control-label">
                                            Selectionner l'enseignant :
                                        </label>
                                        <select name="enseignant_matiere_id" id="enseignant_matiere_id" class="form-select">
                                        @foreach ($enseignantsMatieres as $enseignantMatiere)
                                            <option value="{{ $enseignantMatiere->id }}">
                                                {{ $enseignantMatiere->enseignant->name }} ({{ $enseignantMatiere->matiere->libelleMatiere }})
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="classe_id" class="form-control-label">
                                            Selectionnez la classe :
                                        </label>
                                        <select name="classe_id" id="classe_id" class="form-select">
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}">{{ $classe->libClasse }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="active_year_id" class="form-control-label d-none">
                                            Année :
                                        </label>
                                        <input type="hidden" class="form-control" id="active_year_id" name="active_year_id" value="{{ $activeYear->id }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-enseignement-form-button" class="spinner-submit-enseignement-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-enseignement-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/enseignement.js') }}"></script>
    @endsection
</x-app-layout>
