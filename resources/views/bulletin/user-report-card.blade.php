<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
            <div class="pt-7 pb-6 bg-cover"
                style="background-image: url('{{ asset('img/image-sign-in.jpg') }}'); background-position: bottom;">
            </div>
            <div class="container">
                <div class="card card-body py-2 bg-transparent shadow-none">
                    <div class="row">
                        <div class="col-auto">
                            <div
                                class="overflow-hidden avatar avatar-2xl bg-white rounded-circle position-relative mt-n7 border border-2 border-dark">
                                <img
                                    src="{{ asset('storage/' . $bulletin->student->profile) }}"
                                    alt="school_logo" class="w-100"
                                />
                            </div>
                        </div>
                        <div class="col-auto my-auto">
                            <div class="h-100">
                                <h3 class="mb-0 font-weight-bold">
                                    {{ $bulletin->student->name }}
                                </h3>
                                <p class="mb-0">
                                    classe : {{ $bulletin->classe->libClasse }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- bulletin tempate goes here -->
            <div class="container my-3 py-3">
                <div class="row">
                    <div class="col-12 col-xl-12 p-0">
                        <div class="card border shadow-xs h-100">
                            <div class="card-header text-white bg-dark p-3">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10">
                                        Bulletin {{ $bulletin->type_bulletin }} de : {{ $bulletin->student->name }}
                                    </div>
                                    <!--div class="col-lg-1 col-md-1">
                                        <a
                                            type="button"
                                            title="télécharger"
                                            id="download-report"
                                            class="btn btn-dark mb-0 p-0 text-white"
                                        >
                                            <i class="fas fa-print me-2"></i>
                                        </a>
                                    </div-->
                                    <div class="col-lg-1 col-md-1">
                                        <a
                                            type="button"
                                            title="imprimer"
                                            id="printReport"
                                            class="btn btn-dark mb-0 p-0 text-white"
                                        >
                                            <i class="fas fa-download me-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border border-3 row">
                    <div id="report-card" class="bg-white">
                        @if ($bulletin->type_bulletin === "sequenciel")
                            @include(
                                'bulletin.evaluation',
                                [
                                    'bulletin' => $bulletin,
                                    'studentNotesFirstGroup' => $studentNotesFirstGroup,
                                    'studentNotesSndGroup' => $studentNotesSndGroup,
                                    'studentNotesThirdGroup' => $studentNotesThirdGroup,
                                    'disciplines' => $disciplines,
                                    'conseils' => $conseils,
                                    'principal' => $principal,
                                    'effectif' => $effectif
                                ]
                            )
                        @endif
                        @if ($bulletin->type_bulletin === "trimestre")
                            @include(
                                'bulletin.trimestrielle',
                                [
                                    'bulletin' => $bulletin,
                                    'bulletinsAvgs' => $bulletinsAvgs,
                                    'studentNotesFirstGroup' => $studentNotesFirstGroup,
                                    'studentNotesSndGroup' => $studentNotesSndGroup,
                                    'studentNotesThirdGroup' => $studentNotesThirdGroup,
                                    'disciplines' => $disciplines,
                                    'conseils' => $conseils,
                                    'principal' => $principal,
                                    'effectif' => $effectif
                                ]
                            )
                        @endif
                        @if ($bulletin->type_bulletin === "annuel")
                            @include(
                                'bulletin.annual',
                                [
                                    'bulletin' => $bulletin,
                                    'bulletinsAvgs' => $bulletinsAvgs,
                                    'studentNotesFirstGroup' => $studentNotesFirstGroup,
                                    'studentNotesSndGroup' => $studentNotesSndGroup,
                                    'studentNotesThirdGroup' => $studentNotesThirdGroup,
                                    'disciplines' => $disciplines,
                                    'conseils' => $conseils,
                                    'principal' => $principal,
                                    'effectif' => $effectif
                                ]
                            )
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <!-- adding html2canvas & jspdf -->
        <script src="{{ asset('js/functions/modules/jspdf.js') }}"></script>
        <script src="{{ asset('js/functions/modules/html2canvas.js') }}"></script>
        <script src="{{ asset('js/functions/bulletins.js') }}"></script>
    @endsection
</x-app-layout>
