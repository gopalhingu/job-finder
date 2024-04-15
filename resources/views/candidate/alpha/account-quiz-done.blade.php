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
    </section>
    <!-- #account area section ends -->
</main>
@endsection