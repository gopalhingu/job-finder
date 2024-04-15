@extends('admin.layouts.login')

@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{route('admin')}}">
                <img src="{{setting('site_logo')}}" alt="{{setting('site_name')}}" height="50" />
            </a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{__('message.sign_in_to_start')}}</p>
                @if (isEmpRoute() || isAdminRoute())
                    @include('admin.partials.messages-3')
                @else 
                    @include('admin.partials.messages')
                @endif
                <form action="{{route('admin-login-post')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="{{__('message.email')}}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="{{__('message.password')}}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input class="minimal" type="checkbox" name="rememberme" id="remember">
                                <label for="remember">{{__('message.remember_me')}}</label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">{{__('message.sign_in')}}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- /.social-auth-links -->
                <p class="mb-1">
                    <a href="{{route('admin-forgot-pass')}}">{{__('message.i_forgot_password')}}</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/login.js"></script>
@endsection