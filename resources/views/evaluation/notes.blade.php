<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center" id="success-message">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des Notes par classes</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les notes (par classe, par matière et par évaluation)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <a href="#personnelform" class="btn btn-lg btn-dark btn-primary">
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </a>
                                </div>
                            </div>
                            <form id="filterNoteForm" class="form form-inline row mt-3" action="{{ route('evaluation.notes') }}" method="get">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="searchNote" value="{{ isset($searchNote) ? $searchNote : '' }}" id="searchNote" class="form-control" placeholder="Rechercher une note (nom de l'élève)"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select name="matiereFilter" class="form-select" id="matiereFilter">
                                            <option value="">Toutes les matières</option>
                                            @foreach ($matieres as $mat)
                                                <option value="{{$mat->id}}" {{ request('matiereFilter') == $mat->id ? 'selected' : '' }}>
                                                    {{ $mat->libelleMatiere }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select name="classeFilter" class="form-select" id="classeFilter">
                                            <option value="">Toutes les classes</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{$classe->id}}" {{ request('classeFilter') == $classe->id ? 'selected' : '' }}>
                                                    {{ $classe->libClasse }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select name="remplissageFilter" class="form-select" id="remplissageFilter">
                                            <option value="">Toutes les évaluations</option>
                                            @foreach ($remplissages as $remplissage)
                                                <option value="{{$remplissage->id}}" {{ request('remplissageFilter') == $remplissage->id ? 'selected' : '' }}>
                                                    {{ $remplissage->evaluation->libelleEvaluation }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive mt-3" id="notesTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>

</x-app-layout>

