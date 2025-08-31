<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Elève
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Classe
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Evaluation
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Trimestre
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($bulletins->items()))
            <td class="text" colspan="5">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($bulletins as $bulletin)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $bulletin->student->name }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $bulletin->classe->libClasse }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        @if (isset($bulletin->evaluation))
                            {{ $bulletin->evaluation->libelleEvaluation }}
                        @elseif (isset($bulletin->trimestre))
                            {{ $bulletin->trimestre->libelleTrimestre }}
                        @else
                            {{ "Annuel" }}
                        @endif
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ isset($bulletin->trimestre) ? $bulletin->trimestre->libelleTrimestre : "Annuel" }}
                    </td>
                    <td class="text-center align-middle bg-transparent border-bottom">
                        <a
                            class="btn btn-primary mt-3 p-2"
                            id="bulletin-preview"
                            href="{{ route('bulletins.preview', $bulletin->id) }}"
                        >
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $bulletins->appends(request()->query())->links() }}
</div>