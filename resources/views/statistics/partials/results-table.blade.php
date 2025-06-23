<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Rang</th>
            <th>Matricule</th>
            <th>Nom & Prénom</th>
            <th>Moyenne</th>
            <th>Appréciation</th>
            <th>Actions</th>
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
                <td>{{ $bulletin->range }}</td>
                <td>{{ $bulletin->student->matricule }}</td>
                <td>{{ $bulletin->student->name }} {{ $bulletin->student->surname }}</td>
                <td class="fw-bold {{ $bulletin->average >= 10 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($bulletin->average, 2) }}
                </td>
                <td>{{ $bulletin->appreciation }}</td>
                <td>
                    <a href="{{ route('bulletins.preview', $bulletin->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>