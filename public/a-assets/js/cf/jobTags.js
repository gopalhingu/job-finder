function JobTags() {

    "use strict";

    var self = this;

    this.initSelectRoleForEdit = function() {
        $('.edit-job-tags').click(function() {
            var id = $('#job-tags-dropdown').val();
            var modal = '#modal-default';
            $(modal).modal('show');
            $(modal+' .modal-title').html(lang['edit-job-tags']);
            application.load('/admin/job-tags/create-or-edit/'+id, modal+' .modal-body-container', function (result) {
                self.initJobTagsSave();
            });
        });
    };

    this.initRoleCreateForm = function () {
        $('.create-job-tags').off();
        $('.create-job-tags').on('click', function () {
            var modal = '#modal-default';
            $(modal).modal('show');
            $(modal+' .modal-title').html(lang['create_job_tag']);
            application.load('/admin/job-tags/create-or-edit', modal+' .modal-body-container', function (result) {
                self.initJobTagsSave();
            });
        });
    };

    this.initJobTagsSave = function () {
        application.onSubmit('#job_tags_create_update_form', function (result) {
            application.showLoader('job_tags_create_update_form_button');
            application.post('/admin/job-tags/save', '#job_tags_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    var data = {id: result.data.id, text: result.data.name};
                    var newOption = new Option(data.text, data.id, true, true);
                    $("#job-tags-dropdown option[value='"+$('#id').val()+"']").remove();
                    $('#job-tags-dropdown').prepend(newOption).trigger('change');
                    $('#modal-default').modal('hide');
                } else {
                    application.hideLoader('job_tags_create_update_form_button');
                    application.showMessages(result.messages, 'job_tags_create_update_form');
                }
            });
        });
    };

    this.initJobTagsDelete = function() {
        $('.delete-job-tags').on('click', function(){
            var id = $('#job-tags-dropdown').val();
            var status = confirm(lang['are_u_sure']);
            if (status === true) {
                application.load('/admin/job-tags/delete/'+id, '', function (result) {
                    $("#job-tags-dropdown option[value='"+id+"']").remove();
                    self.loadPermissions();
                });
            }
        });
    }

    this.loadPermissions = function() {
        var role_id = $('#job-tags-dropdown').val();
        application.load('/admin/roles/role-permissions/'+role_id, '', function (result) {
            $('#permissions-container').html(application.response);
            self.initDualListBox();
        });        
    }

    this.initRolesDropDown = function() {
        $('#job-tags-dropdown').off();
        $('#job-tags-dropdown').on('change', function() {
            self.loadPermissions();
        });
    }

    this.initDualListBox = function() {
        $('.select2').select2();
        $('.duallistbox').off();
        $('.duallistbox').bootstrapDualListbox({
            nonSelectedListLabel: lang['non_selected'],
            selectedListLabel: lang['selected'],
            preserveSelectionOnMove: 'moved',
        });
        $('#permissions-multiselect').off();
        $('#permissions-multiselect').on('change', function() {
            var ids = JSON.stringify($(this).val());
            var data = {ids:ids, role_id:$('#job-tags-dropdown').val()};
            application.post('/admin/roles/update-permissions', data, function (res) {});
        });
    }
}

$(document).ready(function() {
    var jobTags = new JobTags();
    jobTags.initRolesDropDown();
    jobTags.initSelectRoleForEdit();
    jobTags.initRoleCreateForm();
    jobTags.initJobTagsDelete();
    jobTags.initDualListBox();
    jobTags.loadPermissions();
});
