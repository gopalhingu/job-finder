function QuizQuestion() {

    "use strict";
    
    var self = this;

    this.initQuizQuestionEditForm = function () {
        $('.edit-quiz-question').off();
        $('.edit-quiz-question').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_quiz_question'] : lang['create_quiz_question'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/employer/quiz-questions/edit'+id, modal+' .modal-body-container', function (result) {
                self.initQuizQuestionSave();
                self.initiCheck();
                self.initSwitchAnswersType();
                self.initAddAnswer();
                self.initRemoveAnswer();
                var dropifyEvent = $('.dropify').dropify();
                dropifyEvent.on('dropify.afterClear', function(event, element){
                    var id = element.input[0].dataset.id;
                    application.load('/employer/quiz-questions/remove-image/'+id, '', function (result) {});
                });
            });
        });
    };

    this.initQuizQuestionSave = function () {
        application.onSubmit('#employer_quiz_question_create_update_form', function (result) {
            application.showLoader('employer_quiz_question_create_update_form_button');
            application.post('/employer/quiz-questions/save', '#employer_quiz_question_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    $('.quiz-dropdown').change();
                } else {
                    application.hideLoader('employer_quiz_question_create_update_form_button');
                    application.showMessages(result.messages, 'employer_quiz_question_create_update_form');
                }
            });
        });
    };

    this.initQuizQuestionDelete = function () {
        $('.delete-quiz-question').off();
        $('.delete-quiz-question').on('click', function () {
            var item = $(this);
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/employer/quiz-questions/delete/'+id, '', function (result) {
                    item.parent().parent().remove();
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
            application.load('/employer/quiz-questions/add-answer/'+id+'/'+type, '', function (result) {
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
                application.load('/employer/quiz-questions/remove-answer/'+id, '', function (result) {});
            }
            $(this).parent().parent().parent().remove();
        });
    }
}

var quiz_question = new QuizQuestion();
