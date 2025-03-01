<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Nom</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Prenom
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Note
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Appreciation
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($students->items()))
            <td class="text" colspan="5">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($students as $student)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $student->name }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $student->surname }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        @php
                            $studentNote = $notes->firstWhere('user_id', $student->id);
                        @endphp
                        <input
                            type="number"
                            class="note-input"
                            name="note"
                            max="20"
                            step="0.25"
                            min="0"
                            id="note-{{ $student->id }}"
                            value="{{ isset($studentNote) ? $studentNote->note : '' }}"
                            data-student-id="{{ $student->id }}"
                            {{ isset($studentNote) ? 'disabled' : '' }}
                        >
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        <input
                            type="text"
                            name="appreciation"
                            class="form-control appreciation-input"
                            id="appreciation-{{ $student->id }}"
                            value="{{ $studentNote->appreciation ?? '' }}"
                            disabled
                        >
                    </td>
                    <td class="text-center d-flex justify-content-evenly align-items-center align-middle bg-transparent border-bottom">
                        <form role="form" class="form" id="note-form">
                            @csrf
                            <input type="hidden" id="note_id" name="note_id" value="{{ isset($studentNote) ? $studentNote->id : '' }}">
                            <input type="hidden" name="user_id" value="{{ $student->id }}">
                            <input type="hidden" name="matiere_id" value="{{ $matiereFilter }}">
                            <input
                                type="hidden"
                                name="evaluation_id"
                                @php
                                    if(isset($remplissageFilter)) {
                                        $evaluationId = $remplissages->firstWhere('id', $remplissageFilter);
                                    }
                                @endphp
                                value="{{ isset($evaluationId) ? $evaluationId->evaluation->id : '' }}"
                            >
                            <input type="hidden" name="remplissage_id" value="{{ isset($remplissageFilter) ? $remplissageFilter : '' }}">
                            <input type="hidden" name="classe_id" value="{{ $student->classe_id }}">
                            <input type="hidden" id="note-input-{{ $student->id }}" name="note" value="">
                            <input type="hidden" id="appreciation-input-{{ $student->id }}" name="appreciation" value="">
                            <button
                                id="save-note-button"
                                type="button"
                                data-student-id="{{ $student->id }}"
                                class="btn btn-primary p-2 mb-0"
                                {{ !isset($matiereFilter) || !isset($remplissageFilter) || !isset($classeFilter) || isset($studentNote) ? 'disabled' : '' }}
                            >
                                <i class="fa-solid fa-floppy-disk" id="button-icon"></i>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </form>
                        <button
                            type="button"
                            id="edit-note-button"
                            data-bs-toggle="modal"
                            data-bs-target="#update-note-modal-{{ isset($studentNote) ? $studentNote->id : '' }}"
                            class="btn btn-secondary p-2 mb-0"
                            data-student-id="{{ $student->id }}"
                            {{ !isset($matiereFilter) || !isset($remplissageFilter) || !isset($classeFilter)  || !isset($studentNote) ? 'disabled' : '' }}
                        >
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ isset($studentNote) ? $studentNote->id : '' }}"
                            {{ !isset($studentNote) ? 'disabled' : '' }}
                        >
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <!-- modal for delete confirmation if note exist -->
                        @if(isset($studentNote))
                            <div class="modal fade" id="confirmDelete-{{ isset($studentNote) ? $studentNote->id : '' }}" tabindex="-1" aria-labelledby="exampleModalLabel">
                                <div class="modal-dialog">
                                    <form role="form" class="form" method="POST" action="{{ route('evaluation.notesDestroy', $studentNote->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title text-white" id="exampleModalLabel">Supprimer la note</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-wrap text-justify">
                                                Voulez-vous vraiment supprimée la note de {{ $student->name }}
                                                {{ $student->surname }} en {{ $studentNote->matiere->libelleMatiere }} de
                                                l' {{ $studentNote->evaluation->libelleEvaluation }} ?
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
                        @endif
                        <!-- modal for updating a note -->
                        @if(isset($studentNote))
                            <div class="modal fade" data-student-id="{{ $student->id }}" id="update-note-modal-{{ isset($studentNote) ? $studentNote->id : '' }}" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form enctype="multipart/form-data" role="form" id="updateNoteForm" class="form row">
                                        @csrf
                                        <input type="hidden" name="noteId" id="noteId" value="{{ $studentNote->id }}">
                                        <div class="modal-content p-0">
                                            <div class="modal-header bg-success">
                                                <div class="modal-title row">
                                                    <div class="col-12">
                                                        <h5 class="text-white text-justify text-wrap">
                                                            Vous modifier la note de {{ $student->name }} {{ $student->surname }}
                                                            en {{ $studentNote->matiere->libelleMatiere }} pour le compte de
                                                            l'évaluation : {{ $studentNote->evaluation->libelleEvaluation }}.
                                                        </h5>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12 text-start">
                                                        <div class="form-group">
                                                            <label for="new_value" class="form-control-label">
                                                                Nouvelle note :
                                                            </label>
                                                            <input
                                                                type="number"
                                                                name="new_value"
                                                                id="new_value-{{ $student->id }}"
                                                                data-student-id="{{ $student->id }}"
                                                                class="form-control new_value"
                                                                max="20"
                                                                step="0.25"
                                                                min="0"
                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 text-start">
                                                        <div class="form-group">
                                                            <label for="date_debut" class="form-control-label">
                                                                Nouvelle appréciation :
                                                            </label>
                                                            <input
                                                                type="text"
                                                                id="update-appreciation-{{ $student->id }}"
                                                                name="appreciation"
                                                                class="form-control"
                                                                value=""
                                                                readonly
                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 text-start">
                                                        <div class="form-group">
                                                            <label for="reason" class="form-control-label">
                                                                Raison du changement de la note :
                                                            </label>
                                                            <input
                                                                type="text"
                                                                id="reason-{{ $student->id }}"
                                                                name="reason"
                                                                class="form-control"
                                                                value=""
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="alert text-start alert-success" style="display: none;" id="note-modal-form-alert-success-{{ $studentNote->id }}">
                                                </div>
                                                <div class="alert text-start alert-danger" style="display: none;" id="note-modal-form-alert-errors-{{ $studentNote->id }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer flex-row-reverse">
                                                <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                                                <button type="button" id="submit-udpate-note-form-button" class="spinner-submit-update-note-form-button btn-outline-success btn btn-lg">
                                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                    Mettre à jour
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $students->appends(request()->query())->links() }}
</div>
