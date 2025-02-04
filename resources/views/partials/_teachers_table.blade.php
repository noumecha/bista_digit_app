<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                ID</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Photo</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Nom</th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Telephone</th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($teachers->items()))
            <td colspan="5" class="text">
                Aucunne donnée disponible
            </td>
        @else
            @foreach ($teachers as $teacher)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $teacher->id }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="{{ asset('storage/' . $teacher->profile) }}" class="rounded-circle mr-2"
                                alt="user1" style="height: 36px; width: 36px;">
                        </div>
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $teacher->name }}
                    </td>
                    <td class="text-center align-middle bg-transparent border-bottom">
                        {{ $teacher->phone }}
                    </td>
                    <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-teacher-modal"
                            data-action="edit"
                            data-teacher-id="{{ $teacher->id }}"
                            data-year-id = "{{ $activeYear->id }}"
                            data-teacher-name="{{ $teacher->name }}"
                            data-url="{{ route('teacher.store', $teacher->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button type="button" class="btn btn-danger ml-2 mt-3 p-2" data-bs-toggle="modal" data-bs-target="#confirmDelete-{{ $teacher->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <div onclick="showDropdown(this)" id="ddown-menu" class="ddown-menu d-flex btn btn-transparent ml-2 mt-3 p-2">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                            <div class="ddown-items-container d-none p-2 bg-dark">
                                <button
                                    type="button"
                                    class="mb-0 p-2 btn text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmMigrate-{{ $teacher->id }}"
                                >
                                    Migrer
                                </button>
                                <button
                                    type="button"
                                    class="mb-0 p-2 btn text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteYear-{{ $teacher->id }}"
                                    {{ $activeYear->id === $teacher->create_year_id ? 'disabled' : '' }}
                                >
                                    Supprimer pour l'année
                                </button>
                                <a class="mb-0 p-2 btn text-white" href="#">
                                    Statistiques
                                </a>
                            </div>
                        </div>
                        <!-- modal for migrate teacher to annother year -->
                        <div class="modal fade" data-form-id="{{ $teacher->id }}" id="confirmMigrate-{{ $teacher->id }}" tabindex="-1" aria-labelledby="migrateModal" aria-hidden="true">
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('teacher.teacherMigrate') }}">
                                    @csrf
                                    <div class="modal-content p-0">
                                        <div class="modal-header bg-dark">
                                            <h5 class="modal-title text-white" id="migrateModal">Confirmation de migration</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            </button>
                                        </div>
                                        <div class="alert text-wrap alert-success text-center" style="display: none;" id="modal-alert-success-{{ $teacher->id }}">
                                        </div>
                                        <div class="alert text-wrap alert-danger text-center" style="display: none;" id="modal-alert-errors-{{ $teacher->id }}">
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            <h5>Vous êtes sur le point d'ajouter l'enseignant {{ $teacher->name }} à une année ultérieure!</h5>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="hidden" name="migrate_current_year_id" id="migrate_current_year_id" value="{{ $activeYear->id }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="migrate_user_id" id="migrate_user_id" value="{{ $teacher->id }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sex" class="form-control-label">
                                                        Selectionnez l'année :
                                                    </label>
                                                    <select name="migrate_year_id" id="migrate_year_id" class="form-control form-select">
                                                        <!-- option value="0">Selectionner une année</!-->
                                                        @foreach ($migrateYears as $myear)
                                                            <option value="{{ $myear->id }}">{{ $myear->libelleAnneeScolaire }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer flex-row-reverse">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
                                            <button type="button" data-data-id="{{ $teacher->id }}" class="spinner-submit-modal-button btn btn-dark">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                Confirmer
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- modal for delete teacher in the current year -->
                        <div class="modal fade" data-form-id="{{ $teacher->id }}" id="confirmDeleteYear-{{ $teacher->id }}" tabindex="-1" aria-labelledby="migrateModal" aria-hidden="true">
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('teacher.teacherDeleteUserCurrentYear') }}">
                                    @csrf
                                    <div class="modal-content p-0">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Suppresion de l'enseignant de l'année scolaire</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <input type="hidden" name="delusyear_year_id" value="{{ $activeYear->id }}">
                                        <input type="hidden" name="delusyear_user_id" value="{{ $teacher->id }}">
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimér l'enseignant {{ $teacher->name }}
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
                        <div class="modal fade" id="confirmDelete-{{ $teacher->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form role="form" id="delete-form" class="form" method="POST" action="{{ route('teacher.destroy', $teacher->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content p-0">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimée <b>définitivement</b> l'enseignant <b>{{ $teacher->name }} ?
                                            (Cette action est irreversible)</b>. Cette action l'éffacera également des <b>enseignements</b>
                                            et de <b>l'attribution des matières</b>.
                                        </div>
                                        <div class="modal-footer flex-row-reverse">
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn spinner-submit-button btn-danger">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
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
    {{ $teachers->appends(request()->query())->links() }}
</div>