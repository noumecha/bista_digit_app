
<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Libellé
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Actions
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($sections->items()))
            <td colspan="2" class="text">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($sections as $section)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $section->libelleSection }}
                    </td>
                    <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-section-modal"
                            data-action="edit"
                            data-section-id="{{ $section->id }}"
                            data-section-libelle="{{ $section->libelleSection }}"
                            data-url="{{ route('section.store', $section->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button type="button" class="btn btn-danger ml-2 mt-3 p-2" data-bs-toggle="modal" data-bs-target="#confirmDelete-{{ $section->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $section->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form class="form" method="POST" action="{{ route('section.destroy', $section->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Année scolaire : {{ $section->libelleSection }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Faut-il vraiment supprimé la section {{ $section->libelleSection }}
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
    {{ !empty($sections) ? $sections->appends(request()->query())->links() : '' }}
</div>