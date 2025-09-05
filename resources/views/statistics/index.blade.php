<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        Génération des Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('statistics.exportPDF') }}" id="getStatsForm" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="trimestre_id" id="trimestre_id" class="form-select">
                                        <option value="">Sélectionner un trimestre</option>
                                        @foreach($trimestres as $trimestre)
                                            <option value="{{ $trimestre->id }}">
                                                {{ $trimestre->libelleTrimestre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="classe_id" id="classe_id" class="form-select">
                                        <option value="">Sélectionner une classe</option>
                                        @foreach($classes as $classe)
                                            <option value="{{ $classe->id }}">{{ $classe->libClasse }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="submit" id="downloadPdfBtn" class="btn btn-success">
                                    <i class="fas fa-file-pdf me-2"></i> PDF
                                </button>
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="button" id="spinner-submit-statspublish-form-button"
                                    class="btn btn-primary spinner-submit-statspublish-form-button">
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                    <i class="fas fa-square-arrow-up-right me-2"></i> Publier sur le site
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            @if (session('deleteSuccess'))
                                <div class="alert alert-danger text-center success-message">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger text-wrap success-message">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive" id="statsTable">
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/stats.js') }}"></script>
    @endsection
</x-app-layout>