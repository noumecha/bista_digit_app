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
                                    <h5 class="">Liste des Sections d'enseignement</h5>
                                    <p class="text-sm">
                                        liste des sections d'enseignement gérées dans l'application
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-section-modal"
                                    >
                                        <i class="fa-solid fa-school-flag me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mb-3 mt-3" id="filterSectionForm">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" name="searchSection" value="" id="searchSection" class="form-control" placeholder="Rechercher par libellé"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="sectionsTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- create or update modal form -->
        <div class="modal fade" id="create-section-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="sectionForm" class="form row">
                    @csrf
                    <input type="hidden" name="sectionId" id="sectionId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-section-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-section-text" class="text-white"></h5>
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
                                        <label for="libelleSection" class="form-control-label">
                                            Libellé de l'année :
                                        </label>
                                        <input type="text" id="libelleSection"
                                            name="libelleSection"
                                            class="form-control"
                                            placeholder="Technique Francophone"
                                            value="{{ old("libelleSection") }}"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-section-form-button" class="spinner-submit-section-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span id="submit-section-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/sections.js') }}"></script>
    @endsection
</x-app-layout>