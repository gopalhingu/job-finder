@extends('front'.viewPrfx().'layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>{{__('message.update').' '.__('message.password')}}</h2>
                </div>
                <div class="col-md-3">
                    <div class="breadcrumbs-text-right">
                        <p class="text-lg-end">
                            <a href="{{route('home')}}">{{__('message.home')}}</a> > 
                            <a href="{{route('front-profile')}}">{{__('message.account')}}</a> > 
                            <a href="{{route('front-password')}}">{{__('message.password')}}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Ends -->

    <div class="account-detail-container">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="account-detail-left-1">
                        @include('front'.viewPrfx().'partials.account-sidebar')
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="account-detail-right-1">
                        <form class="form" id="password_update_form">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.old_password') }}</label>
                                        <input type="password" name="old_password" class="form-control" placeholder="#@$SG2">
                                        <small class="form-text text-muted">{{ __('message.enter_old_password') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.new_password') }}</label>
                                        <input type="password" name="new_password" class="form-control" placeholder="#@$SG2">
                                        <small class="form-text text-muted">{{ __('message.enter_new_password') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.retype_password') }}</label>
                                        <input type="password" name="retype_password" class="form-control" placeholder="adsfadsf">
                                        <small class="form-text text-muted">{{ __('message.enter_password_again') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group form-group-account">
                                        <button type="submit" class="btn btn-cf-general" title="Save" id="password_update_form_button">
                                        <i class="fas fa-save"></i> {{ __('message.save') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
