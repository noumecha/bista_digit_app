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
                    <!-- @ dd($notes)-->
                    @if (!empty($notes->items))
                        @foreach ($notes as $note)
                            @if (isset($note) && $note->user_id === $student->id)
                                <input type="number" name="note" id="note" value={{ $note->note }}>
                            @endif
                        @endforeach
                    @else
                        <input type="number" name="note" id="note" value="">
                    @endif
                </td>
                <td class="align-middle bg-transparent borer-bottom">
                    @if (!empty($notes->items))
                        @foreach ($notes as $note)
                            @if (isset($note) && $note->user_id === $student->id)
                                <input type="text" name="appreciation" class="form-control" id="appreciation" value="{{ $note->appreciation }}" disabled>
                            @endif
                        @endforeach
                    @else
                        <input type="text" name="appreciation" class="form-control" id="appreciation" value="" disabled>
                    @endif
                </td>
                <td class="text-center align-middle bg-transparent border-bottom">
                    <button class="btn btn-primary p-2 mb-0">
                        <i class="fa-solid fa-floppy-disk"></i>
                    </button>
                    <button class="btn btn-secondary p-2 mb-0">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="btn btn-danger p-2 mb-0">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $students->appends(request()->query())->links() }}
</div>
