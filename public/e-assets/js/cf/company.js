function Company() {

    "use strict";

    var self = this;
    var job_filters = {};

    this.initFilters = function () {
        $("#status").off();
        $("#status").on('change', function () {
            self.initCompanysDatatable();
        });
        $('.company-filter').each(function(i,v) {
            $(this).on('change', function () {
                job_filters[$(this).attr('id')] = $(this).val();
                self.initCompanysDatatable();
            });
        });
        $('.select2').select2();
    };

    this.initCompanysDatatable = function () {
        $('#companys_datatable').DataTable({
            "aaSorting": [[ 5, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,3,5]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/employer/companys/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initCompanyCreateOrEditForm();
                    self.initCompanyChangeStatus();
                    self.initCompanyDelete();
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

    this.initCompanySave = function () {
        application.onSubmit('#employer_company_create_update_form', function (result) {
            application.showLoader('employer_company_create_update_form_button');
            application.post('/employer/companys/save', '#employer_company_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('employer_company_create_update_form_button');
                application.showMessages(result.messages, 'employer_company_create_update_form');
                if (result.data) {
                    window.location = application.url+'/employer/companys/create-or-edit/'+result.data;
                }                
            });
        });
    };

    this.initCompanyImport = function () {
        application.onSubmit('#employer_company_import_form', function (result) {
            application.showLoader('employer_company_import_form_button');
            application.post('/employer/companys/import-save', '#employer_company_import_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('employer_company_import_form_button');
                application.showMessages(result.messages, 'employer_company_import_form');
                if (result.data) {
                    window.location = application.url+'/employer/companys/create-or-edit/'+result.data;
                }                
            });
        });
    };

    this.initCompanyCreateOrEditForm = function () {
        $('.create-or-edit-company').off();
        $('.create-or-edit-company').on('click', function () {
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            window.location = application.url+'/employer/companys/create-or-edit'+id;            
        });
    };

    this.initCompanyChangeStatus = function () {
        $('.change-company-status').off();
        $('.change-company-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/employer/companys/status/'+id+'/'+status, '', function (result) {
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

    this.initCompanyDelete = function () {
        $('.delete-company').off();
        $('.delete-company').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/employer/companys/delete/'+id, '', function (result) {
                    self.initCompanysDatatable();
                });
            }
        });
    };

    this.initCompanysListBulkActions = function () {
        $('.company-bulk-action').off();
        $('.company-bulk-action').on('click', function (e) {
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
                $('.company-bulk-action').val('');
                return false;
            } else if (action == 'download-excel') {
                var form = "#companys-form";
                $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo(form);
                $("<input />").attr("type", "hidden").attr("name", "_token").attr("value", application._token).appendTo(form);
                $(form).submit();
            }
        });
    };

    this.initiCheck = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
    };

    this.initRemoveCustomField = function () {
        $('.remove-custom-field').off();
        $('.remove-custom-field').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = confirm(lang['are_u_sure']);
            if (status === true) {
                if (id != '') {
                    application.load('/employer/jobs/remove-custom-field/'+id, '', function (result) {
                        button.parent().parent().parent().parent().remove();
                    });
                } else {
                    button.parent().parent().parent().parent().remove();
                }
                self.initShowHideEmptyCustomField();
            }
        });
    }

    this.initAddCustomField = function () {
        $('.add-custom-field').off();
        $('.add-custom-field').on('click', function (event) {
            event.preventDefault();
            application.load('/employer/jobs/add-custom-field', '', function (result) {
                $('.custom-fields-container').append(application.response);
                self.initRemoveCustomField();
                self.initShowHideEmptyCustomField();
            });
        });
    };

    this.initShowHideEmptyCustomField = function () {
        if ($('.custom-value-box').length == 0) {
            $('.no-custom-value-box').show();
        } else {
            $('.no-custom-value-box').hide();
        }
    };    

    this.initCKEditorFiveClassic = function (element_id) {
        var elementExists = document.getElementById(element_id);
        if (elementExists) {
            ClassicEditor.create(document.querySelector('#'+element_id), {
                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                htmlSupport: {
                    allow: [{name: /.*/, attributes: true, classes: true, styles: true}],
                },                
            }).then( editor => {
            }).catch( err => {
                console.error( err.stack );
            });
        }
    };

    this.initCKEditorFiveDecoupled = function (element_id) {
        var elementExists = document.getElementById(element_id);
        if (elementExists) {
            DecoupledEditor.create(document.querySelector('#'+element_id), {
                ckfinder: {
                    uploadUrl: application.url+'/ckeditor/image?command=QuickUpload&type=Files&responseType=json&_token='+application._token
                }
            }).then( editor => {
                var toolbarContainer = document.querySelector('.toolbar-'+element_id);
                toolbarContainer.prepend(editor.ui.view.toolbar.element);
                self.myEditors.push({'id' : element_id, 'editor' : editor});
            }).catch( error => {
                console.error( error );
            });
        }
    };

    this.initCKEditorFour = function (element_id) {
        var elementExists = document.getElementById(element_id);
        if (elementExists) {
            CKEDITOR.replace(element_id, {
                allowedContent : true,
                filebrowserUploadUrl: application.url+'/ckeditor/image?CKEditorFuncNum=1&_token='+application._token,
                filebrowserUploadMethod: 'form',
            });
        }
    };
}

$(document).ready(function() {
    var company = new Company();

    //For both screens
    company.initiCheck();

    //For listing screen
    company.initFilters();
    company.initCompanysDatatable();
    company.initCompanysListBulkActions();
    
    //For create edit screen
    company.initCKEditorFiveClassic('description');
    company.initCompanySave();
    company.initCompanyImport();
    company.initAddCustomField();
    company.initRemoveCustomField();
    company.initShowHideEmptyCustomField();

});
