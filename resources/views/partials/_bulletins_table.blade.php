<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Elève
            </th>
            <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Classe
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Evaluation
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Trimestre
            </th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($bulletins->items()))
            <td class="text" colspan="5">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($bulletins as $bulletin)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $bulletin->student->name }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $bulletin->classe->libClasse }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $bulletin->evaluation->libelleEvaluation }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $bulletin->trimestre->libelleTrimestre }}
                    </td>
                    <td class="text-center align-middle bg-transparent border-bottom">
                        <a
                            data-bs-toggle="modal"
                            id="edit-button"
                            data-bs-target="#create-bulletin-modal"
                            data-action="edit"
                            data-bulletin-id = "{{ $bulletin->id }}"
                            data-url="{{ route('bulletins.update', $bulletin->id) }}"
                            class="btn btn-primary mt-3 p-2"
                            href="#"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a
                            class="btn btn-primary mt-3 p-2"
                            id="bulletin-preview"
                            data-bs-toggle="modal"
                            href="#"
                            data-bs-target="#bulletinModal-{{ $bulletin->id }}"
                        >
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger ml-2 mt-3 p-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDelete-{{ $bulletin->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <!-- modal for delete confirmation -->
                        <div class="modal fade" id="confirmDelete-{{ $bulletin->id }}" tabindex="-1" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog">
                                <form role="form" class="form" method="POST" action="{{ route('bulletins.destroy', $bulletin->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-wrap text-justify">
                                            Voulez-vous vraiment supprimée la le bulletin de l'élève : {{ $bulletin->student->name }} ?
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
                        <!-- modal for preview -->
                        <div class="modal fade" id="bulletinModal-{{ $bulletin->id }}" tabindex="-1" aria-labelledby="bulletinModalLabel">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark">
                                        <h5 class="modal-title text-white">Prévisualisation du Bulletin de l'élève {{ $bulletin->student->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <iframe src="/bulletins/preview/{{ $bulletin->id }}" width="100%" height="600px"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $bulletins->appends(request()->query())->links() }}
</div>