@extends('front.layouts.master')

@section('content')

    <!-- Start Breadcrumbs -->
    <div class="breadcrumbs-area">
        <div class="bready-inner bready-area-padding">
            <div class="container ">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <!-- Start breadcrumbs Start -->
                        <div class="breadcrumb-content">
                            <h3>{{__('message.signup')}}</h3>
                        </div>
                        <!-- End breadcrumbs end -->
                    </div>
                    <!-- End Right Feature -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
    <div class="news-page area-padding-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="section-headline text-center">
                        <h2>{{__('message.select_package_register')}}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                </div>
                <!-- Start  contact -->
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form contact-form">
                        <form action="" method="post" role="form" id="register_form">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" name="first_name" class="form-control" 
                                        placeholder="{{__('message.first_name')}}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" name="last_name" class="form-control" 
                                        placeholder="{{__('message.last_name')}}" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" name="company_name" class="form-control" 
                                        placeholder="{{__('message.company_name')}}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" 
                                        placeholder="{{__('message.email')}}" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" 
                                        placeholder="{{__('message.password')}}" >
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="password" name="retype_password" class="form-control" 
                                        placeholder="{{__('message.retype_password')}}" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <select class="form-control" name="package_id">
                                        @foreach($packages as $package)
                                            <option value="{{encode($package['package_id'])}}==m">
                                                {{$package['title'].' ('.$package['currency'].$package['monthly_price'].' '.__('message.monthly').')'}}
                                            </option>
                                            <option value="{{encode($package['package_id'])}}==y">
                                                {{$package['title'].' ('.$package['currency'].$package['yearly_price'].' '.__('message.yearly').')'}}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <div class="text-center">
                                        <button type="submit" id="register_form_button">
                                            {{__('message.pay_with_paypal')}}
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <div class="text-center">
                                        <button type="submit" id="register_form_button">
                                            {{__('message.pay_with_stripe')}}
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                </div>
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
