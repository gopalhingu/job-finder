@extends('candidate'.viewPrfx().'layouts.master')
@section('page-title'){{$page}}@endsection
@section('content')
<!--==========================
    Intro Section
============================-->
<section id="intro" class="clearfix front-intro-section">
    <div class="container">
        <div class="intro-img">
        </div>
        <div class="intro-info">
            <h2><span>{{ __('message.login_to_account') }}</span></h2>
        </div>
    </div>
</section>
<!-- #intro -->
<main id="main">
    <!--==========================
        Account Area Setion
    ============================-->
    <section class="main-container">
        <div class="container">
            <div class="row mt-10">
                <div class="col-lg-3">
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="account-box">
                                <p class="account-box-heading">
                                    <span class="account-box-heading-text">{{ __('message.login') }}</span>
                                    <span class="account-box-heading-line"></span>
                                </p>
                                <form action="{{ empUrl() }}post-login" method="POST">
                                @csrf
                                <div class="container">
                                    @include('candidate'.viewPrfx().'partials.messages')
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <label for="">{{ __('message.email') }}</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" name="email" class="form-control" placeholder="{{ __('message.email') }}" 
                                                        aria-label="{{ __('message.email') }}" aria-describedby="basic-addon1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                                                    </div>
                                                </div>
                                                <small id="" class="form-text text-muted">{{ __('message.enter_email') }}.</small>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <label for="">{{ __('message.password') }}</label>
                                                <div class="input-group mb-3">
                                                    <input type="password" name="password" class="form-control" placeholder="{{ __('message.password') }}"
                                                        aria-label="Email" aria-describedby="basic-addon1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-key"></i></span>
                                                    </div>
                                                </div>
                                                <small id="" class="form-text text-muted">{{ __('message.enter_password') }}.</small>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <label for="">
                                                <input type="checkbox" name="remember" class="" placeholder="{{ __('message.remember_me') }}" /> 
                                                {{ __('message.remember_me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <button type="submit" class="btn btn-success">{{ __('message.login') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                @if(setting('enable_candidate_forgot_password') == 'yes')
                                                <a href="{{ empUrl() }}forgot-password">{{ __('message.forgot_password') }} ?</a><br />
                                                @endif
                                                @if(setting('enable_candidate_registeration') == 'yes')
                                                <a href="{{ empUrl() }}register">{{ __('message.register') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        @if(setting('enable_google_login') == 'yes')
                                        <div class="col-md-6 col-lg-6">
                                            <a href="{!! $googleLogin !!}" class="btn btn-block btn-social btn-google">
                                            <span class="fab fa-google"></span> {{ __('message.sign_in_google') }}
                                            </a>
                                            <br />
                                        </div>
                                        @endif
                                        @if(setting('enable_linkedin_login') == 'yes')
                                        <div class="col-md-6 col-lg-6">
                                            <a href="{!! $linkedinLogin !!}" class="btn btn-block btn-social btn-linkedin">
                                            <span class="fab fa-linkedin"></span> {{ __('message.sign_in_linkedin') }}
                                            </a>
                                            <br />
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                </div>
            </div>
        </div>
    </section>
    <!-- #account area section ends -->
</main>
@endsection