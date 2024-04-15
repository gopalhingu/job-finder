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
            <h2><span>{{ __('message.account') }} > {{ __('message.quizes') }}</span></h2>
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
                    @if ($quizes)
                    @foreach ($quizes as $q)
                    @php $d = objToArr(json_decode($q['quiz_data'])); @endphp
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="quiz-item-box">
                            <div class="dotmenu">
                                <ul class="dotMenudropbtn dotmenuicons dotmenuShowLeft" 
                                    onclick="showDotMenu('{{ 'dot-'.$q['candidate_quiz_id'] }}')">
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                </ul>
                                <div id="{{ 'dot-'.$q['candidate_quiz_id'] }}" class="dotmenu-content">
                                    <a href="{{ empUrl() }}account/quiz/{{ encode($q['candidate_quiz_id']) }}">
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
                                        <span class="job-detail-items-title">{{ __('message.allowed_time') }}</span>
                                        <span class="job-detail-items-value">{{ $q['allowed_time'] }} minutes</span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 job-detail-items">
                                        <span class="job-detail-items-title">{{ __('message.questions') }}</span>
                                        <span class="job-detail-items-value">{{ $q['total_questions'] }}</span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 job-detail-items">
                                        <span class="job-detail-items-title">{{ __('message.result') }}</span>
                                        <span class="job-detail-items-value">
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
                    <div class="job-detail account-no-content-box">
                        {{ __('message.no_quizes_found') }}
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
    </section>
    <!-- #account area section ends -->
</main>
@endsection