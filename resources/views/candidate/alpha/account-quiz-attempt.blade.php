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
            <h2><span>{{ __('message.account') }} > {{ __('message.quizes') }} > {{ __('message.progress') }}</span></h2>
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
                                    {{ __('message.question') }} {{ $detail['attempt'] }} of 
                                    {{ $detail['total_questions'] }}
                                    </span>
                                    <span class="quiz-attempt-info-timer">
                                    {{ __('message.time_remaining') }} : {{ $time['clock'] }}</span>
                                </p>
                                <p class="quiz-attempt-description">
                                    {!! textToImage($question['title'], candidateSession()) !!}
                                </p>
                                @if($question['image'])
                                <p>
                                    @php $thumb = questionThumb($question['image']); @endphp
                                    <img class="quiz-attempt-image" src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
                                </p>
                                @endif
                                <form action="{{empUrl().'account/quiz-attempt'}}" method="POST">
                                @csrf
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <input type="hidden" name="quiz" value="{{ encode($detail['candidate_quiz_id']) }}">
                                            <input type="hidden" name="question" value="{{ encode($detail['attempt']) }}">
                                            <ul class="quiz-attempt-list-container">
                                                @foreach ($question['answers'] as $key => $answer) 
                                                <li>
                                                    <input name="answer[]" type="{{ $question['type'] }}" 
                                                    class="minimal" value="{{ encode($answer['quiz_question_answer_id']) }}">
                                                    {{ $answer['title'] }}
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <button type="submit" class="btn btn-success">
                                                {{ __('message.submit_move_to_next') }}
                                                </button>
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