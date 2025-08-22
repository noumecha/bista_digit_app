<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0 text-white">Bulletins de : {{ $user->name }} {{ $user->surname }}</h4>
                                <span class="badge bg-white text-primary">
                                    Classe : {{ $user->getCurrentYearClasseName(getCurrentYear()->id)->libClasse }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
</x-app-layout>