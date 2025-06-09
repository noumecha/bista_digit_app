$(function(){
    // multiselect with search bar
    $('#create-role-modal').on('shown.bs.modal', function () {
       $('#user_id').select2({
           width : '100%',
           placeholder: "Choisir les utilisateurs",
           dropdownParent: $('#create-role-modal'),
       });
    });
    // setting up text on header or button depending of action
    $(document).on('click', '[data-bs-target="#create-role-modal"]', function(e) {
        e.preventDefault();
        var action = $(this).data('action');
        var roleId = $(this).data('role-id');
        var roleIdInput = $('#roleId');
        var form = $('#createEditRoleForm');
        var button = $('#submit-role-form-button');
        var header = $('#modal-header');
        var headerText = $('#header-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-role-form-button-text').text('Enregistrer');
            headerText.text('Attribuer un rôle à un utilisateur');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-role-form-button-text').text('Mettre à jour');
            headerText.text('Changer le rôle de l\'utilisateur');
            roleIdInput.val(roleId);
            $.ajax({
                url: "roles/"+roleId+"/edit",
                type: "GET",
                success: function(res) {
                    fillInputForm(res, form);
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }
    })

    // When submitting create form for updating or creating user role
    $(document).on('click','.spinner-submit-role-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-role-form-button-text');
        var roleId = $('#roleId').val();
        var form = $(this).closest('form')[0];
        var form_datas = $(this).closest('form').serialize();
        var form_method = buttonText.text() === 'Mettre à jour' ? 'PUT' : 'POST';
        var form_action = buttonText.text() === 'Mettre à jour' ? 'role/update/' + roleId : 'role/save';
        var modal_id = $(this).closest('div.modal').prop('id');
        $.ajax({
            url: form_action,
            type: form_method,
            data: form_datas,
            success: function(response) {
                if(response.error)
                    setSuccessMessage(response.error, '#modal-form-alert-errors');
                if(response.success)
                    setSuccessMessage(response.success, '#modal-form-alert-success');
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                if(form_action === 'role/save') {
                    resetForm(form);
                }
                fetchRoles();
            },
            error: function(xhr) {
                var errors = []
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    stylingErrors(xhr.responseJSON.errors);
                    var datas = Object.entries(xhr.responseJSON.errors);
                    errors = datas.map(error => error[1][0]);
                    $('#'+modal_id).on('hidden.bs.modal', function() {
                        return false;
                    });
                } else {
                    setSuccessMessage('Erreur inconue' , '#modal-form-alert-errors');
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });

    // reseting data :
    $('#create-role-modal').on('hidden.bs.modal', function () {
        const form = $('#createEditRoleForm');
        form.trigger('reset');
        $('#modal-header').removeClass('bg-primary bg-success');
        $('#submit-role-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-role-form-button').children('span#submit-role-form-button-text').text('');
    });

    // fetching note dynamically throw filters
    $('#searchFilter, #roleFilter, #typeFilter').on('change keyup', function () {
        fetchRoles();
    });

    // default data :
    fetchRoles();

    // fetching all notes :
    function fetchRoles() {
        var formData = $('#filterRoleForm').serialize();
        try {
            $.ajax({
                url : "roles",
                type : 'GET',
                data : formData,
                success : function(data) {
                    $('#rolesTable').html(data);
                },
                error: function(xhr, status, error) {
                    var datas = Object.entries(xhr.responseJSON.errors);
                    var errors = datas.map(error => error[1][0]);
                    setSuccessMessage(errors, '#modal-form-alert-errors');
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    // handle pagination :
    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        fetchPage(page, '#rolesTable');
    });
});