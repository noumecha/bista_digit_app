
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
                        {{ $student->classe->libClasse }}
                    </td>
                    <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                        <a class="btn btn-primary mt-3 p-2" href="{{ route('student.edit', $student->id) }}">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button type="button" class="btn btn-danger ml-2 mt-3 p-2" data-bs-toggle="modal" data-bs-target="#confirmDelete-{{ $student->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <!-- modal for delete confirmation -->
                <div class="modal fade" id="confirmDelete-{{ $student->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Confirmation de suppression</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Voulez-vous vraiment supprimée l'élève {{ $student->name }} ?
                                (Cette action est irreversible)
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Annuler</button>
                                <form role="form" class="form" method="POST" action="{{ route('student.destroy', $student->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="showSpinner(this)" class="btn btn-success">
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        Confirmer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $students->appends(request()->query())->links() }}
</div>