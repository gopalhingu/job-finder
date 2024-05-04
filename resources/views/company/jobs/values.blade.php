@extends('company.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-briefcase"></i> {{ __('message.jobs') }}<small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}/company/dashboard"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
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
                                </div>
                                <div class="datatable-top-controls datatable-top-controls-dd">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> {{ __('message.status') }}</button>
                                        </span>
                                        <select class="form-control select2" id="jobFollowstatus">
                                            <option value="">{{ __('message.all') }}</option>
                                            <option value="1">{{ __('message.active') }}</option>
                                            <option value="0">{{ __('message.inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="messages-container"></div>
                        @if(empAllowedTo('view_jobs'))
                        <table class="table table-bordered table-striped" id="jobs_follow_datatable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="minimal all-check"></th>
                                    <th>{{ __('message.title') }}</th>
                                    <th>{{ __('message.created_on') }}</th>
                                    <th>{{ __('message.status') }}</th>
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
<!-- Forms for actions -->
<form id="jobs-form" method="POST" action="{{ url('/') }}/company/jobs/excel" target='_blank'></form>
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('company-assets')}}/js/cf/department.js"></script>
<script src="{{url('company-assets')}}/js/cf/job.js"></script>
@endsection