<table class="table text-secondary text-center align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Année
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Position
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Publié le
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Par
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Actions
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($obcStats->items()))
            <td class="text-center" colspan="5">
                Aucune donnée disponible
            </td>
        @else
            @foreach($obcStats as $stat)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $stat->anneescolaire->libelleAnneeScolaire }}
                    </td>
                    <td class="fw-bold align-middle bg-transparent border-bottom">
                        {{ $stat->obc_rank }}<sup>ème</sup>/{{ $stat->data['total_schools'] }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ formatDate($stat->created_at, 'd/m/Y H:i') }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ isset($stat->publisher) ? $stat->publisher->name : "-" }}
                    </td>
                    <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-obcstats-modal"
                            data-action="edit"
                            data-obcstats-id = "{{ $stat->id }}"
                            data-url="{{ route('statistics.obc.edit', $stat->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $stat->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $stat->id }}" tabindex="-1" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('statistics.obc.destroy', $stat->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimer le classement pour l'année scolaire
                                            {{ $stat->anneescolaire->libelleAnneeScolaire }} ? (Cette action est irreversible)
                                        </div>
                                        <div class="modal-footer flex-row-reverse">
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="spinner-submit-button btn btn-danger">
                                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                Confirmer
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>