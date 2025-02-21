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
                                    <h5 class="">Liste des Trimestres</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les Trimestres(Ajouter, Supprimer, Mettre à jour)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-trimestre-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterTrimestreForm">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="searchTrimestre" value="" id="searchTrimestre" class="form-control" placeholder="Rechercher par libellé"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="trimestresTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a trimestre datas -->
        <div class="modal fade" id="create-trimestre-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="trimestreForm" class="form row">
                    @csrf
                    <input type="hidden" name="trimestreId" id="trimestreId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-trimestre-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-trimestre-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="titre" class="form-control-label">
                                            Libellé :
                                        </label>
                                        <input
                                            type="text"
                                            id="libelleTrimestre"
                                            name="libelleTrimestre"
                                            class="form-control"
                                            placeholder="Entrez le libellé du trimestre"
                                            value="{{ old("libelleTrimestre") }}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dateDeDebut" class="form-control-label">
                                            Date de début :
                                        </label>
                                        <input type="date" id="dateDeDebut" name="dateDeDebut" class="form-control" value="{{old("dateDeDebut")}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dateDeFin" class="form-control-label">
                                            Date de fin :
                                        </label>
                                        <input type="date" id="dateDeFin" name="dateDeFin" class="form-control" value="{{old("dateDeFin")}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="annee_scolaire_id" class="form-control-label d-none">
                                            Anneé :
                                        </label>
                                        <input type="hidden" class="form-control" id="annee_scolaire_id" name="annee_scolaire_id" value="{{ $activeYear->id }}">
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
                            <button type="button" id="submit-trimestre-form-button" class="spinner-submit-trimestre-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-trimestre-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/trimestres.js') }}"></script>
    @endsection
</x-app-layout>
