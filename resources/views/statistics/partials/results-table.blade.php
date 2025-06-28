<table class="table text-secondary text-center align-items-center table-hover">
    <thead class="table-light">
        <tr>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Rang
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Matricule
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Nom & Prénom
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Moyenne
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Appréciation
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Actions
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($bulletins->items()))
            <td class="text-center" colspan="6">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($bulletins as $bulletin)
            <tr>
                <td class="align-middle bg-transparent border-bottom">
                    {{ $bulletin->range }}<sup>{{ $bulletin->range === 1 ? "er" : "ème"  }}</sup>
                </td>
                <td class="align-middle bg-transparent border-bottom">
                    {{ $bulletin->student->matricule }}
                </td>
                <td class="align-middle bg-transparent border-bottom">
                    {{ $bulletin->student->name }} {{ $bulletin->student->surname }}
                </td>
                <td class="fw-bold {{ $bulletin->average >= 10 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($bulletin->average, 2) }}
                </td>
                <td class="align-middle bg-transparent border-bottom">
                    {{ $bulletin->appreciation }}
                </td>
                <td class="align-middle bg-transparent border-bottom">
                    <a href="{{ route('bulletins.preview', $bulletin->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>