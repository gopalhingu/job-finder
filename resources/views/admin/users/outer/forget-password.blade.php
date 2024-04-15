@extends('admin.layouts.login')

@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{route('admin')}}">
                <img src="{{setting('site_logo')}}" alt="{{setting('site_name')}}" width="320" />
            </a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{__('message.enter_emal_to_get_reset')}}</p>
                @include('admin.partials.messages')
                <form action="{{route('admin-forgot-pass-post')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="{{__('message.email')}}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">{{__('message.send')}}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- /.social-auth-links -->
                <p class="mb-1">
                    <br />
                    <a href="{{route('admin-login')}}">{{__('message.back_to_login')}}</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

@endsection
