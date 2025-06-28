@if (empty($stats->items()))
    <div class="alert alert-info">
        Aucune statistique publiée pour ces critères
    </div>
@else
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>
                        Trimestre
                    </th>
                    <th>
                        Classe
                    </th>
                    <th>
                        Date Publication
                    </th>
                    <th>
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stats as $stat)
                <tr>
                    <td>
                        {{ $stat->trimestre->libelleTrimestre }}
                    </td>
                    <td>
                        {{ $stat->classe->libClasse }}
                    </td>
                    <td>
                        {{ $stat->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <a href="{{ route('front.statistics.details', $stat->id) }}"
                        class="btn btn-sm btn-outline-primary">
                            Voir les Résultats
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
<div class="d-flex justify-content-center">
    {{ $stats->appends(request()->query())->links() }}
</div>