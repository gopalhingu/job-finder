function Quiz() {

    "use strict";

    var self = this;

    this.initChangeQuiz = function () {
        $('.quiz-dropdown').on('change', function () {
            $('#edit-quiz').data('id', $('.quiz-dropdown').val());
            $('#delete-quiz').data('id', $('.quiz-dropdown').val());
            application.load('/employer/quiz-questions/'+$(this).val(), '', function (result) {
                $('#quiz-questions').html(result);
                quiz_question.initQuizQuestionEditForm();
                quiz_question.initQuizQuestionDelete();
            });
        });
    };

    this.loadQuizList = function (id, selected) {
        application.load('/employer/quizes/dropdown/'+id, '', function (result) {
            var result = JSON.parse(application.response);
            $(".quiz-dropdown").html("");
            if (result === undefined || result.length == 0) {
                var newOption = new Option('No Quiz Found', '0', false, false);
                $('.quiz-dropdown').prepend(newOption);
                $('.quiz-dropdown').val(0);
            } else {
                $(result).each(function(i, v) {
                    var status = " ("+(v.status == 1 ? lang['active'] : lang['inactive'])+")";
                    var newOption = new Option(v.title+status, v.quiz_id, false, false);
                    $('.quiz-dropdown').prepend(newOption);
                });
                var id = selected != '' ? selected : result[0].quiz_id;
                $('.quiz-dropdown').select2('data', {id: id});
            }
            $('.quiz-dropdown').change();
        });
    };

    this.initChangeQuizCategory = function () {
        $('#quizes_category_id').on('change', function () {
            var id = $(this).val();
            self.loadQuizList(id, '');
        });
        $('#quizes_category_id').change(); //For page load
    };

    this.initQuizCreateOrEditForm = function () {
        $('.create-or-edit-quiz').off();
        $('.create-or-edit-quiz').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = (id && id != 0) ? '/'+id : '';
            var modal_title = id ? lang['edit_quiz'] : lang['create_quiz'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/employer/quizes/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initQuizSave();
            });
        });
        $('#edit-quiz').data('id', $('.quiz-dropdown').val());
        $('#delete-quiz').data('id', $('.quiz-dropdown').val());
    };

    this.initQuizSave = function () {
        application.onSubmit('#employer_quiz_create_update_form', function (result) {
            application.showLoader('employer_quiz_create_update_form_button');
            application.post('/employer/quizes/save', '#employer_quiz_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.loadQuizList($('#quizes_category_id').val(), result.data.id);
                } else {
                    application.hideLoader('employer_quiz_create_update_form_button');
                    application.showMessages(result.messages, 'employer_quiz_create_update_form');
                }
            });
        });
    };

    this.initQuizCloneForm = function () {
        $('.clone-quiz').off();
        $('.clone-quiz').on('click', function () {
            var modal = '#modal-default';
            var id = $('.quiz-dropdown').val();
            $(modal).modal('show');
            $(modal+' .modal-title').html(lang['clone_quiz']);
            application.load('/employer/quizes/clone/'+id, modal+' .modal-body-container', function (result) {
                self.initQuizClone();
            });
        });
        $('#edit-quiz').data('id', $('.quiz-dropdown').val());
        $('#delete-quiz').data('id', $('.quiz-dropdown').val());
    };

    this.initQuizClone = function () {
        application.onSubmit('#employer_quiz_clone_form', function (result) {
            application.showLoader('employer_quiz_clone_form_button');
            application.post('/employer/quizes/clone', '#employer_quiz_clone_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.loadQuizList($('#quizes_category_id').val(), '');
                } else {
                    application.hideLoader('employer_quiz_clone_form_button');
                    application.showMessages(result.messages, 'employer_quiz_clone_form');
                }
            });
        });
    };

    this.initQuizDelete = function () {
        $('.delete-quiz').off();
        $('.delete-quiz').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/employer/quizes/delete/'+id, '', function (result) {
                    self.loadQuizList($('#quizes_category_id').val(), '');
                    $('.quiz-dropdown').change();
                });
            }
        });
    };

    this.initFiltersClickPrevention = function () {
        $('.question-bank-question-filter .dropdown-menu').on('click', function (event) {
          event.stopPropagation();
        });
        $('.question-bank-question-filter-apply-btn').on('click', function(e) {
          e.preventDefault();
          $('.question-bank-question-filter').removeClass('open');
        });
    };

    this.initListsSortingAndDragging = function () {
        //Drag from questions bank to quiz
      $("#questions-bank").sortable({
          connectWith: "#quiz-questions",
          start: function(event, ui) {},
          stop: function(event, ui) {},
          change: function(event, ui) {},
          remove: function (event, ui) {
            ui.item.clone().appendTo("#quiz-questions");
            $(this).sortable('cancel');
          },
          update: function(event, ui) {
            var question_id = ui.item[0].dataset.id;
            var quiz_id = $('.quiz-dropdown').val();
            application.load('/employer/quiz-questions/add/'+question_id+'/'+quiz_id, '', function (result) {
                var id = application.response;
                $('.no-questions-found').remove();

                //Updating the question from question bank to quiz question configuration
                var added_question = $("#quiz-questions").find('.bank-item-'+question_id);
                added_question.data('id', id);
                added_question.addClass('quiz-item');
                added_question.find('.fa-edit').data('id', id);
                added_question.find('.fa-trash-o').data('id', id);
                added_question.find('.handle').attr('title', 'Drag to Order');
                added_question.find('.create-or-edit-question').addClass('edit-quiz-question');
                added_question.find('.edit-quiz-question').removeClass('create-or-edit-question');
                added_question.find('.delete-question').addClass('delete-quiz-question');
                added_question.find('.delete-quiz-question').removeClass('delete-question');

                quiz_question.initQuizQuestionEditForm();
                quiz_question.initQuizQuestionDelete();
            });
          }
      }).disableSelection();

      //Sorting questions in quiz for ordering
      $("#quiz-questions").sortable({
        start: function(event, ui) {},
        stop: function(event, ui) {},
        change: function(event, ui) {},
        update: function () {
            var items = [];
            $.each($('.quiz-item'), function (i, v) {
                items.push({order: i + 1, id: $(this).data('id')})
            });
            application.post('/employer/quiz-questions/order', {items: items}, function (result) {
            }, function (error) {
            });
        }
      }).disableSelection();        
    };

    this.downloadQuiz = function (ids) {
        $('.download-quiz').on('click', function() {
            var id = $('.quiz-dropdown').val();
            var form = "#download-quiz-form";
            window.location = application.url+'/employer/quizes/download/'+id;
        });
    };

    this.initQuizDesignerFunctions = function () {
      $('.select2').select2();
      self.initFiltersClickPrevention();
      self.initListsSortingAndDragging();
      self.initQuizCloneForm();
      self.initQuizCreateOrEditForm();
      self.initChangeQuiz();
      self.initQuizDelete();
      self.initChangeQuizCategory();
      self.downloadQuiz();
    };

}

$(document).ready(function() {
    var quiz = new Quiz();
    quiz.initQuizDesignerFunctions();
});
