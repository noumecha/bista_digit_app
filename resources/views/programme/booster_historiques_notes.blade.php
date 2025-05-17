<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center success-message">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Historique de modifications des notes du programme booster</h5>
                                    <p class="text-sm">
                                        rapport de modifications des notes du programme booster
                                    </p>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterBoosterHistoryForm">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="searchStudent" id="searchStudent"
                                            class="form-control" placeholder="Rechercher par eleve(nom,prenom)"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="classeFilter" class="form-select" id="classeFilter">
                                            <option value="">Toutes les classes</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{$classe->classe->id}}">
                                                    {{ $classe->classe->libClasse }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="evaluationFilter" id="evaluationFilter" class="form-select">
                                            <option value="">Toutes les evaluations</option>
                                            @foreach ($evaluations as $evaluation)
                                                <option value="{{ $evaluation->id }}">
                                                    {{ $evaluation->libelleEvaluation }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="matiereFilter" id="matiereFilter" class="form-select">
                                            <option value="">Toutes les matieres</option>
                                            @foreach ($matieres as $matiere)
                                                <option value="{{ $matiere->matiere->id }}">
                                                    {{ $matiere->matiere->libelleMatiere }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="boosterHistoryTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/booster-histories.js') }}"></script>
    @endsection
</x-app-layout>
