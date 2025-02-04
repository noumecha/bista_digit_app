<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                ID</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Enseignants</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Matieres
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($enseignantsMatieres->items()))
            <td class="text" colspan="5">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($enseignantsMatieres as $enseignantMatiere)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $enseignantMatiere->id }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        @foreach ($enseignants as $enseignant)
                            {{ $enseignantMatiere->user_id === $enseignant->id ? $enseignant->name : ''}}
                        @endforeach
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        @foreach ($matieres as $matiere)
                            {{ $enseignantMatiere->matiere_id === $matiere->id ? $matiere->libelleMatiere : '' }}
                        @endforeach
                    </td>
                    <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-teacherSubject-modal"
                            data-action="edit"
                            data-teachersubject-id = "{{ $enseignantMatiere->id }}"
                            data-year-id="{{ $activeYear->id }}"
                            data-enseignantMatiere-name="{{ $enseignantMatiere->name }}"
                            data-url="{{ route('enseignantMatiere.store', $enseignantMatiere->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $enseignantMatiere->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <div onclick="showDropdown(this)" id="ddown-menu" class="ddown-menu d-flex btn btn-transparent ml-2 mt-3 p-2">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                            <div class="ddown-items-container d-none p-2 bg-dark">
                                <button
                                    type="button"
                                    class="mb-0 p-2 btn text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteYear-{{ $enseignantMatiere->id }}"
                                    {{ $activeYear->id === $enseignantMatiere->create_year_id ? 'disabled' : '' }}
                                >
                                    Supprimer pour l'année
                                </button>
                                <a class="mb-0 p-2 btn text-white disabled" href="#">
                                    Statistiques
                                </a>
                            </div>
                        </div>
                        <!-- modal for delete teacher in the current year -->
                        <div class="modal fade" data-form-id="{{ $enseignantMatiere->id }}" id="confirmDeleteYear-{{ $enseignantMatiere->id }}" tabindex="-1" aria-labelledby="migrateModal" aria-hidden="true">
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('enseignantMatiere.deleteEnsMatCurrentYear') }}">
                                    @csrf
                                    <div class="modal-content p-0">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white text-wrap text-justify" id="exampleModalLabel">Suppresion de la configuration enseignant-matière</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <input type="hidden" name="delusyear_year_id" value="{{ $activeYear->id }}">
                                        <input type="hidden" name="delusyear_ensmat_id" value="{{ $enseignantMatiere->id }}">
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimér la configuration
                                            pour l'année {{ $activeYear->libelleAnneeScolaire }} ?
                                        </div>
                                        <div class="modal-footer flex-row-reverse">
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="spinner-submit-button btn btn-danger">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                Confirmer
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $enseignantMatiere->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" >
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('enseignantMatiere.destroy', $enseignantMatiere->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimée définitivement la configuration actuelle ?
                                            (Cette action est irreversible)
                                        </div>
                                        <div class="modal-footer flex-row-reverse">
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="spinner-submit-button btn btn-danger">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" ></span>
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
    {{ $enseignantsMatieres->appends(request()->query())->links() }}
</div>