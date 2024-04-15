function JobBoard() {

    "use strict";
    
    var self = this;

    this.initClickPreventions = function () {
        $('.job-board-job-filter .dropdown-menu').on('click', function (e) {
          e.stopPropagation();
        });
        $('.job-board-job-filter-apply-btn').on('click', function(e) {
          e.preventDefault();
          $('.job-board-job-filter').removeClass('open');
        });
        $('.job-board-candidate-filter .dropdown-menu').on('click', function (e) {
          e.stopPropagation();
        });
        $('.job-board-candidate-filter-apply-btn').on('click', function(e) {
          e.preventDefault();
          $('.job-board-candidate-filter').removeClass('open');
        });
    };

    this.initiChecks = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
        $('input.job-board-candidate-select').on('ifChanged', function(e){
            var existing = $(this).parent().parent().parent().parent().parent().hasClass('job-board-candidate-wrap-active');
            if (existing) {
              $(this).parent().parent().parent().parent().parent().removeClass('job-board-candidate-wrap-active');
            } else {
              $(this).parent().parent().parent().parent().parent().addClass('job-board-candidate-wrap-active');
            }
        });
        $('.select-all').on('click', function(e){
            e.preventDefault();
            $('input').iCheck('check');
            $('.job-board-candidate-wrap').addClass('job-board-candidate-wrap-active');
        });
        $('.unselect-all').on('click', function(e){
            e.preventDefault();
            $('input').iCheck('uncheck');
            $('.job-board-candidate-wrap').removeClass('job-board-candidate-wrap-active');
        });
    };

    this.loadJobsList = function (ids) {
        $('#jobs_list').html('<div class="loader-div"><i class="fa fa-spin fa-spinner"></i></div>')
        var form = "#jobs_form";
        $(form).find('input').remove();
        var jss = $('#jobs_search').val();
        var ci = $('#company_id').val();
        var di = $('#department_id').val();
        var js = $('#jobs_status').val();
        var jp = $('#jobs_page').val();
        var jpp = $('#jobs_per_page').val();
        $("<input />").attr("type", "hidden").attr("name", 'jobs_search').attr("value", jss).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'jobs_company_id').attr("value", ci).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'jobs_department_id').attr("value", di).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'jobs_status').attr("value", js).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'jobs_page').attr("value", jp).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'jobs_per_page').attr("value", jpp).appendTo(form);
        application.post('/employer/job-board/jobs-list', form, function (res) {
            var result = JSON.parse(application.response);
            $('#jobs_list').html(result.list);
            $('#jobs_total_pages').val(result.total_pages);
            $('#jobs_pagination_container').html(result.pagination);
            self.enableDisablePaginationButtons(false);
            self.initJobSelect();
            self.initViewJobDetail();
        });        
    };

    this.initJobsSetPerPage = function () {
        $('.jobs-per-page').on('click', function (e) {
            e.preventDefault();
            $('#jobs_per_page').val(parseInt($(this).data('value')));
            $('#jobs_page').val(1);
            self.loadJobsList();
            self.enableDisablePaginationButtons(true);
        });
    };

    this.initJobsNextPage = function () {
        $('.jobs-next-button').on('click', function (e) {
            e.preventDefault();
            var currentPage = parseInt($('#jobs_page').val());
            var totalPages = parseInt($('#jobs_total_pages').val());
            if (currentPage < totalPages) {
                currentPage = currentPage + 1;
            } else {
                currentPage = totalPages;
            }
            $('#jobs_page').val(currentPage);
            self.loadJobsList();
            self.enableDisablePaginationButtons(true);
        });
    };

    this.initJobsPreviosPage = function () {
        $('.jobs-previos-button').on('click', function (e) {
            e.preventDefault();
            var currentPage = parseInt($('#jobs_page').val());
            if (currentPage > 1) {
                currentPage = currentPage - 1;
            } else {
                currentPage = 1;
            }
            $('#jobs_page').val(currentPage);
            self.loadJobsList();
            self.enableDisablePaginationButtons(true);
        });
    };

    this.enableDisablePaginationButtons = function (type) {
        $('.jobs-next-button').attr('disabled', type);
        $('.jobs-previos-button').attr('disabled', type);
    };

    this.initJobsSearchAndFilters = function () {
        $('.jobs-search-button, .job-board-job-filter-apply-btn').on('click', function (e) {
            e.preventDefault();
            $('#jobs_page').val(1);
            self.loadJobsList();
        });
        $('#jobs_search').on('keypress', function (e) {
            if(e.which == 13) {
                e.preventDefault();
                $('#jobs_page').val(1);
                self.loadJobsList();
            }
        });
    };

    this.initJobSelect = function () {
        $('.job-board-job-title').on('click', function() {
            $('.job-board-job-title').parent().removeClass('job-board-job-wrap-active');
            $(this).parent().addClass('job-board-job-wrap-active');
            $('#job_id').val($(this).data('id'));
            $('.job_title').html($(this).data('title'));
            $('#candidates_page').val(1);
            self.loadCandidatesList();
        });
    };    

    this.loadCandidatesList = function (ids) {
        $('#candidates_list').html('<div class="loader-div"><i class="fa fa-spin fa-spinner"></i></div>')
        var form = "#candidates_form";
        $(form).find('input').remove();
        var sort = $('#candidates_sort').val();
        var jid = $('#job_id').val();
        var css = $('#candidates_search').val();
        var cp = $('#candidates_page').val();
        var cpp = $('#candidates_per_page').val();
        var minexp = $('#candidates_min_experience').val();
        var maxexp = $('#candidates_max_experience').val();
        var minove = $('#candidates_min_overall').val();
        var maxove = $('#candidates_max_overall').val();
        var minint = $('#candidates_min_interview').val();
        var maxint = $('#candidates_max_interview').val();
        var minqui = $('#candidates_min_quiz').val();
        var maxqui = $('#candidates_max_quiz').val();
        var minsel = $('#candidates_min_self').val();
        var maxsel = $('#candidates_max_self').val();
        var status = $('#candidates_status').val();
        $("<input />").attr("type", "hidden").attr("name", 'candidates_sort').attr("value", sort).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_search').attr("value", css).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_page').attr("value", cp).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_per_page').attr("value", cpp).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_min_experience').attr("value", minexp).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_max_experience').attr("value", maxexp).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_min_overall').attr("value", minove).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_max_overall').attr("value", maxove).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_min_interview').attr("value", minint).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_max_interview').attr("value", maxint).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_min_quiz').attr("value", minqui).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_max_quiz').attr("value", maxqui).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_min_self').attr("value", minsel).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_max_self').attr("value", maxsel).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", 'candidates_status').attr("value", status).appendTo(form);
        application.post('/employer/job-board/candidates-list/'+jid, form, function (res) {
            var result = JSON.parse(application.response);
            $('#candidates_list').html(result.list);
            $('#candidates_total_pages').val(result.total_pages);
            $('#candidates_pagination_container').html(result.pagination);
            $('.candidates_all').html(result.candidates_all+' '+lang['candidates']);
            $('.job-board-candidate-item-list').slimScroll({height:"96px"});
            self.enableDisablePaginationButtons(false);
            self.initiChecks();
            self.initCandidatesListBulkActions();
            self.initDeleteCandidateInterview();
            self.initDeleteCandidateQuiz();
            self.initViewResume();
        });        
    };

    this.initCandidatesSetPerPage = function () {
        $('.candidates-per-page').on('click', function (e) {
            e.preventDefault();
            $('#candidates_per_page').val(parseInt($(this).data('value')));
            $('#candidates_page').val(1);
            self.loadCandidatesList();
            self.enableDisablePaginationButtons(true);
        });
    };

    this.initCandidatesNextPage = function () {
        $('.candidates-next-button').on('click', function (e) {
            e.preventDefault();
            var currentPage = parseInt($('#candidates_page').val());
            var totalPages = parseInt($('#candidates_total_pages').val());
            if (currentPage < totalPages) {
                currentPage = currentPage + 1;
            } else {
                currentPage = totalPages;
            }
            $('#candidates_page').val(currentPage);
            self.loadCandidatesList();
            self.enableDisablePaginationButtons(true);
        });
    };

    this.initCandidatesPreviosPage = function () {
        $('.candidates-previos-button').on('click', function (e) {
            e.preventDefault();
            var currentPage = parseInt($('#candidates_page').val());
            if (currentPage > 1) {
                currentPage = currentPage - 1;
            } else {
                currentPage = 1;
            }
            $('#candidates_page').val(currentPage);
            self.loadCandidatesList();
            self.enableDisablePaginationButtons(true);
        });
    };

    this.initDeleteCandidateInterview = function () {
        $('.delete-candidate-interview').on('click', function (e) {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/employer/job-board/delete-interview/'+id, '', function (result) {
                    self.loadCandidatesList();
                });
            }
        });
    };

    this.initDeleteCandidateQuiz = function () {
        $('.delete-candidate-quiz').on('click', function (e) {
            var status = confirm(lang['are_u_sure']);
            var id = $(this).data('id');
            if (status === true) {
                application.load('/employer/job-board/delete-quiz/'+id, '', function (result) {
                    self.loadCandidatesList();
                });
            }
        });
    };

    this.enableDisablePaginationButtons = function (type) {
        $('.candidates-next-button').attr('disabled', type);
        $('.candidates-previos-button').attr('disabled', type);
    };

    this.initCandidatesSearchAndFilters = function () {
        $('.candidates-search-button, .job-board-candidate-filter-apply-btn').on('click', function (e) {
            e.preventDefault();
            $('#candidates_page').val(1);
            self.loadCandidatesList();
        });
        $('#candidates_search').on('keypress', function (e) {
            if(e.which == 13) {
                e.preventDefault();
                $('#candidates_page').val(1);
                self.loadCandidatesList();
            }
        });        
    };

    this.initCandidatesListBulkActions = function () {
        $('.bulk-action').off();
        $('.bulk-action').on('click', function (e) {
            e.preventDefault();
            var ids = [];
            var resumes = [];
            var action = $(this).data('action');
            var job = $('#job_id').val();
            $('.job-board-candidate-wrap-active input').each(function (i, v) {
                if ($(this).is(':checked')) {
                    ids.push($(this).data('id'))
                    resumes.push($(this).data('resume_id'))
                }
            });
            if (ids.length === 0) {
                alert(lang['please_select_some_records_first']);
                $('.bulk-action').val('');
                return false;
            }
            if (action == 'assign-quiz') {
                application.load('/employer/job-board/assign-view/quiz/'+job, '#modal-default .modal-body-container', function (result) {
                    self.initBulkAssign();
                    $('#candidates').val(JSON.stringify(ids));
                    $('.select2').select2();
                    $('#modal-default .modal-title').html('Assign Quiz');
                    $('#modal-default').modal('show');
                    self.initiChecks();
                });
            }
            if (action == 'assign-interview') {
                application.load('/employer/job-board/assign-view/interview/'+job, '#modal-default .modal-body-container', function (result) {
                    self.initBulkAssign();
                    $('#candidates').val(JSON.stringify(ids));
                    $('.select2').select2();
                    $('#modal-default .modal-title').html('Assign Interview');
                    $('#modal-default').modal('show');
                    self.initiChecks();
                    $('.datetimepicker').datetimepicker({
                        dateFormat: 'yy-mm-dd'
                    });
                });
            }
            if (action == 'shortlisted' || action == 'interviewed' || action == 'hired' || action == 'rejected') {
                application.post('/employer/job-board/candidate-status', {ids:ids, action: action, job:job}, function (result) {
                    self.loadCandidatesList();
                });                
            }
            if (action == 'delete-app') {
                var status = confirm(lang['are_u_sure']);
                if (status === true) {
                    application.post('/employer/job-board/delete-app', {ids:ids, job:job}, function (result) {
                        self.loadCandidatesList();
                    });
                }
            }
            if (action == 'e-resume') {
                if (resumes.length > 5) {
                    alert(lang['only_5_candidates_allowed']);
                    return false;
                }
                self.downloadResume(resumes);
            }
            if (action == 'e-overall') {
                self.downloadOverall(ids, job);
            }
            if (action == 'e-interview' || action == 'e-quiz' || action == 'e-self') {
                if (resumes.length > 1 && action == 'e-quiz') {
                    alert(lang['only_1_candidate_allowed']);
                    return false;
                } else if (resumes.length > 5 && action != 'e-quiz') {
                    alert(lang['only_5_candidates_allowed']);
                    return false;
                }
                self.downloadPdf(ids, job, action);
            }
        });
    };

    this.initCandidatesListSort = function () {
        $('.sort').off();
        $('.sort').on('click', function (e) {
            e.preventDefault();
            $('#candidates_sort').val($(this).data('action'));
            self.loadCandidatesList();
        });
    };

    this.initBulkAssign = function () {
        application.onSubmit('#employer_job_board_assign_form', function (result) {
            application.showLoader('employer_job_board_assign_form_button');
            application.post('/employer/job-board/assign', '#employer_job_board_assign_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-default').modal('hide');
                    self.loadCandidatesList();
                } else {
                    application.hideLoader('employer_job_board_assign_form_button');
                    application.showMessages(result.messages, 'employer_job_board_assign_form');
                }
            });
        });
    };

    this.initViewJobDetail = function () {
        $('.view-job-detail').on('click', function () {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var modal = '#modal-right';
            $(modal+' .modal-title').html(title);
            $(modal).modal('show');
            application.load('/employer/job-board/job/'+id, modal+' .modal-body-container', function (result) {
            });
        });
    };

    this.initViewResume = function () {
        $('.view-resume').on('click', function () {
            var id = $(this).data('id');
            var title = $(this).attr('title');
            var modal = '#modal-right';
            $(modal+' .modal-title').html(title);
            $(modal).modal('show');
            application.load('/employer/job-board/resume/'+id, modal+' .modal-body-container', function (result) {
            });
        });
    };

    this.downloadResume = function (ids) {
        var form = "#resumes_form";
        $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", "_token").attr("value", application._token).appendTo(form);
        $(form).submit();
    };

    this.downloadOverall = function (ids, job) {
        var form = "#overall_form";
        $("<input />").attr("type", "hidden").attr("name", "job").attr("value", job).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", "_token").attr("value", application._token).appendTo(form);
        $(form).submit();
    };

    this.downloadPdf = function (ids, job, type) {
        var form = "#pdf_form";
        $("<input />").attr("type", "hidden").attr("name", "job").attr("value", job).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", "ids").attr("value", ids).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", "type").attr("value", type).appendTo(form);
        $("<input />").attr("type", "hidden").attr("name", "_token").attr("value", application._token).appendTo(form);
        $(form).submit();
    };

    this.initViewToggle = function () {
        $('#view-toggle').bootstrapToggle({
          on: 'DETAILED',
          off: 'COMPACT'
        });

        $('#view-toggle').change(function() {
          //alert($(this).prop('checked'));
        })
    };
    
    this.initJobBoardFunctions = function () {
      $('.select2').select2();
      self.initClickPreventions();
      self.initiChecks();

      //Jobs / Left functions
      self.initJobsSetPerPage();
      self.initJobsNextPage();
      self.initJobsPreviosPage();
      self.initJobsSearchAndFilters();
      self.initJobSelect();
      self.initViewJobDetail();
      $('.job-board-left').slimScroll({height:"424px"});
      //hack to remove default values and then add altered style values
      $('.job-board-left').parent().attr('style', 'position: relative; width: auto; height: 424px;');

      //Candidates / Left functions
      self.initCandidatesSetPerPage();
      self.initCandidatesNextPage();
      self.initCandidatesPreviosPage();
      self.initCandidatesSearchAndFilters();
      self.initCandidatesListSort();
      self.initViewResume();

      $('.job-'+$('#job_id').val()).click();
    };

}

$(document).ready(function() {
    var job_board = new JobBoard();
    job_board.initJobBoardFunctions();
});
