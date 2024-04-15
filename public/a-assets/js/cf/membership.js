function Membership() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status, #employer_id, #package_id, #payment_type, #package_type").off();
        $("#status, #employer_id, #package_id, #payment_type, #package_type").change(function () {
            self.initMembershipsDatatable();
        });
        $('.select2').select2();
    };

    this.initMembershipsDatatable = function () {
        $('#memberships_datatable').DataTable({
            "aaSorting": [[ 5, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,10]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/memberships/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d.employer_id = $('#employer_id').val();
                    d.package_id = $('#package_id').val();
                    d.payment_type = $('#payment_type').val();
                    d.package_type = $('#package_type').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initMembershipCreateOrEditForm();
                    self.initMembershipChangeStatus();
                    self.initMembershipDelete();
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

    this.initMembershipCreateOrEditForm = function () {
        $('.create-or-edit-membership').off();
        $('.create-or-edit-membership').on('click', function () {
            var modal = '#modal-default';
            $(modal+' .modal-dialog').addClass('modal-lg');
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_membership'] : lang['create_membership'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/admin/memberships/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initMembershipSave();
                $('.select2').select2();
            });
        });
    };

    this.initMembershipSave = function () {
        application.onSubmit('#admin_membership_create_update_form', function (result) {
            application.showLoader('admin_membership_create_update_form_button');
            application.post('/admin/memberships/save', '#admin_membership_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initMembershipsDatatable();
                } else {
                    application.hideLoader('admin_membership_create_update_form_button');
                    application.showMessages(result.messages, 'admin_membership_create_update_form .modal-body');
                }
            });
        });
    };
    
    this.initMembershipChangeStatus = function () {
        $('.change-membership-status').off();
        $('.change-membership-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/memberships/status/'+id+'/'+status, '', function (result) {
                button.removeClass('btn-success');
                button.removeClass('btn-danger');
                button.addClass(status === 1 ? 'btn-danger' : 'btn-success');
                button.html(status === 1 ? lang['inactive'] : lang['active']);
                button.data('status', status === 1 ? 0 : 1);
                button.attr("disabled", false);
                button.attr("title", status === 1 ? lang['click_to_activate'] : lang['click_to_deactivate']);
                self.initMembershipsDatatable();
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

    this.initMembershipDelete = function () {
        $('.delete-membership').off();
        $('.delete-membership').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/memberships/delete/'+id, '', function (result) {
                    self.initMembershipsDatatable();
                });
            }
        });
    };

    this.initMembershipsListBulkActions = function () {
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
                self.downloadMembershipExcel(ids);
            } else {
                application.post('/admin/memberships/bulk-action', {ids:ids, action: $(this).data('action')}, function (result) {
                    $('.bulk-action').val('');
                    $('.all-check').prop('checked', false);
                    self.initMembershipsDatatable();
                });
            }
        });
    };

    this.downloadMembershipExcel = function (ids) {
        var form = "#memberships-form";
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
    var membership = new Membership();
    membership.initFilters();
    membership.initMembershipsDatatable();
    membership.initMembershipsListBulkActions();
});