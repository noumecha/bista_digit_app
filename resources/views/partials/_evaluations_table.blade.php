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
                Statut
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
            <td class="text" colspan="6">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($evaluations as $evaluation)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $evaluation->libelleEvaluation }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ formatDate($evaluation->dateDeDebut, 'd/m/Y') }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ formatDate($evaluation->dateDeFin, 'd/m/Y') }}
                    </td>
                    <td
                        class="countdown-timer align-middle bg-transparent border-bottom"
                        data-start-date="{{ $evaluation->dateDeDebut }}"
                        data-end-date="{{ $evaluation->dateDeFin }}"
                    >
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $evaluation->trimestre->libelleTrimestre }}
                    </td>
                    <td class="text-center align-middle bg-transparent border-bottom">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-evaluation-modal"
                            data-action="edit"
                            data-evaluation-id = "{{ $evaluation->id }}"
                            data-url="{{ route('evaluation.store', $evaluation->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $evaluation->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $evaluation->id }}" tabindex="-1" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('evaluation.destroy', $evaluation->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimée les informations de l'évaluation : {{ $evaluation->libelleEvaluation }}
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
    {{ $evaluations->appends(request()->query())->links() }}
</div>