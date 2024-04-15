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
                    <h1 class="m-0">{{__('message.languages')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.languages')}}</li>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('message.languages').' '.__('message.list')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            @if(allowedTo('languages_settings'))
                            <div class="row dt-controls-row">
                                <div class="col-md-12">
                                    <div class="datatable-top-controls datatable-top-controls-filter">
                                        <button type="button" class="btn btn-primary btn-blue btn-flat create-or-edit-language">
                                        <i class="fa fa-plus"></i> {{ __('message.create_language') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped" id="languages_datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="minimal all-check"></th>
                                        <th>{{ __('message.title') }}</th>
                                        <th>{{ __('message.created_on') }}</th>
                                        <th>{{ __('message.default') }}</th>
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
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
@section('page-scripts')
<!-- page script -->
<script src="{{url('a-assets')}}/js/cf/language.js"></script>
@endsection