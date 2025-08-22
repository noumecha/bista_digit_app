<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="py-4 container-fluid">
            <div class="row">
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
                                    <h5 class="">Liste des Enseignants(es) principaux(les)</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez définir l'enseignant principal pour chaque classe
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-enseignantprincipal-modal"
                                    >
                                        <i class="fa-solid fa-person-chalkboard me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterEnseignantPrincipalForm">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="searchTeacher" id="searchTeacher" class="form-control" placeholder="Rechercher par nom, prenom de l'enseignant"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                            </form>
                        </div>
                        <div class="table-responsive" id="enseignantPrincipalsTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for create or update enseignant principal -->
        <div class="modal fade" id="create-enseignantprincipal-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form  enctype="multipart/form-data" role="form" id="enseignantPrincipalForm" class="form row">
                    @csrf
                    <input type="hidden" name="enseignantPrincipalId" id="enseignantPrincipalId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-enseignantprincipal-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-enseignantprincipal-text" class="text-white"></h5>
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="classe_id" class="form-control-label">
                                            Selectionnez la classe :
                                        </label>
                                        <select name="classe_id" id="classe_id" class="form-select">
                                            <option value="">Selectionnez une classe</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}">{{ $classe->libClasse }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="user_id" class="form-control-label">
                                            Selectionner l'enseignant :
                                        </label>
                                        <select name="user_id" id="user_id" class="form-select">
                                            <option value="">Aucune classe selectionnée !</option>
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
                            <button type="button" id="submit-enseignantprincipal-form-button" class="spinner-submit-enseignantprincipal-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-enseignantprincipal-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/enseignantprincipals.js') }}"></script>
    @endsection
</x-app-layout>
