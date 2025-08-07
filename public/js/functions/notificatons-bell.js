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
            console.log(response)
        });
    }

    // Function to mark all as read
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