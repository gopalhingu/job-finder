@extends('candidate.beta.layouts.master')

@section('page-title'){{$page}}@endsection

@section('breadcrumb')
@include('candidate.beta.partials.breadcrumb')
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-12 col-sm-12"></div>
            <div class="col-lg-8 col-md-12 col-sm-12">
                <div class="section-login-register-form">
                    <div class="row">
                        <form action="{{ empUrl() }}post-login" method="post">
                            @csrf
                            @include('candidate'.viewPrfx().'partials.messages')
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="">{{ __('message.email') }}</label>
                                <input type="email" name="email" class="form-control shadow-none border-none" />
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label>{{__('message.password')}}</label>
                                <input type="password" name="password" class="form-control shadow-none border-none" />
                            </div>
                            <div class="col-lg-12 col-md-12 col-lg-12">
                                <label for="">
                                    <input type="checkbox" name="remember" /> {{ __('message.remember_me') }}
                                </label>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                <button class="btn" id="front_password_reset_form_button">
                                    <i class="fa-regular fa-paper-plane"></i> {{__('message.login')}}
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group form-group-account login-other-btns">
                                @if(setting('enable_candidate_forgot_password') == 'yes')
                                <a href="{{ empUrl() }}forgot-password">{{ __('message.forgot_password') }} ?</a>
                                @endif
                                @if(setting('enable_candidate_registeration') == 'yes')
                                <a href="{{ empUrl() }}register">{{ __('message.register') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <hr />
                        @if(setting('enable_google_login') == 'yes')
                        <div class="col-md-6 col-lg-6">
                            <a href="{!! $googleLogin !!}" class="btn-social btn-google">
                            <span class="fab fa-google"></span> {{ __('message.sign_in_google') }}
                            </a>
                        </div>
                        @endif
                        @if(setting('enable_linkedin_login') == 'yes')
                        <div class="col-md-6 col-lg-6">
                            <a href="{!! $linkedinLogin !!}" class="btn-social btn-linkedin">
                            <span class="fab fa-linkedin"></span> {{ __('message.sign_in_linkedin') }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12"></div>
        </div>
    </div>

@endsection
