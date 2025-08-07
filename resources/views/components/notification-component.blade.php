<div class="dropdown" id="notification-bell">
    <a href="#" class="text-dark dropdown-toggle" data-bs-toggle="dropdown" id="notificationDropdown">
        <i class="fa-solid fa-bell"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="unread-count">
            0
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-end p-0" style="width: 350px;">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h6 class="mb-0">Notifications</h6>
                <small>
                    <a href="{{ route('notification.index') }}" class="text-white">Voir tout</a>
                </small>
            </div>
            <div class="card-body p-0" id="notification-list">
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center py-2">
                <small>
                    <a href="#" class="text-primary" id="mark-all-read">
                        Marquer tout comme lu
                    </a>
                </small>
            </div>
        </div>
    </div>
</div>