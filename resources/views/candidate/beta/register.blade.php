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
                    <form id="register_form">
                    <div class="row">
                            @csrf
                            @include('candidate'.viewPrfx().'partials.messages')
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <label for="">{{ __('message.first_name') }}</label>
                                <input type="text" name="first_name" class="form-control" />
                                <small id="" class="form-text text-muted">{{ __('message.enter_first_name') }}.</small>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <label for="">{{ __('message.last_name') }}</label>
                                <input type="text" name="last_name" class="form-control" />
                                <small id="" class="form-text text-muted">{{ __('message.enter_last_name') }}.</small>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <label for="">{{ __('message.gender') }}</label>
                                <select name="gender" class="form-control">
                                    <option value="male">{{ __('message.male') }}</option>
                                    <option value="femal">{{ __('message.female') }}</option>
                                    <option value="other">{{ __('message.other') }}</option>
                                </select>
                                <small id="" class="form-text text-muted">{{ __('message.select_gender') }}</small>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <label for="">{{ __('message.email') }}</label>
                                <input type="email" name="email" class="form-control shadow-none border-none" />
                                <small id="" class="form-text text-muted">{{ __('message.enter_email') }}.</small>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <label for="">{{ __('message.password') }}</label>
                                <input type="password" name="password" class="form-control shadow-none border-none" />
                                <small id="" class="form-text text-muted">{{ __('message.enter_password') }}</small>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <label for="">{{ __('message.retype_password') }}</label>
                                <input type="password" name="retype_password" class="form-control shadow-none border-none" />
                                <small id="" class="form-text text-muted">{{ __('message.enter_password_again') }}.</small>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                <button class="btn" id="register_form_button">
                                {{ __('message.register') }}
                                </button>                                
                            </div>
                    </div>
                    </form>
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
