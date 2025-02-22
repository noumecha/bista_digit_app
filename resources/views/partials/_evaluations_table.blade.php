<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Libelle
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Date de debut
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Date de fin
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
        @if (empty($evaluations->items()))
            <td class="text" colspan="5">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($evaluations as $evaluation)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $evaluation->libelleEvaluation }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $evaluation->trimestre->libelleTrimestre }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $evaluation->trimestre->libelleTrimestre }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $evaluation->trimestre->libelleTrimestre }}
                    </td>
                    <td class="text-center align-middle bg-transparent border-bottom">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li>
                                    <a class="dropdown-item" href="{{ route('evaluation.evaluationsEdit', $evaluation->id) }}">
                                        <i class="fas fa-user-edit" aria-hidden="true"></i>
                                        modifier
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-item">
                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                        <form role="form" class="form" method="POST" action="{{ route('evaluation.evaluationsDestroy', $evaluation->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" value="Supprimer">
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $evaluations->appends(request()->query())->links() }}
</div>