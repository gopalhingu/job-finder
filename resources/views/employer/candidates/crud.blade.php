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
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="datatable-top-controls datatable-top-controls-filter">
                                    @if(allowedTo('create_candidate'))
                                    <button type="button" class="btn btn-primary btn-blue btn-flat create-or-edit-candidate">
                                        <i class="fa fa-plus"></i> {{ __('message.add_candidate') }}
                                    </button>
                                    @endif
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-blue btn-flat">{{ __('message.actions') }}</button>
                                        <button type="button" class="btn btn-primary btn-blue btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a class="dropdown-item bulk-action" href="#" data-action="activate">{{ __('message.activate') }}</a></li>
                                            <li><a class="dropdown-item bulk-action" href="#" data-action="deactivate">{{ __('message.deactivate') }}</a></li>
                                            <li><a class="dropdown-item bulk-action" href="#" data-action="email">{{ __('message.email') }}</a></li>
                                            <li><a class="dropdown-item bulk-action" href="#" data-action="download-resume">{{ __('message.download_resume_pdf') }}</a></li>
                                            <li><a class="dropdown-item bulk-action" href="#" data-action="download-excel">{{ __('message.download_candidates_excel') }}</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="datatable-top-controls datatable-top-controls-dd">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> {{ __('message.account_type') }}</button>
                                        </span>
                                        <select class="form-control select2" id="account_type">
                                            <option value="">{{ __('message.all') }}</option>
                                            <option value="site">{{ __('message.site') }}</option>
                                            <option value="google">{{ __('message.google') }}</option>
                                            <option value="linkedin">{{ __('message.linkedin') }}</option>
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
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="box-body">
                        
                        @if(allowedTo('view_candidate_listing'))
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
                                    <th>{{ __('message.account_type') }}</th>
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
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Forms for actions -->
<form id="resume-form" method="POST" action="{{url(route('admin-candidates-resume-pdf'))}}" target='_blank'></form>
<form id="candidates-form" method="POST" action="{{url(route('admin-candidates-excel'))}}" target='_blank'></form>

@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/candidate.js"></script>
@endsection