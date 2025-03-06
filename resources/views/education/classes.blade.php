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
                                    <h5 class="">Liste des Classes</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les classes (Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-classe-modal"
                                    >
                                        <i class="fa-solid fa-people-roof me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterClasseForm">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="searchClasse" id="searchClasse" class="form-control" placeholder="Rechercher par libellé"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select name="cycleFilter" class="form-select" id="cycleFilter">
                                            <option value="">Tout les cycles</option>
                                            <option value="2nd Cycle">2<sup>nd</sup> Cycle</option>
                                            <option value="1er Cycle">1<sup>er</sup> Cycle</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select name="sectionFilter" class="form-select" id="sectionFilter">
                                            <option value="">Toutes les sections</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}">{{ $section->libelleSection }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="classesTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for create or update classe -->
        <div class="modal fade" id="create-classe-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form  enctype="multipart/form-data" role="form" id="classeForm" class="form row">
                    @csrf
                    <input type="hidden" name="classeId" id="classeId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-classe-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-classe-text" class="text-white"></h5>
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
                                        <label for="libClasse" class="form-control-label">
                                            Libellé :
                                        </label>
                                        <input
                                            type="text"
                                            id="libClasse"
                                            name="libClasse"
                                            class="form-control"
                                            placeholder="Entrez le libellé de la classe"
                                            value="{{ old("libClasse") }}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cycleClasse" class="form-control-label">
                                            Cycle :
                                        </label>
                                        <select name="cycleClasse" id="cycleClasse" class="form-select">
                                            <option value="2nd Cycle">2<sup>nd</sup> Cycle</option>
                                            <option value="1er Cycle">1<sup>er</sup> Cycle</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select name="section_id" class="form-select" id="section_id">
                                            <option value="">Toutes les sections</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}">{{ $section->libelleSection }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-classe-form-button" class="spinner-submit-classe-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-classe-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/classes.js') }}"></script>
    @endsection
</x-app-layout>
