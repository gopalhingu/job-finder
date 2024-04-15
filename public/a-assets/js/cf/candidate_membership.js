function CandidateMembership() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status, #employer_id, #package_id, #payment_type, #package_type").off();
        $("#status, #employer_id, #package_id, #payment_type, #package_type").change(function () {
            self.initCandidateMembershipsDatatable();
        });
        $('.select2').select2();
    };

    this.initCandidateMembershipsDatatable = function () {
        $('#candidate_memberships_datatable').DataTable({
            "aaSorting": [[ 5, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,10]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/candidate-memberships/data',
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
                    self.initCandidateMembershipCreateOrEditForm();
                    self.initCandidateMembershipChangeStatus();
                    self.initCandidateMembershipDelete();
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

    this.initCandidateMembershipCreateOrEditForm = function () {
        $('.create-or-edit-candidate-membership').off();
        $('.create-or-edit-candidate-membership').on('click', function () {
            var modal = '#modal-default';
            $(modal+' .modal-dialog').addClass('modal-lg');
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_candidate_membership'] : lang['create_candidate_membership'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/admin/candidate-memberships/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initCandidateMembershipSave();
                $('.select2').select2();
            });
        });
    };

    this.initCandidateMembershipSave = function () {
        application.onSubmit('#admin_candidate_membership_create_update_form', function (result) {
            application.showLoader('admin_candidate_membership_create_update_form_button');
            application.post('/admin/candidate-memberships/save', '#admin_candidate_membership_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initCandidateMembershipsDatatable();
                } else {
                    application.hideLoader('admin_candidate_membership_create_update_form_button');
                    application.showMessages(result.messages, 'admin_candidate_membership_create_update_form .modal-body');
                }
            });
        });
    };
    
    this.initCandidateMembershipChangeStatus = function () {
        $('.change-candidate-membership-status').off();
        $('.change-candidate-membership-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/candidate-memberships/status/'+id+'/'+status, '', function (result) {
                button.removeClass('btn-success');
                button.removeClass('btn-danger');
                button.addClass(status === 1 ? 'btn-danger' : 'btn-success');
                button.html(status === 1 ? lang['inactive'] : lang['active']);
                button.data('status', status === 1 ? 0 : 1);
                button.attr("disabled", false);
                button.attr("title", status === 1 ? lang['click_to_activate'] : lang['click_to_deactivate']);
                self.initCandidateMembershipsDatatable();
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

    this.initCandidateMembershipDelete = function () {
        $('.delete-candidate-membership').off();
        $('.delete-candidate-membership').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/candidate-memberships/delete/'+id, '', function (result) {
                    self.initCandidateMembershipsDatatable();
                });
            }
        });
    };

    this.initCandidateMembershipsListBulkActions = function () {
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
                self.downloadCandidateMembershipExcel(ids);
            } else {
                application.post('/admin/candidate-memberships/bulk-action', {ids:ids, action: $(this).data('action')}, function (result) {
                    $('.bulk-action').val('');
                    $('.all-check').prop('checked', false);
                    self.initCandidateMembershipsDatatable();
                });
            }
        });
    };

    this.downloadCandidateMembershipExcel = function (ids) {
        var form = "#candidate-memberships-form";
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
    var candidate_membership = new CandidateMembership();
    candidate_membership.initFilters();
    candidate_membership.initCandidateMembershipsDatatable();
    candidate_membership.initCandidateMembershipsListBulkActions();
});