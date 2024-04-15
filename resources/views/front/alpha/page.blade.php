@extends('front.layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>{{$page_title}}</h2>
                </div>
                <div class="col-md-3">
                    <div class="breadcrumbs-text-right">
                        <p class="text-lg-end">
                            <a href="{{route('home')}}">{{__('message.home')}}</a> > {{$page_title}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Ends -->

    <div class="news-page area-padding-3">
        <div class="container">
            <div class="row">
                <!-- Start single news -->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="single-page-description">
                                {!! $page_description !!}
                            </div>
                        </div>
                        <!-- End single news -->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
