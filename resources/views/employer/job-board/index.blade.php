@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fas fa-newspaper"></i> {{ __('message.job_board') }}</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fas fa-newspaper"></i> {{ __('message.job_board') }}</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row job-board-main-container">
            <!-- Left col -->
            <section class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-body job-board-box-body">
                        @if(empAllowedTo('view_job_board'))
                        <!-- Job Board Inner/Main Container Starts -->
                        <div class="container job-board-inner-container">
                            <div class="row">
                                <!-- Job Board Left Container Starts -->
                                <div class="col-md-3 job-board-left-container">
                                    <div class="job-board-left-top">
                                        <div class="col-xs-12 col-sm-12 col-md-12 job-board-left-top-heading">
                                            <h3>{{ __('message.jobs') }}  <Br /><span class="small">{{ __('message.select_job_to_view_applications') }}</span></h3>
                                            <div class="job-board-jobs-pagination">
                                                <div class="btn-group pull-right">
                                                    <button type="button" class="btn btn-xs btn-primary btn-blue jobs-previos-button"><</button>
                                                    <button type="button" class="btn btn-xs btn-primary btn-blue disabled" id="jobs_pagination_container">
                                                    {{ $jobs_pagination }}
                                                    </button>
                                                    <button type="button" class="btn btn-xs btn-primary btn-blue jobs-next-button">></button>
                                                </div>
                                                <div class="btn-group pull-right job-board-jobs-perpage-btn">
                                                    <button type="button" class="btn btn-xs btn-primary btn-blue dropdown-toggle" 
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="#" class="jobs-per-page" data-value="10">10 {{ __('message.per_page') }}</a></li>
                                                        <li><a href="#" class="jobs-per-page" data-value="25">25 {{ __('message.per_page') }}</a></li>
                                                        <li><a href="#" class="jobs-per-page" data-value="50">50 {{ __('message.per_page') }}</a></li>
                                                        <li><a href="#" class="jobs-per-page" data-value="200">200 {{ __('message.per_page') }}</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 job-board-left-jobs-container">
                                            <div class="input-group job-board-job-search">
                                                <input type="hidden" id="jobs_page" value="{{ $jobs_page }}">
                                                <input type="hidden" id="jobs_per_page" value="{{ $jobs_per_page }}">
                                                <input type="hidden" id="jobs_total_pages" value="{{ $jobs_total_pages }}">
                                                <input type="text" class="form-control" placeholder="Search Jobs" 
                                                    id="jobs_search" value="{{ $jobs_search }}">
                                                <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary btn-blue btn-flat jobs-search-button">
                                                <i class="fa fa-search"></i>
                                                </button>
                                                </span>
                                            </div>
                                            <div class="btn-group btn-sm pull-right job-board-job-filter" title="More Filters">
                                                <button type="button" class="btn btn-primary btn-blue dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-filter"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <h4 class="job-board-filters-heading">{{ __('message.filters') }}</h4>
                                                        <form role="form">
                                                            <div class="box-body">
                                                                <div class="form-group mt5">
                                                                    <label>{{ __('message.department') }}</label>
                                                                    <select class="form-control" id="department_id">
                                                                        <option value="">{{ __('message.all') }}</option>
                                                                        @if ($departments)
                                                                        @foreach ($departments  as $department)
                                                                        <option value="{{ encode($department['department_id']) }}">{{ $department['title'] }}</option>
                                                                        @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                                <div class="form-group mt5">
                                                                    <label>{{ __('message.status') }}</label>
                                                                    <select class="form-control" id="jobs_status">
                                                                        <option value="">{{ __('message.all') }}</option>
                                                                        <option value="1">{{ __('message.active') }}</option>
                                                                        <option value="zero">{{ __('message.inactive') }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="box-footer">
                                                                <button type="submit" class="btn btn-primary btn-blue btn-xs job-board-job-filter-apply-btn">
                                                                {{ __('message.apply') }}
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="job-board-left">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12" id="jobs_list">
                                                {!! $jobs !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Job Board Left Container Ends -->
                                <!-- Job Board Right Container Starts -->
                                <div class="col-md-9 job-board-right-container">
                                    <div class="job-board-right-controls">
                                        <div class="container job-board-right-controls-inner">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h3><span class="job_title"></span> <Br />
                                                        <span class="small candidates_all"> {{ __('message.candidates_applied') }}</span>
                                                    </h3>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="job-board-candidate-pagination">
                                                        <div class="btn-group pull-right">
                                                            <button type="button" class="btn btn-xs btn-primary btn-blue candidates-previos-button"><</button>
                                                            <button type="button" class="btn btn-xs btn-primary btn-blue disabled" 
                                                                id="candidates_pagination_container">
                                                            1-1 of 1
                                                            </button>
                                                            <button type="button" class="btn btn-xs btn-primary btn-blue candidates-next-button">></button>
                                                        </div>
                                                        <div class="btn-group pull-right job-board-candidate-perpage-btn">
                                                            <button type="button" class="btn btn-xs btn-primary btn-blue dropdown-toggle" 
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="#" class="candidates-per-page" data-value="10">10 {{ __('message.per_page') }}</a></li>
                                                                <li><a href="#" class="candidates-per-page" data-value="25">25 {{ __('message.per_page') }}</a></li>
                                                                <li><a href="#" class="candidates-per-page" data-value="50">50 {{ __('message.per_page') }}</a></li>
                                                                <li><a href="#" class="candidates-per-page" data-value="200">200 {{ __('message.per_page') }}</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @if(empAllowedTo('actions_job_board'))
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-blue dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="#" class="select-all">{{ __('message.select_all') }}</a></li>
                                                            <li><a href="#" class="unselect-all">{{ __('message.unselect_all') }}</a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-blue btn-flat">{{ __('message.actions') }}</button>
                                                        <button type="button" class="btn btn-primary btn-blue btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li><a href="#" class="bulk-action" data-action="assign-quiz">{{ __('message.assign_quiz') }}</a></li>
                                                            <li><a href="#" class="bulk-action" data-action="assign-interview">{{ __('message.assign_interview') }}</a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="#" class="bulk-action" data-action="shortlisted">{{ __('message.mark_shortlisted') }}</a></li>
                                                            <li><a href="#" class="bulk-action" data-action="interviewed">{{ __('message.mark_interviewed') }}</a></li>
                                                            <li><a href="#" class="bulk-action" data-action="hired">{{ __('message.mark_hired') }}</a></li>
                                                            <li><a href="#" class="bulk-action" data-action="rejected">{{ __('message.mark_rejected') }}</a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="#" class="bulk-action" data-action="e-overall">{{ __('message.export_overall_result_excel') }}</a></li>
                                                            <li><a href="#" class="bulk-action" data-action="e-interview">{{ __('message.export_interview_result_pdf') }}</a></li>
                                                            <li><a href="#" class="bulk-action" data-action="e-quiz">{{ __('message.export_quiz_result_pdf') }}</a></li>
                                                            <li><a href="#" class="bulk-action" data-action="e-self">{{ __('message.export_self_assesment_result_pdf') }}</a></li>
                                                            <li><a href="#" class="bulk-action" data-action="e-resume">{{ __('message.export_resume_pdf') }}</a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="#" class="bulk-action" data-action="delete-app">{{ __('message.delete') }}</a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-blue btn-flat">{{ __('message.sort_by') }}</button>
                                                        <button type="button" class="btn btn-primary btn-blue btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li><a href="#" class="sort" data-action="overall">{{ __('message.highest_result') }}</a></li>
                                                            <li><a href="#" class="sort" data-action="interview">{{ __('message.highest_interview_result') }}</a></li>
                                                            <li><a href="#" class="sort" data-action="quiz">{{ __('message.highest_quiz_result') }}</a></li>
                                                            <li><a href="#" class="sort" data-action="self">{{ __('message.highest_self_assesment_result') }}</a></li>
                                                            <li><a href="#" class="sort" data-action="applied">{{ __('message.date_applied') }}</a></li>
                                                            <li><a href="#" class="sort" data-action="experience">{{ __('message.most_experienced') }}</a></li>
                                                        </ul>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group job-board-candidate-search">
                                                        <input type="hidden" id="candidates_page" value="{{ $candidates_page }}">
                                                        <input type="hidden" id="candidates_per_page" value="{{ $candidates_per_page }}">
                                                        <input type="hidden" id="candidates_total_pages" value="">
                                                        <input type="hidden" id="candidates_sort" value="{{ $candidates_sort }}">
                                                        <input type="hidden" id="job_id" value="{{ encode($first_job_id) }}">
                                                        <input type="text" class="form-control" placeholder="Search Candidates" id="candidates_search">
                                                        <span class="input-group-btn">
                                                        <button type="button" class="btn btn-primary btn-blue btn-flat candidates-search-button">
                                                        <i class="fa fa-search"></i>
                                                        </button>
                                                        </span>
                                                    </div>
                                                    <div class="btn-group btn-sm job-board-candidate-filter" title="More Filters">
                                                        <button type="button" class="btn btn-primary btn-blue dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-filter"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <h4 class="job-board-filters-heading">
                                                                    {{ __('message.filters_min_and_or_max') }}
                                                                </h4>
                                                                <form role="form">
                                                                    <div class="box-body">
                                                                        <div class="form-group">
                                                                            <label>{{ __('message.experience_months') }}</label>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <input type="number" class="form-control" id="candidates_min_experience" placeholder="6" value="{{ $candidates_min_experience }}">
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <input type="number" class="form-control" id="candidates_max_experience" placeholder="24" value="{{ $candidates_max_experience }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>{{ __('message.overall_result') }} (%)</label>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <input type="number" class="form-control" id="candidates_min_overall" placeholder="50" value="{{ $candidates_min_overall }}">
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <input type="number" class="form-control" id="candidates_max_overall" placeholder="100" value="{{ $candidates_max_overall }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>{{ __('message.interview_result') }} (%)</label>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <input type="number" class="form-control" id="candidates_min_interview" placeholder="50" value="{{ $candidates_min_interview }}">
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <input type="number" class="form-control" id="candidates_max_interview" placeholder="100" value="{{ $candidates_max_interview }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>{{ __('message.quizes_result') }} (%)</label>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <input type="number" class="form-control" id="candidates_min_quiz" 
                                                                                        placeholder="50" value="{{ $candidates_min_quiz }}">
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <input type="number" class="form-control" id="candidates_max_quiz" 
                                                                                        placeholder="100" value="{{ $candidates_max_quiz }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>{{ __('message.self_assesment') }} (%)</label>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <input type="number" class="form-control" id="candidates_min_self" 
                                                                                        placeholder="50" value="{{ $candidates_min_self }}">
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <input type="number" class="form-control" id="candidates_max_self" 
                                                                                        placeholder="100" value="{{ $candidates_max_self }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>{{ __('message.status') }}</label>
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <select class="form-control" id="candidates_status">
                                                                                        <option value="">{{ __('message.all') }}</option>
                                                                                        <option value="applied">{{ __('message.applied') }}</option>
                                                                                        <option value="shortlisted">{{ __('message.shortlisted') }}</option>
                                                                                        <option value="interviewed">{{ __('message.interviewed') }}</option>
                                                                                        <option value="hired">{{ __('message.hired') }}</option>
                                                                                        <option value="rejected">{{ __('message.rejected') }}</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="box-footer">
                                                                        <button type="submit" class="btn btn-primary btn-blue btn-xs job-board-candidate-filter-apply-btn">{{ __('message.apply') }}
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="job-board-right" id="candidates_list">
                                    </div>
                                </div>
                                <!-- Job Board Right Container Ends -->
                            </div>
                        </div>
                        <!-- Job Board Inner/Main Container Ends -->
                        @endif
                    </div>
                </div>
            </section>
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- Right Modal -->
<div class="modal right fade" id="modal-right" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Right Sidebar</h4>
            </div>
            <div class="modal-body-container">
            </div>
        </div>
        <!-- modal-content -->
    </div>
    <!-- modal-dialog -->
</div>
<!-- modal -->
<!-- Forms for jobs section / left side -->
<form id="jobs_form"></form>
<form id="candidates_form"></form>
<form id="resumes_form" method="POST" action="{{ url('/') }}/employer/candidates/resume-download" target="_blank"></form>
<form id="overall_form" method="POST" action="{{ url('/') }}/employer/job-board/overall-result" target="_blank"></form>
<form id="pdf_form" method="POST" action="{{ url('/') }}/employer/job-board/pdf-result" target="_blank"></form>
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/job_board.js"></script>
@endsection