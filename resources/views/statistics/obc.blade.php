<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="py-4 container-fluid">
            <div class="row">
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
                                    <h5 class="">Gestion du Classement OBC</h5>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-obcstats-modal"
                                    >
                                        <i class="fa-solid fa-sheet-plastic me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterObcStatsForm">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="yearFilter" class="form-control-label">
                                            Année Scolaire :
                                        </label>
                                        <select name="yearFilter" id="yearFilter" class="form-select">
                                            <option value="">Toutes les années</option>
                                            @foreach ($schoolYears as $year)
                                                <option value="{{ $year->id }}">
                                                    {{ $year->libelleAnneeScolaire }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Historique des Classements</h5>
                                </div>
                            </div>
                            <div class="table-responsive" id="obcStatsTable">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a obc position data -->
        <div class="modal fade" id="create-obcstats-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl">
                <form enctype="multipart/form-data" role="form" id="obcstatsForm" class="form row">
                    @csrf
                    <input type="hidden" name="statId" id="statId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-obcstats-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-obcstats-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="annee_scolaire_id" class="form-control-label">
                                            Année Scolaire :
                                        </label>
                                        <select name="annee_scolaire_id" id="annee_scolaire_id" class="form-select">
                                            <option value="">Toutes les années</option>
                                            @foreach ($schoolYears as $year)
                                                <option value="{{ $year->id }}">
                                                    {{ $year->libelleAnneeScolaire }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Position de l'établissement</label>
                                        <input type="number" name="obc_rank" id="obc_rank" class="form-control"
                                            min="1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre total d'établissements</label>
                                        <input type="number" name="total_schools" id="total_schools" class="form-control"
                                            min="1" value="1200">
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
                            <button type="button" id="submit-obcstats-form-button" class="spinner-submit-obcstats-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-obcstats-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/obc-stats.js') }}"></script>
    @endsection
</x-app-layout>