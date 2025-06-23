<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th>
                Année
            </th>
            <th>
                Position
            </th>
            <th>
                Publié le
            </th>
            <th>
                Par
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($obcStats->items()))
            <td class="text-center" colspan="4">
                Aucune donnée disponible
            </td>
        @else
            @foreach($obcStats as $stat)
                <tr>
                    <td>
                        {{ $stat->libelleAnneeScolaire }}
                    </td>
                    <td>
                        {{ $stat->obc_rank }}<sup>ème</sup>
                    </td>
                    <td>
                        {{ formatDate($stat->published_at, 'd/m/Y H:i') }}
                    </td>
                    <td>
                        {{ $stat->publisher->name }}
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>