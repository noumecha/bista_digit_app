<table class="table table-responsive text-secondary text-center table-hover">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Elèves</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Mois
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Dates
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Motifs
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Decisions
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($conseilDisciplines->items()))
            <td class="text" colspan="6">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($conseilDisciplines as $conseildiscipline)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $conseildiscipline->eleve->name }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ monthNameToFrench($conseildiscipline->mois) }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $conseildiscipline->date_conseil }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $conseildiscipline->motif }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $conseildiscipline->decision }}
                    </td>
                    <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-conseildiscipline-modal"
                            data-action="edit"
                            data-conseildiscipline-id = "{{ $conseildiscipline->id }}"
                            data-student-name = "{{ $conseildiscipline->eleve->name }}"
                            data-url="{{ route('conseildiscipline.store', $conseildiscipline->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $conseildiscipline->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $conseildiscipline->id }}" tabindex="-1" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('conseildiscipline.destroy', $conseildiscipline->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimée le rapport du conseil de discipline de {{ $conseildiscipline->eleve->name }}
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
    {{ $conseilDisciplines->appends(request()->query())->links() }}
</div>