@extends('candidate.beta.layouts.master')

@section('page-title'){{$page}}@endsection

@section('breadcrumb')
@include('candidate.beta.partials.breadcrumb')
@endsection

@section('content')

<div class="section-account-alpha-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="section-account-alpha-navigation">
                    @include('candidate.beta.partials.account-sidebar')
                </div>
            </div>
            <div class="col-md-9">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="quiz-detail-box">
                            <p class="quiz-detail-box-heading">
                                <strong>{{ $detail['quiz_title'] }} 
                                {{ $detail['job_title'] ? ' : '.$detail['job_title'] : ''; }}</strong>
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
