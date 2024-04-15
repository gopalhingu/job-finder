function JobFilter() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status, #employer_id").off();
        $("#status, #employer_id").change(function () {
            self.initJobFiltersDatatable();
        });
        $('.select2').select2();
    };

    this.initJobFiltersDatatable = function () {
        $('#admin_job_filters_datatable').DataTable({
            "aaSorting": [[ 7, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,2,9]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/job-filters/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d.employer_id = $('#employer_id').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initJobFilterCreateOrEditForm();
                    self.initJobFilterUpdateValuesForm();
                    self.initJobFilterChangeStatus();
                    self.initJobFilterDelete();
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

    this.initJobFilterUpdateValuesForm = function () {
        $('.add-job-filter-values').off();
        $('.add-job-filter-values').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            var title = $(this).data('title');
            $(modal).modal('show');
            $(modal+' .modal-title').html(lang['update'] + " : " + title);
            application.load('/admin/job-filters/update-values/'+id, modal+' .modal-body-container', function (result) {
                self.initJobFilterValuesSave();
                self.initJobFilterAddValue();
                self.initJobFilterRemoveValue();
            });
        });
    };

    this.initJobFilterAddValue = function () {
        $('.add-value').off();
        $('.add-value').on('click', function () {
            application.load('/admin/job-filters/new-value', '', function (result) {
                $('.values-container').append(result);
                self.initJobFilterRemoveValue();
            });
        });
    };

    this.initJobFilterRemoveValue = function () {
        $('.remove-value').off();
        $('.remove-value').on('click', function () {
            $(this).parent().parent().parent().remove();
        });
    };

    this.initJobFilterValuesSave = function () {
        application.onSubmit('#employer_job_filter_values_update_form', function (result) {
            application.showLoader('employer_job_filter_values_update_form_button');
            application.post('/admin/job-filters/update-values', '#employer_job_filter_values_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initJobFiltersDatatable();
                } else {
                    application.hideLoader('employer_job_filter_values_update_form_button');
                    application.showMessages(result.messages, 'employer_job_filter_values_update_form .modal-body');
                }
            });
        });
    };

    this.initJobFilterCreateOrEditForm = function () {
        $('.create-or-edit-job-filter').off();
        $('.create-or-edit-job-filter').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit'] : lang['create'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/admin/job-filters/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initJobFilterSave();
                $('.dropify').dropify();
            });
        });
    };

    this.initJobFilterSave = function () {
        application.onSubmit('#employer_job_filter_create_update_form', function (result) {
            application.showLoader('employer_job_filter_create_update_form_button');
            application.post('/admin/job-filters/save', '#employer_job_filter_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initJobFiltersDatatable();
                } else {
                    application.hideLoader('employer_job_filter_create_update_form_button');
                    application.showMessages(result.messages, 'employer_job_filter_create_update_form .modal-body');
                }
            });
        });
    };

    this.initJobFilterChangeStatus = function () {
        $('.change-job-filter-status').off();
        $('.change-job-filter-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/job-filters/status/'+id+'/'+status, '', function (result) {
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

    this.initJobFilterDelete = function () {
        $('.delete-job-filter').off();
        $('.delete-job-filter').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/job-filters/delete/'+id, '', function (result) {
                    self.initJobFiltersDatatable();
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

}

$(document).ready(function() {
    var job_filter = new JobFilter();
    job_filter.initFilters();
    job_filter.initJobFiltersDatatable();
});
