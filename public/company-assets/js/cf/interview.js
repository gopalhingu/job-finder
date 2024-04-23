function Interview() {

    "use strict";

    var self = this;

    this.initChangeInterview = function () {
        $('.interview-dropdown').on('change', function () {
            $('#edit-interview').data('id', $('.interview-dropdown').val());
            $('#delete-interview').data('id', $('.interview-dropdown').val());
            application.load('/employer/interview-questions/'+$(this).val(), '', function (result) {
                $('#interview-questions').html(result);
                interview_question.initInterviewQuestionEditForm();
                interview_question.initInterviewQuestionDelete();
            });
        });
    };

    this.loadInterviewList = function (id, selected) {
        application.load('/employer/interviews/dropdown/'+id, '', function (result) {
            var result = JSON.parse(application.response);
            $(".interview-dropdown").html("");
            if (result === undefined || result.length == 0) {
                var newOption = new Option('No Interview Found', '0', false, false);
                $('.interview-dropdown').prepend(newOption);
                $('.interview-dropdown').val(0);
            } else {
                $(result).each(function(i, v) {
                    var status = " ("+(v.status == 1 ? lang['active'] : lang['inactive'])+")";
                    var newOption = new Option(v.title+status, v.interview_id, false, false);
                    $('.interview-dropdown').prepend(newOption);
                });
                var id = selected != '' ? selected : result[0].interview_id;
                $('.interview-dropdown').val(id);
            }
            $('.interview-dropdown').change();
        });
    };

    this.initChangeInterviewCategory = function () {
        $('#interviews_category_id').on('change', function () {
            var id = $(this).val();
            self.loadInterviewList(id, '');
        });
        $('#interviews_category_id').change(); //For page load
    };

    this.initInterviewCreateOrEditForm = function () {
        $('.create-or-edit-interview').off();
        $('.create-or-edit-interview').on('click', function () {
            var modal = '#modal-default';
            var id = $(this).data('id');
            id = (id && id != 0) ? '/'+id : '';
            var modal_title = id ? lang['edit_interview'] : lang['create_interview'];
            $(modal).modal('show');
            $(modal+' .modal-title').html(modal_title);
            application.load('/employer/interviews/create-or-edit'+id, modal+' .modal-body-container', function (result) {
                self.initInterviewSave();
            });
        });
        $('#edit-interview').data('id', $('.interview-dropdown').val());
        $('#delete-interview').data('id', $('.interview-dropdown').val());
    };

    this.initInterviewSave = function () {
        application.onSubmit('#employer_interview_create_update_form', function (result) {
            application.showLoader('employer_interview_create_update_form_button');
            application.post('/employer/interviews/save', '#employer_interview_create_update_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.loadInterviewList($('#interviews_category_id').val(), result.data.id);
                } else {
                    application.hideLoader('employer_interview_create_update_form_button');
                    application.showMessages(result.messages, 'employer_interview_create_update_form');
                }
            });
        });
    };

    this.initInterviewCloneForm = function () {
        $('.clone-interview').off();
        $('.clone-interview').on('click', function () {
            var modal = '#modal-default';
            var id = $('.interview-dropdown').val();
            $(modal).modal('show');
            $(modal+' .modal-title').html(lang['clone_interview']);
            application.load('/employer/interviews/clone/'+id, modal+' .modal-body-container', function (result) {
                self.initInterviewClone();
            });
        });
        $('#edit-interview').data('id', $('.interview-dropdown').val());
        $('#delete-interview').data('id', $('.interview-dropdown').val());
    };

    this.initInterviewClone = function () {
        application.onSubmit('#employer_interview_clone_form', function (result) {
            application.showLoader('employer_interview_clone_form_button');
            application.post('/employer/interviews/clone', '#employer_interview_clone_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.loadInterviewList($('#interviews_category_id').val(), '');
                } else {
                    application.hideLoader('employer_interview_clone_form_button');
                    application.showMessages(result.messages, 'employer_interview_clone_form');
                }
            });
        });
    };

    this.initInterviewDelete = function () {
        $('.delete-interview').off();
        $('.delete-interview').on('click', function () {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/employer/interviews/delete/'+id, '', function (result) {
                    self.loadInterviewList($('#interviews_category_id').val(), '');
                    $('.interview-dropdown').change();
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
        //Drag from questions bank to interview
      $("#questions-bank").sortable({
          connectWith: "#interview-questions",
          start: function(event, ui) {},
          stop: function(event, ui) {},
          change: function(event, ui) {},
          remove: function (event, ui) {
            ui.item.clone().appendTo("#interview-questions");
            $(this).sortable('cancel');
          },
          update: function(event, ui) {
            var question_id = ui.item[0].dataset.id;
            var interview_id = $('.interview-dropdown').val();
            application.load('/employer/interview-questions/add/'+interview_id+'/'+question_id, '', function (result) {
                var id = application.response;
                $('.no-questions-found').remove();

                //Updating the question from question bank to interview question configuration
                var added_question = $("#interview-questions").find('.bank-item-'+question_id);
                added_question.data('id', id);
                added_question.addClass('interview-item');
                added_question.find('.fa-edit').data('id', id);
                added_question.find('.fa-trash-o').data('id', id);
                added_question.find('.handle').attr('title', 'Drag to Order');
                added_question.find('.create-or-edit-question').addClass('edit-interview-question');
                added_question.find('.edit-interview-question').removeClass('create-or-edit-question');
                added_question.find('.delete-question').addClass('delete-interview-question');
                added_question.find('.delete-interview-question').removeClass('delete-question');

                interview_question.initInterviewQuestionEditForm();
                interview_question.initInterviewQuestionDelete();
            });
          }
      }).disableSelection();

      //Sorting questions in interview for ordering
      $("#interview-questions").sortable({
        start: function(event, ui) {},
        stop: function(event, ui) {},
        change: function(event, ui) {},
        update: function () {
            var items = [];
            $.each($('.interview-item'), function (i, v) {
                items.push({order: i + 1, id: $(this).data('id')})
            });
            application.post('/employer/interview-questions/order', {items: items}, function (result) {
            }, function (error) {
            });
        }
      }).disableSelection();        
    };

    this.downloadInterview = function (ids) {
        $('.download-interview').on('click', function() {
            var id = $('.interview-dropdown').val();
            var form = "#download-interview-form";
            window.location = application.url+'/employer/interviews/download/'+id;
        });
    };

    this.initInterviewDesignerFunctions = function () {
      $('.select2').select2();
      self.initFiltersClickPrevention();
      self.initListsSortingAndDragging();
      self.initInterviewCloneForm();
      self.initInterviewCreateOrEditForm();
      self.initChangeInterview();
      self.initInterviewDelete();
      self.initChangeInterviewCategory();
      self.downloadInterview();
    };

}

$(document).ready(function() {
    var interview = new Interview();
    interview.initInterviewDesignerFunctions();
});
