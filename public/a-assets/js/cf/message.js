function Message() {

    "use strict";

    var self = this;

    this.initMessagesDatatable = function () {
        $('#messages_datatable').DataTable({
            "aaSorting": [[ 5, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,6]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/messages/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initMessageDelete();
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
    
    this.initAllCheck = function () {
        $('input.all-check').on('ifChecked', function(event){
            $('input.single-check').iCheck('check');
        });
        $('input.all-check').on('ifUnchecked', function(event){
            $('input.single-check').iCheck('uncheck');
        });
    };

    this.initMessageDelete = function () {
        $('.delete-message').off();
        $('.delete-message').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/messages/delete/'+id, '', function (result) {
                    self.initMessagesDatatable();
                });
            }
        });
    };

    this.initMessagesListBulkActions = function () {
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
                self.downloadMessageExcel(ids);
            } else if (action == 'reply') {
                self.initReplyForm(ids);
            } else {
            }
        });
    };

    this.initReplyForm = function(ids) {
        var modal = '#modal-default';
        $(modal).modal('show');
        $(modal+' .modal-title').html('Email');
        application.load('/admin/messages/message-view', modal+' .modal-body-container', function (result) {
            $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo('#admin_reply_to_message_form');
            self.initCKEditor('message');
            self.initReply();
        });
    };

    this.initReply = function () {
        application.onSubmit('#admin_reply_to_message_form', function (result) {
            for(var instanceName in CKEDITOR.instances)
                CKEDITOR.instances[instanceName].updateElement();
            application.showLoader('admin_reply_to_message_form_button');
            application.post('/admin/messages/message', '#admin_reply_to_message_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                } else {
                    application.hideLoader('admin_reply_to_message_form_button');
                    application.showMessages(result.messages, 'admin_reply_to_message_form .modal-body');
                }
            });
        });
    };

    this.downloadMessageExcel = function (ids) {
        var form = "#messages-form";
        $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", "_token").attr("value", application._token).appendTo(form);
        $(form).submit();
    };

    this.initCKEditor = function (element_id) {
        var elementExists = document.getElementById(element_id);
        if (elementExists) {
            CKEDITOR.replace(element_id, {
                allowedContent : true,
                filebrowserUploadUrl: application.url+'/ckeditor/image?CKEditorFuncNum=1&_token='+application._token,
                filebrowserUploadMethod: 'form',
            });
        }
    };    

    this.initiCheck = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
    };

}

$(document).ready(function() {
    var message = new Message();
    message.initMessagesDatatable();
    message.initMessagesListBulkActions();
});