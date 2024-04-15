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
                                    <span class="quiz-attempt-info quiz-attempt-info-timer">
                                    <i class="fa-regular fa-clock"></i> {{ __('message.time_remaining') }} : {{ $time['clock'] }}
                                    </span>
                                    <span class="quiz-attempt-info">
                                    <strong><i class="fa-solid fa-list-ol"></i></strong> {{ __('message.question') }} {{ $detail['attempt'] }} of 
                                    {{ $detail['total_questions'] }}
                                    </span>
                                </p>
                                <p class="quiz-attempt-description">
                                    {!! textToImage($question['title'], candidateSession()) !!}
                                </p>
                                @if(isset($question["image"]))
                                <p>
                                    @php $thumb = questionThumb($question['image']); @endphp
                                    <img class="quiz-attempt-image" src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
                                </p>
                                @endif
                                <form action="{{url('/').'/account/quiz-attempt'}}" method="POST">
                                @csrf
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
                                                <button type="submit" class="btn btn-cf-general">
                                                {{ __('message.submit_move_to_next') }}
                                                </button>
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
