<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center success-message">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des Bulletins de : {{ $user->name }} {{ $user->surname }}</h5>
                                    <p class="text-sm">
                                        D'ici tu peux consulter tes bulletins
                                    </p>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterBulletinForm">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="typeFilter" id="typeFilter" class="form-select">
                                            <option value="">Tout les types</option>
                                            <option value="sequenciel">Séquenciel</option>
                                            <option value="trimestre">Trimestriel</option>
                                            <option value="annuel">Annuel</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="trimestreFilter" id="trimestreFilter" class="form-select">
                                            <option value="">Tous les trimestres</option>
                                            @foreach ($trimestres as $trimestre)
                                                <option value="{{ $trimestre->id }}">
                                                    {{ $trimestre->libelleTrimestre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="evaluationFilter" id="evaluationFilter" class="form-select">
                                            <option value="">Toutes les évaluations</option>
                                            @foreach ($evaluations as $evaluation)
                                                <option value="{{ $evaluation->id }}">
                                                    {{ $evaluation->libelleEvaluation }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="userBulletinsTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/user-bulletins.js') }}"></script>
    @endsection
</x-app-layout>
