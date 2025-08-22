<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center success-message">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des matieres du programme</h5>
                                    <p class="text-sm">
                                        Gérer les matieres du programme(Ajouter, Supprimer)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-boostermatiere-modal"
                                    >
                                        <i class="fa-solid fa-dna me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterBoosterMatiereForm">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="searchText" value=""
                                            id="searchText" class="form-control"
                                            placeholder="Rechercher la matière par libelle ou par code"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="boosterMatieresTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a boostermatiere datas -->
        <div class="modal fade" id="create-boostermatiere-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="boosterMatiereForm" class="form row">
                    @csrf
                    <input type="hidden" name="boosterMatiereId" id="boosterMatiereId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-boostermatiere-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-boostermatiere-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="matiere_id" class="form-control-label">
                                            Matieres :
                                        </label>
                                        <select name="matiere_id" id="matiere_id" class="form-select">
                                            <option value="">Ajouter une matiere</option>
                                            @foreach ($matieres as $matiere)
                                                <option value="{{ $matiere->id }}">
                                                    {{ $matiere->libelleMatiere }}
                                                </option>
                                            @endforeach
                                        </select>
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
                            <button type="button" id="submit-boostermatiere-form-button" class="spinner-submit-boostermatiere-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-boostermatiere-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/booster-matieres.js') }}"></script>
    @endsection
</x-app-layout>
