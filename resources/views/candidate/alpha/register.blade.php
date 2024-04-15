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
            <h2><span>{{ __('message.register') }}</span></h2>
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
                <div class="col-lg-2">
                </div>
                <div class="col-md-8 col-lg-8 col-sm-12">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="account-box">
                                <p class="account-box-heading">
                                    <span class="account-box-heading-text">{{ __('message.register') }}</span>
                                    <span class="account-box-heading-line"></span>
                                </p>
                                <div class="container">
                                    <form class="form" id="register_form">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.first_name') }}</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" name="first_name" class="form-control" 
                                                        placeholder="First Name">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                    <small id="" class="form-text text-muted">{{ __('message.enter_first_name') }}.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.last_name') }}</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" >
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                    <small id="" class="form-text text-muted">{{ __('message.enter_last_name') }}.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.gender') }}</label>
                                                    <div class="input-group mb-3">
                                                        <select name="gender" class="form-control">
                                                            <option value="male">{{ __('message.male') }}</option>
                                                            <option value="femal">{{ __('message.female') }}</option>
                                                            <option value="other">{{ __('message.other') }}</option>
                                                        </select>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                    <small id="" class="form-text text-muted">{{ __('message.select_gender') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.email') }}</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" name="email" class="form-control" placeholder="Email" >
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                                                        </div>
                                                    </div>
                                                    <small id="" class="form-text text-muted">{{ __('message.enter_email') }}.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.password') }}</label>
                                                    <div class="input-group mb-3">
                                                        <input type="password" name="password" class="form-control" placeholder="{{ __('message.password') }}" 
                                                            >
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-key"></i></span>
                                                        </div>
                                                    </div>
                                                    <small id="" class="form-text text-muted">{{ __('message.enter_password') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.retype_password') }}</label>
                                                    <div class="input-group mb-3">
                                                        <input type="password" name="retype_password" class="form-control" placeholder="Password" 
                                                            >
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-key"></i></span>
                                                        </div>
                                                    </div>
                                                    <small id="" class="form-text text-muted">{{ __('message.enter_password_again') }}.</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group form-group-account">
                                                    <button type="submit" class="btn btn-success" title="Save" id="register_form_button">
                                                    {{ __('message.register') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <a href="{{ empUrl() }}login">{{ __('message.back_to_login') }}</a><br />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                </div>
            </div>
        </div>
    </section>
    <!-- #account area section ends -->
</main>
@endsection