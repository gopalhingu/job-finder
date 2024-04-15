@extends('admin.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{__('message.roles')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.roles')}}</li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">{{__('message.create_edit_roles')}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mt-4 mb-2">
                                {{__('message.roles')}} 
                                <button type="button" class="btn btn-sm btn-success create-role">
                                    {{__('message.create')}}
                                </button>
                            </h5>
                            <div class="input-group input-group-sm">
                                <select class="form-control select2" id="roles-dropdown">
                                    @foreach($roles as $role)
                                    <option value="{{$role['role_id']}}">{{$role['title'].' ('.$role['type'].')'}}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-info btn-flat edit-role">{{__('message.edit')}}</button>
                                    <button type="button" class="btn btn-danger btn-flat delete-role">{{__('message.delete')}}</button>
                                </span>
                            </div>
                            <!-- /input-group -->
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <h5 class="mt-4 mb-2">
                                    {{__('message.permissions')}} 
                                </h5>
                                <div id="permissions-container">
                                </div>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/role.js"></script>
@endsection