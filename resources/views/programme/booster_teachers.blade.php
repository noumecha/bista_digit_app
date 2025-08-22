<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center success-message">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des Enseignants du programme</h5>
                                    <p class="text-sm">
                                        Gérer les enseignants du programme(Ajouter, Supprimer)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-boosterteacher-modal"
                                    >
                                        <i class="fa-solid fa-dna me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterBoosterTeacherForm">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="searchText" value=""
                                            id="searchText" class="form-control"
                                            placeholder="Rechercher l'enseignant par nom ou prénom"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="classeFilter" id="classeFilter" class="form-select">
                                            <option value="">Toutes les classes</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}">
                                                    {{ $classe->libClasse }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="matiereFilter" id="matiereFilter" class="form-select">
                                            <option value="">Toutes les matieres</option>
                                            @foreach ($matieres as $matiere)
                                                <option value="{{ $matiere->id }}">
                                                    {{ $matiere->matiere->libelleMatiere }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="boosterTeachersTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a boosterteacher datas -->
        <div class="modal fade" id="create-boosterteacher-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="boosterTeacherForm" class="form row">
                    @csrf
                    <input type="hidden" name="boosterTeacherId" id="boosterTeacherId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-boosterteacher-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-boosterteacher-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="user_id" class="form-control-label">
                                            Enseignants :
                                        </label>
                                        <select name="user_id" id="user_id" class="form-select">
                                            <option value="">Choisir un enseignant</option>
                                            @foreach ($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="classe_id" class="form-control-label">
                                            Classes :
                                        </label>
                                        <select name="classe_id" id="classe_id" class="form-select">
                                            <option value="">Attribuer une classe</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}">
                                                    {{ $classe->libClasse }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="booster_matiere_id" class="form-control-label">
                                            Matieres :
                                        </label>
                                        <select name="booster_matiere_id" id="booster_matiere_id" class="form-select">
                                            <option value="">Attribuer une matiere</option>
                                        </select>
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
                            <button type="button" id="submit-boosterteacher-form-button" class="spinner-submit-boosterteacher-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-boosterteacher-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/booster-teachers.js') }}"></script>
    @endsection
</x-app-layout>
