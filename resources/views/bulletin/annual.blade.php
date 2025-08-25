<x-report-card-layout :bulletin="$bulletin" :principal="$principal" :effectif="$effectif">
    <!-- bulletin content -->
    <div class="container-flex">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <!-- group 1 data -->
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="border-2 bg-light border-2 text-center p-0" colspan="6">
                                    Matieres
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Eval 1
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Eval 2
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Eval 3
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Eval 4
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Eval 5
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Eval 6
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Moy
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Coef
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Total
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Rang
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    MGC
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Min
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Max
                                </th>
                                <th class="border-2 border-2 bg-light text-center p-0">
                                    Appreciation
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- group 1 data -->
                            @foreach ($studentNotesFirstGroup as $firstGroupeNotes)
                            <tr>
                                <td colspan="6" class="border-2 text-left p-0">
                                    <h6 class="text-left text-uppercase mb-0">
                                        {{ $firstGroupeNotes->matiere->libelleMatiere }}
                                    </h6>
                                    <h6 class="text-left text-uppercase mb-0">
                                        {{ $firstGroupeNotes->matiere->getTeacher($firstGroupeNotes->classe_id) }}
                                    </h6>
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $firstGroupeNotes->eval1_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $firstGroupeNotes->eval2_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $firstGroupeNotes->eval3_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $firstGroupeNotes->eval4_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $firstGroupeNotes->eval5_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $firstGroupeNotes->eval6_note }}
                                </td>
                                <td class="border-2 bg-light text-center p-0">
                                    {{ $firstGroupeNotes->note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $firstGroupeNotes->matiere->getCoef($firstGroupeNotes->classe_id) }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $firstGroupeNotes->note * $firstGroupeNotes->matiere->getCoef($firstGroupeNotes->classe_id) }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    @if ($bulletin->student->sex->value === "F" && $firstGroupeNotes->rang === 1)
                                        {{ $firstGroupeNotes->rang }}<sup>ère</sup>
                                    @elseif ($bulletin->student->sex->value === "M" && $firstGroupeNotes->rang === 1)
                                        {{ $firstGroupeNotes->rang }}<sup>er</sup>
                                    @else
                                        {{ $firstGroupeNotes->rang }}<sup>e</sup>
                                    @endif
                                </td>
                                <td class="border-2 bg-light text-center p-0">
                                    {{ $firstGroupeNotes->mgc }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $firstGroupeNotes->min_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $firstGroupeNotes->max_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $firstGroupeNotes->appreciation }}
                                </td>
                            </tr>
                            @endforeach
                            <!-- group 1 resume -->
                            <tr>
                                <td colspan="6" class="text-center p-0">
                                    <h5 class="fw-bold">
                                        Resumé groupe 1 :
                                    </h5>
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center fw-bold p-0">
                                    <h5 class="fw-bold">
                                        {{ totalCoefGroup($studentNotesFirstGroup) }}
                                    </h5>
                                </td>
                                <td class="text-center fw-bold p-0">
                                    <h5 class="fw-bold">
                                        {{ totalNoteCoefGroup($studentNotesFirstGroup) }}
                                    </h5>
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td colspan="2" class="text-center p-0">
                                    <h5 class="fw-bold">
                                        Moyenne :
                                        @if (!$studentNotesFirstGroup->isEmpty())
                                            {{ bcdiv(totalNoteCoefGroup($studentNotesFirstGroup)/totalCoefGroup($studentNotesFirstGroup),1,2) }}/20
                                        @else
                                            00/20
                                        @endif
                                    </h5>
                                </td>
                            </tr>

                            <!-- group 2 data -->
                            @foreach ($studentNotesSndGroup as $sndGroupeNotes)
                            <tr>
                                <td colspan="6" class="border-2 text-left p-0">
                                    <h6 class="text-left text-uppercase mb-0">
                                        {{ $sndGroupeNotes->matiere->libelleMatiere }}
                                    </h6>
                                    <h6 class="text-left text-uppercase mb-0">
                                        {{ $sndGroupeNotes->matiere->getTeacher($sndGroupeNotes->classe_id) }}
                                    </h6>
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $sndGroupeNotes->eval1_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $sndGroupeNotes->eval2_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $sndGroupeNotes->eval3_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $sndGroupeNotes->eval4_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $sndGroupeNotes->eval5_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $sndGroupeNotes->eval6_note }}
                                </td>
                                <td class="border-2 bg-light text-center p-0">
                                    {{ $sndGroupeNotes->note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $sndGroupeNotes->matiere->getCoef($sndGroupeNotes->classe_id) }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $sndGroupeNotes->note * $sndGroupeNotes->matiere->getCoef($sndGroupeNotes->classe_id) }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    @if ($bulletin->student->sex->value === "F" && $sndGroupeNotes->rang === 1)
                                        {{ $sndGroupeNotes->rang }}<sup>ère</sup>
                                    @elseif ($bulletin->student->sex->value === "M" && $sndGroupeNotes->rang === 1)
                                        {{ $sndGroupeNotes->rang }}<sup>er</sup>
                                    @else
                                        {{ $sndGroupeNotes->rang }}<sup>e</sup>
                                    @endif
                                </td>
                                <td class="border-2 bg-light text-center p-0">
                                    {{ $sndGroupeNotes->mgc }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $sndGroupeNotes->min_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $sndGroupeNotes->max_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $sndGroupeNotes->appreciation }}
                                </td>
                            </tr>
                            @endforeach
                            <!-- group 2 resume -->
                            <tr>
                                <td colspan="6" class="text-center p-0">
                                    <h5 class="fw-bold">
                                        Resumé groupe 2 :
                                    </h5>
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center fw-bold p-0">
                                    <h5 class="fw-bold">
                                        {{ totalCoefGroup($studentNotesSndGroup) }}
                                    </h5>
                                </td>
                                <td class="text-center fw-bold p-0">
                                    <h5 class="fw-bold">
                                        {{ totalNoteCoefGroup($studentNotesSndGroup) }}
                                    </h5>
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td colspan="2" class="text-center p-0">
                                    <h5 class="fw-bold">
                                        Moyenne :
                                        @if (!$studentNotesSndGroup->isEmpty())
                                            {{ bcdiv(totalNoteCoefGroup($studentNotesSndGroup)/totalCoefGroup($studentNotesSndGroup),1,2) }}/20
                                        @else
                                            00/20
                                        @endif
                                    </h5>
                                </td>
                            </tr>

                            <!-- group 3 data -->
                            @foreach ($studentNotesThirdGroup as $thirdGroupeNotes)
                            <tr>
                                <td colspan="6" class="border-2 text-left p-0">
                                    <h6 class="text-left text-uppercase mb-0">
                                        {{ $thirdGroupeNotes->matiere->libelleMatiere }}
                                    </h6>
                                    <h6 class="text-left text-uppercase mb-0">
                                        {{ $thirdGroupeNotes->matiere->getTeacher($thirdGroupeNotes->classe_id) }}
                                    </h6>
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $thirdGroupeNotes->eval1_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $thirdGroupeNotes->eval2_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $thirdGroupeNotes->eval3_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $thirdGroupeNotes->eval4_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $thirdGroupeNotes->eval5_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $thirdGroupeNotes->eval6_note }}
                                </td>
                                <td class="border-2 bg-light text-center p-0">
                                    {{ $thirdGroupeNotes->note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $thirdGroupeNotes->matiere->getCoef($thirdGroupeNotes->classe_id) }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $thirdGroupeNotes->note * $thirdGroupeNotes->matiere->getCoef($thirdGroupeNotes->classe_id) }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    @if ($bulletin->student->sex->value === "F" && $thirdGroupeNotes->rang === 1)
                                        {{ $thirdGroupeNotes->rang }}<sup>ère</sup>
                                    @elseif ($bulletin->student->sex->value === "M" && $thirdGroupeNotes->rang === 1)
                                        {{ $thirdGroupeNotes->rang }}<sup>er</sup>
                                    @else
                                        {{ $thirdGroupeNotes->rang }}<sup>e</sup>
                                    @endif
                                </td>
                                <td class="border-2 bg-light text-center p-0">
                                    {{ $thirdGroupeNotes->mgc }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $thirdGroupeNotes->min_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $thirdGroupeNotes->max_note }}
                                </td>
                                <td class="border-2 text-center p-0">
                                    {{ $thirdGroupeNotes->appreciation }}
                                </td>
                            </tr>
                            @endforeach
                            <!-- group 3 resume -->
                            <tr>
                                <td colspan="6" class="text-center p-0">
                                    <h5 class="fw-bold">
                                        Resumé groupe 2 :
                                    </h5>
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center fw-bold p-0">
                                    <h5 class="fw-bold">
                                        {{ totalCoefGroup($studentNotesThirdGroup) }}
                                    </h5>
                                </td>
                                <td class="text-center fw-bold p-0">
                                    <h5 class="fw-bold">
                                        {{ totalNoteCoefGroup($studentNotesThirdGroup) }}
                                    </h5>
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td class="text-center p-0">
                                </td>
                                <td colspan="2" class="text-center p-0">
                                    <h5 class="fw-bold">
                                        Moyenne :
                                        @if (!$studentNotesThirdGroup->isEmpty())
                                            {{ bcdiv(totalNoteCoefGroup($studentNotesThirdGroup)/totalCoefGroup($studentNotesThirdGroup),1,2) }}/20
                                        @else
                                            00/20
                                        @endif
                                    </h5>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
    </div>
    <!-- bulletin trimestre stats -->
    <div class="container-flex mt-3">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <table border="2" class="w-100 h-100">
                        <thead>
                            <tr>
                                <th colspan="8" class="border-2 bg-light text-center p-0">
                                    Discipline
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border-2" colspan="4"></td>
                                <td class="border-2">Trim 1</td>
                                <td class="border-2">Trim 2</td>
                                <td class="border-2">Trim 3</td>
                                <td class="border-2">Total</td>
                            </tr>
                            <tr>
                                <td class="border-2" colspan="4">Abs. non Just. (h)</td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->absNonJust }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[1]->absNonJust }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[2]->absNonJust }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->absNonJust + $disciplines[1]->absNonJust + $disciplines[2]->absNonJust }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-2" colspan="4">Abs. Just. (h)</td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->absJust }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[1]->absJust }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[2]->absJust }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->absJust + $disciplines[1]->absJust + $disciplines[2]->absJust }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-2" colspan="4">Retards (h)</td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->retards }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[1]->retards }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[2]->retards }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->retards + $disciplines[1]->retards + $disciplines[2]->retards }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-2" colspan="4">Consignes (h)</td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->consignes }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[1]->consignes }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[2]->consignes }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->consignes + $disciplines[1]->consignes + $disciplines[2]->consignes }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-2" colspan="4">Avert.</td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->avertissements }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[1]->avertissements }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[2]->avertissements }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->avertissements + $disciplines[1]->avertissements + $disciplines[2]->avertissements }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-2" colspan="4">Blâme </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->blames }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[1]->blames }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[2]->blames }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->blames + $disciplines[1]->blames + $disciplines[2]->blames }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-2" colspan="4">Excl. (j)</td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->exclusions }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[1]->exclusions }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[2]->exclusions }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $disciplines[0]->exclusions + $disciplines[1]->exclusions + $disciplines[2]->exclusions }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-2" colspan="4">CD</td>
                                <td class="border-2 text-center">
                                    {{ $conseils[0] }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $conseils[1] }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $conseils[2] }}
                                </td>
                                <td class="border-2 text-center">
                                    {{ $conseils[0] + $conseils[1] + $conseils[2] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4 col-lg-4 d-flex flex-column">
                    <table border="2" class="w-100 h-100">
                        <thead>
                            <tr>
                                <th colspan="5" class="border-2 bg-light text-center p-0">
                                    Travail de l'élève
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-0 border-2" colspan="4"></td>
                                <td class="p-0 text-center fw-bold border-2" colspan="1">Moyenne / 20</td>
                            </tr>
                            @foreach ($bulletinsAvgs as $bul)
                                <tr>
                                    <td class="p-0 border-2" colspan="4">
                                        {{ $bul["trimestre_name"] }}
                                    </td>
                                    <td class="p-0 text-center fw-bold border-2" colspan="1">
                                        {{ bcdiv($bul["trimestre_average"],1,2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex flex-column">
                        <h5 class="text-center fw-bold">
                            Moyenne annuelle : {{ bcdiv($bulletin->average,1,2) }}
                        </h5>
                        <h5 class="text-center fw-bold">
                            Rang annuel :
                            @if ($bulletin->student->sex->value === "F" && $bulletin->range === 1)
                                {{ $bulletin->range }}<sup>ère</sup>
                            @elseif ($bulletin->student->sex->value === "M" && $bulletin->range === 1)
                                {{ $bulletin->range }}<sup>er</sup>
                            @else
                                {{ $bulletin->range }}<sup>ème</sup>
                            @endif
                            /{{ $bulletin->classe->effectif->getEffectif() }}
                        </h5>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <table border="2" class="w-100 h-100">
                        <thead>
                            <tr>
                                <th colspan="5" class="border-2 bg-light text-center p-0">
                                    Profil de la classe
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-0 border-2" colspan="4">Moy. gen. classe</td>
                                <td class="p-0 text-center fw-bold border-2" colspan="1">
                                    {{  bcdiv($bulletin->general_average,1,2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0 border-2" colspan="4">Moy. dernier</td>
                                <td class="p-0 text-center fw-bold border-2" colspan="1">
                                    {{  bcdiv($bulletin->min_average,1,2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0 border-2" colspan="4">Moy. premier</td>
                                <td class="p-0 text-center fw-bold border-2" colspan="1">
                                    {{  bcdiv($bulletin->max_average,1,2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0 border-2" colspan="4">Taux R.</td>
                                <td class="p-0 text-center fw-bold border-2" colspan="1">
                                    {{ bcdiv($bulletin->getWinPercent(),1,2) }} %
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0 border-2" colspan="4">Nbre. Moy.</td>
                                <td class="p-0 text-center fw-bold border-2" colspan="1">
                                    {{ $bulletin->totalNumberOfMoy() }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0 border-2" colspan="4">Ecart-type</td>
                                <td class="p-0 text-center fw-bold border-2" colspan="1">
                                    {{  bcdiv($bulletin->standard_deviation,1,2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
    <!-- calcul method text -->
    <div class="container-flex mt-3">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                <h5 class="text-left">
                    - Moy. Annuelle = SOMME(Moy. Trim /3)
                </h5>
            </div>
        </div>
    </div>
</x-report-card-layout>