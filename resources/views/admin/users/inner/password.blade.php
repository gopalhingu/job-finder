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
                    <h1 class="m-0">{{__('message.password')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.password')}}</li>
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
                    <h3 class="card-title">{{__('message.update').' '.__('message.password')}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <form id="admin_password_reset_form" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.old_password')}}</label>
                                <input type="password" class="form-control" name="old_password" 
                                placeholder="{{__('message.enter_old_password')}}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.new_password')}}</label>
                                <input type="password" class="form-control" name="new_password" 
                                placeholder="{{__('message.enter_new_password')}}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.retype_password')}}</label>
                                <input type="password" class="form-control" name="retype_password" 
                                placeholder="{{__('message.enter_password_again')}}" />
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary" id="admin_password_reset_form_button">
                                {{__('message.update')}}
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>

                </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/user.js"></script>
@endsection