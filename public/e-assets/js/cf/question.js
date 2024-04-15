function Question() {

    "use strict";

    var self = this;

    this.initQuestionCreateOrEditForm = function () {
        $('.create-or-edit-question').off();
        $('.create-or-edit-question').on('click', function () {
            var nature = $('#nature').val();
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_question'] : lang['create_question'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/employer/questions/create-or-edit/'+nature+id, modal+' .modal-body-container', function (result) {
                self.initQuestionSave();
                self.initiCheck();
                self.initSwitchAnswersType();
                self.initAddAnswer();
                self.initRemoveAnswer();
                var dropifyEvent = $('.dropify').dropify();
                dropifyEvent.on('dropify.afterClear', function(event, element){
                    var id = element.input[0].dataset.id;
                    application.load('/employer/questions/remove-image/'+id, '', function (result) {});
                });
            });
        });
    };

    this.initQuestionSave = function () {
        application.onSubmit('#employer_question_create_update_form', function (result) {
            application.showLoader('employer_question_create_update_form_button');
            application.post('/employer/questions/save', '#employer_question_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    $('#questions_page').val(1);
                    self.loadListWithParameters();
                } else {
                    application.hideLoader('employer_question_create_update_form_button');
                    application.showMessages(result.messages, 'employer_question_create_update_form');
                }
            });
        });
    };

    this.loadListWithParameters = function (ids) {
        var form = "#questions_form";
        $(form).find('input').remove();
        var nature = $('#nature').val();
        var qs = $('#questions_search').val();
        var nqs = nature+'_questions_search';
        var qci = $('#questions_category_id').val();
        var nqci = nature+'_questions_category_id';
        var qt = $('#questions_type').val();
        var nqt = nature+'_questions_type';
        var qp = $('#questions_page').val();
        var nqp = nature+'_questions_page';
        var qpp = $('#questions_per_page').val();
        var nqpp = nature+'_questions_per_page';
        $("<input />").attr("type", "hidden").attr("name", nqs).attr("value", qs).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", nqci).attr("value", qci).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", nqt).attr("value", qt).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", nqp).attr("value", qp).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", nqpp).attr("value", qpp).appendTo(form);
        application.post('/employer/questions/'+nature, form, function (res) {
            var result = JSON.parse(application.response);
            $('#questions-bank').html(result.list);
            $('#total_pages').val(result.total_pages);
            $('#pagination-container').html(result.pagination);
            self.enableDisablePaginationButtons(false);
            self.initQuestionCreateOrEditForm();
            self.initQuestionDelete();
        });        
    };

    this.initSetPerPage = function () {
        $('.per_page').on('click', function (e) {
            e.preventDefault();
            $('#questions_per_page').val(parseInt($(this).data('value')));
            $('#questions_page').val(1);
            self.loadListWithParameters();
            self.enableDisablePaginationButtons(true);
        });
    };

    this.initNextPage = function () {
        $('.next-button').on('click', function (e) {
            e.preventDefault();
            var currentPage = parseInt($('#questions_page').val());
            var totalPages = parseInt($('#total_pages').val());
            if (currentPage < totalPages) {
                currentPage = currentPage + 1;
            } else {
                currentPage = totalPages;
            }
            $('#questions_page').val(currentPage);
            self.loadListWithParameters();
            self.enableDisablePaginationButtons(true);
        });
    };

    this.initPreviosPage = function () {
        $('.previos-button').on('click', function (e) {
            e.preventDefault();
            var currentPage = parseInt($('#questions_page').val());
            if (currentPage > 1) {
                currentPage = currentPage - 1;
            } else {
                currentPage = 1;
            }
            $('#questions_page').val(currentPage);
            self.loadListWithParameters();
            self.enableDisablePaginationButtons(true);
        });
    };

    this.enableDisablePaginationButtons = function (type) {
        $('.next-button').attr('disabled', type);
        $('.previos-button').attr('disabled', type);
    };

    this.initSearchAndFilters = function () {
        $('.questions-search-button, .question-bank-question-filter-apply-btn').on('click', function (e) {
            e.preventDefault();
            $('#questions_page').val(1);
            self.loadListWithParameters();
        });
        $('#questions_search').on('keypress', function (e) {
            if(e.which == 13) {
                e.preventDefault();
                $('#questions_page').val(1);
                self.loadListWithParameters();
            }
        });        
    };

    this.initQuestionDelete = function () {
        $('.delete-question').off();
        $('.delete-question').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/employer/questions/delete/'+id, '', function (result) {
                    self.loadListWithParameters();
                });
            }
        });
    };

    this.initiCheck = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck('destroy');
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });

        //Storing value to closest input hidden element
        $('input.minimal').on('ifChecked', function(event){
            var type = $('#type').val();
            if (type == 'radio') {
                $(this).parent().parent().parent().parent().parent().find('.answer').val('0');
            }
            $(this).parent().parent().find('.answer').val('1');
        });
        $('input.minimal').on('ifUnchecked', function(event){
            $(this).parent().parent().find('.answer').val('0');
        });
    };

    this.initSwitchAnswersType = function () {
        $('.change-answer-type').on('click', function() {
            var type = $('#type').val();
            if (type == 'checkbox') {
                $(this).parent().parent().parent().parent().parent().find('input[type="checkbox"]').attr('type', 'radio');
                $('#type').val('radio');
                $(this).attr('title', lang['change_to_multi_correct']);
            } else {
                $(this).parent().parent().parent().parent().parent().find('input[type="radio"]').attr('type', 'checkbox');
                $('#type').val('checkbox');
                $(this).attr('title', lang['change_to_single_correct']);
            }
            $('.minimal').each(function() {
                if ($(this).is(':checked')) {
                    $(this).parent().parent().find('.answer').val('1');
                } else {
                    $(this).parent().parent().find('.answer').val('0');
                }
            })
            self.initiCheck();
        });
    };

    this.initAddAnswer = function () {
        $('.add-answer').on('click', function (e) {
            e.preventDefault();
            var type = $('#type').val();
            var id = $(this).data('id');
            application.load('/employer/questions/add-answer/'+type+'/'+id, '', function (result) {
                $('.answers-container:last-child').after(result);
                self.initiCheck();
                self.initRemoveAnswer();
            });
        });
    }

    this.initRemoveAnswer = function () {
        $('.remove-answer').on('click', function () {
            var id = $(this).data('id');
            if (id != '') {
                application.load('/employer/questions/remove-answer/'+id, '', function (result) {});
            }
            $(this).parent().parent().parent().remove();
        });
    }

    this.initQuestionsList = function () {
        self.initSetPerPage();
        self.initNextPage();
        self.initPreviosPage();
        self.initSearchAndFilters();
        self.initQuestionDelete();
        self.initQuestionCreateOrEditForm();
    };
}

$(document).ready(function() {
    var question = new Question();
    question.initQuestionsList();
});
