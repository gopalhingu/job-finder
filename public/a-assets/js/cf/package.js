function Package() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status").off();
        $("#status").change(function () {
            self.initPackagesDatatable();
        });
        $('.select2').select2();
    };

    this.initPackagesDatatable = function () {
        $('#packages_datatable').DataTable({
            "aaSorting": [[ 14, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,18]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/packages/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initPackageCreateOrEditForm();
                    self.initPackageChangeStatus();
                    self.initPackageChangeFreeStatus();
                    self.initPackageChangeTopStatus();
                    self.initPackageDelete();
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

    this.initPackageCreateOrEditForm = function () {
        $('.create-or-edit-package').off();
        $('.create-or-edit-package').on('click', function () {
            var modal = '#modal-default';
            $(modal+' .modal-dialog').addClass('modal-lg');
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_package'] : lang['create_package'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/admin/packages/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initPackageSave();
                $('[data-toggle="tooltip"]').tooltip();
                $('.dropify').dropify();
            });
        });
    };

    this.initPackageSave = function () {
        application.onSubmit('#admin_package_create_update_form', function (result) {
            application.showLoader('admin_package_create_update_form_button');
            application.post('/admin/packages/save', '#admin_package_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initPackagesDatatable();
                } else {
                    application.hideLoader('admin_package_create_update_form_button');
                    application.showMessages(result.messages, 'admin_package_create_update_form .modal-body');
                }
            });
        });
    };
    
    this.initPackageChangeStatus = function () {
        $('.change-package-status').off();
        $('.change-package-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/packages/status/'+id+'/'+status, '', function (result) {
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
    
    this.initPackageChangeFreeStatus = function () {
        $('.change-package-free').off();
        $('.change-package-free').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/packages/status-free/'+id+'/'+status, '', function (result) {
                self.initPackagesDatatable();
            });
        });
    };
    
    this.initPackageChangeTopStatus = function () {
        $('.change-package-top').off();
        $('.change-package-top').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/packages/status-top/'+id+'/'+status, '', function (result) {
                self.initPackagesDatatable();
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

    this.initPackageDelete = function () {
        $('.delete-package').off();
        $('.delete-package').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/packages/delete/'+id, '', function (result) {
                    self.initPackagesDatatable();
                });
            }
        });
    };

    this.initPackagesListBulkActions = function () {
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
                self.downloadPackageExcel(ids);
            } else {
                application.post('/admin/packages/bulk-action', {ids:ids, action: $(this).data('action')}, function (result) {
                    $('.bulk-action').val('');
                    $('.all-check').prop('checked', false);
                    self.initPackagesDatatable();
                });
            }
        });
    };

    this.downloadPackageExcel = function (ids) {
        var form = "#packages-form";
        $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", "_token").attr("value", application._token).appendTo(form);
        $(form).submit();
    };

    this.initiCheck = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
    };

}

$(document).ready(function() {
    var package = new Package();
    package.initFilters();
    package.initPackagesDatatable();
    package.initPackagesListBulkActions();
});