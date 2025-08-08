<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between">
                            <h6>Mes Notifications</h6>
                            <button id="mark-all-read" class="btn btn-sm btn-primary">
                                Marquer tout comme lu
                            </button>
                        </div>
                        <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                        </div>
                        <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Notification</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Expéditeur</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($notifications as $notification)
                                            <tr id="{{ $notification->id }}">
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-0 text-sm">
                                                                <a href="{{ isset($notification->id) ?? route('notifications.show', $notification->id) }}">
                                                                    {{ isset($notification->title) ? $notification->title : "-" }}
                                                                </a>
                                                            </h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ isset($notification->message) ?
                                                                Str::limit($notification->message, 50)
                                                                : "-" }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ isset($notification->sender) ? $notification->sender->name : "-" }}
                                                    </p>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                        {{ isset($notification->created_at) ? $notification->created_at->format('d/m/Y H:i') : "-" }}
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if(isset($notification->read_at))
                                                        <span class="badge badge-sm bg-gradient-success">Lu</span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-danger">Non lu</span>
                                                    @endif
                                                </td>
                                                <td class="text-center d-flex justify-content-center
                                                    align-middle bg-transparent border-bottom"
                                                    style="gap:10px;">
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger ml-2 mt-3 p-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#show-{{ $notification->id }}">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                    <!-- modal for showing notification -->
                                                    <div class="modal fade" id="show-{{ $notification->id }}" tabindex="-1" aria-labelledby="exampleModalLabel">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger">
                                                                    <h5 class="modal-title text-white" id="exampleModalLabel">Confirmation de suppression</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-wrap text-justify">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <p class="text-sm text-secondary mb-0">Aucune notification trouvée</p>
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
        </div>
        <x-app.footer />
    </main>
</x-app-layout>