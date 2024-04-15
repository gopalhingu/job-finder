@extends('employer.layouts.outer')
@section('page'){{$page}}@endsection
@section('content')
<div class="login-box">
    <div class="login-logo">
        <img src="{{ setting('site_logo') }}" height="50" />
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{ __('message.sign_in_to_start') }}</p>
        @include('employer.partials.messages')
        <form action="{{url('/')}}/employer/login-post" method="post" >
        @csrf
        <div class="form-group has-feedback">
            <input type="email" name="email" class="form-control" placeholder="{{__('message.email')}}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="{{__('message.password')}}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                    <input name="rememberme" type="checkbox" class="minimal" /> {{ __('message.remember_me') }}
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('message.sign_in') }}</button>
            </div>
            <!-- /.col -->
        </div>
        </form>
        <a href="{{url('/')}}/employer/forgot-password">{{ __('message.i_forgot_password') }}</a><br>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
@endsection