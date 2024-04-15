@extends('front'.viewPrfx().'layouts.master')

@section('breadcrumb')
@include('front.beta.partials.breadcrumb')
@endsection

@section('content')
    
    <!-- Reset Password Form Section Starts -->
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-12 col-sm-12"></div>
            <div class="col-lg-8 col-md-12 col-sm-12">
                <div class="section-contact-alpha-form">
                    <form id="front_password_reset_form">
                        <div class="row">
                            @csrf
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label>{{__('message.new_password')}}</label>
                                <input type="hidden" name="type" value="{{ $type }}">
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="password" name="new_password" class="form-control shadow-none border-none" />
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label>{{__('message.retype_new_password')}}</label>
                                <input type="password" name="retype_new_password" class="form-control shadow-none border-none" />
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                <button class="btn" id="front_password_reset_form_button">
                                    <i class="fa-regular fa-paper-plane"></i> {{__('message.submit')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12"></div>
        </div>
    </div>
    <!-- Reset Password Form Section Ends -->

@endsection
