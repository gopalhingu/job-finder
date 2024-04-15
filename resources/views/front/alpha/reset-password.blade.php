@extends('front.layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>{{__('message.reset_password')}}</h2>
                </div>
                <div class="col-md-3">
                    <div class="breadcrumbs-text-right">
                        <p class="text-lg-end">
                            <a href="{{route('home')}}">{{__('message.home')}}</a> > <a href="{{route('home').'/register'}}">{{__('message.reset_password')}}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Ends -->

    <div class="front-forgot-password-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="section-headline text-center register-page-hading">
                        <h2>{{__('message.create_new_password')}}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                </div>
                <!-- Start  contact -->
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form forgot-form">
                        <form action="" method="post" role="form" id="front_password_reset_form">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <input type="hidden" name="type" value="{{ $type }}">
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <input type="password" name="new_password" class="form-control" 
                                        placeholder="{{__('message.new_password')}}">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <input type="password" name="retype_new_password" class="form-control" 
                                        placeholder="{{__('message.retype_new_password')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" id="front_password_reset_form_button">{{__('message.submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End Left contact -->
                <div class="col-md-2 col-sm-2 col-xs-12">
                </div>

            </div>
        </div>
    </div>  

@endsection
