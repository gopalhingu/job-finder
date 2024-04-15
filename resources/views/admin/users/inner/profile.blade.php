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
                    <h1 class="m-0">{{__('message.profile')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.profile')}}</li>
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
                    <h3 class="card-title">{{__('message.update').' '.__('message.profile')}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <form id="admin_profile_form" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.first_name')}}</label>
                                <input type="text" class="form-control" name="first_name" 
                                value="{{$profile['first_name']}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.last_name')}}</label>
                                <input type="text" class="form-control" name="last_name" 
                                value="{{$profile['last_name']}}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.username')}}</label>
                                <input type="text" class="form-control" name="username" 
                                value="{{$profile['username']}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.email')}}</label>
                                <input type="text" class="form-control" name="email" 
                                value="{{$profile['email']}}">
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.phone')}}</label>
                                <input type="text" class="form-control" name="phone" 
                                value="{{$profile['phone']}}">
                            </div>
                        </div>                               
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>{{__('message.image')}}</label>
                            @php $thumb = userThumb($profile['image']); @endphp
                            <input type="file" class="form-control dropify" name="image" 
                                  data-default-file="{{$thumb['image']}}" />
                          </div>
                        </div>
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary" id="admin_profile_form_button">
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