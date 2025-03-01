<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Bulletin de [nom]</title>
        <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('front/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/owl.carousel.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/add.css') }}" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body class="m-3">
        <!-- header information -->
        <div class="container-flex header-bulletin">
            <div class="row">
                <div class="col-md-5 d-flex flex-column">
                    <h6 class="m-0 text-center text-uppercase">Republique du cameroun</h6>
                    <p class="fs-6 mb-0 text-center">paix-travail-patrie</p>
                    <h6 class="text-center text-uppercase">Ministère des enseignements secondaire</h6>
                    <h5 class="text-center fw-bold text-uppercase">[nom-etablissement]</h5>
                    <p class="fs-6 mb-0 text-center text-uppercase">[devise-etablissement]</p>
                    <p class="fs-6 mb-0 text-center text-uppercase">P.B.[boite postal] Tel.[contact1]/[contact2] [ville]</p>
                </div>
                <div class="col-md-2">
                    <div class="">
                        <img class="logo-bulletin" src="{{ asset('front/images/logo-01.png') }}"/>
                    </div>
                </div>
                <div class="col-md-5 d-flex flex-column">
                    <h6 class="m-0 text-center text-uppercase">Republic of cameroon</h6>
                    <p class="fs-6 mb-0 text-center">peace-work-fatherland</p>
                    <h6 class="text-center text-uppercase">Ministry of secondary education</h6>
                    <h5 class="text-center fw-bold text-uppercase">[name-establishment]</h5>
                    <p class="fs-6 mb-0 text-center text-uppercase">[currency-establishment]</p>
                    <p class="fs-6 mb-0 text-center text-uppercase">P.B.[postal box] Tel.[contact1]/[contact2] [city]</p>
                </div>
            </div>
        </div>
        <!-- center year text -->
        <div class="container-flex mt-2 header-bulletin">
            <div class="row">
                <div class="col-md-12 col-lg-12 justify-content-center d-flex align-items-center">
                    <p class="fs-6 text-center">Année scolaire : [2024/2025]</p>
                </div>
            </div>
        </div>
        <!-- type-bulletin frame -->
        <div class="container-flex header-bulletin">
            <div class="row d-flex">
                <div class="col-md-10 col-lg-10 justify-content-center d-flex align-items-center">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-center align-items-center col-lg-12">
                            <div class="col-lg-6 ml-5">
                                <h4 class="ml-5 mr-n5 border border-3 fw-bold border-dark p-2 text-uppercase text-center">
                                    [type Bulletin]
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 d-flex justify-content-between">
                            <div class="pl-0 col-lg-4 align-items-start d-flex">
                                <p class="fs-6 text-center">Classe : [student-classe]</p>
                            </div>
                            <div class="pl-0 col-lg-4 align-items-start d-flex">
                                <p class="fs-6 text-center">Effectif : [classe-student-effectif]</p>
                            </div>
                            <div class="pl-0 col-lg-4 align-items-start d-flex">
                                <p class="fs-6 text-center">Prof. Princ : [nom-prof-princ-classe]</p>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 d-flex justify-content-between">
                            <div class="pl-0 col-lg-4 d-flex align-items-start">
                                <p class="fs-6 text-center">Matricule : [student-matricule]</p>
                            </div>
                            <div class="pl-0 col-lg-8 d-flex">
                                <h6 class="text-center fw-bold">Noms & prénoms : </h6>
                                <h6 class="ml-3 bg-light-subtle text-center border p-2 pr-4 pl-4 border-3 text-uppercase fw-bold border-dark">
                                    [Noms-et-prenoms-complets-de-l-eleve]
                                </h6>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 d-flex justify-content-between">
                            <div class="pl-0 col-lg-4 align-items-start d-flex">
                                <p class="fs-6 text-center">Née le [date-naiss] à [lieu-naiss] </p>
                            </div>
                            <div class="pl-0 col-lg-3 align-items-start d-flex">
                                <p class="fs-6 text-center">Redoublant : [statut]</p>
                            </div>
                            <div class="pl-0 col-lg-5 align-items-start d-flex">
                                <p class="fs-6 text-center">Tel. Père/Mère/Tuteur(trice) : [parent-number]</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-lg-2">
                    <div class="p-0 m-0">
                        <img class="logo-bulletin" src="{{ asset('img/default-avatar.png') }}"/>
                    </div>
                </div>
            </div>
        </div>
        <!-- bulletin content -->
        <div class="container-flex header-bulletin">
            <div class="row">
            </div>
        </div>
        <!-- bulletin footer -->
        <div class="container-flex header-bulletin">
            <div class="row">
                <table border="2">
                    <thead>
                        <tr>
                            <th class="text-capitalize">
                                Décision du conseil de classe
                            </th>
                            <th class="text-capitalize">
                                Appréciation du travail
                            </th class="text-capitalize">
                            <th class="text-capitalize">
                                Visa du chef d'établissement
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="container-flex header-bulletin mt-2 border-top border-dark border-3">
            <div class="row mt-2">
                <div class="col-md-6 col-lg-6 d-flex justify-content-start align-items-start flex-column">
                    <p class="fs-6">&copy; {{ Date('Y') }} - Byt3lab - Noumecha Spaker</p>
                </div>
                <div class="col-md-6 col-lg-6 d-flex justify-content-end align-items-end flex-column">
                    <p class="fs-6">Imprimé le : [update-date-(d/m/y)] à [update-date-(hh:mm:ss)] </p>
                </div>
            </div>
        </div>
        <!-- JS file -->
        <script src="{{ asset('bootstrap/js/bootstrap.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
