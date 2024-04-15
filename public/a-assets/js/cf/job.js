function Job() {

    "use strict";

    var self = this;
    var job_filters = {};

    this.initFilters = function () {
        $("#status, #department, #employer_id").off();
        $("#status, #department, #employer_id").on('change', function () {
            self.initJobsDatatable();
        });
        $('.select2').select2();
    };

    this.initJobsDatatable = function () {
        $('#jobs_datatable').DataTable({
            "aaSorting": [[ 9, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,4,11]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/jobs/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d.department = $('#department').val();
                    d.employer_id = $('#employer_id').val();
                    d.job_filters = job_filters;
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initJobCreateOrEditForm();
                    self.initJobChangeStatus();
                    self.initJobDelete();
                    $('.table-bordered').parent().attr('style', 'overflow:auto'); //For responsive
                },
            },
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'info': true,
            'autoWidth': true,
            'destroy':true,
            'stateSave': true
        });
    };

    this.initJobSave = function () {
        application.onSubmit('#admin_job_create_update_form', function (result) {
            application.showLoader('admin_job_create_update_form_button');
            application.post('/admin/jobs/save', '#admin_job_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_job_create_update_form_button');
                application.showMessages(result.messages, 'admin_job_create_update_form');
                if (result.data) {
                    window.location = application.url+'/admin/jobs/create-or-edit/'+result.data;
                }                
            });
        });
    };

    this.initJobCreateOrEditForm = function () {
        $('.create-or-edit-job').off();
        $('.create-or-edit-job').on('click', function () {
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            window.location = application.url+'/admin/jobs/create-or-edit'+id;            
        });
    };

    this.initJobChangeStatus = function () {
        $('.change-job-status').off();
        $('.change-job-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/jobs/status/'+id+'/'+status, '', function (result) {
                if (application.response != '') {
                    var result = JSON.parse(application.response);
                    if (result.success == 'false') {
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                        $('.messages-container').html(result.messages);
                        button.html(lang['inactive']);
                        button.attr("disabled", false);
                        return false;
                    }
                }
                button.removeClass('btn-success');
                button.removeClass('btn-danger');
                button.addClass(status === 1 ? 'btn-danger' : 'btn-success');
                button.html(status === 1 ? lang['inactive'] : lang['active']);
                button.data('status', status === 1 ? 0 : 1);
                button.attr("disabled", false);
                button.attr("title", status === 1 ? lang['click_to_activate'] : lang['click_to_deactivate']);
            });
        });
    };

    this.initAllCheck = function () {
        $('input.all-check').on('ifChecked', function(event){
            $('input.single-check').iCheck('check');
        });
        $('input.all-check').on('ifUnchecked', function(event){
            $('input.single-check').iCheck('uncheck');
        });
    };

    this.initJobDelete = function () {
        $('.delete-job').off();
        $('.delete-job').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/jobs/delete/'+id, '', function (result) {
                    self.initJobsDatatable();
                });
            }
        });
    };

    this.initJobsListBulkActions = function () {
        $('.job-bulk-action').off();
        $('.job-bulk-action').on('click', function (e) {
            e.preventDefault();
            var ids = [];
            var action = $(this).data('action');
            $('.single-check').each(function (i, v) {
                if ($(this).is(':checked')) {
                    ids.push($(this).data('id'))
                }
            });
            if (ids.length === 0) {
                alert(lang['please_select_some_records_first']);
                $('.job-bulk-action').val('');
                return false;
            } else if (action == 'download-excel') {
                var form = "#jobs-form";
                $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo(form);
                $("<input />").attr("type", "hidden").attr("name", "_token").attr("value", application._token).appendTo(form);
                $(form).submit();
            } else {
                application.post('/admin/jobs/bulk-action', {ids:ids, action: $(this).data('action')}, function (result) {
                    $('.bulk-action').val('');
                    $('.all-check').prop('checked', false);
                    self.initJobsDatatable();
                });
            }
        });
    };

    this.initiCheck = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
    };

    this.initCKEditorFiveClassic = function (element_id) {
        var elementExists = document.getElementById(element_id);
        if (elementExists) {
            ClassicEditor.create(document.querySelector('#'+element_id), {
                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                htmlSupport: {
                    allow: [{name: /.*/, attributes: true, classes: true, styles: true}],
                },                
            }).then( editor => {
            }).catch( err => {
                console.error( err.stack );
            });
        }
    };
}

$(document).ready(function() {
    var job = new Job();

    //For both screens
    job.initiCheck();

    //For listing screen
    job.initFilters();
    job.initJobsDatatable();
    job.initJobsListBulkActions();
    
    //For create edit screen
    job.initCKEditorFiveClassic('job-description');
    job.initJobSave();
});
