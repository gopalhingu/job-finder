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
                    <h1 class="m-0">{{__('message.settings')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{__('message.settings')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.general_images')}}</li>
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
                    <h3 class="card-title">{{ __('message.general_images') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <form id="admin_general_images_settings_form" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <h2>{{__('message.features')}}</h2>
                        </div>                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_feature_filters')}}</label>
                                <input type="file" class="form-control dropify" name="general_feature_filters" 
                                data-default-file="{{route('uploads-view', setting('general_feature_filters'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_feature_interviews')}}</label>
                                <input type="file" class="form-control dropify" name="general_feature_interviews" 
                                data-default-file="{{route('uploads-view', setting('general_feature_interviews'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_feature_jobboard')}}</label>
                                <input type="file" class="form-control dropify" name="general_feature_jobboard" 
                                data-default-file="{{route('uploads-view', setting('general_feature_jobboard'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_feature_personalized')}}</label>
                                <input type="file" class="form-control dropify" name="general_feature_personalized" 
                                data-default-file="{{route('uploads-view', setting('general_feature_personalized'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_feature_quizes')}}</label>
                                <input type="file" class="form-control dropify" name="general_feature_quizes" 
                                data-default-file="{{route('uploads-view', setting('general_feature_quizes'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_feature_referjob')}}</label>
                                <input type="file" class="form-control dropify" name="general_feature_referjob" 
                                data-default-file="{{route('uploads-view', setting('general_feature_referjob'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_feature_reports')}}</label>
                                <input type="file" class="form-control dropify" name="general_feature_reports" 
                                data-default-file="{{route('uploads-view', setting('general_feature_reports'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_feature_resume')}}</label>
                                <input type="file" class="form-control dropify" name="general_feature_resume" 
                                data-default-file="{{route('uploads-view', setting('general_feature_resume'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_feature_rolepermissions')}}</label>
                                <input type="file" class="form-control dropify" name="general_feature_rolepermissions" 
                                data-default-file="{{route('uploads-view', setting('general_feature_rolepermissions'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_feature_selfassesment')}}</label>
                                <input type="file" class="form-control dropify" name="general_feature_selfassesment" 
                                data-default-file="{{route('uploads-view', setting('general_feature_selfassesment'))}}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <h2>{{__('message.other')}}</h2>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_modal')}}</label>
                                <input type="file" class="form-control dropify" name="general_modal" 
                                data-default-file="{{route('uploads-view', setting('general_modal'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_candidates')}}</label>
                                <input type="file" class="form-control dropify" name="general_candidates" 
                                data-default-file="{{route('uploads-view', setting('general_candidates'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_employers')}}</label>
                                <input type="file" class="form-control dropify" name="general_employers" 
                                data-default-file="{{route('uploads-view', setting('general_employers'))}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.general_benefits')}}</label>
                                <input type="file" class="form-control dropify" name="general_benefits" 
                                data-default-file="{{route('uploads-view', setting('general_benefits'))}}" />
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary" id="admin_general_images_settings_form_button">
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
<script src="{{url('a-assets')}}/js/cf/setting.js"></script>
@endsection