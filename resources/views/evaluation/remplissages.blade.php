<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
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
                                    <h5 class="">Liste des Remplissages de notes configurés</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les configurations de remplissage de notes(Ajouter, Supprimer, Mettre à jour)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-remplissage-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterEvaluationForm">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="evaluationFilter" id="evaluationFilter" class="form-select">
                                            <option value="">Tout les trimestres</option>
                                            @foreach ($evaluationsas $remplissage)
                                                <option value="{{ $remplissage->id }}">
                                                    {{ $remplissage->libelleEvaluation }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="statutFilter" id="statutFilter" class="form-select">
                                            <option value="">Tout les statuts</option>
                                            <option value="terminé">terminée</option>
                                            <option value="programmé">programmée</option>
                                            <option value="en cours">en cours</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="remplissagesTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a remplissage datas -->
        <div class="modal fade" id="create-remplissage-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="remplissageForm" class="form row">
                    @csrf
                    <input type="hidden" name="remplissageId" id="remplissageId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-remplissage-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-remplissage-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="evaluation_id" class="form-control-label">
                                            Evaluation :
                                        </label>
                                        <select name="evaluation_id" id="evaluation_id" class="form-select">
                                            @foreach ($evaluations as $remplissage)
                                                <option value="{{ $remplissage->id }}" {{ isset($remplissageToEdit) && $remplissageToEdit->evaluation_id === $remplissage->id ? 'selected' : '' }}>
                                                    {{ $remplissage->libelleEvaluation}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_debut" class="form-control-label">
                                            Date de debut :
                                        </label>
                                        <input type="date" id="date_debut" name="date_debut" class="form-control"
                                            placeholder="Entrez la date de la date debut de remplissage de note" value="{{ old("date_debut") }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="duree" class="form-control-label">
                                            Durée (en jours) :
                                        </label>
                                        <input type="number" id="duree" name="duree" class="form-control" value="{{ old("duree") }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_fin" class="form-control-label">
                                            Date de fin du remplissage :
                                        </label>
                                        <input type="date" id="date_fin" name="date_fin" class="form-control"
                                            placeholder="Entrez la date de la date fin de remplissage de note" value="{{ old("date_fin") }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="openDays" class="form-control-label">
                                            Inclure les weekends :
                                        </label>
                                        <input type="checkbox" id="openDays" name="openDays" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-remplissage-form-button" class="spinner-submit-remplissage-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-remplissage-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/remplissage.js') }}"></script>
    @endsection
</x-app-layout>
