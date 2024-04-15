@extends('front.layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>{{__('message.forgot_password')}}</h2>
                </div>
                <div class="col-md-3">
                    <div class="breadcrumbs-text-right">
                        <p class="text-lg-end">
                            <a href="{{route('home')}}">{{__('message.home')}}</a> > <a href="{{route('home').'/register'}}">{{__('message.forgot_password')}}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Ends -->

    <div class="register">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="section-headline text-center register-page-heading"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">                   
                </div>
                <!-- Start  contact -->
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form register-form">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="nav-item">
                                <a href="#forgot-employer" class="nav-link active nav-link-employer" data-bs-toggle="tab">
                                    <h3>{{__('message.for_employer')}}</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#forgot-candidate" class="nav-link nav-link-candidate" data-bs-toggle="tab">
                                    <h3>{{__('message.for_candidate')}}</h3>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="forgot-employer">
                                <form action="" method="post" role="form" id="employer_forgot_form">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <input type="hidden" name="type" value="employer" />
                                                <input type="email" name="email" class="form-control" 
                                                placeholder="{{__('message.enter_email_to_receive')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" id="employer_forgot_form_button">
                                            {{__('message.submit')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="forgot-candidate">
                                <form action="" method="post" role="form" id="candidate_forgot_form">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <input type="hidden" name="type" value="candidate" />
                                                <input type="email" name="email" class="form-control" 
                                                placeholder="{{__('message.enter_email_to_receive')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" id="candidate_forgot_form_button">
                                            {{__('message.submit')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Left contact -->
                <div class="col-md-2 col-sm-2 col-xs-12">
                </div>

            </div>
        </div>
    </div>    

@endsection
