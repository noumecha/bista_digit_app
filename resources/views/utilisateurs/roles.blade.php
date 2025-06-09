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
                                    <h5 class="">Gestion des rôles utilisateurs</h5>
                                    <p class="text-sm">
                                        Gérer l'attribution des rôles aux utilisateurs de l'application
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-role-modal"
                                    >
                                        <i class="fa-brands fa-creative-commons-nd me-2"></i> Attribuer
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mb-3 mt-3" id="filterRoleForm">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" name="searchFilter" value="" id="searchFilter"
                                            class="form-control" placeholder="Rechercher par nom d'utilisateur"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <select name="roleFilter" class="form-select" id="roleFilter">
                                            <option value="">Filtrer par rôle</option>
                                            <option value="admin">Administrateur</option>
                                            <option value="user">Utilisateur</option>
                                            <option value="surveillant">Surveillant Général</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <select name="typeFilter" class="form-select" id="typeFilter">
                                            <option value="">Filtrer par type</option>
                                            <option value="eleve">Eleve</option>
                                            <option value="enseignant">Enseignant</option>
                                            <option value="personnel">Personnel</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="rolesTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- create or update modal form -->
        <div class="modal fade" id="create-role-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form  enctype="multipart/form-data" role="form" id="createEditRoleForm" class="form row">
                    @csrf
                    <input type="hidden" name="roleId" id="roleId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="receiverSelector">
                                        <label for="user_id" class="form-control-label">
                                            Choisir un utilisateur :
                                        </label>
                                        <select class="col-md-12 user_id" id="user_id" name="user_id">
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mt-3">
                                        <select name="role" class="form-select" id="role">
                                            <option value="">Choisir un rôle</option>
                                            <option value="admin">Administrateur</option>
                                            <option value="user">Utilisateur</option>
                                            <option value="surveillant">Surveillant Général</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-role-form-button" class="spinner-submit-role-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span id="submit-role-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/roles.js') }}"></script>
    @endsection
</x-app-layout>
