<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Libelle
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($boostermatieres->items()))
            <td class="text" colspan="2">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($boostermatieres as $boostermatiere)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $boostermatiere->matiere->libelleMatiere }}
                    </td>
                    <td class="text-center align-middle bg-transparent border-bottom">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-boostermatiere-modal"
                            data-action="edit"
                            data-boostermatiere-id = "{{ $boostermatiere->id }}"
                            data-url="{{ route('booster.matiereSave', $boostermatiere->id) }}"
                            class="btn btn-primary mt-3 p-2 disabled"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $boostermatiere->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $boostermatiere->id }}" tabindex="-1" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST"
                                    action="{{ route('booster.matiereDelete', $boostermatiere->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimer la matière du programme ?
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
    {{ $boostermatieres->appends(request()->query())->links() }}
</div>