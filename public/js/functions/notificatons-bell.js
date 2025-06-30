$(function() {
    // Load initial notifications
    fetchNotifications();

    // Setup click handler for mark all as read
    $(document).on('click', '#mark-all-read', function(e) {
        e.preventDefault();
        markAllAsRead();
    });

    // Function to fetch notifications
    function fetchNotifications() {
        $.get('/api/notifications/latest', function(response) {
            updateNotificationUI(response.notifications, response.unreadCount);
        });
    }

    // Function to update UI with notifications
    function updateNotificationUI(notifications, unreadCount) {
        $('#unread-count').text(unreadCount);

        if (notifications.length > 0) {
            let html = '<div class="list-group list-group-flush">';

            notifications.forEach(function(notification) {
                const readClass = notification.read_at ? 'bg-gray-100' : '';
                const timeAgo = moment(notification.created_at).fromNow();

                html += `
                <a href="/notifications/${notification.id}"
                   class="list-group-item list-group-item-action border-0 ${readClass}">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape icon-sm bg-gradient-info text-white rounded-circle me-3">
                            <i class="ni ni-notification-70"></i>
                        </div>
                        <div>
                            <h6 class="text-sm mb-0">${notification.title}</h6>
                            <p class="text-xs text-secondary mb-0">
                                ${notification.message.substring(0, 50)}...
                            </p>
                            <small class="text-xs text-muted">${timeAgo}</small>
                        </div>
                    </div>
                </a>`;
            });

            html += '</div>';
            $('#notification-list').html(html);
        } else {
            $('#notification-list').html(`
                <div class="text-center p-4">
                    <p class="text-sm text-muted mb-0">Aucune nouvelle notification</p>
                </div>
            `);
        }
    }

    // Function to mark all as read
    function markAllAsRead() {
        $.post('/api/notifications/mark-as-read', function() {
            fetchNotifications();
        });
    }

    // Real-time updates with Echo/Pusher
    if (typeof Echo !== 'undefined') {
        Echo.private(`user.${window.Laravel.user.id}`)
            .listen('.notification.sent', function(data) {
                fetchNotifications(); // Refresh notifications
            });
    }
});