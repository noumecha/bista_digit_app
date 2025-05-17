<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Libellé</th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Section
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Effectif
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Cycle
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($boosterclasses->items()))
            <td class="text" colspan="5">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($boosterclasses as $boosterclasse)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $boosterclasse->classe->libClasse }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $boosterclasse->classe->section->libelleSection }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $boosterclasse->getEffectif(getCurrentYear()->id) ?
                        $boosterclasse->getEffectif(getCurrentYear()->id) : 0 }}
                    </td>
                    <td class="align-middle bg-transparent borer-bottom">
                        {{ $boosterclasse->classe->cycleClasse }}
                    </td>
                    <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-boosterclasse-modal"
                            data-action="edit"
                            data-boosterclasse-id = "{{ $boosterclasse->id }}"
                            data-url="{{ route('booster.classeSave', $boosterclasse->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $boosterclasse->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $boosterclasse->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" >
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('booster.classeDelete', $boosterclasse->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimée définitivement la classe du programme ?
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
    {{ $boosterclasses->appends(request()->query())->links() }}
</div>