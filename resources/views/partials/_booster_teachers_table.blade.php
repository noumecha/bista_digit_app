<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Nom
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Classe
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Matiere
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($boosterteachers->items()))
            <td class="text" colspan="4">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($boosterteachers as $boosterteacher)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $boosterteacher->teacher->name }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $boosterteacher->classe->libClasse }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $boosterteacher->booster_matiere->matiere->libelleMatiere }}
                    </td>
                    <td class="text-center align-middle bg-transparent border-bottom">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-boosterteacher-modal"
                            data-action="edit"
                            data-boosterteacher-id = "{{ $boosterteacher->id }}"
                            data-url="{{ route('booster.teacherSave', $boosterteacher->id) }}"
                            class="btn btn-primary mt-3 p-2 disabled"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $boosterteacher->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $boosterteacher->id }}" tabindex="-1" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST"
                                    action="{{ route('booster.teachersDelete', $boosterteacher->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimer l'enseignant du programme ?
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
    {{ $boosterteachers->appends(request()->query())->links() }}
</div>