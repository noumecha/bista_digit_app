<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
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
                                    <h5 class="">Liste des élèves du programme</h5>
                                    <p class="text-sm">
                                        Gérer les élèves du programme(Ajouter, Supprimer)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-boosterstudent-modal"
                                    >
                                        <i class="fa-solid fa-dna me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterBoosterMatiereForm">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="searchText" value=""
                                            id="searchText" class="form-control" placeholder="Rechercher par nom"/>
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
                            </form>
                        </div>
                        <div class="table-responsive" id="boosterStudentsTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a boosterstudent datas -->
        <div class="modal fade" id="create-boosterstudent-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="boosterStudentForm" class="form row">
                    @csrf
                    <input type="hidden" name="boosterStudentId" id="boosterStudentId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-boosterstudent-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-boosterstudent-text" class="text-white"></h5>
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
                                            Elèves :
                                        </label>
                                        <select name="user_id" id="user_id" class="form-select">
                                            <option value="">Ajouter un élève</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}">
                                                    {{ $student->name }}
                                                </option>
                                            @endforeach
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
                            <button type="button" id="submit-boosterstudent-form-button" class="spinner-submit-boosterstudent-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-boosterstudent-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/booster-student.js') }}"></script>
    @endsection
</x-app-layout>
