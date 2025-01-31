<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Matricule</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Photo</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Nom</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Email</th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Telephone</th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Classe</th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($students->items()))
            <td class="text" colspan="7">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($students as $student)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $student->matricule }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="{{ asset('storage/' . $student->profile) }}" class="rounded-circle mr-2"
                                alt="user1" style="height: 36px; width: 36px;">
                        </div>
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $student->name }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $student->email }}
                    </td>
                    <td class="text-center align-middle bg-transparent border-bottom">
                        {{ $student->phone }}
                    </td>
                    <td class="text-center align-middle bg-transparent border-bottom">
                        @foreach ($student->classes as $c)
                            @foreach ($classes as $classe)
                                @if ($classe->id === $c->pivot->classe_id && $activeYear->id === $c->pivot->annee_scolaire_id)
                                    {{ $classe->libClasse }}
                                @endif
                            @endforeach
                        @endforeach
                    </td>
                    <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-student-modal"
                            data-action="edit"
                            data-student-id="{{ $student->id }}"
                            data-year-id="{{ $activeYear->id }}"
                            data-student-name="{{ $student->name }}"
                            data-url="{{ route('student.store', $student->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $student->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <div onclick="showDropdown(this)" id="ddown-menu" class="ddown-menu d-flex btn btn-transparent ml-2 mt-3 p-2">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                            <div class="ddown-items-container d-none p-2 bg-dark">
                                <button
                                    type="button"
                                    class="mb-0 p-2 btn text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmMigrate-{{ $student->id }}"
                                >
                                    Migrer
                                </button>
                                <button
                                    type="button"
                                    class="mb-0 p-2 btn text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteYear-{{ $student->id }}"
                                    {{ $activeYear->id === $student->create_year_id ? 'disabled' : '' }}
                                >
                                    Supprimer pour l'année
                                </button>
                                <a class="mb-0 p-2 btn text-white" href="#">
                                    Informations
                                </a>
                            </div>
                        </div>
                        <!-- modal for migrate user to annother year -->
                        <div class="modal fade" data-form-id="{{ $student->id }}" id="confirmMigrate-{{ $student->id }}" tabindex="-1" aria-labelledby="migrateModal" >
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('student.studentMigrate') }}">
                                    @csrf
                                    <div class="modal-content p-0">
                                        <div class="modal-header bg-dark">
                                            <h5 class="modal-title text-white" id="migrateModal">Confirmation de migration</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            </button>
                                        </div>
                                        <div class="alert text-wrap alert-success text-center" style="display: none;" id="modal-alert-success-{{ $student->id }}">
                                        </div>
                                        <div class="alert text-wrap alert-danger text-center" style="display: none;" id="modal-alert-errors-{{ $student->id }}">
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            <h5>Vous êtes sur le point d'ajouter l'élève {{ $student->name }} à une année ultérieure!</h5>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="hidden" name="migrate_current_year_id" id="migrate_current_year_id" value="{{ $activeYear->id }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="migrate_user_id" id="migrate_user_id" value="{{ $student->id }}">
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
                                            <button type="button" data-personnel-id="{{ $student->id }}" class="spinner-submit-modal-button btn btn-dark">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" ></span>
                                                Confirmer
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- modal for delete student for the current year -->
                        <div class="modal fade" data-form-id="{{ $student->id }}" id="confirmDeleteYear-{{ $student->id }}" tabindex="-1" aria-labelledby="migrateModal" >
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('student.studentDeleteUserCurrentYear') }}">
                                    @csrf
                                    <div class="modal-content p-0">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-wrap text-justify text-white" id="exampleModalLabel">Suppresion de l'élève de l'année scolaire courrante</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <input type="hidden" name="delusyear_year_id" value="{{ $activeYear->id }}">
                                        <input type="hidden" name="delusyear_user_id" value="{{ $student->id }}">
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimér l'élève {{ $student->name }}
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
                        <div class="modal fade" id="confirmDelete-{{ $student->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" >
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('student.destroy', $student->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimée définitivement l'élève {{ $student->name }} ?
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
    {{ $students->appends(request()->query())->links() }}
</div>