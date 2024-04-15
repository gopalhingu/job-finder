function CandidatePackage() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status").off();
        $("#status").change(function () {
            self.initCandidatePackagesDatatable();
        });
        $('.select2').select2();
    };

    this.initCandidatePackagesDatatable = function () {
        $('#candidate_packages_datatable').DataTable({
            "aaSorting": [[ 8, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,12]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/candidate-packages/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initCandidatePackageCreateOrEditForm();
                    self.initCandidatePackageChangeStatus();
                    self.initCandidatePackageChangeFreeStatus();
                    self.initCandidatePackageChangeTopStatus();
                    self.initCandidatePackageDelete();
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

    this.initCandidatePackageCreateOrEditForm = function () {
        $('.create-or-edit-candidate-package').off();
        $('.create-or-edit-candidate-package').on('click', function () {
            var modal = '#modal-default';
            $(modal+' .modal-dialog').addClass('modal-lg');
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_candidate_package'] : lang['create_candidate_package'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/admin/candidate-packages/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initCandidatePackageSave();
                $('[data-toggle="tooltip"]').tooltip();
                $('.dropify').dropify();
            });
        });
    };

    this.initCandidatePackageSave = function () {
        application.onSubmit('#admin_candidate_package_create_update_form', function (result) {
            application.showLoader('admin_candidate_package_create_update_form_button');
            application.post('/admin/candidate-packages/save', '#admin_candidate_package_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initCandidatePackagesDatatable();
                } else {
                    application.hideLoader('admin_candidate_package_create_update_form_button');
                    application.showMessages(result.messages, 'admin_candidate_package_create_update_form .modal-body');
                }
            });
        });
    };
    
    this.initCandidatePackageChangeStatus = function () {
        $('.change-candidate-package-status').off();
        $('.change-candidate-package-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/candidate-packages/status/'+id+'/'+status, '', function (result) {
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
    
    this.initCandidatePackageChangeFreeStatus = function () {
        $('.change-candidate-package-free').off();
        $('.change-candidate-package-free').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/candidate-packages/status-free/'+id+'/'+status, '', function (result) {
                self.initCandidatePackagesDatatable();
            });
        });
    };
    
    this.initCandidatePackageChangeTopStatus = function () {
        $('.change-candidate-package-top').off();
        $('.change-candidate-package-top').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/candidate-packages/status-top/'+id+'/'+status, '', function (result) {
                self.initCandidatePackagesDatatable();
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

    this.initCandidatePackageDelete = function () {
        $('.delete-candidate-package').off();
        $('.delete-candidate-package').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/candidate-packages/delete/'+id, '', function (result) {
                    self.initCandidatePackagesDatatable();
                });
            }
        });
    };

    this.initCandidatePackagesListBulkActions = function () {
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
                self.downloadCandidatePackageExcel(ids);
            } else {
                application.post('/admin/candidate-packages/bulk-action', {ids:ids, action: $(this).data('action')}, function (result) {
                    $('.bulk-action').val('');
                    $('.all-check').prop('checked', false);
                    self.initCandidatePackagesDatatable();
                });
            }
        });
    };

    this.downloadCandidatePackageExcel = function (ids) {
        var form = "#candidate-packages-form";
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
    var candidate_package = new CandidatePackage();
    candidate_package.initFilters();
    candidate_package.initCandidatePackagesDatatable();
    candidate_package.initCandidatePackagesListBulkActions();
});