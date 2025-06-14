<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <!-- Welcome Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-3 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">
                                {{ Date('H') >= 00 && Date('H') <= 15 ? 'Bonjour' : 'Bonsoir' }},
                                {{ Auth::user()->name }}
                            </h3>
                            <p class="mb-0">Ravie de vous revoir</p>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-0">

            <!-- Quick Stats and Year Info -->
            <div class="row mt-4">
                <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                    <div class="card bg-gradient-primary border-0">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Année Scolaire</p>
                                        <h5 class="text-white font-weight-bolder mb-0">
                                            {{ $currentYear->libelleAnneeScolaire ?? 'N/A' }}
                                        </h5>
                                        <p class="text-white text-sm mb-0">
                                            <span class="badge badge-sm bg-gradient-success">Active</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-white shadow rounded-circle">
                                        <i class="ni ni-calendar-grid-58 text-primary text-lg opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                    <div class="card bg-gradient-success border-0">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Actualités</p>
                                        <h5 class="text-white font-weight-bolder mb-0">
                                            {{ $user->getActualites()->count() }}
                                        </h5>
                                        <p class="text-white text-sm mb-0">
                                            <span class="text-white font-weight-600">
                                                + {{ $user->getActualites(true)->count() }}
                                            </span> ce mois
                                        </p>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-white shadow rounded-circle">
                                        <i class="ni ni-collection text-success text-lg opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                    <div class="card bg-gradient-danger border-0">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Notifications</p>
                                        <h5 class="text-white font-weight-bolder mb-0">
                                            {{ $user->getNotifications()->count() }}
                                        </h5>
                                        <p class="text-white text-sm mb-0">
                                            <span class="text-white font-weight-600">
                                                +{{ $user->getNotifications(false, true)->count() }}
                                            </span> ce mois
                                        </p>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-white shadow rounded-circle">
                                        <i class="ni ni-notification-70 text-danger text-lg opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-gradient-info border-0">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Épreuves</p>
                                        <h5 class="text-white font-weight-bolder mb-0">
                                            {{ $user->getEpreuves()->count() }}
                                        </h5>
                                        <p class="text-white text-sm mb-0">
                                            <span class="text-white font-weight-600">+{{ $user->getEpreuves()->count() }}</span> ce mois
                                        </p>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-white shadow rounded-circle">
                                        <i class="ni ni-single-copy-04 text-info text-lg opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-lg-4 mb-lg-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0">Actions Rapides</h6>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="list-group">
                                <a href="{{ route('actualites.index') }}" class="list-group-item list-group-item-action border-0 d-flex align-items-center mb-2 border-radius-lg">
                                    <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center me-3">
                                        <i class="ni ni-collection text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">Créer une Actualité</h6>
                                        <p class="text-xs mb-0">Publier une nouvelle information</p>
                                    </div>
                                </a>
                                <a href="{{ route('notification.create') }}" class="list-group-item list-group-item-action border-0 d-flex align-items-center mb-2 border-radius-lg">
                                    <div class="icon icon-shape icon-sm bg-gradient-danger shadow text-center me-3">
                                        <i class="ni ni-notification-70 text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">Envoyer une Notification</h6>
                                        <p class="text-xs mb-0">Alerter les utilisateurs</p>
                                    </div>
                                </a>
                                <a href="{{ route('education.epreuves') }}" class="list-group-item list-group-item-action border-0 d-flex align-items-center border-radius-lg">
                                    <div class="icon icon-shape icon-sm bg-gradient-success shadow text-center me-3">
                                        <i class="ni ni-single-copy-04 text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">Uploader une Épreuve</h6>
                                        <p class="text-xs mb-0">Partager un examen</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Items Sections -->
            <div class="row mt-4">
                <!-- Recent Actualités -->
                <div class="col-md-6 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-6">
                                    <h6>Dernières Actualités</h6>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{ route('actualites.index') }}" class="btn btn-sm bg-gradient-info mb-0">+ Nouvelle</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Titre
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Catégorie
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Date
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentActualites as $actualite)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        @if($actualite->image)
                                                        <img src="{{ asset('storage/'.$actualite->image) }}" class="avatar avatar-sm me-3" alt="{{ $actualite->titre }}">
                                                        @else
                                                        <div class="avatar avatar-sm bg-gradient-info me-3">
                                                            <i class="ni ni-collection text-white"></i>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ Str::limit($actualite->titre, 20) }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $actualite->categorieActualite->libelleCategorie ?? 'N/A' }}</p>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold">{{ $actualite->created_at->format('d/m/Y') }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge badge-sm bg-gradient-success">Publié</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <p class="text-sm text-secondary mb-0">Aucune actualité récente</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Epreuves -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-6">
                                    <h6>Dernières Épreuves</h6>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{ route('education.epreuves') }}" class="btn btn-sm bg-gradient-success mb-0">
                                        + Nouvelle
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Titre
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Matière
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Classe
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentEpreuves as $epreuve)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <div class="avatar avatar-sm bg-gradient-success me-3">
                                                            <i class="ni ni-single-copy-04 text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ Str::limit($epreuve->libelleEpreuve, 20) }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $epreuve->matiere->libelleMatiere ?? 'N/A' }}</p>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold">{{ $epreuve->classe->libClasse ?? 'N/A' }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('education.epreuves') }}" class="btn btn-sm btn-outline-primary mb-0">Voir</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <p class="text-sm text-secondary mb-0">Aucune épreuve récente</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-6">
                                    <h6>Dernières Notifications</h6>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{ route('notification.create') }}" class="btn btn-sm bg-gradient-danger mb-0">+ Nouvelle</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Titre
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Type
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Destinataires
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Date
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentNotifications as $notification)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <div class="avatar avatar-sm bg-gradient-danger me-3">
                                                            <i class="ni ni-notification-70 text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ Str::limit($notification->title, 25) }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $notification->type }}</p>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold">
                                                    {{ $notification->is_mass ? 'Tous' : count($notification->receivers) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge badge-sm bg-gradient-success">Envoyé</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-sm text-secondary mb-0">Aucune notification récente</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>
</x-app-layout>