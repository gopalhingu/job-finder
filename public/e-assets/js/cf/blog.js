function Blog() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status").off();
        $("#status").change(function () {
            self.initBlogsDatatable();
        });
    };

    this.initSelect2 = function () {
        $('.select2').select2();        
    }

    this.initBlogsDatatable = function () {
        $('#blogs_datatable').DataTable({
            "aaSorting": [[ 2, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,4]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/employer/blogs/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initBlogCreateOrEditForm();
                    self.initBlogChangeStatus();
                    self.initBlogDelete();
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

    this.initBlogSave = function () {
        application.onSubmit('#employer_blog_create_update_form', function (result) {
            application.showLoader('employer_blog_create_update_form_button');
            application.post('/employer/blogs/save', '#employer_blog_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('employer_blog_create_update_form_button');
                application.showMessages(result.messages, 'employer_blog_create_update_form');
                if (result.data ) {
                    window.location = application.url+'/employer/blogs/create-or-edit/'+result.data;
                }
            });
        });
    };

    this.initBlogCreateOrEditForm = function () {
        $('.create-or-edit-blog').off();
        $('.create-or-edit-blog').on('click', function () {
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            window.location = application.url+'/employer/blogs/create-or-edit'+id;
        });
    };

    this.initBlogChangeStatus = function () {
        $('.change-blog-status').off();
        $('.change-blog-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/employer/blogs/status/'+id+'/'+status, '', function (result) {
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

    this.initBlogDelete = function () {
        $('.delete-blog').off();
        $('.delete-blog').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/employer/blogs/delete/'+id, '', function (result) {
                    self.initBlogsDatatable();
                });
            }
        });
    };

    this.initBlogsListBulkActions = function () {
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
                application.post('/employer/blogs/bulk-action', {ids:ids, action: $(this).data('action')}, function (result) {
                    $('.bulk-action').val('');
                    $('.all-check').prop('checked', false);
                    self.initBlogsDatatable();
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

    this.initDropify = function () {
        $('.dropify').dropify();        
    }
}

$(document).ready(function() {
    var blog = new Blog();
    blog.initBlogSave();
    blog.initDropify();
    blog.initCKEditorFiveClassic('description');
    blog.initFilters();
    blog.initSelect2();
    blog.initBlogsDatatable();
    blog.initBlogsListBulkActions();
});
