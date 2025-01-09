
<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Libellé
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Date de debut
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Date de fin
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Statut
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Actions
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($years->items()))
            <td colspan="5" class="text">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($years as $year)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $year->libelleAnneeScolaire }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $year->dateDeDebut ? date('d M Y', strtotime($year->dateDeDebut)) : '' }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{  $year->dateDeFin ? date('d M Y', strtotime($year->dateDeFin)) : '' }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        <span class="badge rounded-pill {{ $year->statut ? 'bg-success' : 'bg-danger'}}">
                            {{ $year->statut ? 'Activé' : 'Désactivé'}}
                        </span>
                    </td>
                    <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-year-modal"
                            data-action="edit"
                            data-year-id="{{ $year->id }}"
                            data-year-libelle="{{ $year->libelleAnneeScolaire }}"
                            data-url="{{ route('annee_scolaire.store', $year->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button type="button" class="btn btn-danger ml-2 mt-3 p-2" data-bs-toggle="modal" data-bs-target="#confirmDelete-{{ $year->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <form role="form" class="activation-form" method="POST" action="{{ !$year->statut ? route('annee_scolaire.activate', $year->id) : route('annee_scolaire.desactivate', $year->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="" class="spinner-submit-button btn {{ !$year->statut ? 'btn-success' : 'btn-danger'}} mt-3 p-2">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                {{ !$year->statut ? 'Activer' : 'Désactiver' }}
                            </button>
                        </form>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $year->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form class="form" method="POST" action="{{ route('annee_scolaire.destroy', $year->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Année scolaire : {{ $year->libelleAnneeScolaire }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Faut-il vraiment supprimé l'année scolaire {{ $year->libelleAnneeScolaire }}
                                            avec toutes ses données ? (Cette action est irreversible)
                                        </div>
                                        <div class="modal-footer flex-row-reverse">
                                            <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="spinner-submit-modal-button btn btn-danger">
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
    {{ !empty($years) ? $years->appends(request()->query())->links() : '' }}
</div>