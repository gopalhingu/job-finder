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
            <h2><span>{{ __('message.account') }} > {{ __('message.job_applications') }}</span></h2>
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
                    @if ($jobs)
                    @foreach ($jobs as $job)
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="job-listing">
                                <p class="job-listing-heading">
                                    <span class="job-listing-heading-text">
                                    <a href="{{ empUrlBySlug($job['employer_slug']) }}job/{{ $job['slug'] }}">
                                        {{ $job['title'] }}
                                    </a>
                                    </span>
                                    <span class="job-listing-heading-line"></span>
                                </p>
                                <p class="job-listing-job-info">
                                    <span class="job-listing-job-info-date"><i class="fa fa-clock-o"></i> {{ __('message.applied_on') }} : {{ timeFormat($job['applied_on']) }}</span>
                                    <span class="job-listing-job-info-date"><i class="fa fa-bookmark"></i> {{ $job['department'] }}</span>
                                    <span class="job-listing-job-info-item refer-job" title="Refer this job"
                                        data-id="{{ encode($job['job_id']) }}">
                                    <i class="fa fa-user-plus"></i></span>
                                </p>
                                <div class="job-listing-job-description">
                                    {!! trimString($job['description'], 280) !!}
                                    <a href="{{ empUrlBySlug($job['employer_slug']) }}job/{{ $job['slug'] }}">
                                        {{ __('message.read_more') }}
                                    </a>
                                </div>
                                <div class="container">
                                    <div class="row job-listing-items-container">
                                        @if ($job['fields'])
                                        @foreach ($job['fields'] as $key => $value)
                                        @if ($value['label'])
                                        <div class="col-md-4 col-sm-6 job-listing-items">
                                            <span class="job-listing-items-title" title="{{ $value['label'] }}">
                                            {{ trimString($value['label']) }}
                                            </span>
                                            <span class="job-listing-items-value" title="{{ $value['value'] }}">
                                            {{ trimString($value['value']) }}
                                            </span>
                                        </div>
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="container">
                                    <div class="row job-application-progress">
                                        <div class="col-xs-3 job-application-progress-step {{ jobStatus($job['job_status'], 1) }}">
                                            <div class="text-center job-application-progress-stepnum">{{ __('message.applied') }}</div>
                                            <div class="progress">
                                                <div class="progress-bar"></div>
                                            </div>
                                            <a href="#" class="job-application-progress-dot"></a>
                                        </div>
                                        <div class="col-xs-3 job-application-progress-step {{ jobStatus($job['job_status'], 2) }}">
                                            <div class="text-center job-application-progress-stepnum">{{ __('message.shortlisted') }}</div>
                                            <div class="progress">
                                                <div class="progress-bar"></div>
                                            </div>
                                            <a href="#" class="job-application-progress-dot"></a>
                                            <div class="job-application-progress-info text-center"></div>
                                        </div>
                                        <div class="col-xs-3 job-application-progress-step {{ jobStatus($job['job_status'], 3) }}">
                                            <div class="text-center job-application-progress-stepnum">{{ __('message.interviewed') }}</div>
                                            <div class="progress">
                                                <div class="progress-bar"></div>
                                            </div>
                                            <a href="#" class="job-application-progress-dot"></a>
                                            <div class="job-application-progress-info text-center"></div>
                                        </div>
                                        <div class="col-xs-3 job-application-progress-step {{ jobStatus($job['job_status'], 4) }}">
                                            <div class="text-center job-application-progress-stepnum">{{ __('message.hired') }}</div>
                                            <div class="progress">
                                                <div class="progress-bar"></div>
                                            </div>
                                            <a href="#" class="job-application-progress-dot"></a>
                                            <div class="job-application-progress-info text-center"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="job-detail account-no-content-box">
                                {{ __('message.no_jobs_found') }}
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            {!! $pagination !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- #account area section ends -->
</main>
@endsection