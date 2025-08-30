<!-- bulletin footer -->
<div class="footer-bulletin mt-2">
    <div class="row">
        <div class="col-33">
            <table class="border-2 w-100 h-100">
                <thead>
                    <tr>
                        <th class="text-capitalize text-center  border-2 p-0 m-0 bg-light">
                            Décision du conseil de classe
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="height: 65px;" class="text-start border-2 align-top p-0 m-0">
                            [Décision du conseil de classe]
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-33">
            <table class="border-2 w-100">
                <thead>
                    <tr>
                        <th class="text-capitalize p-0 m-0  border-2 text-center bg-light">
                            Appréciation du travail
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-uppercase border-2 text-left p-0 m-0">
                            {{ $bulletin->appreciation }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="border-2 w-100 h-100">
                <thead>
                    <tr>
                        <th class="text-capitalize p-0 m-0  border-2 text-center bg-light">
                            Visa professeur principal
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="height: 30px;" class="text-uppercase border-2 text-center p-0 m-0">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-34">
            <table class="border-2 w-100 h-100">
                <thead>
                    <tr>
                        <th class="text-capitalize border-2 p-0 m-0 text-center bg-light">
                            Visa du chef d'établissement
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="height: 65px" class="text-center border-2 pt-0 align-top p-0 m-0">
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