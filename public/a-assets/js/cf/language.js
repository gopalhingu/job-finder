function Language() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status").off();
        $("#status").change(function () {
            self.initLanguagesDatatable();
        });
        $('.select2').select2();
    };

    this.initLanguagesDatatable = function () {
        $('#languages_datatable').DataTable({
            "aaSorting": [[ 2, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,4]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/languages/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initLanguageCreateOrEditForm();
                    self.initLanguageMessagesEditForm();
                    self.initLanguageValidationsEditForm();
                    self.initLanguageChangeStatus();
                    self.initLanguageChangeSelected();
                    self.initLanguageChangeDefault();
                    self.initLanguageDelete();
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

    this.initLanguageSave = function () {
        application.onSubmit('#admin_language_create_or_edit_form', function (result) {
            application.showLoader('admin_language_create_or_edit_form_button');
            application.post('/admin/languages/save', '#admin_language_create_or_edit_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initLanguagesDatatable();
                } else {
                    application.hideLoader('admin_language_create_or_edit_form_button');
                    application.showMessages(result.messages, 'admin_language_create_or_edit_form');
                }
            });
        });
    };

    this.initLanguageMessagesUpdate = function () {
        application.onSubmit('#admin_language_messages_update_form', function (result) {
            application.showLoader('admin_language_messages_update_form_button');
            application.post('/admin/languages/update-messages', '#admin_language_messages_update_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_language_messages_update_form_button');
                application.showMessages(result.messages, 'admin_language_messages_update_form');
            });
        });
    };

    this.initLanguageValidationsUpdate = function () {
        application.onSubmit('#admin_language_validations_update_form', function (result) {
            application.showLoader('admin_language_validations_update_form_button');
            application.post('/admin/languages/update-validations', '#admin_language_validations_update_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_language_validations_update_form_button');
                application.showMessages(result.messages, 'admin_language_validations_update_form');
            });
        });
    };

    this.initLanguageCreateOrEditForm = function () {
        $('.create-or-edit-language').off();
        $('.create-or-edit-language').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_language'] : lang['create_language'];            
            $(modal).modal('show');
            $(modal+' .modal-title').html(lang['create_language']);
            application.load('/admin/languages/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initLanguageSave();
                self.initSelect2FlagDropdown()
            });
        });
    };

    this.initSelect2FlagDropdown = function () {
        $(".select2").select2({
            templateResult: function (item) {
                var $span = $("<span><img src='"+application.url+"/a-assets/flags/"+item.text+".png'/> " + item.text + "</span>");
                return $span;
                },
            templateSelection: function (item) {
                var $span = $("<span><img src='"+application.url+"/a-assets/flags/"+item.text+".png'/> " + item.text + "</span>");
                return $span;
            }
        });        
    }

    this.initLanguageMessagesEditForm = function () {
        $('.edit-language-messages').off();
        $('.edit-language-messages').on('click', function () {
            window.location = application.url+'/admin/languages/edit-messages/'+$(this).data('id');
        });
    };

    this.initLanguageValidationsEditForm = function () {
        $('.edit-language-validations').off();
        $('.edit-language-validations').on('click', function () {
            window.location = application.url+'/admin/languages/edit-validations/'+$(this).data('id');
        });
    };

    this.initLanguageChangeStatus = function () {
        $('.change-language-status').off();
        $('.change-language-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/languages/status/'+id+'/'+status, '', function (result) {
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

    this.initLanguageChangeDefault = function () {
        $('.change-language-default').off();
        $('.change-language-default').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/languages/default/'+id, '', function (result) {
                self.initLanguagesDatatable();
            });
        });
    };

    this.initLanguageChangeSelected = function () {
        $('.change-language-selected').off();
        $('.change-language-selected').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/languages/selected/'+id, '', function (result) {
                self.initLanguagesDatatable();
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

    this.initLanguageDelete = function () {
        $('.delete-language').off();
        $('.delete-language').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/languages/delete/'+id, '', function (result) {
                    self.initLanguagesDatatable();
                });
            }
        });
    };

    this.initLanguagesListBulkActions = function () {
        $('.bulk-action').off();
        $('.bulk-action').on('click', function () {
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
                application.post('/admin/languages/bulk-action', {ids:ids, action: $(this).data('action')}, function (result) {
                    $('.bulk-action').val('');
                    $('.all-check').prop('checked', false);
                    self.initLanguagesDatatable();
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
    var language = new Language();
    language.initLanguageMessagesUpdate();
    language.initLanguageValidationsUpdate();
    language.initFilters();
    language.initLanguagesDatatable();
    language.initLanguagesListBulkActions();
});
