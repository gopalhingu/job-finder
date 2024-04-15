@extends('front'.viewPrfx().'layouts.master')

@section('breadcrumb')
@include('front.beta.partials.breadcrumb')
@endsection

@section('content')
    
    <!-- Forgot Password Section Starts -->
    <div class="front-forgot-password-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="section-headline text-center register-page-hading">
                        <br /><br /><br />
                        <h2>{{__('message.account_activated')}}</h2>
                        <br /><br /><br /><br /><br />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Forgot Password Section Ends -->

@endsection
