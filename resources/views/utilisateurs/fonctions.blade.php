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
                            @if (session('errorSuccess'))
                                <div class="row alert alert-danger text-center success-message" id="">
                                    {{ session('errorSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des fonctions</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les fonctions occupées par le personnel administratif (Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-fonction-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mb-3 mt-3" id="filterFonctionForm">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" name="searchFonction" value="" id="searchFonction" class="form-control" placeholder="Rechercher par libellé"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="fonctionsTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- create or update modal form -->
        <div class="modal fade" id="create-fonction-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form  enctype="multipart/form-data" role="form" id="createEditFonctionForm" class="form row">
                    @csrf
                    <input type="hidden" name="fonctionId" id="fonctionId" value="">
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
                            <div class="alert alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="libelleFonction" class="form-control-label">
                                            Libellé :
                                        </label>
                                        <input
                                            type="text" id="libelleFonction" name="libelleFonction"
                                            class="form-control"
                                            placeholder="Entrez le libellé de la fonction"
                                            value="{{old("libelleFonction")}}"
                                            aria-label="Name"
                                            aria-describedby="name-addon"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-fonction-form-button" class="spinner-submit-fonction-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span id="submit-fonction-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/fonctions.js') }}"></script>
    @endsection
</x-app-layout>
