<!-- bulletin footer -->
<div class="container-flex mt-3">
    <div class="row g-0">
        <div class="col-md-4 col-lg-4">
            <table border="2" class="w-100 h-100">
                <thead>
                    <tr>
                        <th class="text-capitalize text-center p-2 bg-light">
                            Décision du conseil de classe
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" class="text-start align-top p-2">
                            [Décision du conseil de classe]
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 50px;"></td>
                    </tr>
                    <tr>
                        <td style="height: 50px;"></td>
                    </tr>
                    <tr>
                        <td style="height: 50px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4 col-lg-4 d-flex flex-column">
            <table border="2" class="w-100">
                <thead>
                    <tr>
                        <th class="text-capitalize p-2 text-center bg-light">
                            Appréciation du travail
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-uppercase text-left p-2">
                            {{ $bulletin->appreciation }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <table border="2" class="w-100 h-100">
                <thead>
                    <tr>
                        <th class="text-capitalize p-2 text-center bg-light">
                            Visa professeur principal
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-uppercase text-center p-2">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4 col-lg-4">
            <table border="2" class="w-100 h-100">
                <thead>
                    <tr>
                        <th class="text-capitalize p-2 text-center bg-light">
                            Visa du chef d'établissement
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center pt-0 align-top p-2">
                            {{ $bulletin->appconfiguration->school_town }} le {{ Date('d/m/y') }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">Le principal</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3 container-flex header-bulletin bulletin-date-footer border-top border-dark border-3">
    <div class="row mt-2">
        <div class="col-md-6 col-lg-6 d-flex justify-content-start align-items-start flex-column">
            <p class="fs-6">&copy; {{ Date('Y') }} - POWEREDUCATION</p>
        </div>
        <div class="col-md-6 col-lg-6 d-flex justify-content-end align-items-end flex-column">
            <p class="fs-6">Imprimé le : {{ Date('d/m/y') }} à {{ Date('h:m:s') }}</p>
        </div>
    </div>
</div>