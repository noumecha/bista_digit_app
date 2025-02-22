<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center" id="success-message">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des Evaluations</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les Evaluations(Ajouter, Supprimer, Mettre à jour)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-evaluation-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" id="evaluationsTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a evaluation datas -->
        <div class="modal fade" id="create-evaluation-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="evaluationForm" class="form row">
                    @csrf
                    <input type="hidden" name="trimestreId" id="trimestreId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-evaluation-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-evaluation-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titre" class="form-control-label">
                                            Libellé :
                                        </label>
                                        <input
                                            type="text"
                                            id="libelleEvaluation"
                                            name="libelleEvaluation"
                                            class="form-control"
                                            placeholder="Entrez le tire de la evaluation"
                                            value="{{ old("libelleEvaluation") }}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="trimestre_id" class="form-control-label">
                                            Trimestre :
                                        </label>
                                        <select name="trimestre_id" id="trimestre_id" class="form-select">
                                            @foreach ($trimestres as $trimestre)
                                                <option value="{{ $trimestre->id }}">
                                                    {{ $trimestre->libelleTrimestre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-evaluation-form-button" class="spinner-submit-evaluation-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-evaluation-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/evaluations.js') }}"></script>
    @endsection
</x-app-layout>
