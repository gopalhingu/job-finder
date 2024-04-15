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
                    <form id="reset_form">
                    <div class="row">
                        @csrf
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <label>{{__('message.new_password')}}</label>
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="password" name="password" class="form-control shadow-none border-none" />
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <label>{{__('message.retype_new_password')}}</label>
                            <input type="password" name="retype_password" class="form-control shadow-none border-none" />
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                            <button type="submit" class="btn btn-success" id="reset_form_button">{{ __('message.reset') }}</button>
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
