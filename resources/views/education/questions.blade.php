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
                                    <h5 class="">Liste des questions des devoirs</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les questions (Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-question-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterQuestionForm">
                                <div class="col-md-6 mb-4">
                                    <div class="input-group">
                                        <input type="text" name="searchQuestion" id="searchQuestion" class="form-control" placeholder="Rechercher par titre"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="devoirFilter" id="devoirFilter" class="form-select">
                                            <option value="">Toutes les devoirs</option>
                                        @foreach ($devoirs as $devoir)
                                            <option value="{{ $devoir->id }}" class="">{{ $devoir->titre_devoir }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="questionsTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for create or update question -->
        <div class="modal fade" id="create-question-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl">
                <form enctype="multipart/form-data" role="form" id="questionForm" class="form row">
                    @csrf
                    <input type="hidden" name="questionId" id="questionId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-question-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-question-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="question" class="form-control-label">
                                            Selectionnez un devoir :
                                        </label>
                                        <select name="devoir_id" id="devoir_id" class="form-select">
                                            <option value="">Tous les devoirs</option>
                                        @foreach ($devoirs as $devoir)
                                            <option value="{{ $devoir->id }}" class="">{{ $devoir->titre_devoir }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="content" class="form-control-label">
                                            Ajouter une nouvelle Question :
                                        </label>
                                        <textarea
                                            name="content"
                                            id="content"
                                            placeholder="Entrez le texte de la question"
                                            cols="12"
                                            rows="20">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    Listes des choix (réponses)
                                    <div id="reponses-container">
                                    </div >
                                    <button type="button" id="add-reponse-button" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus me-2"></i> Ajouter une réponse
                                    </button>
                                </div>
                            </div>
                            <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-question-form-button" class="spinner-submit-question-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-question-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/questions.js') }}"></script>
    @endsection
</x-app-layout>