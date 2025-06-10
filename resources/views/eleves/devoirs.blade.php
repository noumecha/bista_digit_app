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
                                    <h5 class="">Liste des devoirs créer par les enseignants</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les devoirs (Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-devoir-modal"
                                    >
                                        <i class="fa-solid fa-book me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterDevoirForm">
                                <div class="col-md-12 mb-4">
                                    <div class="input-group">
                                        <input type="text" name="searchDevoir" id="searchDevoir" class="form-control" placeholder="Rechercher par titre"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
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
                                        <select name="classeFilter" id="classeFilter" class="form-select">
                                            <option value="">Toutes les classes</option>
                                        @foreach ($classes as $classe)
                                            <option value="{{ $classe->id }}" class="">{{ $classe->libClasse }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="devoirsTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for create or update devoir -->
        <div class="modal fade" id="create-devoir-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl">
                <form enctype="multipart/form-data" role="form" id="devoirForm" class="form row">
                    @csrf
                    <input type="hidden" name="devoirId" id="devoirId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-devoir-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-devoir-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="ol-md-12">
                                    <div class="form-group">
                                        <label for="titre_devoir" class="form-control-label">
                                            Ajouter le titre du devoir :
                                        </label>
                                        <input type="text" placeholder="Entrez le titre du devoir" class="form-control" id="titre_devoir" name="titre_devoir">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="content" class="form-control-label">
                                            Ajouter une Description du devoir :
                                        </label>
                                        <textarea
                                            name="content"
                                            id="content"
                                            placeholder="Entrez la description du devoir"
                                            cols="12"
                                            rows="20">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            Selectionnez la classe :
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
                                        <label class="form-control-label">
                                            Selectionnez la matiere :
                                        </label>
                                        <select name="matiere_id" id="matiere_id" class="form-select">
                                            <option value="">Toutes les matieres</option>
                                        @foreach ($matieres as $matiere)
                                            <option value="{{ $matiere->id }}" class="">{{ $matiere->libelleMatiere }}</option>
                                        @endforeach
                                        </select>
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
                            <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-devoir-form-button" class="spinner-submit-devoir-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-devoir-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/devoir.js') }}"></script>
    @endsection
</x-app-layout>
