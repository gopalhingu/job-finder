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
                        @if ($quizes)
                        @foreach ($quizes as $q)
                        @php $d = objToArr(json_decode($q['quiz_data'])); @endphp
                        
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="quiz-item-box">
                                    <div class="dotmenu">
                                        <ul class="dotMenudropbtn dotmenuicons dotmenuShowLeft dotmenuShowLeft"
                                            data-id="{{ 'dot-'.$q['candidate_quiz_id'] }}">
                                            <li></li>
                                            <li></li>
                                            <li></li>
                                        </ul>
                                        <div id="{{ 'dot-'.$q['candidate_quiz_id'] }}" class="dotmenu-content">
                                            <a href="{{ url('/') }}/account/quiz/{{ encode($q['candidate_quiz_id']) }}">
                                                {{ __('message.attempt') }}
                                            </a>
                                        </div>
                                    </div>
                                    <p class="quiz-item-box-heading">
                                        {{ $d['quiz']['title'] }}{{ $q['job_title'] ? ' : '.$q['job_title'] : ''; }}
                                    </p>
                                    <p class="quiz-listing-quiz-description">
                                        {!! $d['quiz']['description'] !!}
                                    </p>
                                    <div class="container">
                                        <div class="row quiz-listing-items-container">
                                            <div class="col-md-4 col-sm-4 quiz-listing-items">
                                                <span class="quiz-detail-items-title">{{ __('message.allowed_time') }}</span>
                                                <span class="quiz-detail-items-value">{{ $q['allowed_time'] }} minutes</span>
                                            </div>
                                            <div class="col-md-4 col-sm-4 quiz-detail-items">
                                                <span class="quiz-detail-items-title">{{ __('message.questions') }}</span>
                                                <span class="quiz-detail-items-value">{{ $q['total_questions'] }}</span>
                                            </div>
                                            <div class="col-md-4 col-sm-4 quiz-detail-items">
                                                <span class="quiz-detail-items-title">{{ __('message.result') }}</span>
                                                <span class="quiz-detail-items-value">
                                                @if($q['attempt'] > 0)
                                                {{ $q['total_questions'] != 0 ? round(($q['correct_answers']/$q['total_questions'])*100).'%' : '';  }}
                                                @else
                                                {{ __('message.n_a') }}
                                                @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        @endforeach
                        @else
                        <div class="account-detail-right-1">
                        <div class="job-detail account-no-content-box">
                            {{ __('message.no_quizes_found') }}
                        </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            {!! $pagination !!}
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
    </div>

@endsection
