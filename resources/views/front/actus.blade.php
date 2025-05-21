<x-front-layout>
    <!-- ==== slider === -->
    <div id="carouselExampleIndicators" class="carousel slide slider" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach ($actualitesSliders as $actualitesSlider)
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $loop->index }}"
                    class="{{ $loop->first ? 'active' : "" }}" aria-current="true" aria-label="Slide {{ $loop->index + 1 }}">
                </button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @if (empty($actualitesSliders->items()))
              <div class="carousel-item active">
                <img src="{{ asset('storage/') }}" class="d-block" alt="...">
                <div class="carousel-caption">
                  <h2>
                    Dernières actualités
                  </h2>
                  <p>
                    Aucune actualité pour le moment
                  </p>
                </div>
              </div>
            @else
                @foreach ($actualitesSliders as $actualitesSlider)
                    <div class="carousel-item {{ $loop->first ? 'active' : "" }}" data-bs-interval="5000">
                        <img src="{{ asset('storage/' . $actualitesSlider->image) }}" class="d-block" alt="...">
                        <div class="carousel-caption">
                          <h1>
                            <a class="text-white" href="{{ route('actualites.show', $actualitesSlider->id) }}">
                              {{ $actualitesSlider->titre }}
                            </a>
                          </h1>
                          <p class="">
                            {!! Str::limit(strip_tags($actualitesSlider->contenu), $limit=50, $end="...") !!}
                            <a class="text-white fw-bold" href="{{ route('actualites.show', $actualitesSlider->id) }}">
                              Lire la suite
                            </a>
                          </p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <section class="bg-04">
        <div class="container">
            <div class="row">
               <div class="col-12">
                    <div class="heading">
                        <h2>Les dernières actualités</h2>
                    </div>
                </div>
            </div>
            <form class="form-inline row mt-3" id="actusDataSearch"
                action="">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" name="search"
                            value="{{ isset($search) ? $search : '' }}" id="search" class="form-control"
                            placeholder="Rechercher une actulaité (titre ou contenu)"/>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                        <select name="category" class="form-select" id="category">
                            <option value="">Toutes les catégorie</option>
                            @foreach ($categories as $cat)
                                <option value="{{$cat->id}}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->libelleCategorie }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
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
            <div class="row d-none" id="loader" style="text-align: center;">
                <div class="col-12 mt-5">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="actusDatas">
            </div>
        </div>
    </section>
</x-front-layout>
