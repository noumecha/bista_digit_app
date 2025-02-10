
<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                ID</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Titre</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Matière
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Classe
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Enseignant
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($devoirs->items()))
            <td class="text" colspan="6">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($devoirs as $devoir)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $devoir->id }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $devoir->titre_devoir }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $devoir->classe->libClasse }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $devoir->matiere->libelleMatiere }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $devoir->enseignant->name }}
                    </td>
                    <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-devoir-modal"
                            data-action="edit"
                            data-devoir-id = "{{ $devoir->id }}"
                            data-year-id="{{ $activeYear->id }}"
                            data-url="{{ route('devoir.store', $devoir->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $devoir->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <div onclick="showDropdown(this)" id="ddown-menu" class="ddown-menu d-flex btn btn-transparent ml-2 mt-3 p-2">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                            <div class="ddown-items-container d-none p-2 bg-dark">
                                <button
                                    type="button"
                                    class="mb-0 p-2 btn text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmMigrate-{{ $devoir->id }}"
                                >
                                    Migrer
                                </button>
                                <button
                                    type="button"
                                    class="mb-0 p-2 btn text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteYear-{{ $devoir->id }}"
                                    {{ $activeYear->id === $devoir->annee_scolaire_id ? 'disabled' : '' }}
                                >
                                    Supprimer pour l'année
                                </button>
                            </div>
                        </div>
                        <!-- modal for migrate devoir to annother year -->
                        <div class="modal fade" data-form-id="{{ $devoir->id }}" id="confirmMigrate-{{ $devoir->id }}" tabindex="-1" aria-labelledby="migrateModal" >
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('devoir.migrate') }}">
                                    @csrf
                                    <div class="modal-content p-0">
                                        <div class="modal-header bg-dark">
                                            <h5 class="modal-title text-white" id="migrateModal">Confirmation de migration</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            </button>
                                        </div>
                                        <div class="alert text-wrap alert-success text-center" style="display: none;" id="modal-alert-success-{{ $devoir->id }}">
                                        </div>
                                        <div class="alert text-wrap alert-danger text-center" style="display: none;" id="modal-alert-errors-{{ $devoir->id }}">
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            <h5>Vous êtes sur le point d'ajouter la configuration actuelle à une année ultérieure!</h5>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="hidden" name="migrate_current_year_id" id="migrate_current_year_id" value="{{ $activeYear->id }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="migrate_devoir_id" id="migrate_devoir_id" value="{{ $devoir->id }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sex" class="form-control-label">
                                                        Selectionnez l'année :
                                                    </label>
                                                    <select name="migrate_year_id" id="migrate_year_id" class="form-control form-select">
                                                        @foreach ($migrateYears as $myear)
                                                            <option value="{{ $myear->id }}">{{ $myear->libelleAnneeScolaire }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer flex-row-reverse">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
                                            <button type="button" data-data-id="{{ $devoir->id }}" class="spinner-submit-modal-button btn btn-dark">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" ></span>
                                                Confirmer
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- modal for delete configuration for the current year -->
                        <div class="modal fade" data-form-id="{{ $devoir->id }}" id="confirmDeleteYear-{{ $devoir->id }}" tabindex="-1" aria-labelledby="migrateModal" >
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('devoir.deleteCoefCurrentYear') }}">
                                    @csrf
                                    <div class="modal-content p-0">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-wrap text-justify text-white" id="exampleModalLabel">Suppresion de la configuration de l'année scolaire courrante</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <input type="hidden" name="delusyear_year_id" value="{{ $activeYear->id }}">
                                        <input type="hidden" name="delusyear_coef_id" value="{{ $devoir->id }}">
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimér le devoir
                                            pour l'année {{ $activeYear->libelleAnneeScolaire }} ?
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
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $devoir->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" >
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('devoir.destroy', $devoir->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimée définitivement le devoir ?
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
    {{ $devoirs->appends(request()->query())->links() }}
</div>