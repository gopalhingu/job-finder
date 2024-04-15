function Team() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status, #role").off();
        $("#status, #role").change(function () {
            self.initTeamsDatatable();
        });
        $('.select2').select2();
    };

    this.initProfileUpdate = function () {
        application.onSubmit('#employer_profile_form', function (result) {
            application.showLoader('employer_profile_form_button');
            application.post('/employer/profile-save', '#employer_profile_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('employer_profile_form_button');
                application.showMessages(result.messages, 'employer_profile_form');
            });
        });
    };

    this.initPasswordUpdate = function () {
        application.onSubmit('#employer_password_form', function (result) {
            application.showLoader('employer_password_form_button');
            application.post('/employer/password-save', '#employer_password_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('employer_password_form_button');
                application.showMessages(result.messages, 'employer_password_form');
            });
        });
    };

    this.initTeamsDatatable = function () {
        $('#team_datatable').DataTable({
            "aaSorting": [[ 7, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,1,5,8]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/employer/team/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d.role = $('#role').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initTeamCreateOrEditForm();
                    self.initTeamChangeStatus();
                    self.initTeamDelete();
                    $('.table-bordered').parent().attr('style', 'overflow:auto'); //For responsive
                },
            },
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'info': true,
            'autoWidth': true,
            'destroy':true,
            'stateSave': true,
            'responsive': true
        });
    };

    this.initTeamSave = function () {
        application.onSubmit('#employer_team_create_update_form', function (result) {
            application.showLoader('employer_team_create_update_form_button');
            application.post('/employer/team/save', '#employer_team_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initTeamsDatatable();
                } else {
                    application.hideLoader('employer_team_create_update_form_button');
                    application.showMessages(result.messages, 'employer_team_create_update_form');
                }
            });
        });
    };

    this.initTeamCreateOrEditForm = function () {
        $('.create-or-edit-team').off();
        $('.create-or-edit-team').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_team'] : lang['create_team'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/employer/team/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initTeamSave();
                $('#roles-dropdown').select2();
                $('.dropify').dropify();

                //From assets/employer/js/cf/role.js
                var role = new Role();
                role.initViewRoles();
            });
        });
    };

    this.initTeamChangeStatus = function () {
        $('.change-team-status').off();
        $('.change-team-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/employer/team/status/'+id+'/'+status, '', function (result) {
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

    this.initTeamDelete = function () {
        $('.delete-team').off();
        $('.delete-team').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/employer/team/delete/'+id, '', function (result) {
                    self.initTeamsDatatable();
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
    var team = new Team();
    team.initFilters();
    team.initTeamsDatatable();
    team.initPasswordUpdate();
    team.initProfileUpdate();
    $('.dropify').dropify();
});
