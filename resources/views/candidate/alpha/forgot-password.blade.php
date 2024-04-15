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
            <h2><span>{{ __('message.forgot_password') }}</span></h2>
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
                                    <span class="account-box-heading-text">{{ __('message.forgot_password') }}</span>
                                    <span class="account-box-heading-line"></span>
                                </p>
                                <div class="container">
                                    <form id="forgot_form" action="{{ empUrl() }}send-password-link" method="POST">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.email') }}</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" name="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                                                        </div>
                                                    </div>
                                                    <small id="" class="form-text text-muted">{{ __('message.enter_email_to_receive') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group form-group-account">
                                                    <button type="submit" class="btn btn-success" id="forgot_form_button" title="Save">{{ __('message.send') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <a href="{{ empUrl() }}login">{{ __('message.back_to_login') }}</a>
                                            </div>
                                        </div>
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