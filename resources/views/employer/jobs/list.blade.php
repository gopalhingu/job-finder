@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-briefcase"></i> {{ __('message.jobs') }}<small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}/employer/dashboard"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fa fa-briefcase"></i> {{ __('message.jobs') }}</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="datatable-top-controls datatable-top-controls-filter">
                                    @if(empAllowedTo('create_jobs'))
                                    <button type="button" class="btn btn-primary btn-blue btn-flat create-or-edit-job">
                                    <i class="fa fa-plus"></i> {{ __('message.add_job') }}
                                    </button>
                                    @endif
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-blue btn-flat">{{ __('message.actions') }}</button>
                                        <button type="button" class="btn btn-primary btn-blue btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#" class="job-bulk-action" data-action="download-excel">{{ __('message.download_jobs') }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="datatable-top-controls datatable-top-controls-dd">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> {{ __('message.department') }}</button>
                                        </span>
                                        <select class="form-control select2" id="department">
                                            <option value="">{{ __('message.all') }}</option>
                                            @foreach ($departments as $key => $value)
                                            <option value="{{ encode($value['department_id']) }}">{{ $value['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="datatable-top-controls datatable-top-controls-dd">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> {{ __('message.status') }}</button>
                                        </span>
                                        <select class="form-control select2" id="status">
                                            <option value="">{{ __('message.all') }}</option>
                                            <option value="1">{{ __('message.active') }}</option>
                                            <option value="0">{{ __('message.inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                @if($job_filters)
                                @foreach($job_filters as $filter)
                                @if ($filter['admin_filter'] == 1)
                                <div class="datatable-top-controls datatable-top-controls-dd">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-flat">
                                        <i class="fa fa-filter"></i> {{ $filter['title'] }}</button>
                                        </span>
                                        <select class="form-control select2 job-filter" 
                                            id="{{ encode($filter['job_filter_id']) }}">
                                            <option value="">{{ __('message.all') }}</option>
                                            @foreach ($filter['values'] as $v)
                                            <option value="{{ encode($v['id']) }}">{{ $v['title'] }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="messages-container"></div>
                        @if(empAllowedTo('view_jobs'))
                        <table class="table table-bordered table-striped" id="jobs_datatable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="minimal all-check"></th>
                                    <th>{{ __('message.title') }}</th>
                                    <th>{{ __('message.department') }}</th>
                                    <th>{{ __('message.job_filters') }}</th>
                                    <th>{{ __('message.applications') }}</th>
                                    <th>{{ __('message.favorites') }}</th>
                                    <th>{{ __('message.referred') }}</th>
                                    <th>{{ __('message.traites') }}</th>
                                    <th>{{ __('message.created_on') }}</th>
                                    <th>{{ __('message.status') }}</th>
                                    <th>{{ __('message.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- Right Modal -->
<div class="modal right fade modal-right" id="modal-right" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Departments</h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
        <!-- modal-content -->
    </div>
    <!-- modal-dialog -->
</div>
<!-- modal -->
<!-- Forms for actions -->
<form id="jobs-form" method="POST" action="{{ url('/') }}/employer/jobs/excel" target='_blank'></form>
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/department.js"></script>
<script src="{{url('e-assets')}}/js/cf/job.js"></script>
@endsection