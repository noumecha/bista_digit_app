$(function() {
    // Load initial notifications
    fetchNotifications();

    // Setup click handler for mark all as read
    $(document).on('click', '#mark-all-read', function(e) {
        e.preventDefault();
        markAllAsRead();
        fetchNotifications();
    });

    $(document).on('click', '#notification-show-btn', function(e) {
        e.preventDefault();
        notifId = $(this).data('notification-id');
        markAsRead(notifId);
    })

    // fetching datas when closing show modal
    $(document).on('click', '#close-show-btn', function(e) {
        e.preventDefault();
        console.log("Works !");
        fetchNotifications();
    })

    // fetching actualites dynamically with filters
    $('#searchNotification, #statutFilter').on('change keyup', function () {
        fetchNotifications();
    });

    // Function to fetch notifications
    function fetchNotifications() {
        var formData = $('#filterUserNotifsForm').serialize();
        $.ajax({
            url : "/notifications/show",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#userNotificationsTable').html(data);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }

    // Function to mark notification as read
    // single notification mark
    function markAsRead(id) {
        $.ajax({
            url : "/api/notifications/mark-as-read/" + id,
            type : "GET",
            success : function(data) {
                setSuccessMessage(data.success, '#modal-form-alert-success');
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }
    // for all notification
    function markAllAsRead() {
        $.ajax({
            url : "/api/notifications/mark-all-as-read",
            type : "GET",
            success : function(data) {
                setSuccessMessage(data.success, '#modal-form-alert-success');
                fetchNotifications();
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }
});