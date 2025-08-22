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
                                    <h5 class="">Liste des clubs</h5>
                                    <p class="text-sm">
                                        Gestion des clubs
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-club-modal"
                                    >
                                        <i class="fa-solid fa-kaaba me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterClubForm">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" name="searchText" id="searchText" class="form-control"
                                            placeholder="Rechercher un club (par nom, description)"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="clubsTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creation new actualites -->
        <div class="modal fade" id="create-club-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl">
                <form enctype="multipart/form-data" role="form" id="clubForm" class="form row">
                    @csrf
                    <input type="hidden" name="clubId" id="clubId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-club-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-club-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="club_name" class="form-control-label">
                                            Nom du club :
                                        </label>
                                        <input
                                            type="text"
                                            id="club_name"
                                            name="club_name"
                                            class="form-control"
                                            placeholder="Entrez le nom du club"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @if(isset($clubToEdit) && $clubToEdit->club_image)
                                            <div>
                                                <img src="{{ asset('storage/' . $clubToEdit->club_image) }}" alt="Profile Image"
                                                    style="max-width: 150px; max-height: 150px; display: block; margin-bottom: 10px;">
                                            </div>
                                        @endif
                                        <label for="club_image" class="form-control-label">
                                            Image de mise en Avant :
                                        </label>
                                        <input type="file" id="club_image" name="club_image" class="form-control"
                                            placeholder="Selectionner une image de mise en avant (taille max = 4Mo)" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="president_id" class="form-control-label">
                                            Président :
                                        </label>
                                        <select name="president_id" id="president_id" class="form-select">
                                            <option value="">Selectionnez le président</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}">
                                                    {{ $student->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contenu" class="form-control-label">
                                        Description du club :
                                    </label>
                                    <textarea
                                        name="content"
                                        id="content"
                                        placeholder="Entrez la description du club"
                                        cols="12"
                                        rows="30">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="slider-wrapper" class="form-control-label">
                                        Ajouter des sliders :
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
                            <button type="button" id="submit-club-form-button" class="spinner-submit-club-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-club-form-button-text"></span>
                            </button>
                            <button type="button" id="add-slider" class="btn btn-primary mt-2">Ajouter un slider</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/clubs.js') }}"></script>
    @endsection
</x-app-layout>

