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
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
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
                            <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                            <div class="row">
                                <!-- Devoir input -->
                                <div class="col-md-12 col-lg-12 row">
                                    <h4 class="">Configuration du devoir :</h4>
                                    <div class="form-group col-md-12">
                                        <label for="devoir_titre" class="form-control-label">
                                            Ajouter le titre du devoir :
                                        </label>
                                        <input type="text" placeholder="Entrez le titre du devoir" class="form-control" id="devoir_titre" name="devoir_titre">
                                    </div>
                                    <div class="form-group col-md-6">
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
                                    <div class="form-group col-md-6">
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
                                    <div class="col-md-12 flex-row">
                                        <button type="button" id="submit-devoirSave-form-button" class="spinner-submit-devoirSave-form-button btn btn-lg">
                                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                            Sauvegarder le devoir
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <!-- Questions input -->
                                <div class="col-md-12 col-lg-12 row">
                                    <h4 class="">Configuration des questions : </h4>
                                    <div class="form-group col-md-12">
                                        <label class="form-control-label">
                                            Selectionnez le devoir :
                                        </label>
                                        <select name="devoir_id" id="devoir_id" class="form-select">
                                            <option value="">Toutes les devoirs</option>
                                        @foreach ($matieres as $matiere)
                                            <option value="{{ $matiere->id }}" class="">{{ $matiere->libelleMatiere }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="question" class="form-control-label">
                                                Ajouter une nouvelle Question :
                                            </label>
                                            <textarea
                                                name="question"
                                                id="content"
                                                placeholder="Entrez le texte de la question"
                                                cols="12"
                                                rows="30">
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 flex-row">
                                        <button type="button" id="submit-questionSave-form-button" class="spinner-submit-questionSave-form-button btn btn-lg">
                                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                            Sauvegarder la question
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <!-- Reponses input -->
                                <div class="col-md-12 col-lg-12 row">
                                    <h4 class="">Configuration des réponses : </h4>
                                    <div class="form-group col-md-12">
                                        <label class="form-control-label">
                                            Selectionnez la question :
                                        </label>
                                        <select name="question_id" id="question_id" class="form-select">
                                            <option value="">Toutes les question</option>
                                        @foreach ($matieres as $matiere)
                                            <option value="{{ $matiere->id }}" class="">{{ $matiere->libelleMatiere }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="reponse" class="form-control-label">
                                                Ajouter une nouvelle reponse :
                                            </label>
                                            <input type="text" placeholder="Entrez une reponse pour la question" class="form-control" id="reponse" name="reponse">
                                        </div>
                                    </div>
                                    <div class="col-md-12 flex-row">
                                        <button type="button" id="submit-reponseSave-form-button" class="spinner-submit-reponseSave-form-button btn btn-lg">
                                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                            Sauvegarder la reponse
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <!-- Liste des questions correspondant au devoir -->
                                <div class="col-md-12 col-lg-12">
                                    <h4 class="">Liste des questions de ce devoir :</h4>
                                </div>
                                <!-- autres -->
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
