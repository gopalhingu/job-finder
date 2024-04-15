@extends('front'.viewPrfx().'layouts.master')

@section('breadcrumb')
@include('front'.viewPrfx().'partials.breadcrumb')
@endsection

@section('content')

<!-- Account Section Starts -->
<div class="section-account-alpha-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="section-account-alpha-navigation">
                    @include('front'.viewPrfx().'partials.account-sidebar')
                </div>
            </div>
            <div class="col-md-9">
                <div class="section-account-alpha-profile">
                    <!-- Account Password Form Starts -->
                    <form class="form" id="password_update_form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.old_password') }}</label>
                                    <input type="password" class="form-control shadow-none border-none" 
                                        placeholder="#@$SG2" name="old_password" value="">
                                    <small class="form-text text-muted">{{ __('message.enter_old_password') }}</small>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.new_password') }}</label>
                                    <input type="password" class="form-control shadow-none border-none" 
                                        placeholder="#@$SG2" name="new_password" value="">
                                    <small class="form-text text-muted">{{ __('message.enter_new_password') }}</small>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.retype_password') }}</label>
                                    <input type="password" class="form-control shadow-none border-none" 
                                        placeholder="#@$SG2" name="retype_password" value="">
                                    <small class="form-text text-muted">{{ __('message.enter_password_again') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 text-center">
                                <div class="form-group form-group-account">
                                    <button type="submit" class="btn btn-general" title="Save" id="password_update_form_button">
                                        <i class="fas fa-save"></i> {{ __('message.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Account Password Form Ends -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Account Section Ends -->

@endsection
