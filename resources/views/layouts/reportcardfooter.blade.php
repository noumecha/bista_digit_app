<!-- bulletin footer -->
<div class="container-flex mt-3">
    <div class="row g-0">
        <div class="col-md-4 col-lg-4">
            <table class="border-2 w-100 h-100">
                <thead>
                    <tr>
                        <th class="text-capitalize text-center  border-2 p-2 bg-light">
                            Décision du conseil de classe
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="height: 30px;" class="text-start border-2 align-top p-2">
                            [Décision du conseil de classe]
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4 col-lg-4 d-flex flex-column">
            <table class="border-2 w-100">
                <thead>
                    <tr>
                        <th class="text-capitalize p-2  border-2 text-center bg-light">
                            Appréciation du travail
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-uppercase border-2 text-left p-2">
                            {{ $bulletin->appreciation }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="border-2 w-100 h-100">
                <thead>
                    <tr>
                        <th class="text-capitalize p-2  border-2 text-center bg-light">
                            Visa professeur principal
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="height: 30px;" class="text-uppercase border-2 text-center p-2">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4 col-lg-4">
            <table class="border-2 w-100 h-100">
                <thead>
                    <tr>
                        <th class="text-capitalize border-2 p-2 text-center bg-light">
                            Visa du chef d'établissement
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="height: 30px" class="text-center border-2 pt-0 align-top p-2">
                            {{ $bulletin->appconfiguration->school_town }} le {{ Date('d/m/y') }}
                            <div class="mt-8 text-center text-bottom">
                                Le principal
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3 container-flex header-bulletin bulletin-date-footer border-top border-dark">
    <div class="row mt-2">
        <div class="col-md-6 col-lg-6 d-flex justify-content-start align-items-start flex-column">
            <p class="fs-6">&copy; {{ Date('Y') }} - POWEREDUCATION</p>
        </div>
        <div class="col-md-6 col-lg-6 d-flex justify-content-end align-items-end flex-column">
            <p class="fs-6">Imprimé le : {{ Date('d/m/y') }} à {{ Date('h:m:s') }}</p>
        </div>
    </div>
</div>