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
                                    <h5 class="">Liste des épreuves</h5>
                                    <p class="text-sm">
                                        Ajouter ou modifier des épreuves
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-epreuve-modal"
                                    >
                                        <i class="fa-solid fa-clipboard me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterEpreuveForm">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="searchEpreuve" id="searchEpreuve" class="form-control"
                                            placeholder="Rechercher une épreuve (titre ou contenu)"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="classeFilter" id="classeFilter" class="form-select" id="">
                                            <option value="">Toutes les Classes</option>
                                            @foreach ($classes as $class)
                                                <option value="{{$class->id}}">
                                                    {{ $class->libClasse }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="matiereFilter" id="matiereFilter" class="form-select" id="">
                                            <option value="">Toutes les matieres</option>
                                            @foreach ($matieres as $mat)
                                                <option value="{{$mat->id}}">
                                                    {{ $mat->libelleMatiere }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="typeEpreuveFilter" id="typeEpreuveFilter" class="form-select" id="">
                                            <option value="">Tout les types d'épreuves</option>
                                            @foreach ($typeEpreuves as $typeEpreuve)
                                                <option value="{{$typeEpreuve->id}}">
                                                    {{ $typeEpreuve->libelleTypeEpreuve }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="yearFilter" id="yearFilter" class="form-select" id="">
                                            <option value="">Année Scolaire</option>
                                            @foreach ($yearEpreuves as $yearEpreuve)
                                                <option value="{{ $yearEpreuve->anneeEpreuve }}">
                                                    {{ $yearEpreuve->anneeEpreuve }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="epreuvesTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a remplissage datas -->
        <div class="modal fade" id="create-epreuve-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl">
                <form enctype="multipart/form-data" role="form" id="epreuveForm" class="form row">
                    @csrf
                    <input type="hidden" name="epreuveId" id="epreuveId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-epreuve-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-epreuve-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="libelleEpreuve" class="form-control-label">
                                            Titre:
                                        </label>
                                        <input
                                            type="text"
                                            id="libelleEpreuve"
                                            name="libelleEpreuve"
                                            class="form-control"
                                            placeholder="Entrez le titre de la epreuve"
                                            value="{{ old("libelleEpreuve") }}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="anneeEpreuve" class="form-control-label">
                                            Année :
                                        </label>
                                        <input
                                            type="text"
                                            id="anneeEpreuve"
                                            name="anneeEpreuve"
                                            class="form-control"
                                            maxlength="9"
                                            placeholder="Exemple (2023/2024)"
                                            value="{{ old("anneeEpreuve") }}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fichier" class="form-control-label">
                                            Fichier :
                                        </label>
                                        <input type="file" id="fichier" name="fichier" accept=".pdf,image/*" class="form-control"
                                            placeholder="Selectionner le fichier" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type_epreuve_id" class="form-control-label">
                                            Type d'épreuve :
                                        </label>
                                        <select name="type_epreuve_id" id="type_epreuve_id" class="form-select">
                                            <option value="">Selectionnez le type d'épreuve</option>
                                            @foreach ($typeEpreuves as $typeEpreuve)
                                                <option value="{{ $typeEpreuve->id }}">
                                                    {{ $typeEpreuve->libelleTypeEpreuve }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="matiere_id" class="form-control-label">
                                            Matière :
                                        </label>
                                        <select name="matiere_id" id="matiere_id" class="form-select">
                                            <option value="">Selectionnez une matière</option>
                                            @foreach ($matieres as $matiere)
                                                <option value="{{ $matiere->id }}">
                                                    {{ $matiere->libelleMatiere }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="classe_id" class="form-control-label">
                                            Classe :
                                        </label>
                                        <select name="classe_id" id="classe_id" class="form-select">
                                            <option value="">Selectionnez une classe</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}">
                                                    {{ $classe->libClasse }}
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
                            <button type="button" id="submit-epreuve-form-button" class="spinner-submit-epreuve-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-epreuve-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/epreuves.js') }}"></script>
    @endsection
</x-app-layout>
