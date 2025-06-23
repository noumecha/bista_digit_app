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
                    <form action="{{ route('statistics.generate') }}" id="getStatsForm" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <select name="trimestre_id" id="trimestre_id" class="form-select" required>
                                        <option value="">Sélectionner un trimestre</option>
                                        @foreach($trimestres as $trimestre)
                                            <option value="{{ $trimestre->id }}">
                                                {{ $trimestre->libelleTrimestre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <select name="classe_id" id="classe_id" class="form-select" required>
                                        <option value="">Sélectionner une classe</option>
                                        @foreach($classes as $classe)
                                            <option value="{{ $classe->id }}">{{ $classe->libClasse }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="submit" name="export_pdf" value="1" class="btn btn-success">
                                    <i class="fas fa-file-pdf me-2"></i> PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive" id="statsTable" style="overflow-x: visible;">
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/stats.js') }}"></script>
    @endsection
</x-app-layout>