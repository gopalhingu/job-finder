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
                            <h1 class="m-0">{{__('message.dashboard')}}</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                                <li class="breadcrumb-item active">{{__('message.dashboard')}}</li>
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
                    @if(allowedTo('view_dashboard_stats'))
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-tie"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{__('message.employers')}}</span>
                                    <span class="info-box-number">{{$employers}}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-graduate"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{__('message.candidates')}}</span>
                                    <span class="info-box-number">{{$candidates}}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{__('message.sales')}}</span>
                                    <span class="info-box-number">{{$sales}}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-suitcase"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{__('message.jobs')}}</span>
                                    <span class="info-box-number">{{$jobs}}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-indigo elevation-1"><i class="far fa-list-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{__('message.quizes')}}</span>
                                    <span class="info-box-number">{{$quizes}}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-fuchsia elevation-1"><i class="fas fa-clipboard-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{__('message.interviews')}}</span>
                                    <span class="info-box-number">{{$interviews}}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-star-half-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{__('message.traites')}}</span>
                                    <span class="info-box-number">{{$traites}}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->                        
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-teal elevation-1"><i class="fa fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{__('message.job_applications')}}</span>
                                    <span class="info-box-number">{{$job_applications}}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->                        
                    </div>
                    @endif
                    <div class="row">
                        @if(allowedTo('view_sales_chart'))
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">{{__('message.sales')}}</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <p class="d-flex flex-column">
                                            <select class="form-control" id="sales-data-dd">
                                                <option value="this_month">{{__('message.this_month')}}</option>
                                                <option value="last_month">{{__('message.last_month')}}</option>
                                                <option value="this_year">{{__('message.this_year')}}</option>
                                                <option value="last_year">{{__('message.last_year')}}</option>
                                            </select>                                            
                                        </p>
                                    </div>
                                    <!-- /.d-flex -->
                                    <div class="position-relative mb-4">
                                        <canvas id="sales-chart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col-md-6 -->
                        @endif
                        @if(allowedTo('view_signups_chart'))
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">{{__('message.signups')}}</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <p class="d-flex flex-column">
                                            <select class="form-control" id="signups-data-dd">
                                                <option value="this_month">{{__('message.this_month')}}</option>
                                                <option value="last_month">{{__('message.last_month')}}</option>
                                                <option value="this_year">{{__('message.this_year')}}</option>
                                                <option value="last_year">{{__('message.last_year')}}</option>
                                            </select>                                            
                                        </p>
                                    </div>
                                    <!-- /.d-flex -->
                                    <div class="position-relative mb-4">
                                        <canvas id="signups-chart" height="200"></canvas>
                                    </div>
                                    <div class="d-flex flex-row justify-content-end">
                                        <span class="mr-2">
                                        <i class="fas fa-square text-primary"></i>{{__('message.employers')}}</span>
                                        <span><i class="fas fa-square text-gray"></i>{{__('message.candidates')}}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col-md-6 -->
                        @endif
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
@endsection
@section('page-scripts')
<script src="{{url('/a-assets')}}/js/cf/dashboard.js"></script>
@endsection