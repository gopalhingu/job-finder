@extends('employer.layouts.outer')
@section('page'){{$page}}@endsection
@section('content')
<div class="login-box">
    <div class="login-logo">
        <img src="{{ setting('site_logo') }}" width="350" />
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{ __('message.enter_new_password') }}</p>
        @include('employer.partials.messages')
        <form action="{{url('/')}}/employer/reset-password-post" method="post" >
        @csrf
        <div class="form-group has-feedback">
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="password" name="new_password" class="form-control" placeholder="{{__('message.new_password')}}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" name="retype_new_password" class="form-control" placeholder="{{__('message.retype_new_password')}}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('message.save') }}</button>
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
