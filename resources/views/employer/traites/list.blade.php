@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fas fa-star-half-alt"></i> {{ __('message.traites') }}<small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}/employer/dashboard"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fas fa-star-half-alt"></i> {{ __('message.traites') }}</li>
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
                                    @if(empAllowedTo('create_traites'))
                                    <button type="button" class="btn btn-primary btn-blue btn-flat create-or-edit-traite">
                                    <i class="fa fa-plus"></i> {{ __('message.add_traite') }}
                                    </button>
                                    @endif
                                </div>
                                <div class="datatable-top-controls datatable-top-controls-dd">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> {{ __('message.filter_by_status') }}</button>
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
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="messages-container"></div>
                        @if(empAllowedTo('view_traites'))
                        <table class="table table-bordered table-striped" id="traites_datatable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="minimal all-check"></th>
                                    <th>{{ __('message.title') }}</th>
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
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/traite.js"></script>
@endsection