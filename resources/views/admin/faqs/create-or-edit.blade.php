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
                    <h1 class="m-0">{{__('message.faqs')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.faqs')}}</li>
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
                            <h3 class="card-title">{{__('message.faqs').' '.__('message.edit')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <form id="admin_faqs_create_update_form">
                                <input type="hidden" name="faqs_id" value="{{ $faqs['faqs_id'] }}" />
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('message.status') }}</label>
                                                <select class="form-control" name="status">
                                                    <option value="0" {{ sel($faqs['status'], '0') }}>{{ __('message.no') }}</option>
                                                    <option value="1" {{ sel($faqs['status'], '1') }}>{{ __('message.yes') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('message.category') }}</label>
                                                <select class="form-control select2" name="category_id">
                                                    @foreach ($categories as $key => $value)
                                                    <option value="{{ $value['category_id'] }}" {{sel($value['category_id'], $faqs['category_id'])}}>{{ $value['title'] }}</option>
                                                    @endforeach
                                                </select>                    
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{__('message.question')}}</label>
                                                <textarea class="form-control" name="question">{{$faqs['question']}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{__('message.answer')}}</label>
                                                <textarea class="form-control" name="answer">{{$faqs['answer']}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('message.close') }}</button>
                                    <button type="submit" class="btn btn-primary btn-blue" id="admin_faqs_create_update_form_button">{{__('message.save') }}</button>
                                </div>
                            </form>


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
<form id="faqs-form" method="POST" action="{{url(route('admin-faqs-excel'))}}" target='_blank'></form>

@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/faqs.js"></script>
@endsection