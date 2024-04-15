function FaqsCategory() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status").off();
        $("#status").change(function () {
            self.initFaqsCategoryDatatable();
        });
        $('.select2').select2();
    };

    this.initFaqsCategoryDatatable = function () {
        $('#faqs_categories_datatable').DataTable({
            "aaSorting": [[ 2, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,4]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/faqs-categories/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initFaqsCategoryCreateOrEditForm();
                    self.initFaqsCategoryChangeStatus();
                    self.initFaqsCategoryDelete();
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

    this.initFaqsCategoryCreateOrEditForm = function () {
        $('.create-or-edit-faqs-category').off();
        $('.create-or-edit-faqs-category').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_faqs_category'] : lang['create_faqs_category'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/admin/faqs-categories/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initFaqsCategorySave();
            });
        });
    };

    this.initFaqsCategorySave = function () {
        application.onSubmit('#admin_faqs_categories_create_update_form', function (result) {
            application.showLoader('admin_faqs_categories_create_update_form_button');
            application.post('/admin/faqs-categories/save', '#admin_faqs_categories_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initFaqsCategoryDatatable();
                } else {
                    application.hideLoader('admin_faqs_categories_create_update_form_button');
                    application.showMessages(result.messages, 'admin_faqs_categories_create_update_form .modal-body');
                }
            });
        });
    };
    
    this.initFaqsCategoryChangeStatus = function () {
        $('.change-faqs-category-status').off();
        $('.change-faqs-category-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/faqs-categories/status/'+id+'/'+status, '', function (result) {
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
    
    this.initFaqsCategoryDelete = function () {
        $('.delete-faqs-category').off();
        $('.delete-faqs-category').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/faqs-categories/delete/'+id, '', function (result) {
                    self.initFaqsCategoryDatatable();
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

    this.initAllCheck = function () {
        $('input.all-check').on('ifChecked', function(event){
            $('input.single-check').iCheck('check');
        });
        $('input.all-check').on('ifUnchecked', function(event){
            $('input.single-check').iCheck('uncheck');
        });
    };    
}

$(document).ready(function() {
    var faqs_category = new FaqsCategory();
    faqs_category.initFilters();
    faqs_category.initFaqsCategoryDatatable();
});