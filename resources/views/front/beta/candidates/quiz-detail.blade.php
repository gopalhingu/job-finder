@extends('front'.viewPrfx().'layouts.master')

@section('breadcrumb')
@include('front'.viewPrfx().'partials.breadcrumb')
@endsection

@section('content')

<!-- Account Section Starts -->
<div class="section-account-alpha-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="section-account-alpha-navigation">
                    @include('front'.viewPrfx().'partials.account-sidebar')
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
                            <p>
                                <i class="fa-solid fa-list-ol"></i> {{ __('message.total') }} {{ $detail['total_questions'] }} 
                                {{ __('message.questions') }}
                                <i class="fa-regular fa-clock"></i> {{ __('message.max_time') }} : 
                                {{ $detail['allowed_time'] }} {{ __('message.minutes') }}
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
                                            <button type="submit" class="btn btn-general" id="quiz_start_form_button">
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
<!-- Account Section Ends -->

@endsection
