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

                <div class="row section-quiz-alpha-container">
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        <div class="row section-quiz-alpha-item">
                            <div class="col-md-12 box-title">
                                <h6><i class="fa-regular fa-circle-question"></i> {{__('message.question')}} : {{$detail['attempt']}}</h6>
                            </div>
                            <div class="col-md-12 section-quiz-alpha-activity-item">
                                <div class="section-quiz-alpha-item-q-images">
                                    {!! textToImage($question['title'], candidateSession()) !!}
                                </div>
                                @if(isset($question["image"]))
                                <p>
                                    @php $thumb = questionThumb($question['image']); @endphp
                                    <img class="quiz-attempt-image" src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
                                </p>
                                @endif                                
                                <form action="{{empUrl().'account/quiz-attempt'}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quiz" value="{{ encode($detail['candidate_quiz_id']) }}">
                                    <input type="hidden" name="question" value="{{ encode($detail['attempt']) }}">
                                    <div class="section-quiz-alpha-answers-container">
                                        @foreach ($question['answers'] as $key => $answer) 
                                        <span>
                                            <input name="answer[]" 
                                                type="{{ $question['type'] }}"  
                                                class="" 
                                                id="item_radio{{$key}}" 
                                                value="{{ encode($answer['quiz_question_answer_id']) }}"
                                            />
                                            <label for="item_radio{{$key}}">{{ $answer['title'] }}</label>
                                        </span>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="btn btn-general">{{ __('message.submit_move_to_next') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">

                        <!----- Quiz Timer container starts ------->
                        <div class="row section-quiz-alpha-item">
                            <div class="col-md-12 box-title">
                                <h6><i class="fa-solid fa-hourglass-end"></i> {{__('message.time_remaining')}}</h6>
                            </div>
                            <div class="col-md-12">
                                <div id="CDT" class="count-down"></div>
                            </div>
                        </div>
                        <!----- Quiz Timer container ends ------->

                        <!----- Quiz Question Numbers container starts ------->
                        <div class="row section-quiz-alpha-item">
                            <div class="col-md-12 box-title">
                                @php $progress = count($questions) != 0 ? round((($detail['attempt']-1)/count($questions))*100) : 0; @endphp
                                <h6><i class="fa-solid fa-person-running"></i> {{__('message.progress')}} ({{$progress}}%)</h6>
                            </div>
                            <div class="col-md-12 section-quiz-alpha-item-question-numbers">
                                @for($i=1; $i <= count($questions); $i++)
                                @php $active = $detail['attempt'] == $i ? 'active' : 'remaining'; @endphp
                                <span class="section-quiz-alpha-q-number {{$active}}">{{$i}}</span>
                                @endfor
                            </div>
                        </div>
                        <!----- Quiz Question Numbers container ends ------->

                        <!----- Quiz Description container starts ------->
                        <div class="row section-quiz-alpha-item">
                            <div class="col-md-12 box-title">
                                <h6><i class="fas fa-info-circle"></i> {{__('message.description')}}</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="section-quiz-alpha-item-description">
                                    <p>{{$quiz['description']}}</p>
                                </div>
                            </div>
                        </div>
                        <!----- Quiz Description container ends ------->

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<input type="hidden" id="quiz_attempt_page" value="1">
<input type="hidden" id="now" value="{{$time['now']}}">
<input type="hidden" id="max" value="{{$time['max']}}">
<input type="hidden" id="timesup" value="Timesup">

@endsection
