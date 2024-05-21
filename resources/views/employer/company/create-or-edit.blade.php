@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-briefcase"></i> {{ __('message.company') }}<small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}/employer/dashboard"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fa fa-briefcase"></i> {{ __('message.company') }}</li>
            <li class="active">{{ __('message.create') }}</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            @if(empAllowedTo('create_companys') || empAllowedTo('edit_companys'))
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('message.details') }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="employer_company_create_update_form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.first_name') }}</label>
                                        <input type="hidden" name="company_id" value="{{ encode($company['company_id']) }}" />
                                        <input type="text" class="form-control" placeholder="{{__('message.enter_first_name')}}" name="first_name" value="{{ $company['first_name'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.last_name') }}</label>
                                        <input type="text" class="form-control" placeholder="{{__('message.enter_last_name')}}" name="last_name" value="{{ $company['last_name'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.company_name') }}</label>
                                        <input type="text" class="form-control" placeholder="{{__('message.enter_company_name')}}" name="companyname" value="{{ $company['companyname'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.slug') }}</label>
                                        <input type="text" class="form-control" placeholder="{{__('message.will_auto_generate_if_blank')}}" name="slug" value="{{ $company['slug'] }}">
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.email') }}</label>
                                        <input type="text" class="form-control" placeholder="{{__('message.enter_email')}}" name="email" value="{{ $company['email'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.password') }}</label>
                                        <input type="password" class="form-control" placeholder="{{__('message.enter_password')}}" name="password" value="{{ $company['password'] }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="employer_company_create_update_form_button">
                            {{ __('message.save') }}
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            @endif
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/company.js"></script>
@endsection