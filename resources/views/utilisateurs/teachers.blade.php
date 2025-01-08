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
                                    <h5 class="">Liste du personnel Enseignant</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les enseignants (Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-teacher-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterTeacherForm">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="searchTeacher" value="" id="searchTeacher" class="form-control" placeholder="Rechercher un enseignant (nom,prenom,telephone,email)"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="teachersTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for create or update teacher -->
        <div class="modal fade" id="create-teacher-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form  enctype="multipart/form-data" role="form" id="teacherForm" class="form row">
                    @csrf
                    <input type="hidden" name="teacherId" id="teacherId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-teacher-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-teacher-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                        </div>
                        <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="matricule" class="form-control-label">
                                        Matricule :
                                    </label>
                                    <input type="text" id="matricule" name="matricule" class="form-control"
                                        placeholder="Entrez le matricule" value={{ old("matricule") }}>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">
                                        Nom :
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Entrez le nom du personnel" value="{{ old("name") }}" aria-label="Name"
                                        aria-describedby="name-addon">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="surname" class="form-control-label">
                                        Prenom :
                                    </label>
                                    <input type="text" id="surname" name="surname" class="form-control"
                                        placeholder="Entrez le prénom du personnel" value="{{ old("surname") }}" aria-label="Name"
                                        aria-describedby="name-addon">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-control-label">
                                        Téléphone :
                                    </label>
                                    <input type="tel" id="phone" name="phone" class="form-control" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}"
                                        placeholder="Entrez le numero de téléphone" value="{{ old("phone") }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sex" class="form-control-label">
                                        Sexe :
                                    </label>
                                    <select name="sex" id="sex" class="form-control">
                                        @foreach (\App\Sex::cases() as $sex)
                                            <option value="{{ $sex->value }}">{{ $sex->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">
                                        Email :
                                    </label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="Entrez l'adresse email" value="{{ old("email") }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lieuNaiss" class="form-control-label">
                                        Lieu de naissance :
                                    </label>
                                    <input type="text" id="lieuNaiss" name="lieuNaiss" class="form-control"
                                        placeholder="Entrez le lieu de naissance" value="{{ old("lieuNaiss") }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateNaiss" class="form-control-label">
                                        Date de naissance :
                                    </label>
                                    <input type="date" id="dateNaiss" name="dateNaiss" class="form-control"
                                        placeholder="Entrez la date de naissance" value="{{ old("dateNaiss") }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="diplome1" class="form-control-label">
                                        Diplome 1 :
                                    </label>
                                    <input type="text" id="diplome1" name="diplome1" class="form-control"
                                        placeholder="Entrez le Diplôme 1 " value="{{ old("diplome1") }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="diplome2" class="form-control-label">
                                        Diplome 2 :
                                    </label>
                                    <input type="text" id="diplome2" name="diplome2" class="form-control"
                                        placeholder="Entrez le Diplôme 2 " value="{{ old("diplome2") }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numCni" class="form-control-label">
                                        Numero CNI :
                                    </label>
                                    <input type="text" id="numCni" name="numCni" class="form-control"
                                        placeholder="Entrez le lieu de résidence" value="{{ old("numCni") }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profile" class="form-control-label">
                                        Photo :
                                    </label>
                                    <input type="file" id="profile" name="profile" class="form-control"
                                        placeholder="Entrez le lieu de résidence" value="{{ old("profile") }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location" class="form-control-label">
                                        Lieu de résidence :
                                    </label>
                                    <input type="text" id="location" name="location" class="form-control"
                                        placeholder="Entrez le lieu de résidence" value="{{ old("location") }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-control-label">
                                        Mot de passe générer :
                                    </label>
                                    <input type="text" id="password" name="password" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer flex-row-reverse">
                        <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" id="submit-teacher-form-button" class="spinner-submit-teacher-form-button btn btn-lg">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span id="submit-teacher-form-button-text"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/teachers.js') }}"></script>
    @endsection
</x-app-layout>
