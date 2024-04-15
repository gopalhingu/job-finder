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
                    <h1 class="m-0">{{__('message.messages')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.messages')}}</li>
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
                            <h3 class="card-title">{{__('message.messages').' '.__('message.list')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row dt-controls-row">
                                <div class="col-md-12">
                                    <div class="datatable-top-controls datatable-top-controls-filter">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default">{{ __('message.actions') }}</button>
                                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a class="dropdown-item bulk-action" href="#" data-action="reply">{{ __('message.reply') }}</a>                                                
                                                <a class="dropdown-item bulk-action" href="#" data-action="download-excel">{{ __('message.download_messages_excel') }}</a>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>

                            <table class="table table-bordered table-striped" id="messages_datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="minimal all-check"></th>
                                        <th>{{ __('message.name') }}</th>
                                        <th>{{ __('message.email') }}</th>
                                        <th>{{ __('message.subject') }}</th>
                                        <th>{{ __('message.description') }}</th>
                                        <th>{{ __('message.created_on') }}</th>
                                        <th>{{ __('message.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>                            
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

<!-- Forms for actions -->
<form id="messages-form" method="POST" action="{{url(route('admin-messages-excel'))}}" target='_blank'></form>

@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/message.js"></script>
@endsection