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
                Heures Absences
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Justifiées
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Total
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Avertissement
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Decision
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($disciplines->items()))
            <td class="text" colspan="8">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($disciplines as $discipline)
                <tr class="{{ $discipline->total_absences >= 30 ? 'table-danger bg-danger text-white' : '' }}">
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $discipline->eleve->name }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ monthNameToFrench($discipline->mois) }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $discipline->heures_absence }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $discipline->heures_justifiees }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $discipline->total_absences }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $discipline->avertissement }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $discipline->decision }}
                    </td>
                    <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-discipline-modal"
                            data-action="edit"
                            data-discipline-id = "{{ $discipline->id }}"
                            data-student-name = "{{ $discipline->eleve->name }}"
                            data-url="{{ route('discipline.store', $discipline->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $discipline->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $discipline->id }}" tabindex="-1" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('discipline.destroy', $discipline->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimée les informations sur la discipline de {{ $discipline->eleve->name }}
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
    {{ $disciplines->appends(request()->query())->links() }}
</div>