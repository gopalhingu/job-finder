function Candidate() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#job_title, #experience").off();
        $("#job_title, #experience").on('keyup', function() {
            self.initCandidatesDatatable();
        });        
        $('.select2').select2();
    };

    this.initCandidatesDatatable = function () {
        $('#candidates_datatable').DataTable({
            "aaSorting": [[ 6, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,1]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/employer/candidates/data',
                "data": function ( d ) {
                    d.job_title = $('#job_title').val();
                    d.experience = $('#experience').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initLoadResume();
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

    this.initCandidateChangeStatus = function () {
        $('.change-candidate-status').off();
        $('.change-candidate-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/employer/candidates/status/'+id+'/'+status, '', function (result) {
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

    this.initCandidatesListBulkActions = function () {
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
            if (action == 'download-resume') {
	        	self.downloadResume(ids);
            } else if (action == 'download-excel') {
                self.downloadCandidateExcel(ids);
            } else if (action == 'email') {
	        	self.initEmailCandidateForm(ids);
            }
        });
    };

    this.initiCheck = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
    };

    this.initLoadResume = function() {
        $('.view-resume').on('click', function() {
            var modal = '#modal-right';
            $(modal).modal('show');
            $(modal+' .modal-title').html('Resume');
            var button = $(this);
            var id = button.data('id');
            application.load('/employer/candidates/resume/'+id, modal+' .modal-body', function (result) {
            });
        });
    };

    this.initEmailCandidateForm = function(ids) {
        var modal = '#modal-default';
        $(modal).modal('show');
        $(modal+' .modal-title').html('Email');
        application.load('/employer/candidates/message-view', modal+' .modal-body-container', function (result) {
            $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo('#employer_candidate_message_form');
            self.initCKEditor();
            self.initEmailCandidate();
        });
    };

    this.initEmailCandidate = function () {
        application.onSubmit('#employer_candidate_message_form', function (result) {
            for(var instanceName in CKEDITOR.instances)
                CKEDITOR.instances[instanceName].updateElement();
            application.showLoader('employer_candidate_message_form_button');
            application.post('/employer/candidates/message', '#employer_candidate_message_form', function (res) {
                var result = JSON.parse(application.response);
                console.log(result);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                } else {
                    application.hideLoader('employer_candidate_message_form_button');
                    application.showMessages(result.messages, 'employer_candidate_message_form');
                }
            });
        });
    };        

    this.downloadResume = function (ids) {
        var form = "#resume-form";
        $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", "_token").attr("value", application._token).appendTo(form);
        $(form).submit();
    };

    this.downloadCandidateExcel = function (ids) {
        var form = "#candidates-form";
        $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", "_token").attr("value", application._token).appendTo(form);
        $(form).submit();
    };

    this.initCKEditor = function () {
        var elementExists = document.getElementById("msg");
        if (elementExists) {
            CKEDITOR.replace('msg', {
                allowedContent : true,
                filebrowserUploadUrl: application.url+'/ckeditor/image?CKEditorFuncNum=1&_token='+application._token,
                filebrowserUploadMethod: 'form',
            });
        }
    };

    this.initDropifyAndDatepicker =  function () {
      $(".datepicker").datepicker({
        changeMonth: true, 
        changeYear: true, 
        dateFormat: "yy-mm-dd",
        yearRange: "-90:+00"
      });
      $('.dropify').dropify();
    };

}

$(document).ready(function() {
    var candidate = new Candidate();
    candidate.initFilters();
    candidate.initCandidatesListBulkActions();
    candidate.initCandidatesDatatable();
});
