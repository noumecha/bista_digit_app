<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
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
                            @if (session('deleteError'))
                                <div class="row alert alert-danger text-center success-message">
                                    {{ session('deleteError') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des Notes par classes</h5>
                                    <p class="text-sm">
                                        Gérer les notes du programme booster(par classe, par matière et par évaluation)
                                    </p>
                                </div>
                            </div>
                            <form id="filterBoosterNoteForm" class="form form-inline row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="searchNote" id="searchNote" class="form-control" placeholder="Rechercher une note (nom de l'élève)"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="classeFilter" class="form-select" id="classeFilter">
                                            <option value="">Selectionnez une classe</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}">
                                                    {{ $classe->libClasse }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="matiereFilter" class="form-select" id="matiereFilter">
                                            <option value="">Sélectionnez une matière</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="remplissageFilter" class="form-select" id="remplissageFilter">
                                            <option value="">Sélectionnez une évaluation</option>
                                            @foreach ($remplissages as $remplissage)
                                                <option value="{{ $remplissage->id }}">
                                                    {{ $remplissage->evaluation->libelleEvaluation }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div>
                            <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                        </div>
                        <div class="table-responsive mt-3" id="boosterNotesTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/booster-notes.js') }}"></script>
    @endsection
</x-app-layout>