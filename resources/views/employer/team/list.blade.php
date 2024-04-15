@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-users"></i> {{ __('message.team') }}<small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}/employer/dashboard"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fa fa-users"></i> {{ __('message.team') }}</li>
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
                                    @if(empAllowedTo('create_team_member'))
                                    <button type="button" class="btn btn-primary btn-blue btn-flat create-or-edit-team">
                                    <i class="fa fa-plus"></i> {{ __('message.add_team_member') }}
                                    </button>
                                    @endif
                                    @if(empAllowedTo('view_roles') && empMembership(employerId(), 'role_permissions') == 1)
                                    <button type="button" class="btn btn-primary btn-blue btn-flat view-roles">
                                    <i class="fa fa-user"></i>
                                    <i class="fa fa-cog role-cog"></i> 
                                    {{ __('message.roles') }}
                                    </button>
                                    @endif
                                </div>
                                @if(empMembership(employerId(), 'role_permissions') == 1)
                                <div class="datatable-top-controls datatable-top-controls-dd">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> {{ __('message.filter_by_role') }}</button>
                                        </span>
                                        <select class="form-control select2" id="role">
                                            <option value="">{{ __('message.all') }}</option>
                                            @foreach($roles as $key => $value)
                                            <option value="{{ encode($value['role_id']) }}">{{ $value['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
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
                        @if(empAllowedTo('view_team_listing'))
                        <table class="table table-bordered table-striped" id="team_datatable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="minimal all-check"></th>
                                    <th>{{ __('message.image') }}</th>
                                    <th>{{ __('message.first_name') }}</th>
                                    <th>{{ __('message.last_name') }}</th>
                                    <th>{{ __('message.email') }}</th>
                                    <th>{{ __('message.roles2') }}</th>
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
                <h4 class="modal-title" id="myModalLabel2">{{__('message.roles')}}</h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
        <!-- modal-content -->
    </div>
    <!-- modal-dialog -->
</div>
<!-- modal -->
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/role.js"></script>
<script src="{{url('e-assets')}}/js/cf/team.js"></script>
@endsection