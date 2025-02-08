<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            @if (session('deleteSuccess'))
                            <div class="row alert alert-success text-center" id="success-message">
                                {{ session('deleteSuccess') }}
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des matières et de leur configuration par classe</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer la configuration des matières (Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-coefficient-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterCoefficientForm">
                                <div class="col-md-6">
                                    <label for="searchCoef" class="form-control-label">
                                        Valeur du coefficient :
                                    </label>
                                    <div class="input-group">
                                        <input type="number" name="searchCoef" id="searchCoef" class="form-control" placeholder="Rechercher par valeur"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="matiereFilter" class="form-control-label">
                                            Matiere :
                                        </label>
                                        <select name="matiereFilter" id="matiereFilter" class="form-select">
                                            <option value="">Toutes les matières</option>
                                        @foreach ($matieres as $mat)
                                            <option value="{{ $mat->id }}" class="">{{ $mat->libelleMatiere }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="classeFilter" class="form-control-label">
                                            Classe :
                                        </label>
                                        <select name="classeFilter" id="classeFilter" class="form-select">
                                            <option value="">Toutes les classes</option>
                                        @foreach ($classes as $classe)
                                            <option value="{{ $classe->id }}" class="">{{ $classe->libClasse }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="groupFilter" class="form-control-label">
                                            Groupe de la matière :
                                        </label>
                                        <select name="groupFilter" id="groupFilter" class="form-select">
                                            <option value="">Tout les groupes</option>
                                            @foreach (\App\GroupeMatiere::cases() as $groupe)
                                                <option value="{{ $groupe->value }}">{{ $groupe->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="coefficientsTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for create or update coefficient -->
        <div class="modal fade" id="create-coefficient-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form  enctype="multipart/form-data" role="form" id="coefficientForm" class="form row">
                    @csrf
                    <input type="hidden" name="coefficientId" id="coefficientId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-coefficient-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-coefficient-text" class="text-white"></h5>
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
                                        <label for="matiere_id" class="form-control-label">
                                            Matiere :
                                        </label>
                                        <select name="matiere_id" id="matiere_id" class="form-select">
                                            <option value="">Toutes les matières</option>
                                        @foreach ($matieres as $mat)
                                            <option value="{{ $mat->id }}" class="">{{ $mat->libelleMatiere }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="classe_id" class="form-control-label">
                                            Classe :
                                        </label>
                                        <select name="classe_id" id="classe_id" class="form-select">
                                            <option value="">Toutes les classes</option>
                                        @foreach ($classes as $classe)
                                            <option value="{{ $classe->id }}" class="">{{ $classe->libClasse }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="groupe_matiere" class="form-control-label">
                                            Groupe de la matière :
                                        </label>
                                        <select name="groupe_matiere" id="groupe_matiere" class="form-select">
                                                <option value="">Tout les groupes</option>
                                            @foreach (\App\GroupeMatiere::cases() as $groupe)
                                                <option value="{{ $groupe->value }}">{{ $groupe->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="coefficient" class="form-control-label">
                                            Coefficient :
                                        </label>
                                        <input type="number" id="coefficient" name="coefficient" class="form-control" value="{{ old("coefficient") }}" aria-label="Name"
                                            aria-describedby="name-addon">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="active_year_id" class="form-control-label d-none">
                                            Anneé :
                                        </label>
                                        <input type="hidden" class="form-control" id="active_year_id" name="active_year_id" value="{{ $activeYear->id }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-coefficient-form-button" class="spinner-submit-coefficient-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-coefficient-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/coefficients.js') }}"></script>
    @endsection
</x-app-layout>
