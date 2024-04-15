function Employer() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status, #role").off();
        $("#status, #role").change(function () {
            self.initEmployersDatatable();
        });
        $('.select2').select2();
    };

    this.initEmployersDatatable = function () {
        $('#employers_datatable').DataTable({
            "aaSorting": [[ 5, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,1,6,9]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/employers/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d.role = $('#role').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initEmployerCreateOrEditForm();
                    self.initEmployerChangeStatus();
                    self.initEmployerDelete();
                    $('.table-bordered').parent().attr('style', 'overflow:auto'); //For responsive
                },
            },
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'info': true,
            'autoWidth': false,
            'destroy':true,
            'stateSave': true,
            'responsive': false
        });
    };

    this.initBulkAssignRoleSave = function () {
        application.onSubmit('#admin_roles_bulk_assign_form', function (result) {
            application.showLoader('admin_roles_bulk_assign_form_button');
            application.post('/admin/employers/save-roles', '#admin_roles_bulk_assign_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initEmployersDatatable();
                } else {
                    application.hideLoader('admin_roles_bulk_assign_form_button');
                    application.showMessages(result.messages, 'admin_roles_bulk_assign_form .modal-body');
                }
            });
        });
    };    

    this.initEmployerCreateOrEditForm = function () {
        $('.create-or-edit-employer').off();
        $('.create-or-edit-employer').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_employer'] : lang['create_employer'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/admin/employers/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initEmployerSave();
                $('#roles-dropdown').select2();
                $('.dropify').dropify();
            });
        });
    };

    this.initEmployerSave = function () {
        application.onSubmit('#admin_employer_create_update_form', function (result) {
            application.showLoader('admin_employer_create_update_form_button');
            application.post('/admin/employers/save', '#admin_employer_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initEmployersDatatable();
                } else {
                    application.hideLoader('admin_employer_create_update_form_button');
                    application.showMessages(result.messages, 'admin_employer_create_update_form .modal-body');
                }
            });
        });
    };
    
    this.initEmployerChangeStatus = function () {
        $('.change-employer-status').off();
        $('.change-employer-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/employers/status/'+id+'/'+status, '', function (result) {
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

    this.initEmployerDelete = function () {
        $('.delete-employer').off();
        $('.delete-employer').on('click', function () {
            var status = confirm(lang['are_u_sure']+' '+lang['associated_data_msg']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/employers/delete/'+id, '', function (result) {
                    self.initEmployersDatatable();
                });
            }
        });
    };

    this.initEmployersListBulkActions = function () {
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
            }
            if (action == 'download-excel') {
                self.downloadEmployerExcel(ids);
            } else if (action == 'email') {
                self.initEmailEmployerForm(ids);            
            } else if (action == 'assign-role') {
                application.load('/admin/roles/rolesAsSelect2/employer', '.modal-body-container', function (result) {
                    self.initBulkAssignRoleSave();
                    $('#employer_ids').val(JSON.stringify(ids));
                    $('.select2').select2();
                    $('#modal-default .modal-title').html('Assign Role(s)');
                    $('#modal-default').modal('show');
                });
            } else {
                application.post('/admin/employers/bulk-action', {ids:ids, action: $(this).data('action')}, function (result) {
                    $('.bulk-action').val('');
                    $('.all-check').prop('checked', false);
                    self.initEmployersDatatable();
                });
            }
        });
    };

    this.initEmailEmployerForm = function(ids) {
        var modal = '#modal-default';
        $(modal).modal('show');
        $(modal+' .modal-title').html('Email');
        application.load('/admin/employers/message-view', modal+' .modal-body-container', function (result) {
            $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo('#admin_employer_message_form');
            self.initCKEditor();
            self.initEmailEmployer();
        });
    };

    this.initEmailEmployer = function () {
        application.onSubmit('#admin_employer_message_form', function (result) {
            for(var instanceName in CKEDITOR.instances)
                CKEDITOR.instances[instanceName].updateElement();
            application.showLoader('admin_employer_message_form_button');
            application.post('/admin/employers/message', '#admin_employer_message_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                } else {
                    application.hideLoader('admin_employer_message_form_button');
                    application.showMessages(result.messages, 'admin_employer_message_form .modal-body');
                }
            });
        });
    };

    this.downloadEmployerExcel = function (ids) {
        var form = "#employers-form";
        $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", "_token").attr("value", application._token).appendTo(form);
        $(form).submit();
    };

    this.initCKEditor = function () {
        var elementExists = document.getElementById("msg");
        if (elementExists) {
            CKEDITOR.replace('msg', {
                allowedContent : true,
                filebrowserUploadUrl: application.url+'/ckeditor/image?CKEditorFuncNum=1&_token='+application._token,
                filebrowserUploadMethod: 'form',
            });
        }
    };

    this.initiCheck = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
    };

}

$(document).ready(function() {
    var employer = new Employer();
    employer.initFilters();
    employer.initEmployersDatatable();
    employer.initEmployersListBulkActions();
    $('.dropify').dropify();
});