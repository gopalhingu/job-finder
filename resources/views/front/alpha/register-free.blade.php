@extends('front.layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>{{__('message.register')}}</h2>
                </div>
                <div class="col-md-3">
                    <div class="breadcrumbs-text-right">
                        <p class="text-lg-end">
                            <a href="{{route('home')}}">{{__('message.home')}}</a> > <a href="{{route('home').'/register'}}">{{__('message.register')}}</a>
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
                            @php
                                if (setting('enable_employer_registeration') == 'no') {
                                    $active = 'active';
                                    $active2 = 'show active';
                                } else {
                                    $active = '';
                                    $active2 = '';
                                }
                            @endphp
                            @if(setting('enable_employer_registeration') == 'yes')
                            <li class="nav-item">
                                <a href="#register-employer" class="nav-link active nav-link-employer" data-bs-toggle="tab">
                                    <h3>{{__('message.sign_up_as_employer')}}</h3>
                                </a>
                            </li>
                            @endif
                            @if(setting('enable_candidate_registeration') == 'yes')
                            <li class="nav-item">
                                <a href="#register-candidate" class="nav-link {{$active}} nav-link-candidate" data-bs-toggle="tab">
                                    <h3>{{__('message.sign_up_as_candidate')}}</h3>
                                </a>
                            </li>
                            @endif
                        </ul>
                        <div class="tab-content">
                            @if(setting('enable_employer_registeration') == 'yes')
                            <div class="tab-pane fade show active" id="register-employer">
                                <form action="" method="post" role="form" id="employer_register_form">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" name="first_name" class="form-control" placeholder="{{__('message.first_name')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" name="last_name" class="form-control" placeholder="{{__('message.last_name')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" name="company" class="form-control" placeholder="{{__('message.company_name')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="email" name="email" class="form-control" placeholder="{{__('message.email')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="password" name="password" class="form-control" placeholder="{{__('message.password')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="password" name="retype_password" class="form-control" placeholder="{{__('message.retype_password')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" id="employer_register_form_button">{{__('message.register')}}</button>
                                    </div>
                                </form>
                            </div>
                            @endif
                            @if(setting('enable_candidate_registeration') == 'yes')
                            <div class="tab-pane fade {{$active2}}" id="register-candidate">
                                <form action="" method="post" role="form" id="candidate_register_form">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" name="first_name" class="form-control" placeholder="{{__('message.first_name')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" name="last_name" class="form-control" placeholder="{{__('message.last_name')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <input type="email" name="email" class="form-control" placeholder="{{__('message.email')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="password" name="password" class="form-control" placeholder="{{__('message.password')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="password" name="retype_password" class="form-control" placeholder="{{__('message.retype_password')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" id="candidate_register_form_button">{{__('message.register')}}</button>
                                    </div>
                                </form>
                            </div>
                            @endif
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
