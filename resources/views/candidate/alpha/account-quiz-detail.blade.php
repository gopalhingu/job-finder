@extends('candidate.alpha.layouts.master')
@section('page-title'){{$page}}@endsection
@section('content')
<!--==========================
    Intro Section
============================-->
<section id="intro" class="clearfix front-intro-section">
    <div class="container">
        <div class="intro-img">
        </div>
        <div class="intro-info">
            <h2>
                <span>
                {{ __('message.account') }} > {{ $quiz['title'] }}
                {{ $detail['job_title'] ? ' : '.$detail['job_title'] : ''; }}
                </span>
            </h2>
        </div>
    </div>
</section>
<!-- #intro -->
<main id="main">
    <!--==========================
        Account Area Setion
    ============================-->
    <section id="about">
        <div class="container">
            <div class="row mt-10">
                <div class="col-lg-3">
                    <div class="account-area-left">
                        <ul>
                            @include('candidate.alpha.partials.account-sidebar')
                        </ul>
                    </div>
                </div>
                <div class="col-md-9 col-lg-9 col-sm-12">
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
                                <p class="quiz-attempt-info">
                                    <span class="quiz-attempt-info-question-counter">
                                    {{ __('message.total') }} {{ $detail['total_questions'] }} 
                                    {{ __('message.questions') }}
                                    </span>
                                    <span class="quiz-attempt-info-timer">
                                    {{ __('message.max_time') }} : 
                                    {{ $detail['allowed_time'] }} {{ __('message.minutes') }}
                                    </span>
                                </p>
                                <p class="quiz-attempt-description">
                                    {{ $quiz['description'] }}
                                </p>
                                <form action="{{empUrl().'account/quiz-attempt'}}" method="POST">
                                @csrf
                                <input type="hidden" name="quiz" value="{{ encode($detail['candidate_quiz_id']) }}">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <button type="submit" class="btn btn-success" title="Save" 
                                                id="quiz_start_form_button">
                                                {{ __('message.start_quiz') }}
                                                </button>
                                                <br /><br />
                                                <strong>{{ __('message.note_once_started') }}</strong>
                                            </div>
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
    </section>
    <!-- #account area section ends -->
</main>
@endsection