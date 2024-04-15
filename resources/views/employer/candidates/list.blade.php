@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-graduation-cap"></i> {{ __('message.candidates') }}<small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}/employer/dashboard"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fa fa-graduation-cap"></i> {{ __('message.candidates') }}</li>
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
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-blue btn-flat">{{ __('message.actions') }}</button>
                                        <button type="button" class="btn btn-primary btn-blue btn-flat dropdown-toggle" 
                                            data-toggle="dropdown" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#" class="bulk-action" data-action="download-resume">{{ __('message.download_resume_pdf') }}</a></li>
                                            <li><a href="#" class="bulk-action" data-action="download-excel">{{ __('message.download_candidates_excel') }}</a></li>
                                            @if(empAllowedTo('email_candidates'))
                                            <li><a href="#" class="bulk-action" data-action="email">{{ __('message.email') }}</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="datatable-top-controls datatable-top-controls-dd-2">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> 
                                        {{ __('message.job_title') }}</button>
                                        </span>
                                        <input type="text" class="form-control" id="job_title">
                                    </div>
                                </div>
                                <div class="datatable-top-controls datatable-top-controls-dd-2">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> 
                                        {{ __('message.experience') }}</button>
                                        </span>
                                        <input type="number" class="form-control" id="experience">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if(empAllowedTo('view_candidate_listing'))
                        <table class="table table-bordered table-striped" id="candidates_datatable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="minimal all-check"></th>
                                    <th>{{ __('message.image') }}</th>
                                    <th>{{ __('message.first_name') }}</th>
                                    <th>{{ __('message.last_name') }}</th>
                                    <th>{{ __('message.email') }}</th>
                                    <th>{{ __('message.job_title') }}</th>
                                    <th>{{ __('message.experience_months') }}</th>
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
                <h4 class="modal-title" id="myModalLabel2">Resume</h4>
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
<form id="resume-form" method="POST" action="{{ url('/') }}/employer/candidates/resume-download" target='_blank'></form>
<form id="candidates-form" method="POST" action="{{ url('/') }}/employer/candidates/excel" target='_blank'></form>
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/candidate.js"></script>
@endsection