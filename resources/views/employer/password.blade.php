@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper Starts -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fas fa-cube"></i> {{ __('message.update_password') }} </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }} </a></li>
            <li class="active"><i class="fas fa-cube"></i> {{ __('message.update_password') }} </li>
        </ol>
    </section>
    <!-- Main content Starts-->
    <section class="content">
        <!-- Main row Starts-->
        <div class="row">
            <section class="col-lg-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('message.update_password') }} </h3>
                    </div>
                    <form id="employer_password_form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('message.old_password') }} </label>
                                        <input type="password" class="form-control" name="old_password">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('message.new_password') }} </label>
                                        <input type="password" class="form-control" name="new_password">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('message.retype_password') }} </label>
                                        <input type="password" class="form-control" name="retype_password">
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="employer_password_form_button">{{ __('message.save') }} </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        <!-- Main row Ends-->
    </section>
    <!-- Main content Ends-->
</div>
<!-- Content Wrapper Ends -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/team.js"></script>
@endsection