<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center success-message" id="">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des Enseignants et leurs matieres</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer l'attribution des matieres aux enseignants
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-teacherSubject-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterTeacherSubjectsForm">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="searchTeacherSubjects" id="searchTeacherSubjects" class="form-control" placeholder="Rechercher par nom, prenom de l'enseignant"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select name="matiereFilter" class="form-select" id="matiereFilter">
                                            <option value="">Toutes les matieres</option>
                                            @foreach ($matieres as $matiere)
                                                <option value="{{$matiere->id}}" {{ request('matiereFilter') == $matiere->id ? 'selected' : '' }}>
                                                    {{ $matiere->libelleMatiere }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="teachersSubjectsTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for create or update teacherSubject -->
        <div class="modal fade" id="create-teacherSubject-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form  enctype="multipart/form-data" role="form" id="teacherSubjectForm" class="form row">
                    @csrf
                    <input type="hidden" name="teacherSubjectId" id="teacherSubjectId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-teacherSubject-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-teacherSubject-text" class="text-white"></h5>
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
                                        <label for="user_id" class="form-control-label">
                                            Selectionner l'enseignant :
                                        </label>
                                        <select name="user_id" id="user_id" class="form-select">
                                        @foreach ($enseignants as $enseignant)
                                            <option value="{{ $enseignant->id }}">{{ $enseignant->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="matiere_id" class="form-control-label">
                                            Selectionner la matiere :
                                        </label>
                                        <select name="matiere_id" id="matiere_id" class="form-select">
                                            @foreach ($matieres as $matiere)
                                                <option value="{{ $matiere->id }}">{{ $matiere->libelleMatiere }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
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
                            <button type="button" id="submit-teacherSubject-form-button" class="spinner-submit-teacherSubject-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-teacherSubject-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/teacherSubject.js') }}"></script>
    @endsection
</x-app-layout>
