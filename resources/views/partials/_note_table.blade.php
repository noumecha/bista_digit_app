@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
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
        @foreach ($students as $student)
            <tr>
                <td class="align-middle bg-transparent border-bottom">
                    {{ $student->name }}
                </td>
                <td class="align-middle bg-transparent border-bottom">
                    {{ $student->surname }}
                </td>
                <td class="align-middle bg-transparent borer-bottom">
                    <!-- @ dd($notes) -->
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
                    >
                </td>
                <td class="align-middle bg-transparent borer-bottom">
                    <input
                        type="text"
                        name="appreciation"
                        class="form-control appreciation-input"
                        id="appreciation-{{ $student->id }}"
                        value="{{ $studentNote->appreciation ?? '' }}"
                        disabled
                    >
                </td>
                <td class="text-center align-middle bg-transparent border-bottom">
                    <form data-student-id="{{ $student->id }}" method="POST" action="{{ route('evaluation.notesEdit') }}" class="note-form">
                        @csrf
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
                        <button id="save-note-button" type="submit" class="btn btn-primary p-2 mb-0">
                            <i class="fa-solid fa-floppy-disk"></i>
                        </button>
                        <button type="button" class="btn btn-secondary p-2 mb-0">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button id="delete-note-button" type="button" class="delete-note btn btn-danger p-2 mb-0">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $students->appends(request()->query())->links() }}
</div>
