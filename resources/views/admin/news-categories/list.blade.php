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
                    <h1 class="m-0">{{__('message.news_categories')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.news_categories')}}</li>
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
                            <h3 class="card-title">{{__('message.news_categories').' '.__('message.list')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row dt-controls-row">
                                <div class="col-md-12">
                                    <div class="datatable-top-controls datatable-top-controls-filter">
                                        @if(allowedTo('create_news_categories'))
                                        <button type="button" class="btn btn-info create-or-edit-news-category">
                                        <i class="fa fa-plus"></i> {{ __('message.create_news_category') }}
                                        </button>
                                        @endif                                       
                                    </div>
                                    <div class="datatable-top-controls datatable-top-controls-dd">
                                        <div class="input-group">
                                            <select class="form-control select2" id="status">
                                                <option value="">{{ __('message.all') }}</option>
                                                <option value="1">{{ __('message.active') }}</option>
                                                <option value="0">{{ __('message.inactive') }}</option>
                                            </select>
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-info">
                                                    <i class="fa fa-filter"></i> 
                                                    {{ __('message.filter_by_status') }}
                                                </button>
                                            </span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(allowedTo('view_news_categories'))
                            <table class="table table-bordered table-striped" id="news_categories_datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="minimal all-check"></th>
                                        <th>{{ __('message.title').' ('.__('message.slug').')' }}</th>
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
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/news_category.js"></script>
@endsection