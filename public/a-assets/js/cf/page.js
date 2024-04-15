function Page() {

    "use strict";

    var self = this;
    self.myEditor;

    this.initFilters = function () {
        $("#status").off();
        $("#status").change(function () {
            self.initPagesDatatable();
        });
        $('.select2').select2();
    };

    this.initPagesDatatable = function () {
        $('#pages_datatable').DataTable({
            "aaSorting": [[ 3, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,5]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/pages/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initPageCreateOrEditForm();
                    self.initPageChangeStatus();
                    self.initPageDelete();
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

    this.initPageCreateOrEditForm = function () {
        $('.create-or-edit-page').off();
        $('.create-or-edit-page').on('click', function () {
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            window.location = application.url+'/admin/pages/create-or-edit'+id;
        });
    };

    this.initPageSave = function () {
        application.onSubmit('#admin_page_create_update_form', function (result) {
            application.showLoader('admin_page_create_update_form_button');
            application.post('/admin/pages/save', '#admin_page_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_page_create_update_form_button');
                application.showMessages(result.messages, 'admin_page_create_update_form .modal-body');
            });
        });
    };
    
    this.initPageChangeStatus = function () {
        $('.change-page-status').off();
        $('.change-page-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/pages/status/'+id+'/'+status, '', function (result) {
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

    this.initPageDelete = function () {
        $('.delete-page').off();
        $('.delete-page').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/pages/delete/'+id, '', function (result) {
                    self.initPagesDatatable();
                });
            }
        });
    };

    this.initPagesListBulkActions = function () {
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
                self.downloadPageExcel(ids);
            } else {
                application.post('/admin/pages/bulk-action', {ids:ids, action: $(this).data('action')}, function (result) {
                    $('.bulk-action').val('');
                    $('.all-check').prop('checked', false);
                    self.initPagesDatatable();
                });
            }
        });
    };

    this.downloadPageExcel = function (ids) {
        var form = "#pages-form";
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

    this.initCKEditorFiveClassic = function (element_id) {
        var elementExists = document.getElementById(element_id);
        if (elementExists) {
            ClassicEditor.create(document.querySelector('#'+element_id), {
                extraPlugins: [ MyCustomUploadAdapterPlugin ],
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
    var page = new Page();
    page.initFilters();
    page.initPagesDatatable();
    page.initPagesListBulkActions();
    page.initCKEditorFiveClassic("editor");
    page.initPageSave();
});

