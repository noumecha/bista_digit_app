<!-- header information -->
<div
    class="container-flex header-bulletin"
>
    <div class="row">
        <div class="col-md-5 d-flex flex-column">
            <h6 class="m-0 text-center text-uppercase">Republique du cameroun</h6>
            <p class="fs-6 mb-0 text-center">paix-travail-patrie</p>
            <h6 class="text-center text-uppercase">Ministère des enseignements secondaire</h6>
            <h5 class="text-center fw-bold text-uppercase">
                {{ $bulletin->appconfiguration->school_name }}
            </h5>
            <p class="fs-6 mb-0 text-center text-uppercase">
                {{ $bulletin->appconfiguration->school_motor }}
            </p>
            <p class="fs-6 mb-0 text-center text-uppercase">
                P.B.{{ $bulletin->appconfiguration->school_postal_box }}
                Tel.{{ $bulletin->appconfiguration->contact_phone_1 }}
                @isset($bulletin->appconfiguration->contact_phone_2)
                    /{{ $bulletin->appconfiguration->contact_phone_2 }}
                @endisset
                {{ $bulletin->appconfiguration->school_town }}
            </p>
        </div>
        <div class="col-md-2">
            <div class="">
                <img class="logo-bulletin" src="{{ asset('storage/' . $bulletin->appconfiguration->school_logo) }}"/>
            </div>
        </div>
        <div class="col-md-5 d-flex flex-column">
            <h6 class="m-0 text-center text-uppercase">Republic of cameroon</h6>
            <p class="fs-6 mb-0 text-center">peace-work-fatherland</p>
            <h6 class="text-center text-uppercase">Ministry of secondary education</h6>
            <h5 class="text-center fw-bold text-uppercase">
                {{ $bulletin->appconfiguration->school_name }}
            </h5>
            <p class="fs-6 mb-0 text-center text-uppercase">
                {{ $bulletin->appconfiguration->school_motor }}
            </p>
            <p class="fs-6 mb-0 text-center text-uppercase">
                P.B.{{ $bulletin->appconfiguration->school_postal_box }}
                Tel.{{ $bulletin->appconfiguration->contact_phone_1 }}
                @isset($bulletin->appconfiguration->contact_phone_2)
                    /{{ $bulletin->appconfiguration->contact_phone_2 }}
                @endisset
                {{ $bulletin->appconfiguration->school_town }}
            </p>
        </div>
    </div>
</div>
<!-- center year text -->
<div class="container-flex mt-2 header-bulletin">
    <div class="row">
        <div class="col-md-12 col-lg-12 justify-content-center d-flex align-items-center">
            <p class="fs-6 text-center">Année scolaire : {{ $bulletin->annee_scolaire->libelleAnneeScolaire }}</p>
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
                            Bulletin {{ $bulletin->type_bulletin }}
                        </h4>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12 d-flex justify-content-between">
                    <div class="pl-0 col-lg-4 align-items-start d-flex">
                        <p class="fs-6 text-center">
                            Classe : {{ $bulletin->classe->libClasse }}
                        </p>
                    </div>
                    <div class="pl-0 col-lg-4 align-items-start d-flex">
                        <p class="fs-6 text-center">
                            Effectif : {{ $bulletin->classe->effectif->getEffectif() }}
                        </p>
                    </div>
                    <div class="pl-0 col-lg-4 align-items-start d-flex">
                        <p class="fs-6 text-center">
                            Prof. Princ : {{ getPrincipalClassTeacher($bulletin->classe->id, getCurrentYear()->id) }}
                        </p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12 d-flex justify-content-between">
                    <div class="pl-0 col-lg-4 d-flex align-items-start">
                        <p class="fs-6 text-center">
                            Matricule : {{ $bulletin->student->matricule }}
                        </p>
                    </div>
                    <div class="pl-0 col-lg-8 d-flex">
                        <h6 class="text-center fw-bold mr-2">Noms & prénoms : </h6>
                        <h6 class="m-5 mb-0 mt-0 mr-0 text-center border p-2 pr-4 pl-4 border-3 text-uppercase bg-light fw-bold border-dark">
                            {{ $bulletin->student->name }} {{ $bulletin->student->surname }}
                        </h6>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12 d-flex justify-content-between">
                    <div class="pl-0 col-lg-4 align-items-start d-flex">
                        <p class="fs-6 text-center">
                            Née le {{ formatDate($bulletin->student->dateNaiss,'d/m/y') }}
                            à {{ $bulletin->student->lieuNaiss }}
                        </p>
                    </div>
                    <div class="pl-0 col-lg-3 align-items-start d-flex">
                        <p class="fs-6 text-center">
                            Redoublant(e) :
                            @if ($bulletin->student->statutRedoublance === 1)
                                oui
                            @else
                                non
                            @endif
                        </p>
                    </div>
                    <div class="pl-0 col-lg-5 align-items-start d-flex">
                        <p class="fs-6 text-center">
                            Tel. Père/Mère/Tuteur(trice) : {{ $bulletin->student->phone }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-lg-2">
            <div class="p-0 m-0 mb-2">
                <img
                    class="logo-bulletin"
                    src="{{ asset('storage/' . $bulletin->student->profile) }}"
                />
            </div>
        </div>
    </div>
</div>