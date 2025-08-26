<x-guest-layout>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-md-6 d-flex pt-20 pb-20 flex-column mx-auto">
                            <div class="col-auto d-flex align-items-center justify-content-center">
                                <div
                                    class="overflow-hidden avatar avatar-2xl bg-white 
                                    position-relative">
                                    <!-- to add if necessary : border border-2 border-dark rounded-circle -->
                                    <img
                                        @if (appConfiguration() !== null && isset(appConfiguration()->school_logo))
                                            src="{{ asset('storage/'.appConfiguration()->school_logo) }}"
                                        @else
                                            src="{{ asset('logo/logo-bista.png') }}"
                                        @endif
                                        alt="school_logo" class="w-100"
                                    />
                                </div>
                            </div>
                            <div class="card card-plain mt-4">
                                <div class="card-header pb-0 bg-transparent text-center">
                                    <h4 class="font-weight-black text-dark display-8">
                                        Portail de connexion
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <form role="form" class="text-start" method="POST" action="sign-in">
                                        @csrf
                                        <label>Adresse email ou Matricule</label>
                                        <div class="mb-3">
                                            <input type="text" id="login" name="login" class="form-control"
                                                placeholder="Entrez votre email ou votre matricule"
                                                value="{{ old('login') ? old('login') : 'bista@admin.com' }}"
                                                aria-label="login" aria-describedby="login-addon">
                                        </div>
                                        <label>Mot de passe</label>
                                        <div class="mb-3" id="eye-password-container">
                                            <input type="password" id="password-con" name="password"
                                                value="{{ old('password') ? old('password') : '@dmin123' }}"
                                                class="form-control" autocomplete="current-password">
                                                <span toggle="#password" id="icon-con" class="fa-solid fa-eye field-icon toggle-eye"></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-check-info text-left mb-0">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="flexCheckDefault">
                                                <label class="font-weight-normal text-dark mb-0" for="flexCheckDefault">
                                                    Souvenez-vous de moi
                                                </label>
                                            </div>
                                            <a href="{{ route('password.request') }}"
                                                class="text-xs font-weight-bold ms-auto"
                                            >
                                                mot de passe oublié ?
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Connexion</button>
                                        </div>
                                        <div>
                                            <a href="{{ route("home.index") }}"
                                                class="text-sm font-weight-bold ms-auto"
                                            >
                                                Retour sur le site <i class="fa-solid fa-arrow-right fa-sm"></i>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                                <div class="text-center">
                                    @if (session('status'))
                                        <div class="mb-4 font-medium text-sm text-green-600">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @error('message')
                                        <div class="alert alert-danger text-sm" role="alert">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-100 top-0 text-center mt-6 end-0 p-4 h-100 d-md-block d-none">
                                <h6 class="text-dark text-sm">Copyright © {{ Date('Y') }} - POWEREDUCATION </h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 end-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute fixed-top ms-auto h-100 z-index-0 bg-cover ms-n8"
                                    style="background-image: url('{{
                                        isset($appconfiguration) && isset($appconfiguration->school_image) ?
                                        asset('storage/' . $appconfiguration->school_image) :
                                        asset('img/image-sign-in.jpg')
                                    }}');">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</x-guest-layout>
