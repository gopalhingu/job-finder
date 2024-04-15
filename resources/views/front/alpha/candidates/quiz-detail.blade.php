@extends('front'.viewPrfx().'layouts.master')

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
                        @include('front'.viewPrfx().'partials.account-sidebar')
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
                                    <span class="account-box-heading-line"></span>
                                </p>
                                <p>
                                    <span class="quiz-attempt-info">
                                    <i class="fa-solid fa-list-ol"></i> {{ __('message.total') }} {{ $detail['total_questions'] }} 
                                    {{ __('message.questions') }}
                                    </span>
                                    <span class="quiz-attempt-info">
                                    <i class="fa-regular fa-clock"></i> {{ __('message.max_time') }} : 
                                    {{ $detail['allowed_time'] }} {{ __('message.minutes') }}
                                    </span>
                                </p>
                                <p class="quiz-attempt-description">
                                    {{ $quiz['description'] }}
                                </p>
                                <form action="{{url('/').'/account/quiz-attempt'}}" method="POST">
                                @csrf
                                <input type="hidden" name="quiz" value="{{ encode($detail['candidate_quiz_id']) }}">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <button type="submit" class="btn btn-cf-general" id="quiz_start_form_button">
                                                {{ __('message.start_quiz') }}
                                                </button>
                                                <br /><br />
                                                <small><strong>{{ __('message.note_once_started') }}</strong></small>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>                       
                </div>
            </div>
        </div>
    </div>

@endsection
