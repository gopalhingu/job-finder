function Job() {

    "use strict";

    var self = this;
    var job_filters = {};

    this.initFilters = function () {
        $("#status, #department").off();
        $("#status, #department").on('change', function () {
            self.initJobsDatatable();
        });
        $('.job-filter').each(function(i,v) {
            $(this).on('change', function () {
                job_filters[$(this).attr('id')] = $(this).val();
                self.initJobsDatatable();
            });
        });
        $('.select2').select2();
    };

    this.initJobsDatatable = function () {
        $('#jobs_datatable').DataTable({
            "aaSorting": [[ 8, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,3,10]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/company/jobs/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d.department = $('#department').val();
                    d.job_filters = job_filters;
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initJobCreateOrEditForm();
                    self.initJobChangeStatus();
                    self.initJobDelete();
                    self.initJobFollowList();
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

    this.initJobFollowFilters = function () {
        $("#jobFollowstatus").off();
        $("#jobFollowstatus").on('change', function () {
            var urlParts = window.location.pathname.split('/');
            var idFromUrl = urlParts[urlParts.length - 1]; 
            self.initJobsFollowDatatable(idFromUrl);
        });
        $('.select2').select2();
    };

    this.initJobsFollowDatatable = function (jobid) {
        $('#jobs_follow_datatable').DataTable({
            "aaSorting": [[ 3, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,3,3]}],
            "lengthMenu": [[3, 25, 50, 100000000], [3, 25, 50, "All"]],
            "searchDelay": 1000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/company/jobs/job-follow/new-value',
                "data": function ( d ) {
                    console.log(d)
                    d.jobFollowStatus = $('#jobFollowstatus').val();
                    d.jobid = jobid;
                    // d.job_filters = job_filters;
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initJobFollowChangeStatus();
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

    this.initJobFollowChangeStatus = function () {
        $('.change-job-follow-status').off();
        $('.change-job-follow-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/company/jobs/job-follow-status/'+id+'/'+status, '', function (result) {
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

    this.initJobSave = function () {
        application.onSubmit('#company_job_create_update_form', function (result) {
            application.showLoader('company_job_create_update_form_button');
            application.post('/company/jobs/save', '#company_job_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('company_job_create_update_form_button');
                application.showMessages(result.messages, 'company_job_create_update_form');
                if (result.data) {
                    window.location = application.url+'/company/jobs/create-or-edit/'+result.data;
                }                
            });
        });
    };

    this.initJobCreateOrEditForm = function () {
        $('.create-or-edit-job').off();
        $('.create-or-edit-job').on('click', function () {
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            window.location = application.url+'/company/jobs/create-or-edit'+id;            
        });
    };

    this.initJobFollowList = function () {
        $('.get-job-follow-values').off();
        $('.get-job-follow-values').on('click', function () {
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            window.location = application.url+'/company/job-follow/get-values'+id;            
        });
    };

    this.initJobChangeStatus = function () {
        $('.change-job-status').off();
        $('.change-job-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/company/jobs/status/'+id+'/'+status, '', function (result) {
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

    this.initJobDelete = function () {
        $('.delete-job').off();
        $('.delete-job').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/company/jobs/delete/'+id, '', function (result) {
                    self.initJobsDatatable();
                });
            }
        });
    };

    this.initJobsListBulkActions = function () {
        $('.job-bulk-action').off();
        $('.job-bulk-action').on('click', function (e) {
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
                $('.job-bulk-action').val('');
                return false;
            } else if (action == 'download-excel') {
                var form = "#jobs-form";
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
                    application.load('/company/jobs/remove-custom-field/'+id, '', function (result) {
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
            application.load('/company/jobs/add-custom-field', '', function (result) {
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
    var job = new Job();

    var urlParts = window.location.pathname.split('/');
    var idFromUrl = urlParts[urlParts.length - 1]; 

    //For both screens
    job.initiCheck();

    //For listing screen
    job.initFilters();
    job.initJobsDatatable();
    
    job.initJobFollowFilters();
    job.initJobsFollowDatatable(idFromUrl);
    job.initJobsListBulkActions();
    
    //For create edit screen
    job.initCKEditorFiveClassic('description');
    job.initJobSave();
    job.initAddCustomField();
    job.initRemoveCustomField();
    job.initShowHideEmptyCustomField();

    var department = new Department(); //From department.js
    department.initDepartmentCreateOrEditForm(); 
});