@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-tachometer-alt"></i> {{ __('message.dashboard') }}
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(empAllowedTo('view_dashboard_stats'))
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ $jobsCount }}</h3>
                        <p>{{ __('message.all_jobs') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-suitcase"></i>
                    </div>
                    <a href="{{url('/')}}/employer/jobs" class="small-box-footer">
                    {{ __('message.view_all') }} <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-md-2 col-sm-12">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $candidates }}</h3>
                        <p>{{ __('message.total_candidates') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <a href="{{url('/')}}/employer/candidates" class="small-box-footer">
                    {{ __('message.more_info') }} <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-md-2 col-sm-12">
                <!-- small box -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $applications }}</h3>
                        <p>{{ __('message.total_applications') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hand-paper"></i>
                    </div>
                    <a href="{{url('/')}}/employer/job-board" class="small-box-footer">
                    {{ __('message.more_info') }} <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-md-2 col-sm-12">
                <!-- small box -->
                <div class="small-box bg-maroon">
                    <div class="inner">
                        <h3>{{ $interviews }}</h3>
                        <p>{{ __('message.total_interviews') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <a href="{{url('/')}}/employer/candidate-interviews" class="small-box-footer">
                    {{ __('message.more_info') }} <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-md-2 col-sm-12">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $hired }}</h3>
                        <p>{{ __('message.total_hired') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-check"></i>
                    </div>
                    <a href="{{url('/')}}/employer/job-board" class="small-box-footer">
                    {{ __('message.more_info') }} <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-md-2 col-sm-12">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $rejected }}</h3>
                        <p>{{ __('message.total_rejected') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <a href="{{url('/')}}/employer/job-board" class="small-box-footer">
                    {{ __('message.more_info') }} <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col-->
            <section class="col-lg-6">
                @if(empAllowedTo('view_job_chart'))
                <!-- DONUT CHART -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('message.popular_jobs') }}</h3>
                        <div class="box-tools pull-right">
                            <input class="minimal popular" type="checkbox" checked="checked" id="applied_check" checked="checked" /> 
                            <strong>{{ __('message.applied') }}</strong>
                            &nbsp;<input class="minimal popular" type="checkbox" checked="checked" id="favorited_check" checked="checked" /> <strong>{{ __('message.favorited') }}</strong>
                            &nbsp;<input class="minimal popular" type="checkbox" checked="checked" id="referred_check" checked="checked" /> <strong>{{ __('message.referred') }}</strong>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart tab-pane jobs-chart" id="jobs-chart"></div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                @endif
                @if(empAllowedTo('view_jobs_status'))
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('message.job_statuses') }}</h3>
                        <div class="box-tools pull-right">
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-xs btn-primary btn-blue dashboard-jobs-previos-button"><</button>
                                <button type="button" class="btn btn-xs btn-primary btn-blue disabled" id="dashboard_jobs_pagination_container">
                                1 - 10
                                </button>
                                <button type="button" class="btn btn-xs btn-primary btn-blue dashboard-jobs-next-button">></button>
                            </div>
                            <input type="hidden" id="dashboard_jobs_page" value="{{ $dashboard_jobs_page }}">
                            <input type="hidden" id="dashboard_jobs_total_pages" value="{{ $dashboard_jobs_total_pages }}">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>{{ __('message.job') }}</th>
                                        <th>{{ __('message.department') }}</th>
                                        <th>{{ __('message.candidates') }}</th>
                                        <th>{{ __('message.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="dashboard_jobs_list">
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.box-body -->
                </div>
                @endif
            </section>
            <!-- /.Left col -->
            <!-- right col chart-->
            <section class="col-lg-6">
                @if(empAllowedTo('view_candidate_chart'))
                <!-- BAR CHART -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('message.top_candidates') }}</h3>
                        <div class="box-tools pull-right dashboard-top-candidates-tools">
                            <input class="minimal top" id="traites_check" type="checkbox" checked="checked" /> <strong>{{ __('message.traites') }}</strong>
                            &nbsp;<input class="minimal top" id="interviews_check" type="checkbox" checked="checked" /> <strong>{{ __('message.interviews') }}</strong>
                            &nbsp;<input class="minimal top" id="quizes_check" type="checkbox" checked="checked" /> <strong>{{ __('message.quizes') }}</strong>
                            <select class="select2" id="job_id">
                                <option value="">{{ __('message.all_jobs') }}</option>
                                @foreach ($jobs as $job)
                                <option value="{{ encode($job['job_id']) }}">{{ $job['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart top-candidate-chart-container">
                            <canvas id="top-candidate-chart" class="top-candidate-chart"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                @endif
                @if(empAllowedTo('to_do_list'))
                <div class="box box-warning">
                    <div class="box-header">
                        <i class="ion ion-clipboard"></i>
                        <h3 class="box-title">{{ __('message.to_do_list') }}</h3>
                        <div class="box-tools pull-right">
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-xs btn-primary btn-blue dashboard-todos-previos-button"><</button>
                                <button type="button" class="btn btn-xs btn-primary btn-blue disabled" id="dashboard_todos_pagination_container">
                                1 - 10
                                </button>
                                <button type="button" class="btn btn-xs btn-primary btn-blue dashboard-todos-next-button">></button>
                            </div>
                            <input type="hidden" id="dashboard_todos_page" value="{{ $dashboard_todos_page }}">
                            <input type="hidden" id="dashboard_todos_total_pages" value="{{ $dashboard_todos_total_pages }}">
                            <button type="button" class="btn btn-xs btn-primary btn-blue dashboard-add-note-btn create-or-edit-todo">
                            <i class="fa fa-plus"></i>
                            </button>             
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ul class="todo-list ui-sortable" id="dashboard_todos_list">
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                @endif
            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- Forms for actions -->
<form id="jobs_data_form"></form>
<form id="jobs_list_form"></form>
<form id="todos_list_form"></form>
<form id="candidates_data_form"></form>
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/dashboard.js"></script>
<script src="{{url('a-assets')}}/js/cf/candidate.js"></script>
@endsection