<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="py-4 container-fluid">
            <div class="row">
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
                                    <h5 class="">Gestion des pages pour la présentation des autouts</h5>
                                    <p class="text-sm">
                                        Modifier ou ajouter des atouts de l'établissement (description et images)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-specialite-modal"
                                    >
                                        <i class="fa-solid fa-image me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterSpecialiteForm">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" name="searchText" id="searchText" class="form-control"
                                            placeholder="Rechercher une specialite (par titre, description)"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="specialitesTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creation new actualites -->
        <div class="modal fade" id="create-specialite-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl">
                <form enctype="multipart/form-data" role="form" id="specialiteForm" class="form row">
                    @csrf
                    <input type="hidden" name="specialiteId" id="specialiteId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-specialite-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-specialite-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="specialite_title" class="form-control-label">
                                            Titre de la spécialité (cycle):
                                        </label>
                                        <input
                                            type="text"
                                            id="specialite_title"
                                            name="specialite_title"
                                            class="form-control"
                                            placeholder="Entrez le titre du specialite"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="specialite_image" class="form-control-label">
                                            Image de mise en Avant :
                                        </label>
                                        <input type="file" id="specialite_image" name="specialite_image" class="form-control"
                                            placeholder="Selectionner une image de mise en avant (taille max = 4Mo)" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content" class="form-control-label">
                                        Description de la spécialité ou du cycle :
                                    </label>
                                    <textarea
                                        name="content"
                                        id="content"
                                        placeholder="Entrez la description du specialite"
                                        cols="12"
                                        rows="5">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="slider-wrapper" class="form-control-label">
                                        Ajouter des images + description :
                                    </label>
                                    <div class="d-flex row">
                                        <div id="slider-wrapper">
                                        </div>
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
                            <button type="button" id="submit-specialite-form-button" class="spinner-submit-specialite-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-specialite-form-button-text"></span>
                            </button>
                            <button type="button" id="add-slider" class="btn btn-primary mt-2">Ajouter image + description</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/specialites.js') }}"></script>
    @endsection
</x-app-layout>

