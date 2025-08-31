<!-- Header -->
<!-- first row -->
<div class="header-bulletin clearfix">
    <div class="col-half">
        <p class="text-center text-uppercase m-0">Republique du cameroun</p>
        <p class="text-center m-0">paix-travail-patrie</p>
        <p class="text-center m-0 text-uppercase">Ministère des enseignements secondaire</p>
        <h4 class="text-center text-uppercase m-0 fw-bold">{{ $bulletin->appconfiguration->school_name }}</h4>
        <p class="text-center text-uppercase m-0">{{ $bulletin->appconfiguration->school_motor }}</p>
        <p class="text-center text-uppercase m-0">
            P.B.{{ $bulletin->appconfiguration->school_postal_box }}
            Tel.{{ $bulletin->appconfiguration->contact_phone_1 }}
            @isset($bulletin->appconfiguration->contact_phone_2)
                /{{ $bulletin->appconfiguration->contact_phone_2 }}
            @endisset
            {{ $bulletin->appconfiguration->school_town }}
        </p>
    </div>
    <div class="col-middle">
        <img class="logo-bulletin" src="{{ public_path('storage/' . $bulletin->appconfiguration->school_logo) }}"/>
    </div>
    <div class="col-half">
        <p class="text-center text-uppercase m-0">Republic of cameroon</p>
        <p class="text-center m-0">peace-work-fatherland</p>
        <p class="text-center m-0 text-uppercase">Ministry of secondary education</p>
        <h4 class="text-center m-0 text-uppercase fw-bold">{{ $bulletin->appconfiguration->school_name }}</h4>
        <p class="text-center text-uppercase m-0">{{ $bulletin->appconfiguration->school_motor }}</p>
        <p class="text-center text-uppercase m-0">
            P.B.{{ $bulletin->appconfiguration->school_postal_box }}
            Tel.{{ $bulletin->appconfiguration->contact_phone_1 }}
            @isset($bulletin->appconfiguration->contact_phone_2)
                /{{ $bulletin->appconfiguration->contact_phone_2 }}
            @endisset
            {{ $bulletin->appconfiguration->school_town }}
        </p>
    </div>
</div>

<!-- second-row : School Year -->
<div class="header-bulletin text-center m-0 p-0">
    <p class="m-0 p-0">Année scolaire : {{ $bulletin->annee_scolaire->libelleAnneeScolaire }}</p>
</div>

<!-- third row -->
<!-- Bulletin Title and Info -->
<div class="clearfix header-bulletin p-rlt">
    <!-- bulletin type -->
    <p class="info-box text-uppercase text-center">
        Bulletin 
        @if ($bulletin->type_bulletin === "trimestre")
            {{ $bulletin->trimestre->libelleTrimestre }}
        @elseif ($bulletin->type_bulletin === "sequenciel")
            {{ $bulletin->evaluation->libelleEvaluation }}
        @else
            {{ $bulletin->type_bulletin }}
        @endif
    </p>
    <!-- classe information -->
    <div class="classe-block clearfix">
        <p class="m-0 p-0 col-20">Classe : {{ $bulletin->classe->libClasse }}</p>
        <p class="m-0 p-0 col-20">Effectif : {{ $effectif }}</p>
        <p class="m-0 p-0 col-60">Prof. Princ : {{ $principal }}</p>
    </div>
    <!-- student information -->
    <div class="classe-block clearfix">
        <p class="m-0 p-0 col-20">Matricule : {{ $bulletin->student->matricule }}</p>
        <h3 class="m-0 p-0 col-20"><b>Noms & prénoms : </b></h3>
        <p class="m-0 name-box text-uppercase">
            {{ $bulletin->student->name }} {{ $bulletin->student->surname }}
        </p>
    </div>
    <!-- birth information -->
    <div class="classe-block clearfix">
        <p class="col-30 m-0 p-0">
            Né(e) le : {{ formatDate($bulletin->student->dateNaiss,'d/m/Y') }}
            à : <span class="text-uppercase">{{ $bulletin->student->lieuNaiss }}</span>
        </p>
        <p class="col-70 m-0 p-0">Redoublant :
            @if ($bulletin->student->statutRedoublance === 1)
                oui
            @else
                non
            @endif
            &nbsp; &nbsp; &nbsp; Tel. Père/Mère/Tuteur : {{ $bulletin->student->phone }}
        </p>
    </div>
    <!-- student image -->
    <div class="p-abs d-block user-image-container">
        <img class="user-image" src="{{ public_path('storage/' . $bulletin->student->profile) }}" />
    </div>
</div>