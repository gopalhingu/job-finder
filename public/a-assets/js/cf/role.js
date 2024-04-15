function Role() {

    "use strict";

    var self = this;

    this.initSelectRoleForEdit = function() {
        $('.edit-role').click(function() {
            var role_id = $('#roles-dropdown').val();
            var modal = '#modal-default';
            $(modal).modal('show');
            $(modal+' .modal-title').html(lang['edit_role']);
            application.load('/admin/roles/create-or-edit/'+role_id, modal+' .modal-body-container', function (result) {
                self.initRoleSave();
            });
        });
    };

    this.initRoleCreateForm = function () {
        $('.create-role').off();
        $('.create-role').on('click', function () {
            var modal = '#modal-default';
            $(modal).modal('show');
            $(modal+' .modal-title').html(lang['create_role']);
            application.load('/admin/roles/create-or-edit', modal+' .modal-body-container', function (result) {
                self.initRoleSave();
            });
        });
    };

    this.initRoleSave = function () {
        application.onSubmit('#admin_role_create_update_form', function (result) {
            application.showLoader('admin_role_create_update_form_button');
            application.post('/admin/roles/save', '#admin_role_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    var data = {id: result.data.id, text: result.data.title};
                    var newOption = new Option(data.text, data.id, true, true);
                    $("#roles-dropdown option[value='"+$('#role_id').val()+"']").remove();
                    $('#roles-dropdown').prepend(newOption).trigger('change');
                    $('#modal-default').modal('hide');
                } else {
                    application.hideLoader('admin_role_create_update_form_button');
                    application.showMessages(result.messages, 'admin_role_create_update_form');
                }
            });
        });
    };

    this.initRoleDelete = function() {
        $('.delete-role').on('click', function(){
            var role_id = $('#roles-dropdown').val();
            var status = confirm(lang['are_u_sure']);
            if (status === true) {
                application.load('/admin/roles/delete/'+role_id, '', function (result) {
                    $("#roles-dropdown option[value='"+role_id+"']").remove();
                    self.loadPermissions();
                });
            }
        });
    }

    this.loadPermissions = function() {
        var role_id = $('#roles-dropdown').val();
        application.load('/admin/roles/role-permissions/'+role_id, '', function (result) {
            $('#permissions-container').html(application.response);
            self.initDualListBox();
        });        
    }

    this.initRolesDropDown = function() {
        $('#roles-dropdown').off();
        $('#roles-dropdown').on('change', function() {
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
            var data = {ids:ids, role_id:$('#roles-dropdown').val()};
            application.post('/admin/roles/update-permissions', data, function (res) {});
        });
    }
}

$(document).ready(function() {
    var role = new Role();
    role.initRolesDropDown();
    role.initSelectRoleForEdit();
    role.initRoleCreateForm();
    role.initRoleDelete();
    role.initDualListBox();
    role.loadPermissions();
});
