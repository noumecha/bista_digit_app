<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Date debut
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Date fin
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Evaluation
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Statut
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($remplissages->items()))
            <td class="text" colspan="5">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($remplissages as $remplissage)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ formatDate($remplissage->date_debut, 'd/m/Y') }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ formatDate($remplissage->date_fin , 'd/m/Y') }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $remplissage->evaluation->libelleEvaluation }}
                    </td>
                    <td
                        class="countdown-timer align-middle bg-transparent border-bottom"
                        data-start-date="{{ $remplissage->date_debut }}"
                        data-end-date="{{ $remplissage->date_fin }}"
                    >
                    </td>
                    <td class="text-center align-middle bg-transparent border-bottom">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-remplissage-modal"
                            data-action="edit"
                            data-remplissage-id = "{{ $remplissage->id }}"
                            data-url="{{ route('evaluation.remplissagesStore', $remplissage->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $remplissage->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $remplissage->id }}" tabindex="-1" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('evaluation.remplissagesDestroy', $remplissage->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimée la configuration actuelle ?
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
<div class="d-flex justify-content-center">
    {{ $remplissages->appends(request()->query())->links() }}
</div>