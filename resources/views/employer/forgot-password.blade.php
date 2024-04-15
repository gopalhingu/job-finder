@extends('employer.layouts.outer')
@section('page'){{$page}}@endsection
@section('content')
<div class="login-box">
    <div class="login-logo">
        <img src="{{ setting('site_logo') }}" width="350" />
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{ __('message.enter_emal_to_get_reset') }}</p>
        @include('employer.partials.messages')
        <form action="{{url('/')}}/employer/forgot-password-post" method="post" >
        @csrf        
        <div class="form-group has-feedback">
            <input type="email" name="email" class="form-control" placeholder="Email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('message.send') }}</button>
            </div>
            <!-- /.col -->
        </div>
        </form>
        <a href="{{url('/')}}/employer/login">{{ __('message.back_to_login') }}</a><br>
    </div>
    <!-- /.login-box-body -->

</div>
<!-- /.login-box -->
@endsection