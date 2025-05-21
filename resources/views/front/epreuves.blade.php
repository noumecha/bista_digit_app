<x-front-layout>
    <section class="bg-02-a">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="_head_01">
                        <h2>Epreuves</h2>
                        <p>Acceuil<i class="fas fa-angle-right"></i><span>Portail de téléchargement d'épreuves</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-04">
        <div class="container">
            <div class="row">
               <div class="col-12">
                    <div class="heading">
                        <h2>Liste des épreuves disponibles</h2>
                    </div>
                </div>
            </div>
            <form class="form-inline row mt-3" id="epreuvesDataSearch" action="">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Rechercher une épreuve"/>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="input-group">
                        <select name="matiereId" class="form-select" id="matiereId">
                            <option value="">Toutes les matieres</option>
                            @foreach ($matieres as $matiere)
                                <option value="{{ $matiere->id }}">
                                    {{ $matiere->libelleMatiere }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="input-group">
                        <select name="typeId" class="form-select" id="typeId">
                            <option value="">Touts les types</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->libelleTypeEpreuve }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="input-group">
                        <select name="classeId" class="form-select" id="classeId">
                            <option value="">Toutes les classes</option>
                            @foreach ($classes as $classe)
                                <option value="{{ $classe->id }}">
                                    {{ $classe->libClasse }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-5 mt-3">
                    <div class="input-group">
                        <select name="anneeEpreuve" class="form-select" id="anneeEpreuve">
                            <option value="">Toutes les années</option>
                            @foreach ($anneeEpreuves as $anneeEpreuve)
                                <option value="{{ $anneeEpreuve->anneeEpreuve }}">
                                    {{ $anneeEpreuve->anneeEpreuve }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1 mt-3">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                    </div>
                    <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                    </div>
                </div>
            </div>
            <div class="row d-none" id="epreuve-loader" style="text-align: center;">
                <div class="col-12 mt-5">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="epreuveDatas">
            </div>
        </div>
    </section>
</x-front-layout>
