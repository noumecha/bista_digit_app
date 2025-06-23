<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h5 class="mb-0">
                Résultats du {{ $trimestre->libelleTrimestre ?? "-" }} - Classe : {{ $classe->libClasse ?? "-" }}
            </h5>
            <a href="{{ route('statistics.generate', [
                'trimestre_id' => $trimestre->id ?? null,
                'classe_id' => $classe->id ?? null,
                'export_pdf' => 1
            ]) }}" class="btn btn-light btn-sm">
                <i class="fas fa-file-pdf me-1"></i> PDF
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @include('statistics.partials.results-table')
            </div>
        </div>
    </div>
</div>