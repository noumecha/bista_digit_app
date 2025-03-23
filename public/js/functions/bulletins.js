$(function(){
    // when printing the report card
    $(document).on('click','#printReport',function() {
        let reportCard = document.getElementById("report-card");
        // html2canvas + jspdf
        html2canvas(reportCard, {
            scale: 2,
            useCORS: true,
        }).then((canvas) => {
            let imgData = canvas.toDataURL("image/png");
            let pdf = new jspdf.jsPDF("p", "mm", "a4", true);

            let imgWidth = 210;
            let imgHeight = 297;
            //let imgHeight = (canvas.height * imgWidth) / canvas.width;

            pdf.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);
            pdf.save("bulletin.pdf");
        });
    });

    // filtering student base on the classe id
    $('#classe_id').on('change', function() {
        let classeId = $(this).val();
        $('#user_id').html('<option value="">Tout les élèves</option>');
        if (classeId) {
            $.get('/bulletins/students/' + classeId, function(data) {
                data.forEach(student => {
                    $('#user_id').append(`<option value="${student.id}">${student.name}</option>`);
                });
            });
        }
    });
    // disabled the user_id select input base on a selected option
    $('#option_type').on('change', function() {
        let option = $(this).val();
        if(option === "all") {
            $('#user_id').attr('disabled', true);
        } else {
            $('#user_id').attr('disabled', false);
        }
    })
    // disabled the evaluation input base on a bulletin type
    $('#type_bulletin').on('change', function() {
        let option = $(this).val();
        if(option === "trimestre" || option === "annuel") {
            $('#evaluation_id').attr('disabled', true);
        } else {
            $('#evaluation_id').attr('disabled', false);
        }
    })
    // filtering evaluation base on the trimestre id
    $('#trimestre_id').on('change', function() {
        let triemstreId = $(this).val();
        $('#evaluation_id').html('<option value="">Toutes les évaluations</option>');
        if (triemstreId) {
            $.get('/bulletins/evaluations/' + triemstreId, function(data) {
                data.forEach(evaluation => {
                    $('#evaluation_id').append(`<option value="${evaluation.id}">${evaluation.libelleEvaluation}</option>`);
                });
            });
        }
    });
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-bulletin-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var bulletinId = $(this).data('bulletin-id');
        var bulletinIdInput = $('#bulletinId');
        var form = $('#bulletinForm');
        var button = $('#submit-bulletin-form-button');
        var header = $('#modal-bulletin-header');
        var headerText = $('#header-bulletin-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-bulletin-form-button-text').text('Générer le(s) bulletin(s)');
            headerText.text('Générer de nouveaux bulletins pour toute une classe ou pour un élève en particulier');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-bulletin-form-button-text').text('Regénérer');
            headerText.text('Mettre à jour le bulletin en le regénérant !');
            bulletinIdInput.val(bulletinId);
            $('#user_id').prop("disabled", true);
            $('#evaluation_id').prop("disabled", true);
            $.ajax({
                url: "bulletins/"+bulletinId+"/edit",
                type: "GET",
                success: function(res) {
                    // filling form base on the data res
                    fillInputForm(res, form);
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }
    })

    // When submiting form for updating or creating new bulletin
    $(document).on('click','.spinner-submit-bulletin-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-bulletin-form-button-text');
        var bulletinId = $('#bulletinId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);

        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? '/bulletins/update/' + bulletinId : '/bulletins/save';
        var modalId = $(this).closest('div.modal').prop('id');
        if (buttonText.text() === 'Mettre à jour') {
            formData.append('_method', 'PUT');
        }
        $.ajax({
            url: formAction,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.error)
                    setSuccessMessage(response.error, '#modal-form-alert-errors');
                if(response.success)
                    setSuccessMessage(response.success, '#modal-form-alert-success');
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                // reset form after creation
                if(formAction === '/bulletins/save') {
                    resetForm(form);
                }
                $('#user_id').html('<option value="">Tout les élèves</option>');
                $('#evaluation_id').html('<option value="">Toutes les évaluations</option>');
                fetchBulletins();
            },
            error: function(xhr) {
                var errors = []
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    stylingErrors(xhr.responseJSON.errors);
                    var datas = Object.entries(xhr.responseJSON.errors);
                    errors = datas.map(error => error[1][0]);
                    $('#'+modalId).on('hidden.bs.modal', function() {
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

    // reseting form title and color when closing modal :
    $('#create-bulletin-modal').on('hidden.bs.modal', function () {
        const form = $('#bulletinForm');
        form.trigger('reset');
        $('#modal-bulletin-header').removeClass('bg-primary bg-success');
        $('#submit-bulletin-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-bulletin-form-button').children('span#submit-bulletin-form-button-text').text('');
        $('#user_id').html('<option value="">Tout les élèves</option>');
        $('#evaluation_id').html('<option value="">Toutes les évaluations</option>');
    });

    // fetching bulletins dynamically with filters
    $('#searchStudent,#trimestreFilter,#evaluationFilter,#classFilter').on('change keyup', function () {
        fetchBulletins();
    });

    // default data :
    fetchBulletins();

    // fetching all bulletins :
    function fetchBulletins() {
        var formData = $('#filterBulletinForm').serialize();
        $.ajax({
            url : "/bulletins/list",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#bulletinsTable').html(data);
                initializeCountdowns();
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }

    // handle pagination :
    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        fetchPage(page, '#bulletinsTable');
    });
});