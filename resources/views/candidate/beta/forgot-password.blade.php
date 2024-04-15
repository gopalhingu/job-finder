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
                        <form id="forgot_form" action="{{ empUrl() }}send-password-link" method="POST">
                            @csrf
                            @include('candidate'.viewPrfx().'partials.messages')
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="">{{ __('message.email') }}</label>
                                <input type="email" name="email" class="form-control shadow-none border-none" />
                                <small id="" class="form-text text-muted">{{ __('message.enter_email_to_receive') }}</small>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                <button type="submit" class="btn btn-success" id="forgot_form_button" title="Save">{{ __('message.send') }}</button>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group form-group-account login-other-btns">
                                <a href="{{ empUrl() }}login">{{ __('message.back_to_login') }}</a><br />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12"></div>
        </div>
    </div>

@endsection
