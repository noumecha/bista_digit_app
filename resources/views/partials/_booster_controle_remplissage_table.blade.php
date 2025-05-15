<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Classe
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Matière
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Evaluation
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Enseignant
            </th>
            <th
                class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Pourcentage de remplissage
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($traces->items()))
            <td class="text" colspan="5">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($traces as $trace)
                <tr>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $trace->classe->classe->libClasse }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $trace->matiere->matiere->codeMatiere }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom">
                        {{ $trace->evaluation->libelleEvaluation }}
                    </td>
                    <td class="align-middle bg-transparent border-bottom"
                    >
                        {{ $trace->teacher->teacher->name }}
                    </td>
                    <td class="align-middle bg-transparent pt-0 border-bottom">
                        <div class="progress w-100" role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                            style="height: 12px; width:100% !important;">
                            <div class="progress-bar
                                @if ((int)getBoosterRemplissagePourcentage($trace->classe_id, $trace->booster_matierer_id, $trace->evaluation_id) === 100)
                                    text-white text-bg-success
                                @elseif((int)getBoosterRemplissagePourcentage($trace->classe_id, $trace->booster_matierer_id, $trace->evaluation_id) < 50)
                                    text-white text-bg-danger progress-bar-animated progress-bar-striped
                                @else
                                    text-white text-bg-info progress-bar-animated progress-bar-striped
                                @endif
                                "
                                style="height: 12px; width: {{ getBoosterRemplissagePourcentage($trace->classe_id, $trace->booster_matierer_id, $trace->evaluation_id) }}%">
                                {{ getBoosterRemplissagePourcentage($trace->classe_id, $trace->booster_matierer_id, $trace->evaluation_id) }}%
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $traces->appends(request()->query())->links() }}
</div>