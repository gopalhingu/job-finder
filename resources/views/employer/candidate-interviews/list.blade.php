@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fas fa-gavel"></i> {{ __('message.candidate_interviews') }}<small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}/employer/dashboard"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fas fa-gavel"></i> {{ __('message.candidate_interviews') }}</li>
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
                                <div class="datatable-top-controls datatable-top-controls-dd">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> {{ __('message.filter_by_status') }}</button>
                                        </span>
                                        <select class="form-control select2" id="status">
                                            <option value="">{{ __('message.all') }}</option>
                                            <option value="0">{{ __('message.pending') }}</option>
                                            <option value="1">{{ __('message.done') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="datatable-top-controls datatable-top-controls-dd">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> {{ __('message.job') }}</button>
                                        </span>
                                        <select class="form-control select2" id="job_id">
                                            <option value="">{{ __('message.all') }}</option>
                                            @foreach ($jobs as $job)
                                            <option value="{{ encode($job['job_id']) }}">{{ $job['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if(empAllowedTo('all_candidate_interviews'))
                                <div class="datatable-top-controls datatable-top-controls-dd">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> {{ __('message.assigned_to') }}</button>
                                        </span>
                                        <select class="form-control select2" id="interviewer_id">
                                            <option value="">{{ __('message.all') }}</option>
                                            @foreach ($employers as $employer)
                                            <option value="{{ encode($employer['employer_id']) }}">{{ $employer['first_name'].' '.$employer['last_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped" id="candidate_interviews_datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('message.title') }}</th>
                                    <th>{{ __('message.candidate') }}</th>
                                    <th>{{ __('message.job') }}</th>
                                    <th>{{ __('message.assigned_to') }}</th>
                                    <th>{{ __('message.created_on') }}</th>
                                    <th>{{ __('message.status') }}</th>
                                    <th>{{ __('message.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
<!-- page script -->

@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/candidate_interview.js"></script>
@endsection