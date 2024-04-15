@extends('front.layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>{{__('message.quizes')}}</h2>
                </div>
                <div class="col-md-3">
                    <div class="breadcrumbs-text-right">
                        <p class="text-lg-end">
                            <a href="{{route('home')}}">{{__('message.home')}}</a> > 
                            <a href="{{route('front-profile')}}">{{__('message.account')}}</a> > 
                            <a href="{{route('front-acc-quizes')}}">{{__('message.quizes')}}</a>
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
                        @include('front.partials.account-sidebar')
                    </div>
                </div>
                <div class="col-md-9">                    
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="account-box">
                                <p class="account-box-heading">
                                    <span class="account-box-heading-text">
                                    {{ $quiz['title'] }}
                                    {{ $detail['job_title'] ? ' : '.$detail['job_title'] : ''; }}
                                    </span>
                                </p>
                                <p class="quiz-attempt-description">
                                    {{ __('message.quiz_completed') }} <br />
                                    {{ __('message.result') }} : <strong>{{ $detail['total_questions'] != 0 ? round(($detail['correct_answers']/$detail['total_questions'])*100).'%' : ''; }}</strong><br />
                                    <a href="{{ empUrl('/') }}account/quizes">{{ __('message.back_to_quizes') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>                       
                </div>
            </div>
        </div>
    </div>

@endsection
