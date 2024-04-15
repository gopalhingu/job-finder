@extends('employer.layouts.outer')
@section('page'){{$page}}@endsection
@section('content')
<div class="login-box">
    <div class="login-logo">
        <img src="{{ setting('site_logo') }}" alt="{{setting('site_name')}}" width="350" />
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
	    <strong><h3>{{__('message.congratulations')}}!</h3></strong>
	    <p>
	    	{{__('message.account_activated')}}. <br />
	    	<a href="{{url('/')}}/employer">{{__('message.login')}}</a> 
	    	{{__('message.with_your_cred')}}.
	    </p >
	</div>
</div>
@endsection