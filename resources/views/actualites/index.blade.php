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
                                    <h5 class="">Liste des Actualités</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les actualites(Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-actualite-modal"
                                    >
                                        <i class="fa-solid fa-newspaper me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterActualiteForm">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="searchActualite" id="searchActualite" class="form-control" placeholder="Rechercher une actulaité (titre ou contenu)"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select name="categorieFilter" class="form-select" id="categorieFilter">
                                            <option value="">Toutes les catégorie</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{$cat->id}}">
                                                    {{ $cat->libelleCategorie }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="actualitesTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creation new actualites -->
        <div class="modal fade" id="create-actualite-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl">
                <form enctype="multipart/form-data" role="form" id="actualiteForm" class="form row">
                    @csrf
                    <input type="hidden" name="actualiteId" id="actualiteId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-actualite-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-actualite-text" class="text-white"></h5>
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
                                            Titre :
                                        </label>
                                        <input
                                            type="text"
                                            id="titre"
                                            name="titre"
                                            class="form-control"
                                            placeholder="Entrez le tire de la actualite"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @if(isset($actualiteToEdit) && $actualiteToEdit->image)
                                            <div>
                                                <img src="{{ asset('storage/' . $actualiteToEdit->image) }}" alt="Profile Image"
                                                    style="max-width: 150px; max-height: 150px; display: block; margin-bottom: 10px;">
                                            </div>
                                        @endif
                                        <label for="image" class="form-control-label">
                                            Image de mise en Avant :
                                        </label>
                                        <input type="file" id="image" name="image" class="form-control"
                                            placeholder="Selectionner une image de mise en avant" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="categorie_actualites_id" class="form-control-label">
                                            Categorie :
                                        </label>
                                        <select name="categorie_actualites_id" id="categorie_actualites_id" class="form-select">
                                            <option value="">Toutes les catégories</option>
                                            @foreach ($categories as $categorie)
                                                <option value="{{ $categorie->id }}" {{ isset($actualiteToEdit) && $actualiteToEdit->categorie_actualites_id === $categorie->id ? 'selected' : '' }}>
                                                    {{ $categorie->libelleCategorie }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contenu" class="form-control-label">
                                        Contenu :
                                    </label>
                                    <textarea
                                        name="content"
                                        id="content"
                                        placeholder="Entrez le contenu de l'actualité"
                                        cols="12"
                                        rows="30">
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
                            <button type="button" id="submit-actualite-form-button" class="spinner-submit-actualite-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-actualite-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/actualites.js') }}"></script>
    @endsection
</x-app-layout>

