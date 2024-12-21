
<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                ID</th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Libellé</th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($fonctions->items()))
            <td colspan="6" class="text">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($fonctions as $fonction)
            <tr>
                <td class="align-middle bg-transparent border-bottom">
                    {{ $fonction->id }}
                </td>
                <td class="align-middle bg-transparent border-bottom">
                    {{ $fonction->libelleFonction }}
                </td>
                <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                    <a
                        data-bs-toggle="modal"
                        id="edit-button"
                        data-bs-target="#create-fonction-modal"
                        data-action="edit"
                        data-fonction-id="{{ $fonction->id }}"
                        data-fonction-name="{{ $fonction->libelleFonction }}"
                        data-url="{{ route('utilisateur.fonctionStore', $fonction->id) }}"
                        class="btn btn-primary mt-3 p-2"
                        href="#"
                    >
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <button
                        type="button"
                        class="btn btn-danger ml-2 mt-3 p-2"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmDelete-{{ $fonction->id }}"
                    >
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <!-- modal for delete confirmation -->
                    <div class="modal fade" id="confirmDelete-{{ $fonction->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form role="form" id="delete-form" class="form" method="POST" action="{{ route('utilisateur.fonctionDestroy', $fonction->id) }}">
                                @csrf
                                @method('DELETE')
                                <div class="modal-content p-0">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">Supprimer définitivement la fonction</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-wrap text-justify">
                                        Voulez-vous vraiment supprimée la fonction :
                                        {{ $fonction->libelleFonction }} ? (Cette action est irreversible)
                                    </div>
                                    <div class="modal-footer flex-row-reverse">
                                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn spinner-submit-button btn-danger">
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
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
    {{ !empty($fonctions) ? $fonctions->appends(request()->query())->links() : '' }}
</div>