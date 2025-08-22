<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="py-4 container-fluid">
            <div class="row">
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
                                    <h5 class="">Etat de remplissages des notes</h5>
                                    <p class="text-sm">
                                        controle du remplissage des notes
                                    </p>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterControlForm">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="searchTeacher" id="searchTeacher"
                                            class="form-control" placeholder="Rechercher par enseignant(nom)"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="classeFilter" class="form-select" id="classeFilter">
                                            <option value="">Toutes les classes</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{$classe->id}}">
                                                    {{ $classe->libClasse }}
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
                                                <option value="{{ $matiere->id }}">
                                                    {{ $matiere->libelleMatiere }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="controleRemplissagesTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/controle-remplissage.js') }}"></script>
    @endsection
</x-app-layout>
