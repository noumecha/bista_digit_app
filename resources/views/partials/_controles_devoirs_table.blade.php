<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Eleve
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Classe
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Matière
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Devoir
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                resultat
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($histories->items()))
            <td class="text" colspan="5">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($histories as $history)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $history->note->eleve->name }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $history->note->classe->libClasse }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $history->note->matiere->codeMatiere }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $history->note->evaluation->libelleEvaluation }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $history->old_value }}
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $histories->appends(request()->query())->links() }}
</div>