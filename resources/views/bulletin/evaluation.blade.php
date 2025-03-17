<x-report-card-layout :bulletin="$bulletin">
    <!-- bulletin content -->
    <div class="container-flex header-bulletin">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <table class="w-100">
                    <thead>
                        <tr>
                            <th class="bg-body-secondary border-2 text-center p-2" colspan="8">
                                Matieres
                            </th>
                            <th class="border-2 bg-body-secondary text-center p-2" colspan="7">
                                Note
                            </th>
                            <th class="border-2 bg-body-secondary text-center p-2">
                                Coef
                            </th>
                            <th class="border-2 bg-body-secondary text-center p-2">
                                Total
                            </th>
                            <th class="border-2 bg-body-secondary text-center p-2">
                                Rang
                            </th>
                            <th class="border-2 bg-body-secondary text-center p-2">
                                MGC
                            </th>
                            <th class="border-2 bg-body-secondary text-center p-2">
                                Min
                            </th>
                            <th class="border-2 bg-body-secondary text-center p-2">
                                Max
                            </th>
                            <th class="border-2 bg-body-secondary text-center p-2">
                                Appreciation
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- group 1 data -->
                        @foreach ($studentNotesFirstGroup as $firstGroupeNotes)
                            <tr>
                                <td colspan="8" class="border-2 text-center p-2">
                                    <h6 class="text-left text-uppercase">
                                        Informatique
                                    </h6>
                                    <h6 class="text-left text-uppercase">
                                        M. Noumecha
                                    </h6>
                                </td>
                                <td class="border-2 bg-body-secondary text-center p-2" colspan="7">
                                    {{ $firstGroupeNotes->note }}
                                </td>
                                <td class="border-2 text-center p-2">
                                    3
                                </td>
                                <td class="border-2 text-center p-2">
                                    5
                                </td>
                                <td class="border-2 text-center p-2">
                                    13
                                </td>
                                <td class="border-2 bg-body-secondary text-center p-2">
                                    13
                                </td>
                                <td class="border-2 text-center p-2">
                                    5
                                </td>
                                <td class="border-2 text-center p-2">
                                    15
                                </td>
                                <td class="border-2 text-center p-2">
                                    CNA
                                </td>
                            </tr>
                        @endforeach
                        <!-- group 1 resume -->
                        <tr>
                            <td colspan="8" class="text-center p-2">
                                <h5 class="fw-bold">
                                    Resumé groupe 1 :
                                </h5>
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center fw-bold p-2">
                                <h5 class="fw-bold">
                                    3
                                </h5>
                            </td>
                            <td class="text-center fw-bold p-2">
                                <h5 class="fw-bold">
                                    10
                                </h5>
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td colspan="2" class="text-center p-2">
                                <h5 class="fw-bold">
                                    Moyenne : [xx/20]
                                </h5>
                            </td>
                        </tr>

                        <!-- group 2 data -->
                        <tr>
                            <td colspan="8" class="border-2 text-center p-2">
                                <h6 class="text-left text-uppercase">
                                    Informatique
                                </h6>
                                <h6 class="text-left text-uppercase">
                                    M. Noumecha
                                </h6>
                            </td>
                            <td class="border-2 bg-body-secondary text-center p-2" colspan="7">
                                10
                            </td>
                            <td class="border-2 text-center p-2">
                                3
                            </td>
                            <td class="border-2 text-center p-2">
                                5
                            </td>
                            <td class="border-2 text-center p-2">
                                13
                            </td>
                            <td class="border-2 bg-body-secondary text-center p-2">
                                13
                            </td>
                            <td class="border-2 text-center p-2">
                                5
                            </td>
                            <td class="border-2 text-center p-2">
                                15
                            </td>
                            <td class="border-2 text-center p-2">
                                CNA
                            </td>
                        </tr>
                        <!-- group 2 resume -->
                        <tr>
                            <td colspan="8" class="text-center p-2">
                                <h5 class="fw-bold">
                                    Resumé groupe 2 :
                                </h5>
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center fw-bold p-2">
                                <h5 class="fw-bold">
                                    3
                                </h5>
                            </td>
                            <td class="text-center fw-bold p-2">
                                <h5 class="fw-bold">
                                    10
                                </h5>
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td colspan="2" class="text-center p-2">
                                <h5 class="fw-bold">
                                    Moyenne : [xx/20]
                                </h5>
                            </td>
                        </tr>


                        <!-- group 3 data -->
                        <tr>
                            <td colspan="8" class="border-2 text-center p-2">
                                <h6 class="text-left text-uppercase">
                                    Informatique
                                </h6>
                                <h6 class="text-left text-uppercase">
                                    M. Noumecha
                                </h6>
                            </td>
                            <td class="border-2 bg-body-secondary text-center p-2" colspan="7">
                                10
                            </td>
                            <td class="border-2 text-center p-2">
                                3
                            </td>
                            <td class="border-2 text-center p-2">
                                5
                            </td>
                            <td class="border-2 text-center p-2">
                                13
                            </td>
                            <td class="border-2 bg-body-secondary text-center p-2">
                                13
                            </td>
                            <td class="border-2 text-center p-2">
                                5
                            </td>
                            <td class="border-2 text-center p-2">
                                15
                            </td>
                            <td class="border-2 text-center p-2">
                                CNA
                            </td>
                        </tr>
                        <!-- group 3 resume -->
                        <tr>
                            <td colspan="8" class="text-center p-2">
                                <h5 class="fw-bold">
                                    Resumé groupe 3 :
                                </h5>
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center fw-bold p-2">
                                <h5 class="fw-bold">
                                    3
                                </h5>
                            </td>
                            <td class="text-center fw-bold p-2">
                                <h5 class="fw-bold">
                                    10
                                </h5>
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td class="text-center p-2">
                            </td>
                            <td colspan="2" class="text-center p-2">
                                <h5 class="fw-bold">
                                    Moyenne : [xx/20]
                                </h5>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- bulletin trimestre stats -->
    <div class="container-flex mt-3 header-bulletin">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                <table border="2" class="w-100 h-100">
                    <thead>
                        <tr>
                            <th colspan="8" class="bg-body-secondary text-center p-2">
                                Discipline
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2 border-2" colspan="4"></td>
                            <td class="p-2 border-2">[Eval-X-Trim-x]</td>
                            <td class="p-2 border-2">Total</td>
                        </tr>
                        <tr>
                            <td class="p-2 border-2" colspan="4">Abs. non Just. (h)</td>
                            <td class="p-2 border-2">[0]</td>
                            <td class="p-2 border-2">[0]</td>
                        </tr>
                        <tr>
                            <td class="p-2 border-2" colspan="4">Abs. Just. (h)</td>
                            <td class="p-2 border-2">[0]</td>
                            <td class="p-2 border-2">[0]</td>
                        </tr>
                        <tr>
                            <td class="p-2 border-2" colspan="4">Retards (h)</td>
                            <td class="p-2 border-2">[0]</td>
                            <td class="p-2 border-2">[0]</td>
                        </tr>
                        <tr>
                            <td class="p-2 border-2" colspan="4">Consignes (h)</td>
                            <td class="p-2 border-2">[0]</td>
                            <td class="p-2 border-2">[0]</td>
                        </tr>
                        <tr>
                            <td class="p-2 border-2" colspan="4">Avert.</td>
                            <td class="p-2 border-2">[0]</td>
                            <td class="p-2 border-2">[0]</td>
                        </tr>
                        <tr>
                            <td class="p-2 border-2" colspan="4">Blâme </td>
                            <td class="p-2 border-2">[0]</td>
                            <td class="p-2 border-2">[0]</td>
                        </tr>
                        <tr>
                            <td class="p-2 border-2" colspan="4">Excl. (j)</td>
                            <td class="p-2 border-2">[0]</td>
                            <td class="p-2 border-2">[0]</td>
                        </tr>
                        <tr>
                            <td class="p-2 border-2" colspan="4">CD</td>
                            <td class="p-2 border-2">[0]</td>
                            <td class="p-2 border-2">[0]</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4 col-lg-4 d-flex flex-column">
                <table border="2" class="w-100 h-100">
                    <thead>
                        <tr>
                            <th colspan="5" class="bg-body-secondary text-center p-2">
                                Travail de l'élève
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-1 border-2" colspan="4"></td>
                            <td class="p-1 text-center fw-bold border-2" colspan="1">Moyenne / 20</td>
                        </tr>
                        <tr>
                            <td class="p-1 border-2" colspan="4">[Evaluation-x-trim-x]</td>
                            <td class="p-1 text-center fw-bold border-2" colspan="1">[moy-eval-x-trim-x]</td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex flex-column">
                    <h5 class="text-center fw-bold">
                        Moyenne [Evalauation-x-trim-x] : [moy-eval-x-trim-x]
                    </h5>
                    <h5 class="text-center fw-bold">
                        Rang [Evaluation-x-trim-x] : [rang-eval-x-trim-x]
                    </h5>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <table border="2" class="w-100 h-100">
                    <thead>
                        <tr>
                            <th colspan="5" class="bg-body-secondary text-center p-2">
                                Profil de la classe
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-1 border-2" colspan="4">Moy. gen. classe</td>
                            <td class="p-1 text-center fw-bold border-2" colspan="1">[moy-gen-class]</td>
                        </tr>
                        <tr>
                            <td class="p-1 border-2" colspan="4">Moy. dernier</td>
                            <td class="p-1 text-center fw-bold border-2" colspan="1">[moy-der]</td>
                        </tr>
                        <tr>
                            <td class="p-1 border-2" colspan="4">Moy. premier</td>
                            <td class="p-1 text-center fw-bold border-2" colspan="1">[moy-1er]</td>
                        </tr>
                        <tr>
                            <td class="p-1 border-2" colspan="4">Taux R.</td>
                            <td class="p-1 text-center fw-bold border-2" colspan="1">[taux-reuss]</td>
                        </tr>
                        <tr>
                            <td class="p-1 border-2" colspan="4">Nbre. Moy.</td>
                            <td class="p-1 text-center fw-bold border-2" colspan="1">[nombre-moy]</td>
                        </tr>
                        <tr>
                            <td class="p-1 border-2" colspan="4">Ecart-type</td>
                            <td class="p-1 text-center fw-bold border-2" colspan="1">[ecart-type]</td>
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
                    - Moy. Eval. = SOMME(Coef x Matiere )/SOMME(coefs)
                </h5>
            </div>
        </div>
    </div>
</x-report-card-layout>