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
                                    <h5 class="">Gestion de la page d'accueil</h5>
                                    <p class="text-sm">
                                        Modifier les sliders de la page d'acceuil
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-slider-modal"
                                    >
                                        <i class="fa-solid fa-image me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterClubForm">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" name="searchText" id="searchText" class="form-control"
                                            placeholder="Rechercher un slider (par titre, description)"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="slidersTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creation new actualites -->
        <div class="modal fade" id="create-slider-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl">
                <form enctype="multipart/form-data" role="form" id="sliderForm" class="form row">
                    @csrf
                    <input type="hidden" name="sliderId" id="sliderId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-slider-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-slider-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="slider_title" class="form-control-label">
                                            Titre du slider :
                                        </label>
                                        <input
                                            type="text"
                                            id="slider_title"
                                            name="slider_title"
                                            class="form-control"
                                            placeholder="Entrez le titre du slider"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="slider_image" class="form-control-label">
                                            Image de mise en Avant :
                                        </label>
                                        <input type="file" id="slider_image" name="slider_image" class="form-control"
                                            placeholder="Selectionner une image de mise en avant (taille max = 4Mo)" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="slider_text" class="form-control-label">
                                        Description du slider :
                                    </label>
                                    <textarea
                                        name="slider_text"
                                        id="slider_text"
                                        class="form-control"
                                        placeholder="Entrez la description du slider"
                                        cols="12"
                                        rows="15">
                                    </textarea>
                                </div>
                            </div>
                            <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-slider-form-button" class="spinner-submit-slider-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-slider-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/sliders.js') }}"></script>
    @endsection
</x-app-layout>

