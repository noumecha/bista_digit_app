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
                                    <h5 class="">Liste des Bulletins générés dans l'application</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les bulletins (générer , regénérer[mise à jour] , supprimer)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-bulletin-modal"
                                    >
                                        <i class="fa-solid fa-sheet-plastic me-2"></i> Générer
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterBulletinForm">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input
                                            type="text"
                                            name="searchStudent"
                                            id="searchStudent"
                                            class="form-control"
                                            placeholder="Rechercher un élève (par nom ou prenom)"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="trimestreFilter" id="trimestreFilter" class="form-select">
                                            <option value="">Tous les trimestres</option>
                                            @foreach ($trimestres as $trimestre)
                                                <option value="{{ $trimestre->id }}">
                                                    {{ $trimestre->libelleTrimestre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="evaluationFilter" id="evaluationFilter" class="form-select">
                                            <option value="">Toutes les évaluations</option>
                                            @foreach ($evaluations as $evaluation)
                                                <option value="{{ $evaluation->id }}">
                                                    {{ $evaluation->libelleEvaluation }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="classFilter" id="classFilter" class="form-select">
                                            <option value="">Toutes les classes</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}">
                                                    {{ $classe->libClasse }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="bulletinsTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a bulletins datas -->
        <div class="modal fade" id="create-bulletin-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl">
                <form enctype="multipart/form-data" role="form" id="bulletinForm" class="form row">
                    @csrf
                    <input type="hidden" name="bulletinId" id="bulletinId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-bulletin-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-bulletin-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="option_type" class="form-control-label">
                                            Option de génération :
                                        </label>
                                        <select name="option_type" id="option_type" class="form-select">
                                            <option value="">Selectionnez une option</option>
                                            <option value="all">Toute la classe</option>
                                            <option value="one">Individuelle</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="trimestre_id" class="form-control-label">
                                            Trimestre :
                                        </label>
                                        <select name="trimestre_id" id="trimestre_id" class="form-select">
                                            <option value="">Tout les trimestres</option>
                                            @foreach ($trimestres as $trimestre)
                                                <option value="{{ $trimestre->id }}">
                                                    {{ $trimestre->libelleTrimestre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="evaluation_id" class="form-control-label">
                                            Evaluation :
                                        </label>
                                        <select name="evaluation_id" id="evaluation_id" class="form-select">
                                            <option value="">Toutes les évaluations</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="classe_id" class="form-control-label">
                                            Classe :
                                        </label>
                                        <select name="classe_id" id="classe_id" class="form-select">
                                            <option value="">Toutes les classes</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}">
                                                    {{ $classe->libClasse }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="classe_id" class="form-control-label">
                                            Elève(s):
                                        </label>
                                        <select name="user_id" id="user_id" class="form-select">
                                            <option value="">Tout les élèves</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type_bulletin" class="form-control-label">
                                            Chosir le type de bulletin :
                                        </label>
                                        <select name="type_bulletin" id="type_bulletin" class="form-select">
                                            <option value="">Tout les types</option>
                                            <option value="sequenciel">Séquenciel</option>
                                            <option value="trimestre">Trimestriel</option>
                                            <option value="annuel">Annuel</option>
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
                            <button type="button" id="submit-bulletin-form-button" class="spinner-submit-bulletin-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-bulletin-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/bulletins.js') }}"></script>
    @endsection
</x-app-layout>
