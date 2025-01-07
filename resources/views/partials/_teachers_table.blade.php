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
                            data-bs-target="#create-year-modal"
                            data-action="edit"
                            data-teacher-id="{{ $teacher->id }}"
                            data-teacher-name="{{ $teacher->name }}"
                            data-url="{{ route('teacher.edit', $teacher->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button type="button" class="btn btn-danger ml-2 mt-3 p-2" data-bs-toggle="modal" data-bs-target="#confirmDelete-{{ $teacher->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <!-- modal for delete confirmation -->
                <div class="modal fade" id="confirmDelete-{{ $teacher->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Confirmation de suppression</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Voulez-vous vraiment supprimée l'enseignant {{ $teacher->name }} ?
                                (Cette action est irreversible)
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Annuler</button>
                                <form role="form" class="form" method="POST" action="{{ route('teacher.destroy', $teacher->id) }}">
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
    {{ $teachers->appends(request()->query())->links() }}
</div>