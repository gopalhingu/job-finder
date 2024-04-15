function Testimonial() {

    "use strict";

    var self = this;

    this.initFilters = function () {
        $("#status").off();
        $("#status").change(function () {
            self.initTestimonialsDatatable();
        });
        $('.select2').select2();
    };

    this.initTestimonialsDatatable = function () {
        $('#testimonials_datatable').DataTable({
            "aaSorting": [[ 3, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,5]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/admin/testimonials/data',
                "data": function ( d ) {
                    d.status = $('#status').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    self.initTestimonialCreateOrEditForm();
                    self.initTestimonialChangeStatus();
                    self.initTestimonialDelete();
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

    this.initTestimonialCreateOrEditForm = function () {
        $('.create-or-edit-testimonial').off();
        $('.create-or-edit-testimonial').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_testimonial'] : lang['create_testimonial'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/admin/testimonials/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initTestimonialSave();
                $('[data-toggle="tooltip"]').tooltip();
                $('.select2').select2();
            });
        });
    };

    this.initTestimonialSave = function () {
        application.onSubmit('#admin_testimonial_create_update_form', function (result) {
            application.showLoader('admin_testimonial_create_update_form_button');
            application.post('/admin/testimonials/save', '#admin_testimonial_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.initTestimonialsDatatable();
                } else {
                    application.hideLoader('admin_testimonial_create_update_form_button');
                    application.showMessages(result.messages, 'admin_testimonial_create_update_form .modal-body');
                }
            });
        });
    };
    
    this.initTestimonialChangeStatus = function () {
        $('.change-testimonial-status').off();
        $('.change-testimonial-status').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var status = parseInt($(this).data('status'));
            button.html("<i class='fa fa-spin fa-spinner'></i>");
            button.attr("disabled", true);
            application.load('/admin/testimonials/status/'+id+'/'+status, '', function (result) {
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

    this.initTestimonialDelete = function () {
        $('.delete-testimonial').off();
        $('.delete-testimonial').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/admin/testimonials/delete/'+id, '', function (result) {
                    self.initTestimonialsDatatable();
                });
            }
        });
    };

    this.initTestimonialsListBulkActions = function () {
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
                self.downloadTestimonialExcel(ids);
            } else {
                application.post('/admin/testimonials/bulk-action', {ids:ids, action: $(this).data('action')}, function (result) {
                    $('.bulk-action').val('');
                    $('.all-check').prop('checked', false);
                    self.initTestimonialsDatatable();
                });
            }
        });
    };

    this.downloadTestimonialExcel = function (ids) {
        var form = "#testimonials-form";
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

}

$(document).ready(function() {
    var testimonial = new Testimonial();
    testimonial.initFilters();
    testimonial.initTestimonialsDatatable();
    testimonial.initTestimonialsListBulkActions();
});