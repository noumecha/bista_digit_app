<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
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
                                    <h5 class="">Liste des Matières</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les matières (Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-matiere-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterMatiereForm">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" name="searchMatiere" id="searchMatiere" class="form-control" placeholder="Rechercher par libellé ou par code"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="matieresTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for create or update matiere -->
        <div class="modal fade" id="create-matiere-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form  enctype="multipart/form-data" role="form" id="matiereForm" class="form row">
                    @csrf
                    <input type="hidden" name="matiereId" id="matiereId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-matiere-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-matiere-text" class="text-white"></h5>
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
                                        <label for="libelleMatiere" class="form-control-label">
                                            Libellé :
                                        </label>
                                        <input type="text" id="libelleMatiere" name="libelleMatiere" class="form-control"
                                            placeholder="Entrez le libellé de la matière"
                                            value="{{ old("libelleMatiere") }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="codeMatiere" class="form-control-label">
                                            Code :
                                        </label>
                                        <input
                                            type="text"
                                            id="codeMatiere"
                                            name="codeMatiere"
                                            class="form-control"
                                            placeholder="Entrez le code de la matière"
                                            value="{{ old("codeMatiere") }}"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-matiere-form-button" class="spinner-submit-matiere-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-matiere-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/subjects.js') }}"></script>
    @endsection
</x-app-layout>
