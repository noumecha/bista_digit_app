<x-guest-layout>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-md-6 d-flex pt-20 pb-20 flex-column mx-auto">
                            <div class="logo d-flex justify-content-center align-items-center rounded-full w-100 h-100 overflow-hidden">
                                <img src="../assets/logo/logo-bista.png" alt="logo" class=""/>
                            </div>
                            <div class="card card-plain mt-6">
                                <div class="card-header pb-0 bg-transparent text-center">
                                    <h3 class="font-weight-black text-dark display-6">Portail Administratif</h3>
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
                                <div class="card-body">
                                    <form role="form" class="text-start" method="POST" action="sign-in">
                                        @csrf
                                        <label>Adresse email</label>
                                        <div class="mb-3">
                                            <input type="email" id="email" name="email" class="form-control"
                                                placeholder="Entrez votre adresse email"
                                                value="{{ old('email') ? old('email') : 'admin@corporateui.com' }}"
                                                aria-label="Email" aria-describedby="email-addon">
                                        </div>
                                        <label>Mot de passe</label>
                                        <div class="mb-3">
                                            <input type="password" id="password" name="password"
                                                value="{{ old('password') ? old('password') : 'secret' }}"
                                                class="form-control" placeholder="Entrez votre mot de passe" aria-label="Password"
                                                aria-describedby="password-addon">
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
                                            <button type="button" class="btn btn-white btn-icon w-100 mb-3">
                                                <span class="btn-inner--icon me-1">
                                                    <img class="w-5" src="../assets/img/logos/google-logo.svg"
                                                        alt="google-logo" />
                                                </span>
                                                <span class="btn-inner--text">Se connecter avec Google</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-xs mx-auto">
                                        Pas encore de compte ?
                                        <!-- {#{ route('sign-up') }} -->
                                        <a href="{{ route('sign-up') }}" class="text-dark font-weight-bold">
                                            Créer en un
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="w-100 top-0 text-center mt-6 end-0 p-4 h-100 d-md-block d-none">
                                <h6 class="text-dark text-sm">Copyright © 2024 Byt3lab</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 end-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute fixed-top ms-auto h-100 z-index-0 bg-cover ms-n8"
                                    style="background-image:url('../assets/img/image-sign-in.jpg')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</x-guest-layout>
