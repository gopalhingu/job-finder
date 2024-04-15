function InterviewQuestion() {

    "use strict";

    var self = this;

    this.initInterviewQuestionEditForm = function () {
        $('.edit-interview-question').off();
        $('.edit-interview-question').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = id ? '/'+id : '';
            var modal_title = id ? lang['edit_interview_question'] : lang['create_interview_question'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/employer/interview-questions/edit'+id, modal+' .modal-body-container', function (result) {
                self.initInterviewQuestionSave();
            });
        });
    };

    this.initInterviewQuestionSave = function () {
        application.onSubmit('#employer_interview_question_create_update_form', function (result) {
            application.showLoader('employer_interview_question_create_update_form_button');
            application.post('/employer/interview-questions/save', '#employer_interview_question_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    $('.interview-dropdown').change();
                } else {
                    application.hideLoader('employer_interview_question_create_update_form_button');
                    application.showMessages(result.messages, 'employer_interview_question_create_update_form');
                }
            });
        });
    };

    this.initInterviewQuestionDelete = function () {
        $('.delete-interview-question').off();
        $('.delete-interview-question').on('click', function () {
            var item = $(this);
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/employer/interview-questions/delete/'+id, '', function (result) {
                    item.parent().parent().remove();
                });
            }
        });
    };
}

var interview_question = new InterviewQuestion();
