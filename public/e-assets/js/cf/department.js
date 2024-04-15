function Department() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status").off();
        $("#status").change(function () {
            self.initDepartmentsDatatable();
        });
        $('.select2').select2();
    };

    this.initDepartmentsDatatable = function () {
        $('#departments_datatable').DataTable({
            "aaSorting": [[ 3, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,1,5]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/employer/departments/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initDepartmentCreateOrEditForm();
                    self.initDepartmentChangeStatus();
                    self.initDepartmentDelete();
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

    this.initDepartmentSave = function () {
        application.onSubmit('#employer_department_create_update_form', function (result) {
            application.showLoader('employer_department_create_update_form_button');
            application.post('/employer/departments/save', '#employer_department_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initDepartmentsDatatable();

                    //For job create or edit
                    if (!isEmpty(result.data)) {
                        var data = {id: result.data.id, text: result.data.title};
                        var newOption = new Option(data.text, data.id, false, false);
                        $('#departments').append(newOption).trigger('change');
                    }
                } else {
                    application.hideLoader('employer_department_create_update_form_button');
                    application.showMessages(result.messages, 'employer_department_create_update_form');
                }
            });
        });
    };

    this.initDepartmentCreateOrEditForm = function () {
        $('.create-or-edit-department').off();
        $('.create-or-edit-department').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_department'] : lang['create_department'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/employer/departments/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initDepartmentSave();
                $('.dropify').dropify();
            });
        });
    };

    this.initDepartmentChangeStatus = function () {
        $('.change-department-status').off();
        $('.change-department-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/employer/departments/status/'+id+'/'+status, '', function (result) {
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

    this.initDepartmentDelete = function () {
        $('.delete-department').off();
        $('.delete-department').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/employer/departments/delete/'+id, '', function (result) {
                    self.initDepartmentsDatatable();
                });
            }
        });
    };

    this.initDepartmentsListBulkActions = function () {
        $('.bulk-action').off();
        $('.bulk-action').on('click', function (e) {
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
                $('.bulk-action').val('');
                return false;
            } else {
                application.post('/employer/departments/bulk-action', {ids:ids, action: $(this).data('action')}, function (result) {
                    $('.bulk-action').val('');
                    $('.all-check').prop('checked', false);
                    self.initDepartmentsDatatable();
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
    var department = new Department();
    department.initFilters();
    department.initDepartmentsListBulkActions();
    department.initDepartmentsDatatable();
});
