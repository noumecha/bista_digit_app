<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
            <div class="pt-7 pb-6 bg-cover"
                style="
                    background-position: bottom;background-image:
                    @if(isset($page) && isset($page->specialite_image))
                        url('{{ asset('storage/'.$page->specialite_image) }}');
                    @else
                        url('{{ asset('img/header-blue-purple.jpg') }}');
                    @endif"
                >
            </div>
            <div class="container">
                <div class="card card-body py-2 bg-transparent shadow-none">
                    <div class="row">
                        <div class="col-auto my-auto">
                            <div class="h-100">
                                <h3 class="mb-0 font-weight-bold">
                                    @if (isset($page) && isset($page->specialite_title))
                                        {{ $page->specialite_title }}
                                    @else
                                        Non défini
                                    @endif
                                </h3>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                            <a
                                type="button"
                                class="btn btn-lg btn-dark btn-primary text-white"
                                data-bs-toggle="modal"
                                data-action="create"
                                data-pageconfiguration-id = "{{ isset($page) ? $page->id : ''}}"
                                data-bs-target="#update-pageconfiguration-modal"
                                class="btn btn-sm btn-white"
                            >
                                <i class="fas fa-pen me-2"></i> Modifier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container my-3 py-3">
                <div class="row">
                    <div class="col-12 col-xl-12 mb-4">
                        <div class="card border shadow-xs h-100">
                            <div class="card-header pb-0 p-3">
                                <div class="row">
                                    <div class="col-md-8 col-9">
                                        <h6 class="mb-0 font-weight-semibold text-lg">Informations de la page</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="mb-0 font-weight-semibold text-lg">Description</h6>
                                @if (isset($page) && isset($page->contenu))
                                    {!! Str::limit($page->contenu , $limit=300, $end="...") !!}
                                @else
                                    <p class="text-sm mb-4">aucune description ...</p>
                                @endif
                                <ul class="list-group">
                                    <li
                                        class="list-group-item border-0 ps-0 text-dark font-weight-semibold pt-0 pb-1 text-sm">
                                        <span class="text-secondary">Titre de la page :</span>
                                        @if (isset($page) && isset($page->specialite_title))
                                            {{ $page->specialite_title }}
                                        @else
                                            Non défini
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a pageconfiguration datas -->
        <div class="modal fade" id="update-pageconfiguration-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="pageconfigurationForm" class="form row">
                    @csrf
                    <input type="hidden" name="pageconfigurationId" id="pageconfigurationId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-pageconfiguration-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-pageconfiguration-text" class="text-white"></h5>
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
                                            placeholder="Entrez le titre de la page"
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
                                        placeholder="Entrez la description de la page"
                                        cols="12"
                                        rows="5">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="slider-wrapper" class="form-control-label">
                                        Ajouter des images + description (slider) :
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
                            <button type="button" id="submit-pageconfiguration-form-button" class="spinner-submit-pageconfiguration-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-pageconfiguration-form-button-text"></span>
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
        <script src="{{ asset('js/functions/iamleader.js') }}"></script>
    @endsection
</x-app-layout>